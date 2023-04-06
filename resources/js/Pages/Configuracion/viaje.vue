
<template>
    <v-card>
        {{ datos }}
        <v-form ref="seleccion_equipo">
            <v-row no-gutters>
                <v-col>
                    <v-select v-model="selectequipo" persistent-placeholder placeholder="No se tiene datos" :items="viejos"
                        :rules="selecionRules" label="Seleccione Municipio" required>
                    </v-select>
                    {{ datos }}
                <!--<v-btn color="success" v-if="!getvalores(selectedEvent.fichas, 'id_designado')"
                            @click="save_atender">
                            Guardar
                                </v-btn>-->

                </v-col>

            </v-row>

        </v-form>
        <!--{{ datos }}-->
    </v-card>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import axios from 'axios'
import moment from 'moment'
const day1 = new Date().getFullYear() + '-' + ("0" + (new Date().getMonth() + 1)).slice(-2) + '-' + ("0" + new Date().getDate()).slice(-2)

export default {
    props: {
        //dialog: Boolean,
        //mensaje: Object,
        datos: Object,
        //equipo: Array,
        //cargos: Array,
    },
    created() {
        console.log('crete');
        this.pedir_datos();
    },
    data: () => ({
        selectequipo: '',
        viejos: [],
        integrantes: [],
        selecionRules: [
            v => !!v || "Se requiere seleccion"],
        encabezado: [
            {
                text: "Nombres",
                align: "start",
                value: "nombres",
            },
            {
                text: "Apellido Paterno",
                value: "ap_paterno",
            },
            {
                text: "Apellido Materno",
                value: "ap_materno",
            },
            {
                text: "Cargo",
                value: "cargo",
            },

        ],


    }),
    mounted() {
        //console.log('sssss', this.item);
        /*if(this.cargos.length > 0 ){
            for (const key in this.cargos) {
                if (Object.hasOwnProperty.call(this.cargos, key)) {
                    //const element = this.cargos[key];
                    this.caloriesList.push(this.cargos[key])        
                }
            }
            
        }*/

    },
    components: {
        AppLayout
    },
    computed: {

    },
    methods: {
        async pedir_datos() {
            console.log('opopopop');
            console.log(this.datos)
            var res = await axios({
                method: 'get',
                url: `/${process.env.MIX_CARPETA}/viaje/21312`,
                
                }).then(
                    (response) => {
                        //var r = response.data.seleccion
                        console.log('viejo--------------------')
                        console.log(response.data);
                        /*this.integrantes = response.data
                        this.viejos.push(this.datos.nombre_equipo)
                        this.selectequipo = this.datos.nombre_equipo
                        */

                    }
                ).catch(err => {
                    console.log(err)

                });
        },
        get_nombre_equipo($value) {
            /*console.log("------");
            console.log($value)*/
            return $value.nombre_equipo
        },
        select_nombre_equipo($value) {
            /*console.log("--slect ----");
            console.log($value)*/
            return $value
        },
    },
}
</script>

