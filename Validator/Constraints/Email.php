<?php

/**
 * This file is part of the RCHParamFetcherBundle.
 *
 * Robin Chalas <robin.chalas@gmail.com>
 *
 * For more informations about license, please see the LICENSE
 * file distributed in this source code.
 */
namespace RCH\ParamFetcherBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Email as BaseEmailConstraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
class Email extends BaseEmailConstraint
{
    public function __toString()
    {
        return 'Email';
    }
}
