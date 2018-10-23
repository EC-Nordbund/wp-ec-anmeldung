import { Component, Vue, Prop } from 'vue-property-decorator';
import { IFieldConfig } from '@/config';
import { CreateElement } from 'vue';

import comps from '@/plugins/neededImports';

@Component({
  components: {
    ...comps,
  },
})
export default class formElement extends Vue {
  @Prop({
    required: true,
    type: Object,
  })
  public config!: IFieldConfig;

  @Prop({
    default: '',
    required: false,
  })
  public value!: any;

  public render(h: CreateElement) {
    return h(this.value.componentName || 'v-text-field', {
      props: { ...this.config, value: this.value },
      attrs: this.config,
      on: {
        input: ($event: any) => this.$emit('input', $event),
      },
    });
  }
}
