<?php

/*
 * This file is part of the PHPBench package
 *
 * (c) Daniel Leech <daniel@dantleech.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhpBench\Benchmark\Metadata;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\DocParser as DoctrineDocParser;

/**
 * PHPBench doc parser, uses the Doctrine DocParser.
 */
class DocParser
{
    private $docParser;
    private $imports = array(
        'BeforeMethods' => 'PhpBench\Benchmark\Metadata\Annotations\BeforeMethods',
        'AfterMethods' => 'PhpBench\Benchmark\Metadata\Annotations\AfterMethods',
        'ParamProviders' => 'PhpBench\Benchmark\Metadata\Annotations\ParamProviders',
        'Groups' => 'PhpBench\Benchmark\Metadata\Annotations\Groups',
        'Iterations' => 'PhpBench\Benchmark\Metadata\Annotations\Iterations',
        'Revs' => 'PhpBench\Benchmark\Metadata\Annotations\Revs',
        'Skip' => 'PhpBench\Benchmark\Metadata\Annotations\Skip',
        'Executor' => 'PhpBench\Benchmark\Metadata\Annotations\Executor',
    );
    private static $globalIgnoredNames = array(
        // Annotation tags
        'Annotation' => true, 'Attribute' => true, 'Attributes' => true,
        /* Can we enable this? 'Enum' => true, */
        'Required' => true,
        'Target' => true,
        // Widely used tags (but not existent in phpdoc)
        'fix' => true , 'fixme' => true,
        'override' => true,
        // PHPDocumentor 1 tags
        'abstract' => true, 'access' => true,
        'code' => true,
        'deprec' => true,
        'endcode' => true, 'exception' => true,
        'final' => true,
        'ingroup' => true, 'inheritdoc' => true, 'inheritDoc' => true,
        'magic' => true,
        'name' => true,
        'toc' => true, 'tutorial' => true,
        'private' => true,
        'static' => true, 'staticvar' => true, 'staticVar' => true,
        'throw' => true,
        // PHPDocumentor 2 tags.
        'api' => true, 'author' => true,
        'category' => true, 'copyright' => true,
        'deprecated' => true,
        'example' => true,
        'filesource' => true,
        'global' => true,
        'ignore' => true, /* Can we enable this? 'index' => true, */ 'internal' => true,
        'license' => true, 'link' => true,
        'method' => true,
        'package' => true, 'param' => true, 'property' => true, 'property-read' => true, 'property-write' => true,
        'return' => true,
        'see' => true, 'since' => true, 'source' => true, 'subpackage' => true,
        'throws' => true, 'todo' => true, 'TODO' => true,
        'usedby' => true, 'uses' => true,
        'var' => true, 'version' => true,
        // PHPUnit tags
        'codeCoverageIgnore' => true, 'codeCoverageIgnoreStart' => true, 'codeCoverageIgnoreEnd' => true,
        // PHPCheckStyle
        'SuppressWarnings' => true,
        // PHPStorm
        'noinspection' => true,
        // PEAR
        'package_version' => true,
        // PlantUML
        'startuml' => true, 'enduml' => true,
    );

    public function __construct()
    {
        $this->docParser = new DoctrineDocParser();
        $this->docParser->setIgnoredAnnotationNames(self::$globalIgnoredNames);
        $imports = array();
        foreach ($this->imports as $key => $value) {
            $imports[strtolower($key)] = $value;
        }

        $this->docParser->setImports($imports);
    }

    /**
     * Delegates to the doctrine DocParser but catches annotation not found errors and throws
     * something useful.
     *
     * @see \Doctrine\Common\Annotations\DocParser
     */
    public function parse($input, $context = '')
    {
        try {
            $annotations = $this->docParser->parse($input, $context);
        } catch (AnnotationException $e) {
            if (!preg_match('/The annotation "(.*)" .* was never imported/', $e->getMessage(), $matches)) {
                throw $e;
            }

            throw new \InvalidArgumentException(sprintf(
                'Unrecognized annotation %s, valid PHPBench annotations: @%s',
                $matches[1],
                implode(', @', array_keys($this->imports))
            ), null, $e);
        }

        return $annotations;
    }
}
