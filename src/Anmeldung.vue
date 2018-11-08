<template>
  <v-app>

    <v-toolbar color="primary">
      <v-spacer/>
        <h1 color="white">
          Anmeldung zu Veranstaltung: {{event.title}}
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
                    v-model="data[field.name]" 
                    :key="'field' + step.name + field.name"
                  />
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-spacer/>
                <v-btn @click="currentStep--">Zurück</v-btn>
                <v-btn @click="currentStep++">Weiter</v-btn>
                <v-btn @click="printData()">Absenden</v-btn>
              </v-card-actions>
            </v-card>
          </v-stepper-content>
        </v-stepper-items>

      </v-stepper>
    </v-content>

  </v-app>
</template>


<script lang="ts">
import Axios from 'axios';
import formElement from '@/components/formComponent';
import radio from '@/components/radio'
import datePicker from '@/components/date.vue'
import schwimmen from "@/components/schwimmen.vue";
import label from "@/components/label.vue";
import checkbox from "@/components/checkbox";

Vue.config.productionTip = false;

Vue.component('ec-form-element', formElement);
Vue.component('ec-radio', radio)
Vue.component('ec-date', datePicker)
Vue.component('ec-schwimmen', schwimmen)
Vue.component('ec-label', label)
Vue.component('ec-checkbox', checkbox)

import { Component, Vue, Prop, Watch } from 'vue-property-decorator';
import { Form, Event } from '@/config';

@Component({})
export default class Anmeldung extends Vue {
  public currentStep: number = 1;

  public schema: { [name:string]: Array<string> } = {}
  public data: { [name: string]: boolean | number | string } = {};

  public printData() {
    console.log(this.data);
    //this.submitData();
  }

  public submitData() {
    const json = JSON.stringify({
      eventID: this.event.id,
      data: this.data,
      schema: this.schema
    });

    Axios
      .post('https://www.ec-nordbund.de/wp-json/ec-api/v1/anmeldung', json, {
        headers: {
          'Content-Type': 'application/json'
        }
      })
      .then(response => {
        console.log(response.data);
      })
      .catch(error => {
        console.log(error);
      });
  }

  @Prop({
    required: true,
  })
  public event!: Event;

  @Prop({
    required: true,
  })
  public form!: Form;

  @Watch('config', { immediate: true })
  public onConfigChange() {

    // set init values    
    this.form.steps.forEach((step) => {
      this.schema[step.name] = step.fields.map((f) => f.name);
      step.fields.forEach((field) => {
        if(field.name.length > 0) {
          switch (field.type) {
            case 'boolean':
              this.data[field.name] = false;              
              break;

            case 'string':
              this.data[field.name] = '';
              break;
          
            default:
              break;
          }
        }
      });
    });
  }
}
</script>