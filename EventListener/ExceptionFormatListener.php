<?php

/*
 * This file is part of the RCHParamFetcherBundle.
 *
 * (c) Robin Chalas <https://github.com/chalasr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RCH\ParamFetcherBundle\EventListener;

use RCH\ParamFetcherBundle\Exception\ParamException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Validator\Exception\ValidatorException;

/**
 * Listens for exceptions and transform Response.
 *
 * @author Robin Chalas <rchalas@sutunam.com>
 */
class ExceptionFormatListener
{
    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $request = $event->getRequest();
        $statusCode = 500;

        if ($exception instanceof ParamException) {
            $statusCode = $exception->getStatusCode();
        } elseif ($exception instanceof ValidatorException) {
            $statusCode = 400;
        }

        // Handle XML and HTML + Use twig teplates
        $response = $this->createJsonResponseForException($exception, $statusCode);

        $event->setResponse($response);
    }

    /**
     * Create JsonResponse for Exception.
     *
     * @param int           $statusCode
     * @param UserException $exception
     *
     * @return JsonResponse
     */
    protected function createJsonResponseForException(\Exception $exception, $statusCode)
    {
        $message = [
            'code'    => $statusCode,
            'message' => $exception->getMessage(),
            'errors'  => $exception->getPrevious() ?: null,
        ];

        return new JsonResponse($message, $statusCode);
    }
}
