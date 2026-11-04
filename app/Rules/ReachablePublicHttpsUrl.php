<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;
use Throwable;

class ReachablePublicHttpsUrl implements Rule
{
    private $resolver;
    private $requester;

    public function __construct(callable $resolver = null, callable $requester = null)
    {
        $this->resolver = $resolver ?: [$this, 'resolveHost'];
        $this->requester = $requester ?: [$this, 'requestUrl'];
    }

    public function passes($attribute, $value): bool
    {
        $url = PublicHttpsUrl::normalize($value);

        if (!(new PublicHttpsUrl())->passes($attribute, $url)) {
            return false;
        }

        $visited = [];

        try {
            for ($redirects = 0; $redirects <= 3; $redirects++) {
                if (isset($visited[$url])) {
                    return false;
                }
                $visited[$url] = true;

                $host = parse_url($url, PHP_URL_HOST);
                $addresses = array_values(array_unique((array) call_user_func($this->resolver, $host)));

                if ($addresses === [] || !$this->containsOnlyPublicAddresses($addresses)) {
                    return false;
                }

                $redirectUrl = null;
                foreach ($addresses as $address) {
                    try {
                        $result = call_user_func($this->requester, $url, $address);
                    } catch (Throwable $exception) {
                        continue;
                    }

                    $status = (int) ($result['status'] ?? 0);
                    if (($status >= 200 && $status < 300) || ($status >= 400 && $status < 500)) {
                        return true;
                    }

                    if ($status >= 300 && $status < 400 && !empty($result['location'])) {
                        $redirectUrl = $this->resolveRedirectUrl($url, $result['location']);
                        break;
                    }
                }

                if ($redirectUrl === null || $redirects === 3) {
                    return false;
                }

                $url = $redirectUrl;
                if (!(new PublicHttpsUrl())->passes($attribute, $url)) {
                    return false;
                }
            }
        } catch (Throwable $exception) {
            return false;
        }

        return false;
    }

    public function message(): string
    {
        return 'Situs web tidak dapat diakses melalui HTTPS. Periksa kembali alamatnya. | The website could not be reached over HTTPS. Please check the address.';
    }

    public function resolveHost(string $host): array
    {
        $records = $this->dnsRecords($host);
        $addresses = [];

        foreach ($records ?: [] as $record) {
            if (!empty($record['ip'])) {
                $addresses[] = $record['ip'];
            }
            if (!empty($record['ipv6'])) {
                $addresses[] = $record['ipv6'];
            }
        }

        if ($addresses === []) {
            $addresses = (array) $this->systemIpv4Addresses($host);
        }

        return array_values(array_unique(array_filter($addresses, 'is_string')));
    }

    protected function dnsRecords(string $host)
    {
        return @dns_get_record($host, DNS_A | DNS_AAAA);
    }

    protected function systemIpv4Addresses(string $host)
    {
        return @gethostbynamel($host) ?: [];
    }

    public function requestUrl(string $url, string $ip): array
    {
        if (!defined('CURLOPT_RESOLVE')) {
            throw new \RuntimeException('cURL DNS pinning is unavailable.');
        }

        $host = parse_url($url, PHP_URL_HOST);
        $port = parse_url($url, PHP_URL_PORT) ?: 443;
        $pinnedIp = strpos($ip, ':') !== false ? '['.$ip.']' : $ip;
        $response = Http::withOptions([
            'allow_redirects' => false,
            'connect_timeout' => 2,
            'timeout' => 4,
            'curl' => [CURLOPT_RESOLVE => ["{$host}:{$port}:{$pinnedIp}"]],
        ])->withHeaders([
            'User-Agent' => 'PartnerManagement-WebsiteValidator/1.0',
        ])->head($url);

        return [
            'status' => $response->status(),
            'location' => $response->header('Location'),
        ];
    }

    private function containsOnlyPublicAddresses(array $addresses): bool
    {
        foreach ($addresses as $address) {
            if (filter_var(
                $address,
                FILTER_VALIDATE_IP,
                FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
            ) === false) {
                return false;
            }

            if (filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)
                && !$this->isGlobalUnicastIpv4($address)) {
                return false;
            }

            if (filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)
                && preg_match('/^(?:ff|::(?:ffff:)?0*(?:\.|$))/i', $address)) {
                return false;
            }
        }

        return true;
    }

    private function isGlobalUnicastIpv4(string $address): bool
    {
        $blockedCidrs = [
            '0.0.0.0/8',
            '100.64.0.0/10',
            '127.0.0.0/8',
            '169.254.0.0/16',
            '192.0.0.0/24',
            '192.0.2.0/24',
            '198.18.0.0/15',
            '198.51.100.0/24',
            '203.0.113.0/24',
            '224.0.0.0/4',
            '240.0.0.0/4',
        ];

        $ip = (int) sprintf('%u', ip2long($address));
        foreach ($blockedCidrs as $cidr) {
            [$network, $prefix] = explode('/', $cidr);
            $mask = $prefix === '0' ? 0 : (-1 << (32 - (int) $prefix));
            if (($ip & $mask) === ((int) sprintf('%u', ip2long($network)) & $mask)) {
                return false;
            }
        }

        return true;
    }

    private function resolveRedirectUrl(string $baseUrl, string $location): string
    {
        if (preg_match('#^https?://#i', $location)) {
            return $location;
        }

        if (strpos($location, '//') === 0) {
            return 'https:'.$location;
        }

        $parts = parse_url($baseUrl);
        $origin = 'https://'.$parts['host'].(isset($parts['port']) ? ':'.$parts['port'] : '');

        if (strpos($location, '/') === 0) {
            return $origin.$location;
        }

        $path = $parts['path'] ?? '/';
        $directory = rtrim(str_replace('\\', '/', dirname($path)), '/');

        return $origin.($directory ? $directory.'/' : '/').$location;
    }
}
