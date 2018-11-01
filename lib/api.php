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
    $params = $request->get_json_params();

    $response = new stdClass(); // json: {}
    
    if( isset($params['eventID']) && isset($params['data']) ) {        
        $response = eca_add_registration($params['eventID'], $params['data']);
    }

    return $response;
}