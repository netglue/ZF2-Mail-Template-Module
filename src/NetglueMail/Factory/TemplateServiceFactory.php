<?php

namespace NetglueMail\Factory;

use NetglueMail\TemplateService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Renderer\PhpRenderer;

class TemplateServiceFactory implements FactoryInterface
{
    /**
     * Return EmailTemplateService
     * @return EmailTemplateService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {

        $options  = $serviceLocator->get('NetglueMail\ModuleOptions');
        $viewManager = $serviceLocator->get('ViewManager');
        $renderer = $viewManager->getRenderer();

        return new TemplateService($options, $renderer);
    }
}
