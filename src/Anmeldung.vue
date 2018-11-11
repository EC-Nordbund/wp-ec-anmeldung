<template>
      <v-content fluid full-width fill-height>
        <v-stepper v-model="currentStep" vertical non-linear>
            <template v-for="(step, index) in form.steps">

            <v-stepper-step 
              :step="index+1"
              :key="'s' + index"
              :rules="[v => stepperValid(step) || !visited.includes(step.name)]"
              :complete="stepperValid(step) && visited.includes(step.name)"
              :editable="visited.includes(step.name)"
              >{{step.title}}

              <small v-if="stepperValid(step) || !visited.includes(step.name)">{{step.hint}}</small>
              <small v-else>Erforderliche Felder sind leer</small>
            </v-stepper-step>

            <v-stepper-content :key="'c'+index" :step="index + 1">
              <v-card>
                <v-card-text>
                  <v-form v-model="valid[index]" :ref="'form' + index">
                    <ec-form-element 
                      v-for="field in step.fields" 
                      :field="field" 
                      v-model="data[field.name]" 
                      :key="'field' + step.name + field.name"
                    />
                  </v-form>

                  <v-alert :value="success && currentStep >= form.steps.length" type="success">Daten wurden erfolgreich übermittelt!</v-alert>
                  <v-alert :value="error && currentStep >= form.steps.length" type="error">Fehler bei der übermittling deiner Daten. Versuche es später noch einmal</v-alert>

                </v-card-text>

                <v-card-actions>
                  <v-spacer/>
                  <v-btn v-if="currentStep > 1" @click="currentStep--">Zurück</v-btn>
                  <v-btn v-if="currentStep < form.steps.length" @click="nextStep(step, index)">Weiter</v-btn>
                  <v-btn
                    v-else
                    :disabled="success || !everythingValid"
                    color="primary"
                    @click="submitData()">Absenden<v-progress-circular indeterminate v-if="loading" dark/></v-btn>
                </v-card-actions>
              </v-card>
            </v-stepper-content>
          </template>
        </v-stepper>
      </v-content>
</template>


<script lang="ts">
import Axios from 'axios';
import formElement from '@/components/formComponent';
import radio from '@/components/radio'
import datePicker from '@/components/date.vue'
import schwimmen from "@/components/schwimmen.vue";
import label from "@/components/label.vue";
import checkbox from "@/components/checkbox";
import Countdown from './Countdown.vue';


Vue.config.productionTip = false;

Vue.component('ec-countdown', Countdown)
Vue.component('ec-form-element', formElement);
Vue.component('ec-radio', radio)
Vue.component('ec-date', datePicker)
Vue.component('ec-schwimmen', schwimmen)
Vue.component('ec-label', label)
Vue.component('ec-checkbox', checkbox)

import { Component, Vue, Prop, Watch } from 'vue-property-decorator';
import { Form, Event, Step } from '@/config';

@Component({})
export default class Anmeldung extends Vue {
  private loading = false;
  private success = false;
  private error = false;
  private currIsValid = false;

  private currentStep: number = 1;
  private visited: string[] = [];

  private countdown: boolean = false;

  private valid: boolean[] = [];
    
  private schema: { [name:string]: Array<{ [name: string]: string }> } = {};
  private data: { [name: string]: boolean | number | string } = {};

  private nextStep(step: Step, index: number) {
    // if(!!this.form.steps[index+1].skip_ü18) {
      //TODO: 18+
    // }

    if(this.stepperValid(step)) {
      this.currentStep++;
    } else {
      this.visited.push(step.name);
      this.currIsValid = true;
    }
  }

  // stepper is valid if was visite before and rules met
  private stepperValid(step: Step) {
    return (step.rules||[]).map(v => () => v(this.data)).every(b => b());
  }

  get formValid() {
    return this.valid.every(b=>b);
  }

  get timeDiff() {
    const now = new Date().getTime();
    const then = this.event.start.getTime();
    
    return then - now;
  }

  get everythingValid() {
    const steppersValid = this.form.steps.every(step => this.stepperValid(step));
    const form_valid = this.formValid;

    return (steppersValid && form_valid);
  }


  private submitData() {

    if(this.everythingValid) {

      const json = JSON.stringify({
        eventID: this.event.id,
        data: this.data,
        schema: this.schema
      });

      this.loading = true; 

      Axios
        .post('https://www.ec-nordbund.de/wp-json/ec-api/v1/anmeldung', json, {
          headers: {
            'Content-Type': 'application/json'
          }
        })
        .then(response => response.data)
        .then(data => {
          this.loading = false;

          // successful transmitted
          this.success = data.state === "success";
          this.error = !this.success;

        })
        .catch(error => {
          this.error = true;
        });
    } else {
      this.error = true;
    }

    this.loading = false;
  }

  @Prop({
    required: true,
  })
  public event!: Event;

  @Prop({
    required: true,
  })
  public form!: Form;


  @Watch('currentStep')
  onStepChange(curr: number, prev: number) {
    this.currIsValid = false;
    const step = this.form.steps[prev-1]

    if(!this.visited.includes(step.name)){
      this.visited.push(step.name);
    }
  }

  @Watch('config', { immediate: true })
  public onConfigChange() {

    // set init values    
    this.form.steps.forEach((step) => {
      this.schema[step.title] = step.fields.map((f) => {
        return { name: f.name, lable: f.label };
      });

      step.fields.forEach((field) => {
        switch (field.type) {
          case 'boolean':
            this.data[field.name] = false;              
            break;

          case 'string':
            this.data[field.name] = '';
            break;
          case 'number':
            this.data[field.name] = 0;
            break;
          default:
            break;
        }
      });

    });
  }
}
</script>