<?php
/**
 * Appointment business logic (slots, creation, queries).
 *
 * @package GPHealthcare
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Times already booked for a doctor on a date (excluding cancelled).
 */
function bs_booked_times( $doctor_id, $date ) {
	$ids = get_posts(
		array(
			'post_type'   => 'bs_appointment',
			'numberposts' => -1,
			'fields'      => 'ids',
			'post_status' => 'publish',
			'meta_query'  => array( // phpcs:ignore WordPress.DB.SlowDBQuery
				array( 'key' => '_bs_doctor_id', 'value' => (int) $doctor_id ),
				array( 'key' => '_bs_date', 'value' => $date ),
				array( 'key' => '_bs_status', 'value' => array( 'cancelled' ), 'compare' => 'NOT IN' ),
			),
		)
	);
	$times = array();
	foreach ( $ids as $id ) {
		$times[] = get_post_meta( $id, '_bs_time', true );
	}
	return $times;
}

/**
 * Available slots for a doctor on a date.
 */
function bs_available_slots( $doctor_id, $date ) {
	$slots = array_map( 'trim', explode( ',', bs_opt( 'appt_slots' ) ) );
	$doc   = bs_doctor_data( $doctor_id );
	$day   = gmdate( 'D', strtotime( $date ) );
	if ( $doc['days'] && ! in_array( $day, $doc['days'], true ) ) {
		return array( 'slots' => array(), 'reason' => sprintf( 'Doctor is not available on %s.', gmdate( 'l', strtotime( $date ) ) ) );
	}
	$booked = bs_booked_times( $doctor_id, $date );
	$out    = array();
	$is_today = $date === current_time( 'Y-m-d' );
	foreach ( $slots as $s ) {
		$past = $is_today && strtotime( $date . ' ' . $s ) < current_time( 'timestamp' ); // phpcs:ignore
		$out[] = array( 'time' => $s, 'available' => ! in_array( $s, $booked, true ) && ! $past );
	}
	return array( 'slots' => $out, 'reason' => '' );
}

/**
 * Create an appointment. Returns post ID or WP_Error.
 */
function bs_create_appointment( $data ) {
	$name   = sanitize_text_field( $data['name'] ?? '' );
	$email  = sanitize_email( $data['email'] ?? '' );
	$phone  = sanitize_text_field( $data['phone'] ?? '' );
	$doctor = (int) ( $data['doctor'] ?? 0 );
	$date   = sanitize_text_field( $data['date'] ?? '' );
	$time   = sanitize_text_field( $data['time'] ?? '' );

	if ( strlen( $name ) < 2 ) {
		return new WP_Error( 'name', __( 'Please enter your full name.', 'bshealthcare' ) );
	}
	if ( ! is_email( $email ) ) {
		return new WP_Error( 'email', __( 'Please enter a valid email address.', 'bshealthcare' ) );
	}
	if ( ! preg_match( '/^[+\d][\d\s-]{7,15}$/', $phone ) ) {
		return new WP_Error( 'phone', __( 'Please enter a valid phone number.', 'bshealthcare' ) );
	}
	if ( ! $doctor || 'bs_doctor' !== get_post_type( $doctor ) ) {
		return new WP_Error( 'doctor', __( 'Please choose a doctor.', 'bshealthcare' ) );
	}
	if ( ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date ) || strtotime( $date ) < strtotime( current_time( 'Y-m-d' ) ) ) {
		return new WP_Error( 'date', __( 'Please choose a valid future date.', 'bshealthcare' ) );
	}
	$avail = bs_available_slots( $doctor, $date );
	if ( $avail['reason'] ) {
		return new WP_Error( 'date', $avail['reason'] );
	}
	$ok = false;
	foreach ( $avail['slots'] as $s ) {
		if ( $s['time'] === $time && $s['available'] ) {
			$ok = true;
		}
	}
	if ( ! $ok ) {
		return new WP_Error( 'time', __( 'That time slot is no longer available. Please pick another.', 'bshealthcare' ) );
	}

	// One active booking per patient per doctor per day.
	$dupe = get_posts(
		array(
			'post_type'   => 'bs_appointment',
			'numberposts' => 1,
			'fields'      => 'ids',
			'meta_query'  => array( // phpcs:ignore WordPress.DB.SlowDBQuery
				array( 'key' => '_bs_patient_email', 'value' => $email ),
				array( 'key' => '_bs_doctor_id', 'value' => $doctor ),
				array( 'key' => '_bs_date', 'value' => $date ),
				array( 'key' => '_bs_status', 'value' => array( 'cancelled' ), 'compare' => 'NOT IN' ),
			),
		)
	);
	if ( $dupe ) {
		return new WP_Error( 'dupe', __( 'You already have an appointment with this doctor on that day.', 'bshealthcare' ) );
	}

	$doc_fee = get_post_meta( $doctor, '_bs_fee', true );
	$status  = '1' === (string) bs_opt( 'appt_auto_confirm' ) ? 'confirmed' : 'pending';
	$id      = wp_insert_post(
		array(
			'post_type'   => 'bs_appointment',
			'post_status' => 'publish',
			'post_title'  => $name . ' — ' . $date . ' ' . $time,
		),
		true
	);
	if ( is_wp_error( $id ) ) {
		return $id;
	}
	$meta = array(
		'_bs_patient_name'  => $name,
		'_bs_patient_email' => $email,
		'_bs_patient_phone' => $phone,
		'_bs_doctor_id'     => $doctor,
		'_bs_date'          => $date,
		'_bs_time'          => $time,
		'_bs_status'        => $status,
		'_bs_last_status'   => $status,
		'_bs_fee'           => $doc_fee ? $doc_fee : bs_opt( 'appt_fee' ),
		'_bs_user_id'       => get_current_user_id(),
		'_bs_reference'     => 'BSH-' . strtoupper( wp_generate_password( 6, false ) ),
	);
	foreach ( $meta as $k => $v ) {
		update_post_meta( $id, $k, $v );
	}
	do_action( 'bs_appointment_created', $id );
	return $id;
}

/**
 * Appointment bundle.
 */
function bs_appointment_data( $id ) {
	$doctor_id = (int) get_post_meta( $id, '_bs_doctor_id', true );
	return array(
		'id'        => $id,
		'reference' => get_post_meta( $id, '_bs_reference', true ),
		'name'      => get_post_meta( $id, '_bs_patient_name', true ),
		'email'     => get_post_meta( $id, '_bs_patient_email', true ),
		'phone'     => get_post_meta( $id, '_bs_patient_phone', true ),
		'doctor_id' => $doctor_id,
		'doctor'    => $doctor_id ? get_the_title( $doctor_id ) : '',
		'date'      => get_post_meta( $id, '_bs_date', true ),
		'time'      => get_post_meta( $id, '_bs_time', true ),
		'status'    => get_post_meta( $id, '_bs_status', true ) ?: 'pending',
		'fee'       => get_post_meta( $id, '_bs_fee', true ),
		'notes'     => get_post_meta( $id, '_bs_notes', true ),
		'user_id'   => (int) get_post_meta( $id, '_bs_user_id', true ),
		'prescription' => bs_prescription_for_appointment( $id ),
	);
}

/**
 * Appointments for a patient (user id or email).
 */
function bs_patient_appointments( $user_id ) {
	$user = get_userdata( $user_id );
	$ids  = get_posts(
		array(
			'post_type'   => 'bs_appointment',
			'numberposts' => -1,
			'fields'      => 'ids',
			'meta_query'  => array( // phpcs:ignore WordPress.DB.SlowDBQuery
				'relation' => 'OR',
				array( 'key' => '_bs_user_id', 'value' => (int) $user_id ),
				array( 'key' => '_bs_patient_email', 'value' => $user ? $user->user_email : '__none__' ),
			),
		)
	);
	$list = array_map( 'bs_appointment_data', $ids );
	usort( $list, function ( $a, $b ) {
		return strcmp( $b['date'] . $b['time'], $a['date'] . $a['time'] );
	} );
	return $list;
}

/**
 * Appointments for a doctor post, optional filters.
 */
function bs_doctor_appointments( $doctor_id, $args = array() ) {
	$meta = array( array( 'key' => '_bs_doctor_id', 'value' => (int) $doctor_id ) );
	if ( ! empty( $args['date'] ) ) {
		$meta[] = array( 'key' => '_bs_date', 'value' => $args['date'] );
	}
	if ( ! empty( $args['from'] ) ) {
		$meta[] = array( 'key' => '_bs_date', 'value' => $args['from'], 'compare' => '>=' );
	}
	if ( ! empty( $args['status'] ) ) {
		$meta[] = array( 'key' => '_bs_status', 'value' => (array) $args['status'], 'compare' => 'IN' );
	}
	$ids  = get_posts(
		array(
			'post_type'   => 'bs_appointment',
			'numberposts' => -1,
			'fields'      => 'ids',
			'meta_query'  => $meta, // phpcs:ignore WordPress.DB.SlowDBQuery
		)
	);
	$list = array_map( 'bs_appointment_data', $ids );
	usort( $list, function ( $a, $b ) {
		return strcmp( $a['date'] . strtotime( $a['time'] ), $b['date'] . strtotime( $b['time'] ) );
	} );
	return $list;
}

/**
 * Update status with permission check. Returns true|WP_Error.
 */
function bs_update_appointment_status( $id, $status, $note = '' ) {
	if ( 'bs_appointment' !== get_post_type( $id ) || ! array_key_exists( $status, bs_appointment_statuses() ) ) {
		return new WP_Error( 'bad', __( 'Invalid request.', 'bshealthcare' ) );
	}
	$appt = bs_appointment_data( $id );
	$can  = current_user_can( 'manage_options' )
		|| ( bs_is_doctor_user() && bs_current_doctor_id() === $appt['doctor_id'] )
		|| ( is_user_logged_in() && $appt['user_id'] === get_current_user_id() && in_array( $status, array( 'cancelled', 'reschedule-requested' ), true ) );
	if ( ! $can ) {
		return new WP_Error( 'forbidden', __( 'You are not allowed to do that.', 'bshealthcare' ) );
	}
	$old = $appt['status'];
	update_post_meta( $id, '_bs_status', $status );
	update_post_meta( $id, '_bs_last_status', $status );
	if ( $note ) {
		update_post_meta( $id, '_bs_notes', sanitize_textarea_field( $note ) );
	}
	do_action( 'bs_appointment_status_changed', $id, $status, $old );
	return true;
}
