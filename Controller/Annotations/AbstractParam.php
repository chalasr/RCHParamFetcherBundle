<?php

/**
 * This file is part of the RCHParamFetcherBundle package.
 *
 * Robin Chalas <robin.chalas@gmail.com>
 *
 * For more informations about license, please see the LICENSE
 * file distributed in this source code.
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
     * {@inheritdoc}
     */
    public function fetch(ParameterBag $parameterBag)
    {
        if (!$parameterBag->has($this->name)) {
            return false;
        }

        return $parameterBag->get($this->name);
    }
}
