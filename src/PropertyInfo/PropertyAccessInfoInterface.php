<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo;

/**
 * Guesses if the property can be accessed or mutated.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
interface PropertyAccessInfoInterface
{
    /**
     * Is the property readable?
     *
     * @param string $class
     * @param string $property
     * @param array  $context
     *
     * @return bool|null
     */
    public function isReadable($class, $property, array $context = array());

    /**
     * Is the property writable?
     *
     * @param string $class
     * @param string $property
     * @param array  $context
     *
     * @return bool|null
     */
    public function isWritable($class, $property, array $context = array());
}
