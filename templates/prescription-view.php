<?php
/**
 * Secure prescription view — /prescription/{token}/
 * No login required; the random token is the key.
 *
 * @package GPHealthcare
 */

$token = get_query_var( 'bs_prescription' );
$p     = $token ? bs_prescription_by_token( $token ) : null;
if ( ! $p ) {
	status_header( 404 );
}
$appt = $p ? bs_appointment_data( $p['appointment_id'] ) : null;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex, nofollow">
	<title><?php echo esc_html( $p ? __( 'Prescription', 'bshealthcare' ) . ' – ' . $p['patient_name'] : __( 'Prescription not found', 'bshealthcare' ) ); ?></title>
	<?php wp_head(); ?>
</head>
<body class="bg-navy-50 font-sans">
<?php if ( ! $p ) : ?>
	<main class="min-h-screen flex items-center justify-center p-6">
		<div class="card p-10 text-center max-w-md">
			<span class="w-16 h-16 mx-auto rounded-2xl bg-red-50 text-red-500 flex items-center justify-center mb-4"><?php bs_the_icon( 'alert', 28 ); ?></span>
			<h1 class="text-2xl font-extrabold text-navy-800 mb-2"><?php esc_html_e( 'Prescription not found', 'bshealthcare' ); ?></h1>
			<p class="text-navy-500 mb-6"><?php esc_html_e( 'This link is invalid or has been removed. Please contact the hospital.', 'bshealthcare' ); ?></p>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-primary"><?php esc_html_e( 'Go to website', 'bshealthcare' ); ?></a>
		</div>
	</main>
<?php else : ?>
	<main class="py-8 px-4 print:p-0">
		<div class="max-w-3xl mx-auto">
			<div class="flex justify-end gap-2 mb-4 print:hidden">
				<button type="button" onclick="window.print()" class="btn-outline"><?php bs_the_icon( 'printer', 16 ); ?> <?php esc_html_e( 'Print', 'bshealthcare' ); ?></button>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-ghost"><?php esc_html_e( 'Hospital website', 'bshealthcare' ); ?></a>
			</div>

			<article class="card overflow-hidden print:shadow-none print:border-0">
				<!-- Letterhead -->
				<header class="bg-navy-900 text-white p-6 md:p-8 flex items-start justify-between gap-6 print:bg-white print:text-navy-900 print:border-b print:border-navy-200">
					<div>
						<h1 class="text-2xl font-extrabold text-white print:text-navy-900"><?php bloginfo( 'name' ); ?></h1>
						<p class="text-sm text-navy-300 print:text-navy-500"><?php echo esc_html( bs_opt( 'site_tagline' ) ); ?></p>
						<p class="text-xs text-navy-400 mt-2 print:text-navy-500"><?php echo esc_html( bs_opt( 'address' ) ); ?><br><?php echo esc_html( bs_opt( 'phone' ) ); ?> · <?php echo esc_html( bs_opt( 'email' ) ); ?></p>
					</div>
					<span class="w-14 h-14 rounded-2xl bg-primary-600 text-white flex items-center justify-center shrink-0"><?php bs_the_icon( 'eye', 28 ); ?></span>
				</header>

				<div class="p-6 md:p-8">
					<div class="flex flex-wrap items-center justify-between gap-4 pb-5 mb-5 border-b border-navy-100">
						<div>
							<p class="text-xs font-bold uppercase tracking-wider text-navy-400"><?php esc_html_e( 'Patient', 'bshealthcare' ); ?></p>
							<p class="text-lg font-bold text-navy-800"><?php echo esc_html( $p['patient_name'] ); ?></p>
						</div>
						<div class="text-right">
							<p class="text-xs font-bold uppercase tracking-wider text-navy-400"><?php esc_html_e( 'Date', 'bshealthcare' ); ?></p>
							<p class="font-semibold text-navy-800"><?php echo esc_html( $p['date'] ); ?></p>
						</div>
						<?php if ( $appt && $appt['reference'] ) : ?>
						<div class="text-right">
							<p class="text-xs font-bold uppercase tracking-wider text-navy-400"><?php esc_html_e( 'Reference', 'bshealthcare' ); ?></p>
							<p class="font-semibold text-navy-800"><?php echo esc_html( $appt['reference'] ); ?></p>
						</div>
						<?php endif; ?>
					</div>

					<section class="mb-6">
						<h2 class="text-xs font-bold uppercase tracking-wider text-primary-700 mb-2"><?php esc_html_e( 'Diagnosis', 'bshealthcare' ); ?></h2>
						<p class="text-navy-800"><?php echo nl2br( esc_html( $p['diagnosis'] ) ); ?></p>
					</section>

					<section class="mb-6">
						<h2 class="text-xs font-bold uppercase tracking-wider text-primary-700 mb-3">℞ <?php esc_html_e( 'Medications', 'bshealthcare' ); ?></h2>
						<div class="overflow-x-auto">
							<table class="w-full text-sm">
								<thead>
									<tr class="text-left text-navy-400 text-xs uppercase tracking-wide border-b border-navy-100">
										<th class="py-2 pr-3">#</th>
										<th class="py-2 pr-3"><?php esc_html_e( 'Medicine', 'bshealthcare' ); ?></th>
										<th class="py-2 pr-3"><?php esc_html_e( 'Dosage', 'bshealthcare' ); ?></th>
										<th class="py-2 pr-3"><?php esc_html_e( 'Duration', 'bshealthcare' ); ?></th>
										<th class="py-2"><?php esc_html_e( 'Instructions', 'bshealthcare' ); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ( $p['medications'] as $i => $m ) : ?>
										<tr class="border-b border-navy-50">
											<td class="py-3 pr-3 text-navy-400"><?php echo esc_html( $i + 1 ); ?></td>
											<td class="py-3 pr-3 font-bold text-navy-800"><?php echo esc_html( $m['name'] ); ?></td>
											<td class="py-3 pr-3 text-navy-600"><?php echo esc_html( $m['dosage'] ); ?></td>
											<td class="py-3 pr-3 text-navy-600"><?php echo esc_html( $m['duration'] ); ?></td>
											<td class="py-3 text-navy-600"><?php echo esc_html( $m['instructions'] ); ?></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</section>

					<?php if ( $p['notes'] ) : ?>
					<section class="mb-6 rounded-xl bg-primary-50/70 border border-primary-100 p-4">
						<h2 class="text-xs font-bold uppercase tracking-wider text-primary-700 mb-2"><?php esc_html_e( 'Advice & follow-up', 'bshealthcare' ); ?></h2>
						<p class="text-navy-700 text-sm"><?php echo nl2br( esc_html( $p['notes'] ) ); ?></p>
					</section>
					<?php endif; ?>

					<footer class="pt-6 mt-6 border-t border-navy-100 flex items-end justify-between">
						<p class="text-xs text-navy-400 max-w-xs"><?php esc_html_e( 'This is a digitally issued prescription. Please follow the dosage exactly and contact the hospital if symptoms worsen.', 'bshealthcare' ); ?></p>
						<div class="text-right">
							<p class="font-bold text-navy-800"><?php echo esc_html( $p['doctor'] ); ?></p>
							<p class="text-xs text-navy-400"><?php esc_html_e( 'Consulting Ophthalmologist', 'bshealthcare' ); ?></p>
						</div>
					</footer>
				</div>
			</article>
		</div>
	</main>
<?php endif; ?>
<?php wp_footer(); ?>
</body>
</html>
