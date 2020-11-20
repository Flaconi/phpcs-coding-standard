<?php

declare(strict_types=1);

namespace FlaconiCodingStandard\Sniffs\Test;

use FlaconiCodingStandard\Helper\TestClassHelper;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\AnnotationHelper;
use SlevomatCodingStandard\Helpers\FunctionHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;

use function count;
use function ucfirst;

use const T_FUNCTION;
use const T_STRING;

class UseMethodPrefixInTestcaseSniff implements Sniff
{
    public const TEST_ANNOTATION_USAGE_FOUND = 'TestAnnotationUsageFound';

    /**
     * @return array<int>
     */
    public function register(): array
    {
        return [T_FUNCTION];
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingAnyTypeHint
     */
    public function process(File $phpcsFile, $stackPtr): void
    {
        if (! TestClassHelper::isTestCaseClass($phpcsFile)) {
            return;
        }

        $annotations = AnnotationHelper::getAnnotationsByName($phpcsFile, $stackPtr, '@test');

        if (count($annotations) === 0) {
            return;
        }

        $methodName = FunctionHelper::getName($phpcsFile, $stackPtr);

        $fix = $phpcsFile->addFixableError(
            'The PhpUnit TestCase is using a @test annotation instead of method prefix',
            $annotations[0]->getStartPointer(),
            self::TEST_ANNOTATION_USAGE_FOUND,
        );

        if ($fix !== true) {
            return;
        }

        $methodPoint = TokenHelper::findNextContent($phpcsFile, [T_STRING], $methodName, $stackPtr);

        if ($methodPoint === null) {
            return; // @codeCoverageIgnore
        }

        $phpcsFile->fixer->replaceToken($annotations[0]->getStartPointer(), '');
        $phpcsFile->fixer->replaceToken($annotations[0]->getStartPointer() - 1, '');
        $phpcsFile->fixer->replaceToken($methodPoint, 'test' . ucfirst($methodName));
    }
}
