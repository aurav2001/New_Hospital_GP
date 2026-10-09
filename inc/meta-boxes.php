<?php
/**
 * Meta boxes for doctors, specialities, testimonials and appointments.
 *
 * @package GPHealthcare
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ---------- Field definitions ---------- */

function bs_doctor_fields() {
	return array(
		'_bs_role'          => array( 'label' => 'Speciality / Role', 'type' => 'text', 'placeholder' => 'Cataract & Refractive Surgeon' ),
		'_bs_qualification' => array( 'label' => 'Qualification', 'type' => 'text', 'placeholder' => 'MBBS, MS (Ophthalmology)' ),
		'_bs_experience'    => array( 'label' => 'Experience', 'type' => 'text', 'placeholder' => '12+ years' ),
		'_bs_languages'     => array( 'label' => 'Languages', 'type' => 'text', 'placeholder' => 'Hindi, English' ),
		'_bs_email'         => array( 'label' => 'Notification email', 'type' => 'email', 'desc' => 'New bookings for this doctor are emailed here.' ),
		'_bs_fee'           => array( 'label' => 'Consultation fee (₹)', 'type' => 'number' ),
		'_bs_days'          => array( 'label' => 'Available days', 'type' => 'days' ),
		'_bs_online'        => array( 'label' => 'Currently available (on duty)', 'type' => 'checkbox' ),
		'_bs_user_id'       => array( 'label' => 'Linked WordPress user (Doctor role)', 'type' => 'user', 'desc' => 'This user can log in to the doctor portal and manage this doctor\'s appointments & prescriptions.' ),
		'_bs_keyword'       => array( 'label' => 'Speciality keywords', 'type' => 'text', 'placeholder' => 'cataract, retina', 'desc' => 'Used to list this doctor on matching speciality pages.' ),
	);
}

function bs_service_fields() {
	return array(
		'_bs_tagline'      => array( 'label' => 'Tagline (eyebrow)', 'type' => 'text', 'placeholder' => 'Expert care for clear vision' ),
		'_bs_hero_sub'     => array( 'label' => 'Hero subtitle', 'type' => 'text', 'placeholder' => 'Phaco & SICS methods' ),
		'_bs_scope_title'  => array( 'label' => 'Techniques section title', 'type' => 'text', 'placeholder' => 'Our Techniques' ),
		'_bs_scope_points' => array( 'label' => 'Techniques (one per line: Title|Description)', 'type' => 'textarea' ),
		'_bs_scope_image'  => array( 'label' => 'Techniques image', 'type' => 'image' ),
		'_bs_keyword'      => array( 'label' => 'Doctor keyword', 'type' => 'text', 'placeholder' => 'cataract', 'desc' => 'Doctors whose keywords/role contain this word are shown as experts.' ),
	);
}

function bs_testimonial_fields() {
	return array(
		'_bs_role'  => array( 'label' => 'Patient role / treatment', 'type' => 'text', 'placeholder' => 'Cataract Surgery Patient' ),
		'_bs_video' => array( 'label' => 'Video URL (optional, mp4)', 'type' => 'text' ),
	);
}

function bs_appointment_fields() {
	return array(
		'_bs_patient_name'  => array( 'label' => 'Patient name', 'type' => 'text' ),
		'_bs_patient_email' => array( 'label' => 'Patient email', 'type' => 'email' ),
		'_bs_patient_phone' => array( 'label' => 'Patient phone', 'type' => 'text' ),
		'_bs_doctor_id'     => array( 'label' => 'Doctor', 'type' => 'doctor' ),
		'_bs_date'          => array( 'label' => 'Date', 'type' => 'date' ),
		'_bs_time'          => array( 'label' => 'Time', 'type' => 'text', 'placeholder' => '10:00 AM' ),
		'_bs_status'        => array( 'label' => 'Status', 'type' => 'status' ),
		'_bs_fee'           => array( 'label' => 'Fee (₹)', 'type' => 'number' ),
		'_bs_notes'         => array( 'label' => 'Notes / reschedule request', 'type' => 'textarea' ),
		'_bs_user_id'       => array( 'label' => 'Patient user ID', 'type' => 'number', 'desc' => 'WordPress user who booked (0 for guest).' ),
	);
}

function bs_appointment_statuses() {
	return array(
		'pending'              => __( 'Pending', 'bshealthcare' ),
		'confirmed'            => __( 'Confirmed', 'bshealthcare' ),
		'completed'            => __( 'Completed', 'bshealthcare' ),
		'cancelled'            => __( 'Cancelled', 'bshealthcare' ),
		'reschedule-requested' => __( 'Reschedule requested', 'bshealthcare' ),
	);
}

/* ---------- Registration ---------- */

function bs_add_meta_boxes() {
	add_meta_box( 'bs_doctor_details', __( 'Doctor details', 'bshealthcare' ), 'bs_render_meta_box', 'bs_doctor', 'normal', 'high', array( 'fields' => 'bs_doctor_fields' ) );
	add_meta_box( 'bs_service_details', __( 'Speciality page details', 'bshealthcare' ), 'bs_render_meta_box', 'bs_service', 'normal', 'high', array( 'fields' => 'bs_service_fields' ) );
	add_meta_box( 'bs_testimonial_details', __( 'Testimonial details', 'bshealthcare' ), 'bs_render_meta_box', 'bs_testimonial', 'normal', 'high', array( 'fields' => 'bs_testimonial_fields' ) );
	add_meta_box( 'bs_appointment_details', __( 'Appointment details', 'bshealthcare' ), 'bs_render_meta_box', 'bs_appointment', 'normal', 'high', array( 'fields' => 'bs_appointment_fields' ) );
	add_meta_box( 'bs_prescription_details', __( 'Prescription', 'bshealthcare' ), 'bs_render_prescription_box', 'bs_prescription', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'bs_add_meta_boxes' );

function bs_render_meta_box( $post, $box ) {
	$fields = call_user_func( $box['args']['fields'] );
	wp_nonce_field( 'bs_meta_save', 'bs_meta_nonce' );
	echo '<div class="bs-meta">';
	foreach ( $fields as $key => $f ) {
		$val = get_post_meta( $post->ID, $key, true );
		echo '<div class="bs-meta-row"><label for="' . esc_attr( $key ) . '">' . esc_html( $f['label'] ) . '</label><div>';
		bs_render_field_input( $key, $f, $val );
		if ( ! empty( $f['desc'] ) ) {
			echo '<p class="description">' . esc_html( $f['desc'] ) . '</p>';
		}
		echo '</div></div>';
	}
	echo '</div>';
}

function bs_render_field_input( $key, $f, $val ) {
	$ph = isset( $f['placeholder'] ) ? $f['placeholder'] : '';
	switch ( $f['type'] ) {
		case 'textarea':
			echo '<textarea name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" rows="5" class="large-text" placeholder="' . esc_attr( $ph ) . '">' . esc_textarea( $val ) . '</textarea>';
			break;
		case 'checkbox':
			echo '<label><input type="checkbox" name="' . esc_attr( $key ) . '" value="1" ' . checked( '1', $val, false ) . '> ' . esc_html__( 'Yes', 'bshealthcare' ) . '</label>';
			break;
		case 'days':
			$days = array( 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun' );
			$val  = is_array( $val ) ? $val : $days;
			foreach ( $days as $d ) {
				echo '<label style="margin-right:12px"><input type="checkbox" name="' . esc_attr( $key ) . '[]" value="' . esc_attr( $d ) . '" ' . checked( in_array( $d, $val, true ), true, false ) . '> ' . esc_html( $d ) . '</label>';
			}
			break;
		case 'user':
			$users = get_users( array( 'role__in' => array( 'bs_doctor', 'administrator' ), 'orderby' => 'display_name' ) );
			echo '<select name="' . esc_attr( $key ) . '"><option value="">' . esc_html__( '— Not linked —', 'bshealthcare' ) . '</option>';
			foreach ( $users as $u ) {
				echo '<option value="' . esc_attr( $u->ID ) . '" ' . selected( (int) $val, $u->ID, false ) . '>' . esc_html( $u->display_name . ' (' . $u->user_email . ')' ) . '</option>';
			}
			echo '</select>';
			break;
		case 'doctor':
			$docs = get_posts( array( 'post_type' => 'bs_doctor', 'numberposts' => -1, 'orderby' => 'title', 'order' => 'ASC' ) );
			echo '<select name="' . esc_attr( $key ) . '"><option value="">' . esc_html__( '— Select doctor —', 'bshealthcare' ) . '</option>';
			foreach ( $docs as $d ) {
				echo '<option value="' . esc_attr( $d->ID ) . '" ' . selected( (int) $val, $d->ID, false ) . '>' . esc_html( $d->post_title ) . '</option>';
			}
			echo '</select>';
			break;
		case 'status':
			echo '<select name="' . esc_attr( $key ) . '">';
			foreach ( bs_appointment_statuses() as $k => $l ) {
				echo '<option value="' . esc_attr( $k ) . '" ' . selected( $val ?: 'pending', $k, false ) . '>' . esc_html( $l ) . '</option>';
			}
			echo '</select>';
			break;
		case 'image':
			echo '<div class="bs-image-field"><input type="text" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" class="regular-text" value="' . esc_url( $val ) . '"> <button type="button" class="button bs-upload">' . esc_html__( 'Choose image', 'bshealthcare' ) . '</button>';
			if ( $val ) {
				echo '<br><img src="' . esc_url( $val ) . '" style="max-height:80px;margin-top:8px;border-radius:6px">';
			}
			echo '</div>';
			break;
		default:
			echo '<input type="' . esc_attr( $f['type'] ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" class="regular-text" value="' . esc_attr( $val ) . '" placeholder="' . esc_attr( $ph ) . '">';
	}
}

function bs_render_prescription_box( $post ) {
	$meds = get_post_meta( $post->ID, '_bs_medications', true );
	$rows = array(
		'Patient'     => get_post_meta( $post->ID, '_bs_patient_name', true ) . ' (' . get_post_meta( $post->ID, '_bs_patient_email', true ) . ')',
		'Doctor'      => get_the_title( (int) get_post_meta( $post->ID, '_bs_doctor_id', true ) ),
		'Appointment' => '#' . get_post_meta( $post->ID, '_bs_appointment_id', true ),
		'Diagnosis'   => get_post_meta( $post->ID, '_bs_diagnosis', true ),
		'Notes'       => get_post_meta( $post->ID, '_bs_notes', true ),
		'Secure link' => home_url( '/prescription/' . get_post_meta( $post->ID, '_bs_token', true ) . '/' ),
	);
	echo '<table class="widefat striped">';
	foreach ( $rows as $k => $v ) {
		echo '<tr><th style="width:160px">' . esc_html( $k ) . '</th><td>' . ( 'Secure link' === $k ? '<a href="' . esc_url( $v ) . '" target="_blank">' . esc_html( $v ) . '</a>' : nl2br( esc_html( $v ) ) ) . '</td></tr>';
	}
	echo '<tr><th>Medications</th><td>';
	if ( is_array( $meds ) && $meds ) {
		echo '<ul style="margin:0">';
		foreach ( $meds as $m ) {
			echo '<li><strong>' . esc_html( $m['name'] ) . '</strong> — ' . esc_html( $m['dosage'] ) . ' · ' . esc_html( $m['duration'] ) . ( ! empty( $m['instructions'] ) ? ' · ' . esc_html( $m['instructions'] ) : '' ) . '</li>';
		}
		echo '</ul>';
	}
	echo '</td></tr></table>';
}

/* ---------- Saving ---------- */

function bs_save_meta( $post_id, $post ) {
	if ( ! isset( $_POST['bs_meta_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['bs_meta_nonce'] ), 'bs_meta_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$map = array(
		'bs_doctor'      => 'bs_doctor_fields',
		'bs_service'     => 'bs_service_fields',
		'bs_testimonial' => 'bs_testimonial_fields',
		'bs_appointment' => 'bs_appointment_fields',
	);
	if ( ! isset( $map[ $post->post_type ] ) ) {
		return;
	}
	foreach ( call_user_func( $map[ $post->post_type ] ) as $key => $f ) {
		$raw = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		switch ( $f['type'] ) {
			case 'textarea':
				$val = sanitize_textarea_field( $raw );
				break;
			case 'checkbox':
				$val = $raw ? '1' : '';
				break;
			case 'days':
				$val = is_array( $raw ) ? array_map( 'sanitize_text_field', $raw ) : array();
				break;
			case 'email':
				$val = sanitize_email( $raw );
				break;
			case 'image':
				$val = esc_url_raw( $raw );
				break;
			case 'number':
			case 'user':
			case 'doctor':
				$val = $raw === '' ? '' : (string) (float) $raw;
				break;
			default:
				$val = sanitize_text_field( $raw );
		}
		update_post_meta( $post_id, $key, $val );
	}

	// Appointment: keep a readable title and fire status emails.
	if ( 'bs_appointment' === $post->post_type ) {
		$old = get_post_meta( $post_id, '_bs_last_status', true );
		$new = get_post_meta( $post_id, '_bs_status', true );
		if ( $old !== $new ) {
			update_post_meta( $post_id, '_bs_last_status', $new );
			do_action( 'bs_appointment_status_changed', $post_id, $new, $old );
		}
		bs_refresh_appointment_title( $post_id );
	}
}
add_action( 'save_post', 'bs_save_meta', 10, 2 );

function bs_refresh_appointment_title( $post_id ) {
	$title = trim( get_post_meta( $post_id, '_bs_patient_name', true ) . ' — ' . get_post_meta( $post_id, '_bs_date', true ) . ' ' . get_post_meta( $post_id, '_bs_time', true ) );
	if ( $title && $title !== get_the_title( $post_id ) ) {
		remove_action( 'save_post', 'bs_save_meta', 10 );
		wp_update_post( array( 'ID' => $post_id, 'post_title' => $title ) );
		add_action( 'save_post', 'bs_save_meta', 10, 2 );
	}
}

/* ---------- Admin list columns ---------- */

add_filter( 'manage_bs_doctor_posts_columns', function ( $cols ) {
	return array( 'cb' => $cols['cb'], 'title' => $cols['title'], 'bs_role' => 'Speciality', 'bs_fee' => 'Fee', 'bs_online' => 'On duty', 'bs_user' => 'Portal user', 'date' => $cols['date'] );
} );
add_action( 'manage_bs_doctor_posts_custom_column', function ( $col, $id ) {
	switch ( $col ) {
		case 'bs_role':
			echo esc_html( get_post_meta( $id, '_bs_role', true ) );
			break;
		case 'bs_fee':
			echo '₹' . esc_html( get_post_meta( $id, '_bs_fee', true ) ?: bs_opt( 'appt_fee' ) );
			break;
		case 'bs_online':
			echo '1' === get_post_meta( $id, '_bs_online', true ) ? '<span style="color:#059669">●</span> Yes' : '<span style="color:#9ca3af">●</span> No';
			break;
		case 'bs_user':
			$u = get_userdata( (int) get_post_meta( $id, '_bs_user_id', true ) );
			echo $u ? esc_html( $u->user_email ) : '—';
			break;
	}
}, 10, 2 );

add_filter( 'manage_bs_appointment_posts_columns', function ( $cols ) {
	return array( 'cb' => $cols['cb'], 'title' => 'Patient', 'bs_contact' => 'Contact', 'bs_doctor' => 'Doctor', 'bs_when' => 'Date & time', 'bs_status' => 'Status', 'date' => 'Booked' );
} );
add_action( 'manage_bs_appointment_posts_custom_column', function ( $col, $id ) {
	switch ( $col ) {
		case 'bs_contact':
			echo esc_html( get_post_meta( $id, '_bs_patient_phone', true ) ) . '<br><small>' . esc_html( get_post_meta( $id, '_bs_patient_email', true ) ) . '</small>';
			break;
		case 'bs_doctor':
			echo esc_html( get_the_title( (int) get_post_meta( $id, '_bs_doctor_id', true ) ) );
			break;
		case 'bs_when':
			echo esc_html( get_post_meta( $id, '_bs_date', true ) . ' · ' . get_post_meta( $id, '_bs_time', true ) );
			break;
		case 'bs_status':
			$s = get_post_meta( $id, '_bs_status', true ) ?: 'pending';
			$l = bs_appointment_statuses();
			echo '<span class="bs-status bs-status-' . esc_attr( $s ) . '">' . esc_html( isset( $l[ $s ] ) ? $l[ $s ] : $s ) . '</span>';
			break;
	}
}, 10, 2 );

add_filter( 'manage_bs_prescription_posts_columns', function ( $cols ) {
	return array( 'cb' => $cols['cb'], 'title' => 'Prescription', 'bs_patient' => 'Patient', 'bs_doctor' => 'Doctor', 'bs_link' => 'Secure link', 'date' => 'Issued' );
} );
add_action( 'manage_bs_prescription_posts_custom_column', function ( $col, $id ) {
	switch ( $col ) {
		case 'bs_patient':
			echo esc_html( get_post_meta( $id, '_bs_patient_name', true ) );
			break;
		case 'bs_doctor':
			echo esc_html( get_the_title( (int) get_post_meta( $id, '_bs_doctor_id', true ) ) );
			break;
		case 'bs_link':
			$url = home_url( '/prescription/' . get_post_meta( $id, '_bs_token', true ) . '/' );
			echo '<a href="' . esc_url( $url ) . '" target="_blank">Open</a>';
			break;
	}
}, 10, 2 );

/**
 * Status filter dropdown on the appointments list.
 */
add_action( 'restrict_manage_posts', function ( $type ) {
	if ( 'bs_appointment' !== $type ) {
		return;
	}
	$cur = isset( $_GET['bs_status'] ) ? sanitize_key( $_GET['bs_status'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	echo '<select name="bs_status"><option value="">' . esc_html__( 'All statuses', 'bshealthcare' ) . '</option>';
	foreach ( bs_appointment_statuses() as $k => $l ) {
		echo '<option value="' . esc_attr( $k ) . '" ' . selected( $cur, $k, false ) . '>' . esc_html( $l ) . '</option>';
	}
	echo '</select>';
} );
add_action( 'pre_get_posts', function ( $q ) {
	if ( is_admin() && $q->is_main_query() && 'bs_appointment' === $q->get( 'post_type' ) ) {
		if ( ! empty( $_GET['bs_status'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			$q->set( 'meta_key', '_bs_status' );
			$q->set( 'meta_value', sanitize_key( $_GET['bs_status'] ) ); // phpcs:ignore
		}
	}
} );

/* ---- Enquiry list columns in admin ---- */
add_filter( 'manage_bs_enquiry_posts_columns', function ( $cols ) {
	return array(
		'cb'         => $cols['cb'],
		'title'      => __( 'Sender / Subject', 'bshealthcare' ),
		'bs_sender'  => __( 'Contact Details', 'bshealthcare' ),
		'bs_message' => __( 'Message Snippet', 'bshealthcare' ),
		'date'       => __( 'Received On', 'bshealthcare' ),
	);
} );

add_action( 'manage_bs_enquiry_posts_custom_column', function ( $col, $id ) {
	switch ( $col ) {
		case 'bs_sender':
			$name  = get_post_meta( $id, '_bs_sender_name', true );
			$email = get_post_meta( $id, '_bs_sender_email', true );
			$phone = get_post_meta( $id, '_bs_sender_phone', true );
			echo '<strong>' . esc_html( $name ) . '</strong><br>';
			if ( $email ) {
				echo '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a><br>';
			}
			if ( $phone ) {
				echo '<a href="tel:' . esc_attr( $phone ) . '">' . esc_html( $phone ) . '</a>';
			}
			break;
		case 'bs_message':
			$post = get_post( $id );
			echo esc_html( wp_trim_words( $post->post_content, 18 ) );
			break;
	}
}, 10, 2 );

/* ---- Meta box to show full enquiry details on edit page ---- */
add_action( 'add_meta_boxes', function () {
	add_meta_box(
		'bs_enquiry_details',
		__( 'Enquiry Details', 'bshealthcare' ),
		function ( $post ) {
			$name    = get_post_meta( $post->ID, '_bs_sender_name', true );
			$email   = get_post_meta( $post->ID, '_bs_sender_email', true );
			$phone   = get_post_meta( $post->ID, '_bs_sender_phone', true );
			$subject = get_post_meta( $post->ID, '_bs_subject', true );
			?>
			<table class="widefat striped" style="margin-top:8px;">
				<tr><th style="width:160px;"><?php esc_html_e( 'Name', 'bshealthcare' ); ?></th><td><strong><?php echo esc_html( $name ); ?></strong></td></tr>
				<tr><th><?php esc_html_e( 'Email', 'bshealthcare' ); ?></th><td><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></td></tr>
				<tr><th><?php esc_html_e( 'Phone', 'bshealthcare' ); ?></th><td><a href="tel:<?php echo esc_attr( $phone ); ?>"><?php echo esc_html( $phone ); ?></a></td></tr>
				<tr><th><?php esc_html_e( 'Subject', 'bshealthcare' ); ?></th><td><?php echo esc_html( $subject ); ?></td></tr>
				<tr><th><?php esc_html_e( 'Full Message', 'bshealthcare' ); ?></th><td><div style="background:#f8fafc;padding:12px;border-radius:6px;line-height:1.6;"><?php echo nl2br( esc_html( $post->post_content ) ); ?></div></td></tr>
			</table>
			<p style="margin-top:14px;"><a href="mailto:<?php echo esc_attr( $email ); ?>?subject=Re: <?php echo esc_attr( rawurlencode( $subject ) ); ?>" class="button button-primary"><?php esc_html_e( 'Reply by Email', 'bshealthcare' ); ?></a></p>
			<?php
		},
		'bs_enquiry',
		'normal',
		'high'
	);
} );

