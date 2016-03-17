<?php

namespace NetglueMail;

use Zend\ModuleManager\Feature;

class Module implements
    Feature\ConfigProviderInterface,
    Feature\ServiceProviderInterface,
    Feature\ControllerPluginProviderInterface

{

    /**
     * Include/Return module configuration
     * @return array
     * @implements Feature\ConfigProviderInterface
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    /**
     * Return Service Config
     * @return array
     * @implements Feature\ServiceProviderInterface
     */
    public function getServiceConfig()
    {
        return [
            'factories' => [
                'NetglueMail\ModuleOptions'   => 'NetglueMail\Factory\ModuleOptionsFactory',
                'NetglueMail\TemplateService' => 'NetglueMail\Factory\TemplateServiceFactory',
                'NetglueMail\Dispatcher'      => 'NetglueMail\Factory\DispatcherFactory',
            ],
        ];
    }

    /**
     * Return controller plugin config
     * @return array
     */
    public function getControllerPluginConfig()
    {
        return [
            'factories' => [
                'NetglueMail\Controller\Plugin\MailDispatcher' => 'NetglueMail\Controller\Plugin\Factory\MailDispatcherFactory',
            ],
            'aliases' => [
                'mailDispatch' => 'NetglueMail\Controller\Plugin\MailDispatcher',
            ],
        ];
    }
}
