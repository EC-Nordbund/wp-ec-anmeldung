import Vue from 'vue'
import Vuetify, {
  VTextField,
  VSelect,
  VAutocomplete,
  VCheckbox,
  VRadioGroup,
  VRadio,
  VRangeSlider,
  VSlider,
  VCombobox,
  VSwitch,
  VTextarea
} from 'vuetify/lib'
import 'vuetify/src/stylus/app.styl'
import de from 'vuetify/src/locale/de'

Vue.use(Vuetify, {
  theme: {
    primary: '#8FB217',
    secondary: '#46A215',
    accent: '#AC1636',
    error: '#FF5252',
    info: '#46A215',
    success: '#4CAF50',
    warning: '#FFC107',
    white: '#FFFFFF',
    black: '#000000',
    bg: '#eef3dc',
    male: '#4697BA',
    female: '#C243AA'
  },
  components: {
    VTextField,
    VSelect,
    VAutocomplete,
    VCheckbox,
    VRadioGroup,
    VRadio,
    VRangeSlider,
    VSlider,
    VCombobox,
    VSwitch,
    VTextarea,
  },
  iconfont: 'mdi',
  lang: {
    locales: { de },
    current: 'de'
  }
})