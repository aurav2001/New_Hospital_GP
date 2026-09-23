<?php
/**
 * Front-end login / registration for patients (and doctors).
 *
 * @package BSHealthcare
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Where a user should land after login.
 */
function bs_dashboard_url_for_user( $user ) {
	if ( user_can( $user, 'manage_options' ) ) {
		return admin_url();
	}
	if ( user_can( $user, 'bs_doctor' ) ) {
		return bs_page_url( 'templates/template-doctor-dashboard.php' );
	}
	return bs_page_url( 'templates/template-patient-dashboard.php' );
}

/**
 * Handle POSTed login/register forms from the login template.
 */
function bs_handle_auth_forms() {
	if ( empty( $_POST['bs_auth_action'] ) ) {
		return;
	}
	$action = sanitize_key( $_POST['bs_auth_action'] );
	if ( ! isset( $_POST['bs_auth_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['bs_auth_nonce'] ), 'bs_auth' ) ) {
		bs_auth_redirect( 'error', __( 'Security check failed. Please try again.', 'bshealthcare' ) );
	}
	$redirect = ! empty( $_POST['redirect_to'] ) ? esc_url_raw( wp_unslash( $_POST['redirect_to'] ) ) : '';

	if ( 'login' === $action ) {
		$creds = array(
			'user_login'    => sanitize_text_field( wp_unslash( $_POST['email'] ?? '' ) ),
			'user_password' => (string) ( $_POST['password'] ?? '' ), // phpcs:ignore
			'remember'      => ! empty( $_POST['remember'] ),
		);
		$user  = wp_signon( $creds, is_ssl() );
		if ( is_wp_error( $user ) ) {
			bs_auth_redirect( 'error', __( 'Invalid email or password.', 'bshealthcare' ), 'login' );
		}
		wp_safe_redirect( $redirect ? $redirect : bs_dashboard_url_for_user( $user ) );
		exit;
	}

	if ( 'register' === $action ) {
		$name  = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
		$email = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
		$phone = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
		$pass  = (string) ( $_POST['password'] ?? '' ); // phpcs:ignore
		if ( strlen( $name ) < 2 ) {
			bs_auth_redirect( 'error', __( 'Please enter your name.', 'bshealthcare' ), 'register' );
		}
		if ( ! is_email( $email ) ) {
			bs_auth_redirect( 'error', __( 'Please enter a valid email.', 'bshealthcare' ), 'register' );
		}
		if ( email_exists( $email ) ) {
			bs_auth_redirect( 'error', __( 'An account with this email already exists. Please log in.', 'bshealthcare' ), 'login' );
		}
		if ( strlen( $pass ) < 6 ) {
			bs_auth_redirect( 'error', __( 'Password must be at least 6 characters.', 'bshealthcare' ), 'register' );
		}
		$user_id = wp_insert_user(
			array(
				'user_login'   => $email,
				'user_email'   => $email,
				'user_pass'    => $pass,
				'display_name' => $name,
				'first_name'   => $name,
				'role'         => 'bs_patient',
			)
		);
		if ( is_wp_error( $user_id ) ) {
			bs_auth_redirect( 'error', $user_id->get_error_message(), 'register' );
		}
		update_user_meta( $user_id, 'bs_phone', $phone );
		wp_set_current_user( $user_id );
		wp_set_auth_cookie( $user_id, true, is_ssl() );
		wp_safe_redirect( $redirect ? $redirect : bs_page_url( 'templates/template-patient-dashboard.php' ) );
		exit;
	}
}
add_action( 'template_redirect', 'bs_handle_auth_forms', 1 );

function bs_auth_redirect( $type, $msg, $tab = 'login' ) {
	wp_safe_redirect( add_query_arg( array( 'bs_msg' => rawurlencode( $msg ), 'bs_type' => $type, 'tab' => $tab ), bs_page_url( 'templates/template-login.php' ) ) );
	exit;
}

/**
 * Logout link that returns to the home page.
 */
function bs_logout_url() {
	return wp_logout_url( home_url( '/' ) );
}

/**
 * Doctors/patients logging in through wp-login.php also land on their dashboards.
 */
add_filter( 'login_redirect', function ( $redirect, $requested, $user ) {
	if ( $user instanceof WP_User && ! user_can( $user, 'edit_posts' ) ) {
		return bs_dashboard_url_for_user( $user );
	}
	return $redirect;
}, 10, 3 );
