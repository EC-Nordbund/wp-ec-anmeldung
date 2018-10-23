import Vue from 'vue';
import './plugins/vuetify.js';
import App from './anmeldungsRoot.vue';
// import router from './router'
import 'roboto-fontface/css/roboto/roboto-fontface.css';
import '@mdi/font/css/materialdesignicons.css';

import { IConfig } from './config';
import formElement from '@/plugins/formComponent';

Vue.config.productionTip = false;

Vue.component('ec-form-element', formElement);

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
      name: 'a',
      title: 'Abc',
      fields: [
        {
          name: 'test',
          label: 'Hallo Welt!',
          counter: 20,
        },
      ],
    },
    {
      name: 'b',
      title: 'Abc',
      fields: [],
    },
  ],
}, console.log);
