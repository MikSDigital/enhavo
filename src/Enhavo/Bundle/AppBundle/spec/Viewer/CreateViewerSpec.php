<?php

namespace spec\Enhavo\Bundle\AdminBundle\Viewer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Enhavo\Bundle\AdminBundle\Config\ConfigParser;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Router;

class CreateViewerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Enhavo\Bundle\AdminBundle\Viewer\CreateViewer');
    }

    function it_should_return_parameters(ConfigParser $configParser, ContainerInterface $container, Router $router)
    {
        $tabs = 'tabs';
        $formActionUrl = 'some_action_url';
        $formTemplate = 'form_template';
        $resource = 'resource';
        $form = 'form';
        $buttons = array();
        $parameters = array();

        $configParser->get('tabs')->willReturn($tabs);
        $configParser->get('form.template')->willReturn($formTemplate);
        $configParser->get('form.action')->willReturn('form_action_route');
        $configParser->get('buttons')->willReturn($buttons);
        $configParser->get('parameters')->willReturn($parameters);
        $router->generate('form_action_route')->willReturn($formActionUrl);
        $container->get('router')->willReturn($router);

        $this->setConfig($configParser);
        $this->setForm($form);
        $this->setContainer($container);

        $this->getParameters()->shouldHaveKeyWithValue('tabs', $tabs);
        $this->getParameters()->shouldHaveKeyWithValue('buttons', $buttons);
        $this->getParameters()->shouldHaveKeyWithValue('form_action', $formActionUrl);
        $this->getParameters()->shouldHaveKeyWithValue('form_template', $formTemplate);
        $this->getParameters()->shouldHaveKeyWithValue('form', $form);
        $this->getParameters()->shouldHaveKey('viewer');
    }
}