<template>
    <AppLayout>
        <v-container :dense="true" class="teal lighten-3" align="center" justify="center" id="section-to-print">
            <v-row class="pa-0 ma-0">
                <v-col class="pa-0 ma-0" outlined tile align="center" cols="2" justify="center">
                    <img height="80" width="100" contain src="assets/logo-sedes-lapaz.png">
                    </img>
                </v-col>
                <v-col class="pa-0 ma-0" align="center" justify="center" cols="8" style="font-size: 10px !important;">
                    <v-card>
                        <p class="subtitle-2 pa-0 ma-0">Gobierno Autónomo Departamental de La Paz</p>
                        <p class="subtitle-3 pa-0 ma-0">SERVICIO DEPARTAMENTAL DE SALUD
                            UNIDAD DE TRATAMIENTO, REHABILITACION, INVESTIGACION SOCIAL EN DROGODEPENDENCIAS Y
                            DISCAPACIDAD</p>
                        <p class="subtitle-3 pa-0 ma-0">UTRAID - LA PAZ</p>
                        <v-icon @click="editmagin()">mdi-pencil</v-icon>

                    </v-card>

                </v-col>
                <v-col class="pa-0 ma-0" align="center" cols="2" justify="center">

                    <img height="80" width="100" contain src="assets/GOBIERNO.png">
                    <v-icon @click="editmagin()">mdi-pencil</v-icon>
                    </img>

                </v-col>

            </v-row>
            <v-row class="pa-0 ma-0">

                <v-col class="pa-0 ma-0" cols="4" align="center" justify="center" style="font-size: 20px !important;">
                    <p class="pa-0 ma-0"> Codigo: {{ cita.id }}</p>
                </v-col>

                <v-col cols="4" class="pa-0 ma-0" align="center" justify="center" style="font-size: 10px !important;">
                    <p class="pa-0 ma-0">PROGRAMACION PARA EVALUACION DE DISCAPACIDAD</p>
                </v-col>

                <v-col cols="4" class="pa-0 ma-0" align="center" justify="center" style="font-size: 10px !important;">
                    <p class="pa-0 ma-0">PROGRAMACION PARA EVALUACION DE DISCAPACIDAD</p>
                </v-col>


            </v-row>
            <v-row class="pa-1 ma-0">
                <v-col class="pa-0 ma-0" outlined align="center" justify="center">
                    <v-card class="subtitle-1">
                        Nombre Completo: {{ cita.nombres }} {{ cita.ap_paterno }} {{ cita.ap_materno }}

                    </v-card>
                </v-col>

            </v-row>
            <v-row class="pa-1 ma-0">
                <v-col class="pa-0 ma-0" cols="8" outlined tile align="center" justify="center">
                    <v-card>
                        Fecha de evaluacion: {{ fechaTexto(cita.fecha) }}

                    </v-card>
                </v-col>
                <v-col class="pa-0 ma-0" cols="4" outlined tile align="center" justify="center">
                    <v-card>
                        Hora: {{ horaTexto(cita.hora_inicio) }}

                    </v-card>
                </v-col>

            </v-row>
            <v-row class="pa-0 ma-0">

                <v-col class="pa-1 ma-1" cols="12" outlined tile align="center" justify="center">
                    <v-card>
                        Lugar: {{ getlugar(cita) }}

                    </v-card>
                </v-col>
            </v-row>
            <v-row :dense="true" class="pa-0 ma-0">
                <v-col :dense="true" class="pa-0 ma-0" align="center" justify="center">

                    <v-textarea :dense="true" class="pa-0 ma-0" label="Observaciones" auto-grow outlined rows="1">
                    </v-textarea>
                </v-col>
            </v-row>
            <v-row :dense="true" class="pa-0 ma-0">
                <v-col :dense="true" class="pa-0 ma-0" outlined tile justify="center" style="font-size: 18px !important;">

                    <v-textarea v-show="editrequisito" v-model="requisitos" label="Requisitos" auto-grow
                        outlined></v-textarea>
                    <v-card :dense="true" class="pa-0 ma-0" v-show="!editrequisito">
                        <v-card-text :dense="true" style="white-space: pre-line; font-size: 1.2rem"
                            v-html="textoConSaltosDeLinea()" class="pa-0 ma-0">
                            {{ this.boleta.requisitos }}
                        </v-card-text>
                    </v-card>
                    <v-icon @click="editmagin()">mdi-pencil</v-icon>
                </v-col>
            </v-row>
            <!-- This image from <v-img> will not be visible on print -->

            <!-- This normal <img> would work properly on print -->
        </v-container>
        <v-container>
            <v-row>
                <v-col class="pa-2" outlined tile align="center" justify="center">
                    <v-btn @click='print' align="center" justify="center">IMPRIMIR
                    </v-btn>
                </v-col>
                <v-col class="pa-2" outlined tile align="center" justify="center">
                    <v-btn @click='close' align="center" justify="center">CERRAR
                    </v-btn>
                </v-col>
            </v-row>

        </v-container>

    </AppLayout>
</template>
    
<script>
import moment from 'moment'
import AppLayout from '@/Layouts/AppLayout'
//import AppLayout from '../../Layouts/AppLayout.vue';

export default {
    components: {
        AppLayout
    },
    data() {
        return {
            drawer: null,
            items: [{
                title: 'Home',
                icon: 'mdi-view-dashboard'
            },
            {
                title: 'About',
                icon: 'mdi-forum'
            },
            ],
            cita: {
                nombres: '',
                ap_paterno: '',
                ap_materno: '',
                facha: '',
                hora_inicio: '',
                lugar: '',
            },
            boleta: {


            },
            requisitos: '',
            editrequisito: false,
        }
    },
    created() {
        //this.cita = JSON.parse(localStorage.getItem('cita'))
        //this.$store.dispatch('')
        //const usuarioString = localStorage.getItem("usuario");
        //this.cita = JSON.parse(usuarioString);
    },
    destroyed() {
        //localStorage.setItem("usuario",  JSON.stringify(this.selectedEvent.fichas));
        //localStorage.removeItem('usuario');
    },
    mounted() {
        // agregar evento beforeunload al objeto window
        window.addEventListener('beforeunload', () => {
            // eliminar el componente de la instancia de Vue
            this.$destroy();
        });
    },
    components: {
        AppLayout
    },
    methods:
    {
        print() {
            print()
        },
        fechaTexto(x) {
            moment.locale('es');
            const fecha = moment(x);

            // Obtén la fecha en formato de texto
            return fecha.format('dddd, D [de] MMMM [de] YYYY');
        },
        horaTexto(x) {
            const hora = moment(x, 'HH:mm:ss').format('hh:mm ');

            if (moment(hora, 'hh:mm ').isBefore(moment('12:00', 'hh:mm '))) {
                return `${hora} mañana`;
            } else {
                return `${hora} tarde`;
            }
        },
        close() {
            //destroyed()
            window.close();
        },
        empty(variable) {
            if (variable === null || variable === undefined || isNaN(variable) ||
                variable === '' || (Array.isArray(variable) && variable.length === 0)) {
                return true;
            } else {
                return false;
            }
        },
        getlugar(x) {
            console.log(this.cita.id_municipio);
            if (this.empty(this.cita.id_municipio)) return this.cita.direccion
            return this.cita.municipio
        },
        async editmagin() {
            this.editrequisito = !this.editrequisito
            if (!this.editrequisito) {
                var res = await axios({
                    method: "post",
                    url:
                        `/${process.env.MIX_CARPETA}/boleta`,
                    data: {
                        boleta: this.boleta
                    },
                }).then(
                    (response) => {
                        console.log(response);
                        this.items = response.data
                        /*if (response["data"]["mensaje"] == "SQLSTATE[23505]:") {
                          //let rep = response['data']['persona']
                          this.msm_existe = true;
                          this.paciente_existen = response["data"]["persona"];
                        }*/
                    },).catch((error) => {
                        //console.log(error.response.data.mensaje);

                    });
            }
        },
        textoConSaltosDeLinea() {
            /*console.log('-------');
            console.log(this.requisitos);*/

            this.boleta.requisitos = structuredClone(this.requisitos)
            if (!this.empty(this.requisitos)) {

                this.boleta.requisitos = structuredClone(this.requisitos)

                console.log(this.requisitos);
                console.log('boleta');
                console.log(this.boleta)
                return this.boleta.requisitos.replace(/\n/g, '<br>');
            }
            return this.requisitos
        },

    }

}
</script>
    