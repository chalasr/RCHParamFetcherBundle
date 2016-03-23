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
 * Interface implemented by all kind of Param.
 *
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
interface ParamInterface
{
    /**
     * Set requirements.
     */
    public function setRequirements();

    /**
     * Set class (used to deal with constraints e.g. @UniqueEntity).
     *
     * @param string|object|null $class The name or an instance of the given class
     */
    public function setClass();

    /**
     * Set name.
     *
     * @param string|null $name The parameter name.
     */
    public function setName($name = null);
}
