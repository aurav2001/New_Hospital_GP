<?php
/**
 * HTML emails: booking notifications, status changes, prescriptions, contact form.
 *
 * @package GPHealthcare
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bs_email_wrap( $title, $intro, $rows = array(), $cta = array(), $footer_note = '' ) {
	$site = get_bloginfo( 'name' );
	ob_start();
	?>
	<!DOCTYPE html><html><head><meta charset="utf-8"></head>
	<body style="margin:0;padding:0;background:#f0f4f8;font-family:'Segoe UI',Tahoma,Arial,sans-serif;">
	<div style="max-width:600px;margin:0 auto;padding:24px;">
		<div style="background:linear-gradient(135deg,#0891b2,#155e75);color:#fff;padding:32px 28px;border-radius:20px 20px 0 0;">
			<h1 style="margin:0;font-size:22px;"><?php echo esc_html( $title ); ?></h1>
			<p style="margin:8px 0 0;opacity:.9;"><?php echo esc_html( $site ); ?></p>
		</div>
		<div style="background:#fff;padding:28px;border-radius:0 0 20px 20px;box-shadow:0 10px 40px rgba(0,0,0,.08);">
			<p style="color:#334155;line-height:1.6;margin:0 0 18px;"><?php echo wp_kses_post( $intro ); ?></p>
			<?php if ( $rows ) : ?>
			<div style="background:#f8fafc;border-radius:14px;padding:6px 18px;margin:0 0 18px;">
				<?php foreach ( $rows as $k => $v ) : ?>
				<div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #e2e8f0;">
					<span style="color:#64748b;font-size:13px;"><?php echo esc_html( $k ); ?></span>
					<strong style="color:#0f2438;font-size:14px;text-align:right;"><?php echo wp_kses_post( $v ); ?></strong>
				</div>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
			<?php if ( $cta ) : ?>
			<p style="text-align:center;margin:22px 0;"><a href="<?php echo esc_url( $cta[1] ); ?>" style="display:inline-block;background:#0891b2;color:#fff;text-decoration:none;padding:13px 26px;border-radius:12px;font-weight:700;"><?php echo esc_html( $cta[0] ); ?></a></p>
			<?php endif; ?>
			<?php if ( $footer_note ) : ?><p style="color:#94a3b8;font-size:12px;margin:0;"><?php echo esc_html( $footer_note ); ?></p><?php endif; ?>
		</div>
		<p style="text-align:center;color:#94a3b8;font-size:12px;margin-top:16px;"><?php echo esc_html( bs_opt( 'address' ) ); ?> · <?php echo esc_html( bs_opt( 'phone' ) ); ?></p>
	</div></body></html>
	<?php
	return ob_get_clean();
}

function bs_mail( $to, $subject, $html, $reply_to = '' ) {
	if ( ! $to ) {
		return false;
	}
	$headers = array( 'Content-Type: text/html; charset=UTF-8' );
	if ( $reply_to ) {
		$headers[] = 'Reply-To: ' . $reply_to;
	}
	return wp_mail( $to, $subject, $html, $headers );
}

/* ---- New booking ---- */
function bs_email_new_appointment( $id ) {
	$a    = bs_appointment_data( $id );
	$rows = array(
		'Reference' => $a['reference'],
		'Patient'   => $a['name'],
		'Phone'     => $a['phone'],
		'Doctor'    => $a['doctor'],
		'Date'      => date_i18n( 'l, j F Y', strtotime( $a['date'] ) ),
		'Time'      => $a['time'],
		'Fee'       => '₹' . $a['fee'],
		'Status'    => ucfirst( $a['status'] ),
	);
	// Patient.
	$intro = 'confirmed' === $a['status']
		? 'Hi ' . esc_html( $a['name'] ) . ', your appointment is <strong>confirmed</strong>. Please arrive 10 minutes early and carry any previous reports.'
		: 'Hi ' . esc_html( $a['name'] ) . ', we have received your appointment request. Our team will confirm it shortly.';
	bs_mail( $a['email'], '📅 Appointment ' . ( 'confirmed' === $a['status'] ? 'confirmed' : 'received' ) . ' – ' . $a['reference'], bs_email_wrap( 'Appointment ' . ( 'confirmed' === $a['status'] ? 'Confirmed' : 'Received' ), $intro, $rows, array( 'View my appointments', bs_page_url( 'templates/template-patient-dashboard.php' ) ) ) );

	// Doctor + admin.
	$doctor_email = get_post_meta( $a['doctor_id'], '_bs_email', true );
	$admin_html   = bs_email_wrap( 'New Appointment', 'A new appointment has been booked on the website.', $rows + array( 'Email' => $a['email'] ), array( 'Open in admin', admin_url( 'post.php?post=' . $id . '&action=edit' ) ) );
	$to           = array_unique( array_filter( array( bs_opt( 'appt_notify_email' ), $doctor_email ) ) );
	foreach ( $to as $t ) {
		bs_mail( $t, '📅 New appointment – ' . $a['name'] . ' · ' . $a['date'] . ' ' . $a['time'], $admin_html, $a['email'] );
	}
}
add_action( 'bs_appointment_created', 'bs_email_new_appointment' );

/* ---- Status change ---- */
function bs_email_status_change( $id, $new, $old ) {
	if ( $new === $old || ! in_array( $new, array( 'confirmed', 'cancelled', 'completed' ), true ) ) {
		return;
	}
	$a     = bs_appointment_data( $id );
	$texts = array(
		'confirmed' => array( 'Appointment Confirmed', 'Your appointment has been confirmed. See you soon!' ),
		'cancelled' => array( 'Appointment Cancelled', 'Your appointment has been cancelled. If this was unexpected, please call us and we will find you a new slot.' ),
		'completed' => array( 'Thank you for visiting', 'Your consultation is complete. Any prescription issued will be available in your dashboard.' ),
	);
	$rows  = array( 'Reference' => $a['reference'], 'Doctor' => $a['doctor'], 'Date' => date_i18n( 'j F Y', strtotime( $a['date'] ) ), 'Time' => $a['time'] );
	bs_mail( $a['email'], $texts[ $new ][0] . ' – ' . $a['reference'], bs_email_wrap( $texts[ $new ][0], 'Hi ' . esc_html( $a['name'] ) . ', ' . $texts[ $new ][1], $rows, array( 'My dashboard', bs_page_url( 'templates/template-patient-dashboard.php' ) ) ) );
}
add_action( 'bs_appointment_status_changed', 'bs_email_status_change', 10, 3 );

/* ---- Reschedule request → admin/doctor ---- */
add_action( 'bs_appointment_status_changed', function ( $id, $new ) {
	if ( 'reschedule-requested' !== $new ) {
		return;
	}
	$a    = bs_appointment_data( $id );
	$rows = array( 'Patient' => $a['name'], 'Phone' => $a['phone'], 'Doctor' => $a['doctor'], 'Current slot' => $a['date'] . ' ' . $a['time'], 'Request' => nl2br( esc_html( $a['notes'] ) ) );
	$html = bs_email_wrap( 'Reschedule Requested', 'A patient has asked to reschedule their appointment.', $rows, array( 'Open in admin', admin_url( 'post.php?post=' . $id . '&action=edit' ) ) );
	foreach ( array_unique( array_filter( array( bs_opt( 'appt_notify_email' ), get_post_meta( $a['doctor_id'], '_bs_email', true ) ) ) ) as $t ) {
		bs_mail( $t, '🔁 Reschedule request – ' . $a['name'], $html, $a['email'] );
	}
}, 10, 2 );

/* ---- Prescription link ---- */
function bs_email_prescription( $id ) {
	$p    = bs_prescription_data( $id );
	$rows = array( 'Doctor' => $p['doctor'], 'Date' => $p['date'], 'Diagnosis' => esc_html( $p['diagnosis'] ) );
	bs_mail( $p['patient_email'], '💊 Your prescription from ' . $p['doctor'], bs_email_wrap( 'Your Digital Prescription', 'Hi ' . esc_html( $p['patient_name'] ) . ', your doctor has issued a prescription. Open the secure link below to view or print it.', $rows, array( 'View prescription', $p['url'] ), 'This link is private – please do not share it.' ) );
}
add_action( 'bs_prescription_created', 'bs_email_prescription' );

/* ---- Contact form ---- */
function bs_send_contact_email( $d ) {
	$rows = array( 'Name' => esc_html( $d['name'] ), 'Email' => esc_html( $d['email'] ), 'Phone' => esc_html( $d['phone'] ), 'Subject' => esc_html( $d['subject'] ) );
	$html = bs_email_wrap( 'New website enquiry', nl2br( esc_html( $d['message'] ) ), $rows, array(), 'Reply directly to this email to respond to the patient.' );
	return bs_mail( bs_opt( 'appt_notify_email' ), '📩 Contact form: ' . ( $d['subject'] ?: 'General enquiry' ) . ' – ' . $d['name'], $html, $d['email'] );
}

/* ---- New doctor user welcome (when admin creates a Doctor-role user) ---- */
add_action( 'user_register', function ( $user_id ) {
	$user = get_userdata( $user_id );
	if ( ! $user || ! in_array( 'bs_doctor', (array) $user->roles, true ) ) {
		return;
	}
	bs_mail( $user->user_email, 'Your doctor portal access – ' . get_bloginfo( 'name' ), bs_email_wrap( 'Welcome to the Doctor Portal', 'Hi ' . esc_html( $user->display_name ) . ', your doctor portal account is ready. Log in with your email to manage appointments and issue prescriptions.', array( 'Login email' => $user->user_email ), array( 'Open doctor portal', bs_page_url( 'templates/template-login.php' ) ) ) );
} );
