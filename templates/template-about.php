<?php
/**
 * Template Name: About Page
 *
 * @package GPHealthcare
 */

get_header();

$stats = bs_parse_lines( bs_opt( 'stats_items' ), array( 'number', 'label' ) );
$why   = bs_parse_lines( bs_opt( 'why_items' ), array( 'title', 'desc', 'img' ) );
$icons = array( 'award', 'calendar', 'heart', 'shield' );
?>
<main>
	<?php
	get_template_part(
		'template-parts/page-hero',
		null,
		array(
			'eyebrow'  => __( 'About Us', 'bshealthcare' ),
			'title'    => get_the_title(),
			'subtitle' => bs_opt( 'about_text' ),
			'crumbs'   => array( array( get_the_title() ) ),
		)
	);
	?>

	<?php if ( $stats ) : ?>
	<section class="section-tight">
		<div class="container-x">
			<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
				<?php foreach ( $stats as $s ) : ?>
					<div class="card p-6 text-center">
						<p class="text-3xl font-extrabold text-primary-700 mb-1" data-count="<?php echo esc_attr( $s['number'] ); ?>"><?php echo esc_html( $s['number'] ); ?></p>
						<p class="text-sm text-navy-500"><?php echo esc_html( $s['label'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<?php
	while ( have_posts() ) :
		the_post();
		$content = trim( wp_strip_all_tags( get_the_content() ) );
		if ( $content ) :
			?>
			<section class="section">
				<div class="container-x grid lg:grid-cols-12 gap-10">
					<div class="lg:col-span-8 prose-cms"><?php the_content(); ?></div>
					<aside class="lg:col-span-4">
						<div class="card p-7 bg-gradient-to-br from-primary-50 to-white border-primary-100 lg:sticky lg:top-32">
							<span class="icon-tile bg-white mb-5"><?php bs_the_icon( 'heart', 22 ); ?></span>
							<p class="text-lg font-medium text-navy-800 italic leading-relaxed"><?php esc_html_e( 'Striving every day to protect and enhance the gift of sight.', 'bshealthcare' ); ?></p>
							<p class="mt-5 text-xs font-bold uppercase tracking-widest text-primary-700"><?php esc_html_e( 'Our Commitment', 'bshealthcare' ); ?></p>
						</div>
					</aside>
				</div>
			</section>
			<?php
		endif;
	endwhile;
	?>

	<?php if ( $why ) : ?>
	<section class="section bg-navy-50/60">
		<div class="container-x">
			<?php
			bs_section_header(
				array(
					'eyebrow'  => __( 'Why Choose Us', 'bshealthcare' ),
					'title'    => bs_opt( 'why_headline' ),
					'subtitle' => bs_opt( 'why_subtitle' ),
					'align'    => 'center',
				)
			);
			?>
			<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
				<?php foreach ( $why as $i => $w ) : ?>
					<div class="card-hover p-6">
						<span class="icon-tile mb-4"><?php bs_the_icon( $icons[ $i % 4 ], 22 ); ?></span>
						<h3 class="font-bold text-navy-800 text-lg mb-1.5"><?php echo esc_html( $w['title'] ); ?></h3>
						<p class="text-sm text-navy-500 leading-relaxed"><?php echo esc_html( $w['desc'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/sections/doctors' ); ?>
</main>
<?php
get_footer();
