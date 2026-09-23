<?php
/**
 * Template Name: Patient Login / Register
 *
 * @package GPHealthcare
 */

if ( is_user_logged_in() ) {
	wp_safe_redirect( bs_dashboard_url_for_user( wp_get_current_user() ) );
	exit;
}

get_header();

$tab      = isset( $_GET['tab'] ) && 'register' === $_GET['tab'] ? 'register' : 'login'; // phpcs:ignore WordPress.Security.NonceVerification
$msg      = isset( $_GET['bs_msg'] ) ? sanitize_text_field( wp_unslash( $_GET['bs_msg'] ) ) : ''; // phpcs:ignore
$msg_type = isset( $_GET['bs_type'] ) ? sanitize_key( $_GET['bs_type'] ) : 'error'; // phpcs:ignore
$redirect = isset( $_GET['redirect_to'] ) ? esc_url_raw( wp_unslash( $_GET['redirect_to'] ) ) : ''; // phpcs:ignore
?>
<main class="section">
	<div class="container-x max-w-5xl">
		<div class="card overflow-hidden grid lg:grid-cols-2">
			<!-- Info side -->
			<div class="relative bg-navy-900 text-white p-8 md:p-10 flex flex-col justify-center">
				<div class="absolute -top-20 -right-20 w-72 h-72 rounded-full bg-primary-600/30 blur-3xl" aria-hidden="true"></div>
				<div class="relative">
					<span class="eyebrow text-primary-300 mb-4"><?php esc_html_e( 'Patient Portal', 'bshealthcare' ); ?></span>
					<h1 class="text-3xl font-extrabold text-white mb-4"><?php esc_html_e( 'Your eye care, in one place', 'bshealthcare' ); ?></h1>
					<ul class="space-y-3 text-navy-200 text-sm">
						<li class="flex items-start gap-2.5"><span class="text-primary-400 mt-0.5"><?php bs_the_icon( 'check-circle', 16 ); ?></span> <?php esc_html_e( 'Book and manage appointments online', 'bshealthcare' ); ?></li>
						<li class="flex items-start gap-2.5"><span class="text-primary-400 mt-0.5"><?php bs_the_icon( 'check-circle', 16 ); ?></span> <?php esc_html_e( 'View digital prescriptions any time', 'bshealthcare' ); ?></li>
						<li class="flex items-start gap-2.5"><span class="text-primary-400 mt-0.5"><?php bs_the_icon( 'check-circle', 16 ); ?></span> <?php esc_html_e( 'Request a reschedule in one click', 'bshealthcare' ); ?></li>
						<li class="flex items-start gap-2.5"><span class="text-primary-400 mt-0.5"><?php bs_the_icon( 'check-circle', 16 ); ?></span> <?php esc_html_e( 'Keep your full visit history', 'bshealthcare' ); ?></li>
					</ul>
					<p class="mt-8 text-xs text-navy-400"><?php esc_html_e( 'Doctors: log in here with the email address provided by the hospital.', 'bshealthcare' ); ?></p>
				</div>
			</div>

			<!-- Forms -->
			<div class="p-8 md:p-10">
				<div class="flex gap-2 mb-6 p-1 rounded-xl bg-navy-50">
					<a href="<?php echo esc_url( add_query_arg( 'tab', 'login', get_permalink() ) ); ?>" class="flex-1 text-center py-2.5 rounded-lg text-sm font-bold transition-colors <?php echo 'login' === $tab ? 'bg-white text-primary-700 shadow-soft' : 'text-navy-500 hover:text-navy-800'; ?>"><?php esc_html_e( 'Login', 'bshealthcare' ); ?></a>
					<a href="<?php echo esc_url( add_query_arg( 'tab', 'register', get_permalink() ) ); ?>" class="flex-1 text-center py-2.5 rounded-lg text-sm font-bold transition-colors <?php echo 'register' === $tab ? 'bg-white text-primary-700 shadow-soft' : 'text-navy-500 hover:text-navy-800'; ?>"><?php esc_html_e( 'Register', 'bshealthcare' ); ?></a>
				</div>

				<?php if ( $msg ) : ?>
					<div class="rounded-xl border p-3 text-sm mb-4 <?php echo 'success' === $msg_type ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-red-200 bg-red-50 text-red-700'; ?>"><?php echo esc_html( $msg ); ?></div>
				<?php endif; ?>

				<?php if ( 'login' === $tab ) : ?>
					<form method="post" class="space-y-4">
						<?php wp_nonce_field( 'bs_auth', 'bs_auth_nonce' ); ?>
						<input type="hidden" name="bs_auth_action" value="login">
						<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect ); ?>">
						<div><label class="label" for="l-email"><?php esc_html_e( 'Email', 'bshealthcare' ); ?></label><input class="input" id="l-email" name="email" type="text" autocomplete="username" required></div>
						<div><label class="label" for="l-pass"><?php esc_html_e( 'Password', 'bshealthcare' ); ?></label><input class="input" id="l-pass" name="password" type="password" autocomplete="current-password" required></div>
						<div class="flex items-center justify-between text-sm">
							<label class="flex items-center gap-2 text-navy-600"><input type="checkbox" name="remember" value="1" class="rounded border-navy-300"> <?php esc_html_e( 'Remember me', 'bshealthcare' ); ?></label>
							<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="text-primary-700 font-semibold"><?php esc_html_e( 'Forgot password?', 'bshealthcare' ); ?></a>
						</div>
						<button type="submit" class="btn-primary btn-lg w-full"><?php esc_html_e( 'Log in', 'bshealthcare' ); ?></button>
					</form>
				<?php else : ?>
					<form method="post" class="space-y-4">
						<?php wp_nonce_field( 'bs_auth', 'bs_auth_nonce' ); ?>
						<input type="hidden" name="bs_auth_action" value="register">
						<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect ); ?>">
						<div><label class="label" for="r-name"><?php esc_html_e( 'Full name', 'bshealthcare' ); ?></label><input class="input" id="r-name" name="name" type="text" required></div>
						<div><label class="label" for="r-email"><?php esc_html_e( 'Email', 'bshealthcare' ); ?></label><input class="input" id="r-email" name="email" type="email" autocomplete="email" required></div>
						<div><label class="label" for="r-phone"><?php esc_html_e( 'Phone', 'bshealthcare' ); ?></label><input class="input" id="r-phone" name="phone" type="tel" placeholder="+91 98765 43210"></div>
						<div><label class="label" for="r-pass"><?php esc_html_e( 'Password', 'bshealthcare' ); ?></label><input class="input" id="r-pass" name="password" type="password" autocomplete="new-password" minlength="6" required></div>
						<button type="submit" class="btn-primary btn-lg w-full"><?php esc_html_e( 'Create account', 'bshealthcare' ); ?></button>
						<p class="text-xs text-navy-400 text-center"><?php esc_html_e( 'By registering you agree to be contacted about your appointments.', 'bshealthcare' ); ?></p>
					</form>
				<?php endif; ?>
			</div>
		</div>
	</div>
</main>
<?php
get_footer();
