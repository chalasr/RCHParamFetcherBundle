<?php

/*
* This file is part of RCH/CapistranoBundle.
*
* Robin Chalas <robin.chalas@gmail.com>
*
* For more informations about license, please see the LICENSE
* file distributed in this source code.
*/

namespace RCH\ParamFetcherBundle\Util;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Provides helper methods.
 *
 * @author Robin Chalas <robin.chalas@gmail.com>
 */
trait OutputHelperTrait
{
    /**
     * Writes stylized welcome message in Output.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function sayWelcome(OutputInterface $output)
    {
        $breakline = '';
        $output = $this->createBlockTitle($output);
        $title = $this->formatAsTitle('Thank\'s to use RCHParamFetcherBundle');
        $welcome = array($breakline, $title, $breakline);

        $output->writeln($welcome);
    }

    /**
     * Create a title block.
     *
     * @param OutputInterface $output
     *
     * @return OutputInterface
     */
    protected function createBlockTitle(OutputInterface $output)
    {
        $style = new OutputFormatterStyle('white', 'blue', array('bold'));
        $output->getFormatter()->setStyle('title', $style);

        return $output;
    }

    /**
     * Formats string as output block title.
     *
     * @param string $content
     *
     * @return string
     */
    protected function formatAsTitle($content)
    {
        $formatter = $this->getHelper('formatter');
        $title = $formatter->formatBlock($content, 'title', true);

        return $title;
    }
}
