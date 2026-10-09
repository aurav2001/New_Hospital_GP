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
function bs_fallback_menu( $args = array() ) {
	$menu_class = 'bs-menu';
	if ( is_string( $args ) && ! empty( $args ) ) {
		$menu_class = $args;
	} elseif ( is_object( $args ) && ! empty( $args->menu_class ) ) {
		$menu_class = $args->menu_class;
	} elseif ( is_array( $args ) && ! empty( $args['menu_class'] ) ) {
		$menu_class = $args['menu_class'];
	}

	$items = array(
		array( __( 'Home', 'bshealthcare' ), home_url( '/' ) ),
		array( __( 'About Us', 'bshealthcare' ), bs_page_url( 'templates/template-about.php' ) ),
		array( __( 'Specialities', 'bshealthcare' ), home_url( '/specialities/' ), 'has-mega' ),
		array( __( 'Doctors', 'bshealthcare' ), home_url( '/doctors/' ) ),
		array( __( 'Blog', 'bshealthcare' ), get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ) ),
		array( __( 'Contact', 'bshealthcare' ), bs_page_url( 'templates/template-contact.php' ) ),
	);

	$curr_url = untrailingslashit( home_url( add_query_arg( array(), $GLOBALS['wp']->request ?? '' ) ) );

	echo '<ul class="' . esc_attr( $menu_class ) . '">';
	foreach ( $items as $it ) {
		$url     = $it[1];
		$classes = array( 'menu-item' );
		if ( ! empty( $it[2] ) ) {
			$classes[] = $it[2];
		}
		if ( untrailingslashit( $url ) === $curr_url ) {
			$classes[] = 'current-menu-item';
			$classes[] = 'current_page_item';
		}
		echo '<li class="' . esc_attr( implode( ' ', $classes ) ) . '"><a href="' . esc_url( $url ) . '">' . esc_html( $it[0] ) . '</a></li>';
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
 * Automatically heal menu links that have empty hrefs or broken permalink URLs.
 */
function bs_nav_menu_fix_empty_urls( $items ) {
	$plain = empty( get_option( 'permalink_structure' ) );

	foreach ( $items as $item ) {
		$clean_title = strtolower( trim( $item->title ?? '' ) );
		$url         = trim( $item->url ?? '' );

		if ( empty( $url ) || '#' === $url || '' === $url ) {
			if ( false !== strpos( $clean_title, 'about' ) ) {
				$item->url = bs_page_url( 'templates/template-about.php' );
			} elseif ( false !== strpos( $clean_title, 'contact' ) ) {
				$item->url = bs_page_url( 'templates/template-contact.php' );
			} elseif ( false !== strpos( $clean_title, 'blog' ) ) {
				$p_blog = get_option( 'page_for_posts' );
				$item->url = $p_blog ? get_permalink( $p_blog ) : ( $plain ? home_url( '/?pagename=blog' ) : home_url( '/blog/' ) );
			} elseif ( false !== strpos( $clean_title, 'doctor' ) ) {
				$item->url = get_post_type_archive_link( 'bs_doctor' ) ?: ( $plain ? home_url( '/?post_type=bs_doctor' ) : home_url( '/doctors/' ) );
			} elseif ( false !== strpos( $clean_title, 'special' ) || false !== strpos( $clean_title, 'service' ) ) {
				$item->url = get_post_type_archive_link( 'bs_service' ) ?: ( $plain ? home_url( '/?post_type=bs_service' ) : home_url( '/specialities/' ) );
			} elseif ( false !== strpos( $clean_title, 'appoint' ) || false !== strpos( $clean_title, 'book' ) ) {
				$item->url = bs_page_url( 'templates/template-appointment.php' );
			}
		}

		// When server uses Plain permalinks, prevent 500 errors on custom links like /doctors/ and /specialities/
		if ( $plain && ! empty( $item->url ) ) {
			if ( preg_match( '#/doctors/?$#i', $item->url ) ) {
				$item->url = home_url( '/?post_type=bs_doctor' );
			} elseif ( preg_match( '#/specialities/?$#i', $item->url ) ) {
				$item->url = home_url( '/?post_type=bs_service' );
			} elseif ( preg_match( '#/about(?:-us)?/?$#i', $item->url ) ) {
				$item->url = home_url( '/?pagename=about-us' );
			} elseif ( preg_match( '#/contact/?$#i', $item->url ) ) {
				$item->url = home_url( '/?pagename=contact' );
			} elseif ( preg_match( '#/blog/?$#i', $item->url ) ) {
				$p_blog = get_option( 'page_for_posts' );
				$item->url = $p_blog ? get_permalink( $p_blog ) : home_url( '/?pagename=blog' );
			}
		}
	}
	return $items;
}
add_filter( 'wp_nav_menu_objects', 'bs_nav_menu_fix_empty_urls', 10, 1 );

function bs_nav_menu_link_attributes( $atts, $item ) {
	$plain = empty( get_option( 'permalink_structure' ) );
	$href  = trim( $atts['href'] ?? '' );
	$title = strtolower( trim( $item->title ?? '' ) );

	if ( empty( $href ) || '#' === $href ) {
		if ( false !== strpos( $title, 'about' ) ) {
			$atts['href'] = bs_page_url( 'templates/template-about.php' );
		} elseif ( false !== strpos( $title, 'contact' ) ) {
			$atts['href'] = bs_page_url( 'templates/template-contact.php' );
		} elseif ( false !== strpos( $title, 'blog' ) ) {
			$p_blog = get_option( 'page_for_posts' );
			$atts['href'] = $p_blog ? get_permalink( $p_blog ) : ( $plain ? home_url( '/?pagename=blog' ) : home_url( '/blog/' ) );
		} elseif ( false !== strpos( $title, 'doctor' ) ) {
			$atts['href'] = get_post_type_archive_link( 'bs_doctor' ) ?: ( $plain ? home_url( '/?post_type=bs_doctor' ) : home_url( '/doctors/' ) );
		} elseif ( false !== strpos( $title, 'special' ) || false !== strpos( $title, 'service' ) ) {
			$atts['href'] = get_post_type_archive_link( 'bs_service' ) ?: ( $plain ? home_url( '/?post_type=bs_service' ) : home_url( '/specialities/' ) );
		}
	}

	if ( $plain && ! empty( $atts['href'] ) ) {
		if ( preg_match( '#/doctors/?$#i', $atts['href'] ) ) {
			$atts['href'] = home_url( '/?post_type=bs_doctor' );
		} elseif ( preg_match( '#/specialities/?$#i', $atts['href'] ) ) {
			$atts['href'] = home_url( '/?post_type=bs_service' );
		}
	}

	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'bs_nav_menu_link_attributes', 10, 2 );

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
