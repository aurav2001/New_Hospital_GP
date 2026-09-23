<?php
/**
 * Site footer, floating actions, booking modal, popup.
 *
 * @package GPHealthcare
 */

$bs_phone  = bs_opt( 'phone' );
$bs_email  = bs_opt( 'email' );
$bs_addr   = bs_opt( 'address' );
$bs_wa     = bs_whatsapp_url();
$bs_appt   = bs_page_url( 'templates/template-appointment.php' );
$bs_social = array( 'facebook', 'twitter', 'instagram', 'linkedin', 'youtube' );
?>
</div><!-- #bs-page -->

<footer class="bg-navy-900 text-navy-200 mt-16 md:mt-24">
	<div class="container-x">
		<div class="-translate-y-10 md:-translate-y-12 rounded-3xl bg-gradient-to-r from-primary-600 to-primary-800 p-7 md:p-10 flex flex-col md:flex-row items-center justify-between gap-6 shadow-lift">
			<div>
				<h3 class="text-2xl md:text-3xl font-extrabold text-white mb-1"><?php esc_html_e( 'Ready to see clearly?', 'bshealthcare' ); ?></h3>
				<p class="text-primary-100"><?php esc_html_e( 'Book a consultation with our specialists today. Walk-ins welcome during OPD hours.', 'bshealthcare' ); ?></p>
			</div>
			<div class="flex flex-col sm:flex-row gap-3 shrink-0">
				<button type="button" class="btn bg-white text-primary-700 hover:bg-primary-50 btn-lg" data-bs-book><?php bs_the_icon( 'calendar', 18 ); ?> <?php esc_html_e( 'Book Appointment', 'bshealthcare' ); ?></button>
				<a href="<?php echo esc_attr( bs_phone_href( $bs_phone ) ); ?>" class="btn border border-white/40 text-white hover:bg-white/10 btn-lg"><?php bs_the_icon( 'phone', 18 ); ?> <?php echo esc_html( $bs_phone ); ?></a>
			</div>
		</div>
	</div>

	<div class="container-x grid gap-10 md:grid-cols-2 lg:grid-cols-12 pb-12">
		<div class="lg:col-span-4">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center gap-2.5 mb-5">
				<span class="w-11 h-11 rounded-xl bg-gradient-to-br from-primary-500 to-primary-700 text-white font-extrabold text-lg flex items-center justify-center"><?php echo esc_html( mb_substr( get_bloginfo( 'name' ), 0, 1 ) ); ?></span>
				<span class="text-lg font-extrabold text-white"><?php bloginfo( 'name' ); ?></span>
			</a>
			<p class="text-navy-300 leading-relaxed mb-6 max-w-sm"><?php echo esc_html( bs_opt( 'footer_description' ) ); ?></p>
			<div class="flex gap-2">
				<?php foreach ( $bs_social as $s ) : $url = bs_opt( 'social_' . $s ); if ( ! $url ) { continue; } ?>
					<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( $s ); ?>" class="w-10 h-10 rounded-xl bg-navy-800 border border-navy-700 flex items-center justify-center text-navy-300 hover:bg-primary-600 hover:border-primary-600 hover:text-white transition-colors"><?php bs_the_icon( $s, 16 ); ?></a>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="lg:col-span-2">
			<h4 class="text-white font-bold mb-5"><?php esc_html_e( 'Quick Links', 'bshealthcare' ); ?></h4>
			<?php if ( has_nav_menu( 'footer-quick' ) ) : ?>
				<?php wp_nav_menu( array( 'theme_location' => 'footer-quick', 'container' => false, 'menu_class' => 'bs-footer-menu', 'depth' => 1 ) ); ?>
			<?php else : ?>
				<ul class="bs-footer-menu">
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'bshealthcare' ); ?></a></li>
					<li><a href="<?php echo esc_url( bs_page_url( 'templates/template-about.php' ) ); ?>"><?php esc_html_e( 'About Us', 'bshealthcare' ); ?></a></li>
					<li><a href="<?php echo esc_url( get_post_type_archive_link( 'bs_service' ) ); ?>"><?php esc_html_e( 'Specialities', 'bshealthcare' ); ?></a></li>
					<li><a href="<?php echo esc_url( get_post_type_archive_link( 'bs_doctor' ) ); ?>"><?php esc_html_e( 'Our Doctors', 'bshealthcare' ); ?></a></li>
					<li><a href="<?php echo esc_url( $bs_appt ); ?>"><?php esc_html_e( 'Book Appointment', 'bshealthcare' ); ?></a></li>
					<li><a href="<?php echo esc_url( bs_page_url( 'templates/template-contact.php' ) ); ?>"><?php esc_html_e( 'Contact', 'bshealthcare' ); ?></a></li>
				</ul>
			<?php endif; ?>
		</div>

		<div class="lg:col-span-3">
			<h4 class="text-white font-bold mb-5"><?php esc_html_e( 'Our Services', 'bshealthcare' ); ?></h4>
			<?php if ( has_nav_menu( 'footer-services' ) ) : ?>
				<?php wp_nav_menu( array( 'theme_location' => 'footer-services', 'container' => false, 'menu_class' => 'bs-footer-menu', 'depth' => 1 ) ); ?>
			<?php else : ?>
				<ul class="bs-footer-menu">
					<?php foreach ( get_posts( array( 'post_type' => 'bs_service', 'numberposts' => 6, 'orderby' => 'menu_order title', 'order' => 'ASC' ) ) as $s ) : ?>
						<li><a href="<?php echo esc_url( get_permalink( $s ) ); ?>"><?php echo esc_html( get_the_title( $s ) ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>

		<div class="lg:col-span-3">
			<h4 class="text-white font-bold mb-5"><?php esc_html_e( 'Contact', 'bshealthcare' ); ?></h4>
			<ul class="space-y-4 text-sm">
				<li class="flex gap-3"><span class="text-primary-400 shrink-0 mt-0.5"><?php bs_the_icon( 'map', 18 ); ?></span><a href="https://www.google.com/maps/search/?api=1&query=<?php echo rawurlencode( $bs_addr ); ?>" target="_blank" rel="noopener" class="text-navy-300 hover:text-white"><?php echo esc_html( $bs_addr ); ?></a></li>
				<li class="flex gap-3"><span class="text-primary-400 shrink-0 mt-0.5"><?php bs_the_icon( 'phone', 18 ); ?></span><a href="<?php echo esc_attr( bs_phone_href( $bs_phone ) ); ?>" class="text-navy-300 hover:text-white"><?php echo esc_html( $bs_phone ); ?></a></li>
				<li class="flex gap-3"><span class="text-primary-400 shrink-0 mt-0.5"><?php bs_the_icon( 'mail', 18 ); ?></span><a href="mailto:<?php echo esc_attr( $bs_email ); ?>" class="text-navy-300 hover:text-white break-all"><?php echo esc_html( $bs_email ); ?></a></li>
				<li class="flex gap-3"><span class="text-primary-400 shrink-0 mt-0.5"><?php bs_the_icon( 'clock', 18 ); ?></span>
					<span class="text-navy-300">OPD: <span class="text-white font-medium"><?php echo esc_html( bs_opt( 'hours_opd' ) ); ?></span><br>Inpatient: <span class="text-white font-medium"><?php echo esc_html( bs_opt( 'hours_inpatient' ) ); ?></span></span>
				</li>
			</ul>
		</div>
	</div>

	<?php if ( bs_opt( 'map_embed' ) ) : ?>
	<div class="container-x pb-12">
		<div class="rounded-2xl overflow-hidden border border-navy-700 h-64 md:h-72 bg-navy-800">
			<iframe title="<?php esc_attr_e( 'Hospital location', 'bshealthcare' ); ?>" src="<?php echo esc_url( bs_opt( 'map_embed' ) ); ?>" width="100%" height="100%" style="border:0;filter:grayscale(20%)" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
		</div>
	</div>
	<?php endif; ?>

	<div class="border-t border-navy-800">
		<div class="container-x py-6 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-navy-400">
			<p><?php echo esc_html( bs_opt( 'footer_copyright' ) ); ?></p>
			<div class="flex items-center gap-5">
				<a href="<?php echo esc_url( bs_page_url( 'templates/template-contact.php' ) ); ?>" class="hover:text-white"><?php esc_html_e( 'Contact', 'bshealthcare' ); ?></a>
				<a href="<?php echo esc_url( bs_page_url( 'templates/template-login.php' ) ); ?>" class="hover:text-white"><?php esc_html_e( 'Patient Portal', 'bshealthcare' ); ?></a>
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( bs_logout_url() ); ?>" class="hover:text-white"><?php esc_html_e( 'Logout', 'bshealthcare' ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</footer>

<!-- Floating actions -->
<button type="button" id="bs-top" aria-label="<?php esc_attr_e( 'Back to top', 'bshealthcare' ); ?>" class="opacity-0 pointer-events-none fixed left-4 bottom-20 md:bottom-6 z-[90] w-11 h-11 rounded-full bg-white border border-navy-100 shadow-card text-navy-700 flex items-center justify-center transition-all hover:bg-primary-600 hover:text-white"><?php bs_the_icon( 'arrow-up', 18 ); ?></button>

<?php if ( $bs_wa ) : ?>
<a href="<?php echo esc_url( $bs_wa ); ?>" target="_blank" rel="noopener" aria-label="WhatsApp" class="fixed right-4 bottom-20 md:bottom-6 z-[90] w-12 h-12 rounded-full bg-[#25D366] text-white shadow-card flex items-center justify-center hover:scale-105 transition-transform"><?php bs_the_icon( 'message', 22 ); ?></a>
<?php endif; ?>

<div class="fixed inset-x-0 bottom-0 z-[85] md:hidden bg-white/95 backdrop-blur border-t border-navy-100 px-4 py-2.5 grid grid-cols-2 gap-3">
	<a href="<?php echo esc_attr( bs_phone_href( $bs_phone ) ); ?>" class="btn-outline py-3"><?php bs_the_icon( 'phone', 16 ); ?> <?php esc_html_e( 'Call Now', 'bshealthcare' ); ?></a>
	<button type="button" class="btn-primary py-3" data-bs-book><?php bs_the_icon( 'calendar', 16 ); ?> <?php esc_html_e( 'Book Now', 'bshealthcare' ); ?></button>
</div>
<div class="h-16 md:hidden" aria-hidden="true"></div>

<?php get_template_part( 'template-parts/booking-modal' ); ?>

<?php if ( '1' === (string) bs_opt( 'ad_enabled' ) && is_front_page() ) : ?>
<div id="bs-ad" class="hidden fixed inset-0 z-[130] items-center justify-center p-4 bg-navy-900/70 backdrop-blur-sm">
	<div class="card max-w-lg w-full overflow-hidden relative">
		<button type="button" id="bs-ad-close" class="absolute top-3 right-3 z-10 w-9 h-9 rounded-full bg-white/90 text-navy-700 flex items-center justify-center shadow-soft" aria-label="<?php esc_attr_e( 'Close', 'bshealthcare' ); ?>"><?php bs_the_icon( 'x', 18 ); ?></button>
		<?php if ( bs_opt( 'ad_image' ) ) : ?>
			<img src="<?php echo esc_url( bs_opt( 'ad_image' ) ); ?>" alt="" class="w-full aspect-[16/9] object-cover">
		<?php endif; ?>
		<div class="p-6 md:p-8 text-center">
			<h3 class="text-2xl font-extrabold text-navy-800 mb-2"><?php echo esc_html( bs_opt( 'ad_title' ) ); ?></h3>
			<p class="text-navy-500 mb-6"><?php echo esc_html( bs_opt( 'ad_text' ) ); ?></p>
			<button type="button" class="btn-primary btn-lg w-full" data-bs-book><?php echo esc_html( bs_opt( 'ad_button' ) ); ?></button>
		</div>
	</div>
</div>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
