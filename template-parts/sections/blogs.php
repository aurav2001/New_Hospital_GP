<?php
/**
 * Home – latest blog posts.
 *
 * @package BSHealthcare
 */

$posts_list = get_posts( array( 'numberposts' => 3 ) );
if ( ! $posts_list ) {
	return;
}
$blog_url = get_permalink( get_option( 'page_for_posts' ) );
?>
<section class="section bg-navy-50/60">
	<div class="container-x">
		<?php
		bs_section_header(
			array(
				'eyebrow'  => __( 'Our Blog', 'bshealthcare' ),
				'title'    => bs_opt( 'blogs_headline' ),
				'subtitle' => __( 'Eye health tips, treatment guides and hospital updates from our specialists.', 'bshealthcare' ),
				'action'   => $blog_url ? '<a href="' . esc_url( $blog_url ) . '" class="btn-outline">' . esc_html__( 'View all articles', 'bshealthcare' ) . '</a>' : '',
			)
		);
		?>
		<div class="grid md:grid-cols-3 gap-5">
			<?php foreach ( $posts_list as $p ) : ?>
				<article class="card-hover overflow-hidden group flex flex-col">
					<a href="<?php echo esc_url( get_permalink( $p ) ); ?>" class="block aspect-[16/10] overflow-hidden bg-navy-100 relative">
						<?php if ( has_post_thumbnail( $p ) ) : ?>
							<?php echo get_the_post_thumbnail( $p, 'bs-card', array( 'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-500', 'loading' => 'lazy' ) ); ?>
						<?php else : ?>
							<span class="w-full h-full flex items-center justify-center text-primary-200"><?php bs_the_icon( 'file', 48 ); ?></span>
						<?php endif; ?>
						<?php $cat = get_the_category( $p->ID ); ?>
						<?php if ( $cat ) : ?>
							<span class="absolute top-3 left-3 rounded-full bg-white/95 px-3 py-1 text-[11px] font-bold text-primary-700"><?php echo esc_html( $cat[0]->name ); ?></span>
						<?php endif; ?>
					</a>
					<div class="p-5 flex-1 flex flex-col">
						<div class="flex items-center gap-4 text-xs text-navy-400 mb-3">
							<span class="inline-flex items-center gap-1"><?php bs_the_icon( 'calendar', 13 ); ?> <?php echo esc_html( get_the_date( 'j M Y', $p ) ); ?></span>
							<span class="inline-flex items-center gap-1"><?php bs_the_icon( 'user', 13 ); ?> <?php echo esc_html( get_the_author_meta( 'display_name', $p->post_author ) ); ?></span>
						</div>
						<a href="<?php echo esc_url( get_permalink( $p ) ); ?>"><h3 class="font-bold text-navy-800 text-lg leading-snug mb-2 group-hover:text-primary-700 line-clamp-2"><?php echo esc_html( get_the_title( $p ) ); ?></h3></a>
						<p class="text-sm text-navy-500 line-clamp-2 mb-4"><?php echo esc_html( get_the_excerpt( $p ) ); ?></p>
						<a href="<?php echo esc_url( get_permalink( $p ) ); ?>" class="mt-auto text-sm font-semibold text-primary-700 inline-flex items-center gap-1"><?php esc_html_e( 'Read article', 'bshealthcare' ); ?> <?php bs_the_icon( 'arrow-right', 14 ); ?></a>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
