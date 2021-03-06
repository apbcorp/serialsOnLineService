<?php

namespace App\Structs\Serial;


class VideoParsingItemStruct
{
    /**
     * @var string
     */
    private $title = '';

    /**
     * @var string
     */
    private $screen = '';

    /**
     * @var string
     */
    private $pageLink = '';

    /**
     * @var string[]
     */
    private $playlistLinks = [];

    /** @var PlaylistStruct[] */
    private $playlists = [];

    /**
     * @var int
     */
    private $season = 0;

    /**
     * @var int
     */
    private $episode = 0;

    /**
     * @var int[]
     */
    private $ratings = [];

    /**
     * @var \DateTime|null
     */
    private $releaseDate;

    /**
     * @var bool
     */
    private $isEmpty = true;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function getScreen(): string
    {
        return $this->screen;
    }

    public function setScreen(string $screen)
    {
        $this->screen = $screen;

        return $this;
    }

    public function getPageLink(): string
    {
        return $this->pageLink;
    }

    public function setPageLink(string $pageLink)
    {
        $this->pageLink = $pageLink;
    }

    public function getPlaylistLinks(): array
    {
        return $this->playlistLinks;
    }

    public function setPlaylistLinks(array $links)
    {
        $this->playlistLinks = $links;
    }

    /**
     * @return PlaylistStruct[]
     */
    public function getPlayLists(): array
    {
        return $this->playlists;
    }

    /**
     * @param PlaylistStruct[] $playlists
     */
    public function setPlayLists(array $playlists)
    {
        $this->playlists = $playlists;
    }

    public function addPlaylist(PlaylistStruct $playlist)
    {
        $this->playlists[] = $playlist;
    }

    public function addPlaylistLink(string $link)
    {
        $this->playlistLinks[] = $link;
    }

    public function getSeason(): int
    {
        return $this->season;
    }

    public function setSeason(int $season)
    {
        $this->season = $season;
    }

    public function getEpisode(): int
    {
        return $this->episode;
    }

    public function setEpisode(int $episode)
    {
        $this->episode = $episode;
    }

    public function addRating(int $ratingType, int $value)
    {
        $this->ratings[$ratingType] = $value;
    }

    public function getRatings(): array
    {
        return $this->ratings;
    }

    public function getReleaseDate(): ?\DateTime
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(?\DateTime $dateTime)
    {
        $this->releaseDate = $dateTime;
    }

    public function setIsEmpty(bool $flag)
    {
        $this->isEmpty = $flag;
    }

    public function isEmpty(): bool
    {
        return $this->isEmpty;
    }
}