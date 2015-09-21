<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\Extractors;

use PropertyInfo\PropertyListRetrieverInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;

/**
 * Lists available properties using Symfony Serializer Component metadata.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class SerializerExtractor implements PropertyListRetrieverInterface
{
    /**
     * @var ClassMetadataFactoryInterface
     */
    private $classMetadataFactory;

    public function __construct(ClassMetadataFactoryInterface $classMetadataFactory)
    {
        $this->classMetadataFactory = $classMetadataFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getProperties($class, array $context = array())
    {
        if (!isset($context['serializer_groups']) || !is_array($context['serializer_groups'])) {
            return;
        }

        if (!$this->classMetadataFactory->getMetadataFor($class)) {
            return;
        }

        $properties = array();
        $serializerClassMetadata = $this->classMetadataFactory->getMetadataFor($class);

        foreach ($serializerClassMetadata->getAttributesMetadata() as $serializerAttributeMetadata) {
            if (count(array_intersect($context['serializer_groups'], $serializerAttributeMetadata->getGroups())) > 0) {
                $properties[] = $serializerAttributeMetadata->getName();
            }
        }

        return $properties;
    }
}
