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
        $properties = array(
            array('name' => 'array', 'type' => 'array', 'class' => null),
            array('name' => 'callable', 'type' => 'callable', 'class' => null),
            array('name' => 'object', 'type' => 'object', 'class' => 'stdClass'),
        );

        $cases = array(
            array(
                'PropertyInfo\Tests\NativeExtractors\Data\Php5Data',
                'PropertyInfo\Extractors\SetterExtractor',
                $properties,
            ),
        );

        return $cases;
    }
}
