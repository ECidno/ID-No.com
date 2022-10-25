<?php
namespace App\Entity\Nutzer;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use Doctrine\ORM\Mapping as ORM;

/**
 * Nutzer
 *
 * @ORM\Entity(repositoryClass="App\Repository\NutzerRepository")
 */
class Nutzer
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;



    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


}
