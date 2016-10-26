<?php

namespace Seferov\Bundle\RequestToEntityBundle\EventListener;

use Doctrine\Common\Annotations\Reader;
use Seferov\Bundle\RequestToEntityBundle\Annotation\Entity;
use Seferov\Bundle\RequestToEntityBundle\RequestToEntityManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * Class EntityAnnotationListener
 *
 * @author Farhad Safarov <farhad.safarov@gmail.com>
 */
class EntityAnnotationListener
{
    /**
     * @var Reader
     */
    protected $reader;

    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(Reader $reader, RequestStack $requestStack)
    {
        $this->reader = $reader;
        $this->requestStack = $requestStack;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        if (!is_array($controller = $event->getController())) {
            return;
        }

        $object = new \ReflectionObject($controller[0]);
        $method = $object->getMethod($controller[1]);

        $entityAnnotation = $this->reader->getMethodAnnotation($method, Entity::class);

        if (!$entityAnnotation) {
            return;
        }

        $class = $entityAnnotation->getClass();
        if (!class_exists($class)) {
            throw new \Exception(sprintf('No class as %s', $class));
        }

        // Create object and set attributes from request
        $object = new $class;
        $manager = new RequestToEntityManager($this->requestStack, $this->reader);
        $manager->handle($object);

        $request = $this->requestStack->getCurrentRequest();
        $request->attributes->set(lcfirst((new \ReflectionClass($object))->getShortName()), $object);
    }
}
