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
            return null;
        }
        $viewModel->setTemplate($tmpl);
        return $this->renderToLayout(
            $messageName,
            $this->renderer->render($viewModel)
        );
    }

    /**
     * Render a plain-text template with the given name and model
     * @param string $messageName
     * @param ModelInterface $viewModel
     * @return string
     * @throws Exception\UnknownTemplateException if no template has been set
     */
    public function renderTextTemplate($messageName, ModelInterface $viewModel = null)
    {
        $viewModel = (null === $viewModel) ? new ViewModel : $viewModel;
        $tmpl = $this->getTextTemplateByName($messageName);
        if (null === $tmpl) {
            return null;
        }
        $viewModel->setTemplate($tmpl);
        return $this->renderToTextLayout(
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
     * Render given text to the template specified for the given message or to the default layout (or none at all)
     * @param string $messageName
     * @param string $text
     * @return string
     */
    private function renderToTextLayout($messageName, $text)
    {
        $layout = $this->getTextLayoutByName($messageName);
        if (!empty($layout)) {
            $text = $this->renderer->render($layout, ['content' => $text]);
        }

        return $text;
    }

    /**
     * Return the layout template that should be used
     * @param string $messageName
     * @return string|null
     */
    public function getLayoutByName($messageName)
    {
        $layout = $this->options->getDefaultLayout();
        return $this->options->getMessageOption($messageName, 'layout', $layout);
    }

    /**
     * Return the layout template that should be used
     * @param string $messageName
     * @return string|null
     */
    public function getTextLayoutByName($messageName)
    {
        $layout = $this->options->getTextLayout();
        return $this->options->getMessageOption($messageName, 'textLayout', $layout);
    }

    /**
     * Return template name for specific message
     * @param string $messageName
     * @return string|null
     */
    public function getTemplateByName($messageName)
    {
        return $this->options->getMessageOption($messageName, 'template');
    }


    /**
     * Return text template name for specific message
     * @param string $messageName
     * @return string|null
     */
    public function getTextTemplateByName($messageName)
    {
        return $this->options->getMessageOption($messageName, 'textTemplate');
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
