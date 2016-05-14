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
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;

/**
 * Http Request's FILE param.
 *
 * @author Thomas Jaari <tjaari76@gmail.com>
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
class FileParam extends AbstractParam
{
    public $image = false;

    /**
     * {@inheritdoc}
     */
    public function fetch(Request $request)
    {
        return $this->fetchFromBag($request->files);
    }

    /**
     * {@inheritdoc}
     */
    public function getRequirements()
    {
        $requirements = $this->getRequirements();
        $defaultConstraint = (true === $this->image) ? new Image() : new File();
        $hasConstraint = false;

        foreach ($requirements as $constraint) {
            if (!$constraint instanceof Constraint) {
                continue;
            }

            if ($constraint instanceof $defaultConstraint) {
                $hasConstraint = true;
            }
        }

        if (true === $hasConstraint) {
            $this->requirements[] = $defaultConstraint;
        }

        return $this->requirements;
    }
}
