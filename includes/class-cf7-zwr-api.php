<?php

use \Firebase\JWT\JWT;

/**
 * Handles requests to the Zoom API
 * @since      1.0.0
 * @package    CF7_ZWR
 * @subpackage CF7_ZWR/includes
 * @author     Usame Algan
 */
class CF7_ZWR_Api {
    private $plugin_name;
    private $version;

    protected $api_url = 'https://api.zoom.us/v2';
    protected $required_questions;

    protected $token = '';
    protected $method;
    protected $url;
    protected $arguments;
    protected $response;
    protected $body;
    protected $headers;
    protected $response_code;
    protected $response_message;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        $this->required_questions = array(
            array(
                "field_name" => "first_name",
                "required" => true
            ),
            array(
                "field_name" => "email",
                "required" => true
            ),
        );
    }

    public function build($url, $arguments = array(), $method = "GET") {
        $this->token               = $this->build_token();
        $this->url                 = $url;
        $this->method              = strtoupper( $method );
        $this->arguments           = $arguments;
        $this->arguments['method'] = $this->method;
    }

    public function build_token() {
        $options = CF7_ZWR::get_options();
        $key = $options['api_key'];
        $secret = $options['api_secret'];
        $token = array(
            'iss' => $key,
            'exp' => time() + 60,
        );

        return JWT::encode($token, $secret);
    }

    public function run($token) {
        $this->arguments['headers']['Authorization'] = 'Bearer' . $token;
        $this->arguments['headers']['Content-Type'] = 'application/json';

        $this->response         = wp_remote_request( $this->url, $this->arguments );
        $this->body             = wp_remote_retrieve_body( $this->response );
        $this->headers          = wp_remote_retrieve_headers( $this->response );
        $this->response_code    = wp_remote_retrieve_response_code( $this->response );
        $this->response_message = wp_remote_retrieve_response_message( $this->response );
    }

    public function send_registration($contactform, &$abort, $submission) {
        $wpcf = $contactform::get_current();
        $posted_data = $submission->get_posted_data();
        $webinar_id = get_post_meta($contactform->id(), 'cf7zwr-webinar_id', true);

        $field_parser = new CF7_ZWR_Field_Finder($posted_data);
        $fields = $field_parser->combine(['prefixed', 'guessed']);

        if ($webinar_id) {
            $this->build($this->api_url . '/webinars/' . $webinar_id . '/registrants', array('body' => wp_json_encode($fields)), 'POST');
            $this->run($this->token);
        }
        return $wpcf;
    }

    public function get_webinar_questions($webinar_id) {
        $this->build($this->api_url . '/webinars/' . $webinar_id . '/registrants/questions', array(), 'GET');
        $this->run($this->token);

        $combined_questions = array_merge($this->required_questions, json_decode($this->body, true)["questions"]);

        return $combined_questions;
    }

}
