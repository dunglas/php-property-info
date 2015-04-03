<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace spec\PropertyInfo\Extractors;

use PhpSpec\ObjectBehavior;

/**
 * PhpDocExtractor Spec.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class PhpDocExtractorSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('PropertyInfo\Extractors\PhpDocExtractor');
        $this->shouldHaveType('PropertyInfo\DescriptionExtractorInterface');
        $this->shouldHaveType('PropertyInfo\TypeExtractorInterface');
    }

    public function it_extracts_short_description()
    {
        $reflectionProperty = new \ReflectionProperty(__NAMESPACE__.'\PhpDocDummy', 'foo');
        $this->extractShortDescription($reflectionProperty)->shouldReturn('Short description.');
    }

    public function it_extracts_short_description_from_var()
    {
        $reflectionProperty = new \ReflectionProperty(__NAMESPACE__.'\PhpDocDummy', 'bar');
        $this->extractShortDescription($reflectionProperty)->shouldReturn('This is bar.');
    }

    public function it_respects_short_description_priority()
    {
        $reflectionProperty = new \ReflectionProperty(__NAMESPACE__.'\PhpDocDummy', 'baz');
        $this->extractShortDescription($reflectionProperty)->shouldReturn('Should be used.');
    }

    public function it_extracts_long_description()
    {
        $reflectionProperty = new \ReflectionProperty(__NAMESPACE__.'\PhpDocDummy', 'foo');
        $this->extractLongDescription($reflectionProperty)->shouldReturn('Long description.');
    }

    public function it_extracts_scalar_type()
    {
        $reflectionProperty = new \ReflectionProperty(__NAMESPACE__.'\PhpDocDummy', 'bar');
        $type = $this->extractTypes($reflectionProperty)[0];

        $type->getType()->shouldReturn('string');
        $type->getClass()->shouldBeNull();
        $type->shouldNotBeCollection();
        $type->getCollectionType()->shouldBeNull();
    }

    public function it_normalizes_int()
    {
        $reflectionProperty = new \ReflectionProperty(__NAMESPACE__.'\PhpDocDummy', 'baz');
        $type = $this->extractTypes($reflectionProperty)[0];

        $type->getType()->shouldReturn('int');
        $type->getClass()->shouldBeNull();
        $type->shouldNotBeCollection();
        $type->getCollectionType()->shouldBeNull();
    }

    public function it_normalizes_float()
    {
        $reflectionProperty = new \ReflectionProperty(__NAMESPACE__.'\PhpDocDummy', 'foo2');
        $type = $this->extractTypes($reflectionProperty)[0];

        $type->getType()->shouldReturn('float');
        $type->getClass()->shouldBeNull();
        $type->shouldNotBeCollection();
        $type->getCollectionType()->shouldBeNull();
    }

    public function it_normalizes_callable()
    {
        $reflectionProperty = new \ReflectionProperty(__NAMESPACE__.'\PhpDocDummy', 'foo3');
        $type = $this->extractTypes($reflectionProperty)[0];

        $type->getType()->shouldReturn('callable');
        $type->getClass()->shouldBeNull();
        $type->shouldNotBeCollection();
        $type->getCollectionType()->shouldBeNull();
    }

    public function it_normalizes_null()
    {
        $reflectionProperty = new \ReflectionProperty(__NAMESPACE__.'\PhpDocDummy', 'foo4');
        $type = $this->extractTypes($reflectionProperty)[0];

        $type->getType()->shouldReturn('null');
        $type->getClass()->shouldBeNull();
        $type->shouldNotBeCollection();
        $type->getCollectionType()->shouldBeNull();
    }

    public function it_cannot_guess()
    {
        $reflectionProperty = new \ReflectionProperty(__NAMESPACE__.'\PhpDocDummy', 'foo');
        $this->extractTypes($reflectionProperty)->shouldBeNull();

        $reflectionProperty = new \ReflectionProperty(__NAMESPACE__.'\PhpDocDummy', 'foo5');
        $this->extractTypes($reflectionProperty)->shouldBeNull();
    }

    public function it_extracts_fqn_class()
    {
        $reflectionProperty = new \ReflectionProperty(__NAMESPACE__.'\PhpDocDummy', 'bal');
        $type = $this->extractTypes($reflectionProperty)[0];

        $type->getType()->shouldReturn('object');
        $type->getClass()->shouldReturn('DateTime');
        $type->shouldNotBeCollection();
        $type->getCollectionType()->shouldBeNull();
    }

    public function it_extracts_local_class()
    {
        $reflectionProperty = new \ReflectionProperty(__NAMESPACE__.'\PhpDocDummy', 'parent');
        $type = $this->extractTypes($reflectionProperty)[0];

        $type->getType()->shouldReturn('object');
        $type->getClass()->shouldReturn(__NAMESPACE__.'\PhpDocParent');
        $type->shouldNotBeCollection();
        $type->getCollectionType()->shouldBeNull();
    }

    public function it_extracts_collection()
    {
        $reflectionProperty = new \ReflectionProperty(__NAMESPACE__.'\PhpDocDummy', 'collection');
        $type = $this->extractTypes($reflectionProperty)[0];

        $type->getType()->shouldReturn('array');
        $type->getClass()->shouldBeNull();
        $type->shouldBeCollection();

        $collectionType = $type->getCollectionType();
        $collectionType->getType()->shouldReturn('object');
        $collectionType->getClass()->shouldReturn('DateTime');
        $collectionType->shouldNotBeCollection();
    }

    public function it_extracts_several_types()
    {
        $reflectionProperty = new \ReflectionProperty(__NAMESPACE__.'\PhpDocDummy', 'files');
        list($type1, $type2) = $this->extractTypes($reflectionProperty);

        $type1->getType()->shouldReturn('array');
        $type1->getClass()->shouldBeNull();
        $type1->shouldBeCollection();

        $collectionType = $type1->getCollectionType();
        $collectionType->getType()->shouldReturn('object');
        $collectionType->getClass()->shouldReturn('SplFileInfo');
        $collectionType->shouldNotBeCollection();

        $type2->getType()->shouldReturn('resource');
        $type2->getClass()->shouldBeNull();
        $type2->shouldNotBeCollection();
    }
}

class PhpDocParent
{
    /**
     * Short description.
     *
     * Long description.
     */
    public $foo;
    /**
     * @var float
     */
    public $foo2;
    /**
     * @var callback
     */
    public $foo3;
    /**
     * @var void
     */
    public $foo4;
    /**
     * @var mixed
     */
    public $foo5;
    /**
     * @var \SplFileInfo[]|resource
     */
    public $files;
}

class PhpDocDummy extends PhpDocParent
{
    /**
     * @var string This is bar.
     */
    private $bar;
    /**
     * Should be used.
     *
     * @var int Should be ignored.
     */
    protected $baz;
    /**
     * @var \DateTime
     */
    public $bal;
    /**
     * @var PhpDocParent
     */
    public $parent;
    /**
     * @var \DateTime[]
     */
    public $collection;
}
