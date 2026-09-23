<?php
/**
 * Home – stats band.
 *
 * @package BSHealthcare
 */

$stats = bs_parse_lines( bs_opt( 'stats_items' ), array( 'number', 'label' ) );
if ( ! $stats ) {
	return;
}
?>
<section class="section-tight">
	<div class="container-x">
		<div class="rounded-3xl bg-navy-900 text-white p-8 md:p-12 relative overflow-hidden">
			<div class="absolute -bottom-24 -left-24 w-80 h-80 rounded-full bg-primary-600/30 blur-3xl" aria-hidden="true"></div>
			<div class="relative grid grid-cols-2 md:grid-cols-4 gap-6">
				<?php foreach ( $stats as $s ) : ?>
					<div class="border-l-2 border-primary-500/60 pl-4">
						<p class="text-3xl md:text-4xl font-extrabold text-white mb-1" data-count="<?php echo esc_attr( $s['number'] ); ?>"><?php echo esc_html( $s['number'] ); ?></p>
						<p class="text-sm text-navy-300"><?php echo esc_html( $s['label'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
