<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Rating
 * @package App\Entity
 *
 * @ORM\Table(name="ratings")
 * @ORM\Entity(repositoryClass="App\Repository\RatingRepository")
 */
class Rating
{
    const TYPE_COLDFILM_INT = 1;
    const TYPE_COLDFILM_FLOAT = 2;

    const MAX_RATING_VALUES = [
        self::TYPE_COLDFILM_FLOAT => 500,
        self::TYPE_COLDFILM_INT => 100
    ];

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
     * @ORM\ManyToOne(targetEntity="Episode", inversedBy="ratings")
     * @ORM\JoinColumn(name="episodeId", referencedColumnName="id")
     */
    private $episode;

    /**
     * @var int
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="value", type="integer")
     */
    private $value = 0;

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

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type)
    {
        $this->type = $type;

        return $this;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function setValue(int $value)
    {
        $this->value = $value;

        return $this;
    }
}