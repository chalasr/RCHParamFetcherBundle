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
 * Thrown when a Param is fetched without being configured.
 *
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
class InvalidRequirementParamException extends ParamException
{
    public function __construct($message = 'The given requirement is not valid', $statusCode = 400, \Exception $previous = null)
    {
        parent::__construct($message, $statusCode, $previous);
    }
}
