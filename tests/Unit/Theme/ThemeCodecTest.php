<?php

namespace Tests\Unit\Theme;

use App\Support\Theme\Presets;
use App\Support\Theme\ThemeCodec;
use App\Support\Theme\ThemeTokens;
use Tests\TestCase;

class ThemeCodecTest extends TestCase
{
    public function test_theme_code_encodes_and_decodes_cleanly(): void
    {
        $original = ThemeTokens::fromArray(Presets::tokens('cupcake'));
        $code = ThemeCodec::encode($original);

        $this->assertNotEmpty($code);
        $this->assertMatchesRegularExpression('/^[A-Za-z0-9_-]+$/', $code);

        $decoded = ThemeCodec::decode($code);
        $this->assertNotNull($decoded);
        $this->assertSame($original->toArray(), $decoded->toArray());
    }

    public function test_decode_returns_null_on_garbage_or_corrupted_input(): void
    {
        $this->assertNull(ThemeCodec::decode(null));
        $this->assertNull(ThemeCodec::decode(''));
        $this->assertNull(ThemeCodec::decode('not-a-valid-base64-payload!@#$'));
        $this->assertNull(ThemeCodec::decode('YWJjZGVmZ2hpams=')); // valid base64, not gzipped
    }

    public function test_decode_safeguards_against_oversized_payload(): void
    {
        $huge = str_repeat('a', 3000);
        $this->assertNull(ThemeCodec::decode($huge));
    }

    public function test_decode_rejects_payloads_with_invalid_css_injection(): void
    {
        // Crafted json with injection attempt
        $badData = ThemeTokens::defaults();
        $badData['accent']['light'] = '#ffffff; body { display:none }';

        $compressed = rtrim(strtr(base64_encode((string) gzdeflate((string) json_encode($badData))), '+/', '-_'), '=');
        $this->assertNull(ThemeCodec::decode($compressed));
    }
}
