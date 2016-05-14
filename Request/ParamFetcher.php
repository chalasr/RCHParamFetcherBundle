<?php

/*
 * This file is part of the RCHParamFetcherBundle.
 *
 * (c) Robin Chalas <robin.chalas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RCH\ParamFetcherBundle\Request;

use RCH\ParamFetcherBundle\Controller\Annotations\AbstractParam;
use RCH\ParamFetcherBundle\Exception\InvalidParamException;
use RCH\ParamFetcherBundle\Exception\UnknownParamException;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Fetches params from the body of the current request.
 *
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
class ParamFetcher
{
    /** @var RequestStack */
    protected $requestStack;

    /** @var ValidatorInterface */
    protected $validator;

    /** @var ParamBag */
    protected $paramBag;

    /**
     * Constructor.
     *
     * @param RequestStack       $request
     * @param ValidatorInterface $validator
     * @param ParamReader        $paramReader
     */
    public function __construct(RequestStack $requestStack, ValidatorInterface $validator, ParamReader $paramReader)
    {
        $this->requestStack = $requestStack;
        $this->validator = $validator;
        $this->paramBag = new ParamBag($paramReader);
    }

    /**
     * {@inheritdoc}
     */
    public function setController($controller)
    {
        $this->paramBag->setController($this->getRequest(), $controller);
    }

    /**
     * Fetches all required parameters from the current Request body.
     *
     * @return array Params values
     */
    public function all()
    {
        $bag = $this->getParamsFromBag();
        $params = [];

        foreach ($bag as $key => $config) {
            $params[$key] = $this->get($key);
        }

        return $params;
    }

    /**
     * Fetches a given parameter from the current Request body.
     *
     * @param string $name The parameter key
     *
     * @return mixed The parameter value
     */
    public function get($name)
    {
        $params = $this->getParamsFromBag();

        if (!array_key_exists($name, $params)) {
            throw new UnknownParamException(sprintf('There is no @ParamInterface configuration for param %s', $name));
        }

        /* @var ParamInterface $param */
        $param = $params[$name];
        $paramValue = $param->fetch($this->getRequest());

        if (true === $param->required && false === $paramValue) {
            throw new InvalidParamException($name, null, 'The parameter must be set');
        }

        if (true === $param->required && false === $param->nullable && (null === $paramValue || empty($paramValue))) {
            throw new InvalidParamException($name, null, 'The parameter cannot be null');
        }

        if (($param->default && $paramValue === $param->default) ||
            ($paramValue === null && true === $param->nullable)  ||
            (null === $param->getRequirements())
        ) {
            return $paramValue;
        }

        $this->handleRequirements($param, $paramValue);

        return $paramValue;
    }

    /**
     * Handle requirements validation.
     *
     * @param Param $param
     *
     * @throws \InvalidArgumentException If the param is not valid
     *
     * @return Param
     */
    private function handleRequirements(AbstractParam $config, $value)
    {
        $name = $config->name;

        if (null === $requirements = $config->getRequirements()) {
            return;
        }

        foreach ($requirements as $constraint) {
            if (is_scalar($constraint)) {
                $constraint = new Regex([
                    'pattern' => '#^'.$constraint.'$#xsu',
                    'message' => sprintf('Does not match "%s"', $constraint),
                ]);
            } elseif (is_array($constraint)) {
                continue;
            }

            if ($constraint instanceof UniqueEntity) {
                $object = $config->class;
                $accessor = PropertyAccess::createPropertyAccessor();

                if ($accessor->isWritable($object, $name)) {
                    $accessor->setValue($object, $name, $value);
                } else {
                    throw new InvalidParamException($name, null, 'The @UniqueEntity constraint must be used on an existing property');
                }

                $errors = $this->validator->validate($object, $constraint);
            } else {
                $errors = $this->validator->validate($value, $constraint);
            }

            if (0 !== count($errors)) {
                $error = $errors[0];
                throw new InvalidParamException($name, $error->getInvalidValue(), $error->getMessage());
            }
        }
    }

    /**
     * Get Params from the current Request.
     *
     * @return ParamInterface[]
     */
    protected function getParamsFromBag()
    {
        return $this->paramBag->getParams($this->getRequest());
    }

    /**
     * Get the current Request.
     *
     * @throws \RuntimeException If there is no current request.
     *
     * @return Request
     */
    protected function getRequest()
    {
        if (!$request = $this->requestStack->getCurrentRequest()) {
            throw new \RuntimeException('There is no current request.');
        }

        return $request;
    }
}
