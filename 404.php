<?php
/**
 * 404.
 *
 * @package BSHealthcare
 */

get_header();
?>
<main class="section">
	<div class="container-x max-w-xl text-center">
		<div class="card p-10">
			<span class="w-16 h-16 mx-auto rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center mb-5"><?php bs_the_icon( 'eye', 28 ); ?></span>
			<p class="text-5xl font-extrabold text-navy-800 mb-2">404</p>
			<h1 class="text-2xl font-extrabold text-navy-800 mb-2"><?php esc_html_e( 'Page not found', 'bshealthcare' ); ?></h1>
			<p class="text-navy-500 mb-6"><?php esc_html_e( 'The page you are looking for has moved or no longer exists.', 'bshealthcare' ); ?></p>
			<div class="flex flex-col sm:flex-row gap-3 justify-center">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-primary"><?php esc_html_e( 'Back to home', 'bshealthcare' ); ?></a>
				<button type="button" class="btn-outline" data-bs-book><?php esc_html_e( 'Book appointment', 'bshealthcare' ); ?></button>
			</div>
		</div>
	</div>
</main>
<?php
get_footer();
