<?php
/**
 * Theme supports, menus, assets.
 *
 * @package GPHealthcare
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bs_setup() {
	load_theme_textdomain( 'bshealthcare', BS_DIR . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo', array( 'height' => 88, 'width' => 88, 'flex-height' => true, 'flex-width' => true ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );

	add_image_size( 'bs-card', 640, 400, true );
	add_image_size( 'bs-portrait', 480, 520, true );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'bshealthcare' ),
			'footer-quick' => __( 'Footer – Quick Links', 'bshealthcare' ),
			'footer-services' => __( 'Footer – Services', 'bshealthcare' ),
		)
	);
}
add_action( 'after_setup_theme', 'bs_setup' );

function bs_assets() {
	wp_enqueue_style( 'bs-fonts', 'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap', array(), null );
	wp_enqueue_style( 'bs-theme', BS_URI . '/assets/css/theme.css', array(), BS_VERSION );
	wp_enqueue_script( 'bs-theme', BS_URI . '/assets/js/theme.js', array(), BS_VERSION, true );

	$user = wp_get_current_user();
	wp_localize_script(
		'bs-theme',
		'BSHC',
		array(
			'ajax'       => admin_url( 'admin-ajax.php' ),
			'nonce'      => wp_create_nonce( 'bs_nonce' ),
			'loggedIn'   => is_user_logged_in(),
			'loginUrl'   => bs_page_url( 'templates/template-login.php' ),
			'user'       => is_user_logged_in() ? array(
				'name'  => $user->display_name,
				'email' => $user->user_email,
				'phone' => get_user_meta( $user->ID, 'bs_phone', true ),
			) : null,
			'slots'      => array_map( 'trim', explode( ',', bs_opt( 'appt_slots' ) ) ),
			'fee'        => bs_opt( 'appt_fee' ),
			'requireLogin' => '1' === (string) bs_opt( 'appt_require_login' ),
			'adEnabled'  => '1' === (string) bs_opt( 'ad_enabled' ),
			'i18n'       => array(
				'sending' => __( 'Sending…', 'bshealthcare' ),
				'error'   => __( 'Something went wrong. Please try again.', 'bshealthcare' ),
			),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'bs_assets' );

function bs_admin_assets( $hook ) {
	wp_enqueue_media();
	wp_enqueue_style( 'bs-admin', BS_URI . '/assets/css/admin.css', array(), BS_VERSION );
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'bs-admin', BS_URI . '/assets/js/admin.js', array( 'jquery', 'wp-color-picker' ), BS_VERSION, true );
}
add_action( 'admin_enqueue_scripts', 'bs_admin_assets' );

/**
 * Fallback menu when none is assigned: main pages in a sensible order.
 */
function bs_fallback_menu() {
	$items = array(
		array( 'Home', home_url( '/' ) ),
		array( 'About Us', bs_page_url( 'templates/template-about.php' ) ),
		array( 'Specialities', get_post_type_archive_link( 'bs_service' ) ),
		array( 'Doctors', get_post_type_archive_link( 'bs_doctor' ) ),
		array( 'Blogs', get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ) ),
		array( 'Contact', bs_page_url( 'templates/template-contact.php' ) ),
	);
	echo '<ul class="bs-menu">';
	foreach ( $items as $it ) {
		echo '<li class="menu-item"><a href="' . esc_url( $it[1] ) . '">' . esc_html( $it[0] ) . '</a></li>';
	}
	echo '</ul>';
}

/**
 * Add the mega-menu flag to the Specialities menu item.
 */
function bs_menu_item_classes( $classes, $item ) {
	$archive = get_post_type_archive_link( 'bs_service' );
	if ( ( $archive && untrailingslashit( $item->url ) === untrailingslashit( $archive ) ) || preg_match( '/special|service/i', $item->title ) ) {
		$classes[] = 'has-mega';
	}
	return $classes;
}
add_filter( 'nav_menu_css_class', 'bs_menu_item_classes', 10, 2 );

/**
 * Excerpt tweaks.
 */
add_filter( 'excerpt_length', function () { return 22; } );
add_filter( 'excerpt_more', function () { return '…'; } );

/**
 * Body classes.
 */
add_filter( 'body_class', function ( $classes ) {
	$classes[] = 'bg-white text-navy-800 antialiased font-sans';
	return $classes;
} );
