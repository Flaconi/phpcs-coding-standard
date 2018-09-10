<?php

namespace FlaconiCodingStandard\Sniffs\Test\Data;

use PHPUnit\Framework\TestCase;

class ClassWithTestAnnotation extends TestCase
{
    /**
     *
     */
    public function testExecute()
    {
        $this->assertTrue(true);
    }
}