import { Component, Vue, Prop, Watch } from 'vue-property-decorator'
import { CreateElement } from 'vue';

@Component({})
export default class Radio extends Vue {
  @Prop({})
  public values!: Array<{ label: string; value: any }>;

  @Prop({})
  public value!: any;

  public render(h: CreateElement) {
    return h(
      'v-radio-group',
      {
        props: {
          ...this.$attrs,
          value: this.value,
        },
        on: {
          change: ($event: any) => this.$emit('input', $event),
        },
      },
      this.values.map(v => h('v-radio', { props: v })),
    );
  }
}
