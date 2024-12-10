<?php
namespace App\Entity;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Person;

/**
 * PassEntryCondition
 *
 * @ORM\Entity(repositoryClass="App\Repository\PassEntryConditionRepository")
 * @Gedmo\Loggable
 */
class PassEntryCondition
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Person", inversedBy="passEntryCondition")
     */
    private $person;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Versioned
     */
    private $category;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Versioned
     */
    private $title;

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
     * Get the value of category
     *
     * @return  string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set the value of category
     *
     * @param  string  $category
     *
     * @return  self
     */
    public function setCategory(string $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get the value of title
     *
     * @return  string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @param  string  $title
     *
     * @return  self
     */
    public function setTitle(string $title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of comment
     *
     * @return  string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set the value of comment
     *
     * @param  string  $comment
     *
     * @return  self
     */
    public function setComment(string $comment)
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
