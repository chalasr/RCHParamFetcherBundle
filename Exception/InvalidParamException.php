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
 * Thrown on invalid Request param.
 *
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
class InvalidParamException extends ParamException
{
    /**
     * Constructor.
     *
     * @param string     $message    The internal exception message
     * @param int        $statusCode The HTTP Response status code
     * @param \Exception $previous   The previous exception
     * @param int        $code       The internal exception code
     */
    public function __construct($key, $invalidValue, $rule = null, $statusCode = 400, \Exception $previous = null)
    {
        $message = $this->prepareMessageForResponse($key, $invalidValue, $rule);

        parent::__construct($message, $statusCode, $previous);
    }

    /**
     * Build message for Response.
     *
     * @param string $key          The property with an invalid value
     * @param mixed  $invalidValue The invalid value
     * @param string $errorMessage The constraint error message
     *
     * @return string
     */
    protected function prepareMessageForResponse($key, $invalidValue, $rule = null)
    {
        return sprintf(
            "Request parameter %s value '%s' violated a requirement (%s)",
            $key,
            $invalidValue,
            str_replace('"', '\'', $rule)
        );
    }
}
