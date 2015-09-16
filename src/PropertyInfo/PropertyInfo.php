<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo;

/**
 * Default {@see PropertyInfoInterface} implementation.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
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

    /**
     * {@inheritdoc}
     */
    public function getShortDescription($class, $propertye)
    {
        foreach ($this->descriptionExtractors as $extractor) {
            $desc = $extractor->extractShortDescription($class, $property);
            if (null !== $desc) {
                return $desc;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getLongDescription($class, $property)
    {
        foreach ($this->descriptionExtractors as $extractor) {
            $desc = $extractor->extractLongDescription($class, $property);
            if (null !== $desc) {
                return $desc;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTypes($class, $property)
    {
        foreach ($this->typeExtractors as $extractor) {
            $type = $extractor->extractTypes($class, $property);
            if (null !== $type) {
                return $type;
            }
        }
    }
}
