<?php

/*
 * This file is part of the RCHParamFetcherBundle.
 *
 * (c) Robin Chalas <robin.chalas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RCH\ParamFetcherBundle\EventListener;

use RCH\ParamFetcherBundle\Request\ParamFetcher;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * Performs Request params fetching in a controller action through
 * the injected ParamFetcher.
 */
class ParamListener
{
    /** @var ParamFetcher */
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
        $request->attributes->set($this->getParamFetcherArgument($controller), $this->paramFetcher);
    }

    /**
     * Determines which attribute the ParamFetcher should be injected as.
     *
     * @param callable $controller The controller action as an "array" callable.
     *
     * @return string
     */
    private function getParamFetcherArgument(callable $controller)
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
     * @param \ReflectionParameter $param
     *
     * @return bool
     */
    private function isParamFetcher(\ReflectionParameter $param)
    {
        $paramType = $param->getClass();

        return $paramType ? $paramType->isSubclassOf(ParamFetcher::class) : false;
    }
}
