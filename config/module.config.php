<?php

return [

    'netglue_mail' => [

        'defaultSender' => null,
        'defaultSenderName' => null,

        'defaultLayout' => null,

        /**
         * These headers will be set for every message sent
         */
        'defaultHeaders' => [
            'X-Mailer' => 'Netglue Mail Template Module',
        ],

        'messages' => [

            /**
             * An array of transactional email messages indexed by name
             * Each array contains configuration specifically for that message
             */
            'contactUs' => [

                // The template should be defined in the [view_manager][template_map] or path stack
                'template'   => 'my/view/script/name',

                // Subject for the message
                'subject'    => 'An Interesting Subject Line',

                // To override the default sender
                'sender'     => 'specific@example.com',
                'senderName' => 'Someone Specific',

                // Additional headers for this particular message, will be
                // merged with the default headers
                'headers'    => [
                    'X-Message-Type' => 'My Message Type'
                ],
            ],

        ],

    ],

    'view_manager' => [

    ],

];
