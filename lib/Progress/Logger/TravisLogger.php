<?php

/*
 * This file is part of the PHPBench package
 *
 * (c) Daniel Leech <daniel@dantleech.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhpBench\Progress\Logger;

use PhpBench\Benchmark\IterationCollection;
use PhpBench\Benchmark\Metadata\BenchmarkMetadata;
use PhpBench\Benchmark\SuiteDocument;
use PhpBench\Util\TimeUnit;

class TravisLogger extends PhpBenchLogger
{
    private $currentIterationCollection;

    /**
     * {@inheritdoc}
     */
    public function benchmarkStart(BenchmarkMetadata $benchmark)
    {
        static $first = true;

        if (false === $first) {
            $this->output->write(PHP_EOL);
        }
        $first = false;
        $this->output->writeln(sprintf('<comment>%s</comment>', $benchmark->getClass()));
        $this->output->write(PHP_EOL);
    }

    /**
     * {@inheritdoc}
     */
    public function iterationsEnd(IterationCollection $iterations)
    {
        if ($iterations->getRejectCount() > 0) {
            return;
        }

        $stats = $iterations->getStats();
        $subject = $iterations->getSubject();
        $timeUnit = $subject->getOutputTimeUnit();

        if (null === $timeUnit || $this->timeUnit->isOverridden()) {
            $timeUnit = $this->timeUnit->getDestUnit();
        }

        $suffix = TimeUnit::getSuffix($timeUnit);
        $this->output->writeln(sprintf(
            "\t%-30s P%s\tμ/r: %s%s\tμSD/r %s%s\tμRSD/r: %s%%",
            $subject->getName(),
            $iterations->getParameterSet()->getIndex(),
            number_format(TimeUnit::convert($stats['mean'], TimeUnit::MICROSECONDS, $timeUnit), 3),
            $suffix,
            number_format(TimeUnit::convert($stats['stdev'], TimeUnit::MICROSECONDS, $timeUnit), 3),
            $suffix,
            number_format($stats['rstdev'], 2)
        ));
    }

    public function endSuite(SuiteDocument $suiteDocument)
    {
        $this->output->write(PHP_EOL);
        parent::endSuite($suiteDocument);
    }
}
