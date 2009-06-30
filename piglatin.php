<?php
/*
 * Plugin Name:	Pig Latin
 * Plugin URI: http://wordpress.org/extend/plugins/piglatin/
* Description: Overrides the current language and translates all messages into pig latin. This way you can easily spot, which messages were left untranslatable, while the interface is still usable.
 * Version: 0.1
 * Author: Nikolay Bachiyski
 * Author URI: http://nikolay.bg/
 */

class PigLatin {

	function word2pig($match) {
	    $text = $match[0];
	    $hyphen = '';
	    $consonants = "bBcCdDfFgGhHjJkKlLmMnNpPqQrRsStTvVwWxXyYzZ";
	    $vowels = "aAeEiIoOuU";

		$i = 0;
		if (false !== strpos($consonants, $text[0])) {
			$cons = $text[0];
			$i = 1;
			while ($i < strlen($text) && false !== strpos($consonants, $text[$i])) {
				$cons .= $text[$i];
				++$i;
			}
			return substr($text, $i).$hyphen.$cons.'ay';
		}
		else if (false !== strpos($vowels, $text[0])) {
			return $text.'ay';
		}
		else {
			return $text;
		}
	}


	function translation2pig($string) {
	    if (strlen($string) < 3) {
	        return $string;
	    }
		/*
			do not translate tag names and attributes,
			entities, and %xxx encoded strings
		*/
	    $delimiters = array(
	        '<.*?>',
	        '\&#\d+;',
	        '\&[a-z]+;',
	        '%\d+\$[sd]',
	        '%[sd]',
	        '\s+',
	    );
	    $parts = preg_split('/('.implode('|', $delimiters).')/i', $string, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
	    $cnt = count($parts);
	    for ($i = 0; $i < $cnt; ++$i) {
	        $isdelim = false;
	        foreach ($delimiters as $delim) {
	            if (preg_match("/^$delim$/", $parts[$i])) {
	                $isdelim = true;
	                break;
	            }
	        }
	        if ($isdelim) {
	            continue;
	        }
	        $parts[$i] = preg_replace_callback('/[a-z]+/i', array('PigLatin', 'word2pig'),$parts[$i]);
	    }
	    return implode('', $parts);
	}

	function gettext($translated, $original) {
	    return PigLatin::translation2pig($original);
	}

	function ngettext($translated, $single, $plural, $number) {
		return PigLatin::translation2pig($number == 1? $single : $plural);
	}
};

add_filter('gettext', array('PigLatin', 'gettext'), 10, 2);
add_filter('gettext_with_context', array('PigLatin', 'gettext'), 10, 2);
add_filter('ngettext', array('PigLatin', 'ngettext'), 10, 4);
add_filter('ngettext_with_context', array('PigLatin', 'ngettext'), 10, 4);