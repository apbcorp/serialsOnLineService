<?php

namespace App\Dictionary;

class VideoResolutionDictionary
{
    const RESOLUTIONS = [
        '1280x720' => self::RESOLUTION_720,
        '854x480'  => self::RESOLUTION_480,
        '640x360'  => self::RESOLUTION_360,
    ];

    const RESOLUTION_720 = 720;
    const RESOLUTION_480 = 480;
    const RESOLUTION_360 = 360;
}