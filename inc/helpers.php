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
		$val = $opts[ $key ];
		if ( 'home_sections' === $key ) {
			$val = str_replace( 'ayushman', 'gallery', (string) $val );
		}
		return $val;
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
		'color_primary'      => '#2563eb',
		'color_dark'         => '#0f1f3d',
		'site_tagline'       => 'Sight for Life',
		'phone'              => '+91 77649 63174',
		'emergency'          => '',
		'email'              => 'netranaeye@gmail.com',
		'address'            => 'Saiyari, Opposite Hanuman Mandir, At Gate, Gaya - Cherki Road, Gaya, Bihar 823001',
		'hours_opd'          => '8:00 AM – 6:00 PM',
		'hours_inpatient'    => 'Day Care & Inpatient Available',
		'whatsapp_enabled'   => '1',
		'whatsapp_number'    => '919525334214',
		'whatsapp_message'   => 'Hello Netrana Eye Hospital! I would like to book an appointment or inquire about eye treatment.',
		'map_embed'          => 'https://maps.google.com/maps?q=Saiyari+Opposite+Hanuman+Mandir+Gaya+Cherki+Road+Gaya+823001&t=&z=15&ie=UTF8&iwloc=&output=embed',
		'map_link'           => 'https://maps.app.goo.gl/ZaTGDVq2e7E5cdhh9',
		'social_facebook'    => '',
		'social_twitter'     => '',
		'social_instagram'   => '',
		'social_linkedin'    => '',
		'social_youtube'     => '',
		'home_sections'      => 'hero,gallery,why,about,services,doctors,testimonials,blogs,faq',
		'hero_eyebrow'       => 'Netrana Eye Hospital · Gaya, Bihar',
		'hero_title'         => 'Clear Vision, {Sight for Life} for Every Eye.',
		'hero_subtitle'      => 'Advanced stitchless Phaco cataract surgery, comprehensive optometry and compassionate eye care in Gaya, Bihar. Dedicated to a community free from avoidable blindness.',
		'hero_image'         => BS_URI . '/assets/img/hero-bg.jpg',
		'hero_overlay'       => '50',
		'hero_cta'           => 'Book Appointment',
		'hero_secondary'     => 'Our Journey',
		'hero_secondary_url' => '',
		'hero_highlights'    => "NABH Quality Protocol & Modular OT\nModern Stitchless Phaco Cataract Surgery\nSame-day Discharge & Fast Recovery",
		'hero_rating'        => '4.9/5 Rating',
		'hero_rating_sub'    => '60,000+ Happy Patients',
		'gallery_headline'   => 'Grand Opening & Hospital Facilities',
		'gallery_subtitle'   => 'Glimpses from the inauguration and state-of-the-art ophthalmic facilities of Netrana Eye Hospital in Gaya, Bihar.',
		'why_headline'       => 'Why Choose Netrana Eye Hospital',
		'why_subtitle'       => 'Bringing over 17 years of trusted clinical heritage and modern ophthalmology to Gaya, Bihar.',
		'why_items'          => "Advanced Phaco Surgery|Small-incision stitch-free cataract surgery with rapid visual recovery.|" . BS_URI . "/assets/img/surgery.png\nState-of-the-Art Hospital & Modular OT|Sterile modular operation theatre and advanced micro-surgical equipment.|" . BS_URI . "/assets/img/inauguration-celebration.jpg\nExperienced Eye Specialists|Dedicated team with 17+ years of legacy in treating complex vision disorders.|" . BS_URI . "/assets/img/inauguration-team.jpg\nCompassionate Patient Care|Clean clinical environment, transparent counselling, and personalised attention.|" . BS_URI . '/assets/img/care.jpg',
		'about_headline'     => 'Excellence in Vision Care & Cataract Surgery',
		'about_text'         => 'Netrana Eye Hospital in Gaya is dedicated to protecting and restoring vision. From complete eye examinations to stitchless Phaco cataract surgery, no one in our community should live with preventable blindness.',
		'services_headline'  => 'Comprehensive Eye Care Services',
		'services_subtitle'  => 'From routine check-ups to advanced cataract surgery, every treatment is powered by modern ophthalmic technology.',
		'doctors_headline'   => 'Meet Our Medical Experts',
		'doctors_subtitle'   => 'Experienced ophthalmologists and eye specialists dedicated to protecting your eyesight.',
		'testimonials_headline' => 'Real Patient Experiences',
		'blogs_headline'     => 'Latest Eye Health Insights',
		'faq_headline'       => 'Frequently Asked Questions',
		'faq_items'          => "What is Phaco cataract surgery and how long is recovery?|Phaco (Phacoemulsification) is a modern small-incision, stitch-free cataract procedure. It requires no painful stitches, causes minimal discomfort, and allows most patients to return home the same day and resume daily activities quickly.\nWhat diagnostic and surgical facilities are available at Netrana Eye Hospital?|Netrana Eye Hospital is equipped with sterile modular operation theatres, advanced Phacoemulsification systems, computerized auto-refraction, slit-lamp biomicroscopy, optical prescription testing, and dedicated recovery rooms for same-day discharge.\nWhat are the hospital OPD timings?|Our OPD consultation hours are Monday to Saturday from 8:00 AM to 6:00 PM at Saiyari, Opposite Hanuman Mandir, At Gate Gaya Cherki Road, Gaya.\nHow can I book an appointment?|You can easily book online using the form on this website, call our OPD desk directly at 77649 63174, or message us on WhatsApp at 95253 34214.\nDo you provide complete glasses and spectacles testing?|Yes, our qualified optometrists provide computer-assisted auto-refraction and subjective eye testing with precise prescription eyeglasses on-site.",
		'stats_items'        => "17+|Years of Heritage\n60,000+|Happy Patients\n40,000+|Successful Surgeries\n40+|Hospital Beds",
		// Footer.
		'footer_description' => 'Netrana Eye Hospital in Gaya, Bihar is dedicated to protecting and restoring vision through advanced Phaco cataract surgery, optometry, and compassionate eye care.',
		'footer_copyright'   => '© ' . gmdate( 'Y' ) . ' Netrana Eye Hospital Private Limited. All rights reserved.',
		// Appointments.
		'appt_slots'         => '08:30 AM, 09:30 AM, 10:30 AM, 11:30 AM, 02:00 PM, 03:00 PM, 04:00 PM, 05:00 PM',
		'appt_fee'           => '150',
		'appt_auto_confirm'  => '1',
		'appt_notify_email'  => 'netranaeye@gmail.com',
		'appt_require_login' => '1',
		// Advertisement popup.
		'ad_enabled'         => '',
		'ad_title'           => 'Free Vision Screening & Cataract Consultation',
		'ad_text'            => 'Comprehensive eye check-up and expert consultation by senior ophthalmologists at Netrana Eye Hospital.',
		'ad_image'           => '',
		'ad_button'          => 'Book Consultation',
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
	if ( ! empty( $pages ) ) {
		return get_permalink( $pages[0] );
	}
	// Fallback by slug deduced from template filename.
	$slug = basename( $template, '.php' );
	$slug = str_replace( array( 'template-', 'page-' ), '', $slug );
	$p    = get_page_by_path( $slug );
	if ( ! $p ) {
		$p = get_page_by_path( $slug . '-us' );
	}
	if ( ! $p && function_exists( 'get_page_by_title' ) ) {
		$p = get_page_by_title( ucwords( str_replace( '-', ' ', $slug ) ) );
		if ( ! $p && 'about' === $slug ) {
			$p = get_page_by_title( 'About Us' );
		}
	}
	if ( $p ) {
		return get_permalink( is_object( $p ) ? $p->ID : $p );
	}

	// If pretty permalinks are disabled on the server, avoid 404/500 by querying pagename.
	if ( empty( get_option( 'permalink_structure' ) ) ) {
		return home_url( '/?pagename=' . $slug );
	}
	return home_url( '/' . $slug . '/' );
}

/**
 * Highlight {word} in the hero title.
 */
function bs_hero_title_html( $title ) {
	$safe = esc_html( $title );
	return preg_replace( '/\{(.+?)\}/', '<span class="text-primary-600">$1</span>', $safe );
}

/**
 * Smart contextual fallback image URL for a service.
 */
function bs_service_image_url( $post_id ) {
	$thumb = get_the_post_thumbnail_url( $post_id, 'bs-card' );
	if ( $thumb ) {
		return $thumb;
	}
	$slug = strtolower( (string) get_post_field( 'post_name', $post_id ) . ' ' . get_the_title( $post_id ) . ' ' . get_post_meta( $post_id, '_bs_keyword', true ) );
	if ( false !== strpos( $slug, 'cataract' ) || false !== strpos( $slug, 'phaco' ) ) {
		return BS_URI . '/assets/img/service-cataract.jpg';
	}
	if ( false !== strpos( $slug, 'glaucoma' ) ) {
		return BS_URI . '/assets/img/service-glaucoma.jpg';
	}
	if ( false !== strpos( $slug, 'retina' ) || false !== strpos( $slug, 'diabetic' ) ) {
		return BS_URI . '/assets/img/service-retina.jpg';
	}
	if ( false !== strpos( $slug, 'glass' ) || false !== strpos( $slug, 'refraction' ) || false !== strpos( $slug, 'optometry' ) ) {
		return BS_URI . '/assets/img/service-refraction.jpg';
	}
	if ( false !== strpos( $slug, 'pediatric' ) || false !== strpos( $slug, 'child' ) || false !== strpos( $slug, 'squint' ) ) {
		return BS_URI . '/assets/img/service-pediatric.jpg';
	}
	if ( false !== strpos( $slug, 'cornea' ) || false !== strpos( $slug, 'pterygium' ) ) {
		return BS_URI . '/assets/img/service-cornea.jpg';
	}
	return BS_URI . '/assets/img/service-cataract.jpg';
}

/**
 * Smart contextual fallback image URL for a doctor.
 */
function bs_doctor_image_url( $post_id ) {
	$thumb = get_the_post_thumbnail_url( $post_id, 'large' );
	if ( $thumb ) {
		return $thumb;
	}
	$title = strtolower( (string) get_the_title( $post_id ) . ' ' . get_post_meta( $post_id, '_bs_role', true ) );
	if ( false !== strpos( $title, 'arvind' ) || false !== strpos( $title, 'director' ) ) {
		return BS_URI . '/assets/img/doctor-arvind.jpg';
	}
	if ( false !== strpos( $title, 'sharma' ) || false !== strpos( $title, 'cataract' ) ) {
		return BS_URI . '/assets/img/doctor-sharma.jpg';
	}
	if ( false !== strpos( $title, 'verma' ) || false !== strpos( $title, 'retina' ) ) {
		return BS_URI . '/assets/img/doctor-verma.jpg';
	}
	if ( false !== strpos( $title, 'gupta' ) || false !== strpos( $title, 'pediatric' ) ) {
		return BS_URI . '/assets/img/doctor-gupta.jpg';
	}
	return BS_URI . '/assets/img/doctor-arvind.jpg';
}

/**
 * Smart contextual fallback image URL for a blog post.
 */
function bs_post_image_url( $post_id ) {
	$thumb = get_the_post_thumbnail_url( $post_id, 'bs-card' );
	if ( $thumb ) {
		return $thumb;
	}
	$title = strtolower( (string) get_the_title( $post_id ) . ' ' . get_post_field( 'post_name', $post_id ) );
	if ( false !== strpos( $title, 'glaucoma' ) || false !== strpos( $title, 'pressure' ) ) {
		return BS_URI . '/assets/img/blog-glaucoma.jpg';
	}
	if ( false !== strpos( $title, 'cataract' ) || false !== strpos( $title, 'phaco' ) || false !== strpos( $title, 'stitchless' ) ) {
		return BS_URI . '/assets/img/blog-cataract.jpg';
	}
	if ( false !== strpos( $title, 'glass' ) || false !== strpos( $title, 'refraction' ) || false !== strpos( $title, 'optometry' ) ) {
		return BS_URI . '/assets/img/service-refraction.jpg';
	}
	return BS_URI . '/assets/img/inauguration-team.jpg';
}

/**
 * Smart contextual fallback image URL for testimonials.
 */
function bs_testimonial_image_url( $post_id, $index = 0 ) {
	$thumb = get_the_post_thumbnail_url( $post_id, 'large' );
	if ( $thumb ) {
		return $thumb;
	}
	$images = array(
		BS_URI . '/assets/img/inauguration-celebration.jpg',
		BS_URI . '/assets/img/care.jpg',
		BS_URI . '/assets/img/service-refraction.jpg',
		BS_URI . '/assets/img/inauguration-team.jpg',
	);
	return $images[ $index % count( $images ) ];
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
		'image'         => bs_doctor_image_url( $post_id ),
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
