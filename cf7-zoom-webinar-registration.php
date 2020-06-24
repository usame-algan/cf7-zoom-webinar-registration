<?php
/*
 Plugin Name: CF7 Zoom Webinar registration
 Plugin URI: https://github.com/usame-algan/cf7-zoom-webinar-registration
 Description: Register attendees for a Zoom Webinar through Contact Form 7
 Version: 1.0.0
 Author: Usame Algan
 Author URI: https://usamealgan.com
 Text Domain: cf7-zoom-webinar-registration
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Plugin constants */
if (!defined('CF7_ZWR_VERSION')) {
    define('CF7_ZWR_VERSION', '1.0.0' );
}

if (!defined('CF7_ZWR_PATH')) {
    define('CF7_ZWR_PATH', plugin_dir_path( __FILE__ ) );
}

if (!defined('CF7_ZWR_ZOOM_API_URL')) {
    define('CF7_ZWR_ZOOM_API_URL', 'https://api.zoom.us/v2' );
}

add_action('wpcf7_init', 'cf7_zwr_init');
function cf7_zwr_init() {
    require_once CF7_ZWR_PATH . 'vendor/autoload.php';
    require_once CF7_ZWR_PATH . 'includes/cf7_zwr.php';
}

if (is_admin()) {
    require_once CF7_ZWR_PATH . 'admin/cf7_zwr_admin.php';
}