<?php
return [
    'service_manager' => [
        'factories' => [
            'SmsService' => 'Sms\Service\SmsServiceFactory',
        ],
    ],
    'doctrine' => [
        'driver' => [
            'sms_entities' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => [
                    __DIR__ . '/../src/Entity'
                ],
            ],
            'orm_default' => [
                'drivers' => [
                    'Sms\Entity' => 'sms_entities',
                ],
            ],
        ],
    ],
];
