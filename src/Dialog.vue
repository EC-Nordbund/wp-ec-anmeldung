<template>
    <v-app :style="{background: countdown ? '#eee' : 'transparent'}">
        <v-content>
            <v-container align-baseline justify-center>
                <v-card flat full-width color="white">

                <v-card-text v-if="countdown" transition="scale-transition">
                    <v-card-title primary-title>
                        <v-spacer/>
                            <span class="title text-xs-center text-sm-center text-md-center">
                                Anmeldung in
                            </span>
                        <v-spacer/>
                    </v-card-title>

                    <ec-countdown v-if="countdown" :until="event.start"/>

                    <v-card-title >
                        <v-spacer/>
                            <span class="title text-xs-center text-sm-center text-md-center">
                                möglich
                            </span>
                        <v-spacer/>
                    </v-card-title>

                </v-card-text>
        
                <v-card-actions>
                    <v-spacer/>
                    <v-dialog content-class="wp-overlay" v-model="dialog" fullscreen full-width :disabled="countdown">
                        <v-btn slot="activator" :disabled="countdown" block round large color="primary">Anmelden</v-btn>

                        <v-card flat>      

                            <v-toolbar dark color="primary">
                                <v-toolbar-title >{{event.title}} Anmeldung</v-toolbar-title>
                                <v-spacer/>
                                <v-btn icon dark @click.native="dialog = false">
                                    <v-icon>mdi-close</v-icon>
                                </v-btn>
                            </v-toolbar>

                            <ec-anmeldung :event="event" :form="form" :dialog="dialog"/>

                        </v-card>

                    </v-dialog>
                    <v-spacer/>
                </v-card-actions>

                </v-card>

            </v-container>
        </v-content>
    </v-app>
</template>


<script lang="ts">
import { Component, Vue, Prop, Watch } from 'vue-property-decorator';
import { Form, Event } from '@/config';

import Countdown from '@/Countdown.vue';
import Anmeldung from '@/Anmeldung.vue';

Vue.component('ec-countdown', Countdown);
Vue.component('ec-anmeldung', Anmeldung);

@Component({})
export default class Dialog extends Vue {
    private dialog: boolean = false;
    public countdown: boolean = false;

    get timeDiff() {
        const now = new Date().getTime();
        const then = this.event.start.getTime();
        
        return then - now;
    }

    @Watch('dialog', { immediate: true })
    onDialogChange(open: boolean) {
        var headers: any = window.document.getElementsByClassName('header');

        if(!!headers) {
            for (let i = 0; i < headers.length||0; i++) {
                let header = headers[i];
                header.style.display = open ? 'none' : 'inherit';
            }
        }

        var map: any = window.document.getElementById('ec-loc-map');
        
        if(!!map) {
            map.style.display = open ? 'none' : 'inherit';
        }
    }

    @Prop({
        required: true,
    })
    public event!: Event;

    @Prop({
        required: true,
    })
    public form!: Form;

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

<style>
    .wp-overlay  {
        z-index: 1000;
    }

    .application--wrap {
        min-height: unset;
    }

    @media only screen and (max-width: 600px) {
        .v-dialog__container {
            width: 100%;
        }
    }
</style>

