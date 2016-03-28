<?php

/*
 * This file is part of the RCHParamFetcherBundle.
 *
 * (c) Robin Chalas <robin.chalas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RCH\ParamFetcherBundle\Controller\Annotations;

use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Request parameter.
 *
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
abstract class AbstractParam implements ParamInterface
{
    /** @var string */
    public $name;

    /** @var array */
    public $requirements = array();

    /** @var mixed */
    public $default = null;

    /** @var bool */
    public $nullable = false;

    /** @var bool */
    public $required = true;

    /** @var bool */
    public $class = null;

    /**
     * Fetch an instance of ParamInterface from the corresponding Request's ParameterBag.
     *
     * @param ParameterBag $parameterBag The corresponding Request's ParameterBag
     * @param string       $default      The default value
     * @param bool         $required     True if the parameter must be set.
     *
     * @return mixed
     */
    public function fetchFromBag(ParameterBag $parameterBag)
    {
        if (!($parameterBag->has($this->name)) && (true === $this->required)) {
            return false;
        }

        return $parameterBag->get($this->name, $this->default);
    }
}
