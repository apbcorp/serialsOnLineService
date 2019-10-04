<?php

namespace App\Helper;

use App\Structs\M3U8\M3U8Struct;

class M3U8Helper
{
    const TAG_STREAM_INFO = '#EXT-X-STREAM-INF';

    /**
     * @param string $code
     *
     * @return M3U8Struct[]
     */
    public static function parse(string $url, string $code, string $provider): array
    {
        $rows = explode("\n", $code);

        $result = [];
        for ($i = 0; $i < count($rows); $i++) {
            $struct = new M3U8Struct();

            $row = $rows[$i];
            if (strpos($row, '#') !== 0) {
                continue;
            }

            $parts = explode(':', $row);
            switch ($parts[0]) {
                case self::TAG_STREAM_INFO:
                    self::parseSteamInfo($struct, $url, $row, $rows[$i + 1]);
                    $i++;

                    break;
            }
            $struct->setProvider($provider);

            if (!$struct->isEmpty()) {
                $result[] = $struct;
            }
        }

        return $result;
    }

    public static function parseSteamInfo(M3U8Struct $struct, string $m3u8Url, string $info, string $url)
    {
        $parts = explode(':', $info);
        $paramsParts = explode(',', end($parts));

        foreach ($paramsParts as $paramsPart) {
            self::parseParam($struct, $paramsPart);
        }

        $struct->setUrl(self::buildLink($m3u8Url, $url));
    }

    public static function parseParam(M3U8Struct $struct, string $paramString)
    {
        if (preg_match('/RESOLUTION=(.*)/i', $paramString, $matches)) {
            $struct->setResolutionWithValidation($matches[1]);

            return;
        }

        if (preg_match('/BANDWIDTH=(.*)/i', $paramString, $matches)) {
            $struct->setBandwidth((int) $matches[1]);

            return;
        }
    }

    public static function buildLink(string $m3u8Url, string $url): string
    {
        if (strpos($url, '.') === 0) {
            $url = substr($url, 1, strlen($url));
            $parts = explode('/', $m3u8Url);
            unset($parts[count($parts) - 1]);
            $m3u8Url = implode('/', $parts);

            return $m3u8Url . $url;
        }

        return '';
    }
}