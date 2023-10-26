<template>
    <app-layout>

        <v-tabs v-model="tabs" icons-and-text centered>
            <v-tab key="1">
                <v-icon color="poobrown">{{ "mdi-calendar-account" }}</v-icon>
                Registro
            </v-tab>
            <v-tab key="2">
                <v-icon color="poobrown">{{ "mdi-calendar-account" }}</v-icon>
                Lista Personal

            </v-tab>

        </v-tabs>
        <v-tabs-items v-model="tabs">
            <v-tab-item>
                <Registros   :cargos="cargos" :establecimiento="establecimiento" :departamentos="departamentos">
                </Registros>
            </v-tab-item>
            <v-tab-item>
                <Lista :cargos="cargos" v-if="tabs == '1'"></Lista>
            </v-tab-item>
        </v-tabs-items>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import Welcome from '@/Jetstream/Welcome'
import Barrasu from '@/Pages/Micomponet/Barrasu'
import Registros from '@/Pages/Auth/Register'
import Lista from '@/Pages/Personal/Listapersonal'

export default {
    data: () => ({
        tabs: 0
    }),
    props: {
        cargos: Array,
        establecimiento: Array,
        departamentos: Array,
    },
    components: {
        AppLayout,
        Registros,
        Lista

    },
    
}
</script>
