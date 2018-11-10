import Vue from 'vue';
import './plugins/vuetify.js';
import Anmeldung from './Anmeldung.vue';
import 'roboto-fontface/css/roboto/roboto-fontface.css';
import '@mdi/font/css/materialdesignicons.css';

import { Form, Event } from './config';
import formElement from '@/components/formComponent';
import radio from '@/components/radio';
import datePicker from '@/components/date.vue';
import schwimmen from '@/components/schwimmen.vue';
import label from '@/components/label.vue';

Vue.config.productionTip = false;

Vue.component('ec-form-element', formElement);
Vue.component('ec-radio', radio);
Vue.component('ec-date', datePicker);
Vue.component('ec-schwimmen', schwimmen);
Vue.component('ec-label', label);


const init = (
  id: string,
  event: Event,
  form: Form,
  sendHoock: (data: Array<{ [key: string]: string | number | boolean }>) => void,
) => {
  return new Vue({
    render: (h) => h(Anmeldung, { props: { form, event }}),
  }).$mount('#' + id);
};
(window as any).createAnmeldung = init;

start();

function start() {


  return init('anmeldung', {
    id: 1,
    title: 'Test',
    start: new Date('2018-11-09T11:19:50'),
  },
  {
    steps: [
      {
        name: 'person',
        title: 'Persöhnliche Daten',
        fields: [
          {
            name: 'geschlecht',
            label: '',
            component: 'ec-radio',
            type: 'string',
            required: true,
            values: [
              {
                label: 'männlich',
                value: 'm',
              },
              {
                label: 'weiblich',
                value: 'w',
              },
            ],
            row: true,
            rules: [
              v => (!!v || v === '') || 'Bitte Geschlecht auswählen',
            ],
          },
          {
            name: 'vorname',
            label: 'Vorname',
            type: 'string',
            required: true,
            counter: true,
            maxlength: 50,
          },
          {
            name: 'nachname',
            label: 'Nachname',
            type: 'string',
            required: true,
            counter: true,
            maxlength: 50,
          },
          {
            name: 'gebDat',
            label: 'Geburtsdatum',
            type: 'string',
            required: true,
            component: 'ec-date',
          },
        ],
      },
      {
        name: 'kontakt',
        title: 'Kontaktdaten',
        fields: [
          {
            name: 'telefon',
            label: 'Telefonnummer',
            type: 'string',
            required: true,
            counter: true,
            maxlength: 20,
          },
          {
            name: 'email',
            label: 'E-Mail',
            type: 'string',
            required: true,
            counter: true,
            maxlength: 20,
          },
          {
            name: 'strasse',
            label: 'Strasse',
            type: 'string',
            counter: true,
            maxlength: 50,
            required: true,
          },
          {
            name: 'plz',
            label: 'PLZ',
            type: 'string',
            counter: true,
            maxlength: 5,
            required: true,
          },
          {
            name: 'ort',
            label: 'Ort',
            type: 'string',
            counter: true,
            maxlength: 50,
            required: true,
          },
        ],
      },
      {
        name: 'sonstiges',
        title: 'Sonstiges',
        fields: [
          {
            name: 'vegetarisch',
            label: 'Verplegung:',
            required: true,
            type: 'boolean',
            component: 'v-select',
            items: [
              { text: 'normal', value: false, },
              { text: 'vegetarisch', value: true },
            ],
          },
          {
            name: 'lebensmittel',
            label: 'Lebensmittelunverträglichkeiten',
            type: 'string',
            box: true,
            component: 'v-textarea',
          },
          {
            name: 'gesundheitsinformationen',
            label: 'Gesundheitsinformationen',
            placeholder: 'z.b. Allergien, Krankheiten etc.',
            box: true,
            type: 'string',
            component: 'v-textarea',
          }
        ]
      },
      {
        name: 'permissions',
        title: 'Erlaubnisse',
        fields: [
          {
            name: '',
            label: 'Mein Sohn/Meine Tochter darf:',
            type: 'none',
            component: 'ec-label',
          },
          {
            name: 'schwimmen',
            label: 'Schwimmen',
            type: 'boolean',
            component: 'ec-checkbox',
          },
          {
            name: 'rad',
            label: 'Radfahren',
            type: 'boolean',
            component: 'ec-checkbox',
          },
          {
            name: 'klettern',
            label: 'Klettern',
            type: 'boolean',
            component: 'ec-checkbox',
          },
          {
            name: 'boot',
            label: 'Boot / Kanu fahren',
            type: 'boolean',
            component: 'ec-checkbox',
          },
          {
            name: 'entfernen',
            label: 'Sich in einer Gruppe von mindestens drei Personen eine begrenzte Zeit vom Camp entfernen / in die Stadt gehen',
            type: 'boolean',
            component: 'ec-checkbox',
          },
        ],
      },
      {
        name: 'agreements',
        title: 'Datenschutz & Teilnahmebedingungen',
        fields: [
          {
            name: 'agrees_teilnehmer_bedingung',
            required: true,
            label: 'Ich erkenne die Teilnahmebedingungen für Freizeiten an und melde mich hiermit verbindlich an. (ggf. Einverständnis des Erziehungsberechtigten)',
            type: 'boolean',
            component: 'ec-checkbox',
          },
          {
            name: 'agrees_datenschutz',
            required: true,
            label: 'Ich bin damit Einverstanden, dass die eingegeben Daten (vorerst) für bis zu 48 Stunden gespeichert werden. Während dieser Zeit hat niemand Zugriff auf diese Daten. Ich erhalte eine E-Mail mit weiteren Informationen zum Datenschutz die ich bestätigen muss bevor die Anmeldung weiterverarbeitet wird. Als Anmeldezeitpunkt für die Warteliste etc. wird der Zeitpunkt der Bestätigung angenommen. Nach 48 Stunden ohne Bestätigung wird die Anmeldung gelöscht.',
            type: 'boolean',
            component: 'ec-checkbox',
          },
          {
            name: 'agrees_fahrgemeinschaften',
            label: 'Hiermit willige ich ein, dass meine Anschrift zum Zweck der Bildung von Fahrgemeinschaften bei der Organisation der An- und/oder Abreise an die anderen Teilnehmer der Reisegruppe weitergegeben werden darf. Die Erteilung der Einwilligung ist freiwillig.',
            type: 'boolean',
            component: 'ec-checkbox',
          },
        ],
      },
    ],
  }, console.log);
}
