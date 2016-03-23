<?php

/**
 * This file is part of the RCHParamFetcherBundle package.
 *
 * Robin Chalas <robin.chalas@gmail.com>
 *
 * For more informations about license, please see the LICENSE
 * file distributed in this source code.
 */
namespace RCH\ParamFetcherBundle\Controller\Annotations;

/**
 * Request parameter.
 *
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
abstract class AbstractParam implements ParamInterface
{
    /** @var array */
    protected $options;

    /** @var string */
    public $name;

    /** @var array */
    public $requirements = array();

    /** @var mixed */
    public $default = null;

    /** @var bool */
    public $nullable = false;

    /** @var bool */
    public $required = true;

    /** @var bool */
    public $class = null;

    /**
     * Constructor.
     *
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;

        if (!$this->options) {
            return;
        }

        $this->setName();
        $this->setRequirements();
        $this->setClass();

        foreach ($this->options as $option) {
            if ((null === $option && null === $this->$option)
            || is_bool($this->$option) && !(is_bool($option))) {
                continue;
            }

            $this->$option = $option;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setRequirements()
    {
        if (!isset($this->options['requirements']) || $this->options['requirements'] === null) {
            return $this;
        }

        $requirements = $this->options['requirements'];

        if (!is_array($requirements)) {
            $requirements = array($requirements);
        }

        foreach ($requirements as $constraint) {
            $this->requirements[] = $constraint;
        }

        unset($this->options['requirements']);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setClass()
    {
        if (!isset($this->options['class']) || !$this->options['class']) {
            return $this;
        }

        $class = $this->options['class'];
        unset($this->options['class']);

        if (is_object($class)) {
            $this->class = $class;

            return $this;
        }

        if (class_exists($class)) {
            $this->class = new $class();
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name = null)
    {
        if (!$name && isset($this->options['name'])) {
            $name = $this->options['name'];
            unset($this->options['name']);
        }

        $this->name = $name;

        return $this;
    }
}
