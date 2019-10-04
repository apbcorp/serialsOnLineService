<?php

namespace App\Structs\Serial;


class VideoParsingResultStruct
{
    /**
     * @var string
     */
    private $source = '';

    /**
     * @var VideoParsingItemStruct[]
     */
    private $items = [];

    /**
     * VideoParsingResultStruct constructor.
     *
     * @param string $source
     */
    public function __construct(string $source)
    {
        $this->source = $source;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function addItem(VideoParsingItemStruct $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * @return VideoParsingItemStruct[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getItemCount(): int
    {
        return count($this->items);
    }

    public function getLastItem(): ?VideoParsingItemStruct
    {
        if (!$this->items) {
            return null;
        }

        return $this->items[count($this->items) - 1];
    }
}