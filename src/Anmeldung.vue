<template>
  <v-app>
    <v-toolbar color="primary">
      <v-spacer/>
        <h1 color="white">
          Anmeldung zu Veranstaltung: {{form.event.titel}}
        </h1>
      <v-spacer/>
    </v-toolbar>
    <v-content>
      <v-stepper v-model="currentStep">
        <v-stepper-header>
          <template v-for="(step, index) in form.steps">
            <v-divider v-if="index !== 0" :key="'d' + index"/>
            <v-stepper-step  :complete="currentStep > index+1" :step="index+1" :key="'s' + index">
              {{step.title}}
              <small>{{step.hint}}</small>
            </v-stepper-step>
          </template>
        </v-stepper-header>

        <v-stepper-items>
          <v-stepper-content v-for="(step, index) in form.steps" :key="'c'+index" :step="index + 1">
            <v-card>
              <v-card-text>
                <v-form>
                  <ec-form-element 
                    v-for="field in step.fields" 
                    :field="field" 
                    v-model="data[step.name][field.name]" 
                    :key="'Field' + step.name + field.name"
                  />
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-spacer/>
                <v-btn @click="currentStep--">Zurück</v-btn>
                <v-btn @click="currentStep++">Weiter</v-btn>
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
import { Form } from '@/config';

@Component({})
export default class Anmeldung extends Vue {
  public currentStep: number = 1;

  public data: { [name: string]: { [name: string]: boolean | number | string } } = {};

  @Prop({})
  public eventID!: number;

  @Prop({})
  public form!: Form;


  @Watch('config', { immediate: true })
  public onConfigChange() {
    console.log(this.form)
    
    this.form.steps.forEach((stepper) => {
      const tmp: any = {};
      stepper.fields.forEach((field) => {
        tmp[field.name] = '';
      });
      this.data[stepper.name] = tmp;
    });
  }
}
</script>