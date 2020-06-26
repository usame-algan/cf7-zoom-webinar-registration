<?php
/*
 Plugin Name: CF7 Zoom Webinar registration
 Plugin URI: https://github.com/usame-algan/cf7-zoom-webinar-registration
 Description: Register attendees for a Zoom Webinar through Contact Form 7
 Version: 1.0.0
 Author: Usame Algan
 Author URI: https://usamealgan.com
 Text Domain: cf7-zwr
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Plugin constants */
if (!defined('CF7_ZWR_VERSION')) {
    define('CF7_ZWR_VERSION', '1.0.0' );
}

if (!defined('CF7_ZWR_PATH')) {
    define('CF7_ZWR_PATH', plugin_dir_path( __FILE__ ));
}

if (!defined('CF7_ZWR_PLUGIN_FILE')) {
    define('CF7_ZWR_PLUGIN_FILE', __FILE__);
}

require_once CF7_ZWR_PATH . 'vendor/autoload.php';
require_once CF7_ZWR_PATH . 'includes/class-cf7-zwr.php';

function run_cf7_zwr() {
    $cf7_zwr = new CF7_ZWR();
    $cf7_zwr->run();
}
run_cf7_zwr();
