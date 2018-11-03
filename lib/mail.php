<?php

function eca_confirmation_mail($to = '', $event_id = -1, $data = array(), $schema = array()) {

    $error = $responce = array();

    $headers[] = 'From: EC-Nordbund <noreply@ec-nordbund.de>';
    // $headers[] = 'Bcc: webmaster@ec-nordbund.de';
    $headers[] = 'Reply-To: Referent <referent@ec-nordbund.de>';
    $headers[] = 'Content-Type: text/html';

    $to = eca_mail_to_wrapper($to, $data);

    $event = eca_get_event_data($event_id);

    if(!$event) {
        $error['event_id'] = 'Event with ID ' . $event_id . ' doesn\'t exists.';
    }

    if(empty($error)) {
        $message = eca_get_confirmation_mail_template($data, $schema, $event);

        // $message = json_encode($message);   // only for DEV purpose

        $mailed = wp_mail($to, 'Test', $message , $headers);

        if($mailed) {
            $responce['mailed'] = $mailed;
        } else {
            $error['mailer'] = 'Mail could not be sended.';
        }
    }

    if(!empty($error)) {
        $responce = array('error' => $error);
    }
    
    return $responce;
}

function eca_get_event_data($event_id) {
    if(function_exists('eme_get_event')) {
        $event = eme_get_event($event_id, false);

        $event_out = array(
            'id' => $event['event_id'],
            'title' => $event['event_name']
        );

        return $event_out;
    }

    return false;
}

function eca_mail_to_wrapper($to, $data) {
    if(isset($data['vorname']) || isset($data['nachname'])) {
        $to = '<' . $to . '>';

        if(isset($data['nachname'])) {
            $to = $data['nachname'] . ' ' . $to; 
        }

        if(isset($data['vorname'])) {
            $to = $data['vorname'] . ' ' . $to; 
        }
    }

    return $to;
}

function eca_get_confirmation_mail_template($data, $schema, $event) {
    $template = file_get_contents( ECA_PLUGIN_DIR . '/lib/assets/confirmation_mail.html');

    $regex = "/\*\|(.*?)\|\*/";
    preg_match_all($regex, $template, $matches);

    for($i=0; $i < count($matches[1]); $i++) {
        $match = $matches[1][$i];
        
        $replace = eca_mail_get_replacements($match, $data, $schema, $event);
        $template = str_replace($matches[0][$i], $replace, $template);
    }

    return $template;
}

function eca_mail_get_replacements($match, $data, $schema, $event) {
    switch ($match) {
        case 'anrede':
            if(!empty($data['vorname'])) {
                return 'Moin ' . $data['vorname'];
            } else {
                return 'Moin Moin,';
            }
            break;

        case 'event_title':
            return $event['title'];
            break;
        
        default:
            return '@';
            break;
    }
}