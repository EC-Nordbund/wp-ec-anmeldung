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
    $response = array();

    $params = $request->get_json_params();

    if(isset($params['schema']) && isset($params['data']) && isset($params['eventID'])) {
        $event_id = $params['eventID'];
        $data = $params['data'];
        $schema = $params['schema'];

        $token = 'test_token';

        $error = array();

        $email_to = eca_get_value_of_key_r('email', $data);

        if(!isset($email_to)) {
            $error['Email'] = 'Address of the receiver is missing.';
        }

        if(empty($error) && empty($email_to)) {
            $error['Email'] = 'Address of the receiver is empty.';
        }

        if(empty($error)) {
            $token = hash(
                'sha256',
                json_encode($data) . date('c')
            );
        }
        
        // Add registration to database
        if(empty($error)) {
            $db_access = eca_add_registration($event_id, $data, $token);

            // Add errors
            if(isset($db_access['error'])) {
                $error['Database'] = $db_access['error'];
            } else {
                $response = array('database' => $db_access);
            }
        }

        if(empty($error)) {
            $mailer_responce = eca_confirmation_mail($email_to, $event_id, $token, $data, $schema);

            // Add errors
            if(isset($mailer_responce['error'])) {
                $error['Confirmation Mail'] = $mailer_responce['error'];
            } else {
                $response['confirmation_mail'] =  $mailer_responce;
            }
        }

        if(empty($error)) {
             return array('state' => 'success');
        } else {
            return array(
                'state' => 'error',
                'error' => $error);
        } 
    }

    return array(
        'state' => 'error',
        'error' => array( 'parameters' => 'parameter missing') );
}

function eca_registration_status( WP_REST_Request $request) {
    $response = array(); // json: {}

    $params = $request->get_params();

    $token = $params['token'];

    $status = eca_select_registration_status($token);

    return $status;
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

function eca_success_mail_values($event_id = -1, $data = array()) {
    $val = array(
        'to' => '',
        'eid'=> '',
        'vorname' => '',
        'nachname' => '');

    if(!empty($data['vorname'])) {
        $val['vorname'] = $data['vorname'];
    }

    if(!empty($data['nachname'])) {
        $val['nachname'] = $data['nachname'];
    }

    $val['eid'] = $event_id;

    if(!empty($data['email'])) {
        $val['to'] = $data['email'];
    }

    return $val;
}

function eca_registration_send_to_server($token, $event_id, $data, $created, $debug = false, $dontSend = false) {
    $error = array();

    $mail = eca_success_mail_values($event_id, $data);

    $api_event_id = eca_event_id_mapping($event_id);

    $valid = eca_check_required_fields($api_event_id, $data);

    if($debug) {
        $api_event_id = 4200;
        $valid = eca_check_required_fields($api_event_id, $data);
    }

    if(!$valid['state']) {
        $error['Validation'] = $valid['value'];
    }

    $mutation = '';
    $curl_info = array();

    if(empty($error)) {
        $mutation = eca_registration_prepare_graphql_mutation($api_event_id, $data, $created);
        $query = json_encode(array('query' => $mutation));

        if(!$dontSend) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "https://api.ec-nordbund.de/graphql");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

            $headers = array();
            $headers[] = "Content-Type: application/json";
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);

            $curl_info = curl_getinfo($ch);

            if (curl_errno($ch)) {
                $error['Curl Error'] = curl_error($ch);
                $error['Curl Information'] = $curl_info;
            }

            curl_close ($ch);
        }
    }

    $json = '';
    $resp = array();

    if(empty($error)) {
        $json = json_decode($result, true);
    }

    if(empty($json)) {
        $error['API Response'] = 'Empty or not as JSON decodable result: ' . $result;
    } else {
        $resp = $json['data']['anmelden'];
    }

    if(!empty($json['errors'])) {
        $error['GraphQL'] = json_encode($json, JSON_PRETTY_PRINT);
    }

    $status = 'delayed_expiration';
    $value = 0;

    if(empty($error) && !empty($resp['anmeldeID'])) {
        $id = $resp['anmeldeID'];

        if(is_string($id)) {
            eca_set_anmelde_id_form_api($token, $id);
        } else {
            $error['API Response'] = 'anmeldeID is not a string';
        }
    }

    if(empty($error) && is_integer($resp['status'])) {
        $r = $resp['status'];

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

                // Final Mail
                eca_final_mail($mail['to'] , $mail['eid'], $mail['vorname'], $mail['nachname']);
                break;

            default:
                if(is_int($r) && $r > 0) {
                    $status = 'waitingqueue';
                    $value = $r;

                    // Final Mail
                    eca_final_mail($mail['to'] , $mail['eid'], $mail['vorname'], $mail['nachname'], $r);
                } 
                break;
        }
    }

    if($debug) {
        $mut_for_debug = '';
        if(defined('API_TOKEN')) {
            $mut_for_debug = str_replace(API_TOKEN, "API_TOKEN", $mutation);
        }

        print($mut_for_debug);
        print(json_encode($json, JSON_PRETTY_PRINT));
        print(json_encode($curl_info, JSON_PRETTY_PRINT));
    }

    if($status === 'delayed_expiration' || $status === 'authentication_failed') {
        $value = eca_registration_delay_expiration($token);

        // Send json response
        eca_error_mail($token, $mail['to'] ,$value, $error, $mutation);
    }

    return array('status' => $status, 'value' => $value);
}

function eca_registration_prepare_graphql_mutation($event_id, $data, $created) {

    $token = '';
    if(defined('API_TOKEN')) {
        $token = API_TOKEN;
    }

    $timestamp = date_create('now');

    $params = array(
        'isWP' => true,
        'token' => $token,
        'position' => 1,
        'veranstaltungsID' => -1,
        'anmeldeZeitpunkt' => date_create($created)->format('Y-m-d H:i:s'),
        'vorname' => 'leer',
        'nachname' => 'leer',
        'gebDat' => strftime('%Y-%m-%d'),
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

    if(empty($created)) {
        $created = $timestamp;
    }

    if(isset($event_id)) {
        $params['veranstaltungsID'] = $event_id;
    } 

    if(isset($data['geschlecht'])) {
        $params['geschlecht'] = $data['geschlecht'];
    }

    if(isset($data['vorname'])) {
        $params['vorname'] = $data['vorname'];
    }

    if(isset($data['nachname'])) {
        $params['nachname'] = $data['nachname'];
    }

    if(isset($data['gebDat'])) {
        $params['gebDat'] = $data['gebDat'];
    }

    if(isset($data['telefon'])) {
        $params['telefon'] = $data['telefon'];
    }

    if(isset($data['email'])) {
        $params['eMail'] = $data['email'];
    }

    if(isset($data['strasse'])) {
        $params['strasse'] = $data['strasse'];
    }

    if(isset($data['plz'])) {
        $params['plz'] = $data['plz'];
    }

    if(isset($data['ort'])) {
        $params['ort'] = $data['ort'];
    }

    if(isset($data['vegetarisch'])) {
        $params['vegetarisch'] = $data['vegetarisch'];
    }

    if(isset($data['lebensmittel'])) {
        $params['lebensmittelAllergien'] = $data['lebensmittel'];
    }

    if(isset($data['gesundheitsinformationen'])) {
        $params['gesundheitsinformationen'] = $data['gesundheitsinformationen'];
    }

    if(isset($data['bemerkungen'])) {
        $params['bemerkungen'] = $data['bemerkungen'];
    }

    if(isset($data['schwimmen'])) {
        $params['schwimmen'] = $data['schwimmen'];
    }

    if(isset($data['rad'])) {
        $params['radfahren'] = $data['rad'];
    }

    if(isset($data['klettern'])) {
        $params['klettern'] = $data['klettern'];
    }

    if(isset($data['boot'])) {
        $params['bootFahren'] = $data['boot'];
    }

    if(isset($data['entfernen'])) {
        $params['sichEntfernen'] = $data['entfernen'];
    }

    if(isset($data['agrees_fahrgemeinschaften'])) {
        $params['fahrgemeinschaften'] = $data['agrees_fahrgemeinschaften'];
    }

    
    $known_extra_fields = array('agrees_freizeitleitung');


    $existing_known_extra_fields = array_filter($known_extra_fields, function($key) {
        return isset($data[$key]);
    });

    $extra_fields = array_map(function($key) {
        return array($key => $data[$key]);
    }, $existing_known_extra_fields);

    if(!empty($extra_fields)) {
        $param['extra_json'] = json_decode($extra_fields);
    }


    $params_str = '';
    foreach ($params as $key => $value) {
        // string
        if(is_string($value)) {
            $value = str_replace("\n", ' ', $value);

            $params_str .= $key . ': "' . $value . '", ';
            // $params_str .= $key . ': "string", ';

        }

        // numeric
        if(is_int($value)) { 
            $params_str .= $key . ': ' . $value . ', ';
            // $params_str .= $key . ': "int", ';

        }

        // boolean
        if(is_bool($value)) {
            $params_str .= $key . ': ' . ($value ? 'true' : 'false') . ', ';
            // $params_str .= $key . ': "boolean", ';
        }
    }


    if(!empty($params_str)) {
        return 'mutation { anmelden( ' . substr($params_str, 0, -2) . ') { status, anmeldeID } }';
    }


    return '';
}


function eca_check_required_fields($event_id, $date) {
    $state = true;
    $value = '';

    // event id positiv 
    if(!is_integer($event_id) || $event_id < 0) {
        $state = false;
        $value = 'event_id no positive integer: '.$event_id;
    }

    $required =array(
        'email' => 'Email is required.',
        'vorname' => 'Vorname is required',
        'nachname' => 'Nachname is required',
        'gebDat' => 'Geburtsdatum is required'
    );

    if($status) {
        foreach ($required as $name => $val) {
            if(empty($data[$name])) {
                $state = false;
                $value = $val;
            }
        }
    }

    return array('state' => $state, 'value' => $value);
}


function eca_event_id_mapping($wp_event_id) {
    $api_event_id = -1;

    switch ($wp_event_id) {

        case 61:
            $api_event_id = 400; // MAWE
            break;

        case 62:
            $api_event_id = 401; // ECK1
            break;

        case 63:
            $api_event_id = 402; // ECK2
            break;

        case 64:
            $api_event_id = 403; // MaTa
            break;

        case 65:
            $api_event_id = 404; // JLC
            break;

        case 66:
            $api_event_id = 405; // TO
            break;

        case 67:
            $api_event_id = 406; // BC
            break;

        case 68:
            $api_event_id = 407; // PC
            break;

        case 69:
            $api_event_id = 408; // LJF1
            break;

        case 70:
            $api_event_id = 409; // LJF2
            break;

        case 71:
            $api_event_id = 410; // TC
            break;

        case 72:
            $api_event_id = 411; // JuFr
            break;

        case 73:
            $api_event_id = 412; // JEF
            break;

        case 74:
            $api_event_id = 413; // AF
            break;

        case 75:
            $api_event_id = 414; // RF
            break;

        case 76:
            $api_event_id = 415;  // TO
            break;

        default:
            break;
    }

    return $api_event_id;
}