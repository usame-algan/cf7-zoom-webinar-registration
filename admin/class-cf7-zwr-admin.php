<?php
/**
 * Admin class of CF7 Zoom Webinar Registration
 * @author Usame Algan
 */

class CF7_ZWR_admin {

    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function add_setting_page() {
        add_submenu_page('wpcf7','CF7 Zoom Webinar Registration Settings', 'Zoom Webinar Registration', 'manage_options', 'cf7-zwr', array($this, 'render_setting_page'));
    }

    public function render_setting_page(){ ?>
        <div class="wrap">
            <h2><?php _e('CF7 Zoom Webinar Registration Settings', 'cf7-zwr'); ?></h2>
            <form method="post" action="<?php echo admin_url('options.php'); ?>"><?php
                settings_fields('cf7_zwr_settings');
                do_settings_sections('cf7-zwr');
                submit_button(__('Save Changes'), 'primary', 'submit', false); ?>
            </form>
        </div><?php
    }

    public function initialize_settings() {
        register_setting('cf7_zwr_settings', 'cf7_zwr', array($this, 'save_settings'));

        add_settings_section('cf7_zwr_section_main', __('Insert your Zoom JWT App Credentials', 'cf7-zwr'), array($this, 'display_section_description'), 'cf7-zwr');

        add_settings_field( 'cf7_zwr_api_key', __('API Key' , 'cf7-zwr'), array($this, 'display_api_key_field'), 'cf7-zwr', 'cf7_zwr_section_main' );
        add_settings_field( 'cf7_zwr_api_secret', __('API Secret' , 'cf7-zwr'), array($this, 'display_api_secret_field'), 'cf7-zwr', 'cf7_zwr_section_main' );
    }

    public function display_section_description(){
        echo "";
    }

    public function save_settings($settings) {
        $settings['api_key'] = sanitize_text_field( $settings['api_key'] );
        $settings['api_secret'] = sanitize_text_field( $settings['api_secret'] );
        return $settings;
    }

    public function cf7_zwr_get_options() {
        $options = (array) get_option('cf7_zwr');
        return $options;
    }

    public function display_api_key_field() {
        $settings = CF7_ZWR::get_options(); ?>
        <input type="text" value="<?php echo esc_attr($settings['api_key']); ?>" name="cf7_zwr[api_key]" /><?php
    }

    public function display_api_secret_field() {
        $settings = CF7_ZWR::get_options(); ?>
        <input type="password" value="<?php echo esc_attr($settings['api_secret']); ?>" name="cf7_zwr[api_secret]" /><?php
    }
}