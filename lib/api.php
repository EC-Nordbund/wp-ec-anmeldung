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

        if(empty($data['email'])) {
            $error['email'] = 'E-Mail is a required field and was empty.';
        }
            
        if(empty($error)) {
            $email_to = $data['email'];

            $response = eca_add_registration($params['eventID'], $params['data']);
        } else {        
            $response = array('error' => $error);
        }
    }

    return $response;
}