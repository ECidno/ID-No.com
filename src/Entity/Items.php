<?php
namespace App\Entity;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use Doctrine\ORM\Mapping as ORM;

/**
 * Items
 *
 * @ORM\Entity(repositoryClass="App\Repository\ItemsRepository")
 */
class Items
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @var string
     * @ORM\Column(type="string", length=9)
     */
    private $idNo;



    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }



    /**
     * @param string $idNo
     * @return Items
     */
    public function setIdNo(string $idNo): self
    {
        $this->idNo = $idNo;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIdNo(): ?string
    {
        return $this->idNo;
    }
}
