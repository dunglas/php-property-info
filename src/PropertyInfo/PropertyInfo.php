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
     * @var PropertyTypeInfoInterface[]
     */
    private $typeExtractors;
    /**
     * @var PropertyDescriptionInfoInterface[]
     */
    private $descriptionExtractors;

    /**
     * @param TypeExtractorInterface[]           $typeExtractors
     * @param PropertyDescriptionInfoInterface[] $descriptionExtractors
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
    public function getShortDescription($class, $property)
    {
        foreach ($this->descriptionExtractors as $extractor) {
            $desc = $extractor->getShortDescription($class, $property);
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
            $desc = $extractor->getLongDescription($class, $property);
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
            $type = $extractor->getTypes($class, $property);
            if (null !== $type) {
                return $type;
            }
        }
    }
}
