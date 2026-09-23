<?php
/**
 * Doctor card.
 *
 * @package GPHealthcare
 * @var array $args ['id' => post ID]
 */

$doc = bs_doctor_data( $args['id'] );
?>
<div class="card-hover overflow-hidden flex flex-col h-full group">
	<a href="<?php echo esc_url( $doc['url'] ); ?>" class="relative block aspect-[4/4.2] bg-navy-50 overflow-hidden">
		<?php if ( $doc['image'] ) : ?>
			<img src="<?php echo esc_url( $doc['image'] ); ?>" alt="<?php echo esc_attr( $doc['name'] ); ?>" loading="lazy" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500">
		<?php else : ?>
			<span class="w-full h-full flex items-center justify-center text-navy-300"><?php bs_the_icon( 'user', 64 ); ?></span>
		<?php endif; ?>
		<?php if ( $doc['online'] ) : ?>
			<span class="absolute top-3 left-3 inline-flex items-center gap-1.5 rounded-full bg-white/95 px-2.5 py-1 text-[11px] font-bold text-emerald-700"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> <?php esc_html_e( 'Available today', 'bshealthcare' ); ?></span>
		<?php endif; ?>
	</a>
	<div class="p-5 flex-1 flex flex-col">
		<div class="mb-3">
			<h3 class="font-bold text-navy-800 text-lg leading-snug flex items-center gap-1.5">
				<a href="<?php echo esc_url( $doc['url'] ); ?>" class="hover:text-primary-700"><?php echo esc_html( $doc['name'] ); ?></a>
				<span class="text-primary-600 shrink-0"><?php bs_the_icon( 'badge', 16 ); ?></span>
			</h3>
			<p class="text-sm font-medium text-primary-700"><?php echo esc_html( $doc['role'] ); ?></p>
		</div>

		<dl class="grid grid-cols-2 gap-2 text-xs mb-3">
			<div class="rounded-lg bg-navy-50 px-3 py-2">
				<dt class="flex items-center gap-1 text-navy-400 font-semibold uppercase tracking-wide text-[10px] mb-0.5"><?php bs_the_icon( 'graduation', 12 ); ?> <?php esc_html_e( 'Education', 'bshealthcare' ); ?></dt>
				<dd class="font-bold text-navy-800 leading-tight"><?php echo esc_html( $doc['qualification'] ? $doc['qualification'] : '—' ); ?></dd>
			</div>
			<div class="rounded-lg bg-navy-50 px-3 py-2">
				<dt class="flex items-center gap-1 text-navy-400 font-semibold uppercase tracking-wide text-[10px] mb-0.5"><?php bs_the_icon( 'clock', 12 ); ?> <?php esc_html_e( 'Experience', 'bshealthcare' ); ?></dt>
				<dd class="font-bold text-navy-800 leading-tight"><?php echo esc_html( $doc['experience'] ? $doc['experience'] : '—' ); ?></dd>
			</div>
		</dl>

		<p class="flex items-center gap-1.5 text-xs text-navy-500 mb-4"><span class="text-primary-600"><?php bs_the_icon( 'languages', 14 ); ?></span> <?php echo esc_html( $doc['languages'] ); ?></p>

		<button type="button" class="btn-primary w-full mt-auto" data-bs-book data-doctor="<?php echo esc_attr( $doc['id'] ); ?>"><?php bs_the_icon( 'calendar', 16 ); ?> <?php esc_html_e( 'Book Appointment', 'bshealthcare' ); ?></button>
	</div>
</div>
