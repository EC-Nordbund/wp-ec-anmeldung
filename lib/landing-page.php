<?php

require_once ECA_PLUGIN_DIR . '/lib/database.php';


class ECA_LandingPage {

    public function handle_request($params) {
        $token = $this->token($params);

        if(!empty($token)) {

            // select according registration
            $registration = eca_select_registration($token);

            $response = array(
                'status' => $registration['status'],
                'value' => 0
            );

            $fetch_states = array(
                'waiting_for_confirmation',
                'delayed_expiration',
                'waitingqueue',
                'authentication_failed'
            );

            if(in_array($response['status'], $fetch_states, true)) {

                // send data to API and save response date
                $response = eca_registration_send_to_server(
                    $token,
                    $registration['event_id'],
                    json_decode($registration['data_as_json'], true),
                    $registration['created_at'],
                    $this->debug($params),
                    $this->dontSend($params)
                );

                // updates status in DB
                eca_update_registration_status($token, $response['status']);
            }

            // generates message to depending status
            $message = $this->eca_get_message_by_status($token, $response['status'], $response['value']);

            return $this->message($message['title'], $message['body']);
        }

        if($this->debug($params)) {
            return $this->message('Debug-Mode aktivated');
        } 

        return $this->message('Dieser Link ist ungültig');
    }


    private function message($title, $message = '') {
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


    private function token($get) {
        $token = '';

        if(isset($get['t'])) {
            $token = $get['t'];
        }

        return $token;
    }

    private function debug($get) {
        if(isset($get['debug'])) {
            return boolval($get['debug']);
        }
        return false;
    }

    private function dontSend($get) {
        if(isset($get['dontSend'])) {
            return boolval($get['dontSend']);
        }
        return false;
    }


    private function eca_get_message_by_status($token = 'no_token', $status = '', $value = 0) {
        $title = '';
        $body = '';
    
        switch ($status) {
            case 'not_found':
                $title = 'Keine Daten mit diesem Link gefunden';
                $body = '<p>Überprüfe, ob der Link mit dem aus der Bestätigungs-E-Mail übereinstimmt.</p>';
                break;
            
            case 'expired':
                $title = 'Deine Anmeldedaten sind nicht mehr gültig';
                $body .= '<p>Nachdem du das Anmeldeformular abgesendet hast, speichern wir deine Daten für 24 Stunden und senden dir eine E-Mail mit der du deiner Daten bestätigen kannst.</p>';
                $body .= '<p>Erst dann werden deinen Daten an uns entgültig übermittelt.</p>';
                $body .= '<p><strong>Bitte, melde dich erneut bei der von dir ausgewählten Veranstaltung an und wiederhole den Prozess.</strong></p>';
                break;
    
            case 'waitingqueue':
                if($value > 0) {
                    $title = 'Du bist auf Wartelistenplatz ' . $value;
                } else {
                    $title = 'Du bist auf der Warteliste';
                }
                $body .= '<p>Die Veranstaltung war zu dem Zeitpunkt deiner Anmeldung schon voll.</p>';
                $body .= '<p>Bitte beachte, dass es je nach Veranstaltung und Wartelistenplatz nicht unwarscheinlich ist noch nachzurücken.</p>';
                $body .= '<p>In diesem Fall melden wir uns bei dir.</p>';
                break;

            case 'successful_registered':
                $title = 'Deine Anmeldung war erfolgreich!';
                break;
            
            case 'person_already_registered':
                $title = 'Du bist breits angemeldet';
                $body .= '<p>Du wirst rechtzeitig von uns Post bekommen, in der alle weiteren für dich relevanten Information stehen.</p>';
                break;
            
            case 'delayed_expiration':
                $title = 'Anmeldung zur Zeit nicht möglich';
                $body = '<p>Wir wurden über diesen Fehler informiert und melden uns bei dir.</p>';
                $body .= '<p>Das Einreichen einer neuen Anmeldung ist vorerst nicht notwendig.</p>';
                break;

            case 'authentication_failed':
            case 'waiting_for_confirmation':
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
