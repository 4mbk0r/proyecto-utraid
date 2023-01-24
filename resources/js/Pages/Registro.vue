<template>
    <app-layout>
        
        <v-tabs v-model="tabs" icons-and-text centered>
            <v-tab key="1">
                <v-icon color="poobrown">{{ "mdi-calendar-account" }}</v-icon>
                Registro
            </v-tab>
            <v-tab key="2">
                <v-icon color="poobrown">{{ "mdi-calendar-account" }}</v-icon>
                Personal
                
            </v-tab>
            
        </v-tabs>
        <v-tabs-items v-model="tabs" touchless>
            <v-tab-item>
                <Registros :cargos="cargos" :establecimiento="establecimiento"></Registros>
            </v-tab-item>
            <v-tab-item>
                <Lista v-if="tabs=='1'"></Lista>
            </v-tab-item>
            <v-tab-item> </v-tab-item>
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
    },
    components: {
        AppLayout,
        Registros,
        Lista

    },
}
</script>
