<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo;

/**
 * Gets info about PHP class properties.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
interface PropertyInfoInterface
{
    /**
     * Gets the short description of the property.
     *
     * @param string $class
     * @param string $property
     *
     * @return string|null
     */
    public function getShortDescription($class, $property);

    /**
     * Gets the short description of the property.
     *
     * @param string $class
     * @param string $property
     *
     * @return string|null
     */
    public function getLongDescription($class, $property);

    /**
     * Gets types of the property.
     *
     * @param string $class
     * @param string $property
     *
     * @return Type[]|null
     */
    public function getTypes($class, $property);
}
