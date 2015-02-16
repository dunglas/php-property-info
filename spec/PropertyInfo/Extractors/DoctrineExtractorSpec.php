<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace spec\PropertyInfo\Extractors;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use PhpSpec\ObjectBehavior;

/**
 * DoctrineExtractor Spec.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class DoctrineExtractorSpec extends ObjectBehavior
{
    public function let()
    {
        $config = Setup::createAnnotationMetadataConfiguration([__DIR__], true);
        $entityManager = EntityManager::create(['driver' => 'pdo_sqlite'], $config);

        $this->beConstructedWith($entityManager->getMetadataFactory());
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('PropertyInfo\Extractors\DoctrineExtractor');
        $this->shouldHaveType('PropertyInfo\TypeExtractorInterface');
    }

    public function it_extracts_int()
    {
        $reflectionProperty = new \ReflectionProperty(__NAMESPACE__.'\DoctrineDummy', 'id');
        $type = $this->extractTypes($reflectionProperty)[0];

        $type->getType()->shouldReturn('int');
        $type->getClass()->shouldBeNull();
        $type->shouldNotBeCollection();
        $type->getCollectionType()->shouldBeNull();
    }

    public function it_extracts_string()
    {
        $reflectionProperty = new \ReflectionProperty(__NAMESPACE__.'\DoctrineDummy', 'guid');
        $type = $this->extractTypes($reflectionProperty)[0];

        $type->getType()->shouldReturn('string');
        $type->getClass()->shouldBeNull();
        $type->shouldNotBeCollection();
        $type->getCollectionType()->shouldBeNull();
    }

    public function it_extracts_bool()
    {
        $reflectionProperty = new \ReflectionProperty(__NAMESPACE__.'\DoctrineDummy', 'bool');
        $type = $this->extractTypes($reflectionProperty)[0];

        $type->getType()->shouldReturn('bool');
        $type->getClass()->shouldBeNull();
        $type->shouldNotBeCollection();
        $type->getCollectionType()->shouldBeNull();
    }

    public function it_extracts_resource()
    {
        $reflectionProperty = new \ReflectionProperty(__NAMESPACE__.'\DoctrineDummy', 'binary');
        $type = $this->extractTypes($reflectionProperty)[0];

        $type->getType()->shouldReturn('resource');
        $type->getClass()->shouldBeNull();
        $type->shouldNotBeCollection();
        $type->getCollectionType()->shouldBeNull();
    }

    public function it_extracts_datetime()
    {
        $reflectionProperty = new \ReflectionProperty(__NAMESPACE__.'\DoctrineDummy', 'time');
        $type = $this->extractTypes($reflectionProperty)[0];

        $type->getType()->shouldReturn('object');
        $type->getClass()->shouldReturn('DateTime');
        $type->shouldNotBeCollection();
        $type->getCollectionType()->shouldBeNull();
    }

    public function it_extracts_array()
    {
        $reflectionProperty = new \ReflectionProperty(__NAMESPACE__.'\DoctrineDummy', 'json');
        $type = $this->extractTypes($reflectionProperty)[0];

        $type->getType()->shouldReturn('array');
        $type->getClass()->shouldBeNull();
        $type->shouldBeCollection();
        $type->getCollectionType()->shouldBeNull();
    }

    public function it_extracts_class()
    {
        $reflectionProperty = new \ReflectionProperty(__NAMESPACE__.'\DoctrineDummy', 'foo');
        $type = $this->extractTypes($reflectionProperty)[0];

        $type->getType()->shouldReturn('object');
        $type->getClass()->shouldReturn(__NAMESPACE__.'\DoctrineRelation');
        $type->shouldNotBeCollection();
        $type->getCollectionType()->shouldBeNull();
    }

    public function it_extracts_class_collection()
    {
        $reflectionProperty = new \ReflectionProperty(__NAMESPACE__.'\DoctrineDummy', 'bar');
        $type = $this->extractTypes($reflectionProperty)[0];

        $type->getType()->shouldReturn('object');
        $type->getClass()->shouldReturn('Doctrine\Common\Collections\Collection');
        $type->shouldBeCollection();

        $collectionType = $type->getCollectionType();
        $collectionType->getType()->shouldReturn('object');
        $collectionType->getClass()->shouldReturn(__NAMESPACE__.'\DoctrineRelation');
        $collectionType->shouldNotBeCollection();
        $collectionType->getCollectionType()->shouldBeNull();
    }

    public function it_cannot_guess()
    {
        $reflectionProperty = new \ReflectionProperty(__NAMESPACE__.'\DoctrineDummy', 'notMapped');
        $this->extractTypes($reflectionProperty)->shouldBeNull();
    }
}

/**
 * @Entity
 */
class DoctrineDummy
{
    /**
     * @Id
     * @Column(type="smallint")
     */
    public $id;
    /**
     * @ManyToOne(targetEntity="DoctrineRelation")
     */
    public $foo;
    /**
     * @ManyToMany(targetEntity="DoctrineRelation")
     */
    public $bar;
    /**
     * @Column(type="guid")
     */
    protected $guid;
    /**
     * @Column(type="time")
     */
    private $time;
    /**
     * @Column(type="json_array")
     */
    private $json;
    /**
     * @Column(type="boolean")
     */
    private $bool;
    /**
     * @Column(type="binary")
     */
    private $binary;
    public $notMapped;
}

/**
 * @Entity
 */
class DoctrineRelation
{
    /**
     * @Id
     * @Column(type="smallint")
     */
    public $id;
}
