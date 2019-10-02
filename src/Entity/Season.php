<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Season
 * @package App\Entity
 *
 * @ORM\Table(name="seasons")
 * @ORM\Entity(repositoryClass="App\Repository\SeasonRepository")
 */
class Season
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
     * @var Serial
     *
     * @ORM\ManyToOne(targetEntity="Serial", inversedBy="seasons")
     * @ORM\JoinColumn(name="serialId", referencedColumnName="id")
     */
    private $serial;

    /**
     * @var string
     *
     * @ORM\Column(name="number", type="string", length=256)
     */
    private $number = '';

    /**
     * @var bool
     *
     * @ORM\Column(name="visible", type="integer")
     */
    private $visible = false;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Episode", mappedBy="season", cascade={"persist", "remove"})
     */
    private $episodes;

    public function __construct()
    {
        $this->episodes = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSerial(): ?Serial
    {
        return $this->serial;
    }

    public function setSerial(Serial $serial): Season
    {
        $this->serial = $serial;

        return $this;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setNumber(string $number): Season
    {
        $this->number = $number;

        return $this;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function setVisible(bool $flag): Season
    {
        $this->visible = $flag;

        return $this;
    }

    public function getEpisodes(): Collection
    {
        return $this->episodes;
    }

    public function addEpisode(Episode $episode)
    {
        $episode->setSeason($this);
        $this->episodes->add($episode);

        return $this;
    }

    public function getEpisodeByNumber(int $number): ?Episode
    {
        /** @var Episode $episode */
        foreach ($this->episodes as $episode) {
            if ($episode->getNumber() == $number) {
                return $episode;
            }
        }

        return null;
    }
}