<template>
        <v-content>
            <v-container fluid fill-height>
                <v-layout align-center justify-center>
                    <ul class="vuejs-countdown">
                        <li v-if="days > 0">
                            <p class="digit">{{ days | twoDigits }}</p>
                            <p class="text">{{ days > 1 ? 'days' : 'day' }}</p>
                        </li>
                        <li>
                            <p class="digit">{{ hours | twoDigits }}</p>
                            <p class="text">{{ hours > 1 ? 'hours' : 'hour' }}</p>
                        </li>
                        <li>
                            <p class="digit">{{ minutes | twoDigits }}</p>
                            <p class="text">min</p>
                        </li>
                        <li>
                            <p class="digit">{{ seconds | twoDigits }}</p>
                            <p class="text">Sec</p>
                        </li>
                    </ul>
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