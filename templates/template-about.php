<?php
/**
 * Template Name: About Page
 *
 * @package GPHealthcare
 */

get_header();

$stats = bs_parse_lines( bs_opt( 'stats_items' ), array( 'number', 'label' ) );
$why   = bs_parse_lines( bs_opt( 'why_items' ), array( 'title', 'desc', 'img' ) );
$icons = array( 'award', 'calendar', 'heart', 'shield' );
$phone = bs_opt( 'phone' );
$wa    = bs_whatsapp_url();
?>
<main>
	<?php
	get_template_part(
		'template-parts/page-hero',
		null,
		array(
			'eyebrow'  => __( 'Netrana Eye Hospital · Gaya, Bihar', 'bshealthcare' ),
			'title'    => __( 'About Netrana Eye Hospital', 'bshealthcare' ),
			'subtitle' => __( 'Dedicated to protecting and restoring vision for Gaya and surrounding communities through accessible, modern, and compassionate eye care.', 'bshealthcare' ),
			'crumbs'   => array( array( __( 'About Us', 'bshealthcare' ) ) ),
		)
	);
	?>

	<?php if ( $stats ) : ?>
	<section class="section-tight">
		<div class="container-x">
			<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
				<?php foreach ( $stats as $s ) : ?>
					<div class="card p-6 text-center shadow-card hover:shadow-lift transition-shadow">
						<p class="text-3xl lg:text-4xl font-extrabold text-primary-700 mb-1" data-count="<?php echo esc_attr( $s['number'] ); ?>"><?php echo esc_html( $s['number'] ); ?></p>
						<p class="text-sm font-medium text-navy-600"><?php echo esc_html( $s['label'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<!-- Hospital Overview & Philosophy -->
	<section class="section">
		<div class="container-x">
			<div class="grid lg:grid-cols-12 gap-10 items-start">
				<div class="lg:col-span-7 space-y-5">
					<div class="inline-flex items-center gap-2 rounded-full bg-primary-50 px-3.5 py-1 text-xs font-bold text-primary-700">
						<?php bs_the_icon( 'heart', 14 ); ?> <?php esc_html_e( 'Our Philosophy & Care', 'bshealthcare' ); ?>
					</div>
					<h2 class="text-3xl sm:text-4xl font-extrabold text-navy-800 leading-tight">
						<?php esc_html_e( 'Protecting & Restoring Vision in Gaya and Surrounding Communities', 'bshealthcare' ); ?>
					</h2>
					<p class="text-navy-600 leading-relaxed text-base">
						<?php esc_html_e( 'Netrana Eye Hospital, located in Gaya, Bihar, is dedicated to protecting and restoring vision for the people of Gaya and the surrounding communities. Our mission is simple: no one in our community should live with preventable blindness.', 'bshealthcare' ); ?>
					</p>
					<p class="text-navy-600 leading-relaxed text-base">
						<?php esc_html_e( 'The hospital offers comprehensive eye care under one roof, including complete eye examinations, optometry services, and spectacle correction by qualified optometrists. Our specialty is cataract surgery, performed with modern phacoemulsification (Phaco) technology. Phaco is a small-incision, stitch-free technique that allows faster recovery, less discomfort, and better visual outcomes, so patients can return to daily life quickly.', 'bshealthcare' ); ?>
					</p>
					<p class="text-navy-600 leading-relaxed text-base">
						<?php esc_html_e( 'Our philosophy is that quality eye care should be accessible, affordable, and compassionate. We work closely with the local community through screening efforts and awareness, helping detect eye problems early and guiding patients toward timely treatment.', 'bshealthcare' ); ?>
					</p>
					<p class="text-navy-600 leading-relaxed text-base">
						<?php esc_html_e( 'Every patient is treated with respect, patience, and clear communication. Our team follows strict hygiene and sterilization protocols, uses modern diagnostic equipment, and provides personalised counselling before and after surgery. From the first check-up to the final follow-up, our commitment is to deliver safe, ethical, and high-quality care.', 'bshealthcare' ); ?>
					</p>
					<div class="rounded-2xl bg-gradient-to-r from-primary-50 to-primary-100/60 border border-primary-200 p-5">
						<p class="font-semibold text-primary-900 italic text-base leading-relaxed">
							&ldquo;<?php esc_html_e( "At Netrana Eye Hospital, we don't just improve eyesight. We help people see their families, their work, and their future clearly.", 'bshealthcare' ); ?>&rdquo;
						</p>
					</div>
				</div>

				<div class="lg:col-span-5 space-y-6">
					<!-- Vision Card -->
					<div class="card p-6 md:p-7 bg-gradient-to-br from-white to-primary-50/40 border-primary-100 shadow-card">
						<div class="w-12 h-12 rounded-xl bg-primary-600 text-white flex items-center justify-center mb-4 shadow-lift">
							<?php bs_the_icon( 'eye', 24 ); ?>
						</div>
						<h3 class="text-xl font-bold text-navy-800 mb-2"><?php esc_html_e( 'Our Vision', 'bshealthcare' ); ?></h3>
						<p class="text-navy-600 leading-relaxed text-sm">
							<?php esc_html_e( 'To be the most trusted eye care centre in Gaya and Bihar, where every person, regardless of means, has access to safe, modern, and compassionate eye care, and no one lives with avoidable blindness.', 'bshealthcare' ); ?>
						</p>
					</div>

					<!-- Mission Card -->
					<div class="card p-6 md:p-7 bg-gradient-to-br from-white to-amber-50/40 border-amber-200 shadow-card">
						<div class="w-12 h-12 rounded-xl bg-amber-500 text-white flex items-center justify-center mb-4 shadow-lift">
							<?php bs_the_icon( 'heart', 24 ); ?>
						</div>
						<h3 class="text-xl font-bold text-navy-800 mb-2"><?php esc_html_e( 'Our Mission', 'bshealthcare' ); ?></h3>
						<p class="text-navy-600 leading-relaxed text-sm">
							<?php esc_html_e( 'To detect, treat, and prevent avoidable blindness in our community through accessible eye care, timely surgery, and awareness.', 'bshealthcare' ); ?>
						</p>
					</div>

					<div class="rounded-2xl overflow-hidden aspect-[16/10] shadow-card bg-navy-100 group">
						<img src="<?php echo esc_url( BS_URI . '/assets/img/inauguration-team.jpg' ); ?>" alt="Netrana Eye Hospital Medical Team" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
					</div>

					<!-- Key Highlights Card -->
					<div class="card p-6 bg-navy-900 text-white">
						<h4 class="font-bold text-white text-base mb-4 flex items-center gap-2">
							<span class="text-primary-400"><?php bs_the_icon( 'shield', 18 ); ?></span>
							<?php esc_html_e( 'Excellence Standards', 'bshealthcare' ); ?>
						</h4>
						<ul class="space-y-3 text-sm text-navy-200">
							<li class="flex items-center gap-2.5">
								<span class="text-emerald-400"><?php bs_the_icon( 'check-circle', 16 ); ?></span>
								<span><?php esc_html_e( 'Stitchless Phaco Cataract Surgery', 'bshealthcare' ); ?></span>
							</li>
							<li class="flex items-center gap-2.5">
								<span class="text-emerald-400"><?php bs_the_icon( 'check-circle', 16 ); ?></span>
								<span><?php esc_html_e( 'NABH Safety Protocols & Sterile OT', 'bshealthcare' ); ?></span>
							</li>
							<li class="flex items-center gap-2.5">
								<span class="text-emerald-400"><?php bs_the_icon( 'check-circle', 16 ); ?></span>
								<span><?php esc_html_e( 'Modular Operation Theatre & Sterilization', 'bshealthcare' ); ?></span>
							</li>
							<li class="flex items-center gap-2.5">
								<span class="text-emerald-400"><?php bs_the_icon( 'check-circle', 16 ); ?></span>
								<span><?php esc_html_e( 'Computerised Auto-Refraction & Lens Fitting', 'bshealthcare' ); ?></span>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Chairman / Director's Message -->
	<section class="section bg-gradient-to-b from-navy-50/60 to-white">
		<div class="container-x">
			<div class="card p-8 md:p-12 lg:p-14 bg-white border border-navy-100 shadow-lift relative overflow-hidden">
				<div class="absolute -top-10 -right-10 text-primary-100/60 pointer-events-none select-none" aria-hidden="true">
					<?php bs_the_icon( 'quote', 180 ); ?>
				</div>

				<div class="relative z-10 max-w-4xl">
					<div class="inline-flex items-center gap-2 rounded-full bg-primary-100/80 px-3.5 py-1 text-xs font-bold text-primary-700 mb-4">
						<?php bs_the_icon( 'award', 14 ); ?> <?php esc_html_e( 'Leadership Note', 'bshealthcare' ); ?>
					</div>

					<h2 class="text-2xl sm:text-3xl font-extrabold text-navy-800 mb-6">
						<?php esc_html_e( 'Message from the Director', 'bshealthcare' ); ?>
					</h2>

					<div class="prose text-navy-600 space-y-4 text-base md:text-lg leading-relaxed">
						<p class="font-medium text-navy-800">
							<?php esc_html_e( 'Dear Friends and Well-wishers,', 'bshealthcare' ); ?>
						</p>
						<p>
							<?php esc_html_e( "Sight is one of life's greatest gifts, yet many people in Gaya and nearby villages still live in avoidable darkness because of cataract and untreated eye problems. Netrana Eye Hospital was founded to change this.", 'bshealthcare' ); ?>
						</p>
						<p>
							<?php esc_html_e( 'Our aim is simple: to bring safe, modern, and affordable eye care to every family in our community. With advanced Phaco cataract surgery, complete optometry services, and a dedicated team, we work every day to restore vision and, with it, independence and dignity.', 'bshealthcare' ); ?>
						</p>
						<p>
							<?php esc_html_e( 'We believe that no one should lose their sight only because they could not reach or afford treatment. That is why we reach out through screening and awareness efforts, so that eye problems are caught early and treated on time.', 'bshealthcare' ); ?>
						</p>
						<p>
							<?php esc_html_e( 'I sincerely thank our doctors, staff, and every patient who has trusted us. Together, let us build a Gaya where everyone can see clearly and live fully.', 'bshealthcare' ); ?>
						</p>
					</div>

					<div class="mt-8 pt-6 border-t border-navy-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
						<div class="flex items-center gap-4">
							<img src="<?php echo esc_url( BS_URI . '/assets/img/doctor-arvind.jpg' ); ?>" alt="Dr. Arvind Kumar" class="w-16 h-16 rounded-2xl object-cover shadow-card shrink-0">
							<div>
								<h3 class="text-xl font-bold text-navy-800"><?php esc_html_e( 'Dr. Arvind Kumar', 'bshealthcare' ); ?></h3>
								<p class="text-sm font-semibold text-primary-700"><?php esc_html_e( 'Director & Chief Eye Care Specialist', 'bshealthcare' ); ?></p>
								<p class="text-xs text-navy-400"><?php esc_html_e( 'Netrana Eye Hospital Private Limited', 'bshealthcare' ); ?></p>
							</div>
						</div>
						<div class="flex gap-2">
							<a href="<?php echo esc_attr( bs_phone_href( $phone ) ); ?>" class="btn-outline btn-sm"><?php bs_the_icon( 'phone', 14 ); ?> <?php echo esc_html( $phone ); ?></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Our Journey / Key Milestones -->
	<section class="section bg-white">
		<div class="container-x">
			<?php
			bs_section_header(
				array(
					'eyebrow'  => __( 'Heritage & Growth', 'bshealthcare' ),
					'title'    => __( 'Our Journey', 'bshealthcare' ),
					'subtitle' => __( 'From our foundational roots in 2011 to establishing Netrana Eye Hospital in Gaya, Bihar — a continuous pursuit of preventable blindness eradication.', 'bshealthcare' ),
					'align'    => 'center',
				)
			);
			?>

			<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mt-10">
				<!-- 2011 -->
				<div class="card p-6 border-navy-100 hover:border-primary-300 transition-all hover:shadow-card flex flex-col">
					<div class="inline-flex items-center gap-1.5 self-start px-3 py-1 rounded-full bg-primary-50 text-primary-700 font-extrabold text-xs mb-4">
						<?php bs_the_icon( 'calendar', 13 ); ?> 2011
					</div>
					<h3 class="font-bold text-navy-800 text-lg mb-2"><?php esc_html_e( 'Where It All Began', 'bshealthcare' ); ?></h3>
					<p class="text-sm text-navy-600 leading-relaxed">
						<?php esc_html_e( 'Magadh Eye Hospital was founded in Aurangabad, Bihar, with a simple belief: quality eye care should be available to every family in the region.', 'bshealthcare' ); ?>
					</p>
				</div>

				<!-- 2019 -->
				<div class="card p-6 border-navy-100 hover:border-primary-300 transition-all hover:shadow-card flex flex-col">
					<div class="inline-flex items-center gap-1.5 self-start px-3 py-1 rounded-full bg-primary-50 text-primary-700 font-extrabold text-xs mb-4">
						<?php bs_the_icon( 'calendar', 13 ); ?> 2019
					</div>
					<h3 class="font-bold text-navy-800 text-lg mb-2"><?php esc_html_e( 'Romashka Healthcare Is Born', 'bshealthcare' ); ?></h3>
					<p class="text-sm text-navy-600 leading-relaxed">
						<?php esc_html_e( 'Building on that foundation, Romashka Healthcare was established in Aurangabad with a stronger mission: to make affordable, high-quality eye care reach more people in the community.', 'bshealthcare' ); ?>
					</p>
				</div>

				<!-- 2021 -->
				<div class="card p-6 border-navy-100 hover:border-primary-300 transition-all hover:shadow-card flex flex-col">
					<div class="inline-flex items-center gap-1.5 self-start px-3 py-1 rounded-full bg-primary-50 text-primary-700 font-extrabold text-xs mb-4">
						<?php bs_the_icon( 'calendar', 13 ); ?> 2021
					</div>
					<h3 class="font-bold text-navy-800 text-lg mb-2"><?php esc_html_e( 'Advanced Eye Care Closer to Home', 'bshealthcare' ); ?></h3>
					<p class="text-sm text-navy-600 leading-relaxed">
						<?php esc_html_e( 'We expanded into comprehensive eye examinations and optometry services, and introduced Phaco cataract surgery, a safe, stitch-free technique that means faster recovery and clearer vision.', 'bshealthcare' ); ?>
					</p>
				</div>

				<!-- 2022 -->
				<div class="card p-6 border-navy-100 hover:border-primary-300 transition-all hover:shadow-card flex flex-col">
					<div class="inline-flex items-center gap-1.5 self-start px-3 py-1 rounded-full bg-amber-50 text-amber-800 font-extrabold text-xs mb-4">
						<?php bs_the_icon( 'award', 13 ); ?> 2022
					</div>
					<h3 class="font-bold text-navy-800 text-lg mb-2"><?php esc_html_e( 'Modular OT & Super-Specialty Diagnostics', 'bshealthcare' ); ?></h3>
					<p class="text-sm text-navy-600 leading-relaxed">
						<?php esc_html_e( 'Commissioned dedicated modular operation theatres and advanced digital retinal diagnostic imaging, expanding accessible eye surgery to rural and semi-urban communities.', 'bshealthcare' ); ?>
					</p>
				</div>

				<!-- 2026 -->
				<div class="card p-6 border-primary-300 bg-primary-50/30 hover:shadow-card transition-all flex flex-col">
					<div class="inline-flex items-center gap-1.5 self-start px-3 py-1 rounded-full bg-primary-600 text-white font-extrabold text-xs mb-4">
						<?php bs_the_icon( 'shield', 13 ); ?> 2026
					</div>
					<h3 class="font-bold text-navy-800 text-lg mb-2"><?php esc_html_e( 'Netrana Eye Hospital, Gaya', 'bshealthcare' ); ?></h3>
					<p class="text-sm text-navy-600 leading-relaxed">
						<?php esc_html_e( 'Our vision reached Gaya with the opening of Netrana Eye Hospital, a new state-of-the-art branch bringing stitchless Phaco cataract surgery and complete optometry services to Gaya and surrounding communities.', 'bshealthcare' ); ?>
					</p>
				</div>

				<!-- Today and Tomorrow -->
				<div class="card p-6 border-emerald-300 bg-emerald-50/30 hover:shadow-card transition-all flex flex-col">
					<div class="inline-flex items-center gap-1.5 self-start px-3 py-1 rounded-full bg-emerald-600 text-white font-extrabold text-xs mb-4">
						<?php bs_the_icon( 'heart', 13 ); ?> <?php esc_html_e( 'Our Promise', 'bshealthcare' ); ?>
					</div>
					<h3 class="font-bold text-navy-800 text-lg mb-2"><?php esc_html_e( 'Today and Tomorrow', 'bshealthcare' ); ?></h3>
					<p class="text-sm text-navy-600 leading-relaxed">
						<?php esc_html_e( 'Through ongoing community screening and awareness, we continue to reach more villages and families, working tirelessly towards a community free from avoidable blindness.', 'bshealthcare' ); ?>
					</p>
				</div>
			</div>
		</div>
	</section>

	<!-- Hospital Inauguration & Facilities Gallery -->
	<?php get_template_part( 'template-parts/sections/gallery' ); ?>

	<!-- Editorial page content if any was added in WP Admin -->
	<?php
	while ( have_posts() ) :
		the_post();
		$content = trim( wp_strip_all_tags( get_the_content() ) );
		if ( $content ) :
			?>
			<section class="section bg-white border-t border-navy-100">
				<div class="container-x max-w-4xl prose-cms">
					<?php the_content(); ?>
				</div>
			</section>
			<?php
		endif;
	endwhile;
	?>

	<!-- Why Choose Us -->
	<?php if ( $why ) : ?>
	<section class="section bg-navy-50/60">
		<div class="container-x">
			<?php
			bs_section_header(
				array(
					'eyebrow'  => __( 'Why Choose Us', 'bshealthcare' ),
					'title'    => bs_opt( 'why_headline' ),
					'subtitle' => bs_opt( 'why_subtitle' ),
					'align'    => 'center',
				)
			);
			?>
			<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
				<?php foreach ( $why as $i => $w ) : ?>
					<div class="card-hover p-6 bg-white">
						<span class="icon-tile mb-4"><?php bs_the_icon( $icons[ $i % 4 ], 22 ); ?></span>
						<h3 class="font-bold text-navy-800 text-lg mb-1.5"><?php echo esc_html( $w['title'] ); ?></h3>
						<p class="text-sm text-navy-500 leading-relaxed"><?php echo esc_html( $w['desc'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<!-- Doctors Section -->
	<?php get_template_part( 'template-parts/sections/doctors' ); ?>

	<!-- Book / Visit CTA -->
	<section class="section bg-navy-900 text-white">
		<div class="container-x">
			<div class="flex flex-col lg:flex-row items-center justify-between gap-8 text-center lg:text-left">
				<div>
					<span class="text-xs font-bold uppercase tracking-widest text-primary-400 mb-2 block"><?php esc_html_e( 'OPD Timings: 8:00 AM – 6:00 PM', 'bshealthcare' ); ?></span>
					<h3 class="text-2xl sm:text-3xl font-extrabold text-white mb-2"><?php esc_html_e( 'Consult Our Eye Specialists in Gaya', 'bshealthcare' ); ?></h3>
					<p class="text-navy-300 max-w-xl text-sm sm:text-base"><?php echo esc_html( bs_opt( 'address' ) ); ?></p>
				</div>
				<div class="flex flex-wrap items-center justify-center gap-3 shrink-0">
					<button type="button" class="btn bg-primary-600 hover:bg-primary-500 text-white btn-lg" data-bs-book>
						<?php bs_the_icon( 'calendar', 18 ); ?> <?php esc_html_e( 'Book Appointment', 'bshealthcare' ); ?>
					</button>
					<a href="<?php echo esc_attr( bs_phone_href( $phone ) ); ?>" class="btn border border-white/20 text-white hover:bg-white/10 btn-lg">
						<?php bs_the_icon( 'phone', 18 ); ?> <?php echo esc_html( $phone ); ?>
					</a>
					<?php if ( $wa ) : ?>
					<a href="<?php echo esc_url( $wa ); ?>" target="_blank" rel="noopener" class="btn bg-emerald-600 hover:bg-emerald-500 text-white btn-lg">
						<?php bs_the_icon( 'message', 18 ); ?> WhatsApp
					</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
