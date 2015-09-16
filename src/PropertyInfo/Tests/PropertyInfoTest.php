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
        $dummyExtractor = new DummyExtractor();
        $this->propertyInfo = new PropertyInfo(array($dummyExtractor), array($dummyExtractor));
    }

    public function testInstanceOf()
    {
        $this->assertInstanceOf('PropertyInfo\PropertyInfoInterface', $this->propertyInfo);
        $this->assertInstanceOf('PropertyInfo\PropertyTypeInfoInterface', $this->propertyInfo);
        $this->assertInstanceOf('PropertyInfo\PropertyDescriptionInfoInterface', $this->propertyInfo);
    }

    public function testGetShortDescription()
    {
        $this->assertSame('short', $this->propertyInfo->getShortDescription('foo', 'bar'));
    }

    public function testGetLongDescription()
    {
        $this->assertSame('long', $this->propertyInfo->getLongDescription('foo', 'bar'));
    }

    public function testGetTypes()
    {
        $this->assertEquals(array(new Type(Type::BUILTIN_TYPE_INT)), $this->propertyInfo->getTypes('foo', 'bar'));
    }
}
