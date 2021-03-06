<?php

/*
 * This file is part of the RCHParamFetcherBundle.
 *
 * (c) Robin Chalas <robin.chalas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RCH\ParamFetcherBundle\Request;

use Doctrine\Common\Annotations\Reader;
use RCH\ParamFetcherBundle\Controller\Annotations\ParamInterface;

/**
 * Retrieves @ParamInterface annotations from controllers.
 *
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
class ParamReader
{
    /** @var Reader */
    protected $reader;

    /**
     * Constructor.
     *
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * Read annotations of a given class method.
     *
     * @param \ReflectionClass $controller Reflection class
     * @param string           $action     Method name
     *
     * @return array Param instances of $action
     */
    public function read(\ReflectionClass $controller, $action)
    {
        if (!$controller->hasMethod($action)) {
            throw new \InvalidArgumentException(sprintf("Class '%s' has no method named '%s'.", $controller->getName(), $action));
        }

        /* @var \ReflectionMethod */
        $reflectionAction = $controller->getMethod($action);

        /* @var Annotation[] */
        $annotations = $this->reader->getMethodAnnotations($reflectionAction);

        return $this->getParamsFromAnnotations($annotations);
    }

    /**
     * Fetches parameters from a given array of annotations.
     *
     * @param Annotation[] $annotations
     *
     * @return ParamInterface[] The Param instances fetched from annotations
     */
    protected function getParamsFromAnnotations(array $annotations)
    {
        $params = [];

        foreach ($annotations as $annotation) {
            if ($annotation instanceof ParamInterface) {
                $params[$annotation->name] = $annotation;
            }
        }

        return $params;
    }
}
