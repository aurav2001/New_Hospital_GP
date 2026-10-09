<?php
/**
 * Site header.
 *
 * @package GPHealthcare
 */

$bs_phone   = bs_opt( 'phone' );
$bs_email   = bs_opt( 'email' );
$bs_social  = array( 'facebook', 'twitter', 'instagram', 'linkedin', 'youtube' );
$bs_user    = wp_get_current_user();
$bs_dash    = is_user_logged_in() ? bs_dashboard_url_for_user( $bs_user ) : bs_page_url( 'templates/template-login.php' );
$bs_appt    = bs_page_url( 'templates/template-appointment.php' );
$bs_logo_id  = (int) get_theme_mod( 'custom_logo' );
$bs_services = get_posts( array( 'post_type' => 'bs_service', 'numberposts' => 8, 'orderby' => 'menu_order title', 'order' => 'ASC' ) );
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header id="bs-header" class="fixed top-0 inset-x-0 z-50">
	<!-- Top bar -->
	<div class="hidden md:block bg-navy-900 text-navy-200 text-xs">
		<div class="container-x h-10 flex items-center justify-between gap-6">
			<div class="flex items-center gap-6">
				<a href="<?php echo esc_attr( bs_phone_href( $bs_phone ) ); ?>" class="flex items-center gap-2 hover:text-white"><?php bs_the_icon( 'phone', 13, 'text-primary-400' ); ?> <?php echo esc_html( $bs_phone ); ?></a>
				<a href="mailto:<?php echo esc_attr( $bs_email ); ?>" class="hidden lg:flex items-center gap-2 hover:text-white"><?php bs_the_icon( 'mail', 13, 'text-primary-400' ); ?> <?php echo esc_html( $bs_email ); ?></a>
				<span class="flex items-center gap-2"><?php bs_the_icon( 'clock', 13, 'text-primary-400' ); ?> OPD: <?php echo esc_html( bs_opt( 'hours_opd' ) ); ?></span>
			</div>
			<div class="flex items-center gap-5">
				<?php if ( bs_opt( 'emergency' ) ) : ?>
					<a href="<?php echo esc_attr( bs_phone_href( bs_opt( 'emergency' ) ) ); ?>" class="flex items-center gap-2 font-semibold text-red-300 hover:text-red-200"><span class="relative flex h-2 w-2"><span class="absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75 animate-ping"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span></span> Emergency: <?php echo esc_html( bs_opt( 'emergency' ) ); ?></a>
				<?php endif; ?>
				<a href="<?php echo esc_url( $bs_dash ); ?>" class="hover:text-white"><?php echo is_user_logged_in() ? esc_html__( 'My Dashboard', 'bshealthcare' ) : esc_html__( 'Patient Portal', 'bshealthcare' ); ?></a>
				<div class="flex items-center gap-1 border-l border-navy-700 pl-4">
					<?php foreach ( $bs_social as $s ) : $url = bs_opt( 'social_' . $s ); if ( ! $url ) { continue; } ?>
						<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( $s ); ?>" class="w-7 h-7 rounded-full flex items-center justify-center hover:bg-navy-700 hover:text-white"><?php bs_the_icon( $s, 13 ); ?></a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>

	<!-- Navbar -->
	<nav id="bs-nav" class="bg-white border-b border-navy-100 transition-shadow">
		<div class="container-x h-16 lg:h-[72px] flex items-center justify-between gap-4">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center gap-2.5 min-w-0" rel="home">
				<?php if ( $bs_logo_id ) : ?>
					<?php
					// Print the image only – the_custom_logo() wraps it in its own <a>,
					// and a nested anchor breaks this link's layout.
					echo wp_get_attachment_image(
						$bs_logo_id,
						'full',
						false,
						array(
							'class' => 'h-10 lg:h-12 w-auto object-contain shrink-0',
							'alt'   => esc_attr( get_bloginfo( 'name' ) ),
						)
					);
					?>
				<?php else : ?>
					<img src="<?php echo esc_url( BS_URI . '/assets/img/logo.svg' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="h-12 lg:h-14 w-auto object-contain shrink-0" />
				<?php endif; ?>
				<span class="sr-only"><?php bloginfo( 'name' ); ?></span>
			</a>

			<div class="hidden lg:flex items-center gap-1 bs-nav-links">
				<?php
				$primary_nav_html = '';
				if ( has_nav_menu( 'primary' ) ) {
					$primary_nav_html = wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'container'      => false,
							'menu_class'     => 'bs-menu',
							'depth'          => 1,
							'echo'           => false,
							'fallback_cb'    => false,
						)
					);
				}
				if ( ! empty( $primary_nav_html ) && false !== strpos( $primary_nav_html, '<li' ) ) {
					echo $primary_nav_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				} else {
					bs_fallback_menu( 'bs-menu' );
				}
				?>
				<!-- Mega menu (attached by JS to .has-mega) -->
				<div id="bs-mega" class="hidden absolute left-1/2 -translate-x-1/2 top-full pt-3 w-[720px]">
					<div class="bg-white rounded-2xl border border-navy-100 shadow-card p-5">
						<div class="grid grid-cols-2 gap-1.5">
							<?php foreach ( $bs_services as $s ) : ?>
								<a href="<?php echo esc_url( get_permalink( $s ) ); ?>" class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-primary-50 group">
									<?php if ( has_post_thumbnail( $s ) ) : ?>
										<?php echo get_the_post_thumbnail( $s, 'thumbnail', array( 'class' => 'w-11 h-11 rounded-lg object-cover shrink-0' ) ); ?>
									<?php else : ?>
										<img src="<?php echo esc_url( bs_service_image_url( $s->ID ) ); ?>" alt="<?php echo esc_attr( get_the_title( $s ) ); ?>" class="w-11 h-11 rounded-lg object-cover shrink-0" loading="lazy">
									<?php endif; ?>
									<span class="min-w-0">
										<span class="block text-sm font-semibold text-navy-800 group-hover:text-primary-700 truncate"><?php echo esc_html( get_the_title( $s ) ); ?></span>
										<span class="block text-xs text-navy-400 line-clamp-1"><?php echo esc_html( get_the_excerpt( $s ) ); ?></span>
									</span>
								</a>
							<?php endforeach; ?>
						</div>
						<div class="mt-4 pt-4 border-t border-navy-100 flex items-center justify-between">
							<p class="text-xs text-navy-400"><?php esc_html_e( 'Comprehensive eye care, from routine check-ups to advanced surgery.', 'bshealthcare' ); ?></p>
							<a href="<?php echo esc_url( get_post_type_archive_link( 'bs_service' ) ); ?>" class="text-sm font-semibold text-primary-700 inline-flex items-center gap-1"><?php esc_html_e( 'View all specialities', 'bshealthcare' ); ?> <?php bs_the_icon( 'arrow-right', 14 ); ?></a>
						</div>
					</div>
				</div>
			</div>

			<div class="flex items-center gap-2">
				<a href="<?php echo esc_url( $bs_dash ); ?>" class="hidden lg:inline-flex btn-outline px-4 whitespace-nowrap"><?php bs_the_icon( 'user', 16 ); ?> <?php echo is_user_logged_in() ? esc_html__( 'Dashboard', 'bshealthcare' ) : esc_html__( 'Login', 'bshealthcare' ); ?></a>
				<button type="button" class="hidden md:inline-flex btn-primary whitespace-nowrap" data-bs-book><?php bs_the_icon( 'calendar', 16 ); ?> <?php esc_html_e( 'Book Appointment', 'bshealthcare' ); ?></button>
				<button type="button" id="bs-menu-toggle" aria-label="<?php esc_attr_e( 'Toggle menu', 'bshealthcare' ); ?>" class="lg:hidden w-10 h-10 rounded-lg text-navy-700 hover:bg-navy-50 flex items-center justify-center"><?php bs_the_icon( 'menu', 22 ); ?></button>
			</div>
		</div>
	</nav>
</header>
<div class="h-16 lg:h-[72px] md:mt-10" aria-hidden="true"></div>

<!-- Mobile drawer -->
<div id="bs-drawer-overlay" class="hidden fixed inset-0 bg-navy-900/40 backdrop-blur-sm z-[110] lg:hidden"></div>
<aside id="bs-drawer" class="fixed top-0 right-0 bottom-0 w-[85%] max-w-sm bg-white z-[120] lg:hidden flex flex-col shadow-2xl translate-x-full transition-transform duration-300">
	<div class="flex items-center justify-between px-5 h-16 border-b border-navy-100">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center" rel="home">
			<?php if ( $bs_logo_id ) : ?>
				<?php
				echo wp_get_attachment_image(
					$bs_logo_id,
					'full',
					false,
					array(
						'class' => 'h-8 w-auto object-contain',
						'alt'   => esc_attr( get_bloginfo( 'name' ) ),
					)
				);
				?>
			<?php else : ?>
				<img src="<?php echo esc_url( BS_URI . '/assets/img/logo.svg' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="h-8 w-auto object-contain" />
			<?php endif; ?>
			<span class="sr-only"><?php bloginfo( 'name' ); ?></span>
		</a>
		<button type="button" id="bs-drawer-close" aria-label="<?php esc_attr_e( 'Close menu', 'bshealthcare' ); ?>" class="w-10 h-10 rounded-lg hover:bg-navy-50 flex items-center justify-center"><?php bs_the_icon( 'x', 20 ); ?></button>
	</div>
	<div class="flex-1 overflow-y-auto px-3 py-4 bs-drawer-links">
		<?php
		$drawer_nav_html = '';
		if ( has_nav_menu( 'primary' ) ) {
			$drawer_nav_html = wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'bs-menu-mobile',
					'depth'          => 1,
					'echo'           => false,
					'fallback_cb'    => false,
				)
			);
		}
		if ( ! empty( $drawer_nav_html ) && false !== strpos( $drawer_nav_html, '<li' ) ) {
			echo $drawer_nav_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			bs_fallback_menu( 'bs-menu-mobile' );
		}
		?>
		<div class="mt-3 pt-3 border-t border-navy-100">
			<p class="px-3 text-[11px] font-bold uppercase tracking-wider text-navy-400 mb-1"><?php esc_html_e( 'Specialities', 'bshealthcare' ); ?></p>
			<?php foreach ( $bs_services as $s ) : ?>
				<a href="<?php echo esc_url( get_permalink( $s ) ); ?>" class="block px-3 py-2 text-sm text-navy-600 rounded-lg hover:bg-primary-50 hover:text-primary-700"><?php echo esc_html( get_the_title( $s ) ); ?></a>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="p-4 border-t border-navy-100 space-y-2.5">
		<button type="button" class="btn-primary w-full" data-bs-book><?php bs_the_icon( 'calendar', 16 ); ?> <?php esc_html_e( 'Book Appointment', 'bshealthcare' ); ?></button>
		<a href="<?php echo esc_url( $bs_dash ); ?>" class="btn-outline w-full"><?php bs_the_icon( 'user', 16 ); ?> <?php echo is_user_logged_in() ? esc_html__( 'My Dashboard', 'bshealthcare' ) : esc_html__( 'Patient Login', 'bshealthcare' ); ?></a>
		<a href="<?php echo esc_attr( bs_phone_href( $bs_phone ) ); ?>" class="flex items-center justify-center gap-2 text-sm text-navy-500 pt-1"><?php bs_the_icon( 'phone', 14 ); ?> <?php echo esc_html( $bs_phone ); ?></a>
	</div>
</aside>

<div id="bs-page" class="min-h-screen flex flex-col">
