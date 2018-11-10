<template>
  <v-dialog
    ref="menu"
    :close-on-content-click="false"
    v-model="menu"
    :nudge-right="40"
    transition="scale-transition"
    offset-y
    lazy
    full-width
    width="290px"
  >
    <v-text-field
      slot="activator"
      readonly
      v-model="german"
      v-bind="$attrs"
      @blur="menu = true"
    />
    <v-date-picker
    v-model="date"
    ref="picker"
    :max="new Date().toISOString().substr(0, 10)"
    min="1950-01-01"
    @change="save"
    locale="de-de">
    </v-date-picker>
  </v-dialog>
</template>

<script lang="ts">
import {
  Component,
  Vue,
  Prop,
  Watch,
  Emit
} from 'vue-property-decorator';

@Component({})
export default class DatePicker extends Vue {
  menu: boolean = false;
  date: string | null = null;

  inheritAttrs = false;

  @Prop({
    type: String,
    required: false,
    default: ''
  })
  value!: string;

  @Watch('menu')
  onDialogOpenChange(val: boolean) {
    return val && this.$nextTick(() => ((this.$refs.picker as any).activePicker = 'YEAR'));
  }


  @Watch('value', { immediate: true })
  @Emit()
  save(value: string) {
    this.date = value;
  }

  @Watch('date')
  @Emit('input')
  onDateChange(val: string) {
    if(val.length > 0) {
      this.menu = false
    }
  }

  get german(): string {
    if (
      this.date === '' ||
      this.date === null ||
      this.date === undefined
    ) {
      return '';
    } else {
      return this.date
        .split('-')
        .reverse()
        .join('.');
    }
  }
}
</script>
