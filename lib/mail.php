<?php

function eca_confirmation_mail($to = array(), $event_id = -1, $data = array()) {

    $headers[] = 'From: EC-Nordbund <noreply@ec-nordbund.de>';
    $headers[] = 'Reply-To: Referent <referent@ec-nordbund.de>';


    return wp_mail('tobi-web@akranet.de', 'Test', json_encode($data), $headers);

}