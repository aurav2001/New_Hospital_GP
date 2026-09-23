<?php
/**
 * Template Name: Patient Dashboard
 *
 * @package GPHealthcare
 */

if ( ! is_user_logged_in() ) {
	wp_safe_redirect( add_query_arg( 'redirect_to', rawurlencode( get_permalink() ), bs_page_url( 'templates/template-login.php' ) ) );
	exit;
}

get_header();

$user          = wp_get_current_user();
$appointments  = bs_patient_appointments( $user->ID );
$prescriptions = bs_patient_prescriptions( $user->ID );
$today         = current_time( 'Y-m-d' );
$upcoming      = array_values( array_filter( $appointments, function ( $a ) use ( $today ) {
	return $a['date'] >= $today && ! in_array( $a['status'], array( 'cancelled', 'completed' ), true );
} ) );
$past          = array_values( array_filter( $appointments, function ( $a ) use ( $today ) {
	return $a['date'] < $today || in_array( $a['status'], array( 'cancelled', 'completed' ), true );
} ) );
?>
<main class="bg-navy-50/50 min-h-screen">
	<div class="container-x py-8 md:py-12">
		<!-- Header -->
		<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
			<div>
				<p class="eyebrow mb-2"><?php esc_html_e( 'Patient Dashboard', 'bshealthcare' ); ?></p>
				<h1 class="heading-lg"><?php printf( esc_html__( 'Welcome, %s', 'bshealthcare' ), esc_html( $user->display_name ) ); ?></h1>
			</div>
			<div class="flex gap-2">
				<button type="button" class="btn-primary" data-bs-book><?php bs_the_icon( 'calendar', 16 ); ?> <?php esc_html_e( 'Book Appointment', 'bshealthcare' ); ?></button>
				<a href="<?php echo esc_url( bs_logout_url() ); ?>" class="btn-outline"><?php bs_the_icon( 'logout', 16 ); ?> <?php esc_html_e( 'Logout', 'bshealthcare' ); ?></a>
			</div>
		</div>

		<!-- Stats -->
		<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
			<?php
			$stats = array(
				array( 'calendar', __( 'Upcoming', 'bshealthcare' ), count( $upcoming ) ),
				array( 'check-circle', __( 'Completed', 'bshealthcare' ), count( array_filter( $appointments, function ( $a ) { return 'completed' === $a['status']; } ) ) ),
				array( 'pill', __( 'Prescriptions', 'bshealthcare' ), count( $prescriptions ) ),
				array( 'file', __( 'Total visits', 'bshealthcare' ), count( $appointments ) ),
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

		<!-- Tabs -->
		<div class="bs-tabs" data-tabs>
			<div class="flex gap-2 mb-6 overflow-x-auto hide-scrollbar">
				<button type="button" class="bs-tab is-active" data-tab="upcoming"><?php esc_html_e( 'Upcoming', 'bshealthcare' ); ?> (<?php echo count( $upcoming ); ?>)</button>
				<button type="button" class="bs-tab" data-tab="history"><?php esc_html_e( 'History', 'bshealthcare' ); ?> (<?php echo count( $past ); ?>)</button>
				<button type="button" class="bs-tab" data-tab="prescriptions"><?php esc_html_e( 'Prescriptions', 'bshealthcare' ); ?> (<?php echo count( $prescriptions ); ?>)</button>
				<button type="button" class="bs-tab" data-tab="profile"><?php esc_html_e( 'Profile', 'bshealthcare' ); ?></button>
			</div>

			<!-- Upcoming -->
			<div class="bs-tab-panel" data-panel="upcoming">
				<?php if ( ! $upcoming ) : ?>
					<div class="card p-12 text-center">
						<span class="w-16 h-16 mx-auto rounded-2xl bg-navy-50 text-navy-300 flex items-center justify-center mb-4"><?php bs_the_icon( 'calendar', 28 ); ?></span>
						<h3 class="text-xl font-bold text-navy-800 mb-1"><?php esc_html_e( 'No upcoming appointments', 'bshealthcare' ); ?></h3>
						<p class="text-navy-500 text-sm mb-5"><?php esc_html_e( 'Book a consultation with one of our specialists.', 'bshealthcare' ); ?></p>
						<button type="button" class="btn-primary" data-bs-book><?php esc_html_e( 'Book now', 'bshealthcare' ); ?></button>
					</div>
				<?php else : ?>
					<div class="space-y-4">
						<?php foreach ( $upcoming as $a ) : ?>
							<?php get_template_part( 'template-parts/dashboard/appointment-row', null, array( 'appt' => $a, 'role' => 'patient' ) ); ?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<!-- History -->
			<div class="bs-tab-panel hidden" data-panel="history">
				<?php if ( ! $past ) : ?>
					<div class="card p-12 text-center text-navy-500"><?php esc_html_e( 'No past appointments yet.', 'bshealthcare' ); ?></div>
				<?php else : ?>
					<div class="space-y-4">
						<?php foreach ( $past as $a ) : ?>
							<?php get_template_part( 'template-parts/dashboard/appointment-row', null, array( 'appt' => $a, 'role' => 'patient' ) ); ?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<!-- Prescriptions -->
			<div class="bs-tab-panel hidden" data-panel="prescriptions">
				<?php if ( ! $prescriptions ) : ?>
					<div class="card p-12 text-center text-navy-500"><?php esc_html_e( 'No prescriptions issued yet.', 'bshealthcare' ); ?></div>
				<?php else : ?>
					<div class="grid md:grid-cols-2 gap-4">
						<?php foreach ( $prescriptions as $p ) : ?>
							<div class="card p-5">
								<div class="flex items-start justify-between gap-3 mb-3">
									<div>
										<h3 class="font-bold text-navy-800"><?php echo esc_html( $p['diagnosis'] ); ?></h3>
										<p class="text-sm text-navy-500"><?php echo esc_html( $p['doctor'] ); ?> · <?php echo esc_html( $p['date'] ); ?></p>
									</div>
									<span class="icon-tile w-10 h-10"><?php bs_the_icon( 'pill', 18 ); ?></span>
								</div>
								<ul class="space-y-1.5 text-sm text-navy-600 mb-4">
									<?php foreach ( array_slice( $p['medications'], 0, 3 ) as $m ) : ?>
										<li class="flex gap-2"><span class="text-primary-600 mt-0.5"><?php bs_the_icon( 'check', 14 ); ?></span> <span><strong><?php echo esc_html( $m['name'] ); ?></strong> — <?php echo esc_html( $m['dosage'] ); ?> · <?php echo esc_html( $m['duration'] ); ?></span></li>
									<?php endforeach; ?>
									<?php if ( count( $p['medications'] ) > 3 ) : ?>
										<li class="text-xs text-navy-400"><?php printf( esc_html__( '+ %d more', 'bshealthcare' ), count( $p['medications'] ) - 3 ); ?></li>
									<?php endif; ?>
								</ul>
								<a href="<?php echo esc_url( $p['url'] ); ?>" target="_blank" rel="noopener" class="btn-outline w-full"><?php bs_the_icon( 'file', 16 ); ?> <?php esc_html_e( 'View / print prescription', 'bshealthcare' ); ?></a>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<!-- Profile -->
			<div class="bs-tab-panel hidden" data-panel="profile">
				<div class="card p-6 max-w-lg">
					<h3 class="font-bold text-navy-800 text-lg mb-4"><?php esc_html_e( 'My details', 'bshealthcare' ); ?></h3>
					<dl class="text-sm divide-y divide-navy-100">
						<div class="flex justify-between py-3"><dt class="text-navy-500"><?php esc_html_e( 'Name', 'bshealthcare' ); ?></dt><dd class="font-semibold text-navy-800"><?php echo esc_html( $user->display_name ); ?></dd></div>
						<div class="flex justify-between py-3"><dt class="text-navy-500"><?php esc_html_e( 'Email', 'bshealthcare' ); ?></dt><dd class="font-semibold text-navy-800"><?php echo esc_html( $user->user_email ); ?></dd></div>
						<div class="flex justify-between py-3"><dt class="text-navy-500"><?php esc_html_e( 'Phone', 'bshealthcare' ); ?></dt><dd class="font-semibold text-navy-800"><?php echo esc_html( get_user_meta( $user->ID, 'bs_phone', true ) ? get_user_meta( $user->ID, 'bs_phone', true ) : '—' ); ?></dd></div>
					</dl>
					<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="btn-outline w-full mt-5"><?php esc_html_e( 'Change password', 'bshealthcare' ); ?></a>
				</div>
			</div>
		</div>
	</div>
</main>
<?php
get_footer();
