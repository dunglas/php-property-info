<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\Tests\NativeExtractors\Data;

/**
 *    This exemplifies many combinations of property definitions with typed getters and setters. It is inspected by
 * Getter and Setter extractors during tests.
 *
 * @author Mihai Stancu <stancu.t.mihai@gmail.com>
 */
class Php7Data extends Php5Data
{
    private $bool;
    private $int;
    private $float;
    private $string;
    private $array;
    private $callable;
    private $object;


    public function getBool(): bool
    {
        return $this->bool;
    }
    public function setBool(bool $bool)
    {
        $this->bool = $bool;
    }

    public function getInt(): int
    {
        return $this->int;
    }
    public function setInt(int $int)
    {
        $this->int = $int;
    }

    public function getFloat(): float
    {
        return $this->float;
    }
    public function setFloat(float $float)
    {
        $this->float = $float;
    }

    public function getString(): string
    {
        return $this->string;
    }
    public function setString(string $string)
    {
        $this->string = $string;
    }

    public function getArray(): array
    {
        return $this->array;
    }

    public function getCallable(): callable
    {
        return $this->callable;
    }

    public function getObject(): \stdClass
    {
        return $this->object;
    }
}
