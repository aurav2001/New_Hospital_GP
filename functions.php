<?php
/**
 * BS Healthcare Hospital theme bootstrap.
 *
 * @package BSHealthcare
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BS_VERSION', '1.0.0' );
define( 'BS_DIR', get_template_directory() );
define( 'BS_URI', get_template_directory_uri() );

require_once BS_DIR . '/inc/helpers.php';
require_once BS_DIR . '/inc/setup.php';
require_once BS_DIR . '/inc/roles.php';
require_once BS_DIR . '/inc/post-types.php';
require_once BS_DIR . '/inc/meta-boxes.php';
require_once BS_DIR . '/inc/settings.php';
require_once BS_DIR . '/inc/colors.php';
require_once BS_DIR . '/inc/appointments.php';
require_once BS_DIR . '/inc/prescriptions.php';
require_once BS_DIR . '/inc/auth.php';
require_once BS_DIR . '/inc/ajax.php';
require_once BS_DIR . '/inc/emails.php';
require_once BS_DIR . '/inc/admin-dashboard.php';
require_once BS_DIR . '/inc/activation.php';
