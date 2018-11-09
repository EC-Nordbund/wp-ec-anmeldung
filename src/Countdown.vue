<template>
        <v-content>
            <v-container fluid fill-height>
                <v-layout align-center justify-center>
                    <v-card>
                        <v-card-text>

                        <ul class="ec-countdown">
                            <li v-if="days > 0">
                                <p class="digit">{{ days | twoDigits }}</p>
                                <p class="text">{{ days > 1 ? 'Tage' : 'Tag' }}</p>
                            </li>
                            <li v-if="(days > 0 || hours > 0)">
                                <p class="digit">{{ hours | twoDigits }}</p>
                                <p class="text">{{ hours > 1 ? 'Stunden' : 'Stunde' }}</p>
                            </li>
                            <li v-if="(days > 0 || hours > 0 || minutes > 0)">
                                <p class="digit">{{ minutes | twoDigits }}</p>
                                <p class="text">{{ minutes > 1 ? 'Minuten' : 'Minute' }}</p>
                            </li>
                            <li>
                                <p class="digit">{{ seconds | twoDigits }}</p>
                                <p class="text">{{ seconds > 1 ? 'Seknden' : 'Sekunde' }}</p>
                            </li>
                        </ul>

                        </v-card-text>
                    </v-card>
                </v-layout>
            </v-container>
        </v-content>
</template>


<script lang="ts">

import { Component, Vue, Prop, Watch } from 'vue-property-decorator';
import { Event } from '@/config';

let interval: number | undefined = undefined;

@Component({
    filters: {
        twoDigits(value: number) {
            if ( value.toString().length <= 1 ) {
                return '0'+value.toString()
            }
            return value.toString()
        }
    }
})
export default class Countdown extends Vue {
    private diff: number = 0;
    private now: number = Math.trunc((new Date()).getTime() / 1000);
    private date: number = Math.trunc(new Date().getTime() / 1000);


    get days() {
        return Math.trunc(this.diff / 60 / 60 / 24)
    }

    get hours() {
        return Math.trunc(this.diff / 60 / 60) % 24
    }

    get minutes() {
        return Math.trunc(this.diff / 60) % 60
    }

    get seconds() {
        return Math.trunc(this.diff) % 60
    }


    @Watch('now')
    onNowChange(value: number) {
        this.diff = this.date - this.now;

        if(this.diff <= 0) {
            this.diff = 0;

            clearInterval(interval);

            
            window.location.reload();
        }
    }


    @Prop({
        required: true,
        default: new Date()
    })
    public until!: Date;


    created() {
        this.date = Math.trunc(this.until.getTime() / 1000);

        interval = setInterval(() => {
            this.now = Math.trunc((new Date()).getTime() / 1000);
        }, 1000);
    }

}
</script>

<style>
    .heading {
        color:#8fb217;
        font-size: 20px;
    }

    .ec-countdown {
        text-align: center;
        padding: 1em;
        margin: 0;
        min-width: 320px;
    }

    p {
        margin: 0;
    }

    .ec-countdown li {
        display: inline-block;
        margin: 0 8px;
        text-align: center;
        position: relative;
    }

    .ec-countdown li p {
        margin: 0;
    }

    .ec-countdown li:first-of-type {
        margin-left: 0;
    }

    .ec-countdown li:last-of-type {
        margin-right: 0;
    }

    .ec-countdown .digit {
        font-size: 32px;
        font-weight: 600;
        line-height: 1.4;
        margin-bottom: 0;
        color: #8fb217;

    }

    .ec-countdown .text {
        text-transform: uppercase;
        margin-bottom: 0;
        font-size: 12px;
    }
</style>