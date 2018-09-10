<?php declare(strict_types = 1);

namespace FlaconiCodingStandard\Helper;

use PHP_CodeSniffer\Files\File;
use SlevomatCodingStandard\Helpers\ClassHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;

/**
 * @author Alexander Miehe <alexander.miehe@flaconi.de>
 */
class TestClassHelper
{
    /**
     * @param File $phpcsFile
     *
     * @return bool
     */
    public static function isTestCaseClass(File $phpcsFile): bool
    {
        $classPointer = TokenHelper::findNext($phpcsFile, [\T_CLASS], 0);

        if ($classPointer === null) {
            return false;
        }

        $className = ClassHelper::getFullyQualifiedName($phpcsFile, $classPointer);

        return \is_subclass_of($className, 'PHPUnit\Framework\TestCase');
    }
}
