<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\PropertyInfo\Tests\Extractors;

use Doctrine\Common\Annotations\AnnotationReader;
use PropertyInfo\Extractors\SerializerExtractor;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class SerializerExtractorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SerializerExtractor
     */
    private $extractor;

    public function setUp()
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $this->extractor = new SerializerExtractor($classMetadataFactory);
    }

    public function testGetProperties()
    {
        $this->assertEquals(
            array('collection'),
            $this->extractor->getProperties('PropertyInfo\Tests\Fixtures\Dummy', array('serializer_groups' => array('a')))
        );
    }
}
