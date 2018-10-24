import Vue from 'vue';
import './plugins/vuetify.js';
import App from './anmeldungsRoot.vue';
// import router from './router'
import 'roboto-fontface/css/roboto/roboto-fontface.css';
import '@mdi/font/css/materialdesignicons.css';

import { IConfig } from './config';
import formElement from '@/plugins/formComponent';
import radio from '@/components/radio'
import datePicker from '@/components/date.vue'
import schwimmen from "@/components/schwimmen.vue";
import label from "@/components/label.vue";

Vue.config.productionTip = false;

Vue.component('ec-form-element', formElement);
Vue.component('ec-radio', radio)
Vue.component('ec-date', datePicker)
Vue.component('ec-schwimmen', schwimmen)
Vue.component('ec-label', label)


const init = (
  id: string,
  config: IConfig,
  sendHoock: (data: Array<{ [key: string]: string | number | boolean }>) => void,
) => {
  return new Vue({
    // router: router(config),
    render: (h) => h(App, { props: { config }, on: { sended: sendHoock } }),
  }).$mount('#' + id);
}
; (window as any).createAnmeldung = init;


init('app', {
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
          // label: 'Geschlecht',
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
          label: 'Ich bin damit Einverstanden, dass die eingegeben Daten (vorerst) für bis zu 48 Stunden gespeichert werden. Ich erhalte eine E-Mail mit weiteren Informationen zum Datenschutz die ich bestätigen muss bevor die Anmeldung weiterverarbeitet wird. Nach 48 Stunden ohne Bestätigung wird die Anmeldung gelöscht.',
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
