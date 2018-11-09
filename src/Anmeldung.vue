<template>
  <v-app>

    
    
      <v-toolbar color="primary">
        <v-spacer/>
          <h1 color="white">
            Anmeldung zu Veranstaltung: {{event.title}}
          </h1>
        <v-spacer/>
      </v-toolbar>

      <ec-countdown v-if="countdown" :until="event.start"/>
      
      <template v-else>

      <v-content>
        <v-stepper v-model="currentStep" vertical non-linear>
            <template v-for="(step, index) in form.steps">

              <v-stepper-step  :step="index+1" :key="'s' + index" editable>
                {{step.title}}
                <small>{{step.hint}}</small>
              </v-stepper-step>

            <v-stepper-content :key="'c'+index" :step="index + 1">
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
                  <v-btn v-if="currentStep < form.steps.length" @click="currentStep++">Weiter</v-btn>
                  <v-btn v-else @click="printData()">Absenden</v-btn>
                </v-card-actions>
              </v-card>
            </v-stepper-content>
          
          </template>
        </v-stepper>
      </v-content>

    </template>

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
import { Form, Event } from '@/config';

@Component({})
export default class Anmeldung extends Vue {
  public currentStep: number = 1;

  public schema: { [name:string]: Array<string> } = {}
  public data: { [name: string]: boolean | number | string } = {};

  public countdown: boolean = false;

  get timeDiff() {
    const now = new Date().getTime();
    const then = this.event.start.getTime();
    
    return then - now;
  }

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
      });
    });
  }

  created() {
    // determine whether to show countdown or not 
    this.countdown = this.timeDiff > 0;

    // if countdown is visible,  set timeout to show Anmeldung
    if(this.countdown) {
      setTimeout(() => {
          this.countdown = false;
        }, this.timeDiff );
    }
  }
}
</script>