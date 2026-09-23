<?php
/**
 * Small helpers used across templates.
 *
 * @package GPHealthcare
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme option getter (all settings live in one option array).
 */
function bs_opt( $key, $default = '' ) {
	static $opts = null;
	if ( null === $opts ) {
		$opts = get_option( 'bs_settings', array() );
		if ( ! is_array( $opts ) ) {
			$opts = array();
		}
	}
	if ( isset( $opts[ $key ] ) && '' !== $opts[ $key ] && null !== $opts[ $key ] ) {
		return $opts[ $key ];
	}
	$defaults = bs_default_settings();
	if ( '' === $default && isset( $defaults[ $key ] ) ) {
		return $defaults[ $key ];
	}
	return $default;
}

/**
 * Default values for every theme setting.
 */
function bs_default_settings() {
	return array(
		// General.
		'color_primary'      => '',
		'color_dark'         => '',
		'site_tagline'       => 'World-Class Eye Care',
		'phone'              => '+91 98765 43210',
		'emergency'          => '',
		'email'              => get_option( 'admin_email' ),
		'address'            => '123 Hospital Road, Your City, State 000000',
		'hours_opd'          => '8:00 AM – 5:00 PM',
		'hours_inpatient'    => '24/7',
		'whatsapp_enabled'   => '1',
		'whatsapp_number'    => '',
		'whatsapp_message'   => 'Hello! I would like to know more about your services.',
		'map_embed'          => '',
		'social_facebook'    => '',
		'social_twitter'     => '',
		'social_instagram'   => '',
		'social_linkedin'    => '',
		'social_youtube'     => '',
		// Home.
		'home_sections'      => 'hero,ayushman,why,about,services,doctors,testimonials,blogs,faq',
		'hero_eyebrow'       => 'World-Class Eye Care',
		'hero_title'         => 'Clear vision, {compassionate} care for every eye.',
		'hero_subtitle'      => 'Experience a new standard of eye care excellence, where advanced ophthalmology meets a human touch. Cataract, glaucoma, retina, LASIK and more, all under one roof.',
		'hero_image'         => BS_URI . '/assets/img/hero-bg.jpg',
		'hero_overlay'       => '45',
		'hero_cta'           => 'Book Appointment',
		'hero_secondary'     => 'Watch Our Story',
		'hero_secondary_url' => '',
		'hero_highlights'    => "Ayushman Bharat empanelled\nStitchless Phaco surgery\nSame-day discharge",
		'hero_rating'        => '4.9/5 Rating',
		'hero_rating_sub'    => 'Patient Satisfaction',
		'ayushman_headline'  => 'Ayushman Bharat Scheme',
		'ayushman_subtitle'  => 'Government health cover for eligible citizens – get quality eye treatment completely free of cost.',
		'ayushman_benefits'  => "Free Eye Camp|Under the Ayushman Bharat scheme, FREE for all ration card holders and senior citizens (70+).|" . BS_URI . "/assets/img/ayushman.png\nCashless Cataract Surgery|Free Phaco cataract surgery with lens implantation. No cut, no injection, no pad – same-day discharge.|" . BS_URI . "/assets/img/surgery.png\nDocuments Required|Please carry your Aadhaar card, Ayushman / ration card and your registered mobile phone.|" . BS_URI . '/assets/img/experts.png',
		'ayushman_note'      => 'Free services are subject to verification of the required government documents.',
		'why_headline'       => 'Why Choose Us',
		'why_subtitle'       => 'Delivering excellence in eye care with world-class expertise and compassion.',
		'why_items'          => "Expert Surgeons|Highly skilled specialists with decades of experience in advanced eye care.|" . BS_URI . "/assets/img/experts.png\nEasy Appointment Booking|Book online in minutes and get instant confirmation on your phone.|" . BS_URI . "/assets/img/booking.png\nCompassionate Care|Patient-centric approach ensuring comfort at every step of treatment.|" . BS_URI . "/assets/img/care.jpg\nTrusted Excellence|A legacy of successful outcomes and thousands of satisfied patients.|" . BS_URI . '/assets/img/trusted.png',
		'about_headline'     => 'Excellence in Vision Care',
		'about_text'         => "We don't just treat eyes; we enhance your view of the world. Combining decades of medical expertise with cutting-edge technology to deliver outcomes that exceed expectations.",
		'services_headline'  => 'Comprehensive Eye Care Services',
		'services_subtitle'  => 'From routine check-ups to advanced surgery, every treatment is powered by modern technology and experienced specialists.',
		'doctors_headline'   => 'Meet Our Medical Experts',
		'doctors_subtitle'   => 'Experienced ophthalmologists dedicated to protecting and restoring your vision.',
		'testimonials_headline' => 'Real Results, Real People',
		'blogs_headline'     => 'Latest News & Insights',
		'faq_headline'       => 'Frequently Asked Questions',
		'faq_items'          => "How often should I get my eyes checked?|We recommend an annual exam for adults over 60 and one every two years for younger adults, unless you have risk factors.\nIs cataract surgery painful? How long is recovery?|Modern Phaco cataract surgery is stitchless and painless. Most patients go home the same day and resume normal activities within a few days.\nIs treatment free under Ayushman Bharat?|Yes. Eligible Ayushman Bharat / ration card holders and senior citizens (70+) can get cataract surgery free of cost. Please carry your Aadhaar card, Ayushman/ration card and registered mobile phone.\nWhen should children have their first eye exam?|Screenings are recommended at birth, age 1, age 3 and before starting school.",
		'stats_items'        => "14+|Years of service\n50,000+|Patients treated\n10,000+|Surgeries\n4.9/5|Patient rating",
		// Footer.
		'footer_description' => 'Providing clarity and vision to the world through advanced ophthalmology and compassionate care.',
		'footer_copyright'   => '© ' . gmdate( 'Y' ) . ' GP Healthcare Hospital. All rights reserved.',
		// Appointments.
		'appt_slots'         => '09:00 AM, 10:00 AM, 11:00 AM, 02:00 PM, 04:00 PM',
		'appt_fee'           => '150',
		'appt_auto_confirm'  => '1',
		'appt_notify_email'  => get_option( 'admin_email' ),
		'appt_require_login' => '1',
		// Advertisement popup.
		'ad_enabled'         => '',
		'ad_title'           => 'Free Eye Camp',
		'ad_text'            => 'Under Ayushman Bharat Scheme, FREE cataract surgery for all ration card holders & senior citizens (70+).',
		'ad_image'           => '',
		'ad_button'          => 'Book Appointment',
	);
}

/**
 * Parse "a|b|c" lines from a textarea into arrays.
 */
function bs_parse_lines( $text, $keys ) {
	$rows = array();
	foreach ( preg_split( '/\r\n|\r|\n/', (string) $text ) as $line ) {
		$line = trim( $line );
		if ( '' === $line ) {
			continue;
		}
		$parts = array_map( 'trim', explode( '|', $line ) );
		$row   = array();
		foreach ( $keys as $i => $k ) {
			$row[ $k ] = isset( $parts[ $i ] ) ? $parts[ $i ] : '';
		}
		$rows[] = $row;
	}
	return $rows;
}

function bs_phone_href( $phone ) {
	return 'tel:' . preg_replace( '/\s+/', '', (string) $phone );
}

function bs_whatsapp_url() {
	$num = preg_replace( '/\D/', '', (string) bs_opt( 'whatsapp_number' ) );
	if ( ! $num || '1' !== (string) bs_opt( 'whatsapp_enabled' ) ) {
		return '';
	}
	return 'https://wa.me/' . $num . '?text=' . rawurlencode( bs_opt( 'whatsapp_message' ) );
}

/**
 * URL of a page that uses a given page template (created on activation).
 */
function bs_page_url( $template ) {
	$pages = get_posts(
		array(
			'post_type'   => 'page',
			'meta_key'    => '_wp_page_template',
			'meta_value'  => $template,
			'numberposts' => 1,
			'post_status' => 'publish',
			'fields'      => 'ids',
		)
	);
	return $pages ? get_permalink( $pages[0] ) : home_url( '/' );
}

/**
 * Highlight {word} in the hero title.
 */
function bs_hero_title_html( $title ) {
	$safe = esc_html( $title );
	return preg_replace( '/\{(.+?)\}/', '<span class="text-primary-600">$1</span>', $safe );
}

/**
 * Doctor meta bundle.
 */
function bs_doctor_data( $post_id ) {
	$langs = get_post_meta( $post_id, '_bs_languages', true );
	return array(
		'id'            => $post_id,
		'name'          => get_the_title( $post_id ),
		'role'          => get_post_meta( $post_id, '_bs_role', true ),
		'qualification' => get_post_meta( $post_id, '_bs_qualification', true ),
		'experience'    => get_post_meta( $post_id, '_bs_experience', true ),
		'languages'     => $langs ? $langs : 'English',
		'fee'           => get_post_meta( $post_id, '_bs_fee', true ),
		'email'         => get_post_meta( $post_id, '_bs_email', true ),
		'days'          => (array) get_post_meta( $post_id, '_bs_days', true ),
		'online'        => '1' === get_post_meta( $post_id, '_bs_online', true ),
		'user_id'       => (int) get_post_meta( $post_id, '_bs_user_id', true ),
		'image'         => get_the_post_thumbnail_url( $post_id, 'large' ),
		'url'           => get_permalink( $post_id ),
	);
}

/**
 * Doctor post linked to the current user (for the doctor portal).
 */
function bs_current_doctor_id() {
	if ( ! is_user_logged_in() ) {
		return 0;
	}
	$q = get_posts(
		array(
			'post_type'   => 'bs_doctor',
			'meta_key'    => '_bs_user_id',
			'meta_value'  => get_current_user_id(),
			'numberposts' => 1,
			'fields'      => 'ids',
			'post_status' => 'any',
		)
	);
	return $q ? (int) $q[0] : 0;
}

function bs_is_doctor_user() {
	return current_user_can( 'bs_doctor' ) || current_user_can( 'manage_options' );
}

/**
 * Lucide-style inline icons.
 */
function bs_icon( $name, $size = 18, $class = '' ) {
	$paths = array(
		'phone'     => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>',
		'mail'      => '<rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>',
		'clock'     => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
		'map'       => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
		'calendar'  => '<rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/>',
		'user'      => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
		'menu'      => '<line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/>',
		'x'         => '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
		'chevron-down' => '<path d="m6 9 6 6 6-6"/>',
		'chevron-right' => '<path d="m9 18 6-6-6-6"/>',
		'chevron-left' => '<path d="m15 18-6-6 6-6"/>',
		'arrow-right' => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
		'arrow-up'  => '<path d="m5 12 7-7 7 7"/><path d="M12 19V5"/>',
		'arrow-up-right' => '<path d="M7 7h10v10"/><path d="M7 17 17 7"/>',
		'check'     => '<path d="M20 6 9 17l-5-5"/>',
		'check-circle' => '<circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/>',
		'shield'    => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/>',
		'star'      => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
		'stethoscope' => '<path d="M4.8 2.3A.3.3 0 1 0 5 2H4a2 2 0 0 0-2 2v5a6 6 0 0 0 6 6 6 6 0 0 0 6-6V4a2 2 0 0 0-2-2h-1a.2.2 0 1 0 .3.3"/><path d="M8 15v1a6 6 0 0 0 6 6 6 6 0 0 0 6-6v-4"/><circle cx="20" cy="10" r="2"/>',
		'play'      => '<polygon points="6 3 20 12 6 21 6 3"/>',
		'quote'     => '<path d="M16 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z"/><path d="M5 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z"/>',
		'plus'      => '<path d="M5 12h14"/><path d="M12 5v14"/>',
		'minus'     => '<path d="M5 12h14"/>',
		'send'      => '<path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/>',
		'graduation' => '<path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/><path d="M22 10v6"/><path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"/>',
		'languages' => '<path d="m5 8 6 6"/><path d="m4 14 6-6 2-3"/><path d="M2 5h12"/><path d="M7 2h1"/><path d="m22 22-5-10-5 10"/><path d="M14 18h6"/>',
		'badge'     => '<path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><path d="m9 12 2 2 4-4"/>',
		'message'   => '<path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/>',
		'file'      => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/>',
		'eye'       => '<path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/>',
		'heart'     => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>',
		'award'     => '<circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>',
		'home'      => '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
		'credit'    => '<rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>',
		'alert'     => '<circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/>',
		'loader'    => '<path d="M21 12a9 9 0 1 1-6.219-8.56"/>',
		'logout'    => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/>',
		'printer'   => '<polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/>',
		'pill'      => '<path d="m10.5 20.5 10-10a4.95 4.95 0 1 0-7-7l-10 10a4.95 4.95 0 1 0 7 7Z"/><path d="m8.5 8.5 7 7"/>',
		'facebook'  => '<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>',
		'twitter'   => '<path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/>',
		'instagram' => '<rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/>',
		'linkedin'  => '<path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect width="4" height="12" x="2" y="9"/><circle cx="4" cy="4" r="2"/>',
		'youtube'   => '<path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17"/><path d="m10 15 5-3-5-3z"/>',
	);
	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}
	return sprintf(
		'<svg xmlns="http://www.w3.org/2000/svg" width="%1$d" height="%1$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="%2$s" aria-hidden="true">%3$s</svg>',
		(int) $size,
		esc_attr( $class ),
		$paths[ $name ]
	);
}

function bs_the_icon( $name, $size = 18, $class = '' ) {
	echo bs_icon( $name, $size, $class ); // phpcs:ignore WordPress.Security.EscapeOutput -- static SVG markup.
}

/**
 * Section heading block.
 */
function bs_section_header( $args ) {
	$a      = wp_parse_args( $args, array( 'eyebrow' => '', 'title' => '', 'subtitle' => '', 'align' => 'left', 'action' => '', 'light' => false ) );
	$center = 'center' === $a['align'];
	echo '<div class="flex flex-col ' . ( $center ? 'items-center text-center' : 'md:flex-row md:items-end md:justify-between' ) . ' gap-6 mb-10 md:mb-14">';
	echo '<div class="max-w-2xl">';
	if ( $a['eyebrow'] ) {
		echo '<span class="eyebrow mb-3' . ( $a['light'] ? ' text-primary-300' : '' ) . '">' . esc_html( $a['eyebrow'] ) . '</span>';
	}
	if ( $a['title'] ) {
		echo '<h2 class="heading-lg' . ( $a['light'] ? ' text-white' : '' ) . '">' . esc_html( $a['title'] ) . '</h2>';
	}
	if ( $a['subtitle'] ) {
		echo '<p class="lead mt-3' . ( $a['light'] ? ' text-navy-200' : '' ) . '">' . esc_html( $a['subtitle'] ) . '</p>';
	}
	echo '</div>';
	if ( $a['action'] ) {
		echo '<div class="shrink-0">' . wp_kses_post( $a['action'] ) . '</div>';
	}
	echo '</div>';
}

/**
 * Status label + colour for appointments.
 */
function bs_status_badge( $status ) {
	$map = array(
		'pending'             => array( 'Pending', 'bg-amber-50 text-amber-700 border-amber-200' ),
		'confirmed'           => array( 'Confirmed', 'bg-emerald-50 text-emerald-700 border-emerald-200' ),
		'completed'           => array( 'Completed', 'bg-primary-50 text-primary-700 border-primary-200' ),
		'cancelled'           => array( 'Cancelled', 'bg-red-50 text-red-700 border-red-200' ),
		'reschedule-requested' => array( 'Reschedule requested', 'bg-purple-50 text-purple-700 border-purple-200' ),
	);
	$s = isset( $map[ $status ] ) ? $map[ $status ] : array( ucfirst( $status ), 'bg-navy-50 text-navy-600 border-navy-200' );
	return '<span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-bold ' . esc_attr( $s[1] ) . '">' . esc_html( $s[0] ) . '</span>';
}
