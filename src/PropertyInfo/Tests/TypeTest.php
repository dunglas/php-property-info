<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\PropertyInfo\Tests;

use PropertyInfo\Type;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class TypeTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $type = new Type('object', true, 'ArrayObject', true, new Type('int'), new Type('string'));

        $this->assertEquals(Type::BUILTIN_TYPE_OBJECT, $type->getBuiltinType());
        $this->assertTrue($type->isNullable());
        $this->assertEquals('ArrayObject', $type->getClassName());
        $this->assertTrue($type->isCollection());

        $collectionKeyType = $type->getCollectionKeyType();
        $this->assertInstanceOf('PropertyInfo\Type', $collectionKeyType);
        $this->assertEquals(Type::BUILTIN_TYPE_INT, $collectionKeyType->getBuiltinType());

        $collectionValueType = $type->getCollectionValueType();
        $this->assertInstanceOf('PropertyInfo\Type', $collectionValueType);
        $this->assertEquals(Type::BUILTIN_TYPE_STRING, $collectionValueType->getBuiltinType());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage "foo" is not a PHP valid type.
     */
    public function testInvalidType()
    {
        new Type('foo');
    }
}
