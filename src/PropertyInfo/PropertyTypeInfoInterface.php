<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo;

/**
 * Type Extractor Interface.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
interface PropertyTypeInfoInterface
{
    /**
     * Gets types of a property.
     *
     * @param string $class
     * @param string $property
     * @param array  $context
     *
     * @return Type[]|null
     */
    public function getTypes($class, $property, array $context = array());
}
