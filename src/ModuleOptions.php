<?php
namespace ZfSms;

use Zend\Stdlib\AbstractOptions;

/**
 * Class ModuleOptions
 * @package Sms
 */
class ModuleOptions extends AbstractOptions
{
    /**
     * Конфигурация адаптера
     *
     * @var array
     */
    protected $adapter = [ 'name' => 'noop' ];

    /**
     * Флаг, журналировать ли успешную отправку SMS
     *
     * @var boolean
     */
    protected $logSuccess = false;

    /**
     * @return array
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param array $adapter
     */
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @return boolean
     */
    public function isLogSuccess()
    {
        return $this->logSuccess;
    }

    /**
     * @param boolean $logSuccess
     */
    public function setLogSuccess($logSuccess)
    {
        $this->logSuccess = $logSuccess;
    }
}
