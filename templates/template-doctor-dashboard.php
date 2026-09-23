<?php
/**
 * Template Name: Doctor Portal
 *
 * @package BSHealthcare
 */

if ( ! is_user_logged_in() ) {
	wp_safe_redirect( add_query_arg( 'redirect_to', rawurlencode( get_permalink() ), bs_page_url( 'templates/template-login.php' ) ) );
	exit;
}

get_header();

$doctor_id = bs_current_doctor_id();
if ( ! bs_is_doctor_user() || ! $doctor_id ) :
	?>
	<main class="section">
		<div class="container-x max-w-xl">
			<div class="card p-10 text-center">
				<span class="w-16 h-16 mx-auto rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-4"><?php bs_the_icon( 'alert', 28 ); ?></span>
				<h1 class="text-2xl font-extrabold text-navy-800 mb-2"><?php esc_html_e( 'Doctor portal', 'bshealthcare' ); ?></h1>
				<p class="text-navy-500 mb-6"><?php esc_html_e( 'Your account is not linked to a doctor profile yet. Please ask the administrator to link your user account under Doctors → Edit doctor → Linked WordPress user.', 'bshealthcare' ); ?></p>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-primary"><?php esc_html_e( 'Back to website', 'bshealthcare' ); ?></a>
			</div>
		</div>
	</main>
	<?php
	get_footer();
	return;
endif;

$doc           = bs_doctor_data( $doctor_id );
$today         = current_time( 'Y-m-d' );
$today_appts   = bs_doctor_appointments( $doctor_id, array( 'date' => $today ) );
$pending       = bs_doctor_appointments( $doctor_id, array( 'status' => array( 'pending', 'reschedule-requested' ) ) );
$upcoming      = array_filter( bs_doctor_appointments( $doctor_id, array( 'from' => $today ) ), function ( $a ) {
	return ! in_array( $a['status'], array( 'cancelled', 'completed' ), true );
} );
$prescriptions = bs_doctor_prescriptions( $doctor_id );
$all           = bs_doctor_appointments( $doctor_id );
?>
<main class="bg-navy-50/50 min-h-screen">
	<div class="container-x py-8 md:py-12">
		<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
			<div>
				<p class="eyebrow mb-2"><?php esc_html_e( 'Doctor Portal', 'bshealthcare' ); ?></p>
				<h1 class="heading-lg"><?php echo esc_html( $doc['name'] ); ?></h1>
				<p class="text-navy-500 text-sm"><?php echo esc_html( $doc['role'] ); ?></p>
			</div>
			<div class="flex items-center gap-2">
				<button type="button" id="bs-duty" class="btn <?php echo $doc['online'] ? 'bg-emerald-600 text-white hover:bg-emerald-700' : 'btn-outline'; ?>" data-online="<?php echo $doc['online'] ? '1' : '0'; ?>">
					<span class="w-2 h-2 rounded-full <?php echo $doc['online'] ? 'bg-white' : 'bg-navy-300'; ?>"></span>
					<span class="bs-duty-label"><?php echo $doc['online'] ? esc_html__( 'On duty', 'bshealthcare' ) : esc_html__( 'Off duty', 'bshealthcare' ); ?></span>
				</button>
				<a href="<?php echo esc_url( bs_logout_url() ); ?>" class="btn-outline"><?php bs_the_icon( 'logout', 16 ); ?> <?php esc_html_e( 'Logout', 'bshealthcare' ); ?></a>
			</div>
		</div>

		<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
			<?php
			$stats = array(
				array( 'calendar', __( "Today's appointments", 'bshealthcare' ), count( $today_appts ) ),
				array( 'alert', __( 'Pending requests', 'bshealthcare' ), count( $pending ) ),
				array( 'check-circle', __( 'Upcoming', 'bshealthcare' ), count( $upcoming ) ),
				array( 'pill', __( 'Prescriptions', 'bshealthcare' ), count( $prescriptions ) ),
			);
			foreach ( $stats as $s ) :
				?>
				<div class="card p-5">
					<span class="icon-tile mb-3"><?php bs_the_icon( $s[0], 20 ); ?></span>
					<p class="text-2xl font-extrabold text-navy-800"><?php echo esc_html( $s[2] ); ?></p>
					<p class="text-xs text-navy-400 font-semibold uppercase tracking-wide"><?php echo esc_html( $s[1] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="bs-tabs" data-tabs>
			<div class="flex gap-2 mb-6 overflow-x-auto hide-scrollbar">
				<button type="button" class="bs-tab is-active" data-tab="today"><?php esc_html_e( 'Today', 'bshealthcare' ); ?> (<?php echo count( $today_appts ); ?>)</button>
				<button type="button" class="bs-tab" data-tab="pending"><?php esc_html_e( 'Requests', 'bshealthcare' ); ?> (<?php echo count( $pending ); ?>)</button>
				<button type="button" class="bs-tab" data-tab="all"><?php esc_html_e( 'All appointments', 'bshealthcare' ); ?></button>
				<button type="button" class="bs-tab" data-tab="rx"><?php esc_html_e( 'Prescriptions', 'bshealthcare' ); ?> (<?php echo count( $prescriptions ); ?>)</button>
				<button type="button" class="bs-tab" data-tab="settings"><?php esc_html_e( 'Settings', 'bshealthcare' ); ?></button>
			</div>

			<div class="bs-tab-panel space-y-4" data-panel="today">
				<?php if ( ! $today_appts ) : ?>
					<div class="card p-12 text-center text-navy-500"><?php esc_html_e( 'No appointments scheduled for today.', 'bshealthcare' ); ?></div>
				<?php else : ?>
					<?php foreach ( $today_appts as $a ) : ?>
						<?php get_template_part( 'template-parts/dashboard/appointment-row', null, array( 'appt' => $a, 'role' => 'doctor' ) ); ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>

			<div class="bs-tab-panel hidden space-y-4" data-panel="pending">
				<?php if ( ! $pending ) : ?>
					<div class="card p-12 text-center text-navy-500"><?php esc_html_e( 'No pending requests.', 'bshealthcare' ); ?></div>
				<?php else : ?>
					<?php foreach ( $pending as $a ) : ?>
						<?php get_template_part( 'template-parts/dashboard/appointment-row', null, array( 'appt' => $a, 'role' => 'doctor' ) ); ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>

			<div class="bs-tab-panel hidden space-y-4" data-panel="all">
				<?php if ( ! $all ) : ?>
					<div class="card p-12 text-center text-navy-500"><?php esc_html_e( 'No appointments yet.', 'bshealthcare' ); ?></div>
				<?php else : ?>
					<?php foreach ( array_reverse( $all ) as $a ) : ?>
						<?php get_template_part( 'template-parts/dashboard/appointment-row', null, array( 'appt' => $a, 'role' => 'doctor' ) ); ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>

			<div class="bs-tab-panel hidden" data-panel="rx">
				<?php if ( ! $prescriptions ) : ?>
					<div class="card p-12 text-center text-navy-500"><?php esc_html_e( 'No prescriptions issued yet.', 'bshealthcare' ); ?></div>
				<?php else : ?>
					<div class="grid md:grid-cols-2 gap-4">
						<?php foreach ( array_reverse( $prescriptions ) as $p ) : ?>
							<div class="card p-5">
								<div class="flex items-start justify-between gap-3 mb-2">
									<div><h3 class="font-bold text-navy-800"><?php echo esc_html( $p['patient_name'] ); ?></h3><p class="text-sm text-navy-500"><?php echo esc_html( $p['date'] ); ?></p></div>
									<span class="icon-tile w-10 h-10"><?php bs_the_icon( 'pill', 18 ); ?></span>
								</div>
								<p class="text-sm text-navy-600 mb-3"><strong><?php esc_html_e( 'Diagnosis:', 'bshealthcare' ); ?></strong> <?php echo esc_html( $p['diagnosis'] ); ?></p>
								<p class="text-xs text-navy-400 mb-4"><?php printf( esc_html__( '%d medication(s)', 'bshealthcare' ), count( $p['medications'] ) ); ?></p>
								<a href="<?php echo esc_url( $p['url'] ); ?>" target="_blank" rel="noopener" class="btn-outline w-full text-sm"><?php esc_html_e( 'Open', 'bshealthcare' ); ?></a>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="bs-tab-panel hidden" data-panel="settings">
				<form id="bs-doctor-settings" class="card p-6 max-w-2xl space-y-4">
					<h3 class="font-bold text-navy-800 text-lg"><?php esc_html_e( 'My profile', 'bshealthcare' ); ?></h3>
					<div id="bs-ds-alert" class="hidden rounded-xl border p-3 text-sm"></div>
					<div class="grid sm:grid-cols-2 gap-4">
						<div><label class="label" for="ds-exp"><?php esc_html_e( 'Experience', 'bshealthcare' ); ?></label><input class="input" id="ds-exp" name="experience" value="<?php echo esc_attr( $doc['experience'] ); ?>"></div>
						<div><label class="label" for="ds-fee"><?php esc_html_e( 'Consultation fee (₹)', 'bshealthcare' ); ?></label><input class="input" id="ds-fee" name="fee" type="number" value="<?php echo esc_attr( $doc['fee'] ); ?>"></div>
					</div>
					<div><label class="label" for="ds-lang"><?php esc_html_e( 'Languages', 'bshealthcare' ); ?></label><input class="input" id="ds-lang" name="languages" value="<?php echo esc_attr( $doc['languages'] ); ?>"></div>
					<div>
						<span class="label"><?php esc_html_e( 'Available days', 'bshealthcare' ); ?></span>
						<div class="flex flex-wrap gap-3">
							<?php foreach ( array( 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun' ) as $d ) : ?>
								<label class="flex items-center gap-2 text-sm text-navy-700"><input type="checkbox" name="days[]" value="<?php echo esc_attr( $d ); ?>" class="rounded border-navy-300" <?php checked( in_array( $d, (array) $doc['days'], true ) ); ?>> <?php echo esc_html( $d ); ?></label>
							<?php endforeach; ?>
						</div>
					</div>
					<div><label class="label" for="ds-bio"><?php esc_html_e( 'About me', 'bshealthcare' ); ?></label><textarea class="input resize-none" id="ds-bio" name="bio" rows="4"><?php echo esc_textarea( get_post_field( 'post_content', $doctor_id ) ); ?></textarea></div>
					<button type="submit" class="btn-primary"><?php esc_html_e( 'Save changes', 'bshealthcare' ); ?></button>
				</form>
			</div>
		</div>
	</div>
</main>

<!-- Prescription modal -->
<div id="bs-rx-modal" class="hidden fixed inset-0 z-[140] items-center justify-center p-4">
	<div class="absolute inset-0 bg-navy-900/50 backdrop-blur-sm" data-rx-close></div>
	<div class="relative card w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col">
		<div class="flex items-center justify-between px-6 py-4 border-b border-navy-100">
			<div><h2 class="font-extrabold text-navy-800 text-lg"><?php esc_html_e( 'Write prescription', 'bshealthcare' ); ?></h2><p class="text-xs text-navy-400" id="bs-rx-patient"></p></div>
			<button type="button" class="w-9 h-9 rounded-lg hover:bg-navy-50 flex items-center justify-center" data-rx-close aria-label="<?php esc_attr_e( 'Close', 'bshealthcare' ); ?>"><?php bs_the_icon( 'x', 20 ); ?></button>
		</div>
		<form id="bs-rx-form" class="flex-1 overflow-y-auto p-6 space-y-4">
			<div id="bs-rx-alert" class="hidden rounded-xl border p-3 text-sm"></div>
			<input type="hidden" name="appointment" id="bs-rx-appt">
			<div><label class="label" for="rx-diagnosis"><?php esc_html_e( 'Diagnosis', 'bshealthcare' ); ?></label><textarea class="input resize-none" id="rx-diagnosis" name="diagnosis" rows="2" placeholder="<?php esc_attr_e( 'e.g. Immature senile cataract, right eye', 'bshealthcare' ); ?>"></textarea></div>
			<div>
				<span class="label"><?php esc_html_e( 'Medications', 'bshealthcare' ); ?></span>
				<div id="bs-rx-meds" class="space-y-3"></div>
				<button type="button" id="bs-rx-add" class="btn-outline text-sm mt-3"><?php bs_the_icon( 'plus', 14 ); ?> <?php esc_html_e( 'Add medication', 'bshealthcare' ); ?></button>
			</div>
			<div><label class="label" for="rx-notes"><?php esc_html_e( 'Advice / follow-up notes', 'bshealthcare' ); ?></label><textarea class="input resize-none" id="rx-notes" name="notes" rows="3"></textarea></div>
		</form>
		<div class="px-6 py-4 border-t border-navy-100 flex justify-end gap-3">
			<button type="button" class="btn-ghost" data-rx-close><?php esc_html_e( 'Cancel', 'bshealthcare' ); ?></button>
			<button type="button" id="bs-rx-save" class="btn-primary"><?php esc_html_e( 'Save & email patient', 'bshealthcare' ); ?></button>
		</div>
	</div>
</div>
<?php
get_footer();
