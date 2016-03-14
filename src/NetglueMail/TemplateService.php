<?php

namespace NetglueMail;

use Zend\View\Model\ModelInterface;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\RendererInterface;


class TemplateService
{

    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var ModuleOptions
     */
    private $options;

    /**
     * Construct
     * @param ModuleOptions     $options
     * @param RendererInterface $renderer
     */
    public function __construct(ModuleOptions $options, RendererInterface $renderer)
    {
        $this->options  = $options;
        $this->renderer = $renderer;
    }

    /**
     * Render the given message type with the given view model
     * @param string $messageName
     * @param ModelInterface $viewModel
     * @return string
     * @throws Exception\UnknownTemplateException if no template has been set
     */
    public function renderTemplate($messageName, ModelInterface $viewModel = null)
    {
        $viewModel = (null === $viewModel) ? new ViewModel : $viewModel;
        $tmpl = $this->getTemplateByName($messageName);
        if (null === $tmpl) {
            throw new Exception\UnknownTemplateException(sprintf(
                'The template for the message type %s has not been set',
                $tmpl
            ));
        }
        $viewModel->setTemplate($tmpl);
        return $this->renderToLayout(
            $messageName,
            $this->renderer->render($viewModel)
        );
    }

    /**
     * Render given markup to the template specified for the given message or to the default layout (or none at all)
     * @param string $messageName
     * @param string $markup
     * @return string
     */
    private function renderToLayout($messageName, $markup)
    {
        $layout = $this->getLayoutByName($messageName);
        if (!empty($layout)) {
            $markup = $this->renderer->render($layout, ['content' => $markup]);
        }

        return $markup;
    }

    /**
     * Return the layout template that should be used
     * @param string $messageName
     * @return string|null
     */
    public function getLayoutByName($messageName)
    {
        $layout = $this->options->getDefaultLayout();
        if ($config = $this->options->getMessageConfig($messageName)) {
            if (isset($config['layout'])) {
                $layout = $config['layout'];
            }
        }

        return $layout;
    }

    /**
     * Return template name for specific message
     * @param string $messageName
     * @return string|null
     */
    public function getTemplateByName($messageName)
    {
        if ($config = $this->options->getMessageConfig($messageName)) {
            if (isset($config['template'])) {
                return $config['template'];
            }
        }

        return null;
    }

    /**
     * @return ModuleOptions
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return RendererInterface
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * @param  RendererInterface $renderer
     * @return void
     */
    public function setRenderer(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }


}
