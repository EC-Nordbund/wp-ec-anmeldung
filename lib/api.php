<?php


function eca_api_form_submission() {
    register_rest_route('ec-api/v1', '/anmeldung', array(
        'methods' => 'POST',
        'callback' => 'eca_handle_registration'
    ));
}

function eca_handle_registration( WP_REST_Request $request) {
    $data = $request->get_params();

    $response = new stdClass(); // json: {}

    if(isset($data)) {
        // TODO
    }

    return $response;
}