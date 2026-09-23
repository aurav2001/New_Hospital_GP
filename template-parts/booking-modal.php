<?php
/**
 * Appointment booking modal (3 steps, AJAX).
 *
 * @package GPHealthcare
 */

$bs_user = wp_get_current_user();
?>
<div id="bs-modal" class="hidden fixed inset-0 z-[140] items-center justify-center p-4">
	<div class="absolute inset-0 bg-navy-900/50 backdrop-blur-sm" data-bs-close></div>
	<div class="relative card w-full max-w-lg max-h-[90vh] overflow-hidden flex flex-col">
		<div class="flex items-center justify-between px-6 py-4 border-b border-navy-100">
			<div>
				<h2 class="font-extrabold text-navy-800 text-lg"><?php esc_html_e( 'Book an Appointment', 'bshealthcare' ); ?></h2>
				<p class="text-xs text-navy-400" id="bs-step-label"><?php esc_html_e( 'Step 1 of 3 · Your details', 'bshealthcare' ); ?></p>
			</div>
			<button type="button" class="w-9 h-9 rounded-lg hover:bg-navy-50 flex items-center justify-center" data-bs-close aria-label="<?php esc_attr_e( 'Close', 'bshealthcare' ); ?>"><?php bs_the_icon( 'x', 20 ); ?></button>
		</div>

		<div class="h-1 bg-navy-100"><div id="bs-progress" class="h-full bg-primary-600 transition-all" style="width:33%"></div></div>

		<form id="bs-book-form" class="flex-1 overflow-y-auto p-6 space-y-4">
			<div id="bs-alert" class="hidden rounded-xl border p-3 text-sm"></div>

			<!-- Step 1 -->
			<div class="bs-step" data-step="1">
				<div class="space-y-4">
					<div>
						<label class="label" for="bs-name"><?php esc_html_e( 'Full name', 'bshealthcare' ); ?></label>
						<input class="input" type="text" id="bs-name" name="name" value="<?php echo esc_attr( is_user_logged_in() ? $bs_user->display_name : '' ); ?>" placeholder="<?php esc_attr_e( 'Your name', 'bshealthcare' ); ?>" required>
					</div>
					<div class="grid sm:grid-cols-2 gap-4">
						<div>
							<label class="label" for="bs-phone"><?php esc_html_e( 'Phone', 'bshealthcare' ); ?></label>
							<input class="input" type="tel" id="bs-phone" name="phone" value="<?php echo esc_attr( is_user_logged_in() ? get_user_meta( $bs_user->ID, 'bs_phone', true ) : '' ); ?>" placeholder="+91 98765 43210" required>
						</div>
						<div>
							<label class="label" for="bs-email"><?php esc_html_e( 'Email', 'bshealthcare' ); ?></label>
							<input class="input" type="email" id="bs-email" name="email" value="<?php echo esc_attr( is_user_logged_in() ? $bs_user->user_email : '' ); ?>" placeholder="you@example.com" required>
						</div>
					</div>
				</div>
			</div>

			<!-- Step 2 -->
			<div class="bs-step hidden" data-step="2">
				<p class="label"><?php esc_html_e( 'Choose a doctor', 'bshealthcare' ); ?></p>
				<div id="bs-doctors" class="space-y-2 max-h-72 overflow-y-auto pr-1">
					<div class="text-sm text-navy-400 flex items-center gap-2"><?php bs_the_icon( 'loader', 16, 'animate-spin' ); ?> <?php esc_html_e( 'Loading doctors…', 'bshealthcare' ); ?></div>
				</div>
				<input type="hidden" name="doctor" id="bs-doctor">
			</div>

			<!-- Step 3 -->
			<div class="bs-step hidden" data-step="3">
				<div class="space-y-4">
					<div>
						<label class="label" for="bs-date"><?php esc_html_e( 'Date', 'bshealthcare' ); ?></label>
						<input class="input" type="date" id="bs-date" name="date" min="<?php echo esc_attr( current_time( 'Y-m-d' ) ); ?>" required>
					</div>
					<div>
						<p class="label"><?php esc_html_e( 'Available time slots', 'bshealthcare' ); ?></p>
						<div id="bs-slots" class="grid grid-cols-3 gap-2 text-sm text-navy-400"><?php esc_html_e( 'Pick a date to see slots.', 'bshealthcare' ); ?></div>
						<input type="hidden" name="time" id="bs-time">
					</div>
					<div class="rounded-xl bg-primary-50/70 border border-primary-100 p-4 text-sm" id="bs-summary"></div>
				</div>
			</div>

			<!-- Success -->
			<div id="bs-success" class="hidden text-center py-6">
				<div class="w-16 h-16 mx-auto rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4"><?php bs_the_icon( 'check-circle', 32 ); ?></div>
				<h3 class="text-xl font-extrabold text-navy-800 mb-2" id="bs-success-title"></h3>
				<p class="text-navy-500 mb-5" id="bs-success-text"></p>
				<div class="rounded-xl bg-navy-50 p-4 text-sm text-left space-y-1" id="bs-success-rows"></div>
				<a href="#" id="bs-success-link" class="btn-primary w-full mt-5"><?php esc_html_e( 'View my appointments', 'bshealthcare' ); ?></a>
			</div>
		</form>

		<div id="bs-modal-actions" class="px-6 py-4 border-t border-navy-100 flex items-center justify-between gap-3">
			<button type="button" id="bs-back" class="btn-ghost invisible"><?php bs_the_icon( 'chevron-left', 16 ); ?> <?php esc_html_e( 'Back', 'bshealthcare' ); ?></button>
			<button type="button" id="bs-next" class="btn-primary"><?php esc_html_e( 'Continue', 'bshealthcare' ); ?> <?php bs_the_icon( 'chevron-right', 16 ); ?></button>
		</div>
	</div>
</div>
