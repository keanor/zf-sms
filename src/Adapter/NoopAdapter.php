<?php
namespace ZfSms\Adapter;

/**
 * Адаптер заглушка, не делает ничего. Просто не фаталит при вызове.
 *
 * @package Sms\Adapter
 */
class NoopAdapter implements AdapterInterface
{
    /**
     * Отправка SMS
     *
     * @param string $phone
     * @param string $message
     *
     * @return void
     */
    public function send($phone, $message)
    {
    }

    /**
     * Установка параметров
     *
     * @param array $options
     *
     * @return void
     */
    public function setOptions(array $options)
    {
    }
}
