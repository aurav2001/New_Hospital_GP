<?php
/**
 * Patient and Doctor roles.
 *
 * @package BSHealthcare
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bs_register_roles() {
	if ( ! get_role( 'bs_patient' ) ) {
		add_role( 'bs_patient', __( 'Patient', 'bshealthcare' ), array( 'read' => true ) );
	}
	if ( ! get_role( 'bs_doctor' ) ) {
		add_role(
			'bs_doctor',
			__( 'Doctor', 'bshealthcare' ),
			array(
				'read'       => true,
				'bs_doctor' => true, // capability used by the doctor portal.
			)
		);
	}
	$admin = get_role( 'administrator' );
	if ( $admin && ! $admin->has_cap( 'bs_doctor' ) ) {
		$admin->add_cap( 'bs_doctor' );
	}
}
add_action( 'after_switch_theme', 'bs_register_roles' );
add_action( 'init', 'bs_register_roles', 5 );

/**
 * Keep patients and doctors out of wp-admin; send them to their dashboards.
 */
function bs_block_admin_for_frontend_roles() {
	if ( wp_doing_ajax() || ! is_user_logged_in() || current_user_can( 'edit_posts' ) ) {
		return;
	}
	if ( current_user_can( 'bs_doctor' ) ) {
		wp_safe_redirect( bs_page_url( 'templates/template-doctor-dashboard.php' ) );
		exit;
	}
	wp_safe_redirect( bs_page_url( 'templates/template-patient-dashboard.php' ) );
	exit;
}
add_action( 'admin_init', 'bs_block_admin_for_frontend_roles' );

add_filter( 'show_admin_bar', function ( $show ) {
	return current_user_can( 'edit_posts' ) ? $show : false;
} );

/**
 * Extra phone field on user profiles.
 */
function bs_user_phone_field( $user ) {
	?>
	<h2><?php esc_html_e( 'Patient details', 'bshealthcare' ); ?></h2>
	<table class="form-table">
		<tr>
			<th><label for="bs_phone"><?php esc_html_e( 'Phone', 'bshealthcare' ); ?></label></th>
			<td><input type="text" name="bs_phone" id="bs_phone" class="regular-text" value="<?php echo esc_attr( get_user_meta( $user->ID, 'bs_phone', true ) ); ?>"></td>
		</tr>
	</table>
	<?php
}
add_action( 'show_user_profile', 'bs_user_phone_field' );
add_action( 'edit_user_profile', 'bs_user_phone_field' );

function bs_save_user_phone( $user_id ) {
	if ( current_user_can( 'edit_user', $user_id ) && isset( $_POST['bs_phone'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		update_user_meta( $user_id, 'bs_phone', sanitize_text_field( wp_unslash( $_POST['bs_phone'] ) ) ); // phpcs:ignore
	}
}
add_action( 'personal_options_update', 'bs_save_user_phone' );
add_action( 'edit_user_profile_update', 'bs_save_user_phone' );
