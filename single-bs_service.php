<?php
/**
 * Single speciality / service page.
 *
 * @package GPHealthcare
 */

get_header();
the_post();

$id      = get_the_ID();
$tagline = get_post_meta( $id, '_bs_tagline', true );
$hero_sub = get_post_meta( $id, '_bs_hero_sub', true );
$scope_title  = get_post_meta( $id, '_bs_scope_title', true );
$scope_points = bs_parse_lines( get_post_meta( $id, '_bs_scope_points', true ), array( 'title', 'desc' ) );
$scope_image  = get_post_meta( $id, '_bs_scope_image', true );
$keyword      = strtolower( get_post_meta( $id, '_bs_keyword', true ) );

// Doctors matching this speciality.
$experts = array();
foreach ( get_posts( array( 'post_type' => 'bs_doctor', 'numberposts' => -1, 'orderby' => 'menu_order', 'order' => 'ASC' ) ) as $d ) {
	$hay = strtolower( get_post_meta( $d->ID, '_bs_keyword', true ) . ' ' . get_post_meta( $d->ID, '_bs_role', true ) );
	if ( $keyword && false !== strpos( $hay, $keyword ) ) {
		$experts[] = $d;
	}
}
$related = get_posts( array( 'post_type' => 'bs_service', 'numberposts' => 4, 'post__not_in' => array( $id ), 'orderby' => 'rand' ) );
?>
<main class="bg-white">
	<section class="relative overflow-hidden bg-gradient-to-b from-primary-50/70 to-white">
		<div class="absolute inset-0 bg-dots opacity-50" aria-hidden="true"></div>
		<div class="container-x relative py-10 md:py-16">
			<nav aria-label="<?php esc_attr_e( 'Breadcrumb', 'bshealthcare' ); ?>" class="flex items-center gap-1.5 text-xs text-navy-400 mb-6">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center gap-1 hover:text-primary-700"><?php bs_the_icon( 'home', 12 ); ?> <?php esc_html_e( 'Home', 'bshealthcare' ); ?></a>
				<?php bs_the_icon( 'chevron-right', 12 ); ?>
				<a href="<?php echo esc_url( get_post_type_archive_link( 'bs_service' ) ); ?>" class="hover:text-primary-700"><?php esc_html_e( 'Specialities', 'bshealthcare' ); ?></a>
				<?php bs_the_icon( 'chevron-right', 12 ); ?>
				<span class="text-navy-700 font-medium"><?php the_title(); ?></span>
			</nav>

			<div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
				<div>
					<?php if ( $tagline ) : ?><span class="eyebrow mb-4"><?php echo esc_html( $tagline ); ?></span><?php endif; ?>
					<h1 class="heading-xl text-balance mb-2"><?php the_title(); ?></h1>
					<?php if ( $hero_sub ) : ?><p class="text-xl md:text-2xl text-primary-700 font-semibold mb-5"><?php echo esc_html( $hero_sub ); ?></p><?php endif; ?>
					<p class="lead max-w-lg mb-8"><?php echo esc_html( get_the_excerpt() ); ?></p>

					<div class="flex flex-col sm:flex-row gap-3 mb-8">
						<button type="button" class="btn-primary btn-lg" data-bs-book><?php bs_the_icon( 'calendar', 18 ); ?> <?php esc_html_e( 'Book Appointment', 'bshealthcare' ); ?></button>
						<a href="<?php echo esc_attr( bs_phone_href( bs_opt( 'phone' ) ) ); ?>" class="btn-outline btn-lg"><?php bs_the_icon( 'phone', 18 ); ?> <?php echo esc_html( bs_opt( 'phone' ) ); ?></a>
					</div>

					<div class="grid grid-cols-3 gap-4 pt-6 border-t border-navy-100">
						<span class="flex items-center gap-2 text-sm font-semibold text-navy-700"><span class="text-primary-600"><?php bs_the_icon( 'award', 18 ); ?></span> <?php esc_html_e( 'Expert surgeons', 'bshealthcare' ); ?></span>
						<span class="flex items-center gap-2 text-sm font-semibold text-navy-700"><span class="text-primary-600"><?php bs_the_icon( 'shield', 18 ); ?></span> <?php esc_html_e( 'Safe & proven', 'bshealthcare' ); ?></span>
						<span class="flex items-center gap-2 text-sm font-semibold text-navy-700"><span class="text-primary-600"><?php bs_the_icon( 'clock', 18 ); ?></span> <?php esc_html_e( 'Quick recovery', 'bshealthcare' ); ?></span>
					</div>
				</div>

				<div class="relative">
					<div class="absolute -inset-3 rounded-[2.5rem] bg-gradient-to-br from-primary-200/60 to-transparent -z-10 -rotate-2" aria-hidden="true"></div>
					<div class="rounded-[2rem] overflow-hidden shadow-card aspect-[4/3] bg-navy-50">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-full object-cover' ) ); ?>
						<?php else : ?>
							<img src="<?php echo esc_url( bs_service_image_url( get_the_ID() ) ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover">
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Quick Clinical Highlights / Key Numbers -->
	<section class="border-y border-navy-100 bg-white">
		<div class="container-x py-6">
			<div class="grid grid-cols-2 md:grid-cols-4 gap-6 divide-y md:divide-y-0 md:divide-x divide-navy-100">
				<div class="flex items-center gap-4 pt-4 md:pt-0">
					<div class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center shrink-0">
						<?php bs_the_icon( 'clock', 24 ); ?>
					</div>
					<div>
						<span class="block text-xl font-extrabold text-navy-900">15–20 Mins</span>
						<span class="block text-xs font-medium text-navy-500">Daycare Procedure</span>
					</div>
				</div>
				<div class="flex items-center gap-4 pt-4 md:pt-0 md:pl-6">
					<div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
						<?php bs_the_icon( 'shield', 24 ); ?>
					</div>
					<div>
						<span class="block text-xl font-extrabold text-navy-900">100% Stitchless</span>
						<span class="block text-xs font-medium text-navy-500">No Injections / No Pad</span>
					</div>
				</div>
				<div class="flex items-center gap-4 pt-4 md:pt-0 md:pl-6">
					<div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
						<?php bs_the_icon( 'eye', 24 ); ?>
					</div>
					<div>
						<span class="block text-xl font-extrabold text-navy-900">Premium IOLs</span>
						<span class="block text-xs font-medium text-navy-500">Monofocal & Multifocal</span>
					</div>
				</div>
				<div class="flex items-center gap-4 pt-4 md:pt-0 md:pl-6">
					<div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-700 flex items-center justify-center shrink-0">
						<?php bs_the_icon( 'home', 24 ); ?>
					</div>
					<div>
						<span class="block text-xl font-extrabold text-navy-900">Same-Day Home</span>
						<span class="block text-xs font-medium text-navy-500">Fast Visual Recovery</span>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- About & Overview Section -->
	<section class="section">
		<div class="container-x grid lg:grid-cols-12 gap-10 items-start">
			<div class="lg:col-span-7">
				<span class="eyebrow mb-3"><?php esc_html_e( 'Clinical Overview', 'bshealthcare' ); ?></span>
				<h2 class="heading-lg mb-5"><?php printf( esc_html__( 'Comprehensive Care & Treatment for %s', 'bshealthcare' ), esc_html( get_the_title() ) ); ?></h2>
				
				<div class="prose-cms text-navy-600 leading-relaxed text-base space-y-4">
					<?php if ( get_the_content() ) : ?>
						<?php the_content(); ?>
					<?php else : ?>
						<p><?php echo esc_html( get_the_excerpt() ); ?></p>
						<p><?php esc_html_e( 'At Netrana Eye Hospital in Gaya, our ophthalmology team ensures the highest standard of precision and patient safety. Utilizing state-of-the-art diagnostic imaging and modern micro-surgical equipment, every patient receives a tailored treatment protocol designed for rapid recovery and long-lasting visual acuity.', 'bshealthcare' ); ?></p>
					<?php endif; ?>
				</div>

				<!-- Key Treatment Benefits -->
				<div class="mt-8 pt-8 border-t border-navy-100">
					<h3 class="font-bold text-navy-800 text-lg mb-4"><?php esc_html_e( 'Key Benefits of Treatment at Netrana', 'bshealthcare' ); ?></h3>
					<div class="grid sm:grid-cols-2 gap-4">
						<div class="card p-4 bg-navy-50/50 border-navy-100 flex items-start gap-3">
							<span class="w-8 h-8 rounded-lg bg-primary-100 text-primary-700 flex items-center justify-center shrink-0 mt-0.5"><?php bs_the_icon( 'check', 16 ); ?></span>
							<div>
								<h4 class="font-bold text-navy-800 text-sm mb-1"><?php esc_html_e( 'Minimally Invasive', 'bshealthcare' ); ?></h4>
								<p class="text-xs text-navy-500 leading-relaxed"><?php esc_html_e( 'Ultra-small incisions ensure near-zero tissue trauma and quick natural sealing.', 'bshealthcare' ); ?></p>
							</div>
						</div>
						<div class="card p-4 bg-navy-50/50 border-navy-100 flex items-start gap-3">
							<span class="w-8 h-8 rounded-lg bg-primary-100 text-primary-700 flex items-center justify-center shrink-0 mt-0.5"><?php bs_the_icon( 'check', 16 ); ?></span>
							<div>
								<h4 class="font-bold text-navy-800 text-sm mb-1"><?php esc_html_e( 'NABH Protocol Modular OT', 'bshealthcare' ); ?></h4>
								<p class="text-xs text-navy-500 leading-relaxed"><?php esc_html_e( '100% sterile air filtration and zero-infection operating standards.', 'bshealthcare' ); ?></p>
							</div>
						</div>
						<div class="card p-4 bg-navy-50/50 border-navy-100 flex items-start gap-3">
							<span class="w-8 h-8 rounded-lg bg-primary-100 text-primary-700 flex items-center justify-center shrink-0 mt-0.5"><?php bs_the_icon( 'check', 16 ); ?></span>
							<div>
								<h4 class="font-bold text-navy-800 text-sm mb-1"><?php esc_html_e( 'Fast Visual Rehabilitation', 'bshealthcare' ); ?></h4>
								<p class="text-xs text-navy-500 leading-relaxed"><?php esc_html_e( 'Most patients resume normal everyday activities within 24 to 48 hours.', 'bshealthcare' ); ?></p>
							</div>
						</div>
						<div class="card p-4 bg-navy-50/50 border-navy-100 flex items-start gap-3">
							<span class="w-8 h-8 rounded-lg bg-primary-100 text-primary-700 flex items-center justify-center shrink-0 mt-0.5"><?php bs_the_icon( 'check', 16 ); ?></span>
							<div>
								<h4 class="font-bold text-navy-800 text-sm mb-1"><?php esc_html_e( 'Transparent & Ethical Care', 'bshealthcare' ); ?></h4>
								<p class="text-xs text-navy-500 leading-relaxed"><?php esc_html_e( 'Complete pre-operative counselling with no hidden costs or unnecessary tests.', 'bshealthcare' ); ?></p>
							</div>
						</div>
					</div>
				</div>

				<ul class="mt-8 grid sm:grid-cols-3 gap-3">
					<li class="flex items-center gap-2 rounded-xl bg-navy-50 px-4 py-3 text-sm font-semibold text-navy-700"><span class="text-primary-600 shrink-0"><?php bs_the_icon( 'check-circle', 16 ); ?></span> <?php esc_html_e( 'Advanced technology', 'bshealthcare' ); ?></li>
					<li class="flex items-center gap-2 rounded-xl bg-navy-50 px-4 py-3 text-sm font-semibold text-navy-700"><span class="text-primary-600 shrink-0"><?php bs_the_icon( 'check-circle', 16 ); ?></span> <?php esc_html_e( 'Personalised care', 'bshealthcare' ); ?></li>
					<li class="flex items-center gap-2 rounded-xl bg-navy-50 px-4 py-3 text-sm font-semibold text-navy-700"><span class="text-primary-600 shrink-0"><?php bs_the_icon( 'check-circle', 16 ); ?></span> <?php esc_html_e( 'Experienced team', 'bshealthcare' ); ?></li>
				</ul>
			</div>

			<aside class="lg:col-span-5 space-y-6 lg:sticky lg:top-32">
				<div class="card p-6 bg-gradient-to-br from-primary-50 to-white border-primary-100 shadow-card">
					<span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-primary-100 text-primary-800 mb-3"><?php esc_html_e( 'OPD Consultation', 'bshealthcare' ); ?></span>
					<h3 class="font-bold text-navy-800 text-xl mb-2"><?php esc_html_e( 'Book an Eye Check-Up', 'bshealthcare' ); ?></h3>
					<p class="text-sm text-navy-500 mb-5"><?php esc_html_e( 'Meet our senior eye surgeon and get a comprehensive evaluation of your vision.', 'bshealthcare' ); ?></p>
					
					<div class="space-y-3 mb-6 p-4 rounded-xl bg-white border border-navy-100 text-xs text-navy-600">
						<div class="flex items-center gap-2">
							<span class="text-primary-600"><?php bs_the_icon( 'clock', 14 ); ?></span>
							<span><strong>OPD Hours:</strong> Mon - Sat: 8:00 AM - 6:00 PM</span>
						</div>
						<div class="flex items-center gap-2">
							<span class="text-primary-600"><?php bs_the_icon( 'map', 14 ); ?></span>
							<span>Saiyari, Opp. Hanuman Mandir, Cherki Road, Gaya</span>
						</div>
					</div>

					<button type="button" class="btn-primary w-full mb-3 btn-lg justify-center shadow-lg" data-bs-book><?php bs_the_icon( 'calendar', 16 ); ?> <?php esc_html_e( 'Book Appointment Online', 'bshealthcare' ); ?></button>
					
					<div class="grid grid-cols-2 gap-2">
						<a href="<?php echo esc_attr( bs_phone_href( bs_opt( 'phone' ) ) ); ?>" class="btn-outline justify-center text-xs py-2.5">
							<?php bs_the_icon( 'phone', 14 ); ?> <?php esc_html_e( 'Call Clinic', 'bshealthcare' ); ?>
						</a>
						<a href="<?php echo esc_url( bs_whatsapp_url( 'Hello, I want to book an appointment for ' . get_the_title() ) ); ?>" target="_blank" rel="noopener" class="btn bg-[#25D366] text-white hover:bg-[#1ebd59] justify-center text-xs py-2.5">
							<?php bs_the_icon( 'message', 14 ); ?> <?php esc_html_e( 'WhatsApp', 'bshealthcare' ); ?>
						</a>
					</div>
				</div>

				<!-- Ayushman / Insurance info banner -->
				<div class="card p-5 bg-navy-50/70 border-navy-100 flex items-center gap-4">
					<div class="w-10 h-10 rounded-xl bg-primary-600 text-white flex items-center justify-center shrink-0">
						<?php bs_the_icon( 'shield', 20 ); ?>
					</div>
					<div>
						<h4 class="font-bold text-navy-800 text-sm"><?php esc_html_e( 'Cashless & Ayushman Scheme', 'bshealthcare' ); ?></h4>
						<p class="text-xs text-navy-500"><?php esc_html_e( 'Applicable benefits and transparent government packages available.', 'bshealthcare' ); ?></p>
					</div>
				</div>
			</aside>
		</div>
	</section>

	<!-- Step-by-Step Procedure Timeline -->
	<section class="section bg-gradient-to-b from-navy-50/70 to-white">
		<div class="container-x">
			<div class="max-w-2xl mx-auto text-center mb-12">
				<span class="eyebrow mb-2"><?php esc_html_e( 'Step-by-Step Guide', 'bshealthcare' ); ?></span>
				<h2 class="heading-lg mb-3"><?php esc_html_e( 'Your Treatment Journey at Netrana', 'bshealthcare' ); ?></h2>
				<p class="text-navy-500 text-sm leading-relaxed"><?php esc_html_e( 'From initial diagnosis to crystal-clear vision recovery, here is what you can expect at every step.', 'bshealthcare' ); ?></p>
			</div>

			<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
				<div class="card p-6 bg-white border-navy-100 flex flex-col relative group hover:border-primary-300 transition-colors">
					<div class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center font-extrabold text-lg mb-4 group-hover:bg-primary-600 group-hover:text-white transition-colors">
						01
					</div>
					<h3 class="font-bold text-navy-800 text-base mb-2"><?php esc_html_e( 'Digital Eye Evaluation', 'bshealthcare' ); ?></h3>
					<p class="text-xs text-navy-500 leading-relaxed mb-4"><?php esc_html_e( 'High-precision auto-refraction, slit-lamp assessment, and biometric measurements to calculate exact lens power.', 'bshealthcare' ); ?></p>
					<span class="mt-auto text-[11px] font-bold text-primary-700 uppercase tracking-wider"><?php esc_html_e( 'Day 1: OPD', 'bshealthcare' ); ?></span>
				</div>

				<div class="card p-6 bg-white border-navy-100 flex flex-col relative group hover:border-primary-300 transition-colors">
					<div class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center font-extrabold text-lg mb-4 group-hover:bg-primary-600 group-hover:text-white transition-colors">
						02
					</div>
					<h3 class="font-bold text-navy-800 text-base mb-2"><?php esc_html_e( 'Custom Treatment Plan', 'bshealthcare' ); ?></h3>
					<p class="text-xs text-navy-500 leading-relaxed mb-4"><?php esc_html_e( 'Surgeon counselling on lens options (Monofocal, Toric, or Multifocal) tailored to your visual needs and lifestyle.', 'bshealthcare' ); ?></p>
					<span class="mt-auto text-[11px] font-bold text-primary-700 uppercase tracking-wider"><?php esc_html_e( 'Pre-Surgery', 'bshealthcare' ); ?></span>
				</div>

				<div class="card p-6 bg-white border-navy-100 flex flex-col relative group hover:border-primary-300 transition-colors">
					<div class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center font-extrabold text-lg mb-4 group-hover:bg-primary-600 group-hover:text-white transition-colors">
						03
					</div>
					<h3 class="font-bold text-navy-800 text-base mb-2"><?php esc_html_e( '15-Min Sutureless Procedure', 'bshealthcare' ); ?></h3>
					<p class="text-xs text-navy-500 leading-relaxed mb-4"><?php esc_html_e( 'Gentle ultrasound dissolution of cloudy lens followed by foldable lens implantation. Zero stitches, zero pain.', 'bshealthcare' ); ?></p>
					<span class="mt-auto text-[11px] font-bold text-primary-700 uppercase tracking-wider"><?php esc_html_e( 'Modular OT', 'bshealthcare' ); ?></span>
				</div>

				<div class="card p-6 bg-white border-navy-100 flex flex-col relative group hover:border-primary-300 transition-colors">
					<div class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center font-extrabold text-lg mb-4 group-hover:bg-primary-600 group-hover:text-white transition-colors">
						04
					</div>
					<h3 class="font-bold text-navy-800 text-base mb-2"><?php esc_html_e( 'Same-Day Walk Home', 'bshealthcare' ); ?></h3>
					<p class="text-xs text-navy-500 leading-relaxed mb-4"><?php esc_html_e( 'Post-operative protective goggles and eye drops provided. Patient walks back home within 2 hours of surgery.', 'bshealthcare' ); ?></p>
					<span class="mt-auto text-[11px] font-bold text-primary-700 uppercase tracking-wider"><?php esc_html_e( 'Discharge & Follow-Up', 'bshealthcare' ); ?></span>
				</div>
			</div>
		</div>
	</section>

	<!-- Common Symptoms / When To Consult Section -->
	<section class="section">
		<div class="container-x">
			<div class="rounded-3xl bg-navy-900 text-white p-8 md:p-12 relative overflow-hidden">
				<div class="absolute -bottom-24 -left-24 w-80 h-80 rounded-full bg-primary-600/20 blur-3xl" aria-hidden="true"></div>
				<div class="relative grid lg:grid-cols-12 gap-8 items-center">
					<div class="lg:col-span-5">
						<span class="text-primary-300 font-bold text-xs uppercase tracking-widest block mb-2"><?php esc_html_e( 'Self-Assessment', 'bshealthcare' ); ?></span>
						<h2 class="text-2xl md:text-3xl font-extrabold mb-4"><?php esc_html_e( 'When Should You Consult Our Specialists?', 'bshealthcare' ); ?></h2>
						<p class="text-navy-300 text-sm leading-relaxed mb-6"><?php esc_html_e( 'Vision problems develop gradually. If you or your family members experience any of the symptoms below, timely screening can prevent irreversible loss of sight.', 'bshealthcare' ); ?></p>
						<button type="button" class="btn-primary" data-bs-book><?php bs_the_icon( 'calendar', 16 ); ?> <?php esc_html_e( 'Schedule Screening Today', 'bshealthcare' ); ?></button>
					</div>

					<div class="lg:col-span-7 grid sm:grid-cols-2 gap-4">
						<div class="rounded-2xl bg-white/10 p-4 border border-white/15 backdrop-blur-sm">
							<div class="flex items-center gap-3 mb-2">
								<span class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center text-primary-300"><?php bs_the_icon( 'eye', 18 ); ?></span>
								<h3 class="font-bold text-white text-sm"><?php esc_html_e( 'Blurry or Foggy Vision', 'bshealthcare' ); ?></h3>
							</div>
							<p class="text-xs text-navy-200"><?php esc_html_e( 'Objects appear as if seen through a dusty or cloudy window, making reading challenging.', 'bshealthcare' ); ?></p>
						</div>

						<div class="rounded-2xl bg-white/10 p-4 border border-white/15 backdrop-blur-sm">
							<div class="flex items-center gap-3 mb-2">
								<span class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center text-amber-300"><?php bs_the_icon( 'star', 18 ); ?></span>
								<h3 class="font-bold text-white text-sm"><?php esc_html_e( 'Glare & Halos at Night', 'bshealthcare' ); ?></h3>
							</div>
							<p class="text-xs text-navy-200"><?php esc_html_e( 'Rings or halos around oncoming car headlights and streetlights during night driving.', 'bshealthcare' ); ?></p>
						</div>

						<div class="rounded-2xl bg-white/10 p-4 border border-white/15 backdrop-blur-sm">
							<div class="flex items-center gap-3 mb-2">
								<span class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center text-emerald-300"><?php bs_the_icon( 'file', 18 ); ?></span>
								<h3 class="font-bold text-white text-sm"><?php esc_html_e( 'Frequent Glass Number Change', 'bshealthcare' ); ?></h3>
							</div>
							<p class="text-xs text-navy-200"><?php esc_html_e( 'Prescription spectacles change rapidly within a few months without significant clarity.', 'bshealthcare' ); ?></p>
						</div>

						<div class="rounded-2xl bg-white/10 p-4 border border-white/15 backdrop-blur-sm">
							<div class="flex items-center gap-3 mb-2">
								<span class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center text-red-300"><?php bs_the_icon( 'alert', 18 ); ?></span>
								<h3 class="font-bold text-white text-sm"><?php esc_html_e( 'Fading Colors or Double Vision', 'bshealthcare' ); ?></h3>
							</div>
							<p class="text-xs text-navy-200"><?php esc_html_e( 'Colors look dull or yellowish, or seeing ghost images in one eye.', 'bshealthcare' ); ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php if ( $scope_points ) : ?>
	<section class="section bg-navy-50/60">
		<div class="container-x grid lg:grid-cols-2 gap-10 items-center">
			<div>
				<?php bs_section_header( array( 'eyebrow' => __( 'What We Offer', 'bshealthcare' ), 'title' => $scope_title ? $scope_title : __( 'Our Techniques', 'bshealthcare' ) ) ); ?>
				<ol class="space-y-3">
					<?php foreach ( $scope_points as $i => $p ) : ?>
						<li class="card p-4 flex gap-4">
							<span class="w-9 h-9 rounded-lg bg-primary-600 text-white text-sm font-extrabold flex items-center justify-center shrink-0"><?php echo esc_html( str_pad( $i + 1, 2, '0', STR_PAD_LEFT ) ); ?></span>
							<span><span class="block font-bold text-navy-800"><?php echo esc_html( $p['title'] ); ?></span><span class="block text-sm text-navy-500"><?php echo esc_html( $p['desc'] ); ?></span></span>
						</li>
					<?php endforeach; ?>
				</ol>
			</div>
			<div class="rounded-3xl overflow-hidden aspect-[4/3] lg:aspect-[4/5] bg-navy-100 shadow-card">
				<img src="<?php echo esc_url( $scope_image ? $scope_image : bs_service_image_url( get_the_ID() ) ); ?>" alt="<?php echo esc_attr( $scope_title ); ?>" loading="lazy" class="w-full h-full object-cover">
			</div>
		</div>
	</section>
	<?php endif; ?>

	<!-- Frequently Asked Questions for this Speciality -->
	<section class="section">
		<div class="container-x grid lg:grid-cols-12 gap-10 items-start">
			<div class="lg:col-span-4">
				<span class="eyebrow mb-2"><?php esc_html_e( 'Common Queries', 'bshealthcare' ); ?></span>
				<h2 class="heading-lg mb-4"><?php esc_html_e( 'Frequently Asked Questions', 'bshealthcare' ); ?></h2>
				<p class="text-sm text-navy-500 leading-relaxed mb-6"><?php esc_html_e( 'Have questions about recovery time, pain management, or lens choices? Here are straightforward clinical answers.', 'bshealthcare' ); ?></p>
				<div class="card p-5 bg-primary-50/60 border-primary-100">
					<h3 class="font-bold text-navy-800 text-sm mb-1"><?php esc_html_e( 'Need personal advice?', 'bshealthcare' ); ?></h3>
					<p class="text-xs text-navy-500 mb-3"><?php esc_html_e( 'Call our Gaya helpline to discuss your case directly with our care coordinator.', 'bshealthcare' ); ?></p>
					<a href="<?php echo esc_attr( bs_phone_href( bs_opt( 'phone' ) ) ); ?>" class="btn-primary w-full text-xs py-2.5 justify-center"><?php bs_the_icon( 'phone', 14 ); ?> <?php echo esc_html( bs_opt( 'phone' ) ); ?></a>
				</div>
			</div>

			<div class="lg:col-span-8 space-y-3" id="bs-faq">
				<div class="card bs-faq-item is-open border-primary-200">
					<button type="button" class="w-full text-left px-5 md:px-6 py-4 md:py-5 flex items-center justify-between gap-4 font-bold text-navy-800 hover:text-primary-700" aria-expanded="true">
						<span><?php esc_html_e( 'Is the surgery painful or are stitches required?', 'bshealthcare' ); ?></span>
						<span class="bs-faq-icon w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-colors bg-primary-600 text-white">
							<span class="bs-faq-plus hidden"><?php bs_the_icon( 'plus', 16 ); ?></span>
							<span class="bs-faq-minus"><?php bs_the_icon( 'minus', 16 ); ?></span>
						</span>
					</button>
					<div class="bs-faq-body grid transition-all duration-300 grid-rows-[1fr] opacity-100">
						<div class="overflow-hidden"><p class="px-5 md:px-6 pb-5 text-navy-500 leading-relaxed text-sm"><?php esc_html_e( 'No. Modern stitchless Phaco surgery uses topical anaesthetic eye drops so you feel no pain. The microscopic incision seals naturally without any stitches or bandages.', 'bshealthcare' ); ?></p></div>
					</div>
				</div>

				<div class="card bs-faq-item">
					<button type="button" class="w-full text-left px-5 md:px-6 py-4 md:py-5 flex items-center justify-between gap-4 font-bold text-navy-800 hover:text-primary-700" aria-expanded="false">
						<span><?php esc_html_e( 'How soon can I return to my regular daily routine?', 'bshealthcare' ); ?></span>
						<span class="bs-faq-icon w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-colors bg-navy-50 text-navy-500">
							<span class="bs-faq-plus"><?php bs_the_icon( 'plus', 16 ); ?></span>
							<span class="bs-faq-minus hidden"><?php bs_the_icon( 'minus', 16 ); ?></span>
						</span>
					</button>
					<div class="bs-faq-body grid transition-all duration-300 grid-rows-[0fr] opacity-0">
						<div class="overflow-hidden"><p class="px-5 md:px-6 pb-5 text-navy-500 leading-relaxed text-sm"><?php esc_html_e( 'Most patients resume light daily activities like walking, reading, and watching TV within 24 to 48 hours. Heavy lifting and rubbing the eye should be avoided for 1 to 2 weeks.', 'bshealthcare' ); ?></p></div>
					</div>
				</div>

				<div class="card bs-faq-item">
					<button type="button" class="w-full text-left px-5 md:px-6 py-4 md:py-5 flex items-center justify-between gap-4 font-bold text-navy-800 hover:text-primary-700" aria-expanded="false">
						<span><?php esc_html_e( 'What kind of intraocular lens (IOL) is recommended?', 'bshealthcare' ); ?></span>
						<span class="bs-faq-icon w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-colors bg-navy-50 text-navy-500">
							<span class="bs-faq-plus"><?php bs_the_icon( 'plus', 16 ); ?></span>
							<span class="bs-faq-minus hidden"><?php bs_the_icon( 'minus', 16 ); ?></span>
						</span>
					</button>
					<div class="bs-faq-body grid transition-all duration-300 grid-rows-[0fr] opacity-0">
						<div class="overflow-hidden"><p class="px-5 md:px-6 pb-5 text-navy-500 leading-relaxed text-sm"><?php esc_html_e( 'We offer advanced monofocal, toric (for astigmatism), and premium multifocal foldable lenses. Our eye surgeon recommends the optimal lens depending on your eye structure, occupation, and visual goals.', 'bshealthcare' ); ?></p></div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php if ( $experts ) : ?>
	<section class="section">
		<div class="container-x">
			<?php bs_section_header( array( 'eyebrow' => __( 'Specialists', 'bshealthcare' ), 'title' => __( 'Meet your care team', 'bshealthcare' ), 'align' => 'center' ) ); ?>
			<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
				<?php foreach ( array_slice( $experts, 0, 4 ) as $d ) : ?>
					<?php get_template_part( 'template-parts/doctor-card', null, array( 'id' => $d->ID ) ); ?>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<?php if ( $related ) : ?>
	<section class="section-tight">
		<div class="container-x">
			<?php bs_section_header( array( 'eyebrow' => __( 'Explore', 'bshealthcare' ), 'title' => __( 'Related specialities', 'bshealthcare' ), 'action' => '<a href="' . esc_url( get_post_type_archive_link( 'bs_service' ) ) . '" class="btn-ghost">' . esc_html__( 'All specialities', 'bshealthcare' ) . '</a>' ) ); ?>
			<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
				<?php foreach ( $related as $s ) : ?>
					<a href="<?php echo esc_url( get_permalink( $s ) ); ?>" class="card-hover p-4 flex items-center gap-3 group">
						<?php if ( has_post_thumbnail( $s ) ) : ?>
							<?php echo get_the_post_thumbnail( $s, 'thumbnail', array( 'class' => 'w-14 h-14 rounded-xl object-cover shrink-0', 'loading' => 'lazy' ) ); ?>
						<?php else : ?>
							<img src="<?php echo esc_url( bs_service_image_url( $s->ID ) ); ?>" alt="<?php echo esc_attr( get_the_title( $s ) ); ?>" class="w-14 h-14 rounded-xl object-cover shrink-0" loading="lazy">
						<?php endif; ?>
						<span class="min-w-0"><span class="block font-bold text-navy-800 text-sm group-hover:text-primary-700 truncate"><?php echo esc_html( get_the_title( $s ) ); ?></span><span class="block text-xs text-navy-400 line-clamp-2"><?php echo esc_html( get_the_excerpt( $s ) ); ?></span></span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<section class="container-x pb-4">
		<div class="rounded-3xl bg-navy-900 text-white p-8 md:p-14 text-center relative overflow-hidden">
			<div class="absolute -top-20 -right-20 w-80 h-80 rounded-full bg-primary-600/30 blur-3xl" aria-hidden="true"></div>
			<div class="relative max-w-2xl mx-auto">
				<h2 class="text-2xl md:text-4xl font-extrabold text-white mb-3"><?php esc_html_e( 'Ready to restore your vision?', 'bshealthcare' ); ?></h2>
				<p class="text-navy-200 mb-8"><?php esc_html_e( 'Take the first step towards a clearer tomorrow. Our team will guide you through every stage.', 'bshealthcare' ); ?></p>
				<div class="flex flex-col sm:flex-row gap-3 justify-center">
					<button type="button" class="btn bg-white text-navy-900 hover:bg-primary-50 btn-lg" data-bs-book><?php bs_the_icon( 'calendar', 18 ); ?> <?php esc_html_e( 'Book Appointment', 'bshealthcare' ); ?></button>
					<a href="<?php echo esc_url( bs_page_url( 'templates/template-contact.php' ) ); ?>" class="btn border border-white/30 text-white hover:bg-white/10 btn-lg"><?php esc_html_e( 'Ask a question', 'bshealthcare' ); ?></a>
				</div>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
