<?php

if (!defined('ABSPATH')) exit;

define('ANMELDE_START', 'Date.UTC(2018, 10, 11, 10, 0, 0, 0)');

function eca_anmeldung_shortcode($atts) {
    
    // set attributes with specified defaults
    $attributes = shortcode_atts(
        array(
            'form_id' => 0,
            'event_id' => -1,
            'available_from_year' => 0,
            'available_from_month' => 0,
            'available_from_day' => 0,
            'available_from_hour' => 0,
            'available_from_min' => 0,
            'difference_to_utc' => 1
        ),
        $atts
    );

    $form_id = $attributes['form_id'];
    $event_id = $attributes['event_id'];
    $start_a = array(
      intval( $attributes['available_from_year'] ),
      intval( $attributes['available_from_month'] )-1,
      intval( $attributes['available_from_day'] ),
      intval( $attributes['available_from_hour'] ),
      intval( $attributes['available_from_min'] ),
    );

    $utc_hours = intval( $attributes['difference_to_utc'] );

    $start = '';

    if(  ( $start_a[0] >  0                    )  // year: positive int
      && ( $start_a[1] >= 0 && $start[1] <= 11 )  // month: between 0 and 11 (javascript)
      && ( $start_a[2] >  0 && $start[2] <= 31 )  // day: between 1 and 31
      && ( $start_a[3] >= 0 && $start[3] <= 23 )  // hour: between 0 and 23
      && ( $start_a[4] >= 0 && $start[4] <= 59 )  // min: between 0 and 59
    ) {
      $start = 'Date.UTC(' . implode( ', ', $start_a ) . ', 0, 0)';
    }

    // Don't show anything when no form is specified.
    // Used when there is an event which doesn't need a registration form.
    if ($form_id < 0) {
        return '';
    }

    if(function_exists('eme_get_event')) {
      $event = eme_get_event($event_id, false);
    }

    eca_equeue_styles();

    $html  = "<noscript>";
    $html .= "<strong>We're sorry but vue-ec-anmeldung doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>";
    $html .= "</noscript>";

    // $html .= "<pre>" . json_encode($start_a, JSON_PRETTY_PRINT) . "</pre>";

    $html .= '<style> .application--wrap { min-height: unset !important; } 
      .v-input--selection-controls { margin-top: unset !important; }
      .v-date-picker-table { height: unset !important; } </style>';

    $html .= '<div class="anmelde-wrapper">';
      $html .= '<div id="anmeldung">' . eca_alert_browser_compatibility();
      $html .= "</div>";
    $html .= '</div>';

    $html .= eca_add_script_tags();

    if(!empty($start)) {
      $html .= eca_initialisation_script($event_id, $event['event_name'], $form_id, $start, $utc_hours);
    } else {
      $html .= eca_initialisation_script($event_id, $event['event_name'], $form_id);
    }

    return $html;
}

function eca_alert_browser_compatibility() {
  $alert = '<style>.alert{padding:20px;background-color:#18471e;color:white;}.alert h3{color:white;}';
  $alert .= '.alert ul {list-style-type: none;padding: 16px 12px;margin: 0;} .alert ul li {
    display: inline-block;
    margin-right: 16px;
  }
  .alert .button {
    background-color: #f5f5f5;
    color: #18471e;
    border: none;
    color: black;
    padding: 6px 18px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    margin: 6px 0;
    cursor: pointer;
    border-radius: 24px;
    font-weight: 800;
  } .alert .button:hover { background-color: #eaeaea; }</style>';

  $alert .= '<div class="alert">';
  $alert .= '<h3>Keine Anmeldung mit diesem Browser möglich</h3><hr style="color:white;width:42px;margin:8px 0;border-bottom:3px solid white;">';
  $alert .= 'Dein Browser wird von unserm Anmeldesystem nicht unterstützt.<br>Eine Liste mit kompatiblen Browsern findest du hier:<br>';
  $alert .= '<ul><li>Firefox ✓</li><li>Chrome ✓</li><li>Edge ✓</li><li>Opera ✓</li><li>Safari 10+ ✓</li><li>Internet Explorer ✗</li></ul>';
  $alert .= '<br>Hilf uns unser System zu verbessern und sende uns mit einem Klick auf den Button Informationen zu deinem Browser.';
  $alert .= '<span style="width:100%;text-align:center;display:inline-block;"><a id="sendUserAgent" href="#" class="button" target="_top">Benachrichtige die Entwickler</a></span></div>';

  $alert .= '<script>document.getElementById("sendUserAgent").setAttribute("href", "mailto:webmaster@ec-nordbund.de?subject=Browser-Kompatiblitäts-Report&body=" + encodeURI(window.navigator.userAgent) );</script>';


  return $alert;
}

function eca_add_script_tags() {
  $apps = glob(ECA_PLUGIN_DIR . '/lib/anmeldung/js/app.*.js');
  $app_name = end( explode('/', reset($apps)) );

  $chunks = glob(ECA_PLUGIN_DIR . '/lib/anmeldung/js/chunk-vendors.*.js');
  $chunk_name = end( explode('/', reset($chunks)) );

  $scripts = '';
  $scripts .= '<script type="text/javascript" src="' . ECA_PLUGIN_LIB_URL . 'anmeldung/js/' . $chunk_name . '"></script>';
  $scripts .= '<script type="text/javascript" src="' . ECA_PLUGIN_LIB_URL . 'anmeldung/js/' . $app_name . '"></script>';

  return $scripts;
}

function eca_equeue_styles() {
   
    $styles = glob(ECA_PLUGIN_DIR . '/lib/anmeldung/css/*.css');

    foreach($styles as $style) {
      // registers app styles
      $style_name = end( explode('/', $style) );
      wp_enqueue_style('eca_style' . $style_name, ECA_PLUGIN_LIB_URL . 'anmeldung/css/' . $style_name);
    }


}

function eca_get_filename($path) {

    $array = explode('/', $path);

    return end($array);
}

function eca_initialisation_script($event_id = -1, $event_name = '', $form_id = 0, $start = ANMELDE_START, $utc_hours = 1) {

  // TODO: alters beschränkung

  $script = "<script>
  Date.prototype.addHours= function(h){
    this.setHours(this.getHours()+h);
    return this;
  }
  
  const init_event = {
    id: " . $event_id . ",
    title: '" . addcslashes($event_name, "'") . "', start: new Date(" . $start . ").addHours(" . - $utc_hours . ") };";
  
  $script .= file_get_contents( ECA_PLUGIN_DIR . '/lib/forms.js');
  
  $script .= "window.createAnmeldung('anmeldung', init_event, init_form[" . $form_id . "]); </script>";

  return $script;
}