<?php
/**
 * Home – About / what we do (cards come from the first four Specialities).
 *
 * @package GPHealthcare
 */

$services = get_posts( array( 'post_type' => 'bs_service', 'numberposts' => 4, 'orderby' => 'menu_order title', 'order' => 'ASC' ) );
?>
<section class="section">
	<div class="container-x">
		<?php
		bs_section_header(
			array(
				'eyebrow'  => __( 'What We Do', 'bshealthcare' ),
				'title'    => bs_opt( 'about_headline' ),
				'subtitle' => bs_opt( 'about_text' ),
			)
		);
		?>
		<?php if ( $services ) : ?>
		<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
			<?php foreach ( $services as $s ) : ?>
				<a href="<?php echo esc_url( get_permalink( $s ) ); ?>" class="card-hover overflow-hidden group flex flex-col">
					<div class="aspect-[4/3] overflow-hidden bg-navy-50">
						<?php if ( has_post_thumbnail( $s ) ) : ?>
							<?php echo get_the_post_thumbnail( $s, 'bs-card', array( 'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-500', 'loading' => 'lazy' ) ); ?>
						<?php else : ?>
							<span class="w-full h-full flex items-center justify-center text-primary-200"><?php bs_the_icon( 'eye', 56 ); ?></span>
						<?php endif; ?>
					</div>
					<div class="p-5 flex-1 flex flex-col">
						<div class="flex items-start justify-between gap-3 mb-2">
							<h3 class="font-bold text-navy-800 text-lg leading-snug group-hover:text-primary-700"><?php echo esc_html( get_the_title( $s ) ); ?></h3>
							<span class="w-8 h-8 rounded-lg bg-navy-50 text-navy-400 flex items-center justify-center shrink-0 group-hover:bg-primary-600 group-hover:text-white transition-colors"><?php bs_the_icon( 'arrow-up-right', 16 ); ?></span>
						</div>
						<p class="text-sm text-navy-500 leading-relaxed"><?php echo esc_html( get_the_excerpt( $s ) ); ?></p>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	</div>
</section>
