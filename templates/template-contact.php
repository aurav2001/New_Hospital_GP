<?php
/**
 * Template Name: Contact Page
 *
 * @package GPHealthcare
 */

get_header();

$phone = bs_opt( 'phone' );
$email = bs_opt( 'email' );
$addr  = bs_opt( 'address' );
$wa    = bs_whatsapp_url();
$subjects = array(
	__( 'General enquiry', 'bshealthcare' ),
	__( 'Book / reschedule appointment', 'bshealthcare' ),
	__( 'Surgery & treatment consultation', 'bshealthcare' ),
	__( 'Reports & prescriptions', 'bshealthcare' ),
	__( 'Feedback / complaint', 'bshealthcare' ),
);
?>
<main>
	<?php
	get_template_part(
		'template-parts/page-hero',
		null,
		array(
			'eyebrow'  => __( 'Contact Us', 'bshealthcare' ),
			'title'    => __( "We're here to help", 'bshealthcare' ),
			'subtitle' => __( 'Reach out for appointments, reports, surgery inquiries or anything else. Our team usually responds within a few hours.', 'bshealthcare' ),
			'crumbs'   => array( array( __( 'Contact', 'bshealthcare' ) ) ),
		)
	);
	?>

	<section class="section">
		<div class="container-x grid lg:grid-cols-12 gap-8 lg:gap-12">
			<div class="lg:col-span-5 space-y-4">
				<a href="<?php echo esc_attr( bs_phone_href( $phone ) ); ?>" class="card-hover p-5 flex items-start gap-4">
					<span class="icon-tile"><?php bs_the_icon( 'phone', 22 ); ?></span>
					<span>
						<span class="block font-bold text-navy-800"><?php esc_html_e( 'Call us', 'bshealthcare' ); ?></span>
						<span class="block text-sm text-navy-500 mb-1">OPD <?php echo esc_html( bs_opt( 'hours_opd' ) ); ?> · <?php esc_html_e( 'Inpatient care', 'bshealthcare' ); ?> <?php echo esc_html( bs_opt( 'hours_inpatient' ) ); ?></span>
						<span class="block font-semibold text-primary-700"><?php echo esc_html( $phone ); ?></span>
						<?php if ( bs_opt( 'emergency' ) ) : ?><span class="block text-sm text-red-600 font-semibold mt-1"><?php esc_html_e( 'Emergency:', 'bshealthcare' ); ?> <?php echo esc_html( bs_opt( 'emergency' ) ); ?></span><?php endif; ?>
					</span>
				</a>

				<a href="mailto:<?php echo esc_attr( $email ); ?>" class="card-hover p-5 flex items-start gap-4">
					<span class="icon-tile"><?php bs_the_icon( 'mail', 22 ); ?></span>
					<span>
						<span class="block font-bold text-navy-800"><?php esc_html_e( 'Email us', 'bshealthcare' ); ?></span>
						<span class="block text-sm text-navy-500 mb-1"><?php esc_html_e( 'For reports, documents and general queries.', 'bshealthcare' ); ?></span>
						<span class="block font-semibold text-primary-700 break-all"><?php echo esc_html( $email ); ?></span>
					</span>
				</a>

				<?php $contact_map_url = bs_opt( 'map_link' ) ? bs_opt( 'map_link' ) : 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $addr ); ?>
				<a href="<?php echo esc_url( $contact_map_url ); ?>" target="_blank" rel="noopener" class="card-hover p-5 flex items-start gap-4">
					<span class="icon-tile"><?php bs_the_icon( 'map', 22 ); ?></span>
					<span>
						<span class="block font-bold text-navy-800"><?php esc_html_e( 'Visit us', 'bshealthcare' ); ?></span>
						<span class="block text-sm text-navy-500 mb-1"><?php esc_html_e( 'View exact hospital location on Google Maps.', 'bshealthcare' ); ?></span>
						<span class="block font-semibold text-primary-700"><?php echo esc_html( $addr ); ?></span>
					</span>
				</a>

				<?php if ( $wa ) : ?>
				<a href="<?php echo esc_url( $wa ); ?>" target="_blank" rel="noopener" class="card-hover p-5 flex items-start gap-4">
					<span class="icon-tile bg-emerald-50 text-emerald-600"><?php bs_the_icon( 'message', 22 ); ?></span>
					<span>
						<span class="block font-bold text-navy-800">WhatsApp</span>
						<span class="block text-sm text-navy-500"><?php esc_html_e( 'Chat with our front desk for quick answers.', 'bshealthcare' ); ?></span>
					</span>
				</a>
				<?php endif; ?>

				<div class="card p-5">
					<div class="flex items-center gap-2 mb-4"><span class="text-primary-600"><?php bs_the_icon( 'clock', 18 ); ?></span><h3 class="font-bold text-navy-800"><?php esc_html_e( 'Opening hours', 'bshealthcare' ); ?></h3></div>
					<dl class="text-sm divide-y divide-navy-100">
						<div class="flex justify-between py-2"><dt class="text-navy-500"><?php esc_html_e( 'OPD (all days)', 'bshealthcare' ); ?></dt><dd class="font-semibold text-navy-800"><?php echo esc_html( bs_opt( 'hours_opd' ) ); ?></dd></div>
						<div class="flex justify-between py-2"><dt class="text-navy-500"><?php esc_html_e( 'Inpatient care', 'bshealthcare' ); ?></dt><dd class="font-semibold text-emerald-600"><?php echo esc_html( bs_opt( 'hours_inpatient' ) ); ?></dd></div>
					</dl>
				</div>
			</div>

			<div class="lg:col-span-7">
				<div class="card p-6 md:p-8">
					<h2 class="text-2xl font-extrabold text-navy-800 mb-1"><?php esc_html_e( 'Send us a message', 'bshealthcare' ); ?></h2>
					<p class="text-navy-500 text-sm mb-6"><?php esc_html_e( "Fill in the form and we'll get back to you within 24 hours.", 'bshealthcare' ); ?></p>

					<div id="bs-contact-success" class="hidden rounded-2xl bg-emerald-50 border border-emerald-200 p-6 text-center">
						<div class="w-14 h-14 mx-auto rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mb-3"><?php bs_the_icon( 'check-circle', 28 ); ?></div>
						<h3 class="font-bold text-navy-800 text-lg mb-1"><?php esc_html_e( 'Message sent', 'bshealthcare' ); ?></h3>
						<p class="text-sm text-navy-600" id="bs-contact-success-text"></p>
					</div>

					<form id="bs-contact-form" class="space-y-4" novalidate>
						<div id="bs-contact-alert" class="hidden rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700"></div>
						<input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off" aria-hidden="true">
						<div class="grid sm:grid-cols-2 gap-4">
							<div><label class="label" for="c-name"><?php esc_html_e( 'Full name', 'bshealthcare' ); ?></label><input class="input" id="c-name" name="name" type="text" placeholder="<?php esc_attr_e( 'Your name', 'bshealthcare' ); ?>"></div>
							<div><label class="label" for="c-phone"><?php esc_html_e( 'Phone', 'bshealthcare' ); ?></label><input class="input" id="c-phone" name="phone" type="tel" placeholder="+91 77649 63174"></div>
						</div>
						<div class="grid sm:grid-cols-2 gap-4">
							<div><label class="label" for="c-email"><?php esc_html_e( 'Email', 'bshealthcare' ); ?></label><input class="input" id="c-email" name="email" type="email" placeholder="you@example.com"></div>
							<div><label class="label" for="c-subject"><?php esc_html_e( 'Subject', 'bshealthcare' ); ?></label>
								<select class="input" id="c-subject" name="subject">
									<?php foreach ( $subjects as $s ) : ?><option><?php echo esc_html( $s ); ?></option><?php endforeach; ?>
								</select>
							</div>
						</div>
						<div><label class="label" for="c-message"><?php esc_html_e( 'Message', 'bshealthcare' ); ?></label><textarea class="input resize-none" id="c-message" name="message" rows="5" placeholder="<?php esc_attr_e( 'How can we help you?', 'bshealthcare' ); ?>"></textarea></div>
						<button type="submit" class="btn-primary btn-lg w-full"><?php bs_the_icon( 'send', 18 ); ?> <span><?php esc_html_e( 'Send message', 'bshealthcare' ); ?></span></button>
						<p class="text-xs text-navy-400 text-center"><?php esc_html_e( 'By submitting you agree to be contacted by our team regarding your enquiry.', 'bshealthcare' ); ?></p>
					</form>
				</div>
			</div>
		</div>
	</section>

	<?php
	$page_content = get_the_content( null, false, get_queried_object_id() );
	if ( trim( wp_strip_all_tags( $page_content ) ) ) :
		?>
		<section class="container-x pb-8 prose-cms"><?php echo wp_kses_post( apply_filters( 'the_content', $page_content ) ); ?></section>
	<?php endif; ?>
</main>
<?php
get_footer();
