<?php

namespace Tests\Unit;

use App\Rules\ReachablePublicHttpsUrl;
use RuntimeException;
use Tests\TestCase;

class ReachablePublicHttpsUrlTest extends TestCase
{
    /** @test */
    public function it_accepts_a_public_host_that_responds_over_https(): void
    {
        $rule = new ReachablePublicHttpsUrl(
            fn ($host) => ['93.184.216.34'],
            fn ($url, $ip) => ['status' => 200, 'location' => null]
        );

        $this->assertTrue($rule->passes('website_address', 'https://example.com'));
    }

    /** @test */
    public function it_rejects_hosts_that_resolve_to_private_or_reserved_addresses(): void
    {
        $requestCalled = false;
        $rule = new ReachablePublicHttpsUrl(
            fn ($host) => ['127.0.0.1'],
            function () use (&$requestCalled) {
                $requestCalled = true;
                return ['status' => 200, 'location' => null];
            }
        );

        $this->assertFalse($rule->passes('website_address', 'https://example.com'));
        $this->assertFalse($requestCalled);
    }

    /** @test */
    public function it_rejects_redirects_to_private_hosts(): void
    {
        $rule = new ReachablePublicHttpsUrl(
            fn ($host) => $host === 'example.com' ? ['93.184.216.34'] : ['10.0.0.1'],
            fn ($url, $ip) => ['status' => 302, 'location' => 'https://internal.example.net/admin']
        );

        $this->assertFalse($rule->passes('website_address', 'https://example.com'));
    }

    /** @test */
    public function it_turns_transport_failures_into_validation_failures(): void
    {
        $rule = new ReachablePublicHttpsUrl(
            fn ($host) => ['93.184.216.34'],
            function () {
                throw new RuntimeException('timeout');
            }
        );

        $this->assertFalse($rule->passes('website_address', 'https://example.com'));
    }

    /** @test */
    public function it_tries_each_public_dns_address_until_one_responds(): void
    {
        $attempts = [];
        $rule = new ReachablePublicHttpsUrl(
            fn ($host) => ['93.184.216.34', '8.8.8.8'],
            function ($url, $ip) use (&$attempts) {
                $attempts[] = $ip;
                if ($ip === '93.184.216.34') {
                    throw new RuntimeException('unreachable');
                }

                return ['status' => 200, 'location' => null];
            }
        );

        $this->assertTrue($rule->passes('website_address', 'https://example.com'));
        $this->assertSame(['93.184.216.34', '8.8.8.8'], $attempts);
    }

    /**
     * @test
     * @dataProvider nonGlobalAddressProvider
     */
    public function it_rejects_non_global_unicast_addresses(string $address): void
    {
        $rule = new ReachablePublicHttpsUrl(
            fn ($host) => [$address],
            fn ($url, $ip) => ['status' => 200, 'location' => null]
        );

        $this->assertFalse($rule->passes('website_address', 'https://example.com'));
    }

    public function nonGlobalAddressProvider(): array
    {
        return [
            'carrier grade NAT' => ['100.64.0.1'],
            'multicast' => ['224.0.0.1'],
            'link local' => ['169.254.1.1'],
        ];
    }
}
