<?php

/*
 * This file is part of the RCHParamFetcherBundle.
 *
 * (c) Robin Chalas <robin.chalas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RCH\ParamFetcherBundle;

use RCH\ParamFetcherBundle\DependencyInjection\RCHParamFetcherExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * RCH\ParamFetcherBundle.
 *
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
class RCHParamFetcherBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new RCHParamFetcherExtension();
    }
}
