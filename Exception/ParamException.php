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
class ParamException extends \RuntimeException implements ParamExceptionInterface
{
    /**
     * @var int
     */
    private $statusCode;

    /**
     * Constructor.
     *
     * @param string     $message    The internal exception message
     * @param int        $statusCode The HTTP Response status code
     * @param \Exception $previous   The previous exception
     * @param int        $code       The internal exception code
     */
    public function __construct($message, $statusCode = 400, \Exception $previous = null)
    {
        $this->statusCode = $statusCode;

        parent::__construct($message, 0, $previous);
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
