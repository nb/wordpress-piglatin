<?php
class PiglatinTest extends WP_UnitTestCase {

	public function piglatin_provider() {
		return array(
			array( 'pig', 'igpay', 'starts with a consonant' ),
			array( 'thanks', 'anksthay', 'starts with two consonants' ),
			array( 'omelet', 'omeletay', 'starts with a vowel' ),
			array( '666', '666', 'starts with neither' ),
		);
	}

	public function translation_cleanup_provider () {
		return array(
			array( 'pig <tag> pig', 'igpay <tag> igpay', 'preserve tags' ),
			array( 'pig &#8383; pig', 'igpay &#8383; igpay', 'preserve numeric entities' ),
			array( 'pig &baba; pig', 'igpay &baba; igpay', 'preserve named entities' ),
			array( 'pig %s pig %d', 'igpay %s igpay %d', 'preserve printf placeholders (only s and d)' ),
			array( 'pig %1$s pig %23$d', 'igpay %1$s igpay %23$d', 'preserve numeric printf placeholders (only s and d)' ),
			array( 'pig2pig', 'igpay2igpay', 'treat non-alphabet characters as word boundaries' ),
		);
	}


	/**
     * @dataProvider piglatin_provider
     */
	function test_word2pig( $english, $piglatin, $comment ) {
		$this->assertEquals( $piglatin, PigLatin::word2pig( array( $english ) ), $comment );
	}

	/**
     * @dataProvider translation_cleanup_provider
     */
	function test_translation2pig( $translation, $piglatin, $comment ) {
		$this->assertEquals( $piglatin, PigLatin::translation2pig( $translation ), $comment );
	}

	function test_gettext() {
		$text = 'baba';
		$this->assertEquals( PigLatin::translation2pig( $text ), translate( $text ) );
	}

	function test_context() {
		$text = 'baba';
		$this->assertEquals( PigLatin::translation2pig( $text ), translate_with_gettext_context( $text, 'context' ) );
	}

	function test_plural() {
		$text = 'baba';
		$this->assertEquals( PigLatin::translation2pig( $text ), _n( $text, 'plural', 1 ) );
		$this->assertEquals( PigLatin::translation2pig( $text ), _n( 'singulr', $text, 100 ) );
	}

	function test_plural_context() {
		$text = 'baba';
		$this->assertEquals( PigLatin::translation2pig( $text ), _n( $text, 'plural', 1, 'context' ) );
		$this->assertEquals( PigLatin::translation2pig( $text ), _n( 'singulr', $text, 100, 'context' ) );
	}
}
