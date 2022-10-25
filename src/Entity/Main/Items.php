<?php
namespace App\Entity\Main;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Nutzer\Nutzer;
use App\Entity\Nutzer\Person;

/**
 * Items
 * @ORM\Table(name="items")
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
     * @ORM\Column(type="string", length=12)
     */
    private $noStatus;

    /**
     * @var Nutzer
     * @ORM\ManyToOne(targetEntity="Nutzer")
     private $nutzer = null;
     */

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $nutzerId;

    /**
     * @var Person
     * @ORM\ManyToOne(targetEntity="Person")
     private $person = null;
     */

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $personId;

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
     * @param string $noStatus
     * @return Items
     */
    public function setNoStatus(string $noStatus): self
    {
        $this->noStatus = $noStatus;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNoStatus(): ?string
    {
        return $this->noStatus;
    }


    /**
     * @param Nutzer $nutzer
     * @return Items
     */
    public function setNutzer(Nutzer $nutzer): self
    {
        $this->nutzer = $nutzer;
        return $this;
    }

    /**
     * @return Nutzer|null
     */
    public function getNutzer(): ?Nutzer
    {
        return $this->nutzer;
    }


    /**
     * @param int $nutzerId
     * @return Items
     */
    public function setNutzerId(int $nutzerId): self
    {
        $this->nutzerId = $nutzerId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getNutzerId(): ?int
    {
        return $this->nutzerId;
    }


    /**
     * @param Person $person
     * @return Items
     */
    public function setPerson(Person $person): self
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @return Person|null
     */
    public function getPerson(): ?Person
    {
        return $this->person;
    }


    /**
     * @param int $personId
     * @return Items
     */
    public function setPersonId(int $personId): self
    {
        $this->personId = $personId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPersonId(): ?int
    {
        return $this->personId;
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
