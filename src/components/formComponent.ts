import { Component, Vue, Prop } from 'vue-property-decorator';
import { Field } from '@/config';
import { CreateElement } from 'vue';

@Component({})
export default class formElement extends Vue {
  @Prop({
    required: true,
    type: Object,
  })
  public field!: Field;

  @Prop({
    default: '',
    required: false,
  })
  public value!: any;

  public render(h: CreateElement) {
    return h(this.field.component || 'v-text-field', {
      props: { ...this.field, value: this.value },
      attrs: this.field,
      on: {
        input: ($event: any) => this.$emit('input', $event),
      },
    });
  }
}
