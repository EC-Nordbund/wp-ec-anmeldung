<?php

function eca_confirmation_mail($to = '', $event_id = -1, $data = array(), $schema = array()) {

    $error = $responce = array();

    $headers[] = 'From: EC-Nordbund <noreply@ec-nordbund.de>';
    $headers[] = 'Reply-To: Referent <referent@ec-nordbund.de>';
    $headers[] = 'Content-Type: text/html';

    $to = eca_mail_to_wrapper($to, $data);

    $event = eca_get_event_data($event_id);

    if(!$event) {
        $error['event_id'] = 'Event with ID ' . $event_id . ' doesn\'t exists.';
    }

    if(empty($error)) {
        $message = eca_get_confirmation_mail_template();

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

        $event_out = array('title' => $eve);

        return $event;
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

function eca_get_confirmation_mail_template() {
    $template = file_get_contents( ECA_PLUGIN_DIR . '/lib/assets/confirmation_mail.html');

    $matches = eca_replace_mail_shortcodes($template);

    return $matches;
}

function eca_replace_mail_shortcodes($string) {
    $regex = "/<<(.*?)>>/";
    preg_match_all($regex, $string, $matches);

    for($i=0; $i < count($matches[1]); $i++) {
        $match = $matches[1][$i];

        $replace = '@';
        $string = str_replace($matches[0][$i], $replace, $string);
    }

    return $string;
}