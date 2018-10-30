<template>
  <v-app>
    <v-toolbar color="primary">
      <v-spacer/>
        <h1 color="white">
          Anmeldung zu Veranstaltung: {{config.vConfig.bezeichnung}}
        </h1>
      <v-spacer/>
    </v-toolbar>
    <v-content>
      <v-stepper v-model="e1">
        <v-stepper-header>
          <template v-for="(step, ind) in config.form">
            <v-divider v-if="ind !== 0" :key="'d' + ind"/>
            <v-stepper-step  :complete="e1 > ind+1" :step="ind+1" :key="'s' + ind">
              {{step.title}}
              <small>{{step.hint}}</small>
            </v-stepper-step>
          </template>
        </v-stepper-header>

        <v-stepper-items>
          <v-stepper-content v-for="(step, ind) in config.form" :key="'c'+ind" :step="ind + 1">
            <v-card>
              <v-card-text>
                <v-form>
                  <ec-form-element 
                    v-for="field in step.fields" 
                    :config="field" 
                    v-model="data[step.name][field.name]" 
                    :key="'Field' + step.name + field.name"
                  />
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-spacer/>
                <v-btn @click="e1--">Zurück</v-btn>
                <v-btn @click="e1++">Weiter</v-btn>
                <v-btn>Absenden</v-btn>
              </v-card-actions>
            </v-card>
          </v-stepper-content>

        </v-stepper-items>
      </v-stepper>
    </v-content>
  </v-app>
</template>
<script lang="ts">
import formElement from '@/components/formComponent';
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

import { Component, Vue, Prop, Watch } from 'vue-property-decorator';
import { IConfig } from '@/config';

@Component({})
export default class Anmeldung extends Vue {
  public e1: number = 1;

  public data: { [name: string]: { [name: string]: boolean | number | string } } = {};

  @Prop({})
  public eventId!: number;

  @Prop({})
  public config!: IConfig;


  @Watch('formConfig', { immediate: true })
  public onConfigChange() {
    console.log(this.config)
    
    this.config.form.forEach((stepper) => {
      const tmp: any = {};
      stepper.fields.forEach((field) => {
        tmp[field.name] = '';
      });
      this.data[stepper.name] = tmp;
    });
  }
}
</script>