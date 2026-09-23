<?php
/**
 * WP admin dashboard widget with hospital stats.
 *
 * @package BSHealthcare
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bs_dashboard_widget() {
	wp_add_dashboard_widget( 'bs_stats', __( 'Hospital overview', 'bshealthcare' ), 'bs_render_dashboard_widget' );
}
add_action( 'wp_dashboard_setup', 'bs_dashboard_widget' );

function bs_count_appointments( $meta = array() ) {
	return count(
		get_posts(
			array(
				'post_type'   => 'bs_appointment',
				'numberposts' => -1,
				'fields'      => 'ids',
				'meta_query'  => $meta, // phpcs:ignore WordPress.DB.SlowDBQuery
			)
		)
	);
}

function bs_render_dashboard_widget() {
	$today = current_time( 'Y-m-d' );
	$cards = array(
		array( 'Today\'s appointments', bs_count_appointments( array( array( 'key' => '_bs_date', 'value' => $today ), array( 'key' => '_bs_status', 'value' => 'cancelled', 'compare' => '!=' ) ) ), 'admin.php?post_type=bs_appointment' ),
		array( 'Pending confirmations', bs_count_appointments( array( array( 'key' => '_bs_status', 'value' => 'pending' ) ) ), 'edit.php?post_type=bs_appointment&bs_status=pending' ),
		array( 'Reschedule requests', bs_count_appointments( array( array( 'key' => '_bs_status', 'value' => 'reschedule-requested' ) ) ), 'edit.php?post_type=bs_appointment&bs_status=reschedule-requested' ),
		array( 'Doctors', wp_count_posts( 'bs_doctor' )->publish, 'edit.php?post_type=bs_doctor' ),
		array( 'Patients', count( get_users( array( 'role' => 'bs_patient', 'fields' => 'ID' ) ) ), 'users.php?role=bs_patient' ),
		array( 'Prescriptions', wp_count_posts( 'bs_prescription' )->publish, 'edit.php?post_type=bs_prescription' ),
	);
	echo '<div class="bs-stat-grid">';
	foreach ( $cards as $c ) {
		echo '<a class="bs-stat" href="' . esc_url( admin_url( $c[2] ) ) . '"><strong>' . esc_html( $c[1] ) . '</strong><span>' . esc_html( $c[0] ) . '</span></a>';
	}
	echo '</div>';

	$upcoming = get_posts(
		array(
			'post_type'   => 'bs_appointment',
			'numberposts' => 6,
			'meta_key'    => '_bs_date',
			'orderby'     => 'meta_value',
			'order'       => 'ASC',
			'meta_query'  => array( array( 'key' => '_bs_date', 'value' => $today, 'compare' => '>=' ), array( 'key' => '_bs_status', 'value' => array( 'pending', 'confirmed', 'reschedule-requested' ), 'compare' => 'IN' ) ), // phpcs:ignore
		)
	);
	if ( $upcoming ) {
		echo '<table class="widefat striped" style="margin-top:12px"><thead><tr><th>Patient</th><th>Doctor</th><th>When</th><th>Status</th></tr></thead><tbody>';
		foreach ( $upcoming as $p ) {
			$a = bs_appointment_data( $p->ID );
			echo '<tr><td><a href="' . esc_url( get_edit_post_link( $p->ID ) ) . '">' . esc_html( $a['name'] ) . '</a></td><td>' . esc_html( $a['doctor'] ) . '</td><td>' . esc_html( $a['date'] . ' ' . $a['time'] ) . '</td><td><span class="bs-status bs-status-' . esc_attr( $a['status'] ) . '">' . esc_html( ucfirst( $a['status'] ) ) . '</span></td></tr>';
		}
		echo '</tbody></table>';
	}
}
