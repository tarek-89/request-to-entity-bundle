<?php

namespace Seferov\Bundle\RequestToEntityBundle\Annotation;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationAnnotation;

/**
 * @Annotation
 *
 * @author Farhad Safarov <farhad.safarov@gmail.com>
 */
class Entity extends ConfigurationAnnotation
{
    /**
     * @var string
     */
    private $class;

    public function getAliasName()
    {
        return 'request_entity';
    }

    public function allowArray()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }
}
