import { CreateElement } from 'vue'
import { Component, Vue, Prop, Watch, Emit } from 'vue-property-decorator'

/**
 * V-Switch Wrapper
 *
 * @export
 * @class switchEl
 * @extends {Vue}
 */
@Component({})
export default class ecCheckbox extends Vue {

  @Prop({
    type: Boolean,
    required: true,
    default: false,
  })
  public value!: boolean;


  private intern_value: boolean = false;


  @Watch('value', { immediate: true })
  public onValueChange(value: boolean) {
    this.intern_value = value;
  }


  @Watch('intern_value')
  @Emit('input')
  public onInternValueChange(value: boolean) {}


  public render(h: CreateElement) {
    return h('v-checkbox', {
      props: {
        ...this.$attrs,
        value: this.value,
      },
      on: {
        change: (val: boolean) => {
          this.intern_value = !!val;
        },
      },
    });
  }
}
