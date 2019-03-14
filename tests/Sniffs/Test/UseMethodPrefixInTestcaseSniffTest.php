<?php declare(strict_types = 1);

namespace FlaconiCodingStandard\Sniffs\Test;

use SlevomatCodingStandard\Sniffs\TestCase;

/**
 * @author Alexander Miehe <alexander.miehe@flaconi.de>
 *
 * @covers \FlaconiCodingStandard\Sniffs\Test\UseMethodPrefixInTestcaseSniff
 * @covers \FlaconiCodingStandard\Sniffs\Test\UseStaticCallsForAssertInTestcaseSniff
 * @covers \FlaconiCodingStandard\Helper\TestClassHelper
 */
class UseMethodPrefixInTestcaseSniffTest extends TestCase
{
    public function testErrors(): void
    {
        $report = self::checkFile(__DIR__.'/Data/ClassWithTestAnnotation.php');

        self::assertEquals(1, $report->getErrorCount());
        self::assertSniffError($report, 10, UseMethodPrefixInTestcaseSniff::TEST_ANNOTATION_USAGE_FOUND, 'The PhpUnit TestCase is using a @test annotation instead of method prefix');

        self::assertAllFixedInFile($report);
    }

    public function testIgnoreNonTestClass(): void
    {
        $report = self::checkFile(__DIR__.'/Data/FileWithoutClass.php');

        self::assertEquals(0, $report->getErrorCount());
    }
}
