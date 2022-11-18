<template>

    <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">

        <v-card>
            <v-toolbar dark color="primary">
                <v-btn icon dark @click="close">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
                <v-toolbar-title>ATENCION</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items>
                    <v-btn dark text @click="dialog = false">
                        Guardar
                    </v-btn>
                </v-toolbar-items>
            </v-toolbar>
            <v-card>
                <v-container fluid>
                    <v-row align="center">
                        <v-col cols="12">
                            <v-select v-model="seleccion_profesion" :items="lista_profesion" item-text="cargo" attach
                                chips label="Cargo" multiple></v-select>
                        </v-col>
                    </v-row>
                    <v-row align="center">
                        <v-col cols="6">


                            <v-list two-line>
                                <v-list-item-group v-model="selected" active-class="pink--text" multiple>
                                    <template v-for="(item, index) in items">
                                        <v-list-item v-if="item.title" :key="item.title" @click="elegir(item)">
                                            <template v-slot:default="{ active }">
                                                <v-list-item-content>
                                                    <v-list-item-title v-text="item.title"></v-list-item-title>

                                                    <v-list-item-subtitle class="text--primary" v-text="item.headline">
                                                    </v-list-item-subtitle>

                                                    <v-list-item-subtitle v-text="item.subtitle">
                                                    </v-list-item-subtitle>
                                                </v-list-item-content>

                                                <v-list-item-action>
                                                    <v-list-item-action-text v-text="item.action">
                                                    </v-list-item-action-text>

                                                    <v-icon v-if="!active" color="grey lighten-1">
                                                        mdi-star-outline
                                                    </v-icon>

                                                    <v-icon v-else color="yellow darken-3">
                                                        mdi-star
                                                    </v-icon>
                                                </v-list-item-action>
                                            </template>
                                        </v-list-item>

                                        <v-divider v-if="index < items.length - 1" :key="index"></v-divider>
                                    </template>
                                </v-list-item-group>
                            </v-list>


                        </v-col>
                        <v-col cols="6">

                            <v-list two-line>
                                <v-list-item-group v-model="selected2" active-class="pink--text" multiple>
                                    <template v-for="(item, index) in items_t">
                                        <v-list-item v-if="item.title" @click=elegir(item) :key="item.title">
                                            <template v-slot:default="{ active }">
                                                <v-list-item-content>
                                                    <v-list-item-title v-text="item.title"></v-list-item-title>

                                                    <v-list-item-subtitle class="text--primary" v-text="item.headline">
                                                    </v-list-item-subtitle>

                                                    <v-list-item-subtitle v-text="item.subtitle">
                                                    </v-list-item-subtitle>
                                                </v-list-item-content>

                                                <v-list-item-action>
                                                    <v-list-item-action-text v-text="item.action">
                                                    </v-list-item-action-text>

                                                    <v-icon v-if="!active" color="grey lighten-1">
                                                        mdi-star-outline
                                                    </v-icon>

                                                    <v-icon v-else color="yellow darken-3">
                                                        mdi-star
                                                    </v-icon>
                                                </v-list-item-action>
                                            </template>
                                        </v-list-item>

                                        <v-divider v-if="index < items.length - 1" :key="index"></v-divider>
                                    </template>
                                </v-list-item-group>
                            </v-list>
                            <v-btn class="mx-2" fab dark color="indigo">
                                
                                <v-icon dark>
                                    mdi-plus
                                </v-icon>
                            </v-btn>
                        </v-col>

                    </v-row>
                </v-container>
            </v-card>
        </v-card>

    </v-dialog>


</template>
<script>

import { throwStatement } from '@babel/types';
import axios from 'axios'
import moment from 'moment'
const day1 = new Date().getFullYear() + '-' + ("0" + (new Date().getMonth() + 1)).slice(-2) + '-' + ("0" + new Date().getDate()).slice(-2)

export default {

    props: {


    },

    data: () => ({
        vista: true,
        dialog: false,

        seleccion_profesion: ['foo', 'bar', 'fizz', 'buzz', 'add'],
        lista_profesion: ['foo', 'bar', 'fizz', 'add'],
        selected: [],
        selected2: [],
        items_t: [],
        items: [
            {
                action: '15 min',
                headline: 'Brunch this weekend?',
                subtitle: `I'll be in your neighborhood doing errands this weekend. Do you want to hang out?`,
                title: 'Ali Connors',
            },
            {
                action: '2 hr',
                headline: 'Summer BBQ',
                subtitle: `Wish I could come, but I'm out of town this weekend.`,
                title: 'me, Scrott, Jennifer',
            },
            {
                action: '6 hr',
                headline: 'Oui oui',
                subtitle: 'Do you have Paris recommendations? Have you ever been?',
                title: 'Sandra Adams',
            },
            {
                action: '12 hr',
                headline: 'Birthday gift',
                subtitle: 'Have any ideas about what we should get Heidi for her birthday?',
                title: 'Trevor Hansen',
            },
            {
                action: '18hr',
                headline: 'Recipe to try',
                subtitle: 'We should eat this: Grate, Squash, Corn, and tomatillo Tacos.',
                title: 'Britta Holt',
            },
        ],
    }),
    created() {
        console.log("inicioao");

    },
    updated() {

    },
    mounted() {


    },
    methods: {
        open() {
            this.dialog = true
            this.lista_cargo()
        },
        close() {
            this.dialog = false
        },
        save() {
            this.dialog = true
        },
        async lista_cargo() {
            try {
                var res = await axios({
                    method: 'post',
                    url: `/${process.env.MIX_CARPETA}/api/listar_profesion/`,
                }).then(
                    (response) => {
                        console.log(response);
                        this.lista_profesion = []
                        this.seleccion_profesion = []
                        this.lista_profesion = response.data
                        this.seleccion_profesion = response.data

                    }, (error) => {
                        console.log(error);
                    }
                );
            } catch (err) {
                console.log("err->", err.response.data)
                return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
            }

        },
        
        elegir(active) {
            console.log(active.title)
            let s = this.items_t.findIndex(x => x.title === active.title)
            console.log(s);
            if (s >= 0) {
                this.items_t.splice(s, 1);
            } else {
                this.items_t.push(active)

            }
        },
        comparar(x) {

            console.log(x.title)
            return this.items_t.title === x.title;
        },
    }
}
</script>
