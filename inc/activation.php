<?php
/**
 * On activation: create required pages, front page, menu and demo content.
 *
 * @package BSHealthcare
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Page slug => page template used by this theme.
 */
function bs_required_pages() {
	return array(
		'home'      => array( 'Home', 'templates/template-home.php' ),
		'about'     => array( 'About Us', 'templates/template-about.php' ),
		'contact'   => array( 'Contact', 'templates/template-contact.php' ),
		'appt'      => array( 'Book Appointment', 'templates/template-appointment.php' ),
		'login'     => array( 'Patient Login', 'templates/template-login.php' ),
		'patient'   => array( 'My Dashboard', 'templates/template-patient-dashboard.php' ),
		'doctor'    => array( 'Doctor Portal', 'templates/template-doctor-dashboard.php' ),
		'blog'      => array( 'Blog', '' ),
	);
}

/**
 * Create any missing pages and make sure every one carries its page template.
 * Safe to run repeatedly – it only fills in what is missing.
 *
 * @return array slug key => page ID
 */
function bs_ensure_pages() {
	$pages = bs_required_pages();
	$ids   = array();
	foreach ( $pages as $k => $p ) {
		$existing = get_page_by_path( sanitize_title( $p[0] ) );
		if ( $existing ) {
			$ids[ $k ] = $existing->ID;
		} else {
			$ids[ $k ] = wp_insert_post(
				array(
					'post_type'    => 'page',
					'post_status'  => 'publish',
					'post_title'   => $p[0],
					'post_name'    => sanitize_title( $p[0] ),
					'post_content' => '',
				)
			);
		}
		// Always (re)assign the template – a page with this slug may already have
		// existed from a previous theme, in which case it carries no template.
		if ( $p[1] && ! is_wp_error( $ids[ $k ] ) ) {
			update_post_meta( $ids[ $k ], '_wp_page_template', $p[1] );
		}
	}
	return $ids;
}

/**
 * Runs on theme activation.
 */
function bs_activate() {
	bs_register_roles();
	bs_register_post_types();
	flush_rewrite_rules();

	$ids = bs_ensure_pages();

	if ( get_option( 'bs_installed' ) ) {
		return;
	}

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $ids['home'] );
	update_option( 'page_for_posts', $ids['blog'] );

	bs_seed_demo_content();

	// Primary menu.
	$menu_id = wp_create_nav_menu( 'Primary Menu' );
	if ( ! is_wp_error( $menu_id ) ) {
		$items = array(
			array( 'Home', home_url( '/' ) ),
			array( 'About Us', get_permalink( $ids['about'] ) ),
			array( 'Specialities', home_url( '/specialities/' ) ),
			array( 'Doctors', home_url( '/doctors/' ) ),
			array( 'Blogs', get_permalink( $ids['blog'] ) ),
			array( 'Contact', get_permalink( $ids['contact'] ) ),
		);
		foreach ( $items as $i => $it ) {
			wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => $it[0], 'menu-item-url' => $it[1], 'menu-item-status' => 'publish', 'menu-item-position' => $i + 1 ) );
		}
		$locations            = get_theme_mod( 'nav_menu_locations', array() );
		$locations['primary'] = $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
	}

	update_option( 'bs_installed', 1 );
	update_option( 'bs_theme_version', BS_VERSION );
}
add_action( 'after_switch_theme', 'bs_activate' );

/**
 * Self-heal on upgrade: pages that already existed before the theme was installed
 * never received their page template, which makes them fall back to the plain
 * page layout. Runs once per theme version.
 */
function bs_maybe_upgrade() {
	if ( ! is_admin() || wp_doing_ajax() || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( get_option( 'bs_theme_version' ) === BS_VERSION ) {
		return;
	}
	bs_ensure_pages();
	flush_rewrite_rules();
	update_option( 'bs_theme_version', BS_VERSION );
}
add_action( 'admin_init', 'bs_maybe_upgrade' );

/**
 * Demo doctors, specialities and testimonials so the site is not empty.
 */
function bs_seed_demo_content() {
	if ( wp_count_posts( 'bs_service' )->publish > 0 ) {
		return;
	}
	$services = array(
		array( 'Cataract Surgery', 'Advanced cataract removal including Phaco and SICS methods.', 'Phaco & SICS Methods', 'cataract', "Phaco Method|Ultrasound phacoemulsification for quick recovery.\nSICS Method|Small incision surgery for mature cataracts.\nIOL Implantation|Premium lens options for the best visual outcome.\nPost-Op Care|Comprehensive follow-up to ensure healing." ),
		array( 'Glaucoma Surgery', 'Advanced surgical interventions to lower eye pressure and protect the optic nerve.', 'Trabeculectomy & Laser', 'glaucoma', "Trabeculectomy|Creates a new drainage channel to lower pressure.\nLaser Therapy|SLT / YAG laser for early-stage control.\nMonitoring|Regular visual field and OCT tracking.\nLifetime Care|Long-term management plans." ),
		array( 'Retina Check-up', 'Comprehensive retinal examination and treatment for diabetic retinopathy and macular disease.', 'OCT & Laser Treatment', 'retina', "OCT Imaging|High-resolution retinal cross-sections.\nDiabetic Screening|Early detection of diabetic retinopathy.\nLaser Treatment|Precise retinal laser therapy.\nInjections|Anti-VEGF therapy where needed." ),
		array( 'Glass Check-up', 'Computer-assisted refraction and prescription of corrective glasses.', 'Computerised Eye Testing', 'refraction', "Auto Refraction|Japanese computer-assisted testing.\nSubjective Refraction|Fine-tuned by our optometrists.\nKids Testing|Child-friendly vision assessment.\nOptical Shop|Frames and lenses on site." ),
		array( 'Cornea Check-up', 'Comprehensive examination of the cornea and treatment of corneal conditions.', 'Corneal Care', 'cornea', "Topography|Detailed corneal mapping.\nDry Eye Care|Diagnosis and treatment plans.\nInfections|Rapid treatment of corneal ulcers.\nContact Lenses|Specialised fitting." ),
		array( 'Pterygium Surgery', 'Specialised removal using the autografting method for low recurrence.', 'Autografting Method', 'cornea', "Autografting|Using your own tissue for best results.\nSutureless Options|Advanced glue for patient comfort.\nCosmetic Restoration|Returns the eye to a normal appearance.\nPrevention|UV protection guidance." ),
		array( 'DCR Surgery', 'Dacryocystorhinostomy for blocked tear ducts and watery eyes.', 'Tear Duct Surgery', 'oculoplast', "Evaluation|Syringing and probing to locate the block.\nExternal DCR|Proven technique with high success.\nEndoscopic DCR|Scarless option.\nRecovery|Quick return to daily routine." ),
		array( 'Pediatric Eye Care', 'Gentle, specialised care for children including squint and lazy eye.', 'Kids Vision', 'pediatric', "Vision Screening|From birth to school age.\nSquint Correction|Surgical and non-surgical options.\nAmblyopia Therapy|Patching and vision therapy.\nMyopia Control|Slowing progression in children." ),
	);
	foreach ( $services as $i => $s ) {
		$id = wp_insert_post(
			array(
				'post_type'    => 'bs_service',
				'post_status'  => 'publish',
				'post_title'   => $s[0],
				'post_excerpt' => $s[1],
				'post_content' => '<p>' . $s[1] . ' Our team ensures the best method is chosen for your specific eye health and lifestyle needs, using modern equipment and proven techniques.</p>',
				'menu_order'   => $i,
			)
		);
		update_post_meta( $id, '_bs_hero_sub', $s[2] );
		update_post_meta( $id, '_bs_tagline', 'Expert care for clear vision' );
		update_post_meta( $id, '_bs_keyword', $s[3] );
		update_post_meta( $id, '_bs_scope_title', 'Our Techniques' );
		update_post_meta( $id, '_bs_scope_points', $s[4] );
	}

	$doctors = array(
		array( 'Dr. A. Sharma', 'Senior Ophthalmologist & Cataract Surgeon', 'MBBS, MS (Ophthalmology), DMCH', '25+ years', 'Hindi, English', 'cataract, glaucoma' ),
		array( 'Dr. R. Verma', 'Ophthalmologist', 'MBBS, MS (Ophthalmology)', '12+ years', 'Hindi, English', 'retina, refraction' ),
		array( 'Dr. S. Gupta', 'Ophthalmologist', 'MBBS, DOMS', '10+ years', 'Hindi, English', 'cornea, pediatric' ),
		array( 'Dr. M. Nair', 'Ophthalmologist', 'MBBS, MS (Ophthalmology)', '8+ years', 'Hindi, English', 'oculoplast, cataract' ),
	);
	foreach ( $doctors as $i => $d ) {
		$id = wp_insert_post( array( 'post_type' => 'bs_doctor', 'post_status' => 'publish', 'post_title' => $d[0], 'post_content' => $d[0] . ' is a dedicated eye specialist committed to compassionate, evidence-based care.', 'menu_order' => $i ) );
		update_post_meta( $id, '_bs_role', $d[1] );
		update_post_meta( $id, '_bs_qualification', $d[2] );
		update_post_meta( $id, '_bs_experience', $d[3] );
		update_post_meta( $id, '_bs_languages', $d[4] );
		update_post_meta( $id, '_bs_keyword', $d[5] );
		update_post_meta( $id, '_bs_days', array( 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat' ) );
		update_post_meta( $id, '_bs_online', '1' );
		update_post_meta( $id, '_bs_fee', bs_opt( 'appt_fee' ) );
	}

	$testimonials = array(
		array( 'Ramesh Kumar', 'Glaucoma Patient', "I was terrified when I was diagnosed with early-stage glaucoma. The team didn't just treat my eyes; they treated my fears. The laser treatment was painless, and I feel confident about my future vision." ),
		array( 'Arjun Pandey', 'LASIK Patient', 'Waking up and seeing the alarm clock clearly without reaching for glasses is a miracle I experience every day now. The recovery was faster than I imagined.' ),
		array( 'Lakshmi Devi', 'Cataract Surgery', 'The colours! I had forgotten how vibrant the world actually is. The doctor explained every step, and the stitchless procedure was over before I knew it.' ),
	);
	foreach ( $testimonials as $i => $t ) {
		$id = wp_insert_post( array( 'post_type' => 'bs_testimonial', 'post_status' => 'publish', 'post_title' => $t[0], 'post_content' => $t[2], 'menu_order' => $i ) );
		update_post_meta( $id, '_bs_role', $t[1] );
	}
}
