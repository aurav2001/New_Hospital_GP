<?php
/**
 * Appointment row used in both dashboards.
 *
 * @package BSHealthcare
 * @var array $args ['appt' => array, 'role' => 'patient'|'doctor']
 */

$a    = $args['appt'];
$role = $args['role'];
?>
<div class="card p-5" data-appt="<?php echo esc_attr( $a['id'] ); ?>">
	<div class="flex flex-col md:flex-row md:items-center gap-4">
		<div class="flex items-center gap-4 flex-1 min-w-0">
			<div class="w-14 h-14 rounded-xl bg-primary-50 text-primary-700 flex flex-col items-center justify-center shrink-0 leading-none">
				<span class="text-[10px] font-bold uppercase"><?php echo esc_html( date_i18n( 'M', strtotime( $a['date'] ) ) ); ?></span>
				<span class="text-lg font-extrabold"><?php echo esc_html( date_i18n( 'j', strtotime( $a['date'] ) ) ); ?></span>
			</div>
			<div class="min-w-0">
				<h3 class="font-bold text-navy-800 truncate">
					<?php echo 'patient' === $role ? esc_html( $a['doctor'] ) : esc_html( $a['name'] ); ?>
				</h3>
				<p class="text-sm text-navy-500">
					<?php echo esc_html( date_i18n( 'l, j F Y', strtotime( $a['date'] ) ) ); ?> · <?php echo esc_html( $a['time'] ); ?>
					<?php if ( 'doctor' === $role ) : ?>
						<br><span class="text-xs"><?php echo esc_html( $a['phone'] ); ?> · <?php echo esc_html( $a['email'] ); ?></span>
					<?php endif; ?>
				</p>
				<?php if ( $a['reference'] ) : ?><p class="text-xs text-navy-400 mt-0.5"><?php esc_html_e( 'Ref:', 'bshealthcare' ); ?> <?php echo esc_html( $a['reference'] ); ?></p><?php endif; ?>
			</div>
		</div>

		<div class="flex items-center gap-2 flex-wrap">
			<?php echo bs_status_badge( $a['status'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?>

			<?php if ( 'patient' === $role ) : ?>
				<?php if ( ! in_array( $a['status'], array( 'cancelled', 'completed' ), true ) ) : ?>
					<button type="button" class="btn-outline text-xs px-3 py-2" data-status="reschedule-requested" data-id="<?php echo esc_attr( $a['id'] ); ?>" data-ask="<?php esc_attr_e( 'Tell us your preferred date/time:', 'bshealthcare' ); ?>"><?php esc_html_e( 'Reschedule', 'bshealthcare' ); ?></button>
					<button type="button" class="btn-outline text-xs px-3 py-2 text-red-600 border-red-200 hover:border-red-400" data-status="cancelled" data-id="<?php echo esc_attr( $a['id'] ); ?>" data-confirm="<?php esc_attr_e( 'Cancel this appointment?', 'bshealthcare' ); ?>"><?php esc_html_e( 'Cancel', 'bshealthcare' ); ?></button>
				<?php endif; ?>
				<?php if ( $a['prescription'] ) : ?>
					<a href="<?php echo esc_url( $a['prescription']['url'] ); ?>" target="_blank" rel="noopener" class="btn-primary text-xs px-3 py-2"><?php bs_the_icon( 'pill', 14 ); ?> <?php esc_html_e( 'Prescription', 'bshealthcare' ); ?></a>
				<?php endif; ?>
			<?php else : ?>
				<?php if ( 'pending' === $a['status'] || 'reschedule-requested' === $a['status'] ) : ?>
					<button type="button" class="btn-primary text-xs px-3 py-2" data-status="confirmed" data-id="<?php echo esc_attr( $a['id'] ); ?>"><?php esc_html_e( 'Confirm', 'bshealthcare' ); ?></button>
				<?php endif; ?>
				<?php if ( ! in_array( $a['status'], array( 'cancelled', 'completed' ), true ) ) : ?>
					<button type="button" class="btn-outline text-xs px-3 py-2" data-prescribe="<?php echo esc_attr( $a['id'] ); ?>" data-patient="<?php echo esc_attr( $a['name'] ); ?>"><?php bs_the_icon( 'pill', 14 ); ?> <?php esc_html_e( 'Prescribe', 'bshealthcare' ); ?></button>
					<button type="button" class="btn-outline text-xs px-3 py-2 text-red-600 border-red-200 hover:border-red-400" data-status="cancelled" data-id="<?php echo esc_attr( $a['id'] ); ?>" data-confirm="<?php esc_attr_e( 'Cancel this appointment?', 'bshealthcare' ); ?>"><?php esc_html_e( 'Cancel', 'bshealthcare' ); ?></button>
				<?php elseif ( $a['prescription'] ) : ?>
					<a href="<?php echo esc_url( $a['prescription']['url'] ); ?>" target="_blank" rel="noopener" class="btn-outline text-xs px-3 py-2"><?php esc_html_e( 'View prescription', 'bshealthcare' ); ?></a>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</div>

	<?php if ( $a['notes'] ) : ?>
		<p class="mt-3 pt-3 border-t border-navy-100 text-sm text-navy-500"><strong class="text-navy-700"><?php esc_html_e( 'Note:', 'bshealthcare' ); ?></strong> <?php echo esc_html( $a['notes'] ); ?></p>
	<?php endif; ?>
</div>
