<?php

namespace Seferov\Bundle\RequestToEntityBundle;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;

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

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param mixed $object
     * @return mixed
     */
    public function handle($object)
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        foreach ($this->request->request->all() as $name => $value) {
            if ($accessor->isWritable($object, $name)) {
                $accessor->setValue($object, $name, $value);
            }
        }

        return $object;
    }
}
