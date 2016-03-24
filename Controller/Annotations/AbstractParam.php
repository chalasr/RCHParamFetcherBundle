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
}
