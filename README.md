# Email Templating Module

This module is intended to make sending template based transactional email easier using the ZF2 view layer.

## Install

Install with composer `netglue/mail-template-module` and add the module to your project using the name `'NetglueMail'`.

## Configuration

The module can be provided with some reasonable defaults so that you don’t have to setup every mail message with a global sender and it also requires access to a standard `Zend\Mail\Transport\TransportInterface` instance. This should be congfigured as a service name so you might have to create a factory to return your transport or use [SlmMail](https://github.com/juriansluiman/SlmMail) if you want to send with SES, SendGrid, MailGun et al.

### Setup the View Manager

Make sure you know and have setup the templates you want to send mail with. You can use layouts, globally or on a per message basis and use different layouts for text and HTML.

    'view_manager' => [
        'template_map' => [
            'email/layout/html'   => __DIR__ . '/path/to/html-layout.phtml',
            'email/layout/plain'  => __DIR__ . '/path/to/plain-layout.phtml',
            'email/contact/plain' => __DIR__ . '/path/to/plain.txt',
            'email/contact/html'  => __DIR__ . '/path/to/html.html',
        ],
    ]

### Configure Defaults

The defaults available are:
    
    'netglue_mail' => [
        
        'defaultSender'     => 'email@somewhere.com',
        'defaultSenderName' => 'Fred Bloggs',
        
        /**
         * Names of default layouts to apply to all messages
         */
        'defaultLayout' => 'some-html-layout',
        'textLayout'    => 'some-plaintext-layout',
        
        /**
         * Service Name of the transport to use to send generated messages
         * If unpecified, messages are sent using \Zend\Mail\Transport\InMemory
         */
        'transport' => null,
        
        /**
         * Associative array of message headers to apply to all messages
         */
        'defaultHeaders' => [
            'X-Mailer' => 'Netglue Mail Template Module',
        ],
    ];

### Configure named message types

Messages are an associative array keyed by the name of the 'message type'. Most of the message options can be related directly to methods available in `\Zend\Mail\Message` so the option `'to'` corresponds to `setTo()`

    'netglue_mail' => [
        'messages' => [
            'registrationConfirmation' => [
                'subject'       => 'Please confirm your email address',
                'template'      => 'some-view-script',
                'textTemplate'  => 'some-other-view-script',
                'layout'        => 'override-default-html-layout',
                'textLayout'    => 'override-default-text-layout',
                'from'          => ['sender@example.com' => 'My Real Name'],
                'replyTo'       => ['replieshere@example.com' => 'Alice'],
                'headers'       => ['X-MyHeaderName' => 'Some Value'],
            ],
        ],
    ];

### Dispatch Messages

When it's time to actually send a configured message, you will need to inject the `NetglueMail\Dispatcher` into your controller or service using Zend's service locator and simply call the `send()` method on the dispatcher:
    
    $view = [
        'someVariable'  => 'Foo',
        'otherVariable' => 'Bar',
    ];
    $messageOptions = [
        'to'      => ['recipient@example.com' => 'Fred'],
        'subject' => 'Override Default Configured Subject',
    ];
    $dispatcher->send('registrationConfirmation', $messageOptions, $view);

### Manipulate message before sending it

You can further manipulate a message before it is sent by using the `createMessage()` method of the dispatcher and finally sending it with `sendMessage()`
    
    $message = $dispatcher->createMessage('registrationConfirmation', $messageOptions, $view);
    $message->getHeaders()->addHeader(new \Zend\Mail\Header\GenericHeader('X-Foo', 'Bar'));
    
    $dispatcher->sendMessage($message);
    
### Triggered Events

Events are triggered before and after a message is sent via the transport. You can subscribe to them in the shared event manager thus:

    $sharedManager->attach('NetglueMail\Dispatcher', 'sendMessage', function($event) {
        $params = $event->getParams();
        var_dump('A message is about to be sent with the name: '.$params['messageName']);
    });
    
    $sharedManager->attach('NetglueMail\Dispatcher', 'sendMessage.post', function($event) {
        $params = $event->getParams();
        var_dump('A message was just sent with the name: '.$params['messageName']);
    });

The message object (`Zend\Mail\Message`) is available in the event parameter array keyed as 'message' and the message name keyed as 'messageName'
