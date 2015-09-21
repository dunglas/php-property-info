<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\Tests\Fixtures;

use PropertyInfo\PropertyAccessInfoInterface;
use PropertyInfo\PropertyDescriptionInfoInterface;
use PropertyInfo\PropertyListRetrieverInterface;
use PropertyInfo\PropertyTypeInfoInterface;
use PropertyInfo\Type;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class DummyExtractor implements PropertyListRetrieverInterface, PropertyDescriptionInfoInterface, PropertyTypeInfoInterface, PropertyAccessInfoInterface
{
    /**
     * {@inheritdoc}
     */
    public function getShortDescription($class, $property, array $context = array())
    {
        return 'short';
    }

    /**
     * {@inheritdoc}
     */
    public function getLongDescription($class, $property, array $context = array())
    {
        return 'long';
    }

    /**
     * {@inheritdoc}
     */
    public function getTypes($class, $property, array $context = array())
    {
        return array(new Type(Type::BUILTIN_TYPE_INT));
    }

    /**
     * {@inheritdoc}
     */
    public function isReadable($class, $property, array $context = array())
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isWritable($class, $property, array $context = array())
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getProperties($class, array $context = array())
    {
        return array('a', 'b');
    }
}
