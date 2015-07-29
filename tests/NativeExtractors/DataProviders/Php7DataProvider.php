<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\Tests\NativeExtractors\DataProviders;

use PropertyInfo\Extractors\GetterExtractor;
use PropertyInfo\Extractors\SetterExtractor;
use PropertyInfo\Tests\NativeExtractors\Data\Php7Data;

/**
 * @author Mihai Stancu <stancu.t.mihai@gmail.com>
 */
class Php7DataProvider
{
    /**
     * @return array
     */
    public function extractorsDataProvider()
    {
        $properties = [
            ['name' => 'bool', 'type' => 'bool', 'class' => null],
            ['name' => 'int', 'type' => 'int', 'class' => null],
            ['name' => 'float', 'type' => 'float', 'class' => null],
            ['name' => 'string', 'type' => 'string', 'class' => null],
            ['name' => 'array', 'type' => 'array', 'class' => null],
            ['name' => 'callable', 'type' => 'callable', 'class' => null],
            ['name' => 'object', 'type' => 'object', 'class' => 'stdClass'],
        ];

        $cases = [
            [Php7Data::class, GetterExtractor::class, $properties],
            [Php7Data::class, SetterExtractor::class, $properties],
        ];

        return $cases;
    }
}
