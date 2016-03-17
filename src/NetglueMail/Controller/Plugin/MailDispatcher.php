<?php

namespace NetglueMail\Controller\Plugin;
use NetglueMail\Dispatcher;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
class MailDispatcher extends AbstractPlugin
{

    private $dispatcher;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Create and send the named message
     * @param  string                                $messageName  Configured message name
     * @param  array                                 $options      Message options override configured defaults
     * @param  array|\Zend\View\Model\ModelInterface $viewModel    View model to render templates with
     * @return \Zend\Mail\Message
     */
    public function __invoke($messageName, array $options = [], $viewModel = [])
    {
        return $this->dispatcher->send($messageName, $options, $viewModel);
    }

    public function getDispatcher()
    {
        return $this->dispatcher;
    }
}
