<?php

function eca_anmeldung_shortcode($atts) {
    
    // set attributes with specified defaults
    $attributes = shortcode_atts(
        array(
            'form_id' => -1,
            'event_id' => -1,
            'event_name' => '',
            // format: YYYY-MM-DDTH:mm:ss
            'event_begin' => '',
            'event_end' => ''
        ),
        $atts
    );

    $form_id = $attributes['form_id'];
    $event_id = $attributes['event_id'];

    // Don't show anything when no form is specified.
    // Used when there is an event which doesn't need a registration form.
    if ($form_id === -1) {
        return 'No matching form found.';
    }

    eca_enqueue_scripts_and_styles();

    $html  = "<noscript>";
    $html .= "<strong>We're sorry but vue-ec-anmeldung doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>";
    $html .= "</noscript>";

    $html .= "<div id=app>";
    $html .= "</div>";

    $html .= eca_add_script_tags();

    $html .= eca_initialisation_script();

    return $html;
}

function eca_enqueue_scripts_and_styles() {
    //wp_enqueue_script('eca_script_app');
    //wp_enqueue_script('eca_script_chunk');

    //wp_enqueue_style('google_fonts_roboto');
    //wp_enqueue_style('mdi');
    wp_enqueue_style('eca_style');
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

function eca_register_scripts_and_styles() {
    $apps = glob(ECA_PLUGIN_DIR . '/lib/anmeldung/js/app.*.js');
    $chunks = glob(ECA_PLUGIN_DIR . '/lib/anmeldung/js/chunk-vendors.*.js');
    $styles = glob(ECA_PLUGIN_DIR . '/lib/anmeldung/css/*.css');

    // register app script
    $app_name = end( explode('/', reset($apps)) );
    wp_register_script('eca_script_app', ECA_PLUGIN_LIB_URL . 'anmeldung/js/' . $app_name, array('eca_script_chunk'), false, true);

    // register chunk scripts
    $chunk_name = end( explode('/', reset($chunks)) );
    wp_register_script('eca_script_chunk', ECA_PLUGIN_LIB_URL . 'anmeldung/js/' . $chunk_name, array(), false, true);

    // register styles
    wp_register_style('google_fonts_roboto', 'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900');
    wp_register_style('mdi', 'https://cdn.materialdesignicons.com/2.5.94/css/materialdesignicons.min.css');

    // registers app styles
    $style_name = end( explode('/', reset($styles)) );
    wp_register_style('eca_style', ECA_PLUGIN_LIB_URL . 'anmeldung/css/' . $style_name);
}

function eca_get_filename($path) {

    $array = explode('/', $path);

    return end($array);
}

function eca_initialisation_script() {
  $script = "<script> window.createAnmeldung('app', {
    vConfig: {
      veranstaltungsID: 1,
      bezeichnung: 'Test',
      begin: new Date(),
    },
    form: [
      {
        name: 'pers',
        title: 'Persöhnliche Daten',
        fields: [
          {
            name: 'geschlecht',
            label: '',
            componentName: 'ec-radio',
            values: [
              {
                label: 'Männlich',
                value: 'm'
              },
              {
                label: 'Weiblich',
                value: 'w'
              }
            ],
            row: true
          },
          {
            name: 'vorname',
            label: 'Vorname',
            counter: 50
          },
          {
            name: 'nachname',
            label: 'Nachname',
            counter: 50
          },
          {
            name: 'gebDat',
            label: 'Geburtsdatum',
            componentName: 'ec-date'
          }
        ],
      },
      {
        name: 'kontakt',
        title: 'Kontaktdaten',
        fields: [
          {
            name: 'telefon',
            label: 'Telefonnummer',
            required: true,
            counter: 20
          },
          {
            name: 'email',
            label: 'E-Mail',
            required: true,
            counter: 20
          },
          {
            name: 'strasse',
            label: 'Strasse',
            counter: 50,
            required: true
          },
          {
            name: 'plz',
            label: 'PLZ',
            counter: 5,
            required: true
          },
          {
            name: 'ort',
            label: 'Ort',
            counter: 50,
            required: true
          }
        ]
      },
      {
        name: 'bem',
        title: 'Bemerkungen',
        fields: [
          {
            name: 'vegetarisch',
            label: 'Ich bin Vegetarier!',
            componentName: 'v-checkbox'
          },
          {
            name: 'bemerkungen',
            label: 'Bemerkungen',
            componentName: 'v-textarea'
          },
          {
            name: 'lebensmittel',
            label: 'Lebensmittelunverträglichkeiten',
            componentName: 'v-textarea'
          },
          {
            name: 'gesundheitsinformationen',
            label: 'Gesundheitsinformationen (was gibt es zu beachten, Krankheiten etc.)',
            componentName: 'v-textarea'
          }
        ]
      },
      {
        name: 'erl',
        title: 'Erlaubnisse',
        fields: [
          {
            name: 'schwimen',
            label: '_',
            componentName: 'ec-schwimmen'
          },
          {
            name: '_',
            label: 'Mein Sohn/Meine Tochter darf...',
            componentName: 'ec-label'
          },
          {
            name: 'rad',
            label: 'Radfahren',
            componentName: 'v-checkbox'
          },
          {
            name: 'klettern',
            label: 'Klettern',
            componentName: 'v-checkbox'
          },
          {
            name: 'boot',
            label: 'Boot / Kanu fahren',
            componentName: 'v-checkbox'
          },
          {
            name: 'entfernen',
            label: 'Sich in einer Gruppe von mindestens drei Personen eine begrenzte Zeit vom Camp entfernen / in die Stadt gehen',
            componentName: 'v-checkbox'
          }
        ]
      },
      {
        name: 'tnBed',
        title: 'Datenschutz & Teilnahmebedingungen',
        fields: [
          {
            name: 'tnBedingungen',
            required: true,
            label: 'Ich erkenne die Teilnahmebedingungen für Freizeiten an und melde mich hiermit verbindlich an. (ggf. Einverständnis des Erziehungsberechtigten)',
            componentName: 'v-checkbox'
          },
          {
            name: 'datenschutz',
            required: true,
            label: 'Ich bin damit Einverstanden, dass die eingegeben Daten (vorerst) für bis zu 48 Stunden gespeichert werden. Während dieser Zeit hat niemand Zugriff auf diese Daten. Ich erhalte eine E-Mail mit weiteren Informationen zum Datenschutz die ich bestätigen muss bevor die Anmeldung weiterverarbeitet wird. Als Anmeldezeitpunkt für die Warteliste etc. wird der Zeitpunkt der Bestätigung angenommen. Nach 48 Stunden ohne Bestätigung wird die Anmeldung gelöscht.',
            componentName: 'v-checkbox'
          },
          {
            name: 'fahrgemeinschaften',
            label: 'Hiermit willige ich ein, dass meine Anschrift zum Zweck der Bildung von Fahrgemeinschaften bei der Organisation der An- und/oder Abreise an die anderen Teilnehmer der Reisegruppe weitergegeben werden darf. Die Erteilung der Einwilligung ist freiwillig.',
            componentName: 'v-checkbox'
          }
        ]
      }
    ],
  }, console.log);
  </script>";

  return $script;
}