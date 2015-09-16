<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\Tests\PhpDocExtractors;

use PropertyInfo\Extractors\PhpDocExtractor;
use PropertyInfo\Type;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class PhpDocExtractorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PhpDocExtractor
     */
    private $extractor;

    public function setUp()
    {
        $this->extractor = new PhpDocExtractor();
    }

    /**
     * @dataProvider typesProvider
     */
    public function testExtractors($property, array $type = null, $shortDescription, $longDescription)
    {
        $this->assertEquals($type, $this->extractor->extractTypes('PropertyInfo\Tests\Fixtures\Dummy', $property));
        $this->assertSame($shortDescription, $this->extractor->extractShortDescription('PropertyInfo\Tests\Fixtures\Dummy', $property));
        $this->assertSame($longDescription, $this->extractor->extractLongDescription('PropertyInfo\Tests\Fixtures\Dummy', $property));
    }

    public function typesProvider()
    {
        return [
            ['foo', null, 'Short description.', 'Long description.'],
            ['bar', [new Type(Type::BUILTIN_TYPE_STRING)], 'This is bar.', null],
            ['baz', [new Type(Type::BUILTIN_TYPE_INT)], 'Should be used.', null],
            ['foo2', [new Type(Type::BUILTIN_TYPE_FLOAT)], null, null],
            ['foo3', [new Type(Type::BUILTIN_TYPE_CALLABLE)], null, null],
            ['foo4', [new Type(Type::BUILTIN_TYPE_NULL)], null, null],
            ['foo5', null, null, null],
            [
                'files',
                [
                    new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, new Type(Type::BUILTIN_TYPE_INT), new Type(Type::BUILTIN_TYPE_OBJECT, false, 'SplFileInfo')),
                    new Type(Type::BUILTIN_TYPE_RESOURCE),
                ],
                null,
                null,
            ],
            ['bal', [new Type(Type::BUILTIN_TYPE_OBJECT, false, 'DateTime')], null, null],
            ['parent', [new Type(Type::BUILTIN_TYPE_OBJECT, false, 'PropertyInfo\Tests\Fixtures\ParentDummy')], null, null],
            ['collection', [new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, new Type(Type::BUILTIN_TYPE_INT), new Type(Type::BUILTIN_TYPE_OBJECT, false, 'DateTime'))], null, null],
            ['a', [new Type(Type::BUILTIN_TYPE_INT)], 'A.', null],
            ['b', [new Type(Type::BUILTIN_TYPE_OBJECT, true, 'PropertyInfo\Tests\Fixtures\ParentDummy')], 'B.', null],
            ['c', [new Type(Type::BUILTIN_TYPE_BOOL, true)], null, null],
            ['d', [new Type(Type::BUILTIN_TYPE_BOOL)], null, null],
            ['e', [new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, new Type(Type::BUILTIN_TYPE_INT), new Type(Type::BUILTIN_TYPE_RESOURCE))], null, null],
            ['f', [new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, new Type(Type::BUILTIN_TYPE_INT), new Type(Type::BUILTIN_TYPE_OBJECT, false, 'DateTime'))], null, null],
        ];
    }
}
