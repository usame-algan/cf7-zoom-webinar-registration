<?php

/**
 * Main Class. Loads dependencies and registers hooks
 * @since      1.0.0
 * @package    CF7_ZWR
 * @subpackage CF7_ZWR/includes
 * @author     Usame Algan
 */
class CF7_ZWR {
    protected $loader;
    protected $plugin_name;
    protected $version;

    public function __construct() {
        if ( defined( 'CF7_ZWR_VERSION' ) ) {
            $this->version = CF7_ZWR_VERSION;
        } else {
            $this->version = '1.0.0';
        }

        $this->plugin_name = 'cf7-zwr';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    private function load_dependencies() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cf7-zwr-loader.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cf7-zwr-i18n.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cf7-zwr-field-finder.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cf7-zwr-api.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cf7-zwr-admin.php';

        $this->loader = new CF7_ZWR_Loader();
    }

    private function set_locale() {
        $plugin_i18n = new CF7_ZWR_I18N();
        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
    }

    private function define_admin_hooks() {
        $cf7_zwr_admin = new CF7_ZWR_Admin( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'admin_menu', $cf7_zwr_admin, 'add_setting_page' );
        $this->loader->add_action( 'admin_init', $cf7_zwr_admin, 'initialize_settings' );
		$this->loader->add_action( 'wpcf7_save_contact_form', $cf7_zwr_admin, 'save_custom_fields');
        $this->loader->add_filter( 'wpcf7_editor_panels', $cf7_zwr_admin, 'initialize_settings_panel');
    }

    private function define_public_hooks() {
        $cf7_zwr_public = new CF7_ZWR_Api( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'wpcf7_before_send_mail', $cf7_zwr_public, 'send_registration', 10, 3 );
    }

    public function run() {
        $this->loader->run();
    }

    public function get_plugin_name() {
        return $this->plugin_name;
    }

    public function get_loader() {
        return $this->loader;
    }

    public function get_version() {
        return $this->version;
    }

    public static function get_options() {
        $options = (array) get_option('cf7_zwr');
        return $options;
    }
}
