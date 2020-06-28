<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

$option_name = 'cf7_zwr';
delete_option($option_name);

function cf7zwr_delete_plugin_data() {
	global $wpdb;

	$postmeta_table = $wpdb->postmeta;

	$wpdb->query("DELETE FROM " . $postmeta_table . " WHERE meta_key = 'cf7zwr-webinar_id'");
}
cf7zwr_delete_plugin_data();