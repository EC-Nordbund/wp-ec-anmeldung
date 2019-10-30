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
      $html .= '<div id="anmeldung">';
      $html .= "</div>";
    $html .= '</div>';

    $html .= eca_add_script_tags();

    if(!empty($start)) {
      $html .= eca_initialisation_script($event_id, $event['event_name'], $form_id, $start, $utc_hours);
    } else {
      $html .= eca_initialisation_script($event_id, $event['event_name'], $form_id);
    }

    $html .= eca_alert_browser_compatibility();

    return $html;
}

function eca_alert_browser_compatibility() {
  $alert = '<style>.alert { padding: 20px; background-color: #467A2A; color: white; }';
  $alert .= '.closebtn { margin-left: 15px; color: white; font-weight: bold; float: right; font-size: 22px; line-height: 20px; cursor: pointer; transition: 0.3s; }';
  $alert .= '.closebtn:hover { color: black; opacity: 0.5; }</style>';

  $alert .= '<br/><div class="alert">';
  $alert .= '<span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span>';
  $alert .= '<strong>Hinweis zur Kompatibilität:</strong>';
  $alert .= '<br/>Falls du auf dieser Seite keinen Anmelde-Button entdecken kannst, ist es von dem verwendeten Browser zur Zeit nicht möglich sich anzumelden.';
  $alert .= '<br/>Bitte weiche auf einen anderen Browser aus. Wir empfehlen Firefox oder Google Chrome.</div>';

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