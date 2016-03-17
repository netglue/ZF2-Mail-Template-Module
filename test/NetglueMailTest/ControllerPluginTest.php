<?php

namespace NetglueMailTest;


use Zend\Test\PHPUnit\Controller\AbstractControllerTestCase;
use NetglueMail\Controller\Plugin\MailDispatcher;

class ControllerPluginTest extends AbstractControllerTestCase
{

    public function setUp()
    {
        $this->setUseConsoleRequest(true);
        $this->setApplicationConfig( include __DIR__ . '/../TestConfig.php.dist' );
        parent::setUp();
    }

    public function testPluginCanBeRetrievedFromPluginManager()
    {
        $sl = $this->getApplicationServiceLocator();
        $manager = $sl->get('ControllerPluginManager');
        $plugin = $manager->get('dispatchEmail');
        $this->assertInstanceOf('NetglueMail\Controller\Plugin\MailDispatcher', $plugin);

        $dispatcher = $plugin->getDispatcher();
        $this->assertInstanceOf('NetglueMail\Dispatcher', $dispatcher);

        return $plugin;
    }


    public function testInvokingPluginProxiesToService()
    {
        $dispatcher  = $this->getMock('NetglueMail\Dispatcher', [], [], 'DispatcherMock', false);
        $plugin      = new MailDispatcher($dispatcher);
        $options     = array('foo' => 'bar');
        $variables   = array('baz' => 'bat');
        $messageName = 'viewVariables';
        $dispatcher->expects($this->once())
                   ->method('send')
                   ->with($this->equalTo($messageName),
                          $this->equalTo($options),
                          $this->equalTo($variables));
        $plugin($messageName, $options, $variables);

    }

}
