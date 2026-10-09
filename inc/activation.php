<?php
/**
 * On activation: create required pages, front page, menu and demo content.
 *
 * @package GPHealthcare
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

	if ( ! get_option( 'bs_installed' ) ) {
		update_option( 'show_on_front', 'page' );
		if ( ! empty( $ids['home'] ) ) {
			update_option( 'page_on_front', $ids['home'] );
		}
		if ( ! empty( $ids['blog'] ) ) {
			update_option( 'page_for_posts', $ids['blog'] );
		}

		bs_seed_demo_content();
		update_option( 'bs_installed', 1 );
	}

	bs_ensure_nav_menus();
	update_option( 'bs_theme_version', BS_VERSION );
}
add_action( 'after_switch_theme', 'bs_activate' );

/**
 * Ensures Primary Menu and Footer menus are created, populated with items,
 * and mapped to theme nav menu locations.
 *
 * @param bool $force If true, forces re-assignment of menu locations.
 */
function bs_ensure_nav_menus( $force = false ) {
	$ids       = bs_ensure_pages();
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	if ( ! is_array( $locations ) ) {
		$locations = array();
	}

	// 1. Primary Menu
	$primary_term = wp_get_nav_menu_object( 'Primary Menu' );
	if ( ! $primary_term ) {
		$primary_term = wp_get_nav_menu_object( 'primary-menu' );
	}
	if ( ! $primary_term ) {
		$created = wp_create_nav_menu( 'Primary Menu' );
		if ( ! is_wp_error( $created ) ) {
			$primary_id = (int) $created;
		} elseif ( isset( $created->error_data['menu_exists'] ) ) {
			$primary_id = (int) $created->error_data['menu_exists'];
		} else {
			$primary_id = 0;
		}
	} else {
		$primary_id = (int) $primary_term->term_id;
	}

	if ( $primary_id ) {
		$existing_items   = wp_get_nav_menu_items( $primary_id );
		$has_specialities = false;
		if ( ! empty( $existing_items ) ) {
			foreach ( $existing_items as $ei ) {
				if ( preg_match( '/special|doctor/i', (string) $ei->title ) || preg_match( '/special|doctor/i', (string) $ei->url ) ) {
					$has_specialities = true;
					break;
				}
			}
		}

		if ( empty( $existing_items ) || ! $has_specialities || $force ) {
			if ( ( $force || ! $has_specialities ) && ! empty( $existing_items ) ) {
				foreach ( $existing_items as $ei ) {
					wp_delete_post( $ei->ID, true );
				}
			}

			$items = array(
				array( 'Home', home_url( '/' ), 'custom', 0 ),
				array( 'About Us', ! empty( $ids['about'] ) ? get_permalink( $ids['about'] ) : home_url( '/about-us/' ), 'page', $ids['about'] ?? 0 ),
				array( 'Specialities', home_url( '/specialities/' ), 'custom', 0 ),
				array( 'Doctors', home_url( '/doctors/' ), 'custom', 0 ),
				array( 'Blogs', ! empty( $ids['blog'] ) ? get_permalink( $ids['blog'] ) : home_url( '/blog/' ), 'page', $ids['blog'] ?? 0 ),
				array( 'Contact', ! empty( $ids['contact'] ) ? get_permalink( $ids['contact'] ) : home_url( '/contact/' ), 'page', $ids['contact'] ?? 0 ),
			);
			foreach ( $items as $pos => $it ) {
				wp_update_nav_menu_item(
					$primary_id,
					0,
					array(
						'menu-item-title'     => $it[0],
						'menu-item-url'       => $it[1],
						'menu-item-type'      => $it[2],
						'menu-item-object'    => 'page' === $it[2] ? 'page' : 'custom',
						'menu-item-object-id' => $it[3],
						'menu-item-status'    => 'publish',
						'menu-item-position'  => $pos + 1,
					)
				);
			}
		}

		if ( empty( $locations['primary'] ) || ! wp_get_nav_menu_object( $locations['primary'] ) || $force ) {
			$locations['primary'] = $primary_id;
		}
	}

	// 2. Footer Quick Links
	$quick_term = wp_get_nav_menu_object( 'Footer Quick Links' );
	if ( ! $quick_term ) {
		$quick_term = wp_get_nav_menu_object( 'footer-quick-links' );
	}
	if ( ! $quick_term ) {
		$created = wp_create_nav_menu( 'Footer Quick Links' );
		if ( ! is_wp_error( $created ) ) {
			$quick_id = (int) $created;
		} elseif ( isset( $created->error_data['menu_exists'] ) ) {
			$quick_id = (int) $created->error_data['menu_exists'];
		} else {
			$quick_id = 0;
		}
	} else {
		$quick_id = (int) $quick_term->term_id;
	}

	if ( $quick_id ) {
		$existing_quick = wp_get_nav_menu_items( $quick_id );
		if ( empty( $existing_quick ) ) {
			$qitems = array(
				array( 'Home', home_url( '/' ) ),
				array( 'About Us', ! empty( $ids['about'] ) ? get_permalink( $ids['about'] ) : home_url( '/about-us/' ) ),
				array( 'Specialities', home_url( '/specialities/' ) ),
				array( 'Our Doctors', home_url( '/doctors/' ) ),
				array( 'Book Appointment', ! empty( $ids['appt'] ) ? get_permalink( $ids['appt'] ) : home_url( '/appointment/' ) ),
				array( 'Contact', ! empty( $ids['contact'] ) ? get_permalink( $ids['contact'] ) : home_url( '/contact/' ) ),
			);
			foreach ( $qitems as $pos => $it ) {
				wp_update_nav_menu_item(
					$quick_id,
					0,
					array(
						'menu-item-title'    => $it[0],
						'menu-item-url'      => $it[1],
						'menu-item-status'   => 'publish',
						'menu-item-position' => $pos + 1,
					)
				);
			}
		}

		if ( empty( $locations['footer-quick'] ) || ! wp_get_nav_menu_object( $locations['footer-quick'] ) || $force ) {
			$locations['footer-quick'] = $quick_id;
		}
	}

	// 3. Footer Services
	$serv_term = wp_get_nav_menu_object( 'Footer Services' );
	if ( ! $serv_term ) {
		$serv_term = wp_get_nav_menu_object( 'footer-services' );
	}
	if ( ! $serv_term ) {
		$created = wp_create_nav_menu( 'Footer Services' );
		if ( ! is_wp_error( $created ) ) {
			$serv_id = (int) $created;
		} elseif ( isset( $created->error_data['menu_exists'] ) ) {
			$serv_id = (int) $created->error_data['menu_exists'];
		} else {
			$serv_id = 0;
		}
	} else {
		$serv_id = (int) $serv_term->term_id;
	}

	if ( $serv_id ) {
		$existing_serv = wp_get_nav_menu_items( $serv_id );
		if ( empty( $existing_serv ) ) {
			$serv_posts = get_posts(
				array(
					'post_type'   => 'bs_service',
					'numberposts' => 6,
					'orderby'     => 'menu_order',
					'order'       => 'ASC',
				)
			);
			if ( ! empty( $serv_posts ) ) {
				foreach ( $serv_posts as $pos => $sp ) {
					wp_update_nav_menu_item(
						$serv_id,
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
			} else {
				$default_services = array(
					'Cataract & Phaco Surgery',
					'LASIK & Refractive Care',
					'Retina & Vitreous Services',
					'Glaucoma Diagnosis & Care',
					'Pediatric Ophthalmology',
					'Cornea & External Eye Care',
				);
				foreach ( $default_services as $pos => $ds_name ) {
					wp_update_nav_menu_item(
						$serv_id,
						0,
						array(
							'menu-item-title'    => $ds_name,
							'menu-item-url'      => home_url( '/specialities/' ),
							'menu-item-status'   => 'publish',
							'menu-item-position' => $pos + 1,
						)
					);
				}
			}
		}

		if ( empty( $locations['footer-services'] ) || ! wp_get_nav_menu_object( $locations['footer-services'] ) || $force ) {
			$locations['footer-services'] = $serv_id;
		}
	}

	set_theme_mod( 'nav_menu_locations', $locations );
}

/**
 * Auto-heal nav menu mapping in admin if missing or unassigned.
 */
add_action( 'admin_init', function () {
	if ( ! current_user_can( 'manage_options' ) || wp_doing_ajax() ) {
		return;
	}
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	if ( empty( $locations['primary'] ) || ! wp_get_nav_menu_object( $locations['primary'] ) ) {
		bs_ensure_nav_menus();
	}
}, 25 );

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

	// Update site title and tagline if default or empty.
	$curr_title = get_option( 'blogname' );
	if ( ! $curr_title || 'GP Healthcare Hospital' === $curr_title || 'Just another WordPress site' === $curr_title ) {
		update_option( 'blogname', 'Netrana Eye Hospital' );
	}
	$curr_tagline = get_option( 'blogdescription' );
	if ( ! $curr_tagline || 'World-Class Eye Care' === $curr_tagline || 'Just another WordPress site' === $curr_tagline ) {
		update_option( 'blogdescription', 'Sight for Life' );
	}

	// Sync theme settings with Netrana Eye Hospital requirements.
	$current_opts = get_option( 'bs_settings', array() );
	if ( ! is_array( $current_opts ) ) {
		$current_opts = array();
	}
	$defaults     = bs_default_settings();
	$updated_opts = wp_parse_args( $current_opts, $defaults );

	if ( empty( $current_opts['phone'] ) || '+91 98765 43210' === $current_opts['phone'] ) {
		$updated_opts['phone'] = $defaults['phone'];
	}
	if ( empty( $current_opts['email'] ) || false !== strpos( (string) $current_opts['email'], 'example.com' ) ) {
		$updated_opts['email'] = $defaults['email'];
	}
	if ( empty( $current_opts['address'] ) || false !== strpos( (string) $current_opts['address'], '123 Hospital Road' ) ) {
		$updated_opts['address'] = $defaults['address'];
	}
	if ( empty( $current_opts['whatsapp_number'] ) ) {
		$updated_opts['whatsapp_number'] = $defaults['whatsapp_number'];
	}
	if ( empty( $current_opts['map_embed'] ) ) {
		$updated_opts['map_embed'] = $defaults['map_embed'];
	}
	$updated_opts['map_link']           = $defaults['map_link'];
	$updated_opts['site_tagline']       = $defaults['site_tagline'];
	$updated_opts['hours_opd']          = $defaults['hours_opd'];
	$updated_opts['hours_inpatient']    = $defaults['hours_inpatient'];
	$updated_opts['footer_description'] = $defaults['footer_description'];
	$updated_opts['footer_copyright']   = $defaults['footer_copyright'];
	$updated_opts['stats_items']        = $defaults['stats_items'];
	$updated_opts['hero_eyebrow']       = $defaults['hero_eyebrow'];
	$updated_opts['hero_title']         = $defaults['hero_title'];
	$updated_opts['hero_subtitle']      = $defaults['hero_subtitle'];
	$updated_opts['hero_highlights']    = $defaults['hero_highlights'];
	$updated_opts['why_headline']       = $defaults['why_headline'];
	$updated_opts['why_subtitle']       = $defaults['why_subtitle'];
	$updated_opts['why_items']          = $defaults['why_items'];
	$updated_opts['gallery_headline']   = $defaults['gallery_headline'];
	$updated_opts['gallery_subtitle']   = $defaults['gallery_subtitle'];
	$updated_opts['about_headline']     = $defaults['about_headline'];
	$updated_opts['about_text']         = $defaults['about_text'];
	$updated_opts['appt_notify_email']  = $defaults['appt_notify_email'];
	update_option( 'bs_settings', $updated_opts );

	bs_ensure_nav_menus();
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
		$id = wp_insert_post(
			array(
				'post_type'    => 'bs_service',
				'post_status'  => 'publish',
				'post_title'   => $s[0],
				'post_excerpt' => $s[1],
				'post_content' => '<p>' . $s[1] . ' At Netrana Eye Hospital in Gaya, our ophthalmology team ensures the best treatment method is selected for your vision needs, backed by modern technology and compassionate care.</p>',
				'menu_order'   => $i,
			)
		);
		update_post_meta( $id, '_bs_hero_sub', $s[2] );
		update_post_meta( $id, '_bs_tagline', 'Sight for Life' );
		update_post_meta( $id, '_bs_keyword', $s[3] );
		update_post_meta( $id, '_bs_scope_title', 'Our Techniques' );
		update_post_meta( $id, '_bs_scope_points', $s[4] );
	}

	$doctors = array(
		array( 'Dr. Arvind Kumar', 'Director & Chief Eye Care Specialist', 'MBBS, MS (Ophthalmology)', '17+ years', 'Hindi, English', 'cataract, refraction' ),
		array( 'Dr. A. Sharma', 'Senior Consultant & Cataract Surgeon', 'MBBS, MS (Ophthalmology), FIJR', '15+ years', 'Hindi, English', 'cataract, glaucoma' ),
		array( 'Dr. R. Verma', 'Consultant Ophthalmologist & Retina Specialist', 'MBBS, MS (Ophthalmology)', '12+ years', 'Hindi, English', 'retina, refraction' ),
		array( 'Dr. S. Gupta', 'Pediatric Ophthalmologist & Cornea Specialist', 'MBBS, DOMS', '10+ years', 'Hindi, English', 'cornea, pediatric' ),
	);
	foreach ( $doctors as $i => $d ) {
		$id = wp_insert_post( array( 'post_type' => 'bs_doctor', 'post_status' => 'publish', 'post_title' => $d[0], 'post_content' => $d[0] . ' is a dedicated eye specialist at Netrana Eye Hospital committed to compassionate, evidence-based vision restoration.', 'menu_order' => $i ) );
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
		array( 'Ramesh Kumar', 'Phaco Cataract Patient', 'Underwent stitchless Phaco cataract surgery at Netrana Eye Hospital in Gaya. The procedure was completely painless and I was back home the very same afternoon with clear, bright vision!' ),
		array( 'Sunita Devi', 'Phaco Cataract Patient', 'Received completely stitchless Phaco cataract surgery under Dr. Arvind Kumar. The nursing staff treated us with the utmost dignity, kindness, and professional excellence.' ),
		array( 'Rajesh Verma', 'Eye Examination & Spectacles', 'The most advanced eye care facility in Gaya. Modern computerised testing, accurate glasses prescription, and extremely hygienic premises. Very helpful front desk.' ),
		array( 'Manoj Tiwari', 'Cataract & Glaucoma Care', 'My elderly father was treated for mature cataract. The modern stitchless technique allowed him to regain his independence and vision within days. Truly grateful to Netrana Hospital!' ),
	);
	foreach ( $testimonials as $i => $t ) {
		$id = wp_insert_post( array( 'post_type' => 'bs_testimonial', 'post_status' => 'publish', 'post_title' => $t[0], 'post_content' => $t[2], 'menu_order' => $i ) );
		update_post_meta( $id, '_bs_role', $t[1] );
	}
}
