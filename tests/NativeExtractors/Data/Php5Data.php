<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\Tests\NativeExtractors\Data;

/**
 *    This exemplifies property definitions with typed setters. It is inspected by Getter and Setter extractors during
 * tests.
 *
 * @author Mihai Stancu <stancu.t.mihai@gmail.com>
 */
class Php5Data
{
    private $array;
    private $callable;
    private $object;

    public function setArray(array $array)
    {
        $this->array = $array;
    }

    public function setCallable(callable $callable)
    {
        $this->callable = $callable;
    }

    public function setObject(\stdClass $object)
    {
        $this->object = $object;
    }
}
