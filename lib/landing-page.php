<?php

require_once ECA_PLUGIN_DIR . '/lib/database.php';


class ECA_LandingPage {

    public function handle_request($params) {
        $token = $this->get_token($params);

        $token = $params['t'];

        $registration = array();

        if(!empty($token)) {
            $registration = eca_select_registration($token);

            if(!empty($registration)) {
               
                eca_registration_send_to_server(
                    $registration['event_id'],
                    json_decode($registration['data_as_json'])
                );
            }
        
            return $this->script_prameters($token);
        }

        return '<!-- TOKEN MISSING -->';
    }

    private function script_prameters($token = '') {
        $html = '<script>';
        $html .= 'token = "' . $token . '";';
        $html .= '</script>';

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
