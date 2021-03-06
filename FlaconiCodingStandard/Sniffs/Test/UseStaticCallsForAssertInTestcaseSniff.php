<?php

declare(strict_types=1);

namespace FlaconiCodingStandard\Sniffs\Test;

use FlaconiCodingStandard\Helper\TestClassHelper;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\IdentificatorHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;

use function array_filter;
use function class_exists;
use function get_class_methods;
use function in_array;
use function strpos;

use const T_VARIABLE;

class UseStaticCallsForAssertInTestcaseSniff implements Sniff
{
    public const CODE_NON_STATIC_ASSERTION_METHOD_USAGE = 'NonStaticAssertionMethodUsage';

    /** @var array<string> */
    private array $assertMethods;

    public function __construct()
    {
        if (! class_exists('PHPUnit\Framework\Assert')) {
            return; // @codeCoverageIgnore
        }

        $this->assertMethods = array_filter(
            get_class_methods('PHPUnit\Framework\Assert'),
            static function (string $method): bool {
                return strpos($method, 'assert') === 0;
            },
        );
    }

    /**
     * @return array<int>
     */
    public function register(): array
    {
        return [T_VARIABLE];
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingAnyTypeHint
     */
    public function process(File $phpcsFile, $stackPtr): void
    {
        if (! TestClassHelper::isTestCaseClass($phpcsFile)) {
            return;
        }

        $methodPoint = IdentificatorHelper::findEndPointer($phpcsFile, $stackPtr);

        if ($methodPoint === null) {
            return; // @codeCoverageIgnore
        }

        $methodCall = TokenHelper::getContent($phpcsFile, $methodPoint, $methodPoint);

        if (in_array($methodCall, $this->assertMethods, true) === false) {
            return;
        }

        $fix = $phpcsFile->addFixableError(
            'The PhpUnit TestCase is using a non static usage of Assertion Methods',
            $methodPoint,
            self::CODE_NON_STATIC_ASSERTION_METHOD_USAGE,
        );

        if ($fix !== true) {
            return;
        }

        $operatorPointer = TokenHelper::findNextEffective($phpcsFile, $stackPtr + 1);

        if ($operatorPointer === null) {
            return; // @codeCoverageIgnore
        }

        $phpcsFile->fixer->beginChangeset();
        $phpcsFile->fixer->replaceToken($stackPtr, 'self');
        $phpcsFile->fixer->replaceToken($operatorPointer, '::');
        $phpcsFile->fixer->endChangeset();
    }
}
