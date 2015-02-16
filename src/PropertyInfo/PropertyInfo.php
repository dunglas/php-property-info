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
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class PropertyInfo
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

    /**
     * Gets the short description of the property.
     *
     * @param \ReflectionProperty $reflectionProperty
     *
     * @return string|null
     */
    public function getShortDescription(\ReflectionProperty $reflectionProperty)
    {
        foreach ($this->descriptionExtractors as $extractor) {
            $desc = $extractor->extractShortDescription($reflectionProperty);
            if (null !== $desc) {
                return $desc;
            }
        }
    }

    /**
     * Gets the short description of the property.
     *
     * @param \ReflectionProperty $reflectionProperty
     *
     * @return string|null
     */
    public function getLongDescription(\ReflectionProperty $reflectionProperty)
    {
        foreach ($this->descriptionExtractors as $extractor) {
            $desc = $extractor->extractLongDescription($reflectionProperty);
            if (null !== $desc) {
                return $desc;
            }
        }
    }

    /**
     * Gets types of the property.
     *
     * @param \ReflectionProperty $reflectionProperty
     *
     * @return Type[]|null
     */
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
