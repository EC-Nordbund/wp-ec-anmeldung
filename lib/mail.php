<?php

if (!defined('ABSPATH')) exit;

function eca_confirmation_mail($to = '', $event_id = -1, $token = 'no_token', $data = array(), $schema = array()) {

    $error = $responce = array();

    $headers[] = 'From: EC-Nordbund <noreply@ec-nordbund.de>';
    // $headers[] = 'Bcc: webmaster@ec-nordbund.de';
    $headers[] = 'Reply-To: Referent <referent@ec-nordbund.de>';
    $headers[] = 'Content-Type: text/html';

    $subject = 'Bestätige dein Anmeldung';

    $to = eca_mail_to_wrapper($to, $data);

    $event = eca_get_event_data($event_id);

    if(!$event) {
        $error['event_id'] = 'Event with ID ' . $event_id . ' doesn\'t exists.';
    }

    if(empty($error)) {
        $message = eca_generate_confirmation_mail(array(
            'vorname' => empty($data['vorname']) ? '' : $data['vorname'],
            'subheader' => 'Deine Anmeldung ist noch nicht abgeschlossen!',
            'event_title' => $event['title'],
            'data_table' => eca_mail_generate_data_overview($data, $schema),
            'token' => $token
        ), $data, $schema);

        // $message = json_encode($matches);   // only for DEV purpose

        $mailed = wp_mail($to, $subject, $message , $headers);

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

function eca_generate_confirmation_mail($replacements, $data, $schema) {
    $template = eca_get_mail_template('mail_header');
    $template .= eca_get_mail_template('mail_subheader');
    $template .= eca_get_mail_template('confirmation/message');
    $template .= eca_get_mail_template('confirmation/data-overview');
    $template .= eca_get_mail_template('mail_datenschutzerklärung');
    $template .= eca_get_mail_template('mail_footer');

    $mail = eca_mail_replace_placeholder($template, $replacements, $data, $schema);

    return $mail;
}

function eca_get_mail_template($path, $type = 'html') {
    return file_get_contents(ECA_PLUGIN_DIR . '/lib/templates/' . $path . '.' . $type);
}

function eca_mail_replace_placeholder($template, $replacements, $data, $schema) {
    preg_match_all("/\*\|(.*?)\|\*/", $template, $matches);

    for($i=0; $i < count($matches[1]); $i++) {
        $match = $matches[1][$i];
        
        $replace = empty($replacements[$match]) ? '@@@' : $replacements[$match];
        $template = str_replace($matches[0][$i], $replace, $template);
    }

    return $template;
}

function eca_mail_generate_data_overview($data, $schema) {
    $table = '<div class="">';

    // $table .= json_encode($schema);
    // $table .= json_encode($data);

    foreach ($schema as $step => $fields) {
        
        $no_data = array_map(function ($f) {
            return empty($data[$f]);
        }, $fields);

        if(in_array(true, $no_data)) { 

            $th = $td = '';

            foreach ($fields as $field) {            
                if(!empty($field) && !empty($data[$field])) {
                    $th .= '<td valign="middle" style="border: 1px solid #999; text-align: center; padding: 6px 12px;">';
                        $th .= '<div style="color:#555555;font-family:\'Ubuntu\', Tahoma, Verdana, Segoe, sans-serif;"><i>' . $field . '</i></div></dh>';
                    $td .= '<td valign="middle" style="border: 1px solid #999; text-align: center; padding: 6px 12px;">';
                        $td .= '<div style="color:#555555;font-family:\'Ubuntu\', Tahoma, Verdana, Segoe, sans-serif;"><p>' . $data[$field] . '</p></div></td>';
                } 
            }
            
            $table .= '<!--[if mso]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;"><![endif]-->';
            $table .= '<div style="color:#555555;font-family:\'Ubuntu\', Tahoma, Verdana, Segoe, sans-serif;line-height:120%; padding-right: 10px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px;">';
            $table .= '<div style="font-size:12px;line-height:14px;color:#555555;font-family:\'Ubuntu\', Tahoma, Verdana, Segoe, sans-serif;text-align:left;"><p style="margin: 0; line-height: 22px; font-size: 18px;">';

            $table .= $step; // Abschnit header

            $table .= '</p></div></div>';
            $table .= '<!--[if mso]></td></tr></table><![endif]-->';

            $table .= '<div style="padding: 10px;"><div style="overflow-x:auto;">';
            $table .= '<table cellpadding="2" cellspacing="2" width="100%" style="border-collapse: collapse; border: 1px solid #999;">';
            $table .= '<tr>';

            $table .= $th;

            $table .= '</tr>';
            $table .= '<tr>';

            $table .= $td;
        
            $table .= '</tr>';
            $table .= '</table>';
            $table .= '</div></div>';
        }
    }

    $table .= '</div>';

    return $table;
}