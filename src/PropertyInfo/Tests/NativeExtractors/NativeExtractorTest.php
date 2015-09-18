<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\Tests\NativeExtractors;

use PropertyInfo\PropertyInfo;
use PropertyInfo\Tests\NativeExtractors\DataProviders\HackDataProvider;
use PropertyInfo\Tests\NativeExtractors\DataProviders\Php5DataProvider;
use PropertyInfo\Tests\NativeExtractors\DataProviders\Php7DataProvider;

/**
 * @author Mihai Stancu <stancu.t.mihai@gmail.com>
 */
class NativeExtractorTest extends \PHPUnit_Framework_TestCase
{
    public function extractorsDataProvider()
    {
        if (defined('HHVM_VERSION')) {
            $data = new HackDataProvider();
        } elseif (PHP_VERSION >= 7) {
            $data = new Php7DataProvider();
        } else {
            $data = new Php5DataProvider();
        }

        return $data->extractorsDataProvider();
    }

    /**
     * @dataProvider extractorsDataProvider
     *
     * @param $class
     * @param $extractor
     * @param $properties
     */
    public function testExtractors($class, $extractor, $properties)
    {
        $extractor = new $extractor();
        $propertyInfo = new PropertyInfo(array($extractor), array());

        foreach ($properties as $property) {
            $actualTypes = $propertyInfo->getTypes(new \ReflectionProperty($class, $property['name']));

            if (null !== $actualTypes) {
                $this->assertCount(1, $actualTypes);
                $actualType = $actualTypes[0];

                $this->assertEquals($property['type'], $actualType->getType());
                $this->assertEquals($property['class'], $actualType->getClass());

                if (isset($property['collection'])) {
                    $this->assertEquals($property['collection'], $actualType->isCollection());
                    $this->assertEquals(
                        $property['collectionType']['type'],
                        $actualType->getCollectionType()->getType()
                    );
                    $this->assertEquals(
                        $property['collectionType']['class'],
                        $actualType->getCollectionType()->getClass()
                    );
                }
            } else {
                $this->fail(
                    vsprintf(
                        'Using "%1$s" type "%2$s" resulted in an empty type list',
                        array(get_class($extractor), $property['type'])
                    )
                );
            }
        }
    }
}
