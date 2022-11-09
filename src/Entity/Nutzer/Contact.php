<?php
namespace App\Entity\Nutzer;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Contact
 *
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 */
class Contact extends AbstractEntity
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $id;

    /**
     * @var Person
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="contacts")
     */
     private $person = null;

    /**
     * @var string
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"read"})
     */
    private $contactname;

    /**
     * @var string
     * @ORM\Column(type="boolean", options={"default":"1"}))
     */
    private $contactnameShow = 1;

    /**
     * @var string
     * @ORM\Column(type="string", length=100, nullable=false)
     * @Groups({"read"})
     */
    private $telefon = '';

    /**
     * @var string
     * @ORM\Column(type="boolean", options={"default":"1"}))
     */
    private $telefonShow = 1;

    /**
     * @var string
     * @ORM\Column(type="string", length=100, nullable=false)
     * @Groups({"read"})
     */
    private $beziehung = '';

    /**
     * @var string
     * @ORM\Column(type="boolean", options={"default":"1"}))
     */
    private $beziehungShow = 1;


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @param Person $person
     * @return Contact
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
     * @param string $contactname
     * @return Person
     */
    public function setContactname(string $contactname): self
    {
        $this->contactname = $contactname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getContactname(): ?string
    {
        return $this->contactname;
    }


    /**
     * @param bool $contactnameShow
     * @return Person
     */
    public function setContactnameShow(bool $contactnameShow): self
    {
        $this->contactnameShow = $contactnameShow;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getContactnameShow(): ?bool
    {
        return $this->contactnameShow;
    }


    /**
     * @param ?string $telefon
     * @return Person
     */
    public function setTelefon(?string $telefon): self
    {
        $this->telefon = $telefon ?? '';
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTelefon(): ?string
    {
        return $this->telefon;
    }


    /**
     * @param bool $telefonShow
     * @return Person
     */
    public function setTelefonShow(bool $telefonShow): self
    {
        $this->telefonShow = $telefonShow;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getTelefonShow(): ?bool
    {
        return $this->telefonShow;
    }


    /**
     * @param ?string $beziehung
     * @return Person
     */
    public function setBeziehung(?string $beziehung): self
    {
        $this->beziehung = $beziehung ?? '';
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBeziehung(): ?string
    {
        return $this->beziehung;
    }


    /**
     * @param bool $beziehungShow
     * @return Person
     */
    public function setBeziehungShow(bool $beziehungShow): self
    {
        $this->beziehungShow = $beziehungShow;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getBeziehungShow(): ?bool
    {
        return $this->beziehungShow;
    }


    /**
     * @return array
     */
    public function getEntry(): array
    {
        $name = $this->contactnameShow && !empty($this->contactname)
            ? trim($this->contactname)
            : '';
        $relation = $this->beziehungShow && !empty($this->beziehung)
            ? (
                !empty($name)
                    ? ' ('.trim($this->beziehung).')'
                    : trim($this->beziehung)
            )
            : '';
        $phone = $this->telefonShow && !empty($this->telefon)
            ? trim($this->telefon)
            : '';

        // return
        return array_filter(
            [
                'name' => $name.$relation,
                'phone' => $phone
            ]
        );
    }
}
