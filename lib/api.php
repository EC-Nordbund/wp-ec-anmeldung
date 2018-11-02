<?php

if (!defined('ABSPATH')) exit;

require_once 'database.php';

function eca_api_form_submission() {
    register_rest_route('ec-api/v1', '/anmeldung', array(
        'methods' => 'POST',
        'accept_json' => true,
        'callback' => 'eca_handle_registration'
    ));
}

function eca_handle_registration( WP_REST_Request $request) {
    $response = new stdClass(); // json: {}

    $params = $request->get_json_params();

    if(isset($params['data']) && isset($params['eventID'])) {
        $data = $params['data'];

        $error = array();

        $email_to = eca_get_value_of_key_r('email', $data);

        if(!isset($email_to)) {
            $error['email'] = 'E-Mail field is required but missing.';
        }

        if(empty($error) && empty($email_to)) {
            $error['email'] = 'E-Mail field is empty.';
        }
            
        if(empty($error)) {
            $response = eca_add_registration($params['eventID'], $params['data']);
        } else {        
            $response = array('error' => $error);
        }
    }

    return $response;
}

function eca_get_value_of_key_r($key, $array) {
    if(array_key_exists($key, $array)) return $array[$key];

    $result = null;

    foreach ($array as $subarray) {
        if(is_array($subarray)) {
            $result = eca_get_value_of_key_r($key, $subarray);
        }
        if(isset($result)) return $result;  // found key
    }
    return $result; // key not in array
}