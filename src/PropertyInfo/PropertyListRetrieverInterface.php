<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo;

/**
 * Extracts the list of properties available for the given class.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
interface PropertyListRetrieverInterface
{
    /**
     * Gets the list of properties available for the given class.
     *
     * @param string $class
     * @param array  $context
     *
     * @return string[]|null
     */
    public function getProperties($class, array $context = array());
}
