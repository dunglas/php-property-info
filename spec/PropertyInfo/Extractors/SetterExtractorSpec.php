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
 * SetterExtractor Spec.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class SetterExtractorSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('PropertyInfo\Extractors\SetterExtractor');
        $this->shouldHaveType('PropertyInfo\TypeExtractorInterface');
    }

    public function it_extracts_class()
    {
        $reflectionProperty = new \ReflectionProperty(__NAMESPACE__.'\SetterDummy', 'date');
        $type = $this->extractTypes($reflectionProperty)[0];
        $type->getType()->shouldReturn('object');
        $type->getClass()->shouldReturn('DateTime');
        $type->isCollection()->shouldReturn(false);
        $type->getCollectionType()->shouldBeNull();
    }

    public function it_extracts_array()
    {
        $reflectionProperty = new \ReflectionProperty(__NAMESPACE__.'\SetterDummy', 'array');
        $type = $this->extractTypes($reflectionProperty)[0];
        $type->getType()->shouldReturn('array');
        $type->getClass()->shouldBeNull();
        $type->isCollection()->shouldReturn(true);
        $type->getCollectionType()->shouldBeNull();
    }

    public function it_cannot_guess()
    {
        $reflectionProperty = new \ReflectionProperty(__NAMESPACE__.'\SetterDummy', 'noSetter');
        $this->extractTypes($reflectionProperty)->shouldBeNull();

        $reflectionProperty = new \ReflectionProperty(__NAMESPACE__.'\SetterDummy', 'tooManyParameters');
        $this->extractTypes($reflectionProperty)->shouldBeNull();
    }
}

class SetterDummy
{
    protected $array;
    public $date;
    private $noSetter;
    private $tooManyParameters;

    public function setArray(array $array)
    {
    }

    public function setDate(\DateTime $date)
    {
    }

    public function setTooManyParameters($a, $b)
    {
    }
}
