<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\Tests\DoctrineExtractors;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use PropertyInfo\PropertyInfo;
use PropertyInfo\Type;
use PropertyInfo\TypeExtractorInterface;

/**
 * @author Mihai Stancu <stancu.t.mihai@gmail.com>
 */
class DoctrineExtractorTest extends \PHPUnit_Framework_TestCase
{
    public function extractorsDataProvider()
    {
        $properties = array(
            array(
                'name' => 'id',
                'type' => 'int',
                'collection' => false,
                'class' => null,
            ),
            array(
                'name' => 'guid',
                'type' => 'string',
                'collection' => false,
                'class' => null,
            ),
            array(
                'name' => 'bool',
                'type' => 'bool',
                'collection' => false,
                'class' => null,
            ),
            array('name' => 'binary', 'type' => 'resource', 'collection' => false, 'class' => null),
            array('name' => 'json', 'type' => 'array', 'collection' => true, 'class' => null),
            array(
                'name' => 'foo',
                'type' => 'object',
                'collection' => false,
                'class' => 'PropertyInfo\Tests\DoctrineExtractors\Data\DoctrineRelation',
            ),
            array(
                'name' => 'bar',
                'type' => 'object',
                'collection' => true,
                'class' => 'Doctrine\Common\Collections\Collection',
                'collectionType' => array(
                    'type' => 'object',
                    'collection' => false,
                    'class' => 'PropertyInfo\Tests\DoctrineExtractors\Data\DoctrineRelation',
            ),
            ),
            array('name' => 'notMapped', 'type' => null, 'collection' => false, 'class' => null),
    );

        $cases = array(
            array(
                'PropertyInfo\Tests\DoctrineExtractors\Data\DoctrineDummy',
                'PropertyInfo\Extractors\DoctrineExtractor',
                $properties,
            ),
        );

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
        $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__), true);
        $entityManager = EntityManager::create(array('driver' => 'pdo_sqlite'), $config);

        /** @var TypeExtractorInterface $extractor */
        $extractor = new $extractor($entityManager->getMetadataFactory());
        $propertyInfo = new PropertyInfo(array($extractor), array());

        foreach ($properties as $property) {
            $reflectionProperty = new \ReflectionProperty($class, $property['name']);

            $expectedType = $property['type'];
            /** @var Type[] $actualTypes */
            $actualTypes = $propertyInfo->getTypes($reflectionProperty);
            $actualType = $actualTypes[0];

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
