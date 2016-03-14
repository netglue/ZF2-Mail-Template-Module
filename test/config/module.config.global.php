<?php


return [

    'netglue_mail' => [

        'defaultSender' => 'me@example.com',
        'defaultSenderName' => 'Some Dude',

        /**
         * These headers will be set for every message sent
         */
        'defaultHeaders' => [
            'X-Mailer' => 'Netglue Mail Template Module',
        ],

        'messages' => [
            'contactUs' => [
                'template'   => 'tmpl/one',
                'subject'    => 'An Interesting Subject Line',
            ],
            'nullTemplate' => [
                'subject' => 'Foo',
            ],
            'gotLayout' => [
                'template'   => 'tmpl/one',
                'layout'     => 'layout/one',
            ],
        ],

    ],


    'view_manager' => [
        'template_map' => [
            'tmpl/one' => __DIR__ . '/../view/tmpl-one.phtml',
            'layout/one' => __DIR__ . '/../view/layout-one.phtml',
        ],
    ],

];
