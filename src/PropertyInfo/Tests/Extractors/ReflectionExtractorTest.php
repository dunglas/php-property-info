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
}
