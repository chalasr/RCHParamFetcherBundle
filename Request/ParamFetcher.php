<?php

/**
 * This file is part of the RCHParamFetcherBundle.
 *
 * Robin Chalas <robin.chalas@gmail.com>
 *
 * For more informations about license, please see the LICENSE
 * file distributed in this source code.
 */
namespace RCH\ParamFetcherBundle\Request;

use RCH\ParamFetcherBundle\Controller\Annotations\AbstractParam;
use RCH\ParamFetcherBundle\Exception\InvalidParamException;
use RCH\ParamFetcherBundle\Exception\UnknownParamException;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\Request;
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
    /** @var Request */
    protected $request;

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
    public function __construct(Request $request = null, ValidatorInterface $validator, ParamReader $paramReader)
    {
        if (!$request) {
            throw new \RuntimeException('There is no current request.');
        }

        $this->request = $request;
        $this->validator = $validator;
        $this->paramBag = new ParamBag($paramReader);
    }

    /**
     * {@inheritdoc}
     */
    public function setController($controller)
    {
        $this->paramBag->setController($this->request, $controller);
    }

    /**
     * Fetches all required parameters from the current Request body.
     *
     * @return array Params values
     */
    public function all()
    {
        $bag = $this->getParamsFromBag();
        $params = array();

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
        $request = $this->request;
        $params = $this->getParamsFromBag();

        if (!array_key_exists($name, $params)) {
            throw new UnknownParamException(sprintf('There is no @ParamInterface configuration for param %s', $name));
        }

        /* @var AbstractParam $param */
        $config = $params[$name];

        if (true === $config->required && !$request->request->has($name)) {
            throw new InvalidParamException($name, null, 'The parameter must be set');
        }

        $param = $request->request->get($name);

        if (false === $config->nullable && !$param) {
            throw new InvalidParamException($name, null, 'The parameter cannot be null');
        }

        if (($config->default && $param === $config->default)
        || ($param === null && true === $config->nullable)
        || (null === $config->requirements)) {
            return $param;
        }

        $this->handleRequirements($config, $param);

        return $param;
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
    private function handleRequirements(AbstractParam $config, $param)
    {
        $name = $config->name;

        if (null === $requirements = $config->requirements) {
            return;
        }

        foreach ($requirements as $constraint) {
            if (is_scalar($constraint)) {
                $constraint = new Regex(array(
                    'pattern' => '#^'.$constraint.'$#xsu',
                    'message' => sprintf('Does not match "%s"', $constraint),
                ));
            } elseif (is_array($constraint)) {
                continue;
            }

            if ($constraint instanceof UniqueEntity) {
                $object = $config->class;
                $accessor = PropertyAccess::createPropertyAccessor();

                if ($accessor->isWritable($object, $name)) {
                    $accessor->setValue($object, $name, $param);
                } else {
                    throw new InvalidParamException($name, null, 'The @UniqueEntity constraint must be used on an existing property');
                }

                $errors = $this->validator->validate($object, $constraint);
            } else {
                $errors = $this->validator->validate($param, $constraint);
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
        return $this->paramBag->getParams($this->request);
    }
}
