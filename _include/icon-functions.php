<?php
/**
 * SVG icons related functions and filters
 *
 * @package Joshua McKendall
 * @subpackage Canvas
 * @since 3.0.0
 */

/**
 * Add SVG definitions to the footer.
 */
function canvas_include_svg_icons() {

	// Define SVG sprite file.
	$svg_icons = get_parent_theme_file_path( '/img/svg-icons.svg' );

	// If it exists, include it.
	if ( file_exists( $svg_icons ) ) {

		require_once( $svg_icons );

	}

}

add_action( 'wp_footer', 'canvas_include_svg_icons', 9999 );

/**
 * Return SVG markup.
 *
 * @param array $args {
 *     Parameters needed to display an SVG.
 *
 *     @type string $icon  Required SVG icon filename.
 *     @type string $size  Optional SVG icon size.
 *     @type string $title Optional SVG title.
 *     @type string $desc  Optional SVG description.
 * }
 * @return string SVG markup.
 */
function canvas_get_svg_icon( $args = array() ) {

	// Make sure $args are an array.
	if ( empty( $args ) ) {

		return __( 'Please define default parameters in the form of an array.', 'canvas' );

	}

	// Define an icon.
	if ( false === array_key_exists( 'icon', $args ) ) {

		return __( 'Please define an SVG icon filename.', 'canvas' );

	}

	$sizes = array(
		'lg'	=> 30,
		'md'	=> 24,
		'sm'	=> 18,
		'xs'	=> 16
	);

	$defaults = array(
		'icon'		=> '',
		'size'		=> 'md',
		'title'		=> '',
		'desc'		=> '',
		'fallback'	=> false
	);

	// Parse args.
	$args = wp_parse_args( $args, $defaults );

	// Set aria hidden.
	$aria_hidden = ' aria-hidden="true"';

	// Set ARIA.
	$aria_labelledby = '';

	$size_class = '';

	if( $args['size'] ) {

		$size = $args['size'];

		$size_class = 'icon-' . $size;

		$px 	= $size;

		$width	= $sizes[ $px ];
		$height = $sizes[ $px ];

	}

	if ( $args['title'] ) {
		$aria_hidden     = '';
		$unique_id       = uniqid();
		$aria_labelledby = ' aria-labelledby="title-' . $unique_id . '"';

		if ( $args['desc'] ) {
			$aria_labelledby = ' aria-labelledby="title-' . $unique_id . ' desc-' . $unique_id . '"';
		}
	}

	// Begin SVG markup.
	$svg = '<svg class="icn icn-' . esc_attr( $args['icon'] ) . ' ' . $size_class . '"' . $aria_hidden . $aria_labelledby . ' role="img" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" >';

	// Display the title.
	if ( $args['title'] ) {
		$svg .= '<title id="title-' . $unique_id . '">' . esc_html( $args['title'] ) . '</title>';

		// Display the desc only if the title is already set.
		if ( $args['desc'] ) {
			$svg .= '<desc id="desc-' . $unique_id . '">' . esc_html( $args['desc'] ) . '</desc>';
		}
	}

	/*
	 * Display the icon.
	 *
	 * The whitespace around `<use>` is intentional - it is a work around to a keyboard navigation bug in Safari 10.
	 *
	 * See https://core.trac.wordpress.org/ticket/38387.
	 */
	$svg .= ' <use href="#icn-' . esc_html( $args['icon'] ) . '" xlink:href="#icn-' . esc_html( $args['icon'] ) . '"></use> ';

	// Add some markup to use as a fallback for browsers that do not support SVGs.
	if ( $args['fallback'] ) {
		$svg .= '<span class="svg-fallback icn-' . esc_attr( $args['icon'] ) . '"></span>';
	}

	$svg .= '</svg>';

	return $svg;

}