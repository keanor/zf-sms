<?php
return [
    'service_manager' => [
        'factories' => [
            'SmsService' => 'ZfSms\Service\SmsServiceFactory',
            \ZfSms\ModuleOptions::class => \ZfSms\ModuleOptionsFactory::class,
            \ZfSms\Adapter\AdapterInterface::class => \ZfSms\Adapter\AbstractAdapterFactory::class,
        ],
    ],
];
