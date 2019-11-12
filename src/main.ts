import Vue from 'vue';
import './plugins/vuetify.js';
import Dialog from './Dialog.vue';
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
) => {
  return new Vue({
    render: (h) => h(Dialog, { props: { form, event }}),
  }).$mount('#' + id);
};
(window as any).createAnmeldung = init;

// start();

function start() {

  return init('anmeldung', {
    id: 4200,
    title: 'Test',
    start: new Date('2018-11-10T12:51:00'),
  },
  {
    steps: [
      {
        name: 'person',
        title: 'Persöhnliche Daten',
        rules: [
          (v) => v.geschlecht,
          (v) => v.vorname,
          (v) => v.vorname.length > 0,
          (v) => v.nachname,
          (v) => v.nachname.length > 0,
          (v) => v.gebDat,
          (v) => v.gebDat.length > 0,
        ],
        fields: [
          {
            name: 'geschlecht',
            label: '',
            component: 'ec-radio',
            type: 'string',
            mandatory: true,
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
            required: true,
            counter: true,
            maxlength: 50,
            rules: [
              v=>v?true:'Bitte einen Vornamen angeben'
            ]
          },
          {
            name: 'nachname',
            label: 'Nachname',
            type: 'string',
            required: true,
            counter: true,
            maxlength: 50,
            rules: [
              v=>v?true:'Bitte einen Nachnamen angeben'
            ]
          },
          {
            name: 'gebDat',
            label: 'Geburtsdatum',
            type: 'string',
            required: true,
            component: 'ec-date',
            rules: [
              v=>v?true:'Bitte ein Geburtsdatum angeben'
            ]
          },
        ],
      },
      {
        name: 'kontakt',
        title: 'Kontaktdaten',
        rules: [
          v=>v.telefon && v.telefon.length >0 && v.email && v.email.length > 0 && v.strasse && v.strasse.length > 0 && v.plz && v.plz.length === 5 && v.ort && v.ort.length > 0
        ],
        fields: [
          {
            name: 'telefon',
            label: 'Telefonnummer',
            type: 'string',
            required: true,
            counter: true,
            maxlength: 20,
            mask: '####################',
            rules: [
              v=>v?true:'Bitte eine Telefonnummer angeben'
            ]
          },
          {
            name: 'email',
            label: 'E-Mail',
            type: 'string',
            required: true,
            counter: true,
            maxlength: 50,
            rules: [
              v=>v?true:'Bitte eine E-Mail-Adresse angeben'
            ]
          },
          {
            name: 'strasse',
            label: 'Strasse',
            type: 'string',
            counter: true,
            maxlength: 50,
            required: true,
            rules: [
              v=>v?true:'Bitte eine Strasse angeben'
            ]
          },
          {
            name: 'plz',
            label: 'PLZ',
            type: 'string',
            counter: true,
            maxlength: 5,
            required: true,
            mask: '#####',
            rules: [
              v=>v?true:'Bitte eine PLZ angeben',
              v=>(v&&typeof v === 'string'&&v.length===5)?true:'Bitte eine PLZ angeben, die genau 5 Zeichen lang ist.'
            ]
          },
          {
            name: 'ort',
            label: 'Ort',
            type: 'string',
            counter: true,
            maxlength: 50,
            required: true,
            rules: [
              v=>v?true:'Bitte einen Ort angeben'
            ]
          },
        ],
      },
      {
        name: 'sonstiges',
        title: 'Sonstiges',
        fields: [
          {
            name: 'vegetarisch',
            label: 'Ich möchte vegetarisches Essen',
            required: true,
            type: 'boolean',
            component: 'ec-checkbox',
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
        title: 'Erlaubnisse eines zuständigen Erziehungsberechtigten',
        skip_ü18: true,
        fields: [
          {
            name: '',
            label: 'Ich erlaube dem Teilnehmer...',
            type: 'none',
            component: 'ec-label',
          },
          {
            name: 'schwimmen',
            label: 'Schwimmen',
            type: 'number',
            component: 'ec-schwimmen',
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
        rules: [
          (v) => v.agrees_teilnehmer_bedingung,
          (v) => v.agrees_datenschutz,
        ],
        fields: [
          {
            name: 'agrees_teilnehmer_bedingung',
            required: true, 
            label: 'Ich erkenne die <a href="https://www.ec-nordbund.de/downloads/teilnahmebedingungen/" alt="Teilnahmebedingungen" title="Teilnahmebedingungen" target="_blank"><strong>Teilnahmebedingungen</strong></a> für Freizeiten an und melde mich hiermit verbindlich an. (ggf. Einverständnis des Erziehungsberechtigten)',
            label2Html: true,
            type: 'boolean',
            rules: [
              (v) => v?true:'Diese Zustimmung ist erforderlich',
            ],
            component: 'ec-checkbox',
          },
          {
            name: 'agrees_datenschutz',
            required: true,
            label: 'Ich nehme zur Kenntnis, dass meine eingegebenen Daten vorerst für <strong>24 Stunden</strong> zwischengespeichert werden und mir eine <strong>E-Mail zur Bestätigung und Vervollständigung meiner Anmeldung</strong> zugeschickt wird.',
            label2Html: true,
            type: 'boolean',
            rules: [
              (v) => v?true:'Diese Zustimmung ist erforderlich',
            ],
            component: 'ec-checkbox',
          },
          {
            name: '',
            label: 'Optional:',
            type: 'none',
            component: 'ec-label',
          },
          {
            name: 'agrees_fahrgemeinschaften',
            label: 'Ich erkläre mich bereit meine Anschrift zum Zweck der Bildung von Fahrgemeinschaften an die anderen Teilnehmer weitergegeben werden dürfen.',
            type: 'boolean',
            component: 'ec-checkbox',
          },
        ],
      },
    ],
  });
}
