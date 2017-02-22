<?php
/**
 * Created by PhpStorm.
 * User: keanor
 * Date: 02.03.16
 * Time: 18:56
 */
namespace ZfSms;

/**
 * Class Module
 * @package Sms
 */
class Module
{
    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
