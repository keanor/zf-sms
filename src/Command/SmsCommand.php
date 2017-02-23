<?php
namespace ZfSms\Command;

use ZfSms\Service\Exception\ServiceException;
use ZfSms\Service\SmsService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class ConsoleController
 * @package Sms\Controller
 */
class SmsCommand extends Command
{
    /**
     * @var SmsService
     */
    private $service;

    /**
     * SmsCommand constructor.
     * @param SmsService $service
     * @param string $name
     */
    public function __construct(SmsService $service, $name = 'sms')
    {
        parent::__construct($name);
        $this->service = $service;
    }

    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->setName('sms:send')
            ->setDescription('Send sms')
            ->setHelp('Команда отправляет SMS сообщение. Текст и номер телефона передаются в аргументах')
            ->addArgument('phone', InputArgument::REQUIRED, 'Номер телефона')
            ->addArgument('message', InputArgument::REQUIRED, 'Текст сообщения')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $phone = $input->getArgument('phone');
        $message = $input->getArgument('message');

        $io = new SymfonyStyle($input, $output);

        try {
            $this->getService()->send($phone, $message);

            $io->success(sprintf(
                'Сообщение "%s" успешно отправлено на номер "%s"',
                $message,
                $phone
            ));
        } catch (ServiceException $e) {
            $io->error($e->getMessage());
        }
    }

    /**
     * @return SmsService
     */
    public function getService()
    {
        return $this->service;
    }
}
