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

use Symfony\Component\HttpFoundation\Request;

/**
 * Interface implemented by all kind of Param.
 *
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
interface ParamInterface
{
    /**
     * Fetch a given Param from the current Request.
     *
     * @param Request $request The current Request
     *
     * @return mixed
     */
    public function fetch(Request $request);
}
