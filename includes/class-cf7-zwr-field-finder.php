<?php

/**
 * Filter submitted Contact Form 7 fields by their name or guess the required fields.
 *
 * @since      1.0.0
 * @package    CF7_ZWR
 * @subpackage CF7_ZWR/includes
 * @author     Usame Algan
 */
Class CF7_ZWR_Field_Finder {
    protected $fields;

    public function __construct($fields) {
        $this->fields = $fields;
    }

    public function prefixed($namespace = 'cf7zwr-') {
        $fields_with_prefix = array();
        $length = strlen($namespace);

        foreach($this->fields as $key => $value) {
            if(strpos($key, $namespace) === 0) {
                $key_without_prefix = substr($key, $length);
                $fields_with_prefix[$key_without_prefix] = $value;
            }
        }

        return $fields_with_prefix;
    }

    /**
     * Find fields for first_name and email in case no prefix was used.
     * @return array
     */
    public function guessed() {
        $fields_guessed = array();

        foreach($this->fields as $key => $value) {
            if(preg_match('/email|mail/i', $key)) {
                $fields_guessed["email"] = $value;
            }

            $simple_key = str_replace( array( '-', '_', ' ' ), '', $key );

            if( empty($fields_guessed["first_name"]) && preg_match('/firstname/i', $simple_key)) {
                $fields_guessed["first_name"] = $value;
            } elseif( empty($fields_guessed["first_name"]) && preg_match('/name|lastname|last/i', $simple_key)) {
                $fields_guessed["first_name"] = $value;
            }
        }

        return $fields_guessed;
    }

    public function combine(array $methods) {
        $combined_fields = array();

        foreach ( $methods as $method ) {
            if ( method_exists( $this, $method ) ) {
                $combined_fields = array_merge( $combined_fields, call_user_func( array( $this, $method ) ) );
            }
        }

        return $combined_fields;
    }
}
