<?php
/**
 * EventDispatcher.php
 *
 * @since 26/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Controller;

use Enhavo\Bundle\AppBundle\Event\PreviewEvent;
use Sylius\Bundle\ResourceBundle\Controller\EventDispatcher as SyliusEventDispatcher;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as SymfonyEventDispatcherInterface;

class EventDispatcher extends SyliusEventDispatcher
{
    /**
     * @var SymfonyEventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param SymfonyEventDispatcherInterface $eventDispatcher
     */
    public function __construct(SymfonyEventDispatcherInterface $eventDispatcher)
    {
        parent::__construct($eventDispatcher);
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function dispatchPreEvent($eventName, RequestConfiguration $requestConfiguration, ResourceInterface $resource)
    {
        $eventName = $requestConfiguration->getEvent() ?: $eventName;
        $this->eventDispatcher->dispatch(sprintf('enhavo_app.pre_%s', $eventName), new ResourceControllerEvent($resource));
        return parent::dispatchPreEvent($eventName, $requestConfiguration, $resource);;
    }

    /**
     * {@inheritdoc}
     */
    public function dispatchPostEvent($eventName, RequestConfiguration $requestConfiguration, ResourceInterface $resource)
    {
        $eventName = $requestConfiguration->getEvent() ?: $eventName;
        $this->eventDispatcher->dispatch(sprintf('enhavo_app.post_%s', $eventName), new ResourceControllerEvent($resource));
        return parent::dispatchPostEvent($eventName, $requestConfiguration, $resource);
    }

    public function dispatchInitEvent($eventName, RequestConfiguration $requestConfiguration)
    {
        $this->eventDispatcher->dispatch($eventName, new PreviewEvent($requestConfiguration->getRequest(), $requestConfiguration));
    }
}