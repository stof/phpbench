<?php

/*
 * This file is part of the PHPBench package
 *
 * (c) Daniel Leech <daniel@dantleech.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhpBench;

use PhpBench\DependencyInjection\Container;
use Seld\JsonLint\JsonParser;
use Seld\JsonLint\ParsingException;

class PhpBench
{
    const VERSION = '0.5';

    public static function run()
    {
        $container = new Container();
        $config = self::loadConfig($container);
        self::registerExtensions($container, $config);
        $container->configure();
        $container->mergeParameters($config);
        $container->build();
        $container->get('console.application')->run();
    }

    public static function registerExtensions(Container $container, array $config)
    {
        if (!isset($config['extensions'])) {
            return;
        }

        foreach ($config['extensions'] as $extensionClass) {
            $container->registerExtension($extensionClass);
        }
    }

    private static function loadConfig(Container $container)
    {
        global $argv;

        $configPaths = array();
        foreach ($argv as $arg) {
            if (0 === strpos($arg, '--config=')) {
                $configFile = substr($arg, 9);
                if (!file_exists($configFile)) {
                    echo sprintf('Config file "%s" does not exist', $configFile) . PHP_EOL;
                    exit(1);
                }
                $configPaths = array($configFile);
            }
        }

        if (empty($configPaths)) {
            $configPaths = array(
                getcwd() . '/phpbench.json',
                getcwd() . '/phpbench.json.dist',
            );
        }

        foreach ($configPaths as $configPath) {
            if (!file_exists($configPath)) {
                continue;
            }

            $config = file_get_contents($configPath);

            try {
                $parser = new JsonParser();
                $parser->parse($config);
            } catch (ParsingException $e) {
                echo 'Error parsing config file:' . PHP_EOL . PHP_EOL;
                echo $e->getMessage();
                exit(1);
            }

            $config = json_decode($config, true);

            $config['config_path'] = $configPath;

            return $config;
        }
    }
}
