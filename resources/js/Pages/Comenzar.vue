<template>
    <app-layout>
        <!--<welcome />-->
        <v-card>
            <!--<v-tabs v-model="tab" align-with-title>
                <v-tabs-slider color="yellow"></v-tabs-slider>
                <v-tab v-for="item in items" :key="item">
                    {{ item }}

                </v-tab>
            </v-tabs>

            <v-tabs-items v-model="tab" touchless>
                <v-tab-item>
                    <agenda ref='agendar' />
                </v-tab-item>
                <v-tab-item>
                    <tabla ref='tabla' />
                </v-tab-item>
            </v-tabs-items>-->
            <v-card-text>
                <tabla ref='tabla'  />
            </v-card-text>
            
        </v-card>
        <!--:datos_cita="fechas"-->
        <!--<barrasu/>-->
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import Agenda from '@/Pages/Micomponet/Agenda2'
import Tabla from '@/Pages/Micomponet/Tabla'


export default {
    data() {
        return {
            tab: null,
            items: [
                'agendar',
                'tabla'
            ],
            text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
            dark: true,
        }
    },
    props: {
        fecha_server: String,
    },
    components: {
        AppLayout,
        Agenda,
        Tabla
    },
    methods: {
        menuItems() {
            return this.menu
        },
        async initialize() {
            
            //console.log('inicio')
            //console.log(this.fecha_server)
            //console.log(this.$store.state.fecha_server )
            this.$store.dispatch('guardarfechaserver', this.fecha_server)
            //console.log(this.$store.state.fecha_server)
        }
    },
    created() {
        this.initialize()
    },



}
</script>
