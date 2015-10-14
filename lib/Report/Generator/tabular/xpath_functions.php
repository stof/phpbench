<?php

/*
 * This file is part of the PHPBench package
 *
 * (c) Daniel Leech <daniel@dantleech.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhpBench\Report\Dom\functions;

function parameters_to_json($values)
{
    $array = array();
    foreach ($values as $parameterEl) {
        $array[$parameterEl->getAttribute('name')] = $parameterEl->getAttribute('value');
    }

    return json_encode($array);
}

function class_name($classFqn)
{
    $parts = explode('\\', $classFqn);
    end($parts);

    return current($parts);
}

function join_node_values($delimiter, $list)
{
    $els = array();
    foreach ($list as $el) {
        if (!$el instanceof \DOMNode) {
            continue;
        }
        $els[] = $el->nodeValue;
    }

    return implode($delimiter, $els);
}

function replace($search, $replace, $subject)
{
    return str_replace($search, $replace, $subject);
}

function repeat($string, $multiplier)
{
    return str_repeat($string, $multiplier);
}
