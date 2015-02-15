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
interface TypeExtractorInterface
{
    /**
     * Extracts the type.
     *
     * @param \ReflectionProperty $reflectionProperty
     *
     * @return Type[]|null
     */
    public function extractTypes(\ReflectionProperty $reflectionProperty);
}
