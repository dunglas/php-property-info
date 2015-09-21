<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\Tests\Extractors;

use PropertyInfo\Extractors\ReflectionExtractor;
use PropertyInfo\Type;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 * @author Mihai Stancu <stancu.t.mihai@gmail.com>
 */
class ReflectionExtractorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ReflectionExtractor
     */
    private $extractor;

    public function setUp()
    {
        $this->extractor = new ReflectionExtractor();
    }

    public function testGetProperties()
    {
        $this->assertEquals(
            array(
                'bal',
                'parent',
                'collection',
                'foo',
                'foo2',
                'foo3',
                'foo4',
                'foo5',
                'files',
                'A',
                'B',
                'C',
                'D',
                'E',
                'F',
            ),
            $this->extractor->getProperties('PropertyInfo\Tests\Fixtures\Dummy')
        );
    }

    /**
     * @dataProvider typesProvider
     */
    public function testExtractors($property, array $type = null)
    {
        $this->assertEquals($type, $this->extractor->getTypes('PropertyInfo\Tests\Fixtures\Dummy', $property, array()));
    }

    public function typesProvider()
    {
        return array(
            array('a', null),
            array('b', array(new Type(Type::BUILTIN_TYPE_OBJECT, true, 'PropertyInfo\Tests\Fixtures\ParentDummy'))),
            array('c', array(new Type(Type::BUILTIN_TYPE_BOOL))),
            array('d', array(new Type(Type::BUILTIN_TYPE_BOOL))),
            array('e', null),
            array('f', array(new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, new Type(Type::BUILTIN_TYPE_INT), new Type(Type::BUILTIN_TYPE_OBJECT, false, 'DateTime')))),
        );
    }
    /**
     * @dataProvider php7TypesProvider
     */
    public function testExtractPhp7Type($property, array $type = null)
    {
        if (!method_exists('\ReflectionMethod', 'getReturnType')) {
            $this->markTestSkipped('Available only with PHP 7 and superior.');
        }

        $this->assertEquals($type, $this->extractor->getTypes('PropertyInfo\Tests\Fixtures\Php7Dummy', $property, array()));
    }

    public function php7TypesProvider()
    {
        return array(
            array('foo', array(new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true))),
            array('bar', array(new Type(Type::BUILTIN_TYPE_INT))),
            array('baz', array(new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, new Type(Type::BUILTIN_TYPE_INT), new Type(Type::BUILTIN_TYPE_STRING)))),
        );
    }

    public function testIsReadable()
    {
        $this->assertFalse($this->extractor->isReadable('PropertyInfo\Tests\Fixtures\Dummy', 'bar', array()));
        $this->assertFalse($this->extractor->isReadable('PropertyInfo\Tests\Fixtures\Dummy', 'baz', array()));
        $this->assertTrue($this->extractor->isReadable('PropertyInfo\Tests\Fixtures\Dummy', 'parent', array()));
        $this->assertTrue($this->extractor->isReadable('PropertyInfo\Tests\Fixtures\Dummy', 'a', array()));
        $this->assertFalse($this->extractor->isReadable('PropertyInfo\Tests\Fixtures\Dummy', 'b', array()));
        $this->assertTrue($this->extractor->isReadable('PropertyInfo\Tests\Fixtures\Dummy', 'c', array()));
        $this->assertTrue($this->extractor->isReadable('PropertyInfo\Tests\Fixtures\Dummy', 'd', array()));
        $this->assertFalse($this->extractor->isReadable('PropertyInfo\Tests\Fixtures\Dummy', 'e', array()));
        $this->assertFalse($this->extractor->isReadable('PropertyInfo\Tests\Fixtures\Dummy', 'f', array()));
    }

    public function testIsWritable()
    {
        $this->assertFalse($this->extractor->isWritable('PropertyInfo\Tests\Fixtures\Dummy', 'bar', array()));
        $this->assertFalse($this->extractor->isWritable('PropertyInfo\Tests\Fixtures\Dummy', 'baz', array()));
        $this->assertTrue($this->extractor->isWritable('PropertyInfo\Tests\Fixtures\Dummy', 'parent', array()));
        $this->assertFalse($this->extractor->isWritable('PropertyInfo\Tests\Fixtures\Dummy', 'a', array()));
        $this->assertTrue($this->extractor->isWritable('PropertyInfo\Tests\Fixtures\Dummy', 'b', array()));
        $this->assertFalse($this->extractor->isWritable('PropertyInfo\Tests\Fixtures\Dummy', 'c', array()));
        $this->assertFalse($this->extractor->isWritable('PropertyInfo\Tests\Fixtures\Dummy', 'd', array()));
        $this->assertTrue($this->extractor->isWritable('PropertyInfo\Tests\Fixtures\Dummy', 'e', array()));
        $this->assertTrue($this->extractor->isWritable('PropertyInfo\Tests\Fixtures\Dummy', 'f', array()));
    }

    /**
     * @dataProvider hackTypesProvider
     */
    public function testExtractHackType($property, array $type = null)
    {
        if (!method_exists('\ReflectionProperty', 'getTypeText')) {
            $this->markTestSkipped('Available only with HackLang.');
        }

        $this->assertEquals($type, $this->extractor->getTypes('PropertyInfo\Tests\Fixtures\HackDummy', $property));
    }

    public function hackTypesProvider()
    {
        return array(
            array('bool', array(new Type(Type::BUILTIN_TYPE_BOOL))),
            array('int', array(new Type(Type::BUILTIN_TYPE_INT))),
            array('float', array(new Type(Type::BUILTIN_TYPE_FLOAT))),
            array('string', array(new Type(Type::BUILTIN_TYPE_STRING))),
            array('array', array(new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true))),
            array('object', array(new Type(Type::BUILTIN_TYPE_OBJECT, false, 'stdClass'))),

            array('boolNullable', array(new Type(Type::BUILTIN_TYPE_BOOL, true))),
            array('intNullable', array(new Type(Type::BUILTIN_TYPE_INT, true))),
            array('floatNullable', array(new Type(Type::BUILTIN_TYPE_FLOAT, true))),
            array('stringNullable', array(new Type(Type::BUILTIN_TYPE_STRING, true))),
            array('arrayNullable', array(new Type(Type::BUILTIN_TYPE_ARRAY, true, null, true))),
            array('objectNullable', array(new Type(Type::BUILTIN_TYPE_OBJECT, true, 'stdClass'))),

            array('boolArray', array(new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, new Type(Type::BUILTIN_TYPE_INT), new Type(Type::BUILTIN_TYPE_BOOL)))),
            array('intArray', array(new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, new Type(Type::BUILTIN_TYPE_INT), new Type(Type::BUILTIN_TYPE_INT)))),
            array('floatArray', array(new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, new Type(Type::BUILTIN_TYPE_INT), new Type(Type::BUILTIN_TYPE_FLOAT)))),
            array('stringArray', array(new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, new Type(Type::BUILTIN_TYPE_INT), new Type(Type::BUILTIN_TYPE_STRING)))),
            array('arrayArray', array(new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, new Type(Type::BUILTIN_TYPE_INT), new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true)))),
            array('objectArray', array(new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, new Type(Type::BUILTIN_TYPE_INT), new Type(Type::BUILTIN_TYPE_OBJECT, false, 'stdClass')))),

            array('boolArrayString', array(new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, new Type(Type::BUILTIN_TYPE_STRING), new Type(Type::BUILTIN_TYPE_BOOL)))),
            array('intArrayString', array(new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, new Type(Type::BUILTIN_TYPE_STRING), new Type(Type::BUILTIN_TYPE_INT)))),
            array('floatArrayString', array(new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, new Type(Type::BUILTIN_TYPE_STRING), new Type(Type::BUILTIN_TYPE_FLOAT)))),
            array('stringArrayString', array(new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, new Type(Type::BUILTIN_TYPE_STRING), new Type(Type::BUILTIN_TYPE_STRING)))),
            array('arrayArrayString', array(new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, new Type(Type::BUILTIN_TYPE_STRING), new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true)))),
            array('objectArrayString', array(new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, new Type(Type::BUILTIN_TYPE_STRING), new Type(Type::BUILTIN_TYPE_OBJECT, false, 'stdClass')))),

            array('boolNullableVector', array(new Type(Type::BUILTIN_TYPE_OBJECT, true, 'HH\Vector', true, new Type(Type::BUILTIN_TYPE_INT), new Type(Type::BUILTIN_TYPE_BOOL)))),
            array('intNullableVector', array(new Type(Type::BUILTIN_TYPE_OBJECT, true, 'HH\Vector', true, new Type(Type::BUILTIN_TYPE_INT), new Type(Type::BUILTIN_TYPE_INT)))),
            array('floatNullableVector', array(new Type(Type::BUILTIN_TYPE_OBJECT, true, 'HH\Vector', true, new Type(Type::BUILTIN_TYPE_INT), new Type(Type::BUILTIN_TYPE_FLOAT)))),
            array('stringNullableVector', array(new Type(Type::BUILTIN_TYPE_OBJECT, true, 'HH\Vector', true, new Type(Type::BUILTIN_TYPE_INT), new Type(Type::BUILTIN_TYPE_STRING)))),
            array('arrayNullableVector', array(new Type(Type::BUILTIN_TYPE_OBJECT, true, 'HH\Vector', true, new Type(Type::BUILTIN_TYPE_INT), new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true)))),
            array('objectNullableVector', array(new Type(Type::BUILTIN_TYPE_OBJECT, true, 'HH\Vector', true, new Type(Type::BUILTIN_TYPE_INT), new Type(Type::BUILTIN_TYPE_OBJECT, false, 'stdClass')))),
        );
    }
}
