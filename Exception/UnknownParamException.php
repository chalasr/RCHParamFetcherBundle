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
 * Thrown on invalid Request param.
 *
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
class UnknownParamException extends HttpRequestException
{
    public function __construct($message = 'There is no @ParamInterface configuration for this parameter', $statusCode = 400, \Exception $previous = null)
    {
        parent::__construct($message, $statusCode, $previous);
    }
}
