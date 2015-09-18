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
class Php7DataProvider
{
    /**
     * @return array
     */
    public function extractorsDataProvider()
    {
        $properties = array(
            array('name' => 'bool', 'type' => 'bool', 'class' => null),
            array('name' => 'int', 'type' => 'int', 'class' => null),
            array('name' => 'float', 'type' => 'float', 'class' => null),
            array('name' => 'string', 'type' => 'string', 'class' => null),
            array('name' => 'array', 'type' => 'array', 'class' => null),
            array('name' => 'callable', 'type' => 'callable', 'class' => null),
            array('name' => 'object', 'type' => 'object', 'class' => 'stdClass'),
        );

        $cases = array(
            array(
                'PropertyInfo\Tests\NativeExtractors\Data\Php7Data',
                'PropertyInfo\Extractors\GetterExtractor',
                $properties,
            ),
            array(
                'PropertyInfo\Tests\NativeExtractors\Data\Php7Data',
                'PropertyInfo\Extractors\SetterExtractor',
                $properties,
            ),
        );

        return $cases;
    }
}
