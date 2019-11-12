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

  @Prop({
    type: String,
    default: ''
  })
  public label!: string

  @Prop({
    type: Boolean,
    default: false
  })
  public label2Html!: boolean

  @Prop({
    type: Boolean,
    default: false
  })
  public directAfterLabel!: boolean

  private intern_value: boolean = false;


  @Watch('value', { immediate: true })
  public onValueChange(value: boolean) {
    this.intern_value = value;
  }


  @Watch('intern_value')
  @Emit('input')
  public onInternValueChange(value: boolean) {}


  public render(h: CreateElement) {
    const children = [];

    if(this.$attrs.label2Html) children.push(
      h('div', {
        slot: 'label',
        domProps: {
          innerHTML:this.$attrs.label
        }
      })
    );

    return h('v-checkbox', {
      props: {
        ...this.$attrs,
        class: {
          'mt-2': this.directAfterLabel
        },
        value: this.intern_value,
      },
      on: {
        change: (val: boolean) => {
          this.intern_value = val;
        },
      },
    }, children);
  }
}
