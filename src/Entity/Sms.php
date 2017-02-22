<?php
namespace ZfSms\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Sms
 *
 * @ORM\Table(name="sms_journal")
 * @ORM\Entity
 */
class Sms
{
    const STATUS_CREATED = 'created';
    const STATUS_SUCCESS = 'success';
    const STATUS_ERROR = 'error';

    /**
     * Внутренний ID
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * Дата отправки сообщения
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * Номер телефона
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $phone;

    /**
     * Текст сообщения
     *
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $message;

    /**
     * Статус
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $status = self::STATUS_CREATED;

    /**
     * Доп. информация
     *
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $additional;

    /**
     * Sms constructor.
     */
    public function __construct()
    {
        $this->created = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getAdditional()
    {
        return $this->additional;
    }

    /**
     * @param string $additional
     */
    public function setAdditional($additional)
    {
        $this->additional = $additional;
    }
}
