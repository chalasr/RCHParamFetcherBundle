<?php

/*
 * This file is part of the RCHParamFetcherBundle.
 *
 * (c) Robin Chalas <robin.chalas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RCH\ParamFetcherBundle\Exception;

/**
 * Thrown when a Param is fetched without being configured.
 *
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
class UnknownParamException extends ParamException
{
    public function __construct($message = 'There is no @ParamInterface configuration for this parameter', $statusCode = 400, \Exception $previous = null)
    {
        parent::__construct($message, $statusCode, $previous);
    }
}
