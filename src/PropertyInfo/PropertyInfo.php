<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo;

use PropertyInfo\Extractors\DescriptionExtractorInterface;
use PropertyInfo\Extractors\TypeExtractorInterface;

/**
 * Gets info about PHP class properties.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class PropertyInfo
{
    /**
     * @var \ReflectionProperty
     */
    private $reflectionProperty;
    /**
     * @var TypeExtractorInterface[]
     */
    private $typeExtractors;
    /**
     * @var DescriptionExtractorInterface[]
     */
    private $descriptionExtractors;

    public function __construct(
        \ReflectionProperty $reflectionProperty,
        array $typeExtractors,
        array $descriptionExtractors
    ) {
        $this->reflectionProperty = $reflectionProperty;
        $this->typeExtractors = $typeExtractors;
        $this->descriptionExtractors = $descriptionExtractors;
    }

    /**
     * Gets the short description of the property.
     *
     * @return string|null
     */
    public function getShortDescription()
    {
        foreach ($this->descriptionExtractors as $extractor) {
            $desc = $extractor->extractShortDescription($this->reflectionProperty);
            if (null !== $desc) {
                return $desc;
            }
        }
    }

    /**
     * Gets the short description of the property.
     *
     * @return string|null
     */
    public function getLongDescription()
    {
        foreach ($this->descriptionExtractors as $extractor) {
            $desc = $extractor->extractLongDescription($this->reflectionProperty);
            if (null !== $desc) {
                return $desc;
            }
        }
    }

    /**
     * Gets types of the property.
     *
     * @return Type[]|null
     */
    public function getTypes()
    {
        foreach ($this->typeExtractors as $extractor) {
            $type = $extractor->extractTypes($this->reflectionProperty);
            if (null !== $type) {
                return $type;
            }
        }
    }
}
