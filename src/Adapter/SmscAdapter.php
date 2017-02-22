<?php
namespace ZfSms\Adapter;

use Zend\Json\Json;

/**
 * Class SmscAdapter
 * @package Sms\Adapter
 */
class SmscAdapter implements AdapterInterface
{
    /**
     * @var string
     */
    protected $login;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var array
     */
    protected static $messages = [
        1 => 'Ошибка в параметрах',
        2 => 'Неверный логин или пароль',
        3 => 'Недостаточно средств на счете Клиента',
        4 => 'IP-адрес временно заблокирован из-за частых ошибок в запросах',
        5 => 'Неверный формат даты',
        6 => 'Сообщение запрещено (по тексту или по имени отправителя)',
        7 => 'Неверный формат номера телефона',
        8 => 'Сообщение на указанный номер не может быть доставлено',
        9 => 'Отправка более одного одинакового запроса на передачу SMS-сообщения либо более пяти одинаковых запросов на получение стоимости сообщения в течение минуты',
    ];

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
        // Отправляем запрос
        $url = sprintf(
            'https://smsc.ru/sys/send.php?login=%s&psw=%s&phones=%s&mes=%s&fmt=3&charset=utf-8',
            $this->getLogin(),
            $this->getPassword(),
            urlencode($phone),
            urlencode($message)
        );
        $result = file_get_contents($url);

        // Пробуем декодировать json
        try {
            $responseData = Json::decode($result, Json::TYPE_ARRAY);
        } catch (\Exception $e) {
            throw new Exception\RuntimeException('Unable decode response: ' . $result);
        }

        // Проверяем ошибки принятия смс
        if (array_key_exists('error', $responseData)) {
            $reason = 'Unknown';
            if (array_key_exists('error_code', $responseData) &&
                array_key_exists($responseData['error_code'], self::$messages)) {
                $reason = self::$messages[$responseData['error_code']];
            }

            $description = $responseData['error'];
            throw new Exception\RuntimeException($reason . ': ' . $description);
        }
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
        if (!array_key_exists('login', $options)) {
            throw new Exception\InvalidArgumentException('Required option "login" not found!');
        }

        if (!array_key_exists('password', $options)) {
            throw new Exception\InvalidArgumentException('Required option "password" not found!');
        }

        $this->setLogin($options['login']);
        $this->setPassword($options['password']);
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
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
}
