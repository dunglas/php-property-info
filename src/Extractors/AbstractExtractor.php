<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\Extractors;

/**
 * Extractor Interface.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
trait PropertyAwareTrait
{
    /**
     * @var \ReflectionProperty
     */
    protected $reflectionProperty;

    /**
     * Sets the property to work with.
     *
     * @param \ReflectionProperty $reflectionProperty
     */
    public function setReflectionProperty(\ReflectionProperty $reflectionProperty)
    {
        $this->reflectionProperty = $reflectionProperty;
    }
}
