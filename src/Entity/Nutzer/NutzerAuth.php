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
     * @ORM\Column(type="integer")
     */
    private $nutzerId;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $auth;

    /**
     * @ORM\Column(type="integer")
     */
    private $time;

    /**
     * @ORM\Column(type="string", length=5, options={"default":"neu"})
     */
    private $status = "neu";

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNutzerId(): ?int
    {
        return $this->nutzerId;
    }

    public function setNutzerId(int $nutzerId): self
    {
        $this->nutzerId = $nutzerId;

        return $this;
    }

    public function getAuth(): ?string
    {
        return $this->auth;
    }

    public function setAuth(string $auth): self
    {
        $this->auth = $auth;

        return $this;
    }

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(int $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
