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
     * @var PhpDocPropertyInfo
     */
    private $extractor;

    public function setUp()
    {
        $this->extractor = new PhpDocExtractor();
    }

    /**
     * @dataProvider typesProvider
     */
    public function testExtract($property, array $type = null, $shortDescription, $longDescription)
    {
        $this->assertEquals($type, $this->extractor->getTypes('PropertyInfo\Tests\Fixtures\Dummy', $property));
        $this->assertSame($shortDescription, $this->extractor->getShortDescription('PropertyInfo\Tests\Fixtures\Dummy', $property));
        $this->assertSame($longDescription, $this->extractor->getLongDescription('PropertyInfo\Tests\Fixtures\Dummy', $property));
    }

    public function typesProvider()
    {
        return array(
            array('foo', null, 'Short description.', 'Long description.'),
            array('bar', array(new Type(Type::BUILTIN_TYPE_STRING)), 'This is bar.', null),
            array('baz', array(new Type(Type::BUILTIN_TYPE_INT)), 'Should be used.', null),
            array('foo2', array(new Type(Type::BUILTIN_TYPE_FLOAT)), null, null),
            array('foo3', array(new Type(Type::BUILTIN_TYPE_CALLABLE)), null, null),
            array('foo4', array(new Type(Type::BUILTIN_TYPE_NULL)), null, null),
            array('foo5', null, null, null),
            array(
                'files',
                array(
                    new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, new Type(Type::BUILTIN_TYPE_INT), new Type(Type::BUILTIN_TYPE_OBJECT, false, 'SplFileInfo')),
                    new Type(Type::BUILTIN_TYPE_RESOURCE),
                ),
                null,
                null,
            ),
            array('bal', array(new Type(Type::BUILTIN_TYPE_OBJECT, false, 'DateTime')), null, null),
            array('parent', array(new Type(Type::BUILTIN_TYPE_OBJECT, false, 'PropertyInfo\Tests\Fixtures\ParentDummy')), null, null),
            array('collection', array(new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, new Type(Type::BUILTIN_TYPE_INT), new Type(Type::BUILTIN_TYPE_OBJECT, false, 'DateTime'))), null, null),
            array('a', array(new Type(Type::BUILTIN_TYPE_INT)), 'A.', null),
            array('b', array(new Type(Type::BUILTIN_TYPE_OBJECT, true, 'PropertyInfo\Tests\Fixtures\ParentDummy')), 'B.', null),
            array('c', array(new Type(Type::BUILTIN_TYPE_BOOL, true)), null, null),
            array('d', array(new Type(Type::BUILTIN_TYPE_BOOL)), null, null),
            array('e', array(new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, new Type(Type::BUILTIN_TYPE_INT), new Type(Type::BUILTIN_TYPE_RESOURCE))), null, null),
            array('f', array(new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, new Type(Type::BUILTIN_TYPE_INT), new Type(Type::BUILTIN_TYPE_OBJECT, false, 'DateTime'))), null, null),
        );
    }
}
