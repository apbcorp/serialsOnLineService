<?php

namespace App\Structs\Serial;

use App\Structs\M3U8\M3U8Struct;

class PlaylistStruct
{
    /**
     * @var M3U8Struct[]
     */
    private $m3u8Items = [];

    public function addM3U8Item(M3U8Struct $struct)
    {
        $this->m3u8Items[] = $struct;
    }

    public function setM3U8Items(array $items)
    {
        $this->m3u8Items = $items;
    }

    /**
     * @return M3U8Struct[]
     */
    public function getM3U8Items(): array
    {
        return $this->m3u8Items;
    }
}