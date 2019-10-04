<?php

namespace App\Helper;


class UrlHelper
{
    public static function getDomainName(string $url): string
    {
        $domain = self::getDomain($url);

        $parts = explode('.', $domain);

        return count($parts) == 1 ? $parts[0] : $parts[count($parts) - 2];
    }

    public static function getDomain(string $url): string
    {
        $parts = explode('/', $url);

        return isset($parts[2]) ? $parts[2] : '';
    }
}