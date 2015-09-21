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
 * A convenient interface inheriting all specific info interfaces.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
interface PropertyInfoInterface extends PropertyTypeInfoInterface, PropertyDescriptionInfoInterface, PropertyAccessInfoInterface, PropertyListRetrieverInterface
{
}
