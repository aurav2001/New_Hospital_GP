<?php
/**
 * AJAX endpoints used by the front-end JS.
 *
 * @package BSHealthcare
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bs_ajax_check() {
	if ( ! check_ajax_referer( 'bs_nonce', 'nonce', false ) ) {
		wp_send_json_error( array( 'message' => __( 'Session expired. Please reload the page.', 'bshealthcare' ) ), 403 );
	}
}

/* ---- Doctors list for the booking modal ---- */
function bs_ajax_doctors() {
	bs_ajax_check();
	$docs = get_posts( array( 'post_type' => 'bs_doctor', 'numberposts' => -1, 'orderby' => 'menu_order title', 'order' => 'ASC' ) );
	$out  = array();
	foreach ( $docs as $d ) {
		$data  = bs_doctor_data( $d->ID );
		$out[] = array(
			'id'    => $d->ID,
			'name'  => $data['name'],
			'role'  => $data['role'],
			'fee'   => $data['fee'] ? $data['fee'] : bs_opt( 'appt_fee' ),
			'days'  => $data['days'],
			'image' => $data['image'],
		);
	}
	wp_send_json_success( $out );
}
add_action( 'wp_ajax_bs_doctors', 'bs_ajax_doctors' );
add_action( 'wp_ajax_nopriv_bs_doctors', 'bs_ajax_doctors' );

/* ---- Slots ---- */
function bs_ajax_slots() {
	bs_ajax_check();
	$doctor = (int) ( $_POST['doctor'] ?? 0 );
	$date   = sanitize_text_field( wp_unslash( $_POST['date'] ?? '' ) );
	if ( ! $doctor || ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date ) ) {
		wp_send_json_error( array( 'message' => __( 'Choose a doctor and date.', 'bshealthcare' ) ) );
	}
	wp_send_json_success( bs_available_slots( $doctor, $date ) );
}
add_action( 'wp_ajax_bs_slots', 'bs_ajax_slots' );
add_action( 'wp_ajax_nopriv_bs_slots', 'bs_ajax_slots' );

/* ---- Book ---- */
function bs_ajax_book() {
	bs_ajax_check();
	if ( '1' === (string) bs_opt( 'appt_require_login' ) && ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => __( 'Please log in to book an appointment.', 'bshealthcare' ), 'login' => true ) );
	}
	$id = bs_create_appointment( wp_unslash( $_POST ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- sanitised inside.
	if ( is_wp_error( $id ) ) {
		wp_send_json_error( array( 'message' => $id->get_error_message(), 'field' => $id->get_error_code() ) );
	}
	$a = bs_appointment_data( $id );
	wp_send_json_success(
		array(
			'message'   => 'confirmed' === $a['status'] ? __( 'Your appointment is confirmed!', 'bshealthcare' ) : __( 'Your request has been received. We will confirm shortly.', 'bshealthcare' ),
			'reference' => $a['reference'],
			'doctor'    => $a['doctor'],
			'date'      => $a['date'],
			'time'      => $a['time'],
			'status'    => $a['status'],
			'dashboard' => bs_page_url( 'templates/template-patient-dashboard.php' ),
		)
	);
}
add_action( 'wp_ajax_bs_book', 'bs_ajax_book' );
add_action( 'wp_ajax_nopriv_bs_book', 'bs_ajax_book' );

/* ---- Contact form ---- */
function bs_ajax_contact() {
	bs_ajax_check();
	if ( ! empty( $_POST['website'] ) ) { // honeypot
		wp_send_json_success( array( 'message' => __( 'Thanks!', 'bshealthcare' ) ) );
	}
	$name    = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$email   = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$phone   = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
	$subject = sanitize_text_field( wp_unslash( $_POST['subject'] ?? '' ) );
	$message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );

	if ( strlen( $name ) < 2 ) {
		wp_send_json_error( array( 'message' => __( 'Please enter your name.', 'bshealthcare' ), 'field' => 'name' ) );
	}
	if ( ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => __( 'Please enter a valid email.', 'bshealthcare' ), 'field' => 'email' ) );
	}
	if ( ! preg_match( '/^[+\d][\d\s-]{7,15}$/', $phone ) ) {
		wp_send_json_error( array( 'message' => __( 'Please enter a valid phone number.', 'bshealthcare' ), 'field' => 'phone' ) );
	}
	if ( strlen( $message ) < 10 ) {
		wp_send_json_error( array( 'message' => __( 'Message should be at least 10 characters.', 'bshealthcare' ), 'field' => 'message' ) );
	}
	bs_send_contact_email( compact( 'name', 'email', 'phone', 'subject', 'message' ) );
	wp_send_json_success( array( 'message' => __( 'Thank you! We have received your message and will get back to you within 24 hours.', 'bshealthcare' ) ) );
}
add_action( 'wp_ajax_bs_contact', 'bs_ajax_contact' );
add_action( 'wp_ajax_nopriv_bs_contact', 'bs_ajax_contact' );

/* ---- Status updates (patient cancel / reschedule, doctor confirm/complete/cancel) ---- */
function bs_ajax_status() {
	bs_ajax_check();
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => __( 'Please log in.', 'bshealthcare' ) ), 401 );
	}
	$res = bs_update_appointment_status( (int) ( $_POST['id'] ?? 0 ), sanitize_key( $_POST['status'] ?? '' ), sanitize_textarea_field( wp_unslash( $_POST['note'] ?? '' ) ) );
	if ( is_wp_error( $res ) ) {
		wp_send_json_error( array( 'message' => $res->get_error_message() ) );
	}
	wp_send_json_success( array( 'message' => __( 'Updated.', 'bshealthcare' ) ) );
}
add_action( 'wp_ajax_bs_status', 'bs_ajax_status' );

/* ---- Doctor on-duty toggle ---- */
function bs_ajax_toggle_online() {
	bs_ajax_check();
	$doc = bs_current_doctor_id();
	if ( ! $doc || ! bs_is_doctor_user() ) {
		wp_send_json_error( array( 'message' => __( 'Not allowed.', 'bshealthcare' ) ), 403 );
	}
	$new = '1' === get_post_meta( $doc, '_bs_online', true ) ? '' : '1';
	update_post_meta( $doc, '_bs_online', $new );
	wp_send_json_success( array( 'online' => '1' === $new ) );
}
add_action( 'wp_ajax_bs_toggle_online', 'bs_ajax_toggle_online' );

/* ---- Prescription ---- */
function bs_ajax_prescribe() {
	bs_ajax_check();
	if ( ! bs_is_doctor_user() ) {
		wp_send_json_error( array( 'message' => __( 'Only doctors can prescribe.', 'bshealthcare' ) ), 403 );
	}
	$id = bs_create_prescription( wp_unslash( $_POST ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- sanitised inside.
	if ( is_wp_error( $id ) ) {
		wp_send_json_error( array( 'message' => $id->get_error_message() ) );
	}
	$p = bs_prescription_data( $id );
	wp_send_json_success( array( 'message' => __( 'Prescription saved and emailed to the patient.', 'bshealthcare' ), 'url' => $p['url'] ) );
}
add_action( 'wp_ajax_bs_prescribe', 'bs_ajax_prescribe' );

/* ---- Doctor profile settings ---- */
function bs_ajax_doctor_settings() {
	bs_ajax_check();
	$doc = bs_current_doctor_id();
	if ( ! $doc || ! bs_is_doctor_user() ) {
		wp_send_json_error( array( 'message' => __( 'Not allowed.', 'bshealthcare' ) ), 403 );
	}
	$days = isset( $_POST['days'] ) ? array_map( 'sanitize_text_field', (array) wp_unslash( $_POST['days'] ) ) : array();
	update_post_meta( $doc, '_bs_days', $days );
	update_post_meta( $doc, '_bs_fee', (string) (float) ( $_POST['fee'] ?? 0 ) );
	update_post_meta( $doc, '_bs_languages', sanitize_text_field( wp_unslash( $_POST['languages'] ?? '' ) ) );
	update_post_meta( $doc, '_bs_experience', sanitize_text_field( wp_unslash( $_POST['experience'] ?? '' ) ) );
	if ( isset( $_POST['bio'] ) ) {
		wp_update_post( array( 'ID' => $doc, 'post_content' => sanitize_textarea_field( wp_unslash( $_POST['bio'] ) ) ) );
	}
	wp_send_json_success( array( 'message' => __( 'Profile updated.', 'bshealthcare' ) ) );
}
add_action( 'wp_ajax_bs_doctor_settings', 'bs_ajax_doctor_settings' );
