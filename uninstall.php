<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

$option_name = 'cf7_zwr';
delete_option($option_name);