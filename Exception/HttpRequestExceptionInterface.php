<?php

/*
 * This file is part of the RCHParamFetcherBundle.
 *
 * (c) Robin Chalas <https://github.com/chalasr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RCH\ParamFetcherBundle\Exception;

/**
 * Interface for HTTP Request error exceptions.
 *
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
interface HttpRequestExceptionInterface
{
    /**
     * Returns the status code.
     *
     * @return int An HTTP response status code
     */
    public function getStatusCode();
}
