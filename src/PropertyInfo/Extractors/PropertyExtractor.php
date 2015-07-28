<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\Extractors;

use PropertyInfo\Type;

/**
 * Property Extractor.
 *
 * @author Mihai Stancu <stancu.t.mihai@gmail.com>
 */
class PropertyExtractor extends NativeExtractor
{
    /**
     * @param \ReflectionProperty $property
     *
     * @return array|Type[]
     */
    public function extractTypes(\ReflectionProperty $property)
    {
        $typeInfo = $this->typeInfoParser->getPropertyType($property);

        return $this->typeInfoParser->parse($typeInfo);
    }
}
