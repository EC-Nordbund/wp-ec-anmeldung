<?php

require_once ECA_PLUGIN_DIR . '/lib/database.php';


class ECA_LandingPage {

    public function handle_request($params) {
        $token = $this->get_token($params);

        $token = $params['t'];

        $message = array();

        if(!empty($token)) {
            $registration = eca_select_registration($token);

            // return $registration;

            $status = $registration['status'];

            if($status === 'waiting_for_confirmation' || $status = 'persistent_data') {
                $response = eca_registration_send_to_server(
                    $registration['event_id'],
                    json_decode($registration['data_as_json'], true)
                );


                // updates status 
                $status = $this->handle_api_response($token, $response);

                // delay expiration
                if($status === 'delayed_expiration') {
                    $expire = eca_registration_delay_expiration($token);
                    $status = array('status' => $status, 'value' => $expire);
                }
            }

            if(is_array($status)) {
                $message = $this->eca_get_message_by_status($token, $status['status'], $status['value']);
            } else {
                $message = $this->eca_get_message_by_status($token, $status);

            }
            
            return $this->print_message($message['title'], $message['body']);
        }

        return $this->print_message('Dieser Link ist ungültig');
    }

    private function script_prameters($token = '') {
        $html = '<script>';
        $html .= 'token = "' . $token . '";';
        $html .= '</script>';

        return $html;
    }

    private function print_message($title, $message = '') {
        $html = '';

        if(!empty($title)) {
            $html .= '<h1>' . $title . '</h1>';
        }

        if(!empty($message)) {
            $html .= '<hr/>';
            $html .= $message;
        }

        return $html;
    }

    private function get_token($get) {
        $token = '';

        if(isset($get['t'])) {
            $token = $get['t'];
        }

        return $token;
    }

    private function handle_api_response($token = '', $response = 'delayed_expiration') {

        if(is_array($response) && !empty($response['status'])) {
            eca_update_registration_status($token, $response['status']);
        } else {
            eca_update_registration_status($token, $response);
        }

        return $response;
    }

    private function eca_get_message_by_status($token = 'no_token', $status = '', $value = 0) {
        $title = '';
        $body = '';
    
        switch ($status) {
            case 'waiting_for_confirmation':
                $title = 'Deine Anmeldung kann zur Zeit nicht entgegengenommen werden';
                $body = '<p>Bitte versuche es zu einem späteren Zeitpunk nochmal.</p>';
                break;
    
            case 'not_found':
                $title = 'Keine Daten mit diesem Link gefunden';
                $body = '<p>Überprüfe ob der Link mit der aus der Bestätigungs-E-Mail übereinstimmt.</p>';
                break;
            
            case 'expired':
                $title = 'Deine Anmeldedaten sind nicht mehr gültig';
                $body .= '<p>Nachdem du das Anmeldeformular abgesendet hast, speichern wir deine Daten für 48 Stunden und senden dir eine E-Mail zu, in der du deine Anmeldung bestätigen und damit unserer Datenschutzerklärung zustimmen kannst.</p>';
                $body .= '<p>Erst dann ist deinen Anmeldung bei uns eingegangen und gültig.</p>';
                $body .= '<p><strong>Bitte, melde dich erneut bei der von dir ausgewählten Veranstaltung an und wiederhole den Prozess.</strong></p>';
                break;
    
            case 'waitingqueue':
                if($value > 0) {
                    $title = 'Du bist auf Wartelistenplatz ' . $value;
                } else {
                    $title = 'Du bist auf der Warteliste';
                }
                $body .= '<p>Die Veranstaltung war zu dem Zeitpunkt deiner Anmeldung schon voll.</p>';
                $body .= '<p><strong>Aber</strong> je nach Veranstaltung und Wartelistenplatz ist es nicht unwarscheinlich, dass du noch nachrückst.</p>';
                $body .= '<p>In diesem Fall melden wir uns bei dir</p>';
                break;

            case 'successful_registered':
                $title = 'Deine Angemeldung war erfolgreich!';
                break;
            
            case 'person_already_registered':
                $title = 'Du bist breits angemeldet';
                $body .= '<p>Du wirst rechtzeitig von uns Post bekommen, in der alle weiteren für dich relevanten Information stehen.</p>';
                break;
            
            case 'delayed_expiration':
                $title = 'Anmeldung zur Zeit nicht möglich';
                $body = '<p>Wir verzögern das Löschen deiner Anmeldedaten um 24 Stunden</p>';
                if($value > 0) {
                    $body .= '<p>Deine Daten werden sind also noch bis zum ' . date('d.m. um H:i', $value) . 'gültig.</p>';
                }
                $body .= '<p>Bitte probiere es innerhalb diese Zeitraumes nochmal.</p>';
                break;

            case 'authentication_failed':
            default:
                $title = 'Anfrage konnte nicht verarbeitet werden';
                $body = '<p>Ooops, das tut uns Leid.</p>';
                $body .= '<p>Bitte sende uns eine E-Mail an <a href="mailto:webmaster@ec-nordbund.de?';
                $body .= 'subject=Fehler%20beim%20Best%C3%A4tigen%20der%20Anmeldung&';
                $body .= 'body=%0A%0A%0A%0A%3D%3D%3D%20Fehlerdetails%20%3D%3D%3D%0Atoken%3A%20' . $token;
                $body .= '%0Astatus%3A%20' . $status . '%0A%0A%0A">';
                $body .= 'webmaster@ec-nordbund.de</a>';
                $body .= ' damit wir die Fehlerursache so schnell wie möglich untersuchen können.</p>';
                
                break;
        }
    
        return array('title' => $title, 'body' => $body);
    }
}
