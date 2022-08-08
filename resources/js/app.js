require('./bootstrap');
// Import modules...
//import JQuery from 'jquery-ui/themes/base/all.css';
import Vue from 'vue';
import { VueColor } from 'vue-color/dist/vue-color.min.js'
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue';
import { InertiaProgress } from '@inertiajs/progress'

import PortalVue from 'portal-vue';
//add these two line
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import VuetifyDialogPromise from "vuetify-dialog-promise";
import { Sketch } from 'vue-color-sketch'
import { Photoshop } from 'vue-color'
//import  i18n from  '@/plugins/vue-i18n'
/*import 'jquery-ui/external/jquery-3.6.0/jquery.js'
import JQuery from "jquery/src/jquery.js"
global.$ = JQuery;
*/
const THEME_DARK = 'dark';
const THEME_LIGHT = 'light';
InertiaProgress.init()
Vue.mixin({ methods: { route } });
Vue.use(InertiaPlugin);
Vue.use(PortalVue);
//also add this line
Vue.use(Vuetify);
Vue.use(VuetifyDialogPromise, {
    locale: "fi",
    snackbarX: "left",
    snackbarY: "bottom"
});
const app = document.getElementById('app');

new Vue({
    vuetify: new Vuetify({
        treeShake: true,
        'sketch-picker': Sketch,
        'photoshop-picker': Photoshop,
        themes: {
            dark: {
                primary: "#f44336",
                secondary: "#e57373",
                accent: "#9c27b0",
                error: "#f44336",
                warning: "#ffeb3b",
                info: "#2196f3",
                success: "#4caf50"
            }
        },
        methods: {
            
        },
        icons: {
            iconfont: "mdi",
        }
    }),
    render: (h) =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: (name) => require(`./Pages/${name}`).default,
            },
        }),
}).$mount(app);



