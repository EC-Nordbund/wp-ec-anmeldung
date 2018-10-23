<template>
  <v-radio-group :value="value" @change="$emit('input', $event)" v-bind="$attrs">
    <v-radio label="Männlich" value="m"/>
    <v-radio label="Weiblich" value="w"/>
  </v-radio-group>
</template>
<script lang="ts">
import { Component, Vue, Prop, Watch } from 'vue-property-decorator';
import { IConfig } from '@/config';

@Component({})
export default class geschlechtRadio extends Vue {
  @Prop()
  value!: any
}
</script>