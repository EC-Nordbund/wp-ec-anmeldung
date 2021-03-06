<?php

if (!defined('ABSPATH')) exit;

define('ANMELDE_START', 'Date.UTC(2018, 10, 11, 10, 0, 0, 0)');

function eca_anmeldung_shortcode($atts) {
    
    // set attributes with specified defaults
    $attributes = shortcode_atts(
        array(
            'form_id' => -1,
            'event_id' => -1,
            'start' => ''
        ),
        $atts
    );

    $form_id = $attributes['form_id'];
    $event_id = $attributes['event_id'];
    $start = $attributes['start'];

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

    $html .= '<style> .application--wrap { min-height: unset !important; } 
      .v-input--selection-controls { margin-top: unset !important; }
      .v-date-picker-table { height: unset !important; } </style>';

    $html .= '<div class="anmelde-wrapper">';
      $html .= '<div id="anmeldung">';
      $html .= "</div>";
    $html .= '</div>';

    $html .= eca_add_script_tags();

    if(!empty($start)) {
      $html .= eca_initialisation_script($event_id, $event['event_name'], $form_id, $start);
    } else {
      $html .= eca_initialisation_script($event_id, $event['event_name']);
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

function eca_initialisation_script($event_id = -1, $event_name = '', $form_id = 0, $start = ANMELDE_START) {

  // TODO: alters beschränkung

  $script = "<script> const init_event = {
    id: " . $event_id . ",
    title: '" . $event_name . "', start: new Date(" . $start . ") };";
  
  $script .= file_get_contents( ECA_PLUGIN_DIR . '/lib/forms.js');
  
  $script .= "window.createAnmeldung('anmeldung', init_event, init_form[" . $form_id . "]); </script>";

  return $script;
}