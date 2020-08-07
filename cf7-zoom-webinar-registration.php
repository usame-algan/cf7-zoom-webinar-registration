<?php
/*
 Plugin Name: CF7 Zoom Webinar registration
 Plugin URI: https://github.com/usame-algan/cf7-zoom-webinar-registration
 Description: Allow registrations for your Zoom Webinar through Wordpress Contact Form 7
 Version: 1.0.5
 Author: Usame Algan
 Author URI: https://usamealgan.com
 Text Domain: cf7-zwr
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin constants
 */
if (!defined('CF7_ZWR_VERSION')) {
    define('CF7_ZWR_VERSION', '1.0.5' );
}

if (!defined('CF7_ZWR_PATH')) {
    define('CF7_ZWR_PATH', plugin_dir_path( __FILE__ ));
}

if (!defined('CF7_ZWR_PLUGIN_FILE')) {
    define('CF7_ZWR_PLUGIN_FILE', __FILE__);
}

/**
 * Check if Contact Form 7 is installed and activated.
 */
add_action( 'admin_init', 'cf7_zwr_has_parent_plugin' );
function cf7_zwr_has_parent_plugin() {
    if ( is_admin() && current_user_can( 'activate_plugins' ) &&  !is_plugin_active( 'contact-form-7/wp-contact-form-7.php' )) {
        add_action( 'admin_notices', 'cf7_zwr_nocf7_notice' );

        deactivate_plugins( plugin_basename( __FILE__ ) );

        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
    }
}

function cf7_zwr_nocf7_notice() { ?>
    <div class="error">
        <p>
            <?php printf(
                __('%s must be installed and activated for the CF7 Zoom Webinar Registration plugin to work', 'cf7-zwr'),
                '<a href="'.admin_url('plugin-install.php?tab=search&s=contact+form+7').'">Contact Form 7</a>'
            ); ?>
        </p>
    </div>
    <?php
}

require_once CF7_ZWR_PATH . 'vendor/autoload.php';
require_once CF7_ZWR_PATH . 'includes/class-cf7-zwr.php';

function run_cf7_zwr() {
    $cf7_zwr = new CF7_ZWR();
    $cf7_zwr->run();
}
run_cf7_zwr();
