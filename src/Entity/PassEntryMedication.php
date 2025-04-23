<?php
namespace App\Entity;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Person;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PassEntryMedication
 *
 * @ORM\Entity(repositoryClass="App\Repository\PassEntryMediactionRepository")
 * @Gedmo\Loggable
 */
class PassEntryMedication
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $sorting = 0;

    /**
     * @var Person
     * @ORM\ManyToOne(targetEntity="App\Entity\Person", inversedBy="passEntryMedications")
     */
    private $person;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Versioned
     */
    private $ingredient;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Versioned
     */
    private $tradeName;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Versioned
     */
    private $dosage;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Versioned
     */
    private $consumption;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    private $emergencyNotes;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Gedmo\Versioned
     */
    private $comment;

    /**
     * @Assert\Type("\DateTimeInterface")
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $created;

    /**
     * @Assert\Type("\DateTimeInterface")
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $lastChange;

    /**
     * Get the value of id
     *
     * @return  int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the value of sorting
     *
     * @return  int
     */
    public function getSorting()
    {
        return $this->sorting;
    }

    /**
     * Set the value of sorting
     *
     * @param  int  $sorting
     *
     * @return  self
     */
    public function setSorting(int $sorting)
    {
        $this->sorting = $sorting;

        return $this;
    }

    /**
     * Get the value of person
     *
     * @return  Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Set the value of person
     *
     * @param  Person  $person
     *
     * @return  self
     */
    public function setPerson(Person $person)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get the value of ingredient
     *
     * @return  ?string
     */
    public function getIngredient()
    {
        return $this->ingredient;
    }

    /**
     * Set the value of ingredient
     *
     * @param  ?string  $ingredient
     *
     * @return  self
     */
    public function setIngredient(?string $ingredient)
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    /**
     * Get the value of tradeName
     *
     * @return  ?string
     */
    public function getTradeName()
    {
        return $this->tradeName;
    }

    /**
     * Set the value of tradeName
     *
     * @param  ?string  $tradeName
     *
     * @return  self
     */
    public function setTradeName(?string $tradeName)
    {
        $this->tradeName = $tradeName;

        return $this;
    }

    /**
     * Get the value of dosage
     *
     * @return  ?string
     */
    public function getDosage()
    {
        return $this->dosage;
    }

    /**
     * Set the value of dosage
     *
     * @param  ?string  $dosage
     *
     * @return  self
     */
    public function setDosage(?string $dosage)
    {
        $this->dosage = $dosage;

        return $this;
    }

    /**
     * Get the value of consumption
     *
     * @return  ?string
     */
    public function getConsumption()
    {
        return $this->consumption;
    }

    /**
     * Set the value of consumption
     *
     * @param  ?string  $consumption
     *
     * @return  self
     */
    public function setConsumption(?string $consumption)
    {
        $this->consumption = $consumption;

        return $this;
    }

    /**
     * Get the value of emergencyNotes
     *
     * @return  ?string
     */
    public function getEmergencyNotes()
    {
        return $this->emergencyNotes;
    }

    /**
     * Set the value of emergencyNotes
     *
     * @param  ?string  $emergencyNotes
     *
     * @return  self
     */
    public function setEmergencyNotes(?string $emergencyNotes)
    {
        $this->emergencyNotes = $emergencyNotes;

        return $this;
    }

    /**
     * Get the value of comment
     *
     * @return  ?string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set the value of comment
     *
     * @param  ?string  $comment
     *
     * @return  self
     */
    public function setComment(?string $comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get the value of created
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set the value of created
     *
     * @return  self
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get the value of lastChange
     */
    public function getLastChange()
    {
        return $this->lastChange;
    }

    /**
     * Set the value of lastChange
     *
     * @return  self
     */
    public function setLastChange($lastChange)
    {
        $this->lastChange = $lastChange;

        return $this;
    }
}
