<?php
/**
 * CF7 Zoom Webinar Registration API
 * @author Usame Algan
 */

use \Firebase\JWT\JWT;

class CF7_ZWR_api {
    private $plugin_name;
    private $version;

    protected $api_url = 'https://api.zoom.us/v2';
    protected $webinar_identifier = 'webinar_id:';
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

        $regex = '/(?<=\b' . $this->webinar_identifier . '\s)(?:[\w-]+)/is';
        preg_match($regex, $wpcf->additional_settings, $matches);

        $webinar_id = $matches[0];
        print_r($contactform);

        $field_parser = new CF7_ZWR_field_finder($posted_data);
        $fields = $field_parser->combine(['prefixed', 'guessed']);

        if ($webinar_id) {
            $this->build($this->api_url . '/webinars/' . $webinar_id . '/registrants', array('body' => wp_json_encode($fields)), 'POST');
            $this->run($this->token);

            /*
            print_r("Sent Fields: \n");
            print_r(wp_json_encode($fields));
            print_r("Response Header: \n");
            print_r($this->headers);
            print_r("Response Body: \n");
            print_r($this->body);
            print_r("Response Code: \n");
            print_r($this->response_code);
            print_r("Response Message: \n");
            print_r($this->response_message);
            */
        }
        return $wpcf;
    }

}