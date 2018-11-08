import Vue from 'vue';
import './plugins/vuetify.js';
import Anmeldung from './Anmeldung.vue';
// import router from './router'
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
    // router: router(config),
    render: (h) => h(Anmeldung, { props: { form, event }, on: { sended: sendHoock } }),
  }).$mount('#' + id);
};
(window as any).createAnmeldung = init;

start();

function start() {
  return init('app', {
    id: 1,
    title: 'Test',
    start: new Date(),
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
          },
          {
            name: 'vorname',
            label: 'Vorname',
            type: 'string',
            lenght: 50,
          },
          {
            name: 'nachname',
            label: 'Nachname',
            type: 'string',
            lenght: 50,
          },
          {
            name: 'gebDat',
            label: 'Geburtsdatum',
            type: 'string',
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
            lenght: 20,
          },
          {
            name: 'email',
            label: 'E-Mail',
            type: 'string',
            required: true,
            lenght: 20,
          },
          {
            name: 'strasse',
            label: 'Strasse',
            type: 'string',
            lenght: 50,
            required: true,
          },
          {
            name: 'plz',
            label: 'PLZ',
            type: 'string',
            lenght: 5,
            required: true,
          },
          {
            name: 'ort',
            label: 'Ort',
            type: 'string',
            lenght: 50,
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
            type: 'boolean',
            component: 'v-select',
            items: [
              { text: 'normal', value: false, },
              { text: 'vegetarisch', value: true, }
            ],
          },
          {
            name: 'lebensmittel',
            label: 'Lebensmittelunverträglichkeiten',
            type: 'string',
            component: 'v-textarea',
          },
          {
            name: 'gesundheitsinformationen',
            label: 'Gesundheitsinformationen (z.b. Allergien, Krankheiten etc.)',
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
