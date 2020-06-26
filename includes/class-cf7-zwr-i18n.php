<?php

class CF7_ZWR_i18n {

    public function load_plugin_textdomain() {

        load_plugin_textdomain(
            'cf7-zwr',
            false,
            dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
        );

    }

}