<?php
/**
 * Theme Settings page (Appearance → Hospital Settings).
 *
 * @package BSHealthcare
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bs_settings_tabs() {
	return array(
		'general'      => array(
			'label'  => __( 'General & Contact', 'bshealthcare' ),
			'fields' => array(
				'site_tagline'     => array( 'Tagline (under logo)', 'text' ),
				'phone'            => array( 'Phone', 'text' ),
				'emergency'        => array( 'Emergency number (optional)', 'text' ),
				'email'            => array( 'Email', 'email' ),
				'address'          => array( 'Address', 'text' ),
				'hours_opd'        => array( 'OPD hours', 'text' ),
				'hours_inpatient'  => array( 'Inpatient care', 'text' ),
				'whatsapp_enabled' => array( 'Show WhatsApp button', 'checkbox' ),
				'whatsapp_number'  => array( 'WhatsApp number (with country code)', 'text', 'e.g. 919525334214' ),
				'whatsapp_message' => array( 'WhatsApp default message', 'text' ),
				'map_embed'        => array( 'Google Maps embed URL', 'textarea', 'Google Maps → Share → Embed a map → copy the src="…" URL only.' ),
				'social_facebook'  => array( 'Facebook URL', 'url' ),
				'social_twitter'   => array( 'Twitter / X URL', 'url' ),
				'social_instagram' => array( 'Instagram URL', 'url' ),
				'social_linkedin'  => array( 'LinkedIn URL', 'url' ),
				'social_youtube'   => array( 'YouTube URL', 'url' ),
			),
		),
		'home'         => array(
			'label'  => __( 'Home Page', 'bshealthcare' ),
			'fields' => array(
				'home_sections'      => array( 'Sections (order & visibility)', 'text', 'Comma separated. Available: hero, ayushman, why, about, stats, services, doctors, testimonials, blogs, faq. Remove a name to hide it, reorder to move it.' ),
				'hero_eyebrow'       => array( 'Hero eyebrow', 'text' ),
				'hero_title'         => array( 'Hero title', 'text', 'Wrap a word in {curly braces} to colour it.' ),
				'hero_subtitle'      => array( 'Hero subtitle', 'textarea' ),
				'hero_image'         => array( 'Hero image', 'image' ),
				'hero_overlay'       => array( 'Hero image darkness (%)', 'number', 'How much the hero photo is darkened so the white text stays readable. 0 = original photo, 55 = default, 90 = almost solid colour.' ),
				'hero_cta'           => array( 'Primary button text', 'text' ),
				'hero_secondary'     => array( 'Secondary button text', 'text', 'Leave empty to hide.' ),
				'hero_secondary_url' => array( 'Secondary button link', 'url', 'e.g. a YouTube video. Empty = About page.' ),
				'hero_highlights'    => array( 'Hero highlights (one per line)', 'textarea' ),
				'hero_rating'        => array( 'Rating badge', 'text' ),
				'hero_rating_sub'    => array( 'Rating badge subtitle', 'text' ),
				'ayushman_headline'  => array( 'Ayushman headline', 'text' ),
				'ayushman_subtitle'  => array( 'Ayushman subtitle', 'textarea' ),
				'ayushman_benefits'  => array( 'Ayushman cards (one per line: Title|Description|Image URL)', 'textarea' ),
				'ayushman_note'      => array( 'Ayushman note', 'text' ),
				'why_headline'       => array( 'Why Choose Us headline', 'text' ),
				'why_subtitle'       => array( 'Why Choose Us subtitle', 'text' ),
				'why_items'          => array( 'Why Choose Us cards (one per line: Title|Description|Image URL)', 'textarea' ),
				'about_headline'     => array( 'About headline', 'text' ),
				'about_text'         => array( 'About text', 'textarea', 'The four cards below it come from the first four Specialities.' ),
				'stats_items'        => array( 'Stats (one per line: Number|Label)', 'textarea' ),
				'services_headline'  => array( 'Services headline', 'text' ),
				'services_subtitle'  => array( 'Services subtitle', 'text' ),
				'doctors_headline'   => array( 'Doctors headline', 'text' ),
				'doctors_subtitle'   => array( 'Doctors subtitle', 'text' ),
				'testimonials_headline' => array( 'Testimonials headline', 'text' ),
				'blogs_headline'     => array( 'Blog headline', 'text' ),
				'faq_headline'       => array( 'FAQ headline', 'text' ),
				'faq_items'          => array( 'FAQ (one per line: Question|Answer)', 'textarea' ),
			),
		),
		'colors'       => array(
			'label'  => __( 'Colors', 'bshealthcare' ),
			'fields' => array(
				'color_primary' => array( 'Brand colour', 'color', 'Buttons, links, icons and highlights. Leave empty to keep the default medical teal.' ),
				'color_dark'    => array( 'Dark colour', 'color', 'Headings, the footer and dark sections like the hero. Leave empty to keep the default navy.' ),
			),
		),
		'footer'       => array(
			'label'  => __( 'Footer', 'bshealthcare' ),
			'fields' => array(
				'footer_description' => array( 'Footer description', 'textarea' ),
				'footer_copyright'   => array( 'Copyright line', 'text' ),
			),
		),
		'appointments' => array(
			'label'  => __( 'Appointments', 'bshealthcare' ),
			'fields' => array(
				'appt_slots'         => array( 'Time slots (comma separated)', 'text' ),
				'appt_fee'           => array( 'Default consultation fee (₹)', 'number' ),
				'appt_auto_confirm'  => array( 'Auto-confirm new bookings', 'checkbox', 'Unchecked = bookings start as "Pending" until admin/doctor confirms.' ),
				'appt_require_login' => array( 'Patients must log in to book', 'checkbox' ),
				'appt_notify_email'  => array( 'Notification email for new bookings & contact form', 'email' ),
			),
		),
		'popup'        => array(
			'label'  => __( 'Advertisement Popup', 'bshealthcare' ),
			'fields' => array(
				'ad_enabled' => array( 'Enable popup', 'checkbox', 'Shown once per browser session on the home page.' ),
				'ad_title'   => array( 'Title', 'text' ),
				'ad_text'    => array( 'Text', 'textarea' ),
				'ad_image'   => array( 'Image', 'image' ),
				'ad_button'  => array( 'Button text', 'text' ),
			),
		),
	);
}

function bs_settings_menu() {
	add_menu_page( __( 'Hospital Settings', 'bshealthcare' ), __( 'Hospital Settings', 'bshealthcare' ), 'manage_options', 'bs-settings', 'bs_settings_page', 'dashicons-admin-generic', 20 );
}
add_action( 'admin_menu', 'bs_settings_menu' );

function bs_register_settings() {
	register_setting( 'bs_settings_group', 'bs_settings', array( 'sanitize_callback' => 'bs_sanitize_settings' ) );
}
add_action( 'admin_init', 'bs_register_settings' );

function bs_sanitize_settings( $input ) {
	if ( ! is_array( $input ) ) {
		return get_option( 'bs_settings', array() );
	}
	// WordPress can run the sanitize callback more than once per save; the second
	// pass receives our already-sanitised array, which no longer carries `_tab`.
	// Returning it unchanged keeps the saved values instead of wiping them.
	$tab  = isset( $input['_tab'] ) ? sanitize_key( $input['_tab'] ) : '';
	$tabs = bs_settings_tabs();
	if ( ! isset( $tabs[ $tab ] ) ) {
		return $input;
	}

	$out = get_option( 'bs_settings', array() );
	$out = is_array( $out ) ? $out : array();
	foreach ( $tabs[ $tab ]['fields'] as $key => $f ) {
		$raw = isset( $input[ $key ] ) ? $input[ $key ] : '';
		switch ( $f[1] ) {
			case 'checkbox':
				$out[ $key ] = $raw ? '1' : '';
				break;
			case 'textarea':
				$out[ $key ] = sanitize_textarea_field( $raw );
				break;
			case 'email':
				$out[ $key ] = sanitize_email( $raw );
				break;
			case 'url':
			case 'image':
				$out[ $key ] = esc_url_raw( $raw );
				break;
			case 'number':
				$out[ $key ] = '' === $raw ? '' : (string) (float) $raw;
				break;
			case 'color':
				$hex         = sanitize_hex_color( $raw );
				$out[ $key ] = $hex ? $hex : '';
				break;
			default:
				$out[ $key ] = sanitize_text_field( $raw );
		}
	}
	return $out;
}

function bs_settings_page() {
	$tabs = bs_settings_tabs();
	$cur  = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'general'; // phpcs:ignore WordPress.Security.NonceVerification
	if ( ! isset( $tabs[ $cur ] ) ) {
		$cur = 'general';
	}
	$defaults = bs_default_settings();
	?>
	<div class="wrap bs-settings">
		<h1><?php esc_html_e( 'Hospital Settings', 'bshealthcare' ); ?></h1>
		<p class="description"><?php esc_html_e( 'Everything the public website shows – contact details, home page sections, appointments, footer and popup – is controlled here. Doctors, Specialities, Testimonials, Appointments and Prescriptions have their own menus on the left.', 'bshealthcare' ); ?></p>
		<nav class="nav-tab-wrapper">
			<?php foreach ( $tabs as $k => $t ) : ?>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=bs-settings&tab=' . $k ) ); ?>" class="nav-tab <?php echo $cur === $k ? 'nav-tab-active' : ''; ?>"><?php echo esc_html( $t['label'] ); ?></a>
			<?php endforeach; ?>
		</nav>
		<form method="post" action="options.php">
			<?php settings_fields( 'bs_settings_group' ); ?>
			<input type="hidden" name="bs_settings[_tab]" value="<?php echo esc_attr( $cur ); ?>">
			<table class="form-table" role="presentation">
				<?php foreach ( $tabs[ $cur ]['fields'] as $key => $f ) :
					$val  = bs_opt( $key, isset( $defaults[ $key ] ) ? $defaults[ $key ] : '' );
					$name = 'bs_settings[' . $key . ']';
					?>
					<tr>
						<th scope="row"><label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $f[0] ); ?></label></th>
						<td>
							<?php if ( 'textarea' === $f[1] ) : ?>
								<textarea name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $key ); ?>" rows="5" class="large-text code"><?php echo esc_textarea( $val ); ?></textarea>
							<?php elseif ( 'checkbox' === $f[1] ) : ?>
								<label><input type="checkbox" name="<?php echo esc_attr( $name ); ?>" value="1" <?php checked( '1', (string) $val ); ?>> <?php esc_html_e( 'Enabled', 'bshealthcare' ); ?></label>
							<?php elseif ( 'image' === $f[1] ) : ?>
								<div class="bs-image-field">
									<input type="text" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $key ); ?>" class="regular-text" value="<?php echo esc_url( $val ); ?>">
									<button type="button" class="button bs-upload"><?php esc_html_e( 'Choose image', 'bshealthcare' ); ?></button>
									<?php if ( $val ) : ?><br><img src="<?php echo esc_url( $val ); ?>" style="max-height:90px;margin-top:8px;border-radius:6px"><?php endif; ?>
								</div>
							<?php elseif ( 'color' === $f[1] ) : ?>
								<input type="text" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $key ); ?>" class="bs-color-field" value="<?php echo esc_attr( $val ); ?>" data-default-color="<?php echo esc_attr( 'color_primary' === $key ? '#0891b2' : '#0f2438' ); ?>">
							<?php else : ?>
								<input type="<?php echo esc_attr( $f[1] ); ?>" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $key ); ?>" class="regular-text" value="<?php echo esc_attr( $val ); ?>">
							<?php endif; ?>
							<?php if ( ! empty( $f[2] ) ) : ?><p class="description"><?php echo esc_html( $f[2] ); ?></p><?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
			<?php if ( 'colors' === $cur ) : ?>
				<h2 class="title"><?php esc_html_e( 'Ready-made palettes', 'bshealthcare' ); ?></h2>
				<p class="description"><?php esc_html_e( 'Click one to fill both colours, then Save Changes.', 'bshealthcare' ); ?></p>
				<div class="bs-presets">
					<?php foreach ( bs_color_presets() as $slug => $p ) : ?>
						<button type="button" class="bs-preset" data-primary="<?php echo esc_attr( $p['primary'] ); ?>" data-dark="<?php echo esc_attr( $p['dark'] ); ?>">
							<span class="bs-preset-swatch"><i style="background:<?php echo esc_attr( $p['dark'] ); ?>"></i><i style="background:<?php echo esc_attr( $p['primary'] ); ?>"></i></span>
							<?php echo esc_html( $p['label'] ); ?>
						</button>
					<?php endforeach; ?>
				</div>
				<p class="description" style="margin-top:12px">
					<?php esc_html_e( 'Tip: clear both fields to go back to the theme default. Very light brand colours can make white button text hard to read — pick a medium or dark shade.', 'bshealthcare' ); ?>
				</p>
			<?php endif; ?>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
