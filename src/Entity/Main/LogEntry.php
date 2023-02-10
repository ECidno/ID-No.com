<?php
namespace App\Entity\Main;

/***********************************************************************
 *
 * (c) 2020 Frank Krüger <fkrueger@mp-group.net>, mp group GmbH
 *
 /*********************************************************************/

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Loggable\Entity\MappedSuperclass\AbstractLogEntry;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

/**
 * @ORM\Table(
 *    name="ext_log_entries",
 *    options={"row_format":"DYNAMIC"},
 *    indexes={
 *      @ORM\Index(name="log_class_lookup_idx", columns={"object_class"}),
 *      @ORM\Index(name="log_date_lookup_idx", columns={"logged_at"}),
 *      @ORM\Index(name="log_user_lookup_idx", columns={"username"}),
 *      @ORM\Index(name="log_version_lookup_idx", columns={"object_id", "object_class", "version"})
 *    }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\LogEntryRepository")
 *
 */
class LogEntry extends AbstractLogEntry
{
    /**
     * severity info
     */
    const SEVERITY_INFO = 0;

    /**
     * severity warning
     */
    const SEVERITY_WARN = 1;

    /**
     * severity error
     */
    const SEVERITY_ERROR = 2;

    /**
     * severity fatal
     */
    const SEVERITY_FATAL = 9;


    /**
     * constructor
     */
    public function __construct(
        $objectClass = null,
        $objectId = 0,
        $action = null,
        $username = null,
        $severity = self::SEVERITY_INFO,
        array $details = null
    ) {
        $this->objectClass = $objectClass;
        $this->objectId = $objectId;
        $this->action = $action;
        $this->username = $username;
        $this->severity = $severity;
        $this->details = $details;

        $this->version = 1;
        $this->loggedAt = new \DateTime();
    }


    /**
     * @var int|null
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     * @Groups({"read"})
     */
    protected $id;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="logged_at", type="datetime")
     * @Groups({"read"})
     * @Context({ DateTimeNormalizer::FORMAT_KEY = "d.m.Y H:i:s" })
     */
    protected $loggedAt;

    /**
     * @var string $action
     * @ORM\Column(type="string", length=32)
     * @Groups({"read"})
     */
    protected $action;

    /**
     * @var int $severity
     * @ORM\Column(type="integer", options={"default" : 0})
     * @Groups({"read"})
     */
    protected $severity = self::SEVERITY_INFO;

    /**
     * @var string $objectClass
     * @ORM\Column(name="object_class", type="string", length=255)
     * @Groups({"read"})
     */
    protected $objectClass;

    /**
     * @var string|null
     *
     * @ORM\Column(name="object_id", length=64, nullable=true)
     * @Groups({"read"})
     */
    protected $objectId;

    /**
     * @var string $data
     * @ORM\Column(length=255, nullable=true)
     * @Groups({"read"})
     */
    protected $username;

    /**
     * @var array|null
     * @ORM\Column(type="array", nullable=true)
     */
    protected $details;

    /**
     * @var array
     * @Groups({"read"})
     */
    private $operations;



    /**
     * Get id
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * Get severity
     *
     * @return int
     */
    public function getSeverity(): int
    {
        return (int)$this->severity;
    }

    /**
     * Set severity
     *
     * @param int
     */
    public function setSeverity($severity)
    {
        $this->severity = $severity;
    }

    /**
     * get severityClass
     *
     * @return string
     */
    public function getSeverityClass()
    {
        // switch severity
        switch ($this->severity) {
            // info
            case self::SEVERITY_INFO:
                $class = 'info';
                break;

            // warning
            case self::SEVERITY_WARN:
                $class = 'warning';
                break;

            // error
            case self::SEVERITY_ERROR:
            case self::SEVERITY_FATAL:
                $class = 'danger';
                break;
        }

        // return
        return $class;
    }


    /**
     * get dataArray
     *
     * @return array
     */
    public function getDataArray(): array
    {
        $dataArray = [];
        $data = $this->getData();

        // iterate data
        if (is_array($data) && count($data) > 0) {
            foreach ($data as $field => $item) {
                $type = gettype($item);
                $value = $item;

                // object
                if (gettype($item) === 'object') {
                    // datetime
                    if ($item instanceof \DateTime) {
                        $type = 'datetime';
                        $value = $item;

                    // dateinterval
                    } elseif ($item instanceof \DateInterval) {
                        $type = 'dateinterval';
                        $value = array_filter(
                            [
                                'year' => $item->format('y'),
                                'month' => $item->format('m'),
                                'day' => $item->format('d'),
                                'hour' => $item->format('h'),
                                'minute' => $item->format('i'),
                                'second' => $item->format('s'),
                            ]
                        );

                    // unset all other objects
                    } else {
                        $value = null;
                    }
                }

                // add to array if not empty
                if ($value !== null) {
                    $dataArray[$field] = [
                        'type' => $type,
                        'value' => $value,
                    ];
                }
            }
        }

        // return
        return $dataArray;
    }


    /**
     * @return array
     */
    public function getDetails(): ?array
    {
        return $this->details;
    }

    /**
     * @param ?array $details
     */
    public function setDetails(?array $details): void
    {
        $this->details = $details;
    }


    /**
     * @param array $operations
     * @return void
     */
    public function setOperations(array $operations): void
    {
        $this->operations = $operations;
    }

    /**
     * @return array
     */
    public function getOperations(): array
    {
        return $this->operations ?? [];
    }
}
