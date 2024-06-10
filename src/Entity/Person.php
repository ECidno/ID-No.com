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
use App\Entity\PassEntryCondition;
use App\Entity\PassEntryMedication;
use App\Entity\PassEntryAllergy;

/**
 * Person
 *
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 * @Gedmo\Loggable
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Person")
     * @ORM\JoinColumn(nullable=false, options={"default":0})
     */
     private $parent;

    /**
     * @var Nutzer
     * @ORM\ManyToOne(targetEntity="App\Entity\Nutzer", inversedBy="persons")
     */
    private $nutzer = null;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Contact", mappedBy="person", orphanRemoval="true")
     */
    private $contacts;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\PersonImages", mappedBy="person")
     */
    private $images;

    /**
     * @var bool
     */
    private $imageShow;

    /**
     * @var string
     * @ORM\Column(type="string", length=25)
     * @Gedmo\Versioned
     */
    private $status;

    /**
     * @var string
     * @ORM\Column(type="string", length=3)
     */
    private $sprache = 'de';

    /**
     * @var string
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Gedmo\Versioned
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=5)
     * @Gedmo\Versioned
     */
    private $anrede;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     * @Gedmo\Versioned
     * @Assert\NotBlank
     */
    private $vorname;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     * @Gedmo\Versioned
     * @Assert\NotBlank
     */
    private $nachname;

    /**
     * @var string
     * @Groups({"read"})
     */
    private $fullName;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     * @Gedmo\Versioned
     */
    private $strasse;

    /**
     * @var string
     * @ORM\Column(type="boolean", options={"default":"1"}))
     * @Gedmo\Versioned
     */
    private $strasseShow = 1;

    /**
     * @var string
     * @ORM\Column(type="string", length=13)
     * @Gedmo\Versioned
     */
    private $plz;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     * @Gedmo\Versioned
     */
    private $ort;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     * @Gedmo\Versioned
     */
    private $ortShow = 1;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     * @Gedmo\Versioned
     */
    private $zusatz;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     * @Gedmo\Versioned
     */
    private $zusatzShow = 1;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     * @Gedmo\Versioned
     */
    private $land;

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @Gedmo\Versioned
     */
    private $geburtsdatumTag;

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @Gedmo\Versioned
     */
    private $geburtsdatumMonat;

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @Gedmo\Versioned
     */
    private $geburtsdatumJahr;

    /**
     * @var string
     * @ORM\Column(type="string", length=6, options={"default":"+49"})
     * @Gedmo\Versioned
     */
    private $telefonLand = '+49';

    /**
     * @var string
     * @ORM\Column(type="string", length=10)
     * @Gedmo\Versioned
     */
    private $telefonVorwahl;

    /**
     * @var string
     * @ORM\Column(type="string", length=30)
     * @Gedmo\Versioned
     */
    private $telefon;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     * @Gedmo\Versioned
     */
    private $telefonShow = 1;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     * @Gedmo\Versioned
     */
    private $mobileShow = 1;

    /**
     * @var string
     * @ORM\Column(type="string", length=6, options={"default":"+49"})
     * @Gedmo\Versioned
     */
    private $mobileLand = '+49';

    /**
     * @var string
     * @ORM\Column(type="string", length=10)
     * @Gedmo\Versioned
     */
    private $mobileVorwahl;

    /**
     * @var string
     * @ORM\Column(type="string", length=30)
     * @Gedmo\Versioned
     */
    private $mobile;

    /**
     * @var string
     * @ORM\Column(type="string", length=10)
     * @Gedmo\Versioned
     */
    private $gewicht;

    /**
     * @var string
     * @ORM\Column(type="string", length=5, options={"default":"kg"})
     * @Gedmo\Versioned
     */
    private $gewichtEinheit = 'kg';

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     * @Gedmo\Versioned
     */
    private $gewichtShow = 1;

    /**
     * @var string
     * @ORM\Column(type="string", length=10)
     * @Gedmo\Versioned
     */
    private $groesse;

    /**
     * @var string
     * @ORM\Column(type="string", length=5, options={"default":"cm"})
     * @Gedmo\Versioned
     */
    private $groesseEinheit = 'cm';

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     * @Gedmo\Versioned
     */
    private $groesseShow = 1;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"0"}))
     * @Gedmo\Versioned
     */
    private $operationsActive = 1;

    /**
     * @var string
     * @ORM\Column(type="text", length=65535, nullable=true)
     * @Gedmo\Versioned
     */
    private $operations;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     * @Gedmo\Versioned
     */
    private $operationsShow = 1;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     * @Gedmo\Versioned
     */
    private $krankenversicherung;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     * @Gedmo\Versioned
     */
    private $krankenversicherungShow = 1;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     * @Gedmo\Versioned
     */
    private $versicherungsnummer;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     * @Gedmo\Versioned
     */
    private $versicherungsnummerShow = 1;

    /**
     * @var string
     * @ORM\Column(type="text", length=65535, nullable=true)
     * @Gedmo\Versioned
     */
    private $zusatzversicherung;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     * @Gedmo\Versioned
     */
    private $zusatzversicherungShow = 1;

    /**
     * @var string
     * @ORM\Column(type="string", length=30)
     * @Gedmo\Versioned
     */
    private $blutgruppe;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     * @Gedmo\Versioned
     */
    private $blutgruppeShow = 1;

    /**
     * @var string
     * @ORM\Column(type="text", length=16777215, nullable=true)
     * @Gedmo\Versioned
     */
    private $erkrankungen;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     * @Gedmo\Versioned
     */
    private $erkrankungenShow = 1;

    /**
     * @var string
     * @ORM\Column(type="text", length=65535, nullable=true)
     * @Gedmo\Versioned
     */
    private $medikamente;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     * @Gedmo\Versioned
     */
    private $medikamenteShow = 1;

    /**
     * @var string
     * @ORM\Column(type="text", length=65535, nullable=true)
     * @Gedmo\Versioned
     */
    private $allergieen;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     * @Gedmo\Versioned
     */
    private $allergieenShow = 1;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\PassEntryCondition", mappedBy="person", cascade={"all"}, orphanRemoval=true)
     */
    private $passEntryConditions;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     * @Gedmo\Versioned
     */
    private $conditionsActive = 1;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\PassEntryMedication", mappedBy="person", cascade={"all"}, orphanRemoval=true)
     */
    private $passEntryMedications;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     * @Gedmo\Versioned
     */
    private $medicationsActive = 1;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\PassEntryAllergy", mappedBy="person", cascade={"all"}, orphanRemoval=true)
     */
    private $passEntryAllergies;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     * @Gedmo\Versioned
     */
    private $allergiesActive = 1;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     * @Gedmo\Versioned
     */
    private $organspender = false;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     * @Gedmo\Versioned
     */
    private $organspenderShow = true;

    /**
     * @var string
     * @ORM\Column(type="text", length=65535, nullable=true)
     * @Gedmo\Versioned
     */
    private $organspenderComment;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     * @Gedmo\Versioned
     */
    private $patientenverf = false;

    /**
     * @var string
     * @ORM\Column(type="text", length=65535, nullable=true)
     * @Gedmo\Versioned
     */
    private $patientenverfComment;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     * @Gedmo\Versioned
     */
    private $patientenverfShow = 1;

    /**
     * @var string
     * @ORM\Column(type="text", length=65535, nullable=true)
     * @Gedmo\Versioned
     */
    private $weitereangaben;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     * @Gedmo\Versioned
     */
    private $weitereangabenShow = 1;

    /**
     * @var string
     * @ORM\Column(type="text", length=65535, nullable=true)
     * @Gedmo\Versioned
     */
    private $importantNote;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     * @Gedmo\Versioned
     */
    private $importantNoteShow = 1;

    /**
     * @var string
     * @ORM\Column(type="string", length=32)
     * @Gedmo\Versioned
     */
    private $reanimation;

    /**
     * @var string
     * @ORM\Column(type="text", length=65535, nullable=true)
     * @Gedmo\Versioned
     */
    private $reanimationComment;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     * @Gedmo\Versioned
     */
    private $reanimationShow = 1;

    /**
     * @var string
     * @ORM\Column(type="boolean", options={"default":"0"})
     * @Gedmo\Versioned
     */
    private $pacemaker = false;

    /**
     * @var string
     * @ORM\Column(type="text", length=65535, nullable=true)
     * @Gedmo\Versioned
     */
    private $pacemakerComment;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     * @Gedmo\Versioned
     */
    private $pacemakerShow = 1;

    /**
     * @var string
     * @ORM\Column(type="boolean", options={"default":"0"})
     * @Gedmo\Versioned
     */
    private $pregnancy = false;

    /**
     * @var string
     * @ORM\Column(type="text", length=65535, nullable=true)
     * @Gedmo\Versioned
     */
    private $pregnancyComment;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default":"1"}))
     * @Gedmo\Versioned
     */
    private $pregnancyShow = 1;

    /**
     * @Assert\Type("\DateTimeInterface")
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $registriertDatum;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Type("\DateTimeInterface")
     * @Gedmo\Timestampable(on="update")
     */
    private $lastChangeDatum;

    /**
     * @var int
     */
    private $itemCount;


    /**
     * constructor
     */
    public function __construct()
    {
        $this->contacts = new ArrayCollection();
        $this->images = new ArrayCollection();
        if (empty($this->getErkrankungen()) && empty($this->getPassEntryConditions())) {
            $this->setConditionsActive(false);
        }
        if (empty($this->getMedikamente()) && empty($this->getPassEntryMedications())) {
            $this->setMedicationsActive(false);
        }
        if (empty($this->getAllergieen()) && empty($this->getPassEntryAllergies())) {
            $this->setAllergiesActive(false);
        }
    }


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /*
     * @param ?Person $parent
     * @return Person
     */
    public function setParent(?Person $parent = null): self
    {
        $this->parent = $parent;
        return $this;
    }


    /*
     * @return ?Person
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
     * @param bool $imageShow
     * @return Items
     */
    public function setImageShow(bool $imageShow): self
    {
        if(!$this->getImages()->isEmpty()) {
            $this->getImages()
                ->first()
                ->setBildShow($imageShow);
        }
        $this->imageShow = $imageShow;
        return $this;
    }

    /**
     * @return bool
     */
    public function getImageShow(): bool
    {
        return $this->getImages()->isEmpty()
            ? false
            : $this->getImages()
                ->first()
                ->getBildShow();
    }

    /**
     * @return ?string
     */
    public function getImageSrc(): ?string
    {
        if($this->getImages()->isEmpty()) {
            return 'null';
        }

        // get file
        $imgEntity = $this->getImages()->first();
        $imgFile =  __DIR__.'/../../media/userimages/'.$imgEntity->getBild();

        // exists?
        if (!file_exists($imgFile)) {
            return  null;
        }

        // mime type
        $mimeTypes = new MimeTypes();
        $imgMimeType = $mimeTypes->guessMimeType($imgFile);

        // return
        return join(
            '',
            [
                'data:',
                $imgMimeType,
                ';base64,',
                base64_encode(
                    file_get_contents($imgFile)
                )
            ]
        );
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
     * @param ?string $sprache
     * @return Person
     */
    public function setSprache(?string $sprache): self
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
     * @param ?string $email
     * @return Person
     */
    public function setEmail(?string $email): self
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
    public function setAnrede(?string $anrede): self
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
        return html_entity_decode($this->vorname);
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
                    html_entity_decode($this->vorname),
                    html_entity_decode($this->nachname),
                ]
            )
        );
    }

    /**
     * @return ?string
     */
    public function getFullAddress(): ?string
    {
        // address
        switch ($this->anrede) {
            case 'm':
                $anrede = 'Lieber'; # @TODO translate!
                break;
            case 'w':
                $anrede = 'Liebe'; # @TODO translate!
                break;
            default:
                $anrede = 'Guten Tag'; # @TODO translate!
                break;
        }

        // return
        return join(
            ' ',
            array_filter(
                [
                    $anrede,
                    $this->getFullName()
                ]
            )
        );
    }


    /**
     * @param string $strasse
     * @return Person
     */
    public function setStrasse(?string $strasse): self
    {
        $this->strasse = $strasse;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStrasse(): ?string
    {
        return html_entity_decode($this->strasse);
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
    public function setPlz(?string $plz): self
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
        return html_entity_decode($this->ort);
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
        return html_entity_decode($this->zusatz);
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
    public function setGeburtsdatumTag(?int $geburtsdatumTag): self
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
    public function setGeburtsdatumMonat(?int $geburtsdatumMonat): self
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
    public function setGeburtsdatumJahr(?int $geburtsdatumJahr): self
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
        if (is_null($this->geburtsdatumJahr) || is_null($this->geburtsdatumMonat) || is_null($this->geburtsdatumTag)) {
            return null;
        }

        $dob = new \DateTimeImmutable(
            join(
                '-',
                [
                    $this->geburtsdatumJahr,
                    $this->geburtsdatumMonat,
                    $this->geburtsdatumTag,
                ]
            ),
            new \DateTimeZone('UTC')
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
     * @param string $operations
     * @return Person
     */
    public function setOperations(?string $operations): self
    {
        $this->operations = $operations ?? null;
        return $this;
    }

    /**
     * @return ?string
     */
    public function getOperations(): ?string
    {
        return html_entity_decode($this->operations);
    }

    /**
     * @param bool $operationsShow
     * @return Person
     */
    public function setOperationsShow(bool $operationsShow): self
    {
        $this->operationsShow = $operationsShow;
        return $this;
    }

    /**
     * @return bool
     */
    public function getOperationsShow(): bool
    {
        return $this->operationsShow;
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
        return html_entity_decode($this->krankenversicherung);
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
        return html_entity_decode($this->zusatzversicherung);
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
        return html_entity_decode($this->erkrankungen);
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
        return html_entity_decode($this->medikamente);
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
        return html_entity_decode($this->allergieen);
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
        return $this->organspender ?? false;
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
        return $this->patientenverf ?? false;
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
        return html_entity_decode($this->weitereangaben);
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
     * @param \DateTimeInterface $registriertDatum
     * @return self
     */
    public function setRegistriertDatum(\DateTimeInterface $registriertDatum): self
    {
        $this->registriertDatum = $registriertDatum;
        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getRegistriertDatum(): \DateTimeInterface
    {
        return $this->registriertDatum;
    }

    /**
     * @param \DateTimeInterface $lastChangeDatum
     * @return Person
     */
    public function setLastChangeDatum(\DateTimeInterface $lastChangeDatum): self
    {
        $this->lastChangeDatum = $lastChangeDatum;
        return $this;
    }

    /**
     * @return ?\DateTimeInterface|null
     */
    public function getLastChangeDatum(): ?\DateTimeInterface
    {
        return $this->lastChangeDatum;
    }


    /**
     * @param int $itemCount
     * @return Person
     */
    public function setItemCount(?int $itemCount): self
    {
        $this->itemCount = $itemCount ?? 0;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getItemCount(): ?int
    {
        return html_entity_decode($this->itemCount);
    }

    /**
     * @param ?Collection $passEntryConditions
     * @return Person
     */
    public function setPassEntryConditions(?Collection $passEntryConditions): self
    {
        $this->passEntryConditions = $passEntryConditions;
        return $this;
    }

    /**
     * @return ?Collection|PassEntryCondition[]
     */
    public function getPassEntryConditions(): ?Collection
    {
        return $this->passEntryConditions;
    }

    /**
     * @param PassEntryCondition $passEntryCondition
     * @return Person
     */
    public function addPassEntryCondition(PassEntryCondition $passEntryCondition): self
    {
        if (!$this->passEntryConditions->contains($passEntryCondition)) {
            $this->passEntryConditions[] = $passEntryCondition;
            $passEntryCondition->setPerson($this);
        }
        return $this;
    }

    /**
     * @param PassEntryCondition $passEntryCondition
     * @return Person
     */
    public function removePassEntryCondition(PassEntryCondition $passEntryCondition): self
    {
        if ($this->passEntryConditions->contains($passEntryCondition)) {
            $this->passEntryConditions->removeElement($passEntryCondition);
        }
        return $this;
    }

    /**
     * @param ?Collection $passEntryMedications
     * @return Person
     */
    public function setPassEntryMedications(?Collection $passEntryMedications): self
    {
        $this->passEntryMedications = $passEntryMedications;
        return $this;
    }

    /**
     * @return ?Collection|PassEntryMedication[]
     */
    public function getPassEntryMedications(): ?Collection
    {
        return $this->passEntryMedications;
    }

    /**
     * @param PassEntryMedication $passEntryMedication
     * @return Person
     */
    public function addPassEntryMedication(PassEntryMedication $passEntryMedication): self
    {
        if (!$this->passEntryMedications->contains($passEntryMedication)) {
            $this->passEntryMedications[] = $passEntryMedication;
            $passEntryMedication->setPerson($this);
        }
        return $this;
    }

    /**
     * @param PassEntryMedication $passEntryMedication
     * @return Person
     */
    public function removePassEntryMedication(PassEntryMedication $passEntryMedication): self
    {
        if ($this->passEntryMedications->contains($passEntryMedication)) {
            $this->passEntryMedications->removeElement($passEntryMedication);
        }
        return $this;
    }

    /**
     * @param ?Collection $passEntryAllergies
     * @return Person
     */
    public function setPassEntryAllergies(?Collection $passEntryAllergies): self
    {
        $this->passEntryAllergies = $passEntryAllergies;
        return $this;
    }

    /**
     * @return ?Collection|PassEntryAllergy[]
     */
    public function getPassEntryAllergies(): ?Collection
    {
        return $this->passEntryAllergies;
    }

    /**
     * @param PassEntryAllergy $passEntrAllergy
     * @return Person
     */
    public function addPassEntryAllergy(PassEntryAllergy $passEntryAllergy): self
    {
        if (!$this->passEntryAllergies->contains($passEntryAllergy)) {
            $this->passEntryAllergies[] = $passEntryAllergy;
            $passEntryAllergy->setPerson($this);
        }
        return $this;
    }

    /**
     * @param PassEntryAllergy $passEntryAllergy
     * @return Person
     */
    public function removePassEntryAllergy(PassEntryAllergy $passEntryAllergy): self
    {
        if ($this->passEntryAllergies->contains($passEntryAllergy)) {
            $this->passEntryAllergies->removeElement($passEntryAllergy);
        }
        return $this;
    }

    /**
     * Get the value of operationsActive
     *
     * @return  bool
     */ 
    public function getOperationsActive(): bool
    {
        return $this->operationsActive;
    }

    /**
     * Set the value of operationsActive
     *
     * @param  bool  $operationsActive
     *
     * @return  self
     */ 
    public function setOperationsActive(bool $operationsActive): self
    {
        $this->operationsActive = $operationsActive;

        return $this;
    }

    /**
     * Get the value of patientenverfComment
     *
     * @return  ?string
     */ 
    public function getPatientenverfComment(): ?string
    {
        return $this->patientenverfComment;
    }

    /**
     * Set the value of patientenverfComment
     *
     * @param  string  $patientenverfComment
     *
     * @return  self
     */ 
    public function setPatientenverfComment(?string $patientenverfComment): self
    {
        $this->patientenverfComment = $patientenverfComment;

        return $this;
    }

    /**
     * Get the value of importantNote
     *
     * @return  string
     */ 
    public function getImportantNote(): ?string
    {
        return $this->importantNote;
    }

    /**
     * Set the value of importantNote
     *
     * @param  string  $importantNote
     *
     * @return  self
     */ 
    public function setImportantNote(?string $importantNote): self
    {
        $this->importantNote = $importantNote;

        return $this;
    }

    /**
     * Get the value of importantNoteShow
     *
     * @return  bool
     */ 
    public function getImportantNoteShow(): bool
    {
        return $this->importantNoteShow;
    }

    /**
     * Set the value of importantNoteShow
     *
     * @param  bool  $importantNoteShow
     *
     * @return  self
     */ 
    public function setImportantNoteShow(bool $importantNoteShow): self
    {
        $this->importantNoteShow = $importantNoteShow;

        return $this;
    }

    /**
     * Get the value of pacemaker
     *
     * @return  bool
     */ 
    public function getPacemaker(): bool
    {
        return $this->pacemaker;
    }

    /**
     * Set the value of pacemaker
     *
     * @param  bool  $pacemaker
     *
     * @return  self
     */ 
    public function setPacemaker(bool $pacemaker): self
    {
        $this->pacemaker = $pacemaker;

        return $this;
    }

    /**
     * Get the value of pacemakerComment
     *
     * @return  string
     */ 
    public function getPacemakerComment(): ?string
    {
        return $this->pacemakerComment;
    }

    /**
     * Set the value of pacemakerComment
     *
     * @param  string  $pacemakerComment
     *
     * @return  self
     */ 
    public function setPacemakerComment(?string $pacemakerComment): self
    {
        $this->pacemakerComment = $pacemakerComment;

        return $this;
    }

    /**
     * Get the value of pacemakerShow
     *
     * @return  bool
     */ 
    public function getPacemakerShow(): bool
    {
        return $this->pacemakerShow;
    }

    /**
     * Set the value of pacemakerShow
     *
     * @param  bool  $pacemakerShow
     *
     * @return  self
     */ 
    public function setPacemakerShow(bool $pacemakerShow): self
    {
        $this->pacemakerShow = $pacemakerShow;

        return $this;
    }

    /**
     * Get the value of pregnancy
     *
     * @return  bool
     */ 
    public function getPregnancy(): bool
    {
        return $this->pregnancy;
    }

    /**
     * Set the value of pregnancy
     *
     * @param  bool  $pregnancy
     *
     * @return  self
     */ 
    public function setPregnancy(bool $pregnancy): self
    {
        $this->pregnancy = $pregnancy;

        return $this;
    }

    /**
     * Get the value of pregnancyComment
     *
     * @return  string
     */ 
    public function getPregnancyComment(): ?string
    {
        return $this->pregnancyComment;
    }

    /**
     * Set the value of pregnancyComment
     *
     * @param  string  $pregnancyComment
     *
     * @return  self
     */ 
    public function setPregnancyComment(?string $pregnancyComment): self
    {
        $this->pregnancyComment = $pregnancyComment;

        return $this;
    }

    /**
     * Get the value of pregnancyShow
     *
     * @return  bool
     */ 
    public function getPregnancyShow(): bool
    {
        return $this->pregnancyShow;
    }

    /**
     * Set the value of pregnancyShow
     *
     * @param  bool  $pregnancyShow
     *
     * @return  self
     */ 
    public function setPregnancyShow(bool $pregnancyShow): self
    {
        $this->pregnancyShow = $pregnancyShow;

        return $this;
    }

    /**
     * Get the value of conditionsActive
     *
     * @return  bool
     */ 
    public function getConditionsActive(): bool
    {
        return $this->conditionsActive;
    }

    /**
     * Set the value of conditionsActive
     *
     * @param  bool  $conditionsActive
     *
     * @return  self
     */ 
    public function setConditionsActive(bool $conditionsActive): self
    {
        $this->conditionsActive = $conditionsActive;

        return $this;
    }

    /**
     * Get the value of medicationsActive
     *
     * @return  bool
     */ 
    public function getMedicationsActive(): bool
    {
        return $this->medicationsActive;
    }

    /**
     * Set the value of medicationsActive
     *
     * @param  bool  $medicationsActive
     *
     * @return  self
     */ 
    public function setMedicationsActive(bool $medicationsActive): self
    {
        $this->medicationsActive = $medicationsActive;

        return $this;
    }

    /**
     * Get the value of allergiesActive
     *
     * @return  bool
     */ 
    public function getAllergiesActive(): bool
    {
        return $this->allergiesActive;
    }

    /**
     * Set the value of allergiesActive
     *
     * @param  bool  $allergiesActive
     *
     * @return  self
     */ 
    public function setAllergiesActive(bool $allergiesActive): self
    {
        $this->allergiesActive = $allergiesActive;

        return $this;
    }

    /**
     * Get the value of reanimation
     *
     * @return  string
     */ 
    public function getReanimation(): ?string
    {
        return $this->reanimation;
    }

    /**
     * Set the value of reanimation
     *
     * @param  string  $reanimation
     *
     * @return  self
     */ 
    public function setReanimation(?string $reanimation): self
    {
        $this->reanimation = $reanimation;

        return $this;
    }

    /**
     * Get the value of reanimationComment
     *
     * @return  string
     */ 
    public function getReanimationComment(): ?string
    {
        return $this->reanimationComment;
    }

    /**
     * Set the value of reanimationComment
     *
     * @param  string  $reanimationComment
     *
     * @return  self
     */ 
    public function setReanimationComment(?string $reanimationComment): self
    {
        $this->reanimationComment = $reanimationComment;

        return $this;
    }

    /**
     * Get the value of reanimationShow
     *
     * @return  bool
     */ 
    public function getReanimationShow(): bool
    {
        return $this->reanimationShow;
    }

    /**
     * Set the value of reanimationShow
     *
     * @param  bool  $reanimationShow
     *
     * @return  self
     */ 
    public function setReanimationShow(bool $reanimationShow): self
    {
        $this->reanimationShow = $reanimationShow;

        return $this;
    }

    /**
     * Get the value of organspenderComment
     *
     * @return  string
     */ 
    public function getOrganspenderComment(): ?string
    {
        return $this->organspenderComment;
    }

    /**
     * Set the value of organspenderComment
     *
     * @param  string  $organspenderComment
     *
     * @return  self
     */ 
    public function setOrganspenderComment(?string $organspenderComment): self
    {
        $this->organspenderComment = $organspenderComment;

        return $this;
    }
}
