<?php

namespace spec\FSi\Bundle\AdminPositionableBundle;

use PhpSpec\ObjectBehavior;

class FSiAdminPositionableBundleSpec extends ObjectBehavior
{
    function it_is_bundle()
    {
        $this->shouldHaveType('Symfony\Component\HttpKernel\Bundle\Bundle');
    }
}
