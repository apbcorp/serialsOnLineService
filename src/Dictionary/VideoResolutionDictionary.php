<?php

namespace App\Dictionary;

class VideoResolutionDictionary
{
    const RESOLUTIONS = [
        '1280x720' => self::RESOLUTION_720,
        '1280x640' => self::RESOLUTION_640,
        '854x480'  => self::RESOLUTION_480,
        '640x360'  => self::RESOLUTION_360,
        '854x426'  => self::RESOLUTION_426,
        '640x320'  => self::RESOLUTION_320
    ];

    const RESOLUTION_720 = 720;
    const RESOLUTION_640 = 640;
    const RESOLUTION_480 = 480;
    const RESOLUTION_426 = 426;
    const RESOLUTION_360 = 360;
    const RESOLUTION_320 = 320;
}