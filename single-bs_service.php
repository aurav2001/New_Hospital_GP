<?php
/**
 * Single speciality / service page.
 *
 * @package GPHealthcare
 */

get_header();
the_post();

$id      = get_the_ID();
$tagline = get_post_meta( $id, '_bs_tagline', true );
$hero_sub = get_post_meta( $id, '_bs_hero_sub', true );
$scope_title  = get_post_meta( $id, '_bs_scope_title', true );
$scope_points = bs_parse_lines( get_post_meta( $id, '_bs_scope_points', true ), array( 'title', 'desc' ) );
$scope_image  = get_post_meta( $id, '_bs_scope_image', true );
$keyword      = strtolower( get_post_meta( $id, '_bs_keyword', true ) );

// Doctors matching this speciality.
$experts = array();
foreach ( get_posts( array( 'post_type' => 'bs_doctor', 'numberposts' => -1, 'orderby' => 'menu_order', 'order' => 'ASC' ) ) as $d ) {
	$hay = strtolower( get_post_meta( $d->ID, '_bs_keyword', true ) . ' ' . get_post_meta( $d->ID, '_bs_role', true ) );
	if ( $keyword && false !== strpos( $hay, $keyword ) ) {
		$experts[] = $d;
	}
}
$related = get_posts( array( 'post_type' => 'bs_service', 'numberposts' => 4, 'post__not_in' => array( $id ), 'orderby' => 'rand' ) );
?>
<main class="bg-white">
	<section class="relative overflow-hidden bg-gradient-to-b from-primary-50/70 to-white">
		<div class="absolute inset-0 bg-dots opacity-50" aria-hidden="true"></div>
		<div class="container-x relative py-10 md:py-16">
			<nav aria-label="<?php esc_attr_e( 'Breadcrumb', 'bshealthcare' ); ?>" class="flex items-center gap-1.5 text-xs text-navy-400 mb-6">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center gap-1 hover:text-primary-700"><?php bs_the_icon( 'home', 12 ); ?> <?php esc_html_e( 'Home', 'bshealthcare' ); ?></a>
				<?php bs_the_icon( 'chevron-right', 12 ); ?>
				<a href="<?php echo esc_url( get_post_type_archive_link( 'bs_service' ) ); ?>" class="hover:text-primary-700"><?php esc_html_e( 'Specialities', 'bshealthcare' ); ?></a>
				<?php bs_the_icon( 'chevron-right', 12 ); ?>
				<span class="text-navy-700 font-medium"><?php the_title(); ?></span>
			</nav>

			<div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
				<div>
					<?php if ( $tagline ) : ?><span class="eyebrow mb-4"><?php echo esc_html( $tagline ); ?></span><?php endif; ?>
					<h1 class="heading-xl text-balance mb-2"><?php the_title(); ?></h1>
					<?php if ( $hero_sub ) : ?><p class="text-xl md:text-2xl text-primary-700 font-semibold mb-5"><?php echo esc_html( $hero_sub ); ?></p><?php endif; ?>
					<p class="lead max-w-lg mb-8"><?php echo esc_html( get_the_excerpt() ); ?></p>

					<div class="flex flex-col sm:flex-row gap-3 mb-8">
						<button type="button" class="btn-primary btn-lg" data-bs-book><?php bs_the_icon( 'calendar', 18 ); ?> <?php esc_html_e( 'Book Appointment', 'bshealthcare' ); ?></button>
						<a href="<?php echo esc_attr( bs_phone_href( bs_opt( 'phone' ) ) ); ?>" class="btn-outline btn-lg"><?php bs_the_icon( 'phone', 18 ); ?> <?php echo esc_html( bs_opt( 'phone' ) ); ?></a>
					</div>

					<div class="grid grid-cols-3 gap-4 pt-6 border-t border-navy-100">
						<span class="flex items-center gap-2 text-sm font-semibold text-navy-700"><span class="text-primary-600"><?php bs_the_icon( 'award', 18 ); ?></span> <?php esc_html_e( 'Expert surgeons', 'bshealthcare' ); ?></span>
						<span class="flex items-center gap-2 text-sm font-semibold text-navy-700"><span class="text-primary-600"><?php bs_the_icon( 'shield', 18 ); ?></span> <?php esc_html_e( 'Safe & proven', 'bshealthcare' ); ?></span>
						<span class="flex items-center gap-2 text-sm font-semibold text-navy-700"><span class="text-primary-600"><?php bs_the_icon( 'clock', 18 ); ?></span> <?php esc_html_e( 'Quick recovery', 'bshealthcare' ); ?></span>
					</div>
				</div>

				<div class="relative">
					<div class="absolute -inset-3 rounded-[2.5rem] bg-gradient-to-br from-primary-200/60 to-transparent -z-10 -rotate-2" aria-hidden="true"></div>
					<div class="rounded-[2rem] overflow-hidden shadow-card aspect-[4/3] bg-navy-50">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-full object-cover' ) ); ?>
						<?php else : ?>
							<span class="w-full h-full flex items-center justify-center text-primary-200"><?php bs_the_icon( 'eye', 96 ); ?></span>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container-x grid lg:grid-cols-12 gap-10 items-start">
			<div class="lg:col-span-7">
				<span class="eyebrow mb-3"><?php esc_html_e( 'Overview', 'bshealthcare' ); ?></span>
				<h2 class="heading-lg mb-5"><?php printf( esc_html__( 'About %s', 'bshealthcare' ), esc_html( get_the_title() ) ); ?></h2>
				<div class="prose-cms"><?php the_content(); ?></div>
				<ul class="mt-8 grid sm:grid-cols-3 gap-3">
					<li class="flex items-center gap-2 rounded-xl bg-navy-50 px-4 py-3 text-sm font-semibold text-navy-700"><span class="text-primary-600 shrink-0"><?php bs_the_icon( 'check-circle', 16 ); ?></span> <?php esc_html_e( 'Advanced technology', 'bshealthcare' ); ?></li>
					<li class="flex items-center gap-2 rounded-xl bg-navy-50 px-4 py-3 text-sm font-semibold text-navy-700"><span class="text-primary-600 shrink-0"><?php bs_the_icon( 'check-circle', 16 ); ?></span> <?php esc_html_e( 'Personalised care', 'bshealthcare' ); ?></li>
					<li class="flex items-center gap-2 rounded-xl bg-navy-50 px-4 py-3 text-sm font-semibold text-navy-700"><span class="text-primary-600 shrink-0"><?php bs_the_icon( 'check-circle', 16 ); ?></span> <?php esc_html_e( 'Experienced team', 'bshealthcare' ); ?></li>
				</ul>
			</div>
			<aside class="lg:col-span-5">
				<div class="card p-6 bg-gradient-to-br from-primary-50 to-white border-primary-100 lg:sticky lg:top-32">
					<h3 class="font-bold text-navy-800 text-lg mb-2"><?php esc_html_e( 'Book a consultation', 'bshealthcare' ); ?></h3>
					<p class="text-sm text-navy-500 mb-5"><?php esc_html_e( 'Speak to a specialist about this treatment and find out if you are a candidate.', 'bshealthcare' ); ?></p>
					<button type="button" class="btn-primary w-full mb-2" data-bs-book><?php bs_the_icon( 'calendar', 16 ); ?> <?php esc_html_e( 'Book Appointment', 'bshealthcare' ); ?></button>
					<a href="<?php echo esc_url( bs_page_url( 'templates/template-contact.php' ) ); ?>" class="btn-outline w-full"><?php esc_html_e( 'Ask a question', 'bshealthcare' ); ?></a>
				</div>
			</aside>
		</div>
	</section>

	<?php if ( $scope_points ) : ?>
	<section class="section bg-navy-50/60">
		<div class="container-x grid lg:grid-cols-2 gap-10 items-center">
			<div>
				<?php bs_section_header( array( 'eyebrow' => __( 'What We Offer', 'bshealthcare' ), 'title' => $scope_title ? $scope_title : __( 'Our Techniques', 'bshealthcare' ) ) ); ?>
				<ol class="space-y-3">
					<?php foreach ( $scope_points as $i => $p ) : ?>
						<li class="card p-4 flex gap-4">
							<span class="w-9 h-9 rounded-lg bg-primary-600 text-white text-sm font-extrabold flex items-center justify-center shrink-0"><?php echo esc_html( str_pad( $i + 1, 2, '0', STR_PAD_LEFT ) ); ?></span>
							<span><span class="block font-bold text-navy-800"><?php echo esc_html( $p['title'] ); ?></span><span class="block text-sm text-navy-500"><?php echo esc_html( $p['desc'] ); ?></span></span>
						</li>
					<?php endforeach; ?>
				</ol>
			</div>
			<?php if ( $scope_image ) : ?>
				<div class="rounded-3xl overflow-hidden aspect-[4/3] lg:aspect-[4/5] bg-navy-100 shadow-card">
					<img src="<?php echo esc_url( $scope_image ); ?>" alt="<?php echo esc_attr( $scope_title ); ?>" loading="lazy" class="w-full h-full object-cover">
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php endif; ?>

	<?php if ( $experts ) : ?>
	<section class="section">
		<div class="container-x">
			<?php bs_section_header( array( 'eyebrow' => __( 'Specialists', 'bshealthcare' ), 'title' => __( 'Meet your care team', 'bshealthcare' ), 'align' => 'center' ) ); ?>
			<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
				<?php foreach ( array_slice( $experts, 0, 4 ) as $d ) : ?>
					<?php get_template_part( 'template-parts/doctor-card', null, array( 'id' => $d->ID ) ); ?>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<?php if ( $related ) : ?>
	<section class="section-tight">
		<div class="container-x">
			<?php bs_section_header( array( 'eyebrow' => __( 'Explore', 'bshealthcare' ), 'title' => __( 'Related specialities', 'bshealthcare' ), 'action' => '<a href="' . esc_url( get_post_type_archive_link( 'bs_service' ) ) . '" class="btn-ghost">' . esc_html__( 'All specialities', 'bshealthcare' ) . '</a>' ) ); ?>
			<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
				<?php foreach ( $related as $s ) : ?>
					<a href="<?php echo esc_url( get_permalink( $s ) ); ?>" class="card-hover p-4 flex items-center gap-3 group">
						<?php if ( has_post_thumbnail( $s ) ) : ?>
							<?php echo get_the_post_thumbnail( $s, 'thumbnail', array( 'class' => 'w-14 h-14 rounded-xl object-cover shrink-0', 'loading' => 'lazy' ) ); ?>
						<?php else : ?>
							<span class="w-14 h-14 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center shrink-0"><?php bs_the_icon( 'eye', 20 ); ?></span>
						<?php endif; ?>
						<span class="min-w-0"><span class="block font-bold text-navy-800 text-sm group-hover:text-primary-700 truncate"><?php echo esc_html( get_the_title( $s ) ); ?></span><span class="block text-xs text-navy-400 line-clamp-2"><?php echo esc_html( get_the_excerpt( $s ) ); ?></span></span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<section class="container-x pb-4">
		<div class="rounded-3xl bg-navy-900 text-white p-8 md:p-14 text-center relative overflow-hidden">
			<div class="absolute -top-20 -right-20 w-80 h-80 rounded-full bg-primary-600/30 blur-3xl" aria-hidden="true"></div>
			<div class="relative max-w-2xl mx-auto">
				<h2 class="text-2xl md:text-4xl font-extrabold text-white mb-3"><?php esc_html_e( 'Ready to restore your vision?', 'bshealthcare' ); ?></h2>
				<p class="text-navy-200 mb-8"><?php esc_html_e( 'Take the first step towards a clearer tomorrow. Our team will guide you through every stage.', 'bshealthcare' ); ?></p>
				<div class="flex flex-col sm:flex-row gap-3 justify-center">
					<button type="button" class="btn bg-white text-navy-900 hover:bg-primary-50 btn-lg" data-bs-book><?php bs_the_icon( 'calendar', 18 ); ?> <?php esc_html_e( 'Book Appointment', 'bshealthcare' ); ?></button>
					<a href="<?php echo esc_url( bs_page_url( 'templates/template-contact.php' ) ); ?>" class="btn border border-white/30 text-white hover:bg-white/10 btn-lg"><?php esc_html_e( 'Ask a question', 'bshealthcare' ); ?></a>
				</div>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
