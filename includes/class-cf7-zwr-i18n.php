<?php
/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    CF7_ZWR
 * @subpackage CF7_ZWR/includes
 * @author     Usame Algan
 */
class CF7_ZWR_i18n {

    public function load_plugin_textdomain() {

        load_plugin_textdomain(
            'cf7-zwr',
            false,
            dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
        );

    }

}
