<?php

namespace NetglueMailTest;

use NetglueMailTest\bootstrap;
use NetglueMail\TemplateService;
use Zend\View\Renderer\PhpRenderer;
use Zend\Test\PHPUnit\Controller\AbstractControllerTestCase;
class TemplateServiceTest extends AbstractControllerTestCase
{

    public function setUp()
    {
        $this->setUseConsoleRequest(true);
        $this->setApplicationConfig( include __DIR__ . '/../TestConfig.php.dist' );
        parent::setUp();
        //$this->dispatch('/');
    }

    public function testTemplateServiceCanBeRetrievedFromServiceLocator()
    {
        $sl = $this->getApplicationServiceLocator();
        $service = $sl->get('NetglueMail\TemplateService');

        $this->assertInstanceOf('NetglueMail\TemplateService', $service);
        $this->assertInstanceOf('NetglueMail\ModuleOptions', $service->getOptions());
        $this->assertInstanceOf('Zend\View\Renderer\RendererInterface', $service->getRenderer());

        return $service;
    }

    /**
     * @depends testTemplateServiceCanBeRetrievedFromServiceLocator
     */
    public function testRendererCanBeOverridden(TemplateService $service)
    {
        $renderer = new PhpRenderer;
        $original = $service->getRenderer();
        $service->setRenderer($renderer);
        $this->assertSame($renderer, $service->getRenderer());
        $service->setRenderer($original);
        $this->assertSame($original, $service->getRenderer());
    }

    /**
     * @depends testTemplateServiceCanBeRetrievedFromServiceLocator
     */
    public function testGetTemplateByName(TemplateService $service)
    {
        $this->assertSame('tmpl/one', $service->getTemplateByName('contactUs'));
        $this->assertNull($service->getTemplateByName('unknown-message-type'), 'Template should be null for unknown messages');
        $this->assertNull($service->getTemplateByName('nullTemplate'), 'Template should be null when one has not been set');

        return $service;
    }

    /**
     * @depends testTemplateServiceCanBeRetrievedFromServiceLocator
     */
    public function testGetTextTemplateByName(TemplateService $service)
    {
        $this->assertSame('tmpl/text', $service->getTextTemplateByName('contactUs'));
        $this->assertNull($service->getTextTemplateByName('unknown-message-type'), 'Template should be null for unknown messages');
        $this->assertNull($service->getTextTemplateByName('nullTemplate'), 'Template should be null when one has not been set');

        return $service;
    }

    /**
     * @depends testGetTemplateByName
     */
    public function testRenderTemplate(TemplateService $service)
    {
        $html = $service->renderTemplate('contactUs');
        $this->assertInternalType('string', $html);
        $this->assertContains('&amp;', $html);
    }

    /**
     * @depends testGetTextTemplateByName
     */
    public function testRenderTextTemplate(TemplateService $service)
    {
        $text = $service->renderTextTemplate('contactUs');
        $this->assertInternalType('string', $text);
        $this->assertContains('I’m a Text Template', $text);
    }

    /**
     * @depends testGetTemplateByName
     */
    public function testRenderTemplateReturnsNullForNullTemplate(TemplateService $service)
    {
        $this->assertNull($service->renderTemplate('nullTemplate'));
    }

    /**
     * @depends testGetTemplateByName
     */
    public function testRenderTextTemplateReturnsNullForNullTemplate(TemplateService $service)
    {
        $this->assertNull($service->renderTextTemplate('nullTemplate'));
    }

    /**
     * @depends testGetTemplateByName
     */
    public function testRenderLayout(TemplateService $service)
    {
        $html = $service->renderTemplate('gotLayout');
        $this->assertInternalType('string', $html);
        $this->assertContains('[layoutStart]', $html);
        $this->assertContains('&amp;', $html);
    }

    /**
     * @depends testGetTextTemplateByName
     */
    public function testRenderTextLayout(TemplateService $service)
    {
        $text = $service->renderTextTemplate('gotLayout');
        $this->assertInternalType('string', $text);
        $this->assertContains('[Text Layout Start]', $text);
        $this->assertContains('I’m a Text Template', $text);
    }
}
