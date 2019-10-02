<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Stream
 * @package App\Entity
 *
 * @ORM\Table(name="streams")
 * @ORM\Entity(repositoryClass="App\Repository\StreamRepository")
 */
class Stream
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="id", type="integer")
     */
    private $id = 0;

    /**
     * @var Episode
     *
     * @ORM\ManyToOne(targetEntity="Episode", inversedBy="streams")
     * @ORM\JoinColumn(name="episodeId", referencedColumnName="id")
     */
    private $episode;

    /**
     * @var string
     *
     * @ORM\Column(name="translatedBy", type="string", length=256)
     */
    private $translatedBy = '';

    /**
     * @var string
     *
     * @ORM\Column(name="streamProvider", type="string", length=256)
     */
    private $streamProvider = '';

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=256)
     */
    private $url = '';

    /**
     * @var int
     *
     * @ORM\Column(name="resolution", type="string")
     */
    private $resolution = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="visible", type="integer")
     */
    private $visible = false;

    public function getId(): int
    {
        return $this->id;
    }

    public function getEpisode(): ?Episode
    {
        return $this->episode;
    }

    public function setEpisode(Episode $episode)
    {
        $this->episode = $episode;

        return $this;
    }

    public function getTranslatedBy(): string
    {
        return $this->translatedBy;
    }

    public function setTranslatedBy(string $translatedBy)
    {
        $this->translatedBy = $translatedBy;

        return $this;
    }

    public function getStreamProvider(): string
    {
        return $this->streamProvider;
    }

    public function setStreamProvider(string $streamProvider)
    {
        $this->streamProvider = $streamProvider;

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url)
    {
        $this->url = $url;

        return $this;
    }

    public function getResolution(): int
    {
        return $this->resolution;
    }

    public function setResolution(int $resolution)
    {
        $this->resolution = $resolution;

        return $this;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function setVisible(bool $flag)
    {
        $this->visible = $flag;

        return $this;
    }
}