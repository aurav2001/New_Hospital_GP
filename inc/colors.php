<?php
/**
 * Admin-controlled colour palette.
 *
 * The stylesheet resolves every brand colour through CSS variables
 * (--bs-primary-*, --bs-navy-*). Here we turn the two colours picked in
 * Hospital Settings → Colors into a full 50–950 scale and print it, so the
 * whole site recolours without rebuilding the compiled CSS.
 *
 * @package GPHealthcare
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ready-made palettes offered in the admin.
 */
function bs_color_presets() {
	return array(
		'teal'   => array( 'label' => __( 'Medical Teal (default)', 'bshealthcare' ), 'primary' => '#0891b2', 'dark' => '#0f2438' ),
		'blue'   => array( 'label' => __( 'Hospital Blue', 'bshealthcare' ), 'primary' => '#2563eb', 'dark' => '#0f1f3d' ),
		'green'  => array( 'label' => __( 'Care Green', 'bshealthcare' ), 'primary' => '#059669', 'dark' => '#0c2a22' ),
		'indigo' => array( 'label' => __( 'Deep Indigo', 'bshealthcare' ), 'primary' => '#4f46e5', 'dark' => '#191a3d' ),
		'maroon' => array( 'label' => __( 'Classic Maroon', 'bshealthcare' ), 'primary' => '#be123c', 'dark' => '#2b1218' ),
		'orange' => array( 'label' => __( 'Warm Orange', 'bshealthcare' ), 'primary' => '#ea580c', 'dark' => '#2d1a0f' ),
	);
}

/**
 * "#0891b2" => array( 8, 145, 178 ). Returns null when the value is not a hex colour.
 */
function bs_hex_to_rgb( $hex ) {
	$hex = ltrim( trim( (string) $hex ), '#' );
	if ( 3 === strlen( $hex ) ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}
	if ( ! preg_match( '/^[0-9a-fA-F]{6}$/', $hex ) ) {
		return null;
	}
	return array(
		hexdec( substr( $hex, 0, 2 ) ),
		hexdec( substr( $hex, 2, 2 ) ),
		hexdec( substr( $hex, 4, 2 ) ),
	);
}

/**
 * Blend two RGB colours. $weight is how much of $b to mix in (0–1).
 */
function bs_mix_rgb( $a, $b, $weight ) {
	return array(
		(int) round( $a[0] + ( $b[0] - $a[0] ) * $weight ),
		(int) round( $a[1] + ( $b[1] - $a[1] ) * $weight ),
		(int) round( $a[2] + ( $b[2] - $a[2] ) * $weight ),
	);
}

/**
 * Build a 50–950 scale around a base colour.
 *
 * @param array $base  RGB triplet.
 * @param int   $anchor Which step the base colour sits at (600 for primary, 800 for the dark neutral).
 */
function bs_build_scale( $base, $anchor = 600 ) {
	$white = array( 255, 255, 255 );
	$black = array( 0, 0, 0 );

	// How far each step is from the anchor. Negative = lighter, positive = darker.
	$steps = array(
		50 => -0.96, 100 => -0.90, 200 => -0.78, 300 => -0.62, 400 => -0.38,
		500 => -0.16, 600 => 0.0, 700 => 0.14, 800 => 0.30, 900 => 0.45, 950 => 0.62,
	);

	if ( 800 === $anchor ) {
		// A dark neutral needs its light end to stay near-grey, not a heavy tint.
		$steps = array(
			50 => -0.95, 100 => -0.89, 200 => -0.78, 300 => -0.60, 400 => -0.42,
			500 => -0.28, 600 => -0.16, 700 => -0.07, 800 => 0.0, 900 => 0.18, 950 => 0.36,
		);
	}

	$scale = array();
	foreach ( $steps as $step => $amount ) {
		if ( $amount < 0 ) {
			$scale[ $step ] = bs_mix_rgb( $base, $white, abs( $amount ) );
		} elseif ( $amount > 0 ) {
			$scale[ $step ] = bs_mix_rgb( $base, $black, $amount );
		} else {
			$scale[ $step ] = $base;
		}
	}
	return $scale;
}

/**
 * CSS variable block for the colours currently saved, or '' when both are default.
 */
function bs_palette_css() {
	$primary = bs_hex_to_rgb( bs_opt( 'color_primary', '' ) );
	$dark    = bs_hex_to_rgb( bs_opt( 'color_dark', '' ) );

	if ( ! $primary && ! $dark ) {
		return '';
	}

	$lines = array();
	if ( $primary ) {
		foreach ( bs_build_scale( $primary, 600 ) as $step => $rgb ) {
			$lines[] = sprintf( '--bs-primary-%d: %d %d %d;', $step, $rgb[0], $rgb[1], $rgb[2] );
		}
	}
	if ( $dark ) {
		foreach ( bs_build_scale( $dark, 800 ) as $step => $rgb ) {
			$lines[] = sprintf( '--bs-navy-%d: %d %d %d;', $step, $rgb[0], $rgb[1], $rgb[2] );
		}
	}

	return ':root{' . implode( '', $lines ) . '}';
}

/**
 * Print the palette after the stylesheet so it wins.
 */
function bs_print_palette() {
	$css = bs_palette_css();
	if ( $css ) {
		wp_add_inline_style( 'bs-theme', $css );
	}
}
add_action( 'wp_enqueue_scripts', 'bs_print_palette', 20 );

/**
 * Same palette inside the block editor, so previews match the front end.
 */
add_action( 'enqueue_block_assets', function () {
	$css = bs_palette_css();
	if ( $css && is_admin() ) {
		wp_register_style( 'bs-editor-palette', false );
		wp_enqueue_style( 'bs-editor-palette' );
		wp_add_inline_style( 'bs-editor-palette', $css );
	}
} );
