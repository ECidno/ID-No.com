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
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Person
 *
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 */
class Person
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Nutzer\Person")
     */
    private $parent = null;

    /**
     * @var Nutzer
     * @ORM\ManyToOne(targetEntity="App\Entity\Nutzer\Nutzer", inversedBy="persons")
     */
    private $nutzer = null;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Nutzer\Contact", mappedBy="person")
     */
    private $contacts;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Nutzer\PersonImages", mappedBy="person")
     */
    private $images;

    /**
     * @var string
     * @ORM\Column(type="string", length=25)
     */
    private $status;

    /**
     * @var string
     * @ORM\Column(type="string", length=3)
     */
    private $sprache = 'de';

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=5)
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
     * @ORM\Column(type="string", length=100)
     */
    private $strasse;

    /**
     * @var string
     * @ORM\Column(type="boolean", options={"default":"1"}))
     */
    private $strasseShow = 1;

    /**
     * @var string
     * @ORM\Column(type="string", length=13)
     */
    private $plz;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    private $ort;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     */
    private $ortShow = 1;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    private $zusatz;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     */
    private $zusatzShow = 1;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    private $land;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $geburtsdatumTag;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $geburtsdatumMonat;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $geburtsdatumJahr;

    /**
     * @var string
     * @ORM\Column(type="string", length=6)
     */
    private $telefonLand;

    /**
     * @var string
     * @ORM\Column(type="string", length=10)
     */
    private $telefonVorwahl;

    /**
     * @var string
     * @ORM\Column(type="string", length=30)
     */
    private $telefon;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     */
    private $telefonShow = 1;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     */
    private $mobileShow = 1;

    /**
     * @var string
     * @ORM\Column(type="string", length=6)
     */
    private $mobileLand;

    /**
     * @var string
     * @ORM\Column(type="string", length=10)
     */
    private $mobileVorwahl;

    /**
     * @var string
     * @ORM\Column(type="string", length=30)
     */
    private $mobile;

    /**
     * @var string
     * @ORM\Column(type="string", length=10)
     */
    private $gewicht;

    /**
     * @var string
     * @ORM\Column(type="string", length=5)
     */
    private $gewichtEinheit;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     */
    private $gewichtShow = 1;

    /**
     * @var string
     * @ORM\Column(type="string", length=10)
     */
    private $groesse;

    /**
     * @var string
     * @ORM\Column(type="string", length=5)
     */
    private $groesseEinheit;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     */
    private $groesseShow = 1;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    private $krankenversicherung;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     */
    private $krankenversicherungShow = 1;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    private $versicherungsnummer;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     */
    private $versicherungsnummerShow = 1;

    /**
     * @var string
     * @ORM\Column(type="text", length=65535, nullable=true)
     */
    private $zusatzversicherung;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     */
    private $zusatzversicherungShow = 1;

    /**
     * @var string
     * @ORM\Column(type="string", length=30)
     */
    private $blutgruppe;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     */
    private $blutgruppeShow = 1;

    /**
     * @var string
     * @ORM\Column(type="text", length=16777215, nullable=true)
     */
    private $erkrankungen;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     */
    private $erkrankungenShow = 1;

    /**
     * @var string
     * @ORM\Column(type="text", length=65535, nullable=true)
     */
    private $medikamente;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     */
    private $medikamenteShow = 1;

    /**
     * @var string
     * @ORM\Column(type="text", length=65535, nullable=true)
     */
    private $allergieen;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     */
    private $allergieenShow = 1;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     */
    private $organspender;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     */
    private $organspenderShow = 1;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     */
    private $patientenverf;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     */
    private $patientenverfShow = 1;

    /**
     * @var string
     * @ORM\Column(type="text", length=65535, nullable=true)
     */
    private $weitereangaben;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     */
    private $weitereangabenShow = 1;

    /**
     * @Assert\Type("\DateTimeInterface")
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $lastChangeDatum;


    /**
     * constructor
     */
    public function __construct()
    {
        $this->contacts = new ArrayCollection();
        $this->images = new ArrayCollection();
    }


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @param Person $parent
     * @return Items
     */
    public function setParent(Person $parent): self
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return Person|null
     */
    public function getParent(): ?Person
    {
        return $this->parent;
    }


    /**
     * @param Nutzer $nutzer
     * @return Person
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
     * @param Collection $contacts
     * @return Items
     */
    public function setContacts(Collection $contacts): self
    {
        $this->contacts = $contacts;
        return $this;
    }

    /**
     * @return Collection|Contact[]
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }


    /**
     * @param Collection $images
     * @return Items
     */
    public function setImages(Collection $images): self
    {
        $this->images = $images;
        return $this;
    }

    /**
     * @return Collection|PersonImages[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }


    /**
     * @param string $status
     * @return Person
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
     * @return Person
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
     * @param string $email
     * @return Person
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
     * @param string $anrede
     * @return Person
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
     * @return Person
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
     * @return Person
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
     * @return string|null
     */
    public function getFullName(): ?string
    {
        return join(
            ' ',
            array_filter(
                [
                    $this->vorname,
                    $this->nachname,
                ]
            )
        );
    }


    /**
     * @param string $strasse
     * @return Person
     */
    public function setStrasse(string $strasse): self
    {
        $this->strasse = $strasse;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStrasse(): ?string
    {
        return $this->strasse;
    }


    /**
     * @param bool $strasseShow
     * @return Person
     */
    public function setStrasseShow(bool $strasseShow): self
    {
        $this->strasseShow = $strasseShow;
        return $this;
    }

    /**
     * @return bool
     */
    public function getStrasseShow(): bool
    {
        return $this->strasseShow;
    }


    /**
     * @param string $plz
     * @return Person
     */
    public function setPlz(string $plz): self
    {
        $this->plz = $plz;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPlz(): ?string
    {
        return $this->plz;
    }


    /**
     * @param string|null $ort
     * @return Person
     */
    public function setOrt(?string $ort): self
    {
        $this->ort = $ort;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOrt(): ?string
    {
        return $this->ort;
    }


    /**
     * @param bool $ortShow
     * @return Person
     */
    public function setOrtShow(bool $ortShow): self
    {
        $this->ortShow = $ortShow;
        return $this;
    }

    /**
     * @return bool
     */
    public function getOrtShow(): bool
    {
        return $this->ortShow;
    }


    /**
     * @param string $zusatz
     * @return Person
     */
    public function setZusatz(?string $zusatz): self
    {
        $this->zusatz = $zusatz ?? null;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getZusatz(): ?string
    {
        return $this->zusatz;
    }


    /**
     * @param bool $zusatzShow
     * @return Person
     */
    public function setZusatzShow(bool $zusatzShow): self
    {
        $this->zusatzShow = $zusatzShow;
        return $this;
    }

    /**
     * @return bool
     */
    public function getZusatzShow(): bool
    {
        return $this->zusatzShow;
    }


    /**
     * @param string $land
     * @return Person
     */
    public function setLand(?string $land): self
    {
        $this->land = $land ?? null;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLand(): ?string
    {
        return $this->land;
    }


    /**
     * @param int $geburtsdatumTag
     * @return Person
     */
    public function setGeburtsdatumTag(int $geburtsdatumTag): self
    {
        $this->geburtsdatumTag = $geburtsdatumTag;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getGeburtsdatumTag(): ?int
    {
        return $this->geburtsdatumTag;
    }


    /**
     * @param int $geburtsdatumMonat
     * @return Person
     */
    public function setGeburtsdatumMonat(int $geburtsdatumMonat): self
    {
        $this->geburtsdatumMonat = $geburtsdatumMonat;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getGeburtsdatumMonat(): ?int
    {
        return $this->geburtsdatumMonat;
    }


    /**
     * @param int $geburtsdatumJahr
     * @return Person
     */
    public function setGeburtsdatumJahr(int $geburtsdatumJahr): self
    {
        $this->geburtsdatumJahr = $geburtsdatumJahr;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getGeburtsdatumJahr(): ?int
    {
        return $this->geburtsdatumJahr;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getDateOfBirth(): ?\DateTimeImmutable
    {
        $dob = \DateTimeImmutable::createFromFormat(
            'Y-m-d H:i:s',
            join(
                '-',
                [
                    $this->geburtsdatumJahr,
                    $this->geburtsdatumMonat,
                    $this->geburtsdatumTag,
                ]
            ).'00:00:00'
        );

        // return
        return $dob
            ? $dob
            : null;
    }

    /**
     * @return int|null
     */
    public function getAge(): ?int
    {
        $now = new \DateTime();
        $dob = $this->getDateOfBirth();
        return $dob
            ? $dob
                ->diff($now)
                ->format('%y')
            : null;
    }


    /**
     * @param string $telefonLand
     * @return Person
     */
    public function setTelefonLand(?string $telefonLand): self
    {
        $this->telefonLand = $telefonLand ?? '';
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTelefonLand(): ?string
    {
        return $this->telefonLand;
    }


    /**
     * @param string $telefonVorwahl
     * @return Person
     */
    public function setTelefonVorwahl(?string $telefonVorwahl): self
    {
        $this->telefonVorwahl = $telefonVorwahl ?? null;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTelefonVorwahl(): ?string
    {
        return $this->telefonVorwahl;
    }


    /**
     * @param string $telefon
     * @return Person
     */
    public function setTelefon(?string $telefon): self
    {
        $this->telefon = $telefon ?? null;
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
     * @return bool
     */
    public function getTelefonShow(): bool
    {
        return $this->telefonShow;
    }


    /**
     * @return string|null
     */
    public function getTelefonDisplay(): ?string
    {
        return join(
            ' ',
            array_filter(
                [
                    $this->telefonLand,
                    $this->telefonVorwahl,
                    $this->telefon,
                ]
            )
        );
    }


    /**
     * @param string $mobileLand
     * @return Person
     */
    public function setMobileLand(?string $mobileLand): self
    {
        $this->mobileLand = $mobileLand ?? '';
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMobileLand(): ?string
    {
        return $this->mobileLand;
    }


    /**
     * @param string $mobileVorwahl
     * @return Person
     */
    public function setMobileVorwahl(?string $mobileVorwahl): self
    {
        $this->mobileVorwahl = $mobileVorwahl ?? null;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMobileVorwahl(): ?string
    {
        return $this->mobileVorwahl;
    }


    /**
     * @param string $mobile
     * @return Person
     */
    public function setMobile(?string $mobile): self
    {
        $this->mobile = $mobile ?? null;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMobile(): ?string
    {
        return $this->mobile;
    }


    /**
     * @param bool $mobileShow
     * @return Person
     */
    public function setMobileShow(bool $mobileShow): self
    {
        $this->mobileShow = $mobileShow;
        return $this;
    }

    /**
     * @return bool
     */
    public function getMobileShow(): bool
    {
        return $this->mobileShow;
    }


    /**
     * @return string|null
     */
    public function getMobileDisplay(): ?string
    {
        return join(
            ' ',
            array_filter(
                [
                    $this->mobileLand,
                    $this->mobileVorwahl,
                    $this->mobile,
                ]
            )
        );
    }


    /**
     * @param string $gewicht
     * @return Person
     */
    public function setGewicht(?string $gewicht): self
    {
        $this->gewicht = $gewicht ?? null;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getGewicht(): ?string
    {
        return $this->gewicht;
    }


    /**
     * @param string $gewichtEinheit
     * @return Person
     */
    public function setGewichtEinheit(string $gewichtEinheit): self
    {
        $this->gewichtEinheit = $gewichtEinheit;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getGewichtEinheit(): ?string
    {
        return $this->gewichtEinheit;
    }


    /**
     * @param bool $gewichtShow
     * @return Person
     */
    public function setGewichtShow(bool $gewichtShow): self
    {
        $this->gewichtShow = $gewichtShow;
        return $this;
    }

    /**
     * @return bool
     */
    public function getGewichtShow(): bool
    {
        return $this->gewichtShow;
    }


    /**
     * @param string $groesse
     * @return Person
     */
    public function setGroesse(?string $groesse): self
    {
        $this->groesse = $groesse ?? null;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getGroesse(): ?string
    {
        return $this->groesse;
    }


    /**
     * @param string $groesseEinheit
     * @return Person
     */
    public function setGroesseEinheit(string $groesseEinheit): self
    {
        $this->groesseEinheit = $groesseEinheit;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getGroesseEinheit(): ?string
    {
        return $this->groesseEinheit;
    }


    /**
     * @param bool $groesseShow
     * @return Person
     */
    public function setGroesseShow(bool $groesseShow): self
    {
        $this->groesseShow = $groesseShow;
        return $this;
    }

    /**
     * @return bool
     */
    public function getGroesseShow(): bool
    {
        return $this->groesseShow;
    }


    /**
     * @param string $krankenversicherung
     * @return Person
     */
    public function setKrankenversicherung(?string $krankenversicherung): self
    {
        $this->krankenversicherung = $krankenversicherung ?? null;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getKrankenversicherung(): ?string
    {
        return $this->krankenversicherung;
    }


    /**
     * @param bool $krankenversicherungShow
     * @return Person
     */
    public function setKrankenversicherungShow(bool $krankenversicherungShow): self
    {
        $this->krankenversicherungShow = $krankenversicherungShow;
        return $this;
    }

    /**
     * @return bool
     */
    public function getKrankenversicherungShow(): bool
    {
        return $this->krankenversicherungShow;
    }


    /**
     * @param string $versicherungsnummer
     * @return Person
     */
    public function setVersicherungsnummer(?string $versicherungsnummer): self
    {
        $this->versicherungsnummer = $versicherungsnummer ?? null;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVersicherungsnummer(): ?string
    {
        return $this->versicherungsnummer;
    }


    /**
     * @param bool $versicherungsnummerShow
     * @return Person
     */
    public function setVersicherungsnummerShow(bool $versicherungsnummerShow): self
    {
        $this->versicherungsnummerShow = $versicherungsnummerShow;
        return $this;
    }

    /**
     * @return bool
     */
    public function getVersicherungsnummerShow(): bool
    {
        return $this->versicherungsnummerShow;
    }


    /**
     * @param string $zusatzversicherung
     * @return Person
     */
    public function setZusatzversicherung(?string $zusatzversicherung): self
    {
        $this->zusatzversicherung = $zusatzversicherung ?? null;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getZusatzversicherung(): ?string
    {
        return $this->zusatzversicherung;
    }


    /**
     * @param bool $zusatzversicherungShow
     * @return Person
     */
    public function setZusatzversicherungShow(bool $zusatzversicherungShow): self
    {
        $this->zusatzversicherungShow = $zusatzversicherungShow;
        return $this;
    }

    /**
     * @return bool
     */
    public function getZusatzversicherungShow(): bool
    {
        return $this->zusatzversicherungShow;
    }


    /**
     * @param string $blutgruppe
     * @return Person
     */
    public function setBlutgruppe(string $blutgruppe): self
    {
        $this->blutgruppe = $blutgruppe;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBlutgruppe(): ?string
    {
        return $this->blutgruppe;
    }


    /**
     * @param bool $blutgruppeShow
     * @return Person
     */
    public function setBlutgruppeShow(bool $blutgruppeShow): self
    {
        $this->blutgruppeShow = $blutgruppeShow;
        return $this;
    }

    /**
     * @return bool
     */
    public function getBlutgruppeShow(): bool
    {
        return $this->blutgruppeShow;
    }


    /**
     * @param string $erkrankungen
     * @return Person
     */
    public function setErkrankungen(?string $erkrankungen): self
    {
        $this->erkrankungen = $erkrankungen ?? null;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getErkrankungen(): ?string
    {
        return $this->erkrankungen;
    }


    /**
     * @param bool $erkrankungenShow
     * @return Person
     */
    public function setErkrankungenShow(bool $erkrankungenShow): self
    {
        $this->erkrankungenShow = $erkrankungenShow;
        return $this;
    }

    /**
     * @return bool
     */
    public function getErkrankungenShow(): bool
    {
        return $this->erkrankungenShow;
    }


    /**
     * @param string $medikamente
     * @return Person
     */
    public function setMedikamente(?string $medikamente): self
    {
        $this->medikamente = $medikamente ?? null;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMedikamente(): ?string
    {
        return $this->medikamente;
    }


    /**
     * @param bool $medikamenteShow
     * @return Person
     */
    public function setMedikamenteShow(bool $medikamenteShow): self
    {
        $this->medikamenteShow = $medikamenteShow;
        return $this;
    }

    /**
     * @return bool
     */
    public function getMedikamenteShow(): bool
    {
        return $this->medikamenteShow;
    }


    /**
     * @param string $allergieen
     * @return Person
     */
    public function setAllergieen(?string $allergieen): self
    {
        $this->allergieen = $allergieen ?? null;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAllergieen(): ?string
    {
        return $this->allergieen;
    }


    /**
     * @param bool $allergieenShow
     * @return Person
     */
    public function setAllergieenShow(bool $allergieenShow): self
    {
        $this->allergieenShow = $allergieenShow;
        return $this;
    }

    /**
     * @return bool
     */
    public function getAllergieenShow(): bool
    {
        return $this->allergieenShow;
    }


    /**
     * @param bool $organspender
     * @return Person
     */
    public function setOrganspender(bool $organspender): self
    {
        $this->organspender = $organspender;
        return $this;
    }

    /**
     * @return bool
     */
    public function getOrganspender(): bool
    {
        return $this->organspender;
    }


    /**
     * @param bool $organspenderShow
     * @return Person
     */
    public function setOrganspenderShow(bool $organspenderShow): self
    {
        $this->organspenderShow = $organspenderShow;
        return $this;
    }

    /**
     * @return bool
     */
    public function getOrganspenderShow(): bool
    {
        return $this->organspenderShow;
    }


    /**
     * @param bool $patientenverf
     * @return Person
     */
    public function setPatientenverf(bool $patientenverf): self
    {
        $this->patientenverf = $patientenverf;
        return $this;
    }

    /**
     * @return bool
     */
    public function getPatientenverf(): bool
    {
        return $this->patientenverf;
    }


    /**
     * @param bool $patientenverfShow
     * @return Person
     */
    public function setPatientenverfShow(bool $patientenverfShow): self
    {
        $this->patientenverfShow = $patientenverfShow;
        return $this;
    }

    /**
     * @return bool
     */
    public function getPatientenverfShow(): bool
    {
        return $this->patientenverfShow;
    }


    /**
     * @param string $weitereangaben
     * @return Person
     */
    public function setWeitereangaben(?string $weitereangaben): self
    {
        $this->weitereangaben = $weitereangaben ?? null;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getWeitereangaben(): ?string
    {
        return $this->weitereangaben;
    }


    /**
     * @param bool $weitereangabenShow
     * @return Person
     */
    public function setWeitereangabenShow(bool $weitereangabenShow): self
    {
        $this->weitereangabenShow = $weitereangabenShow;
        return $this;
    }

    /**
     * @return bool
     */
    public function getWeitereangabenShow(): bool
    {
        return $this->weitereangabenShow;
    }




    /**
     * @return bool
     */
    public function getMedicalShow(): bool
    {
        return
            $this->blutgruppeShow ||
            $this->erkrankungenShow ||
            $this->medikamenteShow ||
            $this->allergieenShow;
    }


    /**
     * @return bool
     */
    public function getInsuranceShow(): bool
    {
        return
            $this->krankenversicherungShow ||
            $this->versicherungsnummerShow ||
            $this->zusatzversicherungShow;
    }


    /**
     * @param \DateTime $lastChangeDatum
     * @return Person
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
}
