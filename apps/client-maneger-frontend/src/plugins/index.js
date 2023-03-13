/**
 * plugins/index.js
 *
 * Automatically included in `./src/main.js`
 */

// Plugins
import { loadFonts } from './webfontloader'
import vuetify from './vuetify'
import router from '../router'
import axios from './axios'


export function registerPlugins (app) {
  loadFonts()
  app
    .use(vuetify)
    .use(router)

    app.config.globalProperties.$http = axios
      
}
