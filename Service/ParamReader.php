<?php

/**
 * This file is part of the RCHParamFetcherBundle.
 *
 * Robin Chalas <robin.chalas@gmail.com>
 *
 * For more informations about license, please see the LICENSE
 * file distributed in this source code.
 */
namespace RCH\ParamFetcherBundle\Service;

use Doctrine\Common\Annotations\Reader;
use FOS\RestBundle\Controller\Annotations\Param;

/**
 * Retrieves @RequestParam annotations from action.
 *
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
class ParamReader
{
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
            throw new \InvalidArgumentException(sprintf("Class '%s' has no method '%s' method.", $controller->getName(), $action));
        }

        return $this->getParamsFromMethod($controller->getMethod($action));
    }

    /**
     * Read annotations for a given method.
     *
     * @param \ReflectionMethod $action Reflection method
     *
     * @return array Param instances of $action
     */
    public function getParamsFromMethod(\ReflectionMethod $action)
    {
        $annotations = $this->reader->getMethodAnnotations($action);

        return $this->getParamsFromArray($annotations);
    }

    /**
     * Fetches parameters from a given array of annotations.
     *
     * @param array $annotations
     *
     * @return array Param instances fetched from annotations
     */
    protected function getParamsFromArray(array $annotations)
    {
        $params = array();

        foreach ($annotations as $annotation) {
            if ($annotation instanceof Param) {
                $params[$annotation->name] = $annotation;
            }
        }

        return $params;
    }
}
