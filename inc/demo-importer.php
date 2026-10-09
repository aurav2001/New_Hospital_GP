<?php
/**
 * One-Click Demo Data Importer & WXR Exporter Helper.
 *
 * @package GPHealthcare
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Appearance -> Demo Import menu.
 */
function bs_demo_importer_menu() {
	add_theme_page(
		__( 'Demo Data Import', 'bshealthcare' ),
		__( 'Demo Import', 'bshealthcare' ),
		'manage_options',
		'bs-demo-import',
		'bs_demo_import_page'
	);
}
add_action( 'admin_menu', 'bs_demo_importer_menu' );

/**
 * Handle Demo Import execution via Admin POST.
 */
function bs_handle_demo_import_post() {
	if ( isset( $_POST['bs_fix_menus_action'] ) ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized access', 'bshealthcare' ) );
		}
		check_admin_referer( 'bs_demo_import_nonce' );

		bs_ensure_nav_menus( true );

		set_transient( 'bs_demo_import_feedback', array( 'menus_only' => true ), 60 );
		wp_safe_redirect( admin_url( 'themes.php?page=bs-demo-import&menus_fixed=1' ) );
		exit;
	}

	if ( ! isset( $_POST['bs_import_demo_action'] ) ) {
		return;
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Unauthorized access', 'bshealthcare' ) );
	}
	check_admin_referer( 'bs_demo_import_nonce' );

	$results = bs_run_full_demo_import();

	set_transient( 'bs_demo_import_feedback', $results, 60 );
	wp_safe_redirect( admin_url( 'themes.php?page=bs-demo-import&imported=1' ) );
	exit;
}
add_action( 'admin_init', 'bs_handle_demo_import_post' );

/**
 * Core function that generates/imports all demo content.
 */
function bs_run_full_demo_import() {
	$count_pages     = 0;
	$count_services  = 0;
	$count_doctors   = 0;
	$count_reviews   = 0;
	$count_posts     = 0;
	$count_menus     = 0;

	// 1. Pages Setup
	$pages_def = array(
		'home'    => array( 'Home', 'templates/template-home.php', '' ),
		'about'   => array( 'About Us', 'templates/template-about.php', '<p>Netrana Eye Hospital, located in Gaya, Bihar, is dedicated to protecting and restoring vision for the people of Gaya and the surrounding communities. Our mission is simple: no one in our community should live with preventable blindness.</p><p>The hospital offers comprehensive eye care under one roof, including complete eye examinations, optometry services, and spectacle correction by qualified optometrists. Our specialty is cataract surgery, performed with modern phacoemulsification (Phaco) technology.</p>' ),
		'contact' => array( 'Contact', 'templates/template-contact.php', '' ),
		'appt'    => array( 'Book Appointment', 'templates/template-appointment.php', '' ),
		'login'   => array( 'Patient Login', 'templates/template-login.php', '' ),
		'patient' => array( 'My Dashboard', 'templates/template-patient-dashboard.php', '' ),
		'doctor'  => array( 'Doctor Portal', 'templates/template-doctor-dashboard.php', '' ),
		'blog'    => array( 'Blog', '', '' ),
	);

	$page_ids = array();
	foreach ( $pages_def as $k => $p ) {
		$existing = get_page_by_path( sanitize_title( $p[0] ) );
		if ( $existing ) {
			$page_ids[ $k ] = $existing->ID;
			if ( $p[1] ) {
				update_post_meta( $existing->ID, '_wp_page_template', $p[1] );
			}
		} else {
			$new_id = wp_insert_post(
				array(
					'post_type'    => 'page',
					'post_status'  => 'publish',
					'post_title'   => $p[0],
					'post_name'    => sanitize_title( $p[0] ),
					'post_content' => $p[2],
				)
			);
			if ( ! is_wp_error( $new_id ) ) {
				$page_ids[ $k ] = $new_id;
				$count_pages++;
				if ( $p[1] ) {
					update_post_meta( $new_id, '_wp_page_template', $p[1] );
				}
			}
		}
	}

	// Set Front page & Posts page
	if ( ! empty( $page_ids['home'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $page_ids['home'] );
	}
	if ( ! empty( $page_ids['blog'] ) ) {
		update_option( 'page_for_posts', $page_ids['blog'] );
	}

	// 2. Categories
	$cat_surgery = wp_insert_term( 'Cataract & Surgery', 'category', array( 'slug' => 'cataract-surgery' ) );
	$cat_vision  = wp_insert_term( 'Preventive Eye Care', 'category', array( 'slug' => 'preventive-eye-care' ) );
	$cat_health   = wp_insert_term( 'Eye Health & Tips', 'category', array( 'slug' => 'eye-health-tips' ) );
	$cat_glasses  = wp_insert_term( 'Optometry & Refraction', 'category', array( 'slug' => 'optometry-refraction' ) );

	$cat_surgery_id  = ! is_wp_error( $cat_surgery ) ? $cat_surgery['term_id'] : get_category_by_slug( 'cataract-surgery' )->term_id;
	$cat_vision_id   = ! is_wp_error( $cat_vision ) ? $cat_vision['term_id'] : get_category_by_slug( 'preventive-eye-care' )->term_id;
	$cat_health_id   = ! is_wp_error( $cat_health ) ? $cat_health['term_id'] : get_category_by_slug( 'eye-health-tips' )->term_id;

	// 3. Blog Posts
	$sample_posts = array(
		array(
			'Understanding Stitchless Phaco Cataract Surgery: Recovery & Advantages',
			'understanding-stitchless-phaco-cataract-surgery',
			'<p>Cataract (Motiyabind) is the leading cause of preventable visual impairment. At <strong>Netrana Eye Hospital in Gaya, Bihar</strong>, we specialize in modern <em>phacoemulsification (Phaco)</em> technology—a small-incision, stitch-free surgery that provides crystal-clear vision restoration with rapid healing.</p><h3>Why Choose Stitchless Phaco Surgery?</h3><ul><li><strong>No Injections, No Stitches:</strong> Small-incision ultrasonic probe gently dissolves the cloudy lens with zero stitches.</li><li><strong>Same-Day Discharge:</strong> The procedure takes only 15 to 20 minutes, allowing patients to walk home the same day.</li><li><strong>Premium Foldable IOL:</strong> Advanced monofocal and toric lens implants provide crystal-clear visual acuity.</li><li><strong>Fast Healing:</strong> Return to daily household work and routine within 2 to 3 days.</li></ul><p>Under the leadership of Director Arvind Kumar, Netrana Eye Hospital ensures accessible, safe, and compassionate cataract surgery for every family in Gaya and surrounding communities.</p>',
			'Learn how modern stitchless Phaco cataract surgery at Netrana Eye Hospital provides stitch-free recovery and crystal-clear vision.',
			array( $cat_surgery_id, $cat_health_id ),
		),
		array(
			'Complete Guide to Diabetic Retinopathy and Protecting Your Vision',
			'complete-guide-to-diabetic-retinopathy',
			'<p>Diabetes can silently affect the blood vessels of your retina, leading to diabetic retinopathy if not monitored regularly. At <strong>Netrana Eye Hospital</strong>, we provide digital OCT cross-sectional imaging and dilated fundus examinations to protect your eyesight.</p><h3>Signs You Should Not Ignore:</h3><ul><li>Blurry or fluctuating vision</li><li>Dark floating spots or strings (floaters)</li><li>Difficulty reading or driving at night</li></ul><p>Early diagnosis allows timely management with laser treatment and anti-VEGF therapies, preventing permanent vision impairment.</p>',
			'Essential guidance for diabetic patients in Gaya to detect and manage diabetic eye disease early.',
			array( $cat_vision_id, $cat_health_id ),
		),
		array(
			'Glaucoma: The Silent Thief of Sight and Why Regular Pressure Checkups Matter',
			'glaucoma-silent-thief-of-sight',
			'<p>Glaucoma (Kala Motia) is known as the silent thief of sight because it damages the optic nerve gradually without early pain or obvious warning symptoms until substantial vision is permanently lost.</p><h3>Key Risk Factors:</h3><ul><li>Age above 40 years</li><li>Family history of glaucoma</li><li>High intraocular eye pressure</li><li>Diabetes or high blood pressure</li></ul><p>At Netrana Eye Hospital, we offer tonometry pressure testing, OCT optic nerve imaging, and advanced SLT/YAG laser therapy to keep eye pressure under control.',
			'Why early detection through tonometry and optic nerve screening protects your eyesight against irreversible glaucoma damage.',
			array( $cat_health_id ),
		),
	);

	foreach ( $sample_posts as $sp ) {
		$post_obj = get_page_by_path( $sp[1], OBJECT, 'post' );
		if ( ! $post_obj ) {
			$post_id = wp_insert_post(
				array(
					'post_type'    => 'post',
					'post_status'  => 'publish',
					'post_title'   => $sp[0],
					'post_name'    => $sp[1],
					'post_content' => $sp[2],
					'post_excerpt' => $sp[3],
					'post_category'=> $sp[4],
				)
			);
			if ( ! is_wp_error( $post_id ) ) {
				$count_posts++;
			}
		}
	}

	// 4. Specialities (bs_service)
	$services = array(
		array( 'Cataract Surgery', 'Advanced stitchless Phaco cataract surgery with premium lens implantation.', 'Phaco & SICS Methods', 'cataract', "Phaco Method|Ultrasound small-incision phacoemulsification for rapid recovery.\nFoldable IOL|Premium monofocal, toric, and multifocal lens options.\nStitch-Free Technique|No pad, no injection, sutureless precision.\nPost-Op Care|Comprehensive follow-up to ensure optimal crystal-clear vision." ),
		array( 'Glaucoma Surgery', 'Advanced surgical and laser interventions to regulate intraocular pressure and protect the optic nerve.', 'Trabeculectomy & Laser', 'glaucoma', "Trabeculectomy|Creates a new drainage channel to lower pressure.\nLaser Therapy|SLT / YAG laser for early-stage control.\nMonitoring|Regular visual field and OCT tracking.\nLifetime Care|Long-term management plans." ),
		array( 'Retina Check-up', 'Comprehensive retinal examination and treatment for diabetic retinopathy and macular disease.', 'OCT & Laser Treatment', 'retina', "OCT Imaging|High-resolution retinal cross-sections.\nDiabetic Screening|Early detection of diabetic retinopathy.\nLaser Treatment|Precise retinal laser therapy.\nInjections|Anti-VEGF therapy where needed." ),
		array( 'Glass Check-up', 'Computer-assisted refraction and prescription of corrective glasses by optometrists.', 'Computerised Eye Testing', 'refraction', "Auto Refraction|Advanced computer-assisted vision testing.\nSubjective Refraction|Fine-tuned by our qualified optometrists.\nKids Testing|Child-friendly vision assessment.\nOptical Shop|Accurate frames and lenses on site." ),
		array( 'Cornea Check-up', 'Comprehensive examination of the cornea and treatment of corneal conditions.', 'Corneal Care', 'cornea', "Topography|Detailed corneal mapping.\nDry Eye Care|Diagnosis and treatment plans.\nInfections|Rapid treatment of corneal ulcers.\nContact Lenses|Specialised fitting." ),
		array( 'Pterygium Surgery', 'Specialised removal using the autografting method for low recurrence.', 'Autografting Method', 'cornea', "Autografting|Using your own tissue for best results.\nSutureless Options|Advanced glue for patient comfort.\nCosmetic Restoration|Returns the eye to a normal appearance.\nPrevention|UV protection guidance." ),
		array( 'DCR Surgery', 'Dacryocystorhinostomy for blocked tear ducts and watery eyes.', 'Tear Duct Surgery', 'oculoplast', "Evaluation|Syringing and probing to locate the block.\nExternal DCR|Proven technique with high success.\nEndoscopic DCR|Scarless option.\nRecovery|Quick return to daily routine." ),
		array( 'Pediatric Eye Care', 'Gentle, specialised care for children including squint and lazy eye.', 'Kids Vision', 'pediatric', "Vision Screening|From birth to school age.\nSquint Correction|Surgical and non-surgical options.\nAmblyopia Therapy|Patching and vision therapy.\nMyopia Control|Slowing progression in children." ),
	);

	foreach ( $services as $i => $s ) {
		$existing = get_page_by_path( sanitize_title( $s[0] ), OBJECT, 'bs_service' );
		if ( ! $existing ) {
			$id = wp_insert_post(
				array(
					'post_type'    => 'bs_service',
					'post_status'  => 'publish',
					'post_title'   => $s[0],
					'post_excerpt' => $s[1],
					'post_content' => '<p>' . $s[1] . ' At Netrana Eye Hospital in Gaya, our ophthalmology team ensures the best treatment method is selected for your vision needs, backed by modern technology and compassionate care.</p>',
					'menu_order'   => $i + 1,
				)
			);
			if ( ! is_wp_error( $id ) ) {
				update_post_meta( $id, '_bs_hero_sub', $s[2] );
				update_post_meta( $id, '_bs_tagline', 'Sight for Life' );
				update_post_meta( $id, '_bs_keyword', $s[3] );
				update_post_meta( $id, '_bs_scope_title', 'Our Techniques' );
				update_post_meta( $id, '_bs_scope_points', $s[4] );
				$count_services++;
			}
		}
	}

	// 5. Doctors (bs_doctor)
	$doctors = array(
		array( 'Dr. Arvind Kumar', 'Director & Chief Eye Care Specialist', 'MBBS, MS (Ophthalmology)', '17+ years', 'Hindi, English', 'cataract, refraction' ),
		array( 'Dr. A. Sharma', 'Senior Consultant & Cataract Surgeon', 'MBBS, MS (Ophthalmology), FIJR', '15+ years', 'Hindi, English', 'cataract, glaucoma' ),
		array( 'Dr. R. Verma', 'Consultant Ophthalmologist & Retina Specialist', 'MBBS, MS (Ophthalmology)', '12+ years', 'Hindi, English', 'retina, refraction' ),
		array( 'Dr. S. Gupta', 'Pediatric Ophthalmologist & Cornea Specialist', 'MBBS, DOMS', '10+ years', 'Hindi, English', 'cornea, pediatric' ),
	);
	foreach ( $doctors as $i => $d ) {
		$existing = get_page_by_path( sanitize_title( $d[0] ), OBJECT, 'bs_doctor' );
		if ( ! $existing ) {
			$id = wp_insert_post(
				array(
					'post_type'    => 'bs_doctor',
					'post_status'  => 'publish',
					'post_title'   => $d[0],
					'post_content' => $d[0] . ' is a dedicated eye specialist at Netrana Eye Hospital committed to compassionate, evidence-based vision restoration in Gaya and surrounding communities.',
					'menu_order'   => $i + 1,
				)
			);
			if ( ! is_wp_error( $id ) ) {
				update_post_meta( $id, '_bs_role', $d[1] );
				update_post_meta( $id, '_bs_qualification', $d[2] );
				update_post_meta( $id, '_bs_experience', $d[3] );
				update_post_meta( $id, '_bs_languages', $d[4] );
				update_post_meta( $id, '_bs_keyword', $d[5] );
				update_post_meta( $id, '_bs_days', array( 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat' ) );
				update_post_meta( $id, '_bs_online', '1' );
				update_post_meta( $id, '_bs_fee', bs_opt( 'appt_fee', '150' ) );
				$count_doctors++;
			}
		}
	}

	// 6. Testimonials (bs_testimonial)
	$testimonials = array(
		array( 'Ramesh Kumar', 'Phaco Cataract Patient', 'Underwent stitchless Phaco cataract surgery at Netrana Eye Hospital in Gaya. The procedure was completely painless and I was back home the very same afternoon with clear, bright vision!' ),
		array( 'Sunita Devi', 'Phaco Cataract Patient', 'Received completely stitchless Phaco cataract surgery under Dr. Arvind Kumar. The nursing staff treated us with the utmost dignity, kindness, and professional excellence.' ),
		array( 'Rajesh Verma', 'Eye Examination & Spectacles', 'The most advanced eye care facility in Gaya. Modern computerised testing, accurate glasses prescription, and extremely hygienic premises. Very helpful front desk.' ),
		array( 'Manoj Tiwari', 'Cataract & Glaucoma Care', 'My elderly father was treated for mature cataract. The modern stitchless technique allowed him to regain his independence and vision within days. Truly grateful to Netrana Hospital!' ),
	);
	foreach ( $testimonials as $i => $t ) {
		$existing = get_page_by_path( sanitize_title( $t[0] ), OBJECT, 'bs_testimonial' );
		if ( ! $existing ) {
			$id = wp_insert_post(
				array(
					'post_type'    => 'bs_testimonial',
					'post_status'  => 'publish',
					'post_title'   => $t[0],
					'post_content' => $t[2],
					'menu_order'   => $i + 1,
				)
			);
			if ( ! is_wp_error( $id ) ) {
				update_post_meta( $id, '_bs_role', $t[1] );
				$count_reviews++;
			}
		}
	}

	// 7. Navigation Menus & Locations
	$locations = get_theme_mod( 'nav_menu_locations', array() );

	// Primary Menu
	$primary_menu = wp_get_nav_menu_object( 'Primary Menu' );
	if ( ! $primary_menu ) {
		$primary_menu_id = wp_create_nav_menu( 'Primary Menu' );
	} else {
		$primary_menu_id = $primary_menu->term_id;
	}

	if ( ! is_wp_error( $primary_menu_id ) ) {
		// Populate Primary Menu if empty
		$existing_items = wp_get_nav_menu_items( $primary_menu_id );
		if ( empty( $existing_items ) ) {
			$menu_items = array(
				array( 'Home', home_url( '/' ), 'custom', 0 ),
				array( 'About Us', get_permalink( $page_ids['about'] ), 'page', $page_ids['about'] ),
				array( 'Specialities', home_url( '/specialities/' ), 'custom', 0 ),
				array( 'Doctors', home_url( '/doctors/' ), 'custom', 0 ),
				array( 'Blog', get_permalink( $page_ids['blog'] ), 'page', $page_ids['blog'] ),
				array( 'Contact', get_permalink( $page_ids['contact'] ), 'page', $page_ids['contact'] ),
			);
			foreach ( $menu_items as $pos => $item ) {
				wp_update_nav_menu_item(
					$primary_menu_id,
					0,
					array(
						'menu-item-title'     => $item[0],
						'menu-item-url'       => $item[1],
						'menu-item-type'      => $item[2],
						'menu-item-object'    => 'page' === $item[2] ? 'page' : 'custom',
						'menu-item-object-id' => $item[3],
						'menu-item-status'    => 'publish',
						'menu-item-position'  => $pos + 1,
					)
				);
			}
		}
		$locations['primary'] = $primary_menu_id;
		$count_menus++;
	}

	// Footer Quick Links Menu
	$footer_quick = wp_get_nav_menu_object( 'Footer Quick Links' );
	if ( ! $footer_quick ) {
		$footer_quick_id = wp_create_nav_menu( 'Footer Quick Links' );
	} else {
		$footer_quick_id = $footer_quick->term_id;
	}
	if ( ! is_wp_error( $footer_quick_id ) ) {
		$existing_quick = wp_get_nav_menu_items( $footer_quick_id );
		if ( empty( $existing_quick ) ) {
			$quick_links = array(
				array( 'Home', home_url( '/' ) ),
				array( 'About Us', get_permalink( $page_ids['about'] ) ),
				array( 'Specialities', home_url( '/specialities/' ) ),
				array( 'Doctors', home_url( '/doctors/' ) ),
				array( 'Book Appointment', get_permalink( $page_ids['appt'] ) ),
				array( 'Contact', get_permalink( $page_ids['contact'] ) ),
			);
			foreach ( $quick_links as $pos => $ql ) {
				wp_update_nav_menu_item(
					$footer_quick_id,
					0,
					array(
						'menu-item-title'    => $ql[0],
						'menu-item-url'      => $ql[1],
						'menu-item-status'   => 'publish',
						'menu-item-position' => $pos + 1,
					)
				);
			}
		}
		$locations['footer-quick'] = $footer_quick_id;
		$count_menus++;
	}

	// Footer Services Menu
	$footer_services = wp_get_nav_menu_object( 'Footer Services' );
	if ( ! $footer_services ) {
		$footer_services_id = wp_create_nav_menu( 'Footer Services' );
	} else {
		$footer_services_id = $footer_services->term_id;
	}
	if ( ! is_wp_error( $footer_services_id ) ) {
		$existing_serv = wp_get_nav_menu_items( $footer_services_id );
		if ( empty( $existing_serv ) ) {
			$serv_posts = get_posts(
				array(
					'post_type'   => 'bs_service',
					'numberposts' => 6,
					'orderby'     => 'menu_order',
					'order'       => 'ASC',
				)
			);
			foreach ( $serv_posts as $pos => $sp ) {
				wp_update_nav_menu_item(
					$footer_services_id,
					0,
					array(
						'menu-item-title'     => get_the_title( $sp ),
						'menu-item-url'       => get_permalink( $sp ),
						'menu-item-type'      => 'post_type',
						'menu-item-object'    => 'bs_service',
						'menu-item-object-id' => $sp->ID,
						'menu-item-status'    => 'publish',
						'menu-item-position'  => $pos + 1,
					)
				);
			}
		}
		$locations['footer-services'] = $footer_services_id;
		$count_menus++;
	}

	set_theme_mod( 'nav_menu_locations', $locations );
	bs_ensure_nav_menus( true );

	// 8. Sync Theme Settings & Site Meta
	update_option( 'blogname', 'Netrana Eye Hospital' );
	update_option( 'blogdescription', 'Sight for Life' );

	$defaults = bs_default_settings();
	$current  = get_option( 'bs_settings', array() );
	if ( ! is_array( $current ) ) {
		$current = array();
	}
	update_option( 'bs_settings', wp_parse_args( $current, $defaults ) );

	flush_rewrite_rules();

	return array(
		'pages'      => $count_pages,
		'services'   => $count_services,
		'doctors'    => $count_doctors,
		'reviews'    => $count_reviews,
		'posts'      => $count_posts,
		'menus'      => $count_menus,
	);
}

/**
 * Render Demo Import Admin Page.
 */
function bs_demo_import_page() {
	$feedback = get_transient( 'bs_demo_import_feedback' );
	if ( $feedback ) {
		delete_transient( 'bs_demo_import_feedback' );
	}

	$xml_url = get_template_directory_uri() . '/demo-content.xml';
	$xml_path = get_template_directory() . '/demo-content.xml';
	$xml_exists = file_exists( $xml_path );
	?>
	<div class="wrap" style="max-width: 960px;">
		<h1><?php esc_html_e( 'Netrana Eye Hospital — Demo Content & Menu Import', 'bshealthcare' ); ?></h1>
		<p class="description" style="font-size: 14px; margin-bottom: 20px;">
			<?php esc_html_e( 'Easily populate your website with complete Netrana Eye Hospital pages, services, doctors, testimonials, blog articles, and navigation menus with one click.', 'bshealthcare' ); ?>
		</p>

		<?php if ( ! empty( $_GET['menus_fixed'] ) ) : ?>
			<div class="notice notice-success is-dismissible" style="padding: 15px; border-left-color: #10b981;">
				<h3 style="margin-top:0; color:#047857;">✅ <?php esc_html_e( 'Default Menus Successfully Created & Assigned!', 'bshealthcare' ); ?></h3>
				<p><?php esc_html_e( 'Primary Menu, Footer Quick Links, aur Footer Services successfully create hokar theme locations ke sath attach ho gaye hain.', 'bshealthcare' ); ?></p>
				<p>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" class="button button-primary"><?php esc_html_e( 'Check Live Site →', 'bshealthcare' ); ?></a>
					<a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>" class="button"><?php esc_html_e( 'View Menus (Appearance → Menus)', 'bshealthcare' ); ?></a>
				</p>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $_GET['imported'] ) && $feedback ) : ?>
			<div class="notice notice-success is-dismissible" style="padding: 15px; border-left-color: #2563eb;">
				<h3 style="margin-top:0; color:#1e40af;">🎉 <?php esc_html_e( 'Demo Data Successfully Imported!', 'bshealthcare' ); ?></h3>
				<ul style="list-style: disc; margin-left: 20px;">
					<li><strong><?php echo esc_html( $feedback['pages'] ); ?></strong> <?php esc_html_e( 'Pages verified/created (Home, About Us, Contact, Appointment, Dashboards, Blog)', 'bshealthcare' ); ?></li>
					<li><strong><?php echo esc_html( $feedback['services'] ); ?></strong> <?php esc_html_e( 'Specialities/Services added (Phaco Cataract Surgery, Glaucoma, Retina, Glass Testing, etc.)', 'bshealthcare' ); ?></li>
					<li><strong><?php echo esc_html( $feedback['doctors'] ); ?></strong> <?php esc_html_e( 'Doctor profiles added (Dr. Arvind Kumar & Team)', 'bshealthcare' ); ?></li>
					<li><strong><?php echo esc_html( $feedback['reviews'] ); ?></strong> <?php esc_html_e( 'Patient reviews/testimonials added', 'bshealthcare' ); ?></li>
					<li><strong><?php echo esc_html( $feedback['posts'] ); ?></strong> <?php esc_html_e( 'Eye care blog articles with categories added', 'bshealthcare' ); ?></li>
					<li><strong><?php echo esc_html( $feedback['menus'] ); ?></strong> <?php esc_html_e( 'Navigation menus created & assigned to Header & Footer locations', 'bshealthcare' ); ?></li>
				</ul>
				<p>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" class="button button-primary"><?php esc_html_e( 'View Live Website →', 'bshealthcare' ); ?></a>
					<a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>" class="button"><?php esc_html_e( 'Customize Menus', 'bshealthcare' ); ?></a>
				</p>
			</div>
		<?php endif; ?>

		<div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 24px; margin-top: 20px;">
			<!-- Option 1: One-Click Importer -->
			<div class="card" style="padding: 24px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; background: #fff;">
				<span style="display: inline-block; background: #eff6ff; color: #2563eb; font-weight: 700; font-size: 11px; padding: 4px 10px; border-radius: 999px; margin-bottom: 12px; text-transform: uppercase;">
					<?php esc_html_e( 'Method 1 (Recommended)', 'bshealthcare' ); ?>
				</span>
				<h2 style="margin-top: 0; font-size: 20px; font-weight: 700; color: #0f172a;">
					⚡ <?php esc_html_e( '1-Click Automatic Demo Import', 'bshealthcare' ); ?>
				</h2>
				<p style="color: #475569; font-size: 14px; line-height: 1.6;">
					<?php esc_html_e( 'Yeh option automatically aapki site par sabhi Netrana Eye Hospital ke pages, eye care specialities, doctors list, reviews, blogs aur header/footer nav menus create aur assign kar dega.', 'bshealthcare' ); ?>
				</p>

				<div style="margin-top: 24px; display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
					<form method="post" action="" onsubmit="return confirm('Kya aap Netrana Eye Hospital ka demo content aur navigation menus import karna chahte hain?');" style="margin:0;">
						<?php wp_nonce_field( 'bs_demo_import_nonce' ); ?>
						<input type="hidden" name="bs_import_demo_action" value="1">
						<button type="submit" class="button button-primary button-hero" style="background: #2563eb; border-color: #1d4ed8; font-weight: 700; box-shadow: 0 4px 6px -1px rgba(37,99,235,0.3);">
							📥 <?php esc_html_e( 'Import Demo Content & Menus', 'bshealthcare' ); ?>
						</button>
					</form>

					<form method="post" action="" style="margin:0;">
						<?php wp_nonce_field( 'bs_demo_import_nonce' ); ?>
						<input type="hidden" name="bs_fix_menus_action" value="1">
						<button type="submit" class="button button-secondary" style="font-weight: 600; padding: 6px 14px; height: auto;">
							🔗 <?php esc_html_e( 'Re-create & Assign Menus Only', 'bshealthcare' ); ?>
						</button>
					</form>
				</div>

				<div style="margin-top: 20px; padding: 12px 16px; background: #f8fafc; border-left: 4px solid #0284c7; border-radius: 6px; font-size: 13px; color: #334155;">
					<strong><?php esc_html_e( 'Note:', 'bshealthcare' ); ?></strong>
					<?php esc_html_e( 'Import karne ke baad aap kabhi bhi WordPress Admin se sabhi content aur menus ko apne hisaab se edit ya delete kar sakte hain.', 'bshealthcare' ); ?>
				</div>
			</div>

			<!-- Option 2: WordPress Standard XML Import -->
			<div class="card" style="padding: 24px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; background: #fff;">
				<span style="display: inline-block; background: #f1f5f9; color: #475569; font-weight: 700; font-size: 11px; padding: 4px 10px; border-radius: 999px; margin-bottom: 12px; text-transform: uppercase;">
					<?php esc_html_e( 'Method 2 (Standard WXR)', 'bshealthcare' ); ?>
				</span>
				<h2 style="margin-top: 0; font-size: 18px; font-weight: 700; color: #0f172a;">
					📄 <?php esc_html_e( 'WordPress XML Import File', 'bshealthcare' ); ?>
				</h2>
				<p style="color: #475569; font-size: 13px; line-height: 1.6;">
					<?php esc_html_e( 'Agar aap standard WordPress Importer tool ke through import karna chahte hain, to niche diye gaye XML file ka use karein:', 'bshealthcare' ); ?>
				</p>

				<?php if ( $xml_exists ) : ?>
					<p style="margin-top: 18px;">
						<a href="<?php echo esc_url( $xml_url ); ?>" download class="button button-secondary" style="font-weight: 600;">
							⬇️ <?php esc_html_e( 'Download demo-content.xml', 'bshealthcare' ); ?>
						</a>
					</p>
				<?php endif; ?>

				<ol style="margin-top: 15px; padding-left: 18px; font-size: 13px; color: #64748b; line-height: 1.6;">
					<li>Go to <strong>Tools → Import</strong>.</li>
					<li>Click <strong>Install / Run Importer</strong> under WordPress.</li>
					<li>Upload <code>demo-content.xml</code>.</li>
					<li>Assign authors and click <strong>Submit</strong>.</li>
				</ol>
			</div>
		</div>

		<!-- Current Content Status Table -->
		<div class="card" style="margin-top: 24px; padding: 20px; border-radius: 12px; background: #fff; border: 1px solid #e2e8f0;">
			<h3 style="margin-top:0; font-size: 16px; color: #0f172a;"><?php esc_html_e( 'Current Site Content Summary', 'bshealthcare' ); ?></h3>
			<table class="widefat striped" style="margin-top: 10px; border-radius: 6px; overflow: hidden;">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Content Type', 'bshealthcare' ); ?></th>
						<th><?php esc_html_e( 'Current Count', 'bshealthcare' ); ?></th>
						<th><?php esc_html_e( 'Demo Preset Target', 'bshealthcare' ); ?></th>
						<th><?php esc_html_e( 'Status', 'bshealthcare' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$counts = array(
						__( 'Pages (Home, About, Contact, Appt)', 'bshealthcare' ) => array( wp_count_posts( 'page' )->publish, '8 Pages' ),
						__( 'Specialities (bs_service)', 'bshealthcare' )         => array( wp_count_posts( 'bs_service' )->publish, '8 Services' ),
						__( 'Doctors (bs_doctor)', 'bshealthcare' )               => array( wp_count_posts( 'bs_doctor' )->publish, '4 Doctors' ),
						__( 'Testimonials (bs_testimonial)', 'bshealthcare' )     => array( wp_count_posts( 'bs_testimonial' )->publish, '4 Reviews' ),
						__( 'Blog Posts (post)', 'bshealthcare' )                 => array( wp_count_posts( 'post' )->publish, '3+ Posts' ),
					);
					foreach ( $counts as $label => $c ) :
						$ok = $c[0] > 0;
						?>
						<tr>
							<td><strong><?php echo esc_html( $label ); ?></strong></td>
							<td><?php echo (int) $c[0]; ?></td>
							<td><?php echo esc_html( $c[1] ); ?></td>
							<td>
								<span style="display:inline-block; padding: 2px 8px; border-radius: 999px; font-size: 11px; font-weight: 700; background: <?php echo $ok ? '#ecfdf5; color: #059669;' : '#fef2f2; color: #dc2626;'; ?>">
									<?php echo $ok ? esc_html__( 'Active & Populated', 'bshealthcare' ) : esc_html__( 'Not Populated', 'bshealthcare' ); ?>
								</span>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
	<?php
}
