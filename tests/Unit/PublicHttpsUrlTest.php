<?php

namespace Tests\Unit;

use App\Rules\PublicHttpsUrl;
use PHPUnit\Framework\TestCase;

class PublicHttpsUrlTest extends TestCase
{
    /** @test */
    public function it_normalizes_public_hosts_to_https(): void
    {
        $this->assertSame('https://example.com', PublicHttpsUrl::normalize(' example.com '));
        $this->assertSame(
            'https://example.com/path?q=1#section',
            PublicHttpsUrl::normalize('https://example.com/path?q=1#section')
        );
    }

    /**
     * @test
     * @dataProvider invalidUrlProvider
     */
    public function it_rejects_non_public_or_unsafe_urls(string $value): void
    {
        $rule = new PublicHttpsUrl();

        $this->assertFalse($rule->passes('website_address', $value));
    }

    public function invalidUrlProvider(): array
    {
        return [
            'http' => ['http://example.com'],
            'ftp' => ['ftp://example.com'],
            'localhost' => ['https://localhost'],
            'single label' => ['internal'],
            'ipv4' => ['https://127.0.0.1'],
            'ipv6' => ['https://[::1]'],
            'credentials' => ['https://user:pass@example.com'],
            'embedded whitespace' => ['https://exa mple.com'],
            'malformed port' => ['https://example.com:99999'],
            'unicode host' => ['https://例子.测试'],
        ];
    }

    /** @test */
    public function public_form_applies_the_public_domain_rule_before_submission(): void
    {
        $form = file_get_contents(dirname(__DIR__, 2).'/resources/views/cs_vendor/form/master_information.blade.php');
        $partnerScript = file_get_contents(dirname(__DIR__, 2).'/resources/views/cs_vendor/partner_js.blade.php');
        $wizardScript = file_get_contents(dirname(__DIR__, 2).'/resources/views/public/form/js.blade.php');

        $this->assertStringContainsString('data-public-website', $form);
        $this->assertStringContainsString('function sanitizeWebsiteHost', $partnerScript);
        $this->assertStringContainsString("on('input', '[data-public-website]'", $partnerScript);
        $this->assertStringContainsString("on('blur', '[data-public-website]'", $partnerScript);
        $this->assertStringContainsString('$pane.find(\'[data-public-website]\')', $wizardScript);
        $this->assertStringContainsString('isValidWebsiteHost(value)', $wizardScript);
        $this->assertStringContainsString('HTTPS dan domain publik yang valid', $wizardScript);
    }
}
