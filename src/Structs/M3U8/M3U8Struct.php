<?php

namespace App\Structs\M3U8;

use App\Dictionary\VideoResolutionDictionary;

class M3U8Struct
{
    /**
     * @var string
     */
    private $url = '';

    /**
     * @var string
     */
    private $provider = '';

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
        if (!isset(VideoResolutionDictionary::RESOLUTIONS[$resolution])) {
            return false;
        }

        $this->resolution = VideoResolutionDictionary::RESOLUTIONS[$resolution];

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

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function setProvider(string $provider)
    {
        $this->provider = $provider;
    }
}