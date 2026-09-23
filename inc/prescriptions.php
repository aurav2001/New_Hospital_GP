<?php
/**
 * Digital prescriptions written by doctors, viewed via secure token link.
 *
 * @package BSHealthcare
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bs_prescription_for_appointment( $appointment_id ) {
	$ids = get_posts(
		array(
			'post_type'   => 'bs_prescription',
			'numberposts' => 1,
			'fields'      => 'ids',
			'meta_key'    => '_bs_appointment_id',
			'meta_value'  => (int) $appointment_id,
		)
	);
	return $ids ? bs_prescription_data( $ids[0] ) : null;
}

function bs_prescription_data( $id ) {
	$meds = get_post_meta( $id, '_bs_medications', true );
	return array(
		'id'             => $id,
		'token'          => get_post_meta( $id, '_bs_token', true ),
		'url'            => home_url( '/prescription/' . get_post_meta( $id, '_bs_token', true ) . '/' ),
		'appointment_id' => (int) get_post_meta( $id, '_bs_appointment_id', true ),
		'doctor_id'      => (int) get_post_meta( $id, '_bs_doctor_id', true ),
		'doctor'         => get_the_title( (int) get_post_meta( $id, '_bs_doctor_id', true ) ),
		'patient_name'   => get_post_meta( $id, '_bs_patient_name', true ),
		'patient_email'  => get_post_meta( $id, '_bs_patient_email', true ),
		'diagnosis'      => get_post_meta( $id, '_bs_diagnosis', true ),
		'medications'    => is_array( $meds ) ? $meds : array(),
		'notes'          => get_post_meta( $id, '_bs_notes', true ),
		'date'           => get_the_date( 'j F Y', $id ),
	);
}

function bs_prescription_by_token( $token ) {
	$ids = get_posts(
		array(
			'post_type'   => 'bs_prescription',
			'numberposts' => 1,
			'fields'      => 'ids',
			'meta_key'    => '_bs_token',
			'meta_value'  => sanitize_text_field( $token ),
		)
	);
	return $ids ? bs_prescription_data( $ids[0] ) : null;
}

/**
 * Create a prescription for an appointment (doctor portal). Returns ID or WP_Error.
 */
function bs_create_prescription( $data ) {
	$appt_id = (int) ( $data['appointment'] ?? 0 );
	if ( 'bs_appointment' !== get_post_type( $appt_id ) ) {
		return new WP_Error( 'appt', __( 'Appointment not found.', 'bshealthcare' ) );
	}
	$appt      = bs_appointment_data( $appt_id );
	$doctor_id = bs_current_doctor_id();
	if ( ! current_user_can( 'manage_options' ) && $doctor_id !== $appt['doctor_id'] ) {
		return new WP_Error( 'forbidden', __( 'You can only prescribe for your own appointments.', 'bshealthcare' ) );
	}
	$diagnosis = sanitize_textarea_field( $data['diagnosis'] ?? '' );
	if ( strlen( $diagnosis ) < 3 ) {
		return new WP_Error( 'diagnosis', __( 'Please enter a diagnosis.', 'bshealthcare' ) );
	}
	$meds = array();
	foreach ( (array) ( $data['medications'] ?? array() ) as $m ) {
		$name = sanitize_text_field( $m['name'] ?? '' );
		if ( ! $name ) {
			continue;
		}
		$meds[] = array(
			'name'         => $name,
			'dosage'       => sanitize_text_field( $m['dosage'] ?? '' ),
			'duration'     => sanitize_text_field( $m['duration'] ?? '' ),
			'instructions' => sanitize_text_field( $m['instructions'] ?? '' ),
		);
	}
	if ( ! $meds ) {
		return new WP_Error( 'meds', __( 'Add at least one medication.', 'bshealthcare' ) );
	}

	$existing = bs_prescription_for_appointment( $appt_id );
	$token    = $existing ? $existing['token'] : wp_generate_password( 32, false );
	$post     = array(
		'post_type'   => 'bs_prescription',
		'post_status' => 'publish',
		'post_title'  => $appt['name'] . ' — ' . current_time( 'Y-m-d' ),
	);
	if ( $existing ) {
		$post['ID'] = $existing['id'];
		$id         = wp_update_post( $post, true );
	} else {
		$id = wp_insert_post( $post, true );
	}
	if ( is_wp_error( $id ) ) {
		return $id;
	}
	$meta = array(
		'_bs_token'          => $token,
		'_bs_appointment_id' => $appt_id,
		'_bs_doctor_id'      => $appt['doctor_id'] ?: $doctor_id,
		'_bs_patient_name'   => $appt['name'],
		'_bs_patient_email'  => $appt['email'],
		'_bs_patient_user'   => $appt['user_id'],
		'_bs_diagnosis'      => $diagnosis,
		'_bs_medications'    => $meds,
		'_bs_notes'          => sanitize_textarea_field( $data['notes'] ?? '' ),
	);
	foreach ( $meta as $k => $v ) {
		update_post_meta( $id, $k, $v );
	}
	// Consultation done.
	update_post_meta( $appt_id, '_bs_status', 'completed' );
	update_post_meta( $appt_id, '_bs_last_status', 'completed' );

	do_action( 'bs_prescription_created', $id, $appt_id );
	return $id;
}

/**
 * Prescriptions of a patient.
 */
function bs_patient_prescriptions( $user_id ) {
	$user = get_userdata( $user_id );
	$ids  = get_posts(
		array(
			'post_type'   => 'bs_prescription',
			'numberposts' => -1,
			'fields'      => 'ids',
			'meta_query'  => array( // phpcs:ignore WordPress.DB.SlowDBQuery
				'relation' => 'OR',
				array( 'key' => '_bs_patient_user', 'value' => (int) $user_id ),
				array( 'key' => '_bs_patient_email', 'value' => $user ? $user->user_email : '__none__' ),
			),
		)
	);
	return array_map( 'bs_prescription_data', $ids );
}

function bs_doctor_prescriptions( $doctor_id ) {
	$ids = get_posts(
		array(
			'post_type'   => 'bs_prescription',
			'numberposts' => -1,
			'fields'      => 'ids',
			'meta_key'    => '_bs_doctor_id',
			'meta_value'  => (int) $doctor_id,
		)
	);
	return array_map( 'bs_prescription_data', $ids );
}

/**
 * Route /prescription/{token}/ to the secure view template.
 */
function bs_prescription_template( $template ) {
	$token = get_query_var( 'bs_prescription' );
	if ( $token ) {
		return BS_DIR . '/templates/prescription-view.php';
	}
	return $template;
}
add_filter( 'template_include', 'bs_prescription_template' );
