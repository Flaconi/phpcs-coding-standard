<?php

namespace FlaconiCodingStandard\Sniffs\Test\Data;

use PHPUnit\Framework\TestCase;

class ClassWithTestAnnotation extends TestCase
{
    /**
     * @test
     */
    public function execute()
    {
        $this->assertTrue(true);
    }
}