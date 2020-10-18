<?php

/**
 * Registers settings page and handles the saved data.
 * @since      1.0.0
 * @package    CF7_ZWR
 * @subpackage CF7_ZWR/admin
 * @author     Usame Algan
 */
class CF7_ZWR_Admin {

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

    public function display_api_key_field() {
        $settings = CF7_ZWR::get_options(); ?>
        <input type="text" value="<?php echo esc_attr($settings['api_key']); ?>" name="cf7_zwr[api_key]" /><?php
    }

    public function display_api_secret_field() {
        $settings = CF7_ZWR::get_options(); ?>
        <input type="password" value="<?php echo esc_attr($settings['api_secret']); ?>" name="cf7_zwr[api_secret]" /><?php
    }
	
	public function initialize_settings_panel($panels) {
        $panels['cf7zwr-custom-fields'] = array(
            'title' => 'Zoom',
            'callback' => array($this, 'display_custom_fields'),
        );

        return $panels;
    }

    public function display_custom_fields($contactform) {
        $webinar_id = get_post_meta($contactform->id(), 'cf7zwr-webinar_id', true); ?>
        <fieldset>
            <label for="wpcf7-custom-field"><?php _e('Webinar-ID', 'cf7-zwr'); ?></label>
            <input type="text" id="wpcf7-custom-field" name="cf7_zwr_webinar_id" value="<?php echo $webinar_id ?>" />
        </fieldset><?php
        $api = new CF7_ZWR_Api($this->plugin_name, $this->version);
        if ($webinar_id) {
            $webinar_fields = $api->get_webinar_questions($webinar_id);
            $combined_questions = array_merge($api->required_questions, $webinar_fields["questions"]);
            $this->display_webinar_questions($combined_questions);
            $this->display_missing_fields_notice($contactform, $webinar_fields);
        }
    }

    public function display_webinar_questions($webinar_fields) { ?>
        <p><?php _e('Fields found for this webinar', 'cf7-zwr'); ?></p>
        <table class="widefat striped">
            <thead>
            <tr>
                <td><?php _e('Field name', 'cf7-zwr'); ?></td>
                <td><?php _e('Required', 'cf7-zwr'); ?></td>
            </tr>
            </thead>
            <tbody>
            <?php foreach($webinar_fields as $field) : ?>
                <tr>
                    <td><?php echo $field['field_name']; ?></td>
                    <td><?php echo $field['required'] ? "true" : "false" ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table><?php
    }

    public function display_missing_fields_notice($contactform, $webinar_fields) {
        $cf7_form_tags = $contactform->scan_form_tags();
        $stripped_fields = array_map(function($field) { return self::strip_prefix($field["name"], "cf7zwr-"); }, $cf7_form_tags);
        $stripped_fields = array_filter($stripped_fields);
        $missing_fields = array();

        foreach($webinar_fields as $zoom_field) {
            if (!in_array($zoom_field["field_name"], $stripped_fields) && $zoom_field["required"]) {
                $missing_fields[] = $zoom_field["field_name"];
            }
        }
        if (count($missing_fields) > 0) : ?>
            <div class="notice inline notice-warning notice-alt">
                <p><?php _e("Can't find the following required field(s):", "cf7-zwr"); ?></p>
                <p><?php echo(implode(",", $missing_fields)); ?></p>
            </div>
        <?php endif;
    }

    public function strip_prefix($value, $prefix) {
        if (strpos($value, $prefix) === 0) {
            return substr($value, strlen($prefix));
        }
    }

    public function save_custom_fields($contactform) {
        if (array_key_exists('cf7_zwr_webinar_id', $_POST)) {
            $webinarId = str_replace(" ", "", $_POST['cf7_zwr_webinar_id']);
            update_post_meta(
                $contactform->id(),
                'cf7zwr-webinar_id',
                $webinarId
            );
        }
    }
}
