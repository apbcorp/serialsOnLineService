<?php

namespace App\Formatter;

interface FormatterInterface
{
    /**
     * @param mixed $data
     *
     * @return array
     */
    public function format($data): array;
}