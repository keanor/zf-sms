<?php
/**
 * Created by PhpStorm.
 * User: keanor
 * Date: 02.03.16
 * Time: 18:56
 */
namespace ZfSms\Service;

use ZfSms\Adapter\Exception\AdapterException;
use ZfSms\Entity\Sms;

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
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * Отправить СМС
     *
     * @param string $phone
     * @param string $message
     */
    public function send($phone, $message)
    {

        $sms = $this->createJournalEntry($phone, $message);
        try {
            $this->getAdapter()->send($phone, $message);
            $this->sendSuccess($sms);
        } catch (AdapterException $e) {
            $this->sendError($sms, $e);

            throw new Exception\ServiceException(sprintf(
                'Не удалось отправить sms на номер %s с текстом "%s"',
                $phone,
                $message
            ), $e->getCode(), $e);
        }
    }

    /**
     * Создаем запись в журнале
     *
     * @param string $phone
     * @param string $message
     *
     * @return Sms
     */
    protected function createJournalEntry($phone, $message)
    {
        $entity = new Sms();
        $entity->setPhone($phone);
        $entity->setMessage($message);

        return $entity;
    }

    /**
     * Помечаем запись в журнале как успешную, или удаляем
     *
     * @param Sms $entry
     */
    protected function sendSuccess(Sms $entry)
    {
        if ($this->getOptions()->isLogSuccess()) {
            $entry->setStatus(Sms::STATUS_SUCCESS);
        }
    }

    /**
     * Помечаем запись в журнале как ошибочную
     *
     * @param Sms $entry
     * @param \Exception $e
     */
    protected function sendError(Sms $entry, \Exception $e)
    {
        $sms = $entry;
        $sms->setStatus(Sms::STATUS_ERROR);
        $sms->setAdditional($e->__toString());
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

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }
}
