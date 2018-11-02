<?php

if (!defined('ABSPATH')) exit;

require_once 'database.php';
require_once 'mail.php';

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

    if(isset($params['schema']) && isset($params['data']) && isset($params['eventID'])) {
        $event_id = $params['eventID'];
        $data = $params['data'];
        $schema = $params['schema'];

        $error = array();

        $email_to = eca_get_value_of_key_r('email', $data);

        if(!isset($email_to)) {
            $error['email'] = 'E-Mail field is required but missing.';
        }

        if(empty($error) && empty($email_to)) {
            $error['email'] = 'E-Mail field is empty.';
        }
        
        // Add registration to database
        if(empty($error)) {
            $db_access = eca_add_registration($event_id, $data);

            // Add errors
            if(isset($db_access['error'])) {
                $error['database'] = $db_access['error'];
            } else {
                $response = array('database' => $db_access);
            }
        }

        if(empty($error)) {
            $email_status = eca_confirmation_mail($email_to, $event_id, $data);

            // Add errors
            if(isset($email_status['error'])) {
                $error['confirmation_mail'] = $email_status['error'];
            } else {
                $response['confirmation_mail'] = array('sended' => $email_status);
            }
        }

        if(!empty($error)) {
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