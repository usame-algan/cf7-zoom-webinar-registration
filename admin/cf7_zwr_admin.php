<?php
/**
 * Admin class of CF7 Zoom Webinar Registration
 * @author Usame Algan
 */

class CF7_ZWR_admin {

    public function __construct() {
        add_action('admin_menu', array($this, 'cf7_zwr_admin_add_page'));
        add_action('admin_init', array($this, 'cf7_zwr_admin_init'));
    }

    public function cf7_zwr_admin_add_page() {
        add_options_page('CF7 Zoom Webinar Registration Settings', 'CF7 ZWR', 'manage_options', 'cf7-zwr', array($this, 'cf7_zwr_page'));
    }

    public function cf7_zwr_page(){ ?>
        <div class="wrap">
        <h2>CF7 Zoom Webinar Registration <?php _e('Settings', 'cf7-zoom-webinar-registration'); ?></h2>

        <form method="post" action="options.php"><?php
            settings_fields('cf7_zwr_option_group');
            //do_settings_sections('wp-es');
            submit_button(__('Save Changes'), 'primary', 'submit', false); ?>
        </form>
        </div><?php
    }

    public function cf7_zwr_admin_init() {
        register_setting('cf7_zwr_option_group', 'cf7_zwr_options', array($this, 'cf7_zwr_save'));
    }
}