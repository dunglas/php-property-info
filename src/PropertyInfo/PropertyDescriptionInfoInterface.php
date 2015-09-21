<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo;

/**
 * Description extractor Interface.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
interface PropertyDescriptionInfoInterface
{
    /**
     * Gets the short description of the property.
     *
     * @param string $class
     * @param string $property
     * @param array  $context
     *
     * @return string|null
     */
    public function getShortDescription($class, $property, array $context = array());

    /**
     * Gets the long description of the property.
     *
     * @param string $class
     * @param string $property
     * @param array  $context
     *
     * @return string|null
     */
    public function getLongDescription($class, $property, array $context = array());
}
