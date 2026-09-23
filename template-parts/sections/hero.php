<?php
/**
 * Home – hero with an inline appointment form.
 *
 * @package GPHealthcare
 */

$highlights = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', bs_opt( 'hero_highlights' ) ) ) );
$phone      = bs_opt( 'phone' );
$secondary  = bs_opt( 'hero_secondary' );
$hero_image = bs_opt( 'hero_image' );
$doctors    = get_posts( array( 'post_type' => 'bs_doctor', 'numberposts' => -1, 'orderby' => 'menu_order title', 'order' => 'ASC' ) );
$user       = wp_get_current_user();
// The hero carries its own stats strip, unless the standalone `stats` section is
// also enabled – otherwise the same numbers would appear twice on the page.
$sections   = array_map( 'trim', explode( ',', bs_opt( 'home_sections' ) ) );
$stats      = in_array( 'stats', $sections, true )
	? array()
	: array_slice( bs_parse_lines( bs_opt( 'stats_items' ), array( 'number', 'label' ) ), 0, 4 );
// 0 = photo untouched, 90 = almost solid. Kept within a readable range.
$overlay    = min( 90, max( 0, (int) bs_opt( 'hero_overlay' ) ) ) / 100;
?>
<section class="relative overflow-hidden bg-navy-900">
	<!-- Backdrop: photo, then a scrim dark enough to keep the white text readable. -->
	<?php if ( $hero_image ) : ?>
		<img src="<?php echo esc_url( $hero_image ); ?>" alt="" aria-hidden="true" class="absolute inset-0 w-full h-full object-cover" fetchpriority="high">
		<div class="absolute inset-0" style="background-color: rgba(6, 18, 32, <?php echo esc_attr( $overlay ); ?>);" aria-hidden="true"></div>
		<?php /* Narrow screens darken top-down (text sits on top); wide screens darken left-to-right so the photo stays visible on the right. */ ?>
		<div class="absolute inset-0 bg-gradient-to-b from-navy-950/45 via-transparent to-navy-950/25 lg:bg-gradient-to-r lg:from-navy-950 lg:via-navy-950/65 lg:to-transparent" aria-hidden="true"></div>
		<div class="absolute inset-0 bg-gradient-to-t from-primary-950/50 via-transparent to-transparent" aria-hidden="true"></div>
	<?php else : ?>
		<div class="absolute inset-0 bg-gradient-to-br from-navy-900 via-navy-900 to-primary-900" aria-hidden="true"></div>
	<?php endif; ?>
	<div class="absolute inset-0 bg-dots opacity-[0.12]" aria-hidden="true"></div>
	<div class="absolute -top-40 -right-40 w-[600px] h-[600px] rounded-full bg-primary-500/20 blur-3xl" aria-hidden="true"></div>

	<div class="container-x relative py-14 md:py-20 lg:py-24">
		<?php /* On mobile these stack as: headline → booking form → highlights/trust. */ ?>
		<div class="grid lg:grid-cols-12 lg:grid-rows-[auto_auto] gap-x-12 gap-y-9 items-start">

			<!-- Headline -->
			<div class="lg:col-span-7 lg:col-start-1 lg:row-start-1">
				<span class="inline-flex items-center gap-2 rounded-full bg-white/10 border border-white/15 backdrop-blur px-3.5 py-1.5 text-xs font-bold text-primary-200 mb-6">
					<?php bs_the_icon( 'shield', 14 ); ?> <?php echo esc_html( bs_opt( 'hero_eyebrow' ) ); ?>
				</span>

				<h1 class="text-4xl sm:text-5xl lg:text-[3.75rem] font-extrabold leading-[1.05] text-white text-balance mb-6">
					<?php echo wp_kses_post( str_replace( 'text-primary-600', 'text-primary-400', bs_hero_title_html( bs_opt( 'hero_title' ) ) ) ); ?>
				</h1>

				<p class="text-base md:text-lg text-navy-200 leading-relaxed max-w-xl"><?php echo esc_html( bs_opt( 'hero_subtitle' ) ); ?></p>
			</div>

			<!-- Inline booking form -->
			<div class="lg:col-span-5 lg:col-start-8 lg:row-start-1 lg:row-span-2">
				<div class="card p-6 md:p-7 shadow-2xl relative">
					<span class="absolute -top-3 right-6 rounded-full bg-emerald-500 text-white text-[11px] font-bold px-3 py-1 shadow-lift"><?php esc_html_e( 'Free consultation slot', 'bshealthcare' ); ?></span>

					<div class="flex items-start gap-3 mb-5">
						<span class="icon-tile"><?php bs_the_icon( 'calendar', 22 ); ?></span>
						<div>
							<h2 class="text-xl font-extrabold text-navy-800 leading-tight"><?php esc_html_e( 'Book an Appointment', 'bshealthcare' ); ?></h2>
							<p class="text-xs text-navy-400"><?php esc_html_e( 'Pick a doctor and time — confirmation in 2 minutes.', 'bshealthcare' ); ?></p>
						</div>
					</div>

					<!-- Success -->
					<div id="bs-hf-success" class="hidden text-center py-6">
						<div class="w-14 h-14 mx-auto rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3"><?php bs_the_icon( 'check-circle', 28 ); ?></div>
						<h3 class="font-extrabold text-navy-800 text-lg mb-1" id="bs-hf-success-title"></h3>
						<p class="text-sm text-navy-500 mb-4"><?php esc_html_e( 'A confirmation has been sent to your email.', 'bshealthcare' ); ?></p>
						<div class="rounded-xl bg-navy-50 p-3 text-sm text-left space-y-1" id="bs-hf-success-rows"></div>
						<a href="<?php echo esc_url( bs_page_url( 'templates/template-patient-dashboard.php' ) ); ?>" class="btn-primary w-full mt-4"><?php esc_html_e( 'View my appointments', 'bshealthcare' ); ?></a>
					</div>

					<form id="bs-hero-form" class="space-y-3" novalidate>
						<div id="bs-hf-alert" class="hidden rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700"></div>

						<div>
							<label class="label" for="bs-hf-name"><?php esc_html_e( 'Full name', 'bshealthcare' ); ?></label>
							<input class="input" type="text" id="bs-hf-name" name="name" value="<?php echo esc_attr( is_user_logged_in() ? $user->display_name : '' ); ?>" placeholder="<?php esc_attr_e( 'Your name', 'bshealthcare' ); ?>">
						</div>

						<div class="grid grid-cols-2 gap-3">
							<div>
								<label class="label" for="bs-hf-phone"><?php esc_html_e( 'Phone', 'bshealthcare' ); ?></label>
								<input class="input" type="tel" id="bs-hf-phone" name="phone" value="<?php echo esc_attr( is_user_logged_in() ? get_user_meta( $user->ID, 'bs_phone', true ) : '' ); ?>" placeholder="+91 98765 43210">
							</div>
							<div>
								<label class="label" for="bs-hf-email"><?php esc_html_e( 'Email', 'bshealthcare' ); ?></label>
								<input class="input" type="email" id="bs-hf-email" name="email" value="<?php echo esc_attr( is_user_logged_in() ? $user->user_email : '' ); ?>" placeholder="you@example.com">
							</div>
						</div>

						<div>
							<label class="label" for="bs-hf-doctor"><?php esc_html_e( 'Doctor / speciality', 'bshealthcare' ); ?></label>
							<select class="input" id="bs-hf-doctor" name="doctor">
								<option value=""><?php esc_html_e( 'Select a doctor', 'bshealthcare' ); ?></option>
								<?php foreach ( $doctors as $d ) : ?>
									<option value="<?php echo esc_attr( $d->ID ); ?>"><?php echo esc_html( get_the_title( $d ) ); ?><?php $r = get_post_meta( $d->ID, '_bs_role', true ); echo $r ? ' — ' . esc_html( $r ) : ''; ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<div class="grid grid-cols-2 gap-3">
							<div>
								<label class="label" for="bs-hf-date"><?php esc_html_e( 'Date', 'bshealthcare' ); ?></label>
								<input class="input" type="date" id="bs-hf-date" name="date" min="<?php echo esc_attr( current_time( 'Y-m-d' ) ); ?>">
							</div>
							<div>
								<label class="label" for="bs-hf-time"><?php esc_html_e( 'Time', 'bshealthcare' ); ?></label>
								<select class="input" id="bs-hf-time" name="time" disabled>
									<option value=""><?php esc_html_e( 'Pick a date first', 'bshealthcare' ); ?></option>
								</select>
							</div>
						</div>

						<button type="submit" class="btn-primary btn-lg w-full mt-1">
							<?php bs_the_icon( 'calendar', 18 ); ?> <span><?php esc_html_e( 'Book Appointment', 'bshealthcare' ); ?></span>
						</button>

						<p class="flex items-center justify-center gap-4 text-[11px] text-navy-400 pt-1">
							<span class="inline-flex items-center gap-1"><?php bs_the_icon( 'shield', 12 ); ?> <?php esc_html_e( 'Secure', 'bshealthcare' ); ?></span>
							<span class="inline-flex items-center gap-1"><?php bs_the_icon( 'clock', 12 ); ?> <?php esc_html_e( '2 minutes', 'bshealthcare' ); ?></span>
							<span class="inline-flex items-center gap-1"><?php bs_the_icon( 'check-circle', 12 ); ?> <?php esc_html_e( 'Instant confirmation', 'bshealthcare' ); ?></span>
						</p>
					</form>
				</div>
			</div>

			<!-- Highlights, call buttons, trust row -->
			<div class="lg:col-span-7 lg:col-start-1 lg:row-start-2">
				<?php if ( $highlights ) : ?>
					<ul class="flex flex-wrap gap-x-6 gap-y-3 mb-8">
						<?php foreach ( $highlights as $h ) : ?>
							<li class="flex items-center gap-2 text-sm font-medium text-white">
								<span class="w-5 h-5 rounded-full bg-primary-500/20 text-primary-300 flex items-center justify-center shrink-0"><?php bs_the_icon( 'check', 12 ); ?></span>
								<?php echo esc_html( $h ); ?>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<div class="flex flex-wrap items-center gap-3 mb-10">
					<a href="<?php echo esc_attr( bs_phone_href( $phone ) ); ?>" class="btn bg-white text-navy-900 hover:bg-primary-50 btn-lg">
						<?php bs_the_icon( 'phone', 18 ); ?> <?php echo esc_html( $phone ); ?>
					</a>
					<?php if ( $secondary ) : ?>
						<a href="<?php echo esc_url( bs_opt( 'hero_secondary_url' ) ? bs_opt( 'hero_secondary_url' ) : bs_page_url( 'templates/template-about.php' ) ); ?>" class="btn border border-white/25 text-white hover:bg-white/10 btn-lg" <?php echo bs_opt( 'hero_secondary_url' ) ? 'target="_blank" rel="noopener"' : ''; ?>>
							<?php bs_the_icon( 'play', 18, 'fill-current' ); ?> <?php echo esc_html( $secondary ); ?>
						</a>
					<?php endif; ?>
				</div>

				<!-- Trust row -->
				<div class="flex flex-wrap items-center gap-x-8 gap-y-4 pt-7 border-t border-white/10">
					<div class="flex items-center gap-3">
						<span class="w-10 h-10 rounded-xl bg-amber-400/15 text-amber-300 flex items-center justify-center"><?php bs_the_icon( 'star', 18, 'fill-current' ); ?></span>
						<span>
							<span class="block font-bold text-white text-sm leading-tight"><?php echo esc_html( bs_opt( 'hero_rating' ) ); ?></span>
							<span class="block text-xs text-navy-300"><?php echo esc_html( bs_opt( 'hero_rating_sub' ) ); ?></span>
						</span>
					</div>
					<div class="flex items-center gap-3">
						<span class="w-10 h-10 rounded-xl bg-primary-500/20 text-primary-300 flex items-center justify-center"><?php bs_the_icon( 'clock', 18 ); ?></span>
						<span>
							<span class="block font-bold text-white text-sm leading-tight">OPD <?php echo esc_html( bs_opt( 'hours_opd' ) ); ?></span>
							<span class="block text-xs text-navy-300"><?php esc_html_e( 'Inpatient care', 'bshealthcare' ); ?> <?php echo esc_html( bs_opt( 'hours_inpatient' ) ); ?></span>
						</span>
					</div>
					<div class="hidden sm:flex items-center gap-3">
						<span class="w-10 h-10 rounded-xl bg-emerald-400/15 text-emerald-300 flex items-center justify-center"><?php bs_the_icon( 'stethoscope', 18 ); ?></span>
						<span>
							<span class="block font-bold text-white text-sm leading-tight"><?php echo count( $doctors ); ?>+ <?php esc_html_e( 'Specialists', 'bshealthcare' ); ?></span>
							<span class="block text-xs text-navy-300"><?php esc_html_e( 'MBBS, MS Ophthalmology', 'bshealthcare' ); ?></span>
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Stats strip -->
	<?php if ( $stats ) : ?>
		<div class="relative border-t border-white/10 bg-navy-950/40 backdrop-blur">
			<div class="container-x grid grid-cols-2 md:grid-cols-4 divide-x divide-white/10">
				<?php foreach ( $stats as $s ) : ?>
					<div class="py-6 px-4 text-center">
						<p class="text-2xl md:text-3xl font-extrabold text-white" data-count="<?php echo esc_attr( $s['number'] ); ?>"><?php echo esc_html( $s['number'] ); ?></p>
						<p class="text-xs text-navy-300 mt-0.5"><?php echo esc_html( $s['label'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>
</section>
