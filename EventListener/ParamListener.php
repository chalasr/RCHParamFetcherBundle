<?php

/*
 * This file is part of the RCHParamFetcherBundle.
 *
 * (c) Robin Chalas <https://github.com/chalasr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RCH\ParamFetcherBundle\EventListener;

use RCH\ParamFetcherBundle\Service\ParamFetcher;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * Performs Request params fetching in a controller action through
 * the injected ParamFetcher.
 */
class ParamListener
{
    /** @var \RCH\ParamFetcherBundle\Service\ParamFetcher */
    private $paramFetcher;

    /**
     * Constructor.
     *
     * @param ParamFetcherInterface $paramFetcher
     * @param bool                  $setParamsAsAttributes
     */
    public function __construct(ParamFetcher $paramFetcher)
    {
        $this->paramFetcher = $paramFetcher;
    }

    /**
     * Core controller handler.
     *
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $request = $event->getRequest();
        $controller = $event->getController();

        if (is_callable($controller) && method_exists($controller, '__invoke')) {
            $controller = [$controller, '__invoke'];
        }

        $this->paramFetcher->setController($controller);
        $attributeName = $this->getAttributeName($controller);
        $request->attributes->set($attributeName, $this->paramFetcher);
    }

    /**
     * Determines which attribute the ParamFetcher should be injected as.
     *
     * @param callable $controller The controller action as an "array" callable.
     *
     * @return string
     */
    private function getAttributeName(callable $controller)
    {
        list($object, $name) = $controller;
        $method = new \ReflectionMethod($object, $name);

        foreach ($method->getParameters() as $param) {
            if ($this->isParamFetcher($param)) {
                return $param->getName();
            }
        }

        return 'paramFetcher';
    }

    /**
     * Returns true if the given controller parameter is type-hinted as
     * an instance of ParamFetcher.
     *
     * @param \ReflectionParameter $actionParam
     *
     * @return bool
     */
    private function isParamFetcher(\ReflectionParameter $actionParam)
    {
        return $actionParam
            ->getClass()
            ->isSubclassOf('\RCH\ParamFetcherBundle\Service\ParamFetcher');
    }
}
