<?php
/**
 * Blog index / fallback archive.
 *
 * @package GPHealthcare
 */

get_header();

$title = __( 'Eye health insights', 'bshealthcare' );
$sub   = __( 'Guides, tips and news from our ophthalmologists to help you take care of your vision.', 'bshealthcare' );
$crumb = __( 'Blog', 'bshealthcare' );

if ( is_category() ) {
	$title = single_cat_title( '', false );
	$sub   = wp_strip_all_tags( category_description() );
	$crumb = $title;
} elseif ( is_tag() ) {
	/* translators: %s: tag name */
	$title = sprintf( __( 'Articles tagged “%s”', 'bshealthcare' ), single_tag_title( '', false ) );
	$crumb = single_tag_title( '', false );
} elseif ( is_search() ) {
	/* translators: %s: search term */
	$title = sprintf( __( 'Search results for “%s”', 'bshealthcare' ), get_search_query() );
	$sub   = '';
	$crumb = __( 'Search', 'bshealthcare' );
} elseif ( is_home() && get_option( 'page_for_posts' ) ) {
	$title = get_the_title( get_option( 'page_for_posts' ) );
}
?>
<main>
	<?php
	get_template_part(
		'template-parts/page-hero',
		null,
		array(
			'eyebrow'  => __( 'Health Journal', 'bshealthcare' ),
			'title'    => $title,
			'subtitle' => $sub,
			'crumbs'   => array( array( $crumb ) ),
		)
	);
	?>

	<section class="section">
		<div class="container-x">
			<?php if ( have_posts() ) : ?>
				<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<article class="card-hover overflow-hidden flex flex-col group">
							<a href="<?php the_permalink(); ?>" class="block aspect-[16/10] bg-navy-100 overflow-hidden relative">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'bs-card', array( 'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-500', 'loading' => 'lazy' ) ); ?>
								<?php else : ?>
									<span class="w-full h-full flex items-center justify-center text-primary-200"><?php bs_the_icon( 'file', 48 ); ?></span>
								<?php endif; ?>
								<?php $cat = get_the_category(); ?>
								<?php if ( $cat ) : ?><span class="absolute top-3 left-3 rounded-full bg-white/95 px-3 py-1 text-[11px] font-bold text-primary-700"><?php echo esc_html( $cat[0]->name ); ?></span><?php endif; ?>
							</a>
							<div class="p-5 flex-1 flex flex-col">
								<a href="<?php the_permalink(); ?>"><h2 class="font-bold text-navy-800 text-lg leading-snug mb-2 group-hover:text-primary-700 line-clamp-2"><?php the_title(); ?></h2></a>
								<p class="text-sm text-navy-500 line-clamp-2 mb-4"><?php echo esc_html( get_the_excerpt() ); ?></p>
								<div class="mt-auto pt-4 border-t border-navy-100 flex items-center justify-between text-xs text-navy-400">
									<span class="inline-flex items-center gap-1.5"><span class="w-6 h-6 rounded-full bg-navy-800 text-white flex items-center justify-center text-[10px] font-bold"><?php echo esc_html( mb_substr( get_the_author(), 0, 1 ) ); ?></span><span class="font-semibold text-navy-700"><?php the_author(); ?></span></span>
									<span><?php echo esc_html( get_the_date( 'j M Y' ) ); ?></span>
								</div>
							</div>
						</article>
					<?php endwhile; ?>
				</div>

				<div class="mt-10 bs-pagination">
					<?php
					the_posts_pagination(
						array(
							'mid_size'  => 1,
							'prev_text' => esc_html__( 'Previous', 'bshealthcare' ),
							'next_text' => esc_html__( 'Next', 'bshealthcare' ),
						)
					);
					?>
				</div>
			<?php else : ?>
				<div class="card p-16 text-center">
					<p class="text-navy-500 mb-5"><?php esc_html_e( 'No articles found.', 'bshealthcare' ); ?></p>
					<?php get_search_form(); ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>
<?php
get_footer();
