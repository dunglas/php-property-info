<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace spec\PropertyInfo;

use PhpSpec\ObjectBehavior;
use PropertyInfo\DescriptionExtractorInterface;
use PropertyInfo\Type;
use PropertyInfo\TypeExtractorInterface;
use Prophecy\Argument;

/**
 * PropertyInfo Spec.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class PropertyInfoSpec extends ObjectBehavior
{
    public function let(
        TypeExtractorInterface $typeExtractor1,
        TypeExtractorInterface $typeExtractor2,
        DescriptionExtractorInterface $descriptionExtractor1,
        DescriptionExtractorInterface $descriptionExtractor2,
        Type $type1,
        Type $type2
    ) {
        $typeExtractor1->extractTypes(Argument::type('\ReflectionProperty'))->willReturn(null);
        $typeExtractor2->extractTypes(Argument::type('\ReflectionProperty'))->willReturn([$type1, $type2]);

        $descriptionExtractor1->extractShortDescription(Argument::type('\ReflectionProperty'))->willReturn(null);
        $descriptionExtractor1->extractLongDescription(Argument::type('\ReflectionProperty'))->willReturn(null);
        $descriptionExtractor2->extractShortDescription(Argument::type('\ReflectionProperty'))->willReturn('short desc');
        $descriptionExtractor2->extractLongDescription(Argument::type('\ReflectionProperty'))->willReturn('long desc');

        $this->beConstructedWith([$typeExtractor1, $typeExtractor2], [$descriptionExtractor1, $descriptionExtractor2]);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('PropertyInfo\PropertyInfo');
        $this->shouldHaveType('PropertyInfo\PropertyInfoInterface');
    }

    public function it_gets_types(\ReflectionProperty $property)
    {
        $types = $this->getTypes($property);
        $types[0]->shouldHaveType('PropertyInfo\Type');
        $types[1]->shouldHaveType('PropertyInfo\Type');
    }

    public function it_gets_short_description(\ReflectionProperty $property)
    {
        $this->getShortDescription($property)->shouldBe('short desc');
    }

    public function it_gets_long_description(\ReflectionProperty $property)
    {
        $this->getLongDescription($property)->shouldBe('long desc');
    }
}
