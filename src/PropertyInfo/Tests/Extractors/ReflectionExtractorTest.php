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

    /**
     * @dataProvider typesProvider
     */
    public function testExtractors($property, array $type = null)
    {
        $this->assertEquals($type, $this->extractor->getTypes('PropertyInfo\Tests\Fixtures\Dummy', $property));
    }

    public function typesProvider()
    {
        return array(
            array('a', null),
            array('b', array(new Type(Type::BUILTIN_TYPE_OBJECT, true, 'PropertyInfo\Tests\Fixtures\ParentDummy'))),
            array('c', null),
            array('d', null),
            array('e', null),
            array('f', array(new Type(Type::BUILTIN_TYPE_ARRAY, false, null, true, new Type(Type::BUILTIN_TYPE_INT), new Type(Type::BUILTIN_TYPE_OBJECT, false, 'DateTime')))),
        );
    }
}
