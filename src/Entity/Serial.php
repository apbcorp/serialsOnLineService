<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Serial
 * @package App\Entity
 *
 * @ORM\Table(name="serials")
 * @ORM\Entity(repositoryClass="App\Repository\SerialRepository")
 */
class Serial
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=256)
     */
    private $name = '';

    /**
     * @var string
     *
     * @ORM\Column(name="screen", type="string", length=2048)
     */
    private $screen = '';

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Season", mappedBy="serial", cascade={"persist", "remove"})
     */
    private $seasons;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Synonim", mappedBy="serial", cascade={"persist", "remove"})
     */
    private $synonims;

    /**
     * @var bool
     *
     * @ORM\Column(name="visible", type="integer")
     */
    private $visible = false;

    public function __construct()
    {
        $this->seasons = new ArrayCollection();
        $this->synonims = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Serial
    {
        $this->name = $name;

        return $this;
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

    public function getSeasons(): Collection
    {
        return $this->seasons;
    }

    public function addSeason(Season $season): Serial
    {
        $season->setSerial($this);
        $this->seasons->add($season);

        return $this;
    }

    public function getSeasonByNumber(string $number): ?Season
    {
        /** @var Season $season */
        foreach ($this->seasons as $season) {
            if ($season->getNumber() == $number) {
                return $season;
            }
        }

        return null;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function setVisible(bool $flag): Serial
    {
        $this->visible = $flag;

        return $this;
    }

    public function getSynonims(): Collection
    {
        return $this->synonims;
    }
}