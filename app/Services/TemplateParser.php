<?php
namespace App\Services;

class TemplateParser
{
    public static function parse(string $content, array $context): string
    {
        return preg_replace_callback(
            '/\{\$(\w+)(?:\|([^}]+))?\}/',
            function ($matches) use ($context) {
                $key     = $matches[1];
                $default = $matches[2] ?? '-';

                return array_key_exists($key, $context)
                    ? (string) $context[$key]
                    : $default;
            },
            $content
        );
    }

    public static function resolveValues(array $context, array $keys): array
    {
        $result = [];
        foreach ($keys as $templateKey => $contextKey) {
            if (is_int($templateKey)) {
                $result[$contextKey] = $context[$contextKey] ?? '-';
            } else {
                $result[$templateKey] = $context[$contextKey] ?? '-';
            }
        }
        return $result;
    }

    public static function parseHtml(string $html, array $context): string
    {
        return preg_replace_callback(
            '/\{\$(\w+)(?:\|([^}]+))?\}/',
            function ($matches) use ($context) {
                $key     = $matches[1];
                $default = $matches[2] ?? '-';

                return array_key_exists($key, $context)
                    ? e((string) $context[$key])
                    : e($default);
            },
            $html
        );
    }
}
