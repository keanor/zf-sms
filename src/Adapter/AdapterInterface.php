<?php
namespace ZfSms\Adapter;

/**
 * Class AdapterInterface
 * @package Sms\Adapter
 */
interface AdapterInterface
{
    /**
     * Отправка SMS
     *
     * @param string $phone
     * @param string $message
     *
     * @return void
     */
    public function send($phone, $message);

    /**
     * Установка параметров
     *
     * @param array $options
     *
     * @return void
     */
    public function setOptions(array $options);
}
