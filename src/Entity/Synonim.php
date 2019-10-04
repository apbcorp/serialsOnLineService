<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Synonim
 * @package App\Entity
 *
 * @ORM\Table(name="serialSynonims")
 * @ORM\Entity()
 */
class Synonim
{
    /**
     * @var Serial
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Serial", inversedBy="synonims")
     * @ORM\JoinColumn(name="serialId", referencedColumnName="id")
     */
    private $serial;

    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(name="name", type="string", length=512)
     */
    private $name = '';

    public function getSerial(): ?Serial
    {
        return $this->serial;
    }

    public function setSerial(Serial $serial)
    {
        $this->serial = $serial;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }
}