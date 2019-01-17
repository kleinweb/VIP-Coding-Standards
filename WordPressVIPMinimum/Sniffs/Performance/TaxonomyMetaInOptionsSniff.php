<?php
/**
 * WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 * @link https://github.com/Automattic/VIP-Coding-Standards
 */

namespace WordPressVIPMinimum\Sniffs\Performance;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

/**
 * Restricts the implementation of taxonomy term meta via options.
 *
 *  @package VIPCS\WordPressVIPMinimum
 */
class TaxonomyMetaInOptionsSniff implements Sniff {

	/**
	 * List of options_ functions
	 *
	 * @var array
	 */
	public $option_functions = [
		'get_option',
		'add_option',
		'update_option',
		'delete_option',
	];

	/**
	 * List of possible variable names holding term ID.
	 *
	 * @var array
	 */
	public $taxonomy_term_patterns = [
		'category_id',
		'cat_id',
		'cat',
		'term_id',
		'term',
		'tag_id',
		'tag',
	];

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register() {
		return Tokens::$functionNameTokens;
	}

	/**
	 * Process this test when one of its tokens is encountered
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int  $stackPtr  The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process( File $phpcsFile, $stackPtr ) {

		$tokens = $phpcsFile->getTokens();

		if ( false === in_array( $tokens[ $stackPtr ]['content'], $this->option_functions, true ) ) {
			return;
		}

		$openBracket = $phpcsFile->findNext( Tokens::$emptyTokens, $stackPtr + 1, null, true );

		if ( T_OPEN_PARENTHESIS !== $tokens[ $openBracket ]['code'] ) {
			return;
		}

		$param_ptr = $phpcsFile->findNext( Tokens::$emptyTokens, $openBracket + 1, null, true );

		if ( T_DOUBLE_QUOTED_STRING === $tokens[ $param_ptr ]['code'] ) {
			foreach ( $this->taxonomy_term_patterns as $taxonomy_term_pattern ) {
				if ( false !== strpos( $tokens[ $param_ptr ]['content'], $taxonomy_term_pattern ) ) {
					$this->addPossibleTermMetaInOptionsWarning( $phpcsFile, $stackPtr );
					return;
				}
			}
		} elseif ( T_CONSTANT_ENCAPSED_STRING === $tokens[ $param_ptr ]['code'] ) {

			$string_concat = $phpcsFile->findNext( Tokens::$emptyTokens, $param_ptr + 1, null, true );
			if ( T_STRING_CONCAT !== $tokens[ $string_concat ]['code'] ) {
				return;
			}

			$variable_name = $phpcsFile->findNext( Tokens::$emptyTokens, $string_concat + 1, null, true );
			if ( T_VARIABLE !== $tokens[ $variable_name ]['code'] ) {
				return;
			}

			foreach ( $this->taxonomy_term_patterns as $taxonomy_term_pattern ) {
				if ( false !== strpos( $tokens[ $variable_name ]['content'], $taxonomy_term_pattern ) ) {
					$this->addPossibleTermMetaInOptionsWarning( $phpcsFile, $stackPtr );
					return;
				}
			}

			$object_operator = $phpcsFile->findNext( Tokens::$emptyTokens, $variable_name + 1, null, true );
			if ( T_OBJECT_OPERATOR !== $tokens[ $object_operator ]['code'] ) {
				return;
			}

			$object_property = $phpcsFile->findNext( Tokens::$emptyTokens, $object_operator + 1, null, true );
			if ( T_STRING !== $tokens[ $object_property ]['code'] ) {
				return;
			}

			foreach ( $this->taxonomy_term_patterns as $taxonomy_term_pattern ) {
				if ( false !== strpos( $tokens[ $object_property ]['content'], $taxonomy_term_pattern ) ) {
					$this->addPossibleTermMetaInOptionsWarning( $phpcsFile, $stackPtr );
					return;
				}
			}
		}
	}

	/**
	 * Helper method for composing the Warning for all possible cases.
	 *
	 * @param File $phpcsFile The PHP_CodeSniffer file where the token was found.
	 * @param int  $stackPtr  The position of the current token in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function addPossibleTermMetaInOptionsWarning( File $phpcsFile, $stackPtr ) {
		$message = 'Possible detection of storing taxonomy term meta in options table. Needs manual inspection. All such data should be stored in term_meta.';
		$phpcsFile->addWarning( $message, $stackPtr, 'PossibleTermMetaInOptions' );
	}
}
