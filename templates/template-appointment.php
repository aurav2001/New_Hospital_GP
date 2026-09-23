<?php
/**
 * Template Name: Book Appointment
 *
 * @package BSHealthcare
 */

get_header();

$phone = bs_opt( 'phone' );
$email = bs_opt( 'email' );
$steps = array(
	array( 'user', __( 'Your details', 'bshealthcare' ), __( 'Name, phone and email so we can confirm your slot.', 'bshealthcare' ) ),
	array( 'stethoscope', __( 'Choose a doctor', 'bshealthcare' ), __( 'Pick the specialist you want to consult.', 'bshealthcare' ) ),
	array( 'calendar', __( 'Pick date & time', 'bshealthcare' ), __( 'See live availability and confirm instantly.', 'bshealthcare' ) ),
);
?>
<main>
	<?php
	get_template_part(
		'template-parts/page-hero',
		null,
		array(
			'eyebrow'  => __( 'Appointments', 'bshealthcare' ),
			'title'    => __( 'Book an appointment', 'bshealthcare' ),
			'subtitle' => __( "Schedule a visit with our specialists in under two minutes. You'll receive confirmation on your phone and email.", 'bshealthcare' ),
			'crumbs'   => array( array( __( 'Book Appointment', 'bshealthcare' ) ) ),
		)
	);
	?>

	<section class="section">
		<div class="container-x grid lg:grid-cols-12 gap-8 lg:gap-12">
			<div class="lg:col-span-7">
				<div class="card p-6 md:p-10 relative overflow-hidden">
					<div class="absolute -top-16 -right-16 w-56 h-56 rounded-full bg-primary-50 blur-2xl" aria-hidden="true"></div>
					<div class="relative">
						<span class="w-16 h-16 rounded-2xl bg-primary-600 text-white flex items-center justify-center shadow-lift mb-6"><?php bs_the_icon( 'calendar', 28 ); ?></span>
						<h2 class="text-2xl md:text-3xl font-extrabold text-navy-800 mb-2"><?php esc_html_e( 'Start your booking', 'bshealthcare' ); ?></h2>
						<p class="text-navy-500 mb-8 max-w-md"><?php esc_html_e( 'Our secure booking wizard guides you through three quick steps.', 'bshealthcare' ); ?></p>

						<ol class="space-y-4 mb-8">
							<?php foreach ( $steps as $i => $s ) : ?>
								<li class="flex items-start gap-4">
									<span class="icon-tile relative"><?php bs_the_icon( $s[0], 20 ); ?><span class="absolute -top-1.5 -left-1.5 w-5 h-5 rounded-full bg-navy-800 text-white text-[10px] font-bold flex items-center justify-center"><?php echo (int) ( $i + 1 ); ?></span></span>
									<span><span class="block font-bold text-navy-800"><?php echo esc_html( $s[1] ); ?></span><span class="block text-sm text-navy-500"><?php echo esc_html( $s[2] ); ?></span></span>
								</li>
							<?php endforeach; ?>
						</ol>

						<button type="button" class="btn-primary btn-lg w-full sm:w-auto" data-bs-book><?php esc_html_e( 'Open booking form', 'bshealthcare' ); ?> <?php bs_the_icon( 'arrow-right', 18 ); ?></button>

						<?php if ( '1' === (string) bs_opt( 'appt_require_login' ) && ! is_user_logged_in() ) : ?>
							<p class="text-xs text-navy-400 mt-3"><?php esc_html_e( 'You will be asked to', 'bshealthcare' ); ?> <a href="<?php echo esc_url( bs_page_url( 'templates/template-login.php' ) ); ?>" class="text-primary-700 font-semibold underline"><?php esc_html_e( 'log in or create an account', 'bshealthcare' ); ?></a> <?php esc_html_e( 'so we can keep your records in one place.', 'bshealthcare' ); ?></p>
						<?php endif; ?>

						<div class="mt-8 pt-6 border-t border-navy-100 flex flex-wrap gap-x-6 gap-y-2 text-sm text-navy-500">
							<span class="inline-flex items-center gap-1.5 text-primary-600"><?php bs_the_icon( 'shield', 16 ); ?> <span class="text-navy-500"><?php esc_html_e( 'Secure', 'bshealthcare' ); ?></span></span>
							<span class="inline-flex items-center gap-1.5 text-primary-600"><?php bs_the_icon( 'clock', 16 ); ?> <span class="text-navy-500"><?php esc_html_e( '2 minutes', 'bshealthcare' ); ?></span></span>
							<span class="inline-flex items-center gap-1.5 text-primary-600"><?php bs_the_icon( 'check-circle', 16 ); ?> <span class="text-navy-500"><?php esc_html_e( 'Instant confirmation', 'bshealthcare' ); ?></span></span>
						</div>
					</div>
				</div>
			</div>

			<div class="lg:col-span-5 space-y-4">
				<div class="card p-5">
					<h3 class="font-bold text-navy-800 mb-4"><?php esc_html_e( 'Prefer to call?', 'bshealthcare' ); ?></h3>
					<ul class="space-y-4 text-sm">
						<li class="flex items-center gap-3"><span class="icon-tile w-10 h-10"><?php bs_the_icon( 'phone', 18 ); ?></span><span><span class="block text-navy-400 text-xs"><?php esc_html_e( 'Support line', 'bshealthcare' ); ?></span><a href="<?php echo esc_attr( bs_phone_href( $phone ) ); ?>" class="font-semibold text-navy-800 hover:text-primary-700"><?php echo esc_html( $phone ); ?></a></span></li>
						<li class="flex items-center gap-3"><span class="icon-tile w-10 h-10"><?php bs_the_icon( 'mail', 18 ); ?></span><span><span class="block text-navy-400 text-xs"><?php esc_html_e( 'Email', 'bshealthcare' ); ?></span><a href="mailto:<?php echo esc_attr( $email ); ?>" class="font-semibold text-navy-800 hover:text-primary-700 break-all"><?php echo esc_html( $email ); ?></a></span></li>
						<li class="flex items-center gap-3"><span class="icon-tile w-10 h-10"><?php bs_the_icon( 'map', 18 ); ?></span><span><span class="block text-navy-400 text-xs"><?php esc_html_e( 'Location', 'bshealthcare' ); ?></span><span class="font-semibold text-navy-800"><?php echo esc_html( bs_opt( 'address' ) ); ?></span></span></li>
					</ul>
				</div>

				<div class="card p-5 bg-primary-50/60 border-primary-100">
					<div class="flex items-center gap-2 mb-4"><span class="text-primary-700"><?php bs_the_icon( 'clock', 18 ); ?></span><h3 class="font-bold text-navy-800"><?php esc_html_e( 'Opening hours', 'bshealthcare' ); ?></h3></div>
					<div class="grid grid-cols-2 gap-3">
						<div class="rounded-xl bg-white p-3 text-center"><p class="text-xs text-navy-400 mb-0.5"><?php esc_html_e( 'OPD (all days)', 'bshealthcare' ); ?></p><p class="font-bold text-navy-800 text-sm"><?php echo esc_html( bs_opt( 'hours_opd' ) ); ?></p></div>
						<div class="rounded-xl bg-white p-3 text-center"><p class="text-xs text-navy-400 mb-0.5"><?php esc_html_e( 'Inpatient care', 'bshealthcare' ); ?></p><p class="font-bold text-emerald-600 text-sm"><?php echo esc_html( bs_opt( 'hours_inpatient' ) ); ?></p></div>
					</div>
				</div>

				<a href="<?php echo esc_url( get_post_type_archive_link( 'bs_doctor' ) ); ?>" class="card-hover p-5 flex items-center gap-4 group">
					<span class="icon-tile"><?php bs_the_icon( 'stethoscope', 22 ); ?></span>
					<span class="flex-1"><span class="block font-bold text-navy-800"><?php esc_html_e( 'Not sure who to see?', 'bshealthcare' ); ?></span><span class="block text-sm text-navy-500"><?php esc_html_e( 'Browse our specialists first.', 'bshealthcare' ); ?></span></span>
					<span class="text-navy-300 group-hover:text-primary-600"><?php bs_the_icon( 'arrow-right', 18 ); ?></span>
				</a>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
