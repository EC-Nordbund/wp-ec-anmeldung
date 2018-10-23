import Vue from 'vue'
import Vuetify from 'vuetify/lib'
import 'vuetify/src/stylus/app.styl'
import de from 'vuetify/src/locale/de'

Vue.use(Vuetify, {
  theme: {
    primary: '#8FB217',
    secondary: '#46A215',
    accent: '#AC1636',
    error: '#FF5252',
    info: '#2196F3',
    success: '#4CAF50',
    warning: '#FFC107'
  },
  iconfont: 'mdi',
  lang: {
    locales: { de },
    current: 'de'
  }
})
