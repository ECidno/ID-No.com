<?php

namespace App\Entity\Nutzer;

use Doctrine\ORM\Mapping as ORM;

/**
 * Texte
 *
 * @ORM\Table(name="texte")
 * @ORM\Entity
 */
class Texte
{
    /**
     * @var string
     *
     * @ORM\Column(name="bez", type="string", length=150, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $bez;

    /**
     * @var string
     *
     * @ORM\Column(name="sprache", type="string", length=10, nullable=false, options={"default"="de"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $sprache = 'de';

    /**
     * @var string
     *
     * @ORM\Column(name="string", type="text", length=0, nullable=false)
     */
    private $string;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="gelesen", type="datetime", nullable=false, options={"default"="0000-00-00 00:00:00"})
     */
    private $gelesen = '0000-00-00 00:00:00';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="geschrieben", type="datetime", nullable=false, options={"default"="0000-00-00 00:00:00"})
     */
    private $geschrieben = '0000-00-00 00:00:00';

    /**
     * @return string|null
     */
    public function getBez(): ?string
    {
        return $this->bez;
    }

    /**
     * @param string $bez
     * @return self
     */
    public function setBez(string $bez): self
    {
        $this->bez = $bez;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSprache(): ?string
    {
        return $this->sprache;
    }

    /**
     * @param string $sprache
     * @return self
     */
    public function setSprache(string $sprache): self
    {
        $this->sprache = $sprache;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getString(): ?string
    {
        return $this->string;
    }

    /**
     * @param string $string
     * @return self
     */
    public function setString(string $string): self
    {
        $this->string = $string;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getGelesen(): ?\DateTimeInterface
    {
        return $this->gelesen;
    }

    /**
     * @param \DateTimeInterface $gelesen
     * @return self
     */
    public function setGelesen(\DateTimeInterface $gelesen): self
    {
        $this->gelesen = $gelesen;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getGeschrieben(): ?\DateTimeInterface
    {
        return $this->geschrieben;
    }

    /**
     * @param \DateTimeInterface $geschrieben
     * @return self
     */
    public function setGeschrieben(\DateTimeInterface $geschrieben): self
    {
        $this->geschrieben = $geschrieben;

        return $this;
    }
}
