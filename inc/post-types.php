<?php
/**
 * Custom post types: doctors, services, testimonials, appointments, prescriptions.
 *
 * @package BSHealthcare
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bs_register_post_types() {
	register_post_type(
		'bs_doctor',
		array(
			'labels'       => array(
				'name'          => __( 'Doctors', 'bshealthcare' ),
				'singular_name' => __( 'Doctor', 'bshealthcare' ),
				'add_new_item'  => __( 'Add New Doctor', 'bshealthcare' ),
				'edit_item'     => __( 'Edit Doctor', 'bshealthcare' ),
			),
			'public'       => true,
			'has_archive'  => true,
			'rewrite'      => array( 'slug' => 'doctors', 'with_front' => false ),
			'menu_icon'    => 'dashicons-groups',
			'menu_position' => 21,
			'supports'     => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
			'show_in_rest' => true,
		)
	);

	register_post_type(
		'bs_service',
		array(
			'labels'       => array(
				'name'          => __( 'Specialities', 'bshealthcare' ),
				'singular_name' => __( 'Speciality', 'bshealthcare' ),
				'add_new_item'  => __( 'Add New Speciality', 'bshealthcare' ),
				'edit_item'     => __( 'Edit Speciality', 'bshealthcare' ),
			),
			'public'       => true,
			'has_archive'  => true,
			'rewrite'      => array( 'slug' => 'specialities', 'with_front' => false ),
			'menu_icon'    => 'dashicons-visibility',
			'menu_position' => 22,
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
			'show_in_rest' => true,
		)
	);

	register_post_type(
		'bs_testimonial',
		array(
			'labels'       => array(
				'name'          => __( 'Testimonials', 'bshealthcare' ),
				'singular_name' => __( 'Testimonial', 'bshealthcare' ),
				'add_new_item'  => __( 'Add New Testimonial', 'bshealthcare' ),
			),
			'public'       => false,
			'show_ui'      => true,
			'menu_icon'    => 'dashicons-format-quote',
			'menu_position' => 23,
			'supports'     => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
		)
	);

	register_post_type(
		'bs_appointment',
		array(
			'labels'       => array(
				'name'          => __( 'Appointments', 'bshealthcare' ),
				'singular_name' => __( 'Appointment', 'bshealthcare' ),
				'add_new_item'  => __( 'Add Appointment', 'bshealthcare' ),
				'edit_item'     => __( 'Appointment', 'bshealthcare' ),
			),
			'public'       => false,
			'show_ui'      => true,
			'menu_icon'    => 'dashicons-calendar-alt',
			'menu_position' => 24,
			'supports'     => array( 'title' ),
			'capability_type' => 'post',
			'map_meta_cap' => true,
		)
	);

	register_post_type(
		'bs_prescription',
		array(
			'labels'       => array(
				'name'          => __( 'Prescriptions', 'bshealthcare' ),
				'singular_name' => __( 'Prescription', 'bshealthcare' ),
			),
			'public'       => false,
			'show_ui'      => true,
			'menu_icon'    => 'dashicons-clipboard',
			'menu_position' => 25,
			'supports'     => array( 'title' ),
		)
	);

	// Secure prescription view: /prescription/{token}/
	add_rewrite_rule( '^prescription/([A-Za-z0-9]+)/?$', 'index.php?bs_prescription=$matches[1]', 'top' );
}
add_action( 'init', 'bs_register_post_types' );

add_filter( 'query_vars', function ( $vars ) {
	$vars[] = 'bs_prescription';
	return $vars;
} );

/**
 * Services archive shows every speciality in menu order; doctors too.
 */
function bs_archive_queries( $q ) {
	if ( is_admin() || ! $q->is_main_query() ) {
		return;
	}
	if ( $q->is_post_type_archive( 'bs_service' ) || $q->is_post_type_archive( 'bs_doctor' ) ) {
		$q->set( 'posts_per_page', -1 );
		$q->set( 'orderby', 'menu_order title' );
		$q->set( 'order', 'ASC' );
	}
}
add_action( 'pre_get_posts', 'bs_archive_queries' );
