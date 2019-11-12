<template>
  <div class="body-2 grey--text text--darken-2 mt-3 pt-1" v-html="label"></div>
</template>
<script lang="ts">
import { Component, Vue, Prop, Watch } from 'vue-property-decorator';

@Component({})
export default class ecLabel extends Vue {
  @Prop({
    type: String
  })
  label!: string

}
</script>
