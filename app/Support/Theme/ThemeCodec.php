<?php

namespace App\Support\Theme;

/**
 * Packs a theme into a short, URL-safe string for `?theme=` share links:
 * base64url(gzdeflate(json)), the same idea as DaisyUI's theme generator.
 */
final class ThemeCodec
{
    /** Hard cap on the encoded input, well above any real theme. */
    private const MAX_LENGTH = 2048;

    public static function encode(ThemeTokens $tokens): string
    {
        $json = json_encode($tokens->toArray(), JSON_THROW_ON_ERROR | JSON_PRESERVE_ZERO_FRACTION);

        return rtrim(strtr(base64_encode((string) gzdeflate($json, 9)), '+/', '-_'), '=');
    }

    public static function decode(?string $code): ?ThemeTokens
    {
        if ($code === null || $code === '' || strlen($code) > self::MAX_LENGTH) {
            return null;
        }

        if (! preg_match('/^[A-Za-z0-9_-]+$/', $code)) {
            return null;
        }

        $binary = base64_decode(strtr($code, '-_', '+/'), true);

        if ($binary === false) {
            return null;
        }

        // gzinflate's limit stops a crafted payload from inflating into a memory bomb.
        $json = @gzinflate($binary, 16384);

        if ($json === false) {
            return null;
        }

        $data = json_decode($json, true);

        return is_array($data) ? ThemeTokens::tryFrom($data) : null;
    }
}
