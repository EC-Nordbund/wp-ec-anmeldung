<?php

if (!defined('ABSPATH')) exit;

require_once 'database.php';
require_once 'mail.php';

include_once 'token.php';

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

        if(empty($error)) {
            $token = hash(
                'sha256',
                json_encode($data)
            );
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

function eca_registration_send_to_server($event_id, $data) {
    $error = array();

    $mutation = eca_registration_prepare_graphql_mutation($event_id, $data);
    $query = json_encode(array('query' => $mutation));

    // return $mutation;

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://ec-api.de/graphql");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

    $headers = array();
    $headers[] = "Content-Type: application/json";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        $error['curl'] = curl_error($ch);
    }
    curl_close ($ch);

    $json = '';

    if(empty($error)) {
        $json = json_decode($result, true);
    }

    if(empty($json)) {
        $error['json'] = 'Empty result.';
    }

    if(!empty($json['errors'])) {
        $error['graphQL'] = $json['errors'];
    }

    $status = 'delayed_expiration';
    $waiting_position = 0;

    if(empty($error) && !empty($json['data']['anmelden'])) {
        $r = $json['data']['anmelden'];

        switch ($r) {

            // duplicate person & event combi
            case -2:
                $status = 'person_already_registered';
                break;
            
            // Authentifizierungsfehler
            case -1:
                $status = 'authentication_failed';
                break;

            // Successful
            case 0:
                $status = 'successful_registered';
                break;

            default:
                if(is_int($r) && $r > 0) {
                    $status = 'waitingqueue';
                    $waiting_position = $r;
                } 
                break;
        }
    }
    
    if($status === 'waitingqueue') {
        return array('status' => $status, 'value' => $waiting_position);
    }

    return $status;
}

function eca_registration_prepare_graphql_mutation($event_id, $data) {

    $token = '';
    if(defined('API_TOKEN')) {
        $token = API_TOKEN;
    }

    $timestamp = date_create('now');

    $params = array(
        'isWP' => true,
        'token' => $token,
        'position' => 1,
        'veranstaltungsID' => 4200,
        'anmeldeZeitpunkt' => $timestamp->format('Y-m-d H:i:s'),
        'vorname' => 'leer',
        'nachname' => 'leer',
        'gebDat' => $timestamp->format('Y-m-d'),
        'geschlecht' => '',
        'eMail' => '',
        'telefon' => '',
        'strasse' => '',
        'plz' => '',
        'ort' => '',
        'vegetarisch' => false,
        'lebensmittelAllergien' => '',
        'gesundheitsinformationen' => '',
        'bemerkungen' => '',
        'radfahren' => false,
        'schwimmen' => 0,
        'fahrgemeinschaften' => false,
        'klettern' => false,
        'sichEntfernen' => false,
        'bootFahren' => false,
        'extra_json' => '{}'
    );

    $params_str = '';
    foreach ($params as $key => $value) {
        // string
        if(is_string($value)) { 
            $params_str .= $key . ': "' . $value . '", ';
        }

        // numeric
        if(is_int($value)) { 
            $params_str .= $key . ': ' . $value . ', ';
        }

        // boolean
        if(is_bool($value)) {
            $params_str .= $key . ': ' . ($value ? 'true' : 'false') . ', ';
        }
    }

    if(!empty($params_str)) {
        return 'mutation { anmelden( ' . substr($params_str, 0, -2) . ') }';
    }


    // TODO: map event IDs

    // TODO: JSON decode & validate/sort data

    // 0 = success
    // positive zahle warteliste pos

    // -1 = token stimmt nicht (keine berechtigung)
    // -2 = person hat sich schon mal angemendelt für veranstaltung (daten ändern: referent@ec-nordbund.de)

    // 4200 test veranstaltung 

    return '';
}