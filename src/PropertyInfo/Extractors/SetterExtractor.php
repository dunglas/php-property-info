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
 * Setter Extractor.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class SetterExtractor extends NativeExtractor
{
    /**
     * @param \ReflectionProperty $reflectionProperty
     *
     * @return array|Type[]|null
     */
    public function extractTypes(\ReflectionProperty $reflectionProperty)
    {
        $typeInfo = $this->typeInfoParser->getSetterParamType($reflectionProperty);

        return $this->typeInfoParser->parse($typeInfo);
    }
}
