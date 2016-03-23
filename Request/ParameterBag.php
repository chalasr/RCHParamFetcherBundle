<?php

/**
 * This file is part of the RCHParamFetcherBundle package.
 *
 * Robin Chalas <robin.chalas@gmail.com>
 *
 * For more informations about license, please see the LICENSE
 * file distributed in this source code.
 */
namespace RCH\ParamFetcherBundle\Request;

use Doctrine\Common\Util\ClassUtils;
use RCH\ParamFetcherBundle\Service\ParamReader;
use Symfony\Component\HttpFoundation\Request;

/**
 * Stores params from Requests body.
 *
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
class ParameterBag
{
    /** @var ParamReader */
    private $paramReader;

    /** @var array */
    private $requests = array();

    /**
     * Constructor.
     *
     * @param ParamReader $paramReader
     */
    public function __construct(ParamReader $paramReader)
    {
        $this->paramReader = $paramReader;
    }

    /**
     * Get parameters from request body.
     *
     * @param Request $request
     *
     * @return array
     */
    public function getParams(Request $request)
    {
        $requestHash = spl_object_hash($request);

        if (!isset($this->requests[$requestHash]) || empty($this->requests[$requestHash]['controller'])) {
            throw new \InvalidArgumentException('Controller and method needs to be set via setController.');
        }

        if ($this->requests[$requestHash]['params'] === null) {
            return $this->initParams($requestHash);
        }

        return $this->requests[$requestHash]['params'];
    }

    /**
     * Set the controller/action of the current request.
     *
     * @param Request  $request
     * @param callable $controller
     */
    public function setController(Request $request, $controller)
    {
        $requestHash = spl_object_hash($request);

        $this->requests[$requestHash] = array(
            'controller' => $controller,
            'params'     => null,
        );
    }

    /**
     * Initialize the parameters.
     *
     * @param string $requestHash
     *
     * @throws \InvalidArgumentException
     */
    private function initParams($requestHash)
    {
        $controller = $this->requests[$requestHash]['controller'];

        if (!is_array($controller) || empty($controller[0]) || !is_object($controller[0])) {
            throw new \InvalidArgumentException('Controller needs to be a class instance');
        }

        return $this->requests[$requestHash]['params'] = $this->paramReader->read(
            new \ReflectionClass(ClassUtils::getClass($controller[0])),
            $controller[1]
        );
    }
}
