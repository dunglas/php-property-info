<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\PropertyInfo\Tests;

use PropertyInfo\PropertyInfo;
use PropertyInfo\Tests\Fixtures\DummyExtractor;
use PropertyInfo\Type;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class PropertyInfoTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PropertyInfo
     */
    private $propertyInfo;

    public function setUp()
    {
        $extractors = array(new DummyExtractor());
        $this->propertyInfo = new PropertyInfo($extractors, $extractors, $extractors, $extractors);
    }

    public function testInstanceOf()
    {
        $this->assertInstanceOf('PropertyInfo\PropertyInfoInterface', $this->propertyInfo);
        $this->assertInstanceOf('PropertyInfo\PropertyTypeInfoInterface', $this->propertyInfo);
        $this->assertInstanceOf('PropertyInfo\PropertyDescriptionInfoInterface', $this->propertyInfo);
        $this->assertInstanceOf('PropertyInfo\PropertyAccessInfoInterface', $this->propertyInfo);
    }

    public function testGetShortDescription()
    {
        $this->assertSame('short', $this->propertyInfo->getShortDescription('Foo', 'bar', array()));
    }

    public function testGetLongDescription()
    {
        $this->assertSame('long', $this->propertyInfo->getLongDescription('Foo', 'bar', array()));
    }

    public function testGetTypes()
    {
        $this->assertEquals(array(new Type(Type::BUILTIN_TYPE_INT)), $this->propertyInfo->getTypes('Foo', 'bar', array()));
    }

    public function testIsReadable()
    {
        $this->assertTrue($this->propertyInfo->isReadable('Foo', 'bar', array()));
    }

    public function testIsWritable()
    {
        $this->assertTrue($this->propertyInfo->isWritable('Foo', 'bar', array()));
    }

    public function testGetProperties()
    {
        $this->assertEquals(array('a', 'b'), $this->propertyInfo->getProperties('Foo'));
    }
}
