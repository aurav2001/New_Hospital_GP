<?php
/**
 * Specialities archive.
 *
 * @package BSHealthcare
 */

get_header();

$steps = array(
	array( '01', __( 'Comprehensive Exam', 'bshealthcare' ), __( 'Computer-assisted testing and detailed examination to build a complete picture of your eye health.', 'bshealthcare' ) ),
	array( '02', __( 'Personalised Plan', 'bshealthcare' ), __( 'No two eyes are alike. Your treatment is tailored to your condition and lifestyle.', 'bshealthcare' ) ),
	array( '03', __( 'Precision Treatment', 'bshealthcare' ), __( 'Stitchless, painless procedures using modern Phaco and laser technology.', 'bshealthcare' ) ),
	array( '04', __( 'Follow-up Care', 'bshealthcare' ), __( 'We track your recovery long-term so your results stay stable for years.', 'bshealthcare' ) ),
);
?>
<main>
	<?php
	get_template_part(
		'template-parts/page-hero',
		null,
		array(
			'eyebrow'  => __( 'Specialities', 'bshealthcare' ),
			'title'    => __( 'Precision in every procedure', 'bshealthcare' ),
			'subtitle' => bs_opt( 'services_subtitle' ),
			'crumbs'   => array( array( __( 'Specialities', 'bshealthcare' ) ) ),
		)
	);
	?>

	<section class="section">
		<div class="container-x">
			<?php if ( have_posts() ) : ?>
				<div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<a href="<?php the_permalink(); ?>" class="card-hover overflow-hidden group flex flex-col">
							<div class="relative aspect-[16/10] overflow-hidden bg-navy-50">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'bs-card', array( 'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-500', 'loading' => 'lazy' ) ); ?>
								<?php else : ?>
									<span class="w-full h-full flex items-center justify-center text-primary-200"><?php bs_the_icon( 'eye', 48 ); ?></span>
								<?php endif; ?>
							</div>
							<div class="p-5 flex-1 flex flex-col">
								<h2 class="font-bold text-navy-800 text-lg mb-1.5 group-hover:text-primary-700"><?php the_title(); ?></h2>
								<p class="text-sm text-navy-500 leading-relaxed line-clamp-3"><?php echo esc_html( get_the_excerpt() ); ?></p>
								<span class="mt-4 text-sm font-semibold text-primary-700 inline-flex items-center gap-1"><?php esc_html_e( 'Learn more', 'bshealthcare' ); ?> <?php bs_the_icon( 'chevron-right', 14 ); ?></span>
							</div>
						</a>
					<?php endwhile; ?>
				</div>
			<?php else : ?>
				<div class="card p-12 text-center text-navy-500"><?php esc_html_e( 'No specialities published yet.', 'bshealthcare' ); ?></div>
			<?php endif; ?>
		</div>
	</section>

	<section class="section bg-navy-50/60">
		<div class="container-x">
			<?php bs_section_header( array( 'eyebrow' => __( 'How It Works', 'bshealthcare' ), 'title' => __( 'Your journey to clear vision', 'bshealthcare' ), 'align' => 'center' ) ); ?>
			<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
				<?php foreach ( $steps as $i => $s ) : ?>
					<div class="card-hover p-6 relative">
						<span class="text-4xl font-extrabold text-primary-100 absolute top-4 right-5"><?php echo esc_html( $s[0] ); ?></span>
						<span class="w-10 h-10 rounded-xl bg-primary-600 text-white font-extrabold text-sm flex items-center justify-center mb-5"><?php echo esc_html( $i + 1 ); ?></span>
						<h3 class="font-bold text-navy-800 text-lg mb-2"><?php echo esc_html( $s[1] ); ?></h3>
						<p class="text-sm text-navy-500 leading-relaxed"><?php echo esc_html( $s[2] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
