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
     * @param \ReflectionProperty $reflectionProperty
     *
     * @return string|null
     */
    public function getShortDescription(\ReflectionProperty $reflectionProperty);

    /**
     * Gets the short description of the property.
     *
     * @param \ReflectionProperty $reflectionProperty
     *
     * @return string|null
     */
    public function getLongDescription(\ReflectionProperty $reflectionProperty);

    /**
     * Gets types of the property.
     *
     * @param \ReflectionProperty $reflectionProperty
     *
     * @return Type[]|null
     */
    public function getTypes(\ReflectionProperty $reflectionProperty);
}
