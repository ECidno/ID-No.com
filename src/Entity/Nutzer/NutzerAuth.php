<?php

namespace App\Entity\Nutzer;

use App\Repository\Nutzer\NutzerAuthRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NutzerAuthRepository::class)
 */
class NutzerAuth
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Nutzer
     * @ORM\OneToOne(targetEntity="App\Entity\Nutzer\Nutzer")
     */
    private $nutzer;

    /**
     * @var string
     * @ORM\Column(type="string", length=40)
     */
    private $auth;

    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $time;

    /**
     * @var string
     * @ORM\Column(type="string", length=5, options={"default":"neu"})
     */
    private $status = "neu";

    /**
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

     /**
      * @param Nutzer $nutzer
      * @return self
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
     * @return string|null
     */
    public function getAuth(): ?string
    {
        return $this->auth;
    }

    /**
     * @param string $auth
     * @return self
     */
    public function setAuth(string $auth): self
    {
        $this->auth = $auth;

        return $this;
    }

    /**
     * @return integer|null
     */
    public function getTime(): ?int
    {
        return $this->time;
    }

    /**
     * @param integer $time
     * @return self
     */
    public function setTime(int $time): self
    {
        $this->time = $time;

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
     * @param string $status
     * @return self
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
