<template>
    <v-dialog v-if="dialog" v-model="dialog" persistent>


        <v-stepper v-model="paso">
            <v-stepper-header>
                <v-stepper-step  step="1">
                    Paso 1.
                </v-stepper-step>

                <v-divider></v-divider>

                <v-stepper-step  step="2">
                    Paso 2.
                </v-stepper-step>

                <v-divider></v-divider>

                <v-stepper-step step="3">
                    Paso 3.
                </v-stepper-step>
                <v-divider></v-divider>
                <v-stepper-step step="4">
                    Paso 4.
                </v-stepper-step>
            </v-stepper-header>

            <v-stepper-items>
                <v-stepper-content step="1">
                    <v-card>
                        <v-card-title>
                            <span class="text-h5">Fecha de vigencia</span>
                        </v-card-title>
                        <v-card-text>
                            <v-form ref="calendario_linar">
                                <v-row>
                                    <v-col cols="12">
                                        <v-menu v-model="menu1" :close-on-content-click="false" max-width="290">
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-text-field :value="computedDateFormattedMomentjs" clearable
                                                    label="Fecha de Vigencia" readonly v-bind="attrs" v-on="on"
                                                    @click:clear="date = null"
                                                    :rules="[v => !!v || 'Debe de seleccionar fecha']"></v-text-field>
                                            </template>
                                            <v-date-picker locale="es-ES" v-model="date" :min="minfecha()"
                                                :max="maxfecha()" @change="menu1 = false">
                                            </v-date-picker>
                                        </v-menu>
                                    </v-col>
                                </v-row>
                            </v-form>
                        </v-card-text>
                        <v-card-actions>
                            <!--<v-spacer></v-spacer>
                            <v-btn color="blue darken-1" text @click="close">
                                Cerrar
                            </v-btn>
                            <v-btn color="blue darken-1" text @click="crear">
                                Crear
                            </v-btn>-->
                        </v-card-actions>
                    </v-card>
                    <v-btn color="primary" @click="paso2">
                        Continue
                    </v-btn>
                    <v-divider row></v-divider>
                    <v-btn text @click="cancelar">
                        Cancelar
                    </v-btn>
                </v-stepper-content>

                <v-stepper-content step="2">
                    <v-card class="mb-12" color="grey lighten-1" height="200px">
                        <v-form ref="form_configuracion">
                            <v-col cols="12">
                                <v-text-field v-model="configuracion.descripcion" label="Descripcion"
                                    :rules="[v => !!v || 'Debe ingresar una descripcion']"
                                    :error-messages="errors_descripcion" @input="limpiar_errors">
                                </v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field v-model="color" hide-details class="ma-0 pa-0" solo>
                                    <template v-slot:append>
                                        <v-menu v-model="menu" top nudge-bottom="105" nudge-left="16"
                                            :close-on-content-click="false">
                                            <template v-slot:activator="{ on }">
                                                <div :style="swatchStyle" v-on="on" />
                                            </template>
                                            <v-card>
                                                <v-card-text class="pa-0">
                                                    <v-color-picker v-model="color" flat />
                                                </v-card-text>
                                            </v-card>
                                        </v-menu>
                                    </template>
                                </v-text-field>
                            </v-col>
                        </v-form>
                    </v-card>

                    <v-btn color="primary" @click="paso3">
                        Continue
                    </v-btn>
                    <v-divider row></v-divider>
                    <v-btn text @click="cancelar">
                        Cancelar
                    </v-btn>
                </v-stepper-content>

                <v-stepper-content step="3">
                    <salas v-if="paso=='3'" ref="sala" :configuracion="item"></salas>

                    <v-btn color="primary" @click="paso4">
                        Continue
                    </v-btn>
                    <v-divider row></v-divider>
                    <v-btn text @click="cancelar">
                        Cancelar
                    </v-btn>
                </v-stepper-content>
            </v-stepper-items>
            <v-stepper-content step="4">
                    <equipo v-if="paso=='4'" :equipo="equipo" ref="equipo" ></equipo>

                    <v-btn color="primary" @click="paso5">
                        Continue
                    </v-btn>
                    <v-divider row></v-divider>
                    <v-btn text @click="cancelar">
                        Cancelar
                    </v-btn>
                </v-stepper-content>
            </v-stepper-items>
        </v-stepper>


    </v-dialog>



</template>

<script>
import axios from 'axios'
import moment from 'moment'

import Salas from '@/Pages/Micomponet/Sala'
import Equipo from '@/Pages/Configuracion/Equipo'

const day1 = new Date().getFullYear() + '-' + ("0" + (new Date().getMonth() + 1)).slice(-2) + '-' + ("0" + new Date().getDate()).slice(-2)

export default {
    components: {
        Salas,
        Equipo,
    },
    props: {
        dialog: Boolean,
        item: Object
    },
    data: () => ({
        //dialog: false,
        date: '',
        menu1: false,
        configuracion: {},
        calendario: {},
        color: '#1976D2FF',
        mask: '',
        menu: false,
        paso: 1,
        alert: false,
        errors_descripcion: [],
        equipo: [],
        salas: []

    }),

    computed: {
        computedDateFormattedMomentjs() {
            moment.locale('es');
            this.calendario.fecha_inicio = this.date
            return this.date ? moment(this.date).format('dddd, MMMM Do YYYY') : ''
        },
        swatchStyle() {
            const { color, menu } = this
            return {
                backgroundColor: color,
                cursor: 'pointer',
                height: '30px',
                width: '30px',
                borderRadius: menu ? '50%' : '4px',
                transition: 'border-radius 200ms ease-in-out'
            }
        }
    },
    watch: {
        limpiar_errors() {
            this.errors_descripcion = []
        }
    },

    methods: {
        limpiar_errors() {
            this.errors_descripcion = []
        },
        close() {
            //this.dialog = false
            //console.log(this.date);
            this.$emit('cerrar', false)
        },
        async crear() {
            //this.dialog = false
            //console.log(this.date);

            try {
                this.configuracion.color = this.color
                var res = await axios({
                    method: "post",
                    url: `/${process.env.MIX_CARPETA}/calendariolineal`,
                    data: {
                        calendario: this.calendario,
                        configuracion_nueva: this.configuracion,
                        configuracion_antigua: this.item
                    },
                }).then(
                    (response) => {
                        console.log(response);
                    },
                    (error) => {
                        console.log(error);
                    }
                );
            } catch (err) {
                console.log("err->", err.response.data);
                return res
                    .status(500)
                    .send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
            }
            this.$emit('cerrar', false)
        },
        minfecha() {

            //console.log(item)
            /*moment('2010-10-20').isBefore('2010-12-31', 'year'); // false <
    
            moment('2010-10-20').isBefore('2011-01-01', 'year'); // true    <
            moment('2010-10-20').isSame('2009-12-31', 'year');  // false =
            moment('2010-10-20').isSame('2010-01-01', 'year');  // true
            moment('2010-10-20').isSame('2010-12-31', 'year');  // true
            moment('2010-10-20').isSame('2011-01-01', 'year');  // false
    
            moment('2010-10-20').isAfter('2010-01-01', 'year'); // false >
            moment('2010-10-20').isAfter('2009-12-31', 'year'); // true>*/
            //console.log(this.item);
            console.log(this.item)
            if (moment(this.$store.getters.getfecha_server).isSameOrAfter(this.item.fecha_inicio)) {
                return this.$store.getters.getfecha_server
            }
            return this.item.fecha_inicio

        },
        maxfecha() {

            //console.log(item)
            /*moment('2010-10-20').isBefore('2010-12-31', 'year'); // false <
            
            moment('2010-10-20').isBefore('2011-01-01', 'year'); // true    <
            moment('2010-10-20').isSame('2009-12-31', 'year');  // false =
            moment('2010-10-20').isSame('2010-01-01', 'year');  // true
            moment('2010-10-20').isSame('2010-12-31', 'year');  // true
            moment('2010-10-20').isSame('2011-01-01', 'year');  // false
            
            moment('2010-10-20').isAfter('2010-01-01', 'year'); // false >
            moment('2010-10-20').isAfter('2009-12-31', 'year'); // true>*/
            //console.log(this.item);
            //console.log(this.item)
            let t_year = moment(this.$store.getters.fecha_inicio).add(3, 'year')
            //console.log(t_year);
            if (t_year.isSameOrAfter(this.item.fecha_final)) {//>
                return this.item.fecha_final
            }
            return t_year.format('yyyy-mm-dd')

        },
        paso2() {
            if (this.$refs.calendario_linar.validate()) {
                this.paso += 1
            }

        },
        async paso3() {

            if (this.$refs.form_configuracion.validate()) {
                this.validar_configuracion()
                console.log("_________")
                console.log(this.item)

                
            }



        },
        paso4() {
            //this.paso+=1
            /*setTimeout(() => {
                
            }, 30);*/
            this.salas  =  structuredClone(this.$refs.sala.desserts)
            if(  !(this.salas.length > 0) ) {
                alert('no se puede crear confuguracion con salas 0')
                return
            }
            let k = 1
            this.equipo = [...Array(this.salas.length).fill(0).map(x => ({ equipo: 'Equipo '+(k++), lista: [] }))]
            this.paso+=1

            /*
            this.$refs.equipo.equipo = [{
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
            },]*/

        },
        paso5(){
            console.log("______________");
            console.log(this.$refs.equipo.equipo);
            let e = this.$refs.equipo.equipo
            
            //console.log(this.$refs.equipo.items);
            //let lista = this.$refs.equipo.items
            //this.equipo = [...Array(this.salas.length).fill(0).map(x => ({ equipo: 'Equipo '+(k++), lista: [] }))]
            //let lista = this.equipo[this.selected_equipo].lista
           
            
        },
        async validar_configuracion() {
            try {
                this.configuracion.color = this.color
                this.configuracion.institucion = '01'

                var res = await axios({
                    method: "post",
                    url: `/${process.env.MIX_CARPETA}/api/validar_configuracion`,
                    data: {
                        configuracion: this.configuracion,
                    },
                }).then(
                    (response) => {
                        //console.log('validat');
                        //console.log(response);
                        if (response.data['validar'] == false) {
                            this.errors_descripcion = ['ya existe esta descripcion']
                            return
                        }
                        
                        this.paso += 1
                        

                    },
                    (error) => {
                        console.log(error);
                        return false


                    }
                );
            } catch (err) {
                console.error("err->", err.response.data);
                return false

                return res
                    .status(500)
                    .send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
            }
        },
        cancelar(){
            this.close()
        }

    }
}
</script>
