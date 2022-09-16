<template>
    <v-app id="inspire" class="grey--text text--darken-2">
        <div class="c_azul">


            <!-- Page Heading -->
            <v-app-bar color="#a2315a" class="white--text" app>
                <v-app-bar-nav-icon style="color: white;" @click="drawer = !drawer">

                </v-app-bar-nav-icon>

                <v-toolbar-title>UTRAID
                </v-toolbar-title>
                <v-btn class="mx-2" @click="toggleTheme()">
                    <v-icon>
                        mdi-pencil
                    </v-icon>
                </v-btn>

            </v-app-bar>

            <!--Page Content-->
            <v-navigation-drawer v-model="drawer" color="#a2315a" style="color: white;" app>
                <v-list v-if="$page.props.user" dense>
                    <v-list-item-group v-model="idlist">

                        <v-list-item class="px-2" key="0">
                            <v-img max-height="80" max-width="150" src="assets/logo-sedes-lapaz.png"></v-img>
                        </v-list-item>

                        <v-list-item link>
                            <v-list-item-content style="color: white;" key="1">
                                <v-list-item-title>
                                    {{ $page.props.user.name }}
                                </v-list-item-title>
                                <v-list-item-subtitle style="color: white;">{{ $page.props.user.email }}
                                </v-list-item-subtitle>
                            </v-list-item-content>
                        </v-list-item>
                        <v-list-item link key="2">
                            <form method="POST" @submit.prevent="logout">
                                <v-hover>
                                    <jet-responsive-nav-link as="button">

                                        <v-list-item-title style="color: black;">Cerrar Secion</v-list-item-title>
                                    </jet-responsive-nav-link>
                                </v-hover>
                            </form>
                        </v-list-item>

                        <jet-nav-link :href="route('inicio')">
                            <v-list-item link key="3">
                                <v-list-item-content style="color: white;">
                                    <v-list-item-title>
                                        <v-icon>mdi-file-document-edit</v-icon>
                                        <span>Agendar</span>
                                    </v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </jet-nav-link>
                        <jet-nav-link :href="route('registro')">
                            <v-list-item v-if=" $page.props.user.cargo=='Admin'" key="4" link>
                                <v-list-item-content style="color: white;">
                                    <v-list-item-title>
                                        <v-icon>mdi-file-document-edit</v-icon>
                                        <span>Registar Usuario</span>
                                    </v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </jet-nav-link>
                        <jet-nav-link :href="route('configuracion')">
                            <v-list-item v-if=" $page.props.user.cargo=='Admin'" key="5" link>

                                <v-list-item-content style="color: white;">
                                    <v-list-item-title>
                                        <v-icon>mdi-file-document-edit</v-icon>
                                        <span>Configuracion</span>
                                    </v-list-item-title>

                                </v-list-item-content>
                            </v-list-item>
                        </jet-nav-link>

                    </v-list-item-group>
                </v-list>
            </v-navigation-drawer>
            <v-main>
                <slot></slot>
            </v-main>

            <!-- Modal Portal -->
            <portal-target name="modal" multiple>
            </portal-target>
            <v-dialog v-model="v_carga" hide-overlay persistent width="300">
                <v-card color="primary" dark>
                    <v-card-text>
                        Espere por favor
                        <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                    </v-card-text>
                </v-card>
            </v-dialog>
        </div>
    </v-app>
</template>

<script>

import JetApplicationMark from '@/Jetstream/ApplicationMark'
import JetBanner from '@/Jetstream/Banner'
import JetDropdown from '@/Jetstream/Dropdown'
import JetDropdownLink from '@/Jetstream/DropdownLink'
import JetNavLink from '@/Jetstream/NavLink'
import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink'
import { VueColor } from 'vue-color/dist/vue-color.min.js'


const THEME_DARK = 'dark';
const THEME_LIGHT = 'light';
export default {
    components: {
        JetApplicationMark,
        JetBanner,
        JetDropdown,
        JetDropdownLink,
        JetNavLink,
        JetResponsiveNavLink,
    },

    data() {
        return {

            drawer: true,
            itemsDrawer: [{
                icon: 'mdi-security',
                text: 'Menu 1',
            },
            {
                icon: 'mdi-text-box-multiple-outline',
                text: 'Menu 2',
            },
            {
                icon: 'mdi-contacts',
                text: 'Menu 3',
                model: false,
                children: [
                    { icon: 'mdi-menu-right', text: 'Nested 1' },
                    { icon: 'mdi-menu-right', text: 'Nested 2' }
                ]
            }
            ],
            showingNavigationDropdown: false,
            drawer: null,
            items: [{
                title: 'Home',
                icon: 'mdi-view-dashboard'
            },
            {
                title: 'About',
                icon: 'mdi-forum'
            },
            ],
            v_carga: false,
            pendingRequests: 0,
            totalRequests: 0,
            dark: false,
            idlist: 0,
        }
    },

    methods: {

        switchToTeam(team) {
            this.$inertia.put(route('current-team.update'), {
                'team_id': team.id
            }, {
                preserveState: false
            })
        },

        logout() {

            this.$inertia.post(route('logout'));
        },
        enableInterceptor() {
            axios.interceptors.request.use(config => {
                // Every Axios request should increment our counter
                this.v_carga = true;
                this.pendingRequests++;
                this.totalRequests++;
                return config;
            });
            axios.interceptors.response.use(response => {
                // Every Axios response should decrease our counter
                this.pendingRequests--;
                if (this.pendingRequests == 0) {
                    this.v_carga = false;
                }
                return response;
            });
        },
        toggleTheme() {
            if (this.theme == THEME_DARK) {
                this.theme = THEME_LIGHT;
                this.$vuetify.theme.dark = false;
            } else {
                this.theme = THEME_DARK;
                this.$vuetify.theme.dark = true;
            }
        },
    },
    mounted() {
        this.enableInterceptor()
    },
}
</script>
