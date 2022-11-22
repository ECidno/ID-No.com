<?php
namespace App\Entity\Main;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\AbstractEntity;
use App\Entity\Nutzer\Nutzer;
use App\Entity\Nutzer\Person;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Items
 * @ORM\Table(name="items")
 * @ORM\Entity(repositoryClass="App\Repository\ItemsRepository")
 */
class Items extends AbstractEntity
{
    /**
     * @var string IDNO_PATTERN
     */
    const IDNO_PATTERN = '[a-z,A-Z,0-9]{4}-[a-z,A-Z,0-9]{4}';

    /**
     * @var string IDNO_PATTERN
     */
    const IDNO_PATTERN_UPPER = '[A-Z,0-9]{4}-[A-Z,0-9]{4}';


    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=12, nullable=false)
     * @Groups({"read"})
     */
    private $noStatus;

    /**
     * @var bool
     * @Groups({"read"})
     */
    private $status;

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
     * @ORM\Column(type="text", length=65535, nullable=false)
     * @Groups({"read"})
     */
    private $anbringung;

    /**
     * @var string
     * @ORM\Column(type="string", length=9, nullable=false)
     * @Groups({"read"})
     */
    private $idNo;

    /**
     * @Assert\Type("\DateTimeInterface")
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $registriertDatum;

    /**
     * @Assert\Type("\DateTimeInterface")
     * @ORM\Column(type="datetime", nullable=false)
     * @Groups({"read"})
     * @Context({DateTimeNormalizer::FORMAT_KEY = "d.m.Y"})
     */
    private $aktiviertDatum;

    /**
     * @Assert\Type("\DateTimeInterface")
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $lastChangeDatum;


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @param ?string $noStatus
     * @return Items
     */
    public function setNoStatus(?string $noStatus): self
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
     * @param bool $status
     * @return Items
     */
    public function setStatus(bool $status): self
    {
        $this->noStatus = $status === true
            ? 'registriert'
            : 'deaktiviert';
        return $this;
    }

    /**
     * @return bool
     */
    public function getStatus(): bool
    {
        return $this->noStatus === 'registriert';
    }


    /**
     * @param Nutzer $nutzer
     * @return Items
     public function setNutzer(Nutzer $nutzer): self
     {
         $this->nutzer = $nutzer;
         return $this;
        }
     /

    /**
     * @return Nutzer|null

    public function getNutzer(): ?Nutzer
    {
        return $this->nutzer;
    }
    */


    /**
     * @param ?int $nutzerId
     * @return Items
     */
    public function setNutzerId(?int $nutzerId): self
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

    public function setPerson(Person $person): self
    {
        $this->person = $person;
        return $this;
    }
     */

    /**
     * @return Person|null

    public function getPerson(): ?Person
    {
        return $this->person;
    }
     */


    /**
     * @param ?int $personId
     * @return Items
     */
    public function setPersonId(?int $personId): self
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
     * @param ?string $anbringung
     * @return Items
     */
    public function setAnbringung(?string $anbringung): self
    {
        $this->anbringung = $anbringung;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAnbringung(): ?string
    {
        return $this->anbringung;
    }


    /**
     * @param ?string $idNo
     * @return Items
     */
    public function setIdNo(?string $idNo): self
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


    /**
     * @param ?\DateTimeInterface $registriertDatum
     * @return Items
     */
    public function setRegistriertDatum(?\DateTimeInterface $registriertDatum): self
    {
        $this->registriertDatum = $registriertDatum;
        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getRegistriertDatum(): ?\DateTimeInterface
    {
        return $this->registriertDatum;
    }


    /**
     * @param ?\DateTimeInterface $aktiviertDatum
     * @return Items
     */
    public function setAktiviertDatum(?\DateTimeInterface $aktiviertDatum): self
    {
        $this->aktiviertDatum = $aktiviertDatum;
        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getAktiviertDatum(): ?\DateTimeInterface
    {
        return $this->aktiviertDatum;
    }


    /**
     * @param \DateTimeInterface $lastChangeDatum
     * @return Items
     */
    public function setLastChangeDatum(\DateTimeInterface $lastChangeDatum): self
    {
        $this->lastChangeDatum = $lastChangeDatum;
        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getLastChangeDatum(): ?\DateTimeInterface
    {
        return $this->lastChangeDatum;
    }
}
