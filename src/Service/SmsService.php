<?php
/**
 * Created by PhpStorm.
 * User: keanor
 * Date: 02.03.16
 * Time: 18:56
 */
namespace ZfSms\Service;

use Doctrine\ORM\EntityManager;
use ZfSms\Adapter\AdapterInterface;
use ZfSms\Adapter\Exception\AdapterException;
use ZfSms\Entity\Sms;
use ZfSms\ModuleOptions;

/**
 * Сервис отправки СМС
 * @package Sms\Service
 */
class SmsService
{
    /**
     * @var \ZfSms\ModuleOptions
     */
    protected $options;

    /**
     * @var \ZfSms\Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * SmsService constructor.
     *
     * @param ModuleOptions $options
     * @param AdapterInterface $adapter
     */
    public function __construct(
        ModuleOptions $options,
        AdapterInterface $adapter
    ) {
        $this->options = $options;
        $this->adapter = $adapter;
    }

    /**
     * Отправить СМС
     *
     * @param string $phone
     * @param string $message
     */
    public function send($phone, $message)
    {
        try {
            $this->getAdapter()->send($phone, $message);
        } catch (AdapterException $e) {
            throw new Exception\ServiceException(sprintf(
                'Не удалось отправить sms на номер %s с текстом "%s"',
                $phone,
                $message
            ), $e->getCode(), $e);
        }
    }

    /**
     * @return \ZfSms\Adapter\AdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param \ZfSms\Adapter\AdapterInterface $adapter
     */
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @return \ZfSms\ModuleOptions
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param \ZfSms\ModuleOptions $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }
}
