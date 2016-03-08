<?php

namespace RCH\ParamFetcherBundle;

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
