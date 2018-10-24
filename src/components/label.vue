<template>
  <p>{{label}}</p>
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
