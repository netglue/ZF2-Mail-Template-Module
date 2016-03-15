<?php

namespace NetglueMail\Factory;

use NetglueMail\Dispatcher;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mail\Transport\InMemory;

class DispatcherFactory implements FactoryInterface
{
    /**
     * Return Dispatcher
     * @param ServiceLocatorInterface $serviceLocator
     * @return Dispatcher
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $templateService = $serviceLocator->get('NetglueMail\TemplateService');
        $options = $serviceLocator->get('NetglueMail\ModuleOptions');

        $transportName = $options->getTransport();
        $transport = (null === $transportName) ?
            new InMemory :
            $serviceLocator->get($transportName);

        return new Dispatcher($transport, $templateService);
    }
}
