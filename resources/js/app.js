require('./bootstrap');
import '@mdi/font/css/materialdesignicons.css'

// Import modules...
//import JQuery from 'jquery-ui/themes/base/all.css';
import Vue from 'vue';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue';
import { InertiaProgress } from '@inertiajs/progress'

import PortalVue from 'portal-vue';
//add these two line
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import VuetifyDialogPromise from "vuetify-dialog-promise";

import axios from 'axios'
import VueAxios from 'vue-axios'
import store from './store'
import { XlsxRead, XlsxTable, XlsxSheets, XlsxJson, XlsxWorkbook, XlsxSheet, XlsxDownload } from 'vue-xlsx'



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
Vue.use({ XlsxRead, XlsxTable, XlsxSheets, XlsxJson, XlsxWorkbook, XlsxSheet, XlsxDownload});

//also add this line    
Vue.use(Vuetify);
Vue.use(VueAxios, axios);

Vue.use(VuetifyDialogPromise, {
    locale: "fi",
    snackbarX: "left",
    snackbarY: "bottom"
});
const app = document.getElementById('app');

new Vue({
    vuetify: new Vuetify({

        treeShake: true,
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
    store,
    render: (h) =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: (name) => require(`./Pages/${name}`).default,
            },
        }),
}).$mount(app);



