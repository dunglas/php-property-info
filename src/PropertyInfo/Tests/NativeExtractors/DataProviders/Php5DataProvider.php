<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\Tests\NativeExtractors\DataProviders;

/**
 * @author Mihai Stancu <stancu.t.mihai@gmail.com>
 */
class Php5DataProvider
{
    /**
     * @return array
     */
    public function extractorsDataProvider()
    {
        $properties = [
            ['name' => 'array', 'type' => 'array', 'class' => null],
            ['name' => 'callable', 'type' => 'callable', 'class' => null],
            ['name' => 'object', 'type' => 'object', 'class' => 'stdClass'],
        ];

        $cases = [
            [
                'PropertyInfo\Tests\NativeExtractors\Data\Php5Data',
                'PropertyInfo\Extractors\SetterExtractor',
                $properties,
            ],
        ];

        return $cases;
    }
}
