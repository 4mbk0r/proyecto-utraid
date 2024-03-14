
<template>
    <v-card>
        <v-form ref="municipio">
            

            <v-row no-gutters>
                <v-col>
                    <v-select v-model="selectMunicipio" persistent-placeholder placeholder="No se tiene datos"
                        :items="viaje" item-text="municipio" item-value="id" :rules="selecionRules"
                        label="Seleccione Municipio" required>
                    </v-select>

                    <!--<v-btn color="success" v-if="!getvalores(selectedEvent.fichas, 'id_designado')"
                            @click="save_atender">
                            Guardar
                                </v-btn>-->

                </v-col>

            </v-row>
            <v-row>
                <v-col>
                    <v-btn @click="guardar">
                        Guardar
                    </v-btn>
                </v-col>
                <v-col>
                    <v-btn @click="eliminar">
                        Eliminar
                    </v-btn>
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
        fecha: String
        //equipo: Array,
        //cargos: Array,
    },
    created() {
        console.log('crete');
        this.pedir_datos();
    },
    data: () => ({
        selectequipo: '',
        selectMunicipio: '',
        viaje: [],
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
        horario: [],
        hhorario: [

            { text: 'Asignado', value: 'id_ficha' },

            { text: 'Hora Inicio', value: 'hora_inicio' },
            { text: 'Hora Final', value: 'hora_final' },
            // Agrega más encabezados según tus campos
        ]


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
            this.datos.fecha = this.fecha
            var res = await axios({
                method: 'get',
                url: `/${process.env.MIX_CARPETA}/viaje/` + JSON.stringify(this.datos),

            }).then(
                (response) => {
                    //var r = response.data.seleccion
                    console.log('viejo--------------------')
                    console.log(response.data);
                    this.viaje = response.data['municipios']
                    this.selectMunicipio = response.data['municipio']
                    this.horario = response.data['horarios']
                    /*this.integrantes = response.data
                    
                    tjh
                    this.viejos.push(this.datos.nombre_equipo)
                    this.selectequipo = this.datos.nombre_equipo
                    */

                }
            ).catch(err => {
                console.log(err)

            });
        },
        async guardar() {
            if (!this.$refs.municipio.validate()) return;
            this.datos.fecha = this.fecha
            var res = await axios({
                method: 'post',
                url: `/${process.env.MIX_CARPETA}/viaje`,
                data: {
                    datos: this.datos,
                    municipio: this.selectMunicipio
                }
            }).then(
                (response) => {
                    //var r = response.data.seleccion
                    /*console.log('viejo--------------------')
                    console.log(response.data);
                    this.viaje = response.data['municipios']
                    /*this.integrantes = response.data
                    
                    tjh
                    this.viejos.push(this.datos.nombre_equipo)
                    this.selectequipo = this.datos.nombre_equipo
                    */

                }
            ).catch(err => {
                console.log(err)

            });
        },
        async eliminar() {
            if (!this.$refs.municipio.validate()) return;
            this.datos.fecha = this.fecha
            var res = await axios({
                method: 'delete',
                url: `/${process.env.MIX_CARPETA}/viaje/` + JSON.stringify(this.selectMunicipio),

            }).then(
                (response) => {
                    console.log(response)
                    this.pedir_datos()
                    //var r = response.data.seleccion
                    /*console.log('viejo--------------------')
                    console.log(response.data);
                    this.viaje = response.data['municipios']
                    /*this.integrantes = response.data
                    
                    tjh
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

