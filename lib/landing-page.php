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

            if($status === 'waiting_for_confirmation') {
                $status = eca_registration_send_to_server(
                    $registration['event_id'],
                    json_decode($registration['data_as_json'])
                );
            }

            $message = eca_get_message_by_status($status, $token);
            
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
}
