<?php
namespace App\Entity\Nutzer;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Nutzer
 *
 * @ORM\Entity(repositoryClass="App\Repository\NutzerRepository")
 */
class Nutzer implements UserInterface, PasswordAuthenticatedUserInterface
#class Nutzer
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
    private $status;

    /**
     * @var string
     * @ORM\Column(type="string", length=3)
     */
    private $sprache = 'de';

    /**
     * @var array
     *
     * @Assert\NotBlank
     */
    private $roles = [
        'ROLE_USER',
    ];

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=10)
     */
    private $anrede;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    private $vorname;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    private $nachname;

    /**
     * @var string
     * @ORM\Column(type="string", length=5)
     */
    private $freigabe;

    /**
     * @var string
     * @ORM\Column(type="boolean", options={"default":"1"}))
     */
    private $sichtbar = 1;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    private $passwort;

    /**
     * @Assert\DateTime()
     * @ORM\Column(type="datetime")
     */
    private $stempel;

    /**
     * @Assert\DateTime()
     * @ORM\Column(type="datetime")
     */
    private $registriertDatum;

    /**
     * @Assert\DateTime()
     * @ORM\Column(type="datetime")
     */
    private $aktiviertDatum;

    /**
     * @Assert\DateTime()
     * @ORM\Column(type="datetime")
     */
    private $lastChangeDatum;

    /**
     * @Assert\DateTime()
     * @ORM\Column(type="datetime")
     */
    private $lastLogin;

    /**
     * @var string
     * @ORM\Column(type="string", length=5)
     */
    private $gesperrt;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $gesperrtAnzahl;

    /**
     * @Assert\DateTime()
     * @ORM\Column(type="integer")
     */
    private $gesperrtDatum;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $loginFehler;



    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @param string $status
     * @return Nutzer
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
     * @param string $sprache
     * @return Nutzer
     */
    public function setSprache(string $sprache): self
    {
        $this->sprache = $sprache;
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
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return array_unique($this->roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function addRole(string $role): self
    {
        if (!in_array($role, $this->getRoles())) {
            $this->roles[] = $role;
        }
        return $this;
    }


    /**
     * @param string $email
     * @return Nutzer
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getUserName(): ?string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getUserIdentifier(): ?string
    {
        return $this->email;
    }


    /**
     * @param string $anrede
     * @return Nutzer
     */
    public function setAnrede(string $anrede): self
    {
        $this->anrede = $anrede;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAnrede(): ?string
    {
        return $this->anrede;
    }


    /**
     * @param string $vorname
     * @return Nutzer
     */
    public function setVorname(string $vorname): self
    {
        $this->vorname = $vorname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVorname(): ?string
    {
        return $this->vorname;
    }


    /**
     * @param string $nachname
     * @return Nutzer
     */
    public function setNachname(string $nachname): self
    {
        $this->nachname = $nachname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNachname(): ?string
    {
        return $this->nachname;
    }


    /**
     * @param string $freigabe
     * @return Nutzer
     */
    public function setFreigabe(string $freigabe): self
    {
        $this->freigabe = $freigabe;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFreigabe(): ?string
    {
        return $this->freigabe;
    }


    /**
     * @param bool $sichtbar
     * @return Nutzer
     */
    public function setSichtbar(bool $sichtbar): self
    {
        $this->sichtbar = $sichtbar;
        return $this;
    }

    /**
     * @return bool
     */
    public function getSichtbar(): bool
    {
        return $this->sichtbar;
    }


    /**
     * @param string $passwort
     * @return Nutzer
     */
    public function setPasswort(string $passwort): self
    {
        $this->passwort = $passwort;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPasswort(): ?string
    {
        return $this->passwort;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->passwort;
    }


    /**
     * @param \DateTime $stempel
     * @return Nutzer
     */
    public function setStempel(\DateTime $stempel): self
    {
        $this->stempel = $stempel;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getStempel(): ?\DateTime
    {
        return $this->stempel;
    }


    /**
     * @param \DateTime $registriertDatum
     * @return Nutzer
     */
    public function setRegistriertDatum(\DateTime $registriertDatum): self
    {
        $this->registriertDatum = $registriertDatum;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getRegistriertDatum(): ?\DateTime
    {
        return $this->registriertDatum;
    }


    /**
     * @param \DateTime $aktiviertDatum
     * @return Nutzer
     */
    public function setAktiviertDatum(\DateTime $aktiviertDatum): self
    {
        $this->aktiviertDatum = $aktiviertDatum;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getAktiviertDatum(): ?\DateTime
    {
        return $this->aktiviertDatum;
    }


    /**
     * @param \DateTime $lastChangeDatum
     * @return Nutzer
     */
    public function setLastChangeDatum(\DateTime $lastChangeDatum): self
    {
        $this->lastChangeDatum = $lastChangeDatum;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastChangeDatum(): ?\DateTime
    {
        return $this->lastChangeDatum;
    }


    /**
     * @param \DateTime $lastLogin
     * @return Nutzer
     */
    public function setLastLogin(\DateTime $lastLogin): self
    {
        $this->lastLogin = $lastLogin;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastLogin(): ?\DateTime
    {
        return $this->lastLogin;
    }


    /**
     * @return bool
     */
    public function isAllowedToLogin(): bool
    {
        return  $this->getStatus() === 'ok';
    }


    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }


    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
        $this->repeatPassword = null;
    }


    /**
     * @param string $gesperrt
     * @return Nutzer
     */
    public function setGesperrt(string $gesperrt): self
    {
        $this->gesperrt = $gesperrt;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getGesperrt(): ?string
    {
        return $this->gesperrt;
    }


    /**
     * @param int $gesperrtAnzahl
     * @return Nutzer
     */
    public function setGesperrtAnzahl(int $gesperrtAnzahl): self
    {
        $this->gesperrtAnzahl = $gesperrtAnzahl;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getGesperrtAnzahl(): ?int
    {
        return $this->gesperrtAnzahl;
    }


    /**
     * @param \DateTime $gesperrtDatum
     * @return Nutzer
     */
    public function setGesperrtDatum(\DateTime $gesperrtDatum): self
    {
        $this->gesperrtDatum = $gesperrtDatum;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getGesperrtDatum(): ?\DateTime
    {
        return $this->gesperrtDatum;
    }


    /**
     * @param int $loginFehler
     * @return Nutzer
     */
    public function setLoginFehler(int $loginFehler): self
    {
        $this->loginFehler = $loginFehler;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getLoginFehler(): ?int
    {
        return $this->loginFehler;
    }
}