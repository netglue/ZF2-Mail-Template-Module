<?php

namespace NetglueMailTest;

use NetglueMailTest\bootstrap;
use NetglueMail\ModuleOptions;
use Zend\Test\PHPUnit\Controller\AbstractControllerTestCase;

class ModuleOptionsTest extends AbstractControllerTestCase
{

    public function setUp()
    {
        $this->setUseConsoleRequest(true);
        $this->setApplicationConfig( include __DIR__ . '/../TestConfig.php.dist' );
        parent::setUp();
    }

    public function testModuleOptionsCanBeRetrievedFromServiceLocator()
    {
        $sl = $this->getApplicationServiceLocator();
        $options = $sl->get('NetglueMail\ModuleOptions');

        $this->assertInstanceOf('NetglueMail\ModuleOptions', $options);

        return $options;
    }

    /**
     * @expectedException NetglueMail\Exception\InvalidArgumentException
     * @depends testModuleOptionsCanBeRetrievedFromServiceLocator
     */
    public function testGetMessageOptionThrowsExceptionForNonString(ModuleOptions $options)
    {
        $options->getMessageOption('foo', 123);
    }

    /**
     * @depends testModuleOptionsCanBeRetrievedFromServiceLocator
     */
    public function testGetMessageOptionReturnsDefaultGivenWhenUnset(ModuleOptions $options)
    {
        $this->assertSame('Foo', $options->getMessageOption('nullTemplate', 'subject', 'Bar'));
        $this->assertSame('MyDefault', $options->getMessageOption('nullTemplate', 'unknownOption', 'MyDefault'));
    }

}
