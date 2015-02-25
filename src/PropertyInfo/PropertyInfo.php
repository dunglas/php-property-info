<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo;

class PropertyInfo implements PropertyInfoInterface
{
    /**
     * @var TypeExtractorInterface[]
     */
    private $typeExtractors;
    /**
     * @var DescriptionExtractorInterface[]
     */
    private $descriptionExtractors;

    /**
     * @param TypeExtractorInterface[]        $typeExtractors
     * @param DescriptionExtractorInterface[] $descriptionExtractors
     */
    public function __construct(
        array $typeExtractors,
        array $descriptionExtractors
    ) {
        $this->typeExtractors = $typeExtractors;
        $this->descriptionExtractors = $descriptionExtractors;
    }

    public function getShortDescription(\ReflectionProperty $reflectionProperty)
    {
        foreach ($this->descriptionExtractors as $extractor) {
            $desc = $extractor->extractShortDescription($reflectionProperty);
            if (null !== $desc) {
                return $desc;
            }
        }
    }

    public function getLongDescription(\ReflectionProperty $reflectionProperty)
    {
        foreach ($this->descriptionExtractors as $extractor) {
            $desc = $extractor->extractLongDescription($reflectionProperty);
            if (null !== $desc) {
                return $desc;
            }
        }
    }

    public function getTypes(\ReflectionProperty $reflectionProperty)
    {
        foreach ($this->typeExtractors as $extractor) {
            $type = $extractor->extractTypes($reflectionProperty);
            if (null !== $type) {
                return $type;
            }
        }
    }
}
