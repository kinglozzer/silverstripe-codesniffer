<?php
/**
 * SilverStripe_Sniffs_ControlStructures_ControlSignatureWhitespaceSniff.
 *
 * Verifies that control structures follow the SilverStripe coding conventions
 * for whitespace around parentheses.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @link      https://github.com/stojg/silverstripe-codesniffer
 */
class SilverStripe_Sniffs_ControlStructures_ControlSignatureWhitespaceSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array(
                                   'PHP',
                                   'JS',
                                  );

    /**
     * If true, an error will be thrown; otherwise a warning.
     *
     * @var bool
     */
    public $error = true;

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
                T_IF,
                T_ELSE,
                T_ELSEIF,
                T_FOREACH,
                T_WHILE,
                T_DO,
                T_SWITCH,
                T_FOR,
                T_TRY,
                T_CATCH,
               );

    }//end register()

    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Ignore the ELSE in ELSE IF. We'll process the IF part later when the parser gets to it
        if (($tokens[$stackPtr]['code'] === T_ELSE) && ($tokens[($stackPtr + 2)]['code'] === T_IF)) {
            return;
        }

        // If the control structure has parentheses, get the position of the opening one
        // If not, we ain't interested, so return
        if (isset($tokens[$stackPtr]['parenthesis_opener']) === true) {
            $checkPosition = $tokens[$stackPtr]['parenthesis_opener'];
        } else {
            return;
        }

        if ($tokens[$checkPosition-1]['code'] === T_WHITESPACE) {
            $this->markAsFailed($phpcsFile, $stackPtr, 'Control structures with whitespace before parenthesis are not allowed.');
        }
    }

    protected function markAsFailed($phpcsFile, $stackPtr, $message)
    {
        if ($this->error === true) {
            $phpcsFile->addError($message, $stackPtr, 'NotAllowed');
        } else {
            $phpcsFile->addWarning($message, $stackPtr, 'Discouraged');
        }
    }


}
