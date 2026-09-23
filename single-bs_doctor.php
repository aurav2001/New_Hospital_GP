<?php
/**
 * Single doctor.
 *
 * @package GPHealthcare
 */

get_header();
the_post();
$doc = bs_doctor_data( get_the_ID() );

// Specialities matching this doctor's keywords.
$keywords = array_filter( array_map( 'trim', explode( ',', strtolower( get_post_meta( get_the_ID(), '_bs_keyword', true ) ) ) ) );
$related  = array();
if ( $keywords ) {
	foreach ( get_posts( array( 'post_type' => 'bs_service', 'numberposts' => -1, 'orderby' => 'menu_order', 'order' => 'ASC' ) ) as $s ) {
		$sk = strtolower( get_post_meta( $s->ID, '_bs_keyword', true ) . ' ' . $s->post_title );
		foreach ( $keywords as $k ) {
			if ( $k && false !== strpos( $sk, $k ) ) {
				$related[ $s->ID ] = $s;
				break;
			}
		}
	}
}
?>
<main>
	<section class="relative overflow-hidden bg-gradient-to-b from-primary-50/70 to-white">
		<div class="absolute inset-0 bg-dots opacity-50" aria-hidden="true"></div>
		<div class="container-x relative py-10 md:py-16">
			<nav aria-label="<?php esc_attr_e( 'Breadcrumb', 'bshealthcare' ); ?>" class="flex items-center gap-1.5 text-xs text-navy-400 mb-6">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center gap-1 hover:text-primary-700"><?php bs_the_icon( 'home', 12 ); ?> <?php esc_html_e( 'Home', 'bshealthcare' ); ?></a>
				<?php bs_the_icon( 'chevron-right', 12 ); ?>
				<a href="<?php echo esc_url( get_post_type_archive_link( 'bs_doctor' ) ); ?>" class="hover:text-primary-700"><?php esc_html_e( 'Doctors', 'bshealthcare' ); ?></a>
				<?php bs_the_icon( 'chevron-right', 12 ); ?>
				<span class="text-navy-700 font-medium"><?php the_title(); ?></span>
			</nav>

			<div class="grid lg:grid-cols-12 gap-10 items-center">
				<div class="lg:col-span-5">
					<div class="rounded-[2rem] overflow-hidden shadow-card aspect-[4/4.5] bg-navy-100">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'bs-portrait', array( 'class' => 'w-full h-full object-cover object-top' ) ); ?>
						<?php else : ?>
							<span class="w-full h-full flex items-center justify-center text-navy-300"><?php bs_the_icon( 'user', 96 ); ?></span>
						<?php endif; ?>
					</div>
				</div>
				<div class="lg:col-span-7">
					<?php if ( $doc['online'] ) : ?>
						<span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 border border-emerald-200 px-3 py-1 text-xs font-bold text-emerald-700 mb-4"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> <?php esc_html_e( 'Available today', 'bshealthcare' ); ?></span>
					<?php endif; ?>
					<h1 class="heading-xl mb-2"><?php the_title(); ?></h1>
					<p class="text-xl text-primary-700 font-semibold mb-6"><?php echo esc_html( $doc['role'] ); ?></p>

					<div class="grid sm:grid-cols-3 gap-3 mb-6">
						<div class="card p-4"><p class="text-[10px] font-bold uppercase tracking-wider text-navy-400 mb-1 flex items-center gap-1"><?php bs_the_icon( 'graduation', 12 ); ?> <?php esc_html_e( 'Education', 'bshealthcare' ); ?></p><p class="font-bold text-navy-800 text-sm"><?php echo esc_html( $doc['qualification'] ? $doc['qualification'] : '—' ); ?></p></div>
						<div class="card p-4"><p class="text-[10px] font-bold uppercase tracking-wider text-navy-400 mb-1 flex items-center gap-1"><?php bs_the_icon( 'clock', 12 ); ?> <?php esc_html_e( 'Experience', 'bshealthcare' ); ?></p><p class="font-bold text-navy-800 text-sm"><?php echo esc_html( $doc['experience'] ? $doc['experience'] : '—' ); ?></p></div>
						<div class="card p-4"><p class="text-[10px] font-bold uppercase tracking-wider text-navy-400 mb-1 flex items-center gap-1"><?php bs_the_icon( 'languages', 12 ); ?> <?php esc_html_e( 'Languages', 'bshealthcare' ); ?></p><p class="font-bold text-navy-800 text-sm"><?php echo esc_html( $doc['languages'] ); ?></p></div>
					</div>

					<?php if ( $doc['days'] ) : ?>
						<p class="text-sm text-navy-500 mb-6"><strong class="text-navy-700"><?php esc_html_e( 'Available days:', 'bshealthcare' ); ?></strong> <?php echo esc_html( implode( ', ', (array) $doc['days'] ) ); ?> · <strong class="text-navy-700"><?php esc_html_e( 'Fee:', 'bshealthcare' ); ?></strong> ₹<?php echo esc_html( $doc['fee'] ? $doc['fee'] : bs_opt( 'appt_fee' ) ); ?></p>
					<?php endif; ?>

					<div class="flex flex-col sm:flex-row gap-3">
						<button type="button" class="btn-primary btn-lg" data-bs-book data-doctor="<?php echo esc_attr( get_the_ID() ); ?>"><?php bs_the_icon( 'calendar', 18 ); ?> <?php esc_html_e( 'Book Appointment', 'bshealthcare' ); ?></button>
						<a href="<?php echo esc_attr( bs_phone_href( bs_opt( 'phone' ) ) ); ?>" class="btn-outline btn-lg"><?php bs_the_icon( 'phone', 18 ); ?> <?php echo esc_html( bs_opt( 'phone' ) ); ?></a>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php if ( trim( wp_strip_all_tags( get_the_content() ) ) ) : ?>
	<section class="section">
		<div class="container-x max-w-3xl">
			<h2 class="heading-lg mb-5"><?php esc_html_e( 'About the doctor', 'bshealthcare' ); ?></h2>
			<div class="prose-cms"><?php the_content(); ?></div>
		</div>
	</section>
	<?php endif; ?>

	<?php if ( $related ) : ?>
	<section class="section-tight">
		<div class="container-x">
			<?php bs_section_header( array( 'eyebrow' => __( 'Treats', 'bshealthcare' ), 'title' => __( 'Specialities', 'bshealthcare' ) ) ); ?>
			<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
				<?php foreach ( array_slice( $related, 0, 4 ) as $s ) : ?>
					<a href="<?php echo esc_url( get_permalink( $s ) ); ?>" class="card-hover p-5 group">
						<span class="icon-tile mb-3 group-hover:bg-primary-600 group-hover:text-white transition-colors"><?php bs_the_icon( 'eye', 20 ); ?></span>
						<h3 class="font-bold text-navy-800 group-hover:text-primary-700"><?php echo esc_html( get_the_title( $s ) ); ?></h3>
						<p class="text-sm text-navy-500 line-clamp-2 mt-1"><?php echo esc_html( get_the_excerpt( $s ) ); ?></p>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>
</main>
<?php
get_footer();
