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
            lenght: 50,
          },
          {
            name: 'nachname',
            label: 'Nachname',
            lenght: 50,
          },
          {
            name: 'gebDat',
            label: 'Geburtsdatum',
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
            required: true,
            lenght: 20,
          },
          {
            name: 'email',
            label: 'E-Mail',
            required: true,
            lenght: 20,
          },
          {
            name: 'strasse',
            label: 'Strasse',
            lenght: 50,
            required: true,
          },
          {
            name: 'plz',
            label: 'PLZ',
            lenght: 5,
            required: true,
          },
          {
            name: 'ort',
            label: 'Ort',
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
            name: '',
            label: 'Verplegung:',
            component: 'ec-label',
          },
          {
            name: 'vegetarisch',
            label: 'vegetarisch',
            component: 'v-checkbox',
          },
          {
            name: 'lebensmittel',
            label: 'Lebensmittelunverträglichkeiten',
            component: 'v-textarea',
          },
          {
            name: 'gesundheitsinformationen',
            label: 'Gesundheitsinformationen (z.b. Allergien, Krankheiten etc.)',
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
            component: 'ec-label',
          },
          {
            name: 'schwimmen',
            label: 'Schwimmen',
            component: 'v-checkbox',
          },
          {
            name: 'rad',
            label: 'Radfahren',
            component: 'v-checkbox',
          },
          {
            name: 'klettern',
            label: 'Klettern',
            component: 'v-checkbox',
          },
          {
            name: 'boot',
            label: 'Boot / Kanu fahren',
            component: 'v-checkbox',
          },
          {
            name: 'entfernen',
            label: 'Sich in einer Gruppe von mindestens drei Personen eine begrenzte Zeit vom Camp entfernen / in die Stadt gehen',
            component: 'v-checkbox',
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
            component: 'v-checkbox',
          },
          {
            name: 'agrees_datenschutz',
            required: true,
            label: 'Ich bin damit Einverstanden, dass die eingegeben Daten (vorerst) für bis zu 48 Stunden gespeichert werden. Während dieser Zeit hat niemand Zugriff auf diese Daten. Ich erhalte eine E-Mail mit weiteren Informationen zum Datenschutz die ich bestätigen muss bevor die Anmeldung weiterverarbeitet wird. Als Anmeldezeitpunkt für die Warteliste etc. wird der Zeitpunkt der Bestätigung angenommen. Nach 48 Stunden ohne Bestätigung wird die Anmeldung gelöscht.',
            component: 'v-checkbox',
          },
          {
            name: 'agrees_fahrgemeinschaften',
            label: 'Hiermit willige ich ein, dass meine Anschrift zum Zweck der Bildung von Fahrgemeinschaften bei der Organisation der An- und/oder Abreise an die anderen Teilnehmer der Reisegruppe weitergegeben werden darf. Die Erteilung der Einwilligung ist freiwillig.',
            component: 'v-checkbox',
          },
        ],
      },
    ],
  }, console.log);
}
