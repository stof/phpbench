<?php

/*
 * This file is part of the PHPBench package
 *
 * (c) Daniel Leech <daniel@dantleech.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhpBench\Report\Renderer;

use PhpBench\Console\OutputAwareInterface;
use PhpBench\Dom\Document;
use PhpBench\Report\RendererInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DebugRenderer implements RendererInterface, OutputAwareInterface
{
    /**
     * @var OutputAwareInterface
     */
    private $output;

    /**
     * {@inheritdoc}
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function render(Document $reportsDocument, array $config)
    {
        $this->output->writeln('Report XML (debug):');
        $this->output->writeln($reportsDocument->saveXml());
    }

    public function getSchema()
    {
        return array();
    }

    public function getDefaultConfig()
    {
        return array();
    }

    public function getDefaultOutputs()
    {
        return array('debug' => array());
    }
}
