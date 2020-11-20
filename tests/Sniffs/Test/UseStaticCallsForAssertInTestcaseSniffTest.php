<?php

declare(strict_types=1);

namespace FlaconiCodingStandard\Sniffs\Test;

use SlevomatCodingStandard\Sniffs\TestCase;

/**
 * @covers \FlaconiCodingStandard\Sniffs\Test\UseStaticCallsForAssertInTestcaseSniff
 * @covers \FlaconiCodingStandard\Sniffs\Test\UseMethodPrefixInTestcaseSniff
 * @covers \FlaconiCodingStandard\Helper\TestClassHelper
 */
class UseStaticCallsForAssertInTestcaseSniffTest extends TestCase
{
    public function testErrors(): void
    {
        $report = self::checkFile(__DIR__ . '/Data/ClassWithNonStaticAssert.php');

        self::assertEquals(1, $report->getErrorCount());
        self::assertSniffError($report, 11, UseStaticCallsForAssertInTestcaseSniff::CODE_NON_STATIC_ASSERTION_METHOD_USAGE, 'The PhpUnit TestCase is using a non static usage of Assertion Methods');

        self::assertAllFixedInFile($report);
    }

    public function testIgnoreNonTestClass(): void
    {
        $report = self::checkFile(__DIR__ . '/Data/FileWithoutClass.php');

        self::assertEquals(0, $report->getErrorCount());
    }
}
