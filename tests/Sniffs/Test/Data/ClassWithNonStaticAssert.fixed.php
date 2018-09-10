<?php

namespace FlaconiCodingStandard\Sniffs\Test\Data;

use PHPUnit\Framework\TestCase;

class ClassWithNonStaticAssert extends TestCase
{
    public function execute()
    {
        self::assertTrue(true);
        $this->dummy();
    }

    public function dummy()
    {

    }
}