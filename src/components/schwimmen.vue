<template>
  <div>
    <v-checkbox v-model="erlaubt" label="Schwimmen"/>
    <v-radio-group v-model="radio" v-if="erlaubt" label="Schwimmfähigkeit">
      <v-radio label="Gut" :value="3"/>
      <v-radio label="Mittel" :value="2"/>
      <v-radio label="Nichtschwimmer" :value="1"/>
    </v-radio-group>
  </div>
</template>
<script lang="ts">
import { Component, Vue, Prop, Watch } from 'vue-property-decorator';

@Component({})
export default class schwimmen extends Vue {
  erlaubt:boolean = false

  radio: number = 0

  @Prop()
  value!: number

  @Watch('value', {immediate: true})
  onValueChange () {
    this.radio = this.value
    this.erlaubt  = !!this.value
  }

  @Watch('radio')
  @Watch('erlaubt')
  onChange(){
    this.$emit('input', this.erlaubt?this.radio:0)
  }
}
</script>
