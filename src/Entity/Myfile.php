<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MyfileRepository")
 */
class Myfile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $mycsv;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMycsv()
    {
        return $this->mycsv;
    }

    public function setMycsv($mycsv): self
    {
        $this->mycsv = $mycsv;

        return $this;
    }
}
