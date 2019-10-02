<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Episode
 * @package App\Entity
 *
 * @ORM\Table(name="episodes")
 * @ORM\Entity(repositoryClass="App\Repository\EpisodeRepository")
 */
class Episode
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
     * @var Season
     *
     * @ORM\ManyToOne(targetEntity="Season", inversedBy="episodes")
     * @ORM\JoinColumn(name="seasonId", referencedColumnName="id")
     */
    private $season;

    /**
     * @var int
     *
     * @ORM\Column(name="number", type="integer")
     */
    private $number = 0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="releaseDate", type="datetime")
     */
    private $releaseDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="visible", type="integer")
     */
    private $visible = false;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Stream", mappedBy="stream", cascade={"persist", "remove"})
     */
    private $streams;

    public function getId(): int
    {
        return $this->id;
    }

    public function getSeason(): ?Season
    {
        return $this->season;
    }

    public function setSeason(Season $season)
    {
        $this->season = $season;

        return $this;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setNumber(int $number)
    {
        $this->number = $number;

        return $this;
    }

    public function getReleaseDate(): ?\DateTime
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTime $date)
    {
        $this->releaseDate = $date;

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

    public function getStreams(): Collection
    {
        return $this->streams;
    }

    public function addStream(Stream $stream)
    {
        $stream->setEpisode($this);
        $this->streams->add($stream);

        return $this;
    }
}