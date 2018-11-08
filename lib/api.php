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
    register_rest_route('ec-api/v1', '/anmeldung/(?P<token>\S+)', array(
        'methods' => 'GET',
        'callback' => 'eca_registration_status',
        'agrs' => array(
            'token' => array(
                'validate_callback' => function($param, $request, $key) {
                    return is_string($param);
                }
            )
        )
    ));
    register_rest_route('ec-api/v1', '/anmeldungen', array(
        'methods' => 'GET',
        'callback' => 'eca_registrations_stats'
    ));
    }

function eca_handle_registration( WP_REST_Request $request) {
    $response = new stdClass(); // json: {}

    $params = $request->get_json_params();

    if(isset($params['schema']) && isset($params['data']) && isset($params['eventID'])) {
        $event_id = $params['eventID'];
        $data = $params['data'];
        $schema = $params['schema'];

        $token = 'test_token';

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
            $db_access = eca_add_registration($event_id, $data, $token);

            // Add errors
            if(isset($db_access['error'])) {
                $error['database'] = $db_access['error'];
            } else {
                $response = array('database' => $db_access);
            }
        }

        if(empty($error)) {
            $mailer_responce = eca_confirmation_mail($email_to, $event_id, $token, $data, $schema);

            // Add errors
            if(isset($mailer_responce['error'])) {
                $error['confirmation_mail'] = $mailer_responce['error'];
            } else {
                $response['confirmation_mail'] =  $mailer_responce;
            }
        }

        if(!empty($error)) {
            $response = array('error' => $error);
        }
    }

    return $response;
}

function eca_registration_status( WP_REST_Request $request) {
    $response = array(); // json: {}

    $params = $request->get_params();

    $token = $params['token'];

    $response = eca_select_registration_status($token);
    $response['message'] = eca_get_message_by_status($response['status']);

    return $response;
}

function eca_get_message_by_status($status) {
    $title = '';
    $body = '';

    switch ($status) {
        case 'waiting_for_confirmation':
            $title = 'Deine Anmeldung kann zur Zeit nicht entgegengenommen werden.';
            $body = '<p>Bitte versuche es zu einem späteren Zeitpunk nochmal.</p>';
            break;

        case 'not_found':
            $title = 'Keine Daten mit diesem Link gefunden';
            $body = '<p>Überprüfe ob der Link mit der aus der Bestätigungs-E-Mail übereinstimmt.</p>';
            break;
        
        case 'expired':
            $title = 'Deine Anmeldedaten sind nicht mehr gültig.';
            $body .= '<p>Nachdem du das Anmeldeformular abgesendet hast, speichern wir deine Daten für 48 Stunden und senden dir eine E-Mail zu, in der du deine Anmeldung bestätigen und damit unserer Datenschutzerklärung zustimmen kannst.</p>';
            $body .= '<p>Erst dann ist deinen Anmeldung bei uns eingegangen und gültig.</p>';
            $body .= '<p><strong>Bitte, melde dich erneut bei der von dir ausgewählten Veranstaltung an und wiederhole den Prozess.</strong></p>';
            break;

        default:
            break;
    }

    return array('title' => $title, 'body' => $body);
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

function eca_registration_send_to_server($event_id, $data) {

    //TODO: update status

    return 0;
}

function eca_registration_prepare_to_send() {

    // TODO: map event IDs

    // JSON decode & validate/sort data

}

function eca_registrations_stats() {
    $total = eca_registration_count();
    $waiting = eca_registration_count('status', 'waiting_for_confirmation');
    $expired = eca_registration_count('status', 'expired');

    return array(
        'total' => $total,
        'status' => array(
            'waiting' => $waiting,
            'expired' => $expired
        ));
}