<?php declare(strict_types = 1);

namespace FlaconiCodingStandard\Sniffs\Test;

use FlaconiCodingStandard\Helper\TestClassHelper;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\IdentificatorHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;

/**
 * @author Alexander Miehe <alexander.miehe@flaconi.de>
 */
class UseStaticCallsForAssertInTestcaseSniff implements Sniff
{
    public const CODE_NON_STATIC_ASSERTION_METHOD_USAGE = 'NonStaticAssertionMethodUsage';

    /**
     * @var string[]
     */
    private $assertMethods;

    public function __construct()
    {
        if (!\class_exists('PHPUnit\Framework\Assert')) {
            return;
        }

        $this->assertMethods = \array_filter(
            \get_class_methods('PHPUnit\Framework\Assert'),
            static function (string $method) {
                return \strpos($method, 'assert') === 0;
            }
        );
    }


    /**
     * @inheritDoc
     */
    public function register(): array
    {
        return [
            \T_VARIABLE,
        ];
    }

    /**
     * @param File $phpcsFile
     * @param int  $stackPtr
     */
    public function process(File $phpcsFile, $stackPtr): void
    {

        if (!TestClassHelper::isTestCaseClass($phpcsFile)) {
            return;
        }

        $methodPoint = IdentificatorHelper::findEndPointer($phpcsFile, $stackPtr);

        if ($methodPoint === null) {
            return;
        }

        $methodCall = TokenHelper::getContent($phpcsFile, $methodPoint, $methodPoint);

        if (\in_array($methodCall, $this->assertMethods, true) === false) {
            return;
        }

        $fix = $phpcsFile->addFixableError(
            'The PhpUnit TestCase is using a non static usage of Assertion Methods',
            $methodPoint,
            self::CODE_NON_STATIC_ASSERTION_METHOD_USAGE
        );

        if ($fix !== true) {
            return;
        }

        $operatorPointer = TokenHelper::findNextEffective($phpcsFile, $stackPtr + 1);

        if ($operatorPointer === null) {
            return;
        }

        $phpcsFile->fixer->beginChangeset();
        $phpcsFile->fixer->replaceToken($stackPtr, 'self');
        $phpcsFile->fixer->replaceToken($operatorPointer, '::');
        $phpcsFile->fixer->endChangeset();
    }
}
