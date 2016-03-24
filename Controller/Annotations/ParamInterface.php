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
     * Get ParamInterface as string.
     *
     * @return string
     */
    public function __toString();
}
