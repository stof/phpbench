<?php

/*
 * This file is part of the PHPBench package
 *
 * (c) Daniel Leech <daniel@dantleech.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhpBench\Benchmark;

use PhpBench\Benchmark\Metadata\BenchmarkMetadata;
use PhpBench\Config\ConfigurableInterface;

/**
 * Executors are responsible for executing the benchmark class
 * and returning the timing metrics, and optionally the memory and profling
 * data.
 */
interface ExecutorInterface extends ConfigurableInterface
{
    /**
     * Execute the benchmark and return the result.
     *
     * @param Iteration $iteration
     * @param array $options
     *
     * @return IterationResult
     */
    public function execute(Iteration $iteration, array $options = array());

    /**
     * Execute arbitrary methods.
     *
     * This should be called based on the value of `@BeforeClassMethods` and `@AfterClassMethods`
     * and used to establish some persistent state.
     *
     * Methods called here cannot establish a runtime state.
     *
     * @param string[]
     */
    public function executeMethods(BenchmarkMetadata $benchmark, array $methods);
}
