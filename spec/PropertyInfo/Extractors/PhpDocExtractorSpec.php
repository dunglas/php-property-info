<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace spec\PropertyInfo\Extractors;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * PhpDocExtractor Spec.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class PhpDocExtractorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('PropertyInfo\Extractors\PhpDocExtractor');
    }

    function it_extracts_short_description()
    {
        $reflectionProperty = new \ReflectionProperty(__NAMESPACE__.'\\PhpDocDummy', 'foo');
        $this->extractShortDescription($reflectionProperty)->shouldReturn('Short description.');
    }

    function it_extracts_long_description()
    {
        $reflectionProperty = new \ReflectionProperty(__NAMESPACE__.'\\PhpDocDummy', 'foo');
        $this->extractLongDescription($reflectionProperty)->shouldReturn('Long description.');
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
}

class PhpDocDummy extends PhpDocParent
{
}
