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
 * Interface implemented by all kind of Param.
 *
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
interface ParamInterface
{
    /**
     * Fetch the Param from the current Request body.
     *
     * @param ParameterBag $parameterBag The corresponding ParameterBag of the current Request
     *
     * @return mixed
     */
    public function fetch(ParameterBag $parameterBag);
}
