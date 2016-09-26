<?php

namespace Seferov\Bundle\RequestToEntityBundle;

use Seferov\Bundle\RequestToEntityBundle\Annotation\RequestOptions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\Common\Annotations\Reader;

/**
 * Class RequestToEntityManager
 *
 * @author Farhad Safarov <farhad.safarov@gmail.com>
 */
class RequestToEntityManager
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Reader
     */
    private $reader;

    public function __construct(Request $request, Reader $reader)
    {
        $this->request = $request;
        $this->reader = $reader;
    }

    /**
     * @param mixed $object
     * @return mixed
     */
    public function handle($object)
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        $rf = new \ReflectionObject($object);
        foreach ($rf->getProperties() as $prop) {
            /** @var RequestOptions $requestOptions */
            $requestOptions = $this->reader->getPropertyAnnotation($prop, RequestOptions::class);

            if ($requestOptions && $requestOptions->readOnly) {
                continue;
            }

            $value = $this->request->get($prop->getName());

            if ($value && $accessor->isWritable($object, $prop->getName())) {
                $accessor->setValue($object, $prop->getName(), $value);
            }
        }

        return $object;
    }
}
