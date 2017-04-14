<?php
/**********************
 *
 *
 *
 * STR TO LOWER UTF8
 *
 *
 *
 *********************/
function strtolower_utf8( $inputString ) {
	$outputString = utf8_decode( $inputString );
	$outputString = strtolower( $outputString );
	$outputString = utf8_encode( $outputString );

	return $outputString;
}

function _print_r( $a, $wrap = true ) {
	echo '<pre';
	if ( $wrap ) {
		echo ' style="white-space: pre-wrap;"';
	}
	echo '>', htmlspecialchars( print_r( $a, true ) ), '</pre>';
}

/**********************
 *
 *
 * DATE FORMAT PHP TO JS
 *
 *
 *
 *********************/
function dateformat_to_js( $php_format ) {
	$SYMBOLS_MATCHING = array(
		// Day
		'd' => 'dd',
		'D' => 'D',
		'j' => 'd',
		'l' => 'DD',
		'N' => '',
		'S' => '',
		'w' => '',
		'z' => 'o',
		// Week
		'W' => '',
		// Month
		'F' => 'MM',
		'm' => 'mm',
		'M' => 'M',
		'n' => 'm',
		't' => '',
		// Year
		'L' => '',
		'o' => '',
		'Y' => 'yy',
		'y' => 'y',
		// Time
		'a' => '',
		'A' => '',
		'B' => '',
		'g' => '',
		'G' => '',
		'h' => '',
		'H' => '',
		'i' => '',
		's' => '',
		'u' => ''
	);
	$jqueryui_format  = "";
	$escaping         = false;
	for ( $i = 0; $i < strlen( $php_format ); $i ++ ) {
		$char = $php_format[ $i ];
		if ( $char === '\\' ) // PHP date format escaping character
		{
			$i ++;
			if ( $escaping ) {
				$jqueryui_format .= $php_format[ $i ];
			} else {
				$jqueryui_format .= '\'' . $php_format[ $i ];
			}
			$escaping = true;
		} else {
			if ( $escaping ) {
				$jqueryui_format .= "'";
				$escaping        = false;
			}
			if ( isset( $SYMBOLS_MATCHING[ $char ] ) ) {
				$jqueryui_format .= $SYMBOLS_MATCHING[ $char ];
			} else {
				$jqueryui_format .= $char;
			}
		}
	}

	return $jqueryui_format;
}



/**********************
 *
 *
 *
 * ARCHIVE TITLE
 *
 *
 *
 *********************/
function extra_get_archive_title( $id = 0 ) {
	global $post;
	$old_post = $post;

	if ( $id != 0 ) {
		$post = get_post( $id );
	}

	$title = null;
	if ( isset( $post ) && ! empty( $post ) ) {
		// CATEGORY
		if ( is_category() ) {
			$title = sprintf( __( 'Archive de la catégorie "%s"', 'extra' ), single_cat_title( '', false ) );
		} // SEARCH
		else {
			if ( is_search() ) {
				$title = sprintf( __( 'Résultats pour la recherche "%s"', 'extra' ), get_search_query() );
			} // TIME - DAY
			else {
				if ( is_day() ) {
					$title = sprintf( __( 'Archive du %s', 'extra' ), get_the_time( 'd F Y' ) );

				} // TIME - MONTH
				else {
					if ( is_month() ) {
						$title = sprintf( __( 'Archive %s', 'extra' ), get_the_time( 'F Y' ) );
					} // TIME - YEAR
					else {
						if ( is_year() ) {
							$title = sprintf( __( 'Archive %s', 'extra' ), get_the_time( 'Y' ) );
						}
					}
				}
			}
		}
	}

	$post = $old_post;

	return $title;
}

function extra_the_archive_title( $id = 0 ) {
	echo extra_get_archive_title( $id );
}

/**********************
 *
 *
 *
 * BODY CLASSES
 *
 *
 *
 *********************/
function extra_body_class( $classes ) {
	if ( is_page() ) {
		global $post;
		$classes[] = 'page-' . $post->post_name;
	}

	return $classes;
}

add_filter( 'body_class', 'extra_body_class' );



/**
 * Shortify a string with "..."
 *
 * @param $text
 * @param $max_length
 *
 * @return null|string
 */
function extra_shortify_text( $text, $max_length ) {
	if ( strlen( $text ) > $max_length ) {
		$text_array = explode( ' ', $text );
		$text       = null;
		foreach ( $text_array as $text_part ) {
			if ( $text == null ) {
				$text = $text_part;
				if ( strlen( $text ) > $max_length ) {
					$text = substr( $text, 0, $max_length - 1 ) . '...';
					break;
				}
			} else {
				if ( strlen( $text . ' ' . $text_part ) <= $max_length ) {
					$text .= ' ' . $text_part;
				} else {
					$text .= '...';
					break;
				}
			}
		}
	}

	return $text;
}