<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\Tests\PhpDocExtractors;

use PropertyInfo\DescriptionExtractorInterface;
use PropertyInfo\PropertyInfo;
use PropertyInfo\Type;
use PropertyInfo\TypeExtractorInterface;

/**
 * @author Mihai Stancu <stancu.t.mihai@gmail.com>
 */
class PhpDocExtractorTest extends \PHPUnit_Framework_TestCase
{
    public function extractorsDataProvider()
    {
        $properties = [
            [
                'name' => 'foo',
                'type' => null,
                'collection' => false,
                'class' => null,
                'short_description' => 'Short description.',
                'long_description' => 'Long description.',
            ],
            [
                'name' => 'bar',
                'type' => 'string',
                'collection' => false,
                'class' => null,
                'short_description' => 'This is bar.',
            ],
            [
                'name' => 'baz',
                'type' => 'int',
                'collection' => false,
                'class' => null,
                'short_description' => 'Should be used.',
            ],
            ['name' => 'foo2', 'type' => 'float', 'collection' => false, 'class' => null],
            ['name' => 'foo3', 'type' => 'callable', 'collection' => false, 'class' => null],
            ['name' => 'foo4', 'type' => 'null', 'collection' => false, 'class' => null],
            ['name' => 'foo5', 'type' => null, 'collection' => false, 'class' => null],
            [
                'name' => 'files',
                'type' => 'array',
                'collection' => true,
                'class' => null,
                'collectionType' => ['type' => 'object', 'collection' => false, 'class' => 'SplFileInfo'],
            ],
            ['name' => 'bal', 'type' => 'object', 'collection' => false, 'class' => 'DateTime'],
            [
                'name' => 'parent',
                'type' => 'object',
                'collection' => false,
                'class' => 'PropertyInfo\Tests\PhpDocExtractors\Data\PhpDocParent',
            ],
            [
                'name' => 'collection',
                'type' => 'array',
                'collection' => true,
                'class' => null,
                'collectionType' => ['type' => 'object', 'collection' => false, 'class' => 'DateTime'],
            ],
        ];

        $cases = [
            [
                'PropertyInfo\Tests\PhpDocExtractors\Data\PhpDocDummy',
                'PropertyInfo\Extractors\PhpDocExtractor',
                $properties,
            ],
        ];

        return $cases;
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
        /** @var TypeExtractorInterface|DescriptionExtractorInterface $extractor */
        $extractor = new $extractor();
        $propertyInfo = new PropertyInfo([$extractor], [$extractor]);

        foreach ($properties as $property) {
            $reflectionProperty = new \ReflectionProperty($class, $property['name']);

            $expectedType = $property['type'];
            /** @var Type[] $actualTypes */
            $actualTypes = $propertyInfo->getTypes($reflectionProperty);
            $actualType = $actualTypes[0];

            if (isset($property['short_description'])) {
                $this->assertEquals(
                    $property['short_description'],
                    $propertyInfo->getShortDescription($reflectionProperty)
                );
            }
            if (isset($property['long_description'])) {
                $this->assertEquals(
                    $property['long_description'],
                    $propertyInfo->getLongDescription($reflectionProperty)
                );
            }

            if ($expectedType !== null) {
                $this->assertEquals($expectedType, $actualType->getType());
                $this->assertEquals($property['class'], $actualType->getClass());
                $this->assertEquals($property['collection'], $actualType->isCollection());

                if (isset($property['collectionType'])) {
                    $actualCollectionType = $actualType->getCollectionType();
                    $this->assertEquals($property['collectionType']['type'], $actualCollectionType->getType());
                    $this->assertEquals($property['collectionType']['class'], $actualCollectionType->getClass());
                    $this->assertEquals(
                        $property['collectionType']['collection'],
                        $actualCollectionType->isCollection()
                    );
                }
            } else {
                $this->assertNull($actualType);
            }
        }
    }
}
