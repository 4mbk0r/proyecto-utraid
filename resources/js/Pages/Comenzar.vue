<template>
    <app-layout>


                    <!--<welcome />-->

                    <v-card   > 
                        

                        <v-tabs v-model="tab"  align-with-title>
                            <v-tabs-slider color="yellow"></v-tabs-slider>

                            <v-tab v-for="item in items" :key="item">
                                {{ item }}
                            </v-tab>
                        </v-tabs>

                        <v-tabs-items v-model="tab" touchless>
                            <v-tab-item v-for="item in items" :key="item">
                                <v-card flat>
                                    <barrasu v-if="item == 'agendar'" :datos_cita="fechas" />
                                </v-card>
                            </v-tab-item>
                        </v-tabs-items>
                    </v-card>
                    <!--<barrasu :datos_cita="fechas" />-->
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
        }
    }

}
</script>
