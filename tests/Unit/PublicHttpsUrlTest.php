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
}
