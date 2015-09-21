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
     * @var PropertyAccessInfoInterface[]
     */
    private $accessExtractors;

    /**
     * @param TypeExtractorInterface[]           $typeExtractors
     * @param PropertyDescriptionInfoInterface[] $descriptionExtractors
     * @param PropertyAccessInfoInterface[]      $accessExtractors
     */
    public function __construct(
        array $typeExtractors = array(),
        array $descriptionExtractors = array(),
        array $accessExtractors = array()
    ) {
        $this->typeExtractors = $typeExtractors;
        $this->descriptionExtractors = $descriptionExtractors;
        $this->accessExtractors = $accessExtractors;
    }

    /**
     * {@inheritdoc}
     */
    public function getShortDescription($class, $property, array $context = array())
    {
        return $this->extract($this->descriptionExtractors, 'getShortDescription', $class, $property, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function getLongDescription($class, $property, array $context = array())
    {
        return $this->extract($this->descriptionExtractors, 'getLongDescription', $class, $property, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function getTypes($class, $property, array $context = array())
    {
        return $this->extract($this->typeExtractors, 'getTypes', $class, $property, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function isReadable($class, $property, array $context = array())
    {
        return $this->extract($this->accessExtractors, 'isReadable', $class, $property, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function isWritable($class, $property, array $context = array())
    {
        return $this->extract($this->accessExtractors, 'isWritable', $class, $property, $context);
    }

    /**
     * Iterates over registered extractors and return the first value found.
     *
     * @param array  $extractors
     * @param string $method
     * @param string $class
     * @param string $property
     * @param array  $context
     *
     * @return mixed
     */
    private function extract(array $extractors, $method, $class, $property, array $context)
    {
        foreach ($extractors as $extractor) {
            $value = $extractor->$method($class, $property, $context);
            if (null !== $value) {
                return $value;
            }
        }
    }
}
