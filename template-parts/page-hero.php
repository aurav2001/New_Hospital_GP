<?php
/**
 * Inner page banner with breadcrumb.
 *
 * @package GPHealthcare
 * @var array $args ['eyebrow','title','subtitle','crumbs' => [ [label, url|null] ]]
 */

$a = wp_parse_args( $args, array( 'eyebrow' => '', 'title' => '', 'subtitle' => '', 'crumbs' => array() ) );
?>
<section class="relative overflow-hidden bg-navy-900 text-white">
	<div class="absolute inset-0 bg-dots opacity-20" aria-hidden="true"></div>
	<div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-primary-600/30 blur-3xl" aria-hidden="true"></div>
	<div class="container-x relative py-14 md:py-20">
		<nav aria-label="<?php esc_attr_e( 'Breadcrumb', 'bshealthcare' ); ?>" class="flex items-center gap-1.5 text-xs text-navy-300 mb-5 flex-wrap">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center gap-1 hover:text-white"><?php bs_the_icon( 'home', 12 ); ?> <?php esc_html_e( 'Home', 'bshealthcare' ); ?></a>
			<?php foreach ( $a['crumbs'] as $c ) : ?>
				<span class="inline-flex items-center gap-1.5">
					<?php bs_the_icon( 'chevron-right', 12 ); ?>
					<?php if ( ! empty( $c[1] ) ) : ?>
						<a href="<?php echo esc_url( $c[1] ); ?>" class="hover:text-white"><?php echo esc_html( $c[0] ); ?></a>
					<?php else : ?>
						<span class="text-white"><?php echo esc_html( $c[0] ); ?></span>
					<?php endif; ?>
				</span>
			<?php endforeach; ?>
		</nav>
		<?php if ( $a['eyebrow'] ) : ?><span class="eyebrow text-primary-300 mb-3"><?php echo esc_html( $a['eyebrow'] ); ?></span><?php endif; ?>
		<h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-white text-balance mb-3"><?php echo esc_html( $a['title'] ); ?></h1>
		<?php if ( $a['subtitle'] ) : ?><p class="text-navy-200 text-base md:text-lg max-w-2xl"><?php echo esc_html( $a['subtitle'] ); ?></p><?php endif; ?>
	</div>
</section>
