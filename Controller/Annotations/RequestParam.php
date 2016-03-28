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
 * HTTP Request's POST param.
 *
 * @Annotation
 * @Target("METHOD")
 *
 * @author Robin Chalas <rchalas@gmail.com>
 */
class RequestParam extends AbstractParam
{
    /**
     * {@inheritdoc}
     */
    public function fetch(Request $request)
    {
        return $this->fetchFromBag($request->request);
    }
}
