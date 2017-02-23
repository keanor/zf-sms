<?php
namespace ZfSms\Adapter;

/**
 * Адаптер для отправки СМС через шлюз sms48.ru
 *
 * @package Sms\Adapter
 */
class Sms48Adapter implements AdapterInterface
{
    /**
     * Имя пользователя
     *
     * @var string
     */
    protected $username;

    /**
     * Пароль
     *
     * @var string
     */
    protected $password;

    /**
     * Наименование отправителя
     *
     * @var string
     */
    protected $from;

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
        $phone = preg_replace('/^\+/', '', $phone);
        if (!preg_match('/^[0-9]{11}$/', $phone)) {
            throw new Exception\AdapterException('Incorrect phone number: ' . $phone);
        }

        $phone = iconv('UTF-8', 'cp1251', $phone);
        $password = iconv('UTF-8', 'cp1251', $this->getPassword());
        $login = iconv('UTF-8', 'cp1251', $this->getUsername());
        $msg = iconv('UTF-8', 'cp1251', $message);

        $hash = md5($login.md5($password).$phone);
        $hash = iconv('UTF-8', 'cp1251', $hash);

        $result = @file_get_contents($this->getBaseUrl() . '/send_sms.php?api_login=' . urlencode($login) . '&to=' . urlencode($phone) . '&msg=' . urlencode($msg) . '&from=' . urlencode($this->getFrom()) . '&check3=' . urlencode($hash));

        if ((($result != 1) && $result != 8) && (trim($result) !== 'sent for moderation')) {
            throw new Exception\AdapterException('Incorrect gate response: "' . $result . '"');
        }
    }

    /**
     * @return string
     */
    protected function getBaseUrl()
    {
        return 'http://sms48.ru';
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
        if (!array_key_exists('username', $options)) {
            throw new Exception\InvalidArgumentException('Option "username" not exists!');
        }
        $this->setUsername($options['username']);

        if (!array_key_exists('password', $options)) {
            throw new Exception\InvalidArgumentException('Option "password" not exists!');
        }
        $this->setPassword($options['password']);

        if (!array_key_exists('from', $options)) {
            throw new Exception\InvalidArgumentException('Option "from" not exists!');
        }
        $this->setFrom($options['from']);
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param string $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }
}
