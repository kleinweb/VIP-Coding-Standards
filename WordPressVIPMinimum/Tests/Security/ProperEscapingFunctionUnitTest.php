<?php
/**
 * Unit test class for WordPressVIPMinimum Coding Standard.
 *
 * @package VIPCS\WordPressVIPMinimum
 */

namespace WordPressVIPMinimum\Tests\Security;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Unit test class for the ProperEscapingFunction sniff.
 *
 * @package VIPCS\WordPressVIPMinimum
 *
 * @covers \WordPressVIPMinimum\Sniffs\Security\ProperEscapingFunctionSniff
 */
class ProperEscapingFunctionUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @param string $testFile The name of the file being tested.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList( $testFile = '' ) {
		switch ( $testFile ) {
			case 'ProperEscapingFunctionUnitTest.1.inc':
				return [
					3   => 1,
					5   => 1,
					15  => 1,
					17  => 1,
					21  => 1,
					23  => 1,
					33  => 1,
					37  => 1,
					41  => 1,
					45  => 1,
					48  => 1,
					62  => 1,
					63  => 1,
					64  => 1,
					65  => 1,
					67  => 1,
					68  => 1,
					69  => 1,
					72  => 1,
					73  => 1,
					74  => 1,
					75  => 1,
					76  => 1,
					77  => 1,
					78  => 1,
					79  => 1,
					80  => 1,
					82  => 1,
					92  => 1,
					97  => 1,
					102 => 1,
					104 => 1,
				];

			default:
				return [];
		}
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array <int line number> => <int number of warnings>
	 */
	public function getWarningList() {
		return [];
	}
}
