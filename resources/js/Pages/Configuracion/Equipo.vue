
<template>
    <AppLayout>
        <v-row>
            <v-col cols="6">
                <v-list two-line>
                    <v-list-item-group v-model="selected_equipo" active-class="pink--text">
                        <template v-for="(item, index) in equipo">
                            <v-list-item :key="item.title" @click="seleccion_equipo">
                                <template v-slot:default="{ active }">
                                    <v-list-item-content>
                                        <v-list-item-title v-text="item.equipo"></v-list-item-title>

                                        <v-list-item-subtitle class="text--primary"
                                            v-text="item.headline"></v-list-item-subtitle>

                                        <v-list-item-subtitle v-text="item.ap_paterno"></v-list-item-subtitle>
                                    </v-list-item-content>

                                    <v-list-item-action>
                                        <v-list-item-action-text v-text="item.action"></v-list-item-action-text>

                                        <v-icon v-if="!active" color="grey lighten-1">
                                            mdi-star-outline
                                        </v-icon>

                                        <v-icon v-else color="yello w darken-3">
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
            <!--seleccion_equipo(selected_equipo)">-->

            <v-col cols="6">
                <v-list two-line v-if="selected_equipo >= 0">
                    <v-list-item-group v-model="selected_lista" active-class="pink--text">
                        <template v-for="(item, index) in items">

                            <v-list-item v-if="item.equipo === selected_equipo && item.guardar" :key="item.title">
                                <template v-slot:default="{ active }">
                                    <v-list-item-content>
                                        <v-list-item-title v-text="item.nombre"></v-list-item-title>

                                        <v-list-item-subtitle class="text--primary"
                                            v-text="item.headline"></v-list-item-subtitle>

                                        <v-list-item-subtitle v-text="item.ap_paterno"></v-list-item-subtitle>
                                    </v-list-item-content>

                                    <v-list-item-action>
                                        <v-list-item-action-text v-text="item.action"></v-list-item-action-text>

                                        <v-icon v-if="!active" color="grey lighten-1">
                                            mdi-star-outline
                                        </v-icon>

                                        <v-icon v-else color="yello w darken-3">
                                            mdi-star
                                        </v-icon>
                                        <v-btn @click = "selected_delete(item)">
                                            <v-icon color="yello w darken-3">
                                                mdi-delete
                                            </v-icon>
                                        </v-btn>
                                    </v-list-item-action>
                                </template>
                            </v-list-item>

                            <v-divider v-if="index < items.length - 1" :key="index"></v-divider>

                        </template>
                        <v-row>
                            <v-col>
                                <v-btn class="mx-2" fab dark color="indigo">
                                    <v-icon dark>
                                        mdi-content-save-outline
                                    </v-icon>
                                </v-btn>
                            </v-col>
                            <v-col>
                                <v-btn class="mx-2" fab dark color="indigo">
                                    <v-icon dark>
                                        mdi-delete
                                    </v-icon>
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-list-item-group>
                </v-list>
            </v-col>

        </v-row>
        <v-row>
            <v-col cols="4">
                <v-list two-line>
                    <h3>Medico</h3>
                    <v-list-item-group v-model="selected_medico" active-class="pink--text">
                        <template v-for="(item, index) in items">
                            <v-list-item v-if="item.cargo == 'Medico General' && !item.guardar" :key="item.title"
                                @click="addequipo(item, index)">
                                <template v-slot:default="{ active }">
                                    <v-list-item-content>
                                        <v-list-item-title v-text="item.nombre"></v-list-item-title>

                                        <v-list-item-subtitle class="text--primary"
                                            v-text="item.headline"></v-list-item-subtitle>

                                        <v-list-item-subtitle v-text="item.ap_paterno"></v-list-item-subtitle>
                                    </v-list-item-content>

                                    <v-list-item-action>
                                        <v-list-item-action-text v-text="item.action"></v-list-item-action-text>

                                        <v-icon v-if="!(item.equipo >= 0)" color="grey lighten-1">
                                            mdi-star-outline
                                        </v-icon>

                                        <v-icon v-else color="yellow darken-3">
                                            {{ item.equipo + 1 }}
                                        </v-icon>
                                    </v-list-item-action>
                                </template>
                            </v-list-item>

                            <v-divider v-if="index < items.length - 1" :key="index"></v-divider>
                        </template>
                    </v-list-item-group>
                </v-list>



            </v-col>

            <v-col cols="4">
                <h3>Psicologo</h3>
                <v-list two-line>
                    <v-list-item-group v-model="selected_psicologo" active-class="pink--text">
                        <template v-for="(item, index) in items">
                            <v-list-item v-if="item.cargo == 'Psicologo' && !item.guardar" :key="item.title"
                                @click="addequipo(item, index)">
                                <template v-slot:default="{ active }">
                                    <v-list-item-content>
                                        <v-list-item-title v-text="item.nombre"></v-list-item-title>

                                        <v-list-item-subtitle class="text--primary"
                                            v-text="item.headline"></v-list-item-subtitle>

                                        <v-list-item-subtitle v-text="item.ap_paterno"></v-list-item-subtitle>
                                    </v-list-item-content>

                                    <v-list-item-action>
                                        <v-list-item-action-text v-text="item.action"></v-list-item-action-text>

                                        <v-icon v-if="!(item.equipo >= 0)" color="grey lighten-1">
                                            mdi-star-outline
                                        </v-icon>

                                        <v-icon v-else color="yellow darken-3">
                                            {{ item.equipo + 1 }}
                                        </v-icon>
                                    </v-list-item-action>
                                </template>
                            </v-list-item>

                            <v-divider v-if="index < items.length - 1" :key="index"></v-divider>
                        </template>
                    </v-list-item-group>
                </v-list>



            </v-col>

            <v-col cols="4">
                <h3>Trabajo Social</h3>
                <v-list two-line>
                    <v-list-item-group v-model="selected_trabajo" active-class="pink--text">
                        <template v-if="item.cargo == 'Trabajo Social' && !item.guardar" v-for="(item, index) in items">
                            <v-list-item :key="item.title" ref="trabajo" @click="addequipo(item, index)">
                                <template v-slot:default="{ active }">
                                    <v-list-item-content>
                                        <v-list-item-title v-text="item.nombre"></v-list-item-title>

                                        <v-list-item-subtitle class="text--primary"
                                            v-text="item.headline"></v-list-item-subtitle>

                                        <v-list-item-subtitle v-text="item.ap_paterno"></v-list-item-subtitle>
                                    </v-list-item-content>

                                    <v-list-item-action>
                                        <v-list-item-action-text v-text="item.action"></v-list-item-action-text>

                                        <v-icon v-if="!(item.equipo >= 0)" color="grey lighten-1">
                                            mdi-star-outline
                                        </v-icon>

                                        <v-icon v-else color="yellow darken-3">
                                            {{ item.equipo + 1 }}
                                        </v-icon>
                                    </v-list-item-action>
                                </template>
                            </v-list-item>

                            <v-divider v-if="index < items.length - 1" :key="index"></v-divider>
                        </template>
                    </v-list-item-group>
                </v-list>



            </v-col>

        </v-row>

    </AppLayout>

</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import axios from 'axios'
import moment from 'moment'
const day1 = new Date().getFullYear() + '-' + ("0" + (new Date().getMonth() + 1)).slice(-2) + '-' + ("0" + new Date().getDate()).slice(-2)

export default {
    props: {
        dialog: Boolean,
        mensaje: Object,
        datos: Object,
    },
    created() {
        this.pedir_datos();
    },
    data: () => ({
        selected_medico: '',
        selected_psicologo: '',
        selected_trabajo: '',
        selected: [2],
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
        equipo: [
            {
                equipo: 'Equipo 1',
                lista: [
                ]
            },
            {
                equipo: 'Equipo 2',
                lista: [

                ]
            },
            {
                equipo: 'Equipo 3',
                lista: [

                ]
            },
        ],
        selected_equipo: '',
        selected_lista: []

    }),
    mounted() {
        //console.log('sssss', this.item);
    },
    components: {
        AppLayout
    },
    methods: {
        close() {
            //this.dialog = false
            //console.log(this.date);
            this.$emit('respuesta', false)
        },
        aceptar() {
            //this.dialog = false
            //console.log(this.date);
            this.$emit('respuesta', true)
        },
        async pedir_datos() {
            console.log("datops");
            try {
                var res = await axios({
                    method: 'get',
                    url: `/${process.env.MIX_CARPETA}/` + "lista_personal",
                }).then(
                    (response) => {
                        console.log(response);
                        this.items = response.data
                        console.log(response.data)

                    }, (error) => {
                        console.log(error);
                    }
                );
            } catch (err) {
                console.log("err->", err.response.data)
                return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
            }

        },
        selected_delete(item){
            console.log(item);
            item.guardar=false
            item.equipo=-1
        },
        seleccion_equipo() {
            //console.log(i);
            this.selected_medico = ''
            this.selected_psicologo = ''
            this.selected_trabajo = ''


        },
        addequipo(item, index) {
            console.log(this.selected_equipo);
            console.log('-->', index);
            if (this.selected_equipo == 'undefined' || this.selected_equipo === '') {
                this.selected_medico = ''
                this.selected_psicologo = ''
                this.selected_trabajo = ''
                return

            }
            let findx = this.items.findIndex(o => o.cargo === item.cargo && o.equipo == this.selected_equipo)

            if (item.equipo >= 0) {
                item.equipo = -1
                item.guardar = false

            } else {
                if (findx >= 0) {
                    this.items[findx].equipo = -1
                    this.items[findx].guardar = false
                }
                item.equipo = this.selected_equipo
                item.guardar = true
            }
            return
            //</let findx = this.equipo[this.selected_equipo].lista.findIndex(o => o.ci === item.ci)>
            if (typeof this.equipo[this.selected_equipo] == 'undefined') return
            if (findx > -1) {
                this.equipo[this.selected_equipo].lista.splice(findx, 1)

                //this.equipo[this.selected_equipo].lista.push(item)
                return

            }
            let findy = this.equipo[this.selected_equipo].lista.findIndex(o => o.cargo === item.cargo)
            if (findy > -1) {
                this.equipo[this.selected_equipo].lista.splice(findy, 1)

            }
            this.equipo[this.selected_equipo].lista.push(item)
            //this.equipo[this.selected_equ,,ipo].

        },
        verificar(active, item) {
            return

        }
    },
}
</script>

