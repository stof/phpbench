<?php

namespace PhpBench\Benchmark;

use PhpBench\Benchmark\Metadata\SubjectMetadata;

/**
 * Executors are responsible for executing the benchmark class
 * and returning the timing metrics, and optionally the memory and profling
 * data.
 */
interface ExecutorInterface
{
    /**
     * Execute the benchmark and return metrics.
     *
     * @return \DOMDocument
     */
    public function execute(SubjectMetadata $subject, $revolutions = 1, array $parameters = array());
}
