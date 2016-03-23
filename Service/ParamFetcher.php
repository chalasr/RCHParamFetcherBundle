<?php

/**
 * This file is part of the RCHParamFetcherBundle.
 *
 * Robin Chalas <robin.chalas@gmail.com>
 *
 * For more informations about license, please see the LICENSE
 * file distributed in this source code.
 */
namespace RCH\ParamFetcherBundle\Service;

use RCH\ParamFetcherBundle\Controller\Annotations\AbstractParam;
use RCH\ParamFetcherBundle\Request\ParameterBag;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
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
    protected $requestStack;
    protected $validator;
    protected $params;
    protected $parameterBag;

    /**
     * Constructor.
     *
     * @param RequestStack       $request
     * @param ValidatorInterface $validator
     * @param array              $$params
     */
    public function __construct(RequestStack $requestStack, ValidatorInterface $validator, ParamReader $paramReader)
    {
        $this->requestStack = $requestStack;
        $this->validator = $validator;
        $this->parameterBag = new ParameterBag($paramReader);
    }

    /**
     * {@inheritdoc}
     */
    public function setController($controller)
    {
        $this->parameterBag->setController($this->getRequest(), $controller);
    }

    /**
     * @return ParamInterface[]
     */
    public function getParams()
    {
        return $this->parameterBag->getParams($this->getRequest());
    }

    /**
     * Fetches all required parameters from the current Request body.
     *
     * @return array Params values
     */
    public function all()
    {
        $bag = $this->getParams();
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
        $request = $this->getRequest();
        $params = $this->getParams();

        if (!array_key_exists($name, $params)) {
            throw new \InvalidArgumentException(sprintf("No @ParamInterface configuration for parameter '%s'.", $name));
        }

        /* @var AbstractParam $param */
        $config = $params[$name];

        if (true === $config->required && !$request->request->has($name)) {
            throw new BadRequestHttpException(
                $this->formatError($name, null, 'The parameter must be set')
            );
        }

        $param = $request->request->get($name);

        if (false === $config->nullable && !$param) {
            throw new BadRequestHttpException(
                $this->formatError($name, null, 'The parameter cannot be null')
            );
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
     * @throws BadRequestHttpException If the param is not valid
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
                    throw new BadRequestHttpException(
                        sprintf('The @UniqueEntity constraint must be used on an existing property. The class "%s" does not have a property "%s"', get_class($object), $name)
                    );
                }

                $errors = $this->validator->validate($object, $constraint);
            } else {
                $errors = $this->validator->validate($param, $constraint);
            }

            // Use ValidatorException.

            if (0 !== count($errors)) {
                $error = $errors[0];
                throw new BadRequestHttpException(
                    $this->formatError($name, $error->getInvalidValue(), $error->getMessage())
                );
            }
        }
    }

    private function formatError($key, $invalidValue, $errorMessage)
    {
        return sprintf(
            "Request parameter %s value '%s' violated a requirement (%s)",
            $key,
            $invalidValue,
            $errorMessage
        );
    }

    /**
     * @throws \RuntimeException
     *
     * @return Request
     */
    private function getRequest()
    {
        if (!($request = $this->requestStack->getCurrentRequest())) {
            throw new \RuntimeException('There is no current request.');
        }

        return $request;
    }
}
