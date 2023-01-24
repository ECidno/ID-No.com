<?php
namespace App\Entity\Nutzer;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Rollerworks\Component\PasswordStrength\Validator\Constraints as RollerworksPassword;

/**
 * Nutzer
 *
 * @ORM\Entity(repositoryClass="App\Repository\NutzerRepository")
 * @UniqueEntity(fields={"email"}, message="nutzer.email.unique")
 */
class Nutzer implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Nutzer\Person", mappedBy="nutzer", cascade={"persist"})
     */
    private $persons;

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
     * @Assert\NotBlank
     */
    private $vorname;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     */
    private $nachname;

    /**
     * @var string
     * @ORM\Column(type="string", length=5, options={"default":"nein"})
     */
    private $freigabe = "nein";

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
     * Plain Password for double Check
     *
     * @var string
     * @RollerworksPassword\PasswordStrength(minLength=8, minStrength=3)
     */
    private $plainPasswort;

    /**
     * @var string
     */
    private $plainPassword;

    /**
     * @var string
     */
    private $repeatPassword;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type("\DateTimeInterface")
     * @Gedmo\Timestampable(on="create")
     */
    private $stempel;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type("\DateTimeInterface")
     * @Gedmo\Timestampable(on="create")
     */
    private $registriertDatum;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type("\DateTimeInterface")
     */
    private $aktiviertDatum;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type("\DateTimeInterface")
     * @Gedmo\Timestampable(on="create")
     * @Gedmo\Timestampable(on="update")
     */
    private $lastChangeDatum;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type("\DateTimeInterface")
     */
    private $lastLogin;

    /**
     * @var string
     * @ORM\Column(type="string", length=5, options={"default":"nein"})
     */
    private $gesperrt = "nein";

    /**
     * @var int
     * @ORM\Column(type="integer", options={"default":"0"})
     */
    private $gesperrtAnzahl = 0;

    /**
     * @var int
     * @ORM\Column(type="integer", options={"default":"0"})
     */
    private $gesperrtDatum = 0;

    /**
     * @var int
     * @ORM\Column(type="integer", options={"default":"0"})
     */
    private $loginFehler = 0;

    /**
     * @var int
     * @ORM\Column(type="integer", options={"default":"1"})
     */
    private $source;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $sendInformation;


    /**
     * constructor
     */
    public function __construct()
    {
        $this->persons = new ArrayCollection();
    }


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @param Collection $persons
     * @return Nutzer
     */
    public function setPersons(Collection $persons): self
    {
        $this->persons = $persons;
        return $this;
    }

    /**
     * @return Collection|Person[]
     */
    public function getPersons(): Collection
    {
        return $this->persons;
    }

    /**
     * @param Person $person
     * @return Nutzer
     */
    public function addPerson(Person $person): self
    {
        if (!$this->persons->contains($person)) {
            $this->persons[] = $person;
            $person->setNutzer($this);
        }
        return $this;
    }

    /**
     * @param Person $person
     * @return Nutzer
     */
    public function removePerson(Person $person): self
    {
        if ($this->persons->contains($person)) {
            $this->persons->removeElement($person);
            if ($person->getNutzer() === $this) {
 #               $person->setNutzer(null);
            }
        }
        return $this;
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

    /**
     * @param array $roles
     * @return self
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @param string $role
     * @return self
     */
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
        return html_entity_decode($this->vorname);
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
        return html_entity_decode($this->nachname);
    }

    /**
     * @return ?string
     */
    public function getFullName(): ?string
    {
        return join(
            ' ',
            array_filter(
                [
                    $this->getVorname(),
                    $this->getNachname(),
                ]
            )
        );
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
     * @return string
     */
    public function getFreigabe(): string
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
     * @param string $passwort
     * @return Nutzer
     */
    public function setPlainPasswort(string $passwort): self
    {
        $this->plainPasswort = $passwort;
        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPasswort(): string
    {
        return $this->plainPasswort;
    }


    /**
     * @param \DateTimeInterface $stempel
     * @return Nutzer
     */
    public function setStempel(\DateTimeInterface $stempel): self
    {
        $this->stempel = $stempel;
        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getStempel(): ?\DateTimeInterface
    {
        return $this->stempel;
    }


    /**
     * @param \DateTimeInterface $registriertDatum
     * @return Nutzer
     */
    public function setRegistriertDatum(\DateTimeInterface $registriertDatum): self
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
     * @param \DateTimeInterface $aktiviertDatum
     * @return Nutzer
     */
    public function setAktiviertDatum(\DateTimeInterface $aktiviertDatum): self
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
     * @return Nutzer
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


    /**
     * @param \DateTimeInterface $lastLogin
     * @return Nutzer
     */
    public function setLastLogin(\DateTimeInterface $lastLogin): self
    {
        $this->lastLogin = $lastLogin;
        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }


    /**
     * @return bool
     */
    public function isAllowedToLogin(): bool
    {
        return
            $this->getStatus() === 'ok' ||
            $this->getStatus() === 'unlogged';
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
     * @param int $gesperrtDatum
     * @return Nutzer
     */
    public function setGesperrtDatum(int $gesperrtDatum): self
    {
        $this->gesperrtDatum = $gesperrtDatum;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getGesperrtDatum(): ?int
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

    /**
     * @param integer $source
     * @return self
     */
    public function setSource(int $source): self
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return integer
     */
    public function getSource(): int
    {
        return $this->source;
    }

    /**
     * @param integer $sendInformation
     * @return self
     */
    public function setSendInformation(int $sendInformation): self
    {
        $this->sendInformation = $sendInformation;
        return $this;
    }

    /**
     * @return integer|null
     */
    public function getSendInformation(): ?int
    {
        return $this->sendInformation;
    }
}