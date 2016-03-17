<?php

namespace NetglueMail\Controller\Plugin\Factory;

use NetglueMail\Controller\Plugin\MailDispatcher;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class MailDispatcherFactory implements FactoryInterface
{

    /**
     * Return MailDispatcher Plugin
     * @param  ServiceLocatorInterface $controllerPluginManager
     * @return MailDispatcher
     */
    public function createService(ServiceLocatorInterface $controllerPluginManager)
    {
        $serviceLocator = $controllerPluginManager->getServiceLocator();
        $dispatcher = $serviceLocator->get('NetglueMail\Dispatcher');

        return new MailDispatcher($dispatcher);
    }

}
