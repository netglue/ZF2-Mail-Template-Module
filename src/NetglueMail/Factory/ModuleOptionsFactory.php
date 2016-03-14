<?php

namespace NetglueMail\Factory;
use NetglueMail\ModuleOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ModuleOptionsFactory implements FactoryInterface
{
    /**
     * Return module Options
     * @param ServiceLocatorInterface $serviceLocator
     * @return
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $config = isset($config['netglue_mail']) ? $config['netglue_mail'] : [];
        return new ModuleOptions($config);
    }
}
