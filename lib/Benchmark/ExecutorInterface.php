<?php

namespace PhpBench\Benchmark;

use PhpBench\Benchmark\Metadata\SubjectMetadata;
use PhpBench\Config\ConfigurableInterface;

/**
 * Executors are responsible for executing the benchmark class
 * and returning the timing metrics, and optionally the memory and profling
 * data.
 */
interface ExecutorInterface extends ConfigurableInterface
{
    /**
     * Execute the benchmark and return metrics.
     *
     * @param SubjectMetadata $subject
     * @param int $revolutions
     * @param array $parameters
     * @param array $options
     *
     * @return \DOMDocument
     */
    public function execute(SubjectMetadata $subject, $revolutions = 1, array $parameters = array(), $options = array());
}
