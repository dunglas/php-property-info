<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\Tests\Fixtures;

use PropertyInfo\PropertyDescriptionInfoInterface;
use PropertyInfo\PropertyTypeInfoInterface;
use PropertyInfo\Type;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class DummyExtractor implements PropertyDescriptionInfoInterface, PropertyTypeInfoInterface
{
    /**
     * {@inheritdoc}
     */
    public function getShortDescription($class, $property)
    {
        return 'short';
    }

    /**
     * {@inheritdoc}
     */
    public function getLongDescription($class, $property)
    {
        return 'long';
    }

    /**
     * {@inheritdoc}
     */
    public function getTypes($class, $property)
    {
        return array(new Type(Type::BUILTIN_TYPE_INT));
    }
}
