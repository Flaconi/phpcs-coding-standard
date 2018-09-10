<?php

namespace FlaconiCodingStandard\Sniffs\Test\Data;

use PHPUnit\Framework\TestCase;

class ClassWithNonStaticAssert extends TestCase
{
    public function execute()
    {
        $this->assertTrue(true);
        $this->dummy();
    }

    public function dummy()
    {

    }
}