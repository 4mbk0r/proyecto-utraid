<template>
    <app-layout>
        <!--<welcome />-->
        <v-card>
            <v-tabs v-model="tab" align-with-title>
                <v-tabs-slider color="yellow"></v-tabs-slider>
                <v-tab v-for="item in items" :key="item">
                    {{ item }}

                </v-tab>
            </v-tabs>

            <v-tabs-items v-model="tab" touchless>
                <v-tab-item>
                    <v-card flat>
                        <barrasu />
                    </v-card>
                </v-tab-item>
                <v-tab-item>
                    {{ $store.getters.gethoy }}
                    {{ $store.getters.getfechas }}
                    {{ $store.getters.getfecha }}
                    {{ $store.getters.getConfig}}
                </v-tab-item>
            </v-tabs-items>
        </v-card>
        <!--:datos_cita="fechas"-->
        <!--<barrasu/>-->
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import Welcome from '@/Jetstream/Welcome'
import Barrasu from '@/Pages/Micomponet/Barrasu'
import Agenda from '@/Pages/Micomponet/Agenda'

export default {
    data() {
        return {
            tab: null,
            items: [
                'agendar', 'atender',
            ],
            text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
            dark: true,
        }
    },
    props: {
        fechas: Array,
    },
    components: {
        AppLayout,
        Welcome,
        Barrasu,
        Agenda,
    },
    methods: {
        menuItems() {
            return this.menu
        },
        async initialize() {
            console.log('inicio')
            console.log(this.fechas["data"])
            this.$store.state.fecha = this.fechas["data"]
            await this.$store.dispatch('pedirConfig')
        }
    },
    created() {
        this.initialize()

        console.log('--sss-')
        console.log(this.$store.state.config_data)
    },



}
</script>
