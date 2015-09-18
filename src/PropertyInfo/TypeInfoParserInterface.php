<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo;

/**
 *     Type info parser interface. Implementing classes of this interface should be able to retrieve information about a
 * property based on the given \ReflectionProperty.
 *
 *     The information returned should be a string representation of the type information.
 *
 *     Implementing classes should also be able to parse this string and return Type[] objects based on the it.
 *
 * @author Mihai Stancu <stancu.t.mihai@gmail.com>
 */
interface TypeInfoParserInterface
{
    /**
     * @param \ReflectionProperty $property
     *
     * @return string|null
     */
    public function getPropertyType(\ReflectionProperty $property);

    /**
     * @param string $info
     *
     * @return array|Type[]
     */
    public function parse($info);
}
