<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\Tests\Extractors;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use PropertyInfo\Extractors\DoctrineExtractor;
use PropertyInfo\Type;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class DoctrineExtractorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DoctrineExtractor
     */
    private $extractor;

    public function setUp()
    {
        $config = Setup::createAnnotationMetadataConfiguration([__DIR__.DIRECTORY_SEPARATOR.'Fixtures'], true);
        $entityManager = EntityManager::create(['driver' => 'pdo_sqlite'], $config);

        $this->extractor = new DoctrineExtractor($entityManager->getMetadataFactory());
    }

    /**
     * @dataProvider typesProvider
     */
    public function testExtractors($property, array $type = null)
    {
        $this->assertEquals($type, $this->extractor->extractTypes('PropertyInfo\Tests\Fixtures\DoctrineDummy', $property));
    }

    public function typesProvider()
    {
        return [
            ['id', [new Type(Type::BUILTIN_TYPE_INT)]],
            ['guid', [new Type(Type::BUILTIN_TYPE_STRING)]],
            ['bool', [new Type(Type::BUILTIN_TYPE_BOOL)]],
            ['binary', [new Type(Type::BUILTIN_TYPE_RESOURCE)]],
            ['json', [new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true)]],
            ['foo', [new Type(Type::BUILTIN_TYPE_OBJECT, false, 'PropertyInfo\Tests\Fixtures\DoctrineRelation')]],
            ['bar', [new Type(
                Type::BUILTIN_TYPE_OBJECT,
                false,
                'Doctrine\Common\Collections\Collection',
                true,
                new Type(Type::BUILTIN_TYPE_INT),
                new Type(Type::BUILTIN_TYPE_OBJECT, false, 'PropertyInfo\Tests\Fixtures\DoctrineRelation')
            )]],
            ['notMapped', null],
        ];
    }
}
