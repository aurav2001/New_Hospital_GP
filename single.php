<?php
/**
 * Single blog post.
 *
 * @package GPHealthcare
 */

get_header();
the_post();
$blog_url = get_permalink( get_option( 'page_for_posts' ) );
$cats     = get_the_category();
?>
<main class="pb-16">
	<article>
		<header class="bg-navy-50/60 border-b border-navy-100">
			<div class="container-x max-w-4xl py-10 md:py-14">
				<?php if ( $blog_url ) : ?>
					<a href="<?php echo esc_url( $blog_url ); ?>" class="inline-flex items-center gap-2 text-sm text-navy-500 hover:text-primary-700 mb-6"><?php bs_the_icon( 'chevron-left', 16 ); ?> <?php esc_html_e( 'Back to all articles', 'bshealthcare' ); ?></a>
				<?php endif; ?>
				<?php if ( $cats ) : ?><span class="inline-flex rounded-full bg-primary-50 text-primary-700 text-xs font-bold px-3 py-1 mb-4"><?php echo esc_html( $cats[0]->name ); ?></span><?php endif; ?>
				<h1 class="text-3xl md:text-5xl font-extrabold text-navy-800 leading-tight text-balance mb-4"><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?><p class="lead mb-6"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
				<div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-navy-500">
					<span class="inline-flex items-center gap-2"><span class="w-8 h-8 rounded-full bg-navy-800 text-white flex items-center justify-center text-xs font-bold"><?php echo esc_html( mb_substr( get_the_author(), 0, 1 ) ); ?></span><span class="font-semibold text-navy-800"><?php the_author(); ?></span></span>
					<span class="inline-flex items-center gap-1.5"><?php bs_the_icon( 'calendar', 14 ); ?> <?php echo esc_html( get_the_date( 'j F Y' ) ); ?></span>
				</div>
			</div>
		</header>

		<div class="container-x max-w-5xl">
			<div class="rounded-3xl overflow-hidden aspect-[16/9] bg-navy-100 shadow-card mt-8">
				<?php if ( has_post_thumbnail() ) : ?>
					<?php the_post_thumbnail( 'full', array( 'class' => 'w-full h-full object-cover' ) ); ?>
				<?php else : ?>
					<img src="<?php echo esc_url( bs_post_image_url( get_the_ID() ) ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover">
				<?php endif; ?>
			</div>
		</div>

		<div class="container-x max-w-3xl py-10 md:py-14">
			<div class="prose-cms"><?php the_content(); ?></div>

			<?php if ( get_the_tags() ) : ?>
				<div class="flex flex-wrap items-center gap-2 mt-8">
					<?php foreach ( get_the_tags() as $t ) : ?>
						<a href="<?php echo esc_url( get_tag_link( $t ) ); ?>" class="rounded-full bg-navy-50 border border-navy-100 px-3 py-1 text-xs font-semibold text-navy-600 hover:bg-primary-50 hover:text-primary-700">#<?php echo esc_html( $t->name ); ?></a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<footer class="mt-10 pt-6 border-t border-navy-100 flex items-center justify-between">
				<button type="button" class="btn-outline" data-share><?php esc_html_e( 'Share', 'bshealthcare' ); ?></button>
				<button type="button" class="btn-ghost" onclick="window.print()"><?php bs_the_icon( 'printer', 16 ); ?> <?php esc_html_e( 'Print', 'bshealthcare' ); ?></button>
			</footer>
		</div>
	</article>

	<?php
	$related = get_posts( array( 'numberposts' => 3, 'post__not_in' => array( get_the_ID() ), 'category__in' => wp_get_post_categories( get_the_ID() ) ) );
	if ( $related ) :
		?>
		<section class="section-tight bg-navy-50/60">
			<div class="container-x">
				<?php bs_section_header( array( 'eyebrow' => __( 'Keep reading', 'bshealthcare' ), 'title' => __( 'Related articles', 'bshealthcare' ) ) ); ?>
				<div class="grid md:grid-cols-3 gap-5">
					<?php foreach ( $related as $p ) : ?>
						<a href="<?php echo esc_url( get_permalink( $p ) ); ?>" class="card-hover overflow-hidden group">
							<div class="aspect-[16/10] bg-navy-100 overflow-hidden">
								<?php if ( has_post_thumbnail( $p ) ) : ?>
									<?php echo get_the_post_thumbnail( $p, 'bs-card', array( 'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-500', 'loading' => 'lazy' ) ); ?>
								<?php else : ?>
									<img src="<?php echo esc_url( bs_post_image_url( $p->ID ) ); ?>" alt="<?php echo esc_attr( get_the_title( $p ) ); ?>" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
								<?php endif; ?>
							</div>
							<div class="p-5"><h3 class="font-bold text-navy-800 group-hover:text-primary-700 line-clamp-2"><?php echo esc_html( get_the_title( $p ) ); ?></h3></div>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>
</main>
<?php
get_footer();
