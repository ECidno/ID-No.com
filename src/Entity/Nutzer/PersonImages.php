<?php
namespace App\Entity\Nutzer;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PersonImages
 *
 * @ORM\Entity(repositoryClass="App\Repository\PersonImagesRepository")
 */
class PersonImages
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Person
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="images")
     */
    private $person = null;

    /**
     * @var string
     * @ORM\Column(type="string", length=20)
     */
    private $status;

    /**
     * @var string
     * @ORM\Column(type="string", length=50)
     */
    private $bild;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     */
    private $bildShow = 1;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $width;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $height;

    /**
     * @var string
     * @ORM\Column(type="string", length=15)
     */
    private $ip;

    /**
     * @Assert\Type("\DateTimeInterface")
     * @ORM\Column(type="datetime")
     */
    private $created;


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @param Person $person
     * @return PersonImages
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
     * @param string $status
     * @return PersonImages
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }


    /**
     * @param string $bild
     * @return PersonImages
     */
    public function setBild(string $bild): self
    {
        $this->bild = $bild;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBild(): ?string
    {
        return $this->bild;
    }


    /**
     * @param bool $bildShow
     * @return PersonImages
     */
    public function setBildShow(bool $bildShow): self
    {
        $this->bildShow = $bildShow;
        return $this;
    }

    /**
     * @return bool
     */
    public function getBildShow(): bool
    {
        return $this->bildShow;
    }


    /**
     * @param int $width
     * @return PersonImages
     */
    public function setWidth(int $width): self
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getWidth(): ?int
    {
        return $this->width;
    }


    /**
     * @param int $height
     * @return PersonImages
     */
    public function setHeight(int $height): self
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getHeight(): ?int
    {
        return $this->height;
    }


    /**
     * @param string $ip
     * @return PersonImages
     */
    public function setIp(string $ip): self
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }


    /**
     * @param \DateTimeInterface $created
     * @return PersonImages
     */
    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }
}