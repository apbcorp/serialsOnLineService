<?php

namespace App\Structs\M3U8;

class M3U8Struct
{
    const RESOLUTIONS = [
        '1280x720' => self::RESOLUTION_720,
        '854x480'  => self::RESOLUTION_480,
        '640x360'  => self::RESOLUTION_360,
    ];

    const RESOLUTION_720 = 720;
    const RESOLUTION_480 = 480;
    const RESOLUTION_360 = 360;

    /**
     * @var string
     */
    private $url = '';

    /**
     * @var int
     */
    private $resolution = 0;

    /**
     * @var int
     */
    private $bandwidth = 0;

    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setResolution(int $resolution)
    {
        $this->resolution = $resolution;
    }

    public function setResolutionWithValidation(string $resolution): bool
    {
        if (!isset(self::RESOLUTIONS[$resolution])) {
            return false;
        }

        $this->resolution = self::RESOLUTIONS[$resolution];

        return true;
    }

    public function getResolution(): int
    {
        return $this->resolution;
    }

    public function setBandwidth(int $bandwidth)
    {
        $this->bandwidth = $bandwidth;
    }

    public function getBandwidth(): int
    {
        return $this->bandwidth;
    }

    public function isEmpty(): bool
    {
        return !$this->url;
    }
}