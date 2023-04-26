<template>
    <v-app>

        <v-container class="teal lighten-3" align="center" justify="center" id="section-to-print">
            <v-row class="pa-0 ma-0">
                <v-col class="pa-0 ma-0" outlined tile align="center" cols="2" justify="center">
                    <img height="80" width="100" contain src="assets/logo-sedes-lapaz.png"></img>
                </v-col>
                <v-col class="pa-0 ma-0" align="center" justify="center" cols="8" style="font-size: 10px !important;">
                    <v-card>
                        <p class="subtitle-2 pa-0 ma-0">Gobierno Autónomo Departamental de La Paz</p>
                        <p class="subtitle-3 pa-0 ma-0">SERVICIO DEPARTAMENTAL DE SALUD
                            UNIDAD DE TRATAMIENTO, REHABILITACION, INVESTIGACION SOCIAL EN DROGODEPENDENCIAS Y
                            DISCAPACIDAD</p>
                        <p class="subtitle-3 pa-0 ma-0">UTRAID - LA PAZ</p>
                    </v-card>

                </v-col>
                <v-col class="pa-0 ma-0" align="center" cols="2" justify="center">

                    <img height="80" width="100" contain src="assets/GOBIERNO.png"></img>

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
            <v-row class="pa-1 ma-0">

                <v-col class="pa-0 ma-0" cols="12" outlined tile align="center" justify="center">
                    <v-card>
                        Lugar: {{ getlugar(cita) }}

                    </v-card>
                </v-col>
            </v-row>
            <v-row class="pa-0 ma-0">
                <v-col class="pa-0 ma-0" align="center" justify="center">

                    <v-textarea class="pa-0 ma-0" label="Observaciones" auto-grow outlined rows="1">
                    </v-textarea>
                </v-col>
            </v-row>
            <v-row class="pa-0 ma-0">
                <v-col class="pa-0 ma-0" outlined tile justify="center" style="font-size: 18px !important;">
                    <v-card class="pa-0 ma-0">
                        <p class="pa-0 ma-0" style="font-size: 14px !important;">
                            <v-row justify="center" class="ma-0 pa-0" no-gutters>
                                <v-col cols="auto" class="pa-0 ma-0 d-flex flex-column align-center">
                                    <v-icon>mdi-numeric-1-circle</v-icon>
                                </v-col>
                                <v-col class=" pa-0 ma-0">
                                    LA FALTA DE DOCUMENTACION Y EL RETRASO DE <v-icon
                                        color="red">mdi-numeric-10-circle-outline</v-icon> MINUTOS DARA LUGAR A
                                    REPROGRAMAR<br>

                                </v-col>
                            </v-row>
                            <v-row justify="center" class="ma-0 pa-0" no-gutters>
                                <v-col cols="auto" class="pa-0 ma-0 d-flex flex-column align-center">
                                    <v-icon>mdi-numeric-2-circle</v-icon>
                                </v-col>
                                <v-col class=" pa-0 ma-0">
                                    <v-col class="pa-0 ma-0 ">
                                        PRESENTARSE 10 MINUTOS ANTES DE LA HORA DE PROGRAMACION<br>
                                    </v-col>
                                </v-col>
                            </v-row>
                            <v-row justify="center" class="ma-0 pa-0" no-gutters>
                                <v-col cols="auto" class="pa-0 ma-0 d-flex flex-column align-center">
                                    <v-icon>mdi-numeric-3-circle</v-icon>
                                </v-col>
                                <v-col class=" pa-0 ma-0">
                                    <v-col class="pa-0 ma-0 ">
                                        DE NO ASISTIR INFORMAR CON 48 HORAS DE ANTICIPACION
                                        TELEFONO 2412391 UTRAID<v-icon>mdi-phone-incoming-outline</v-icon><br>
                                    </v-col>
                                </v-col>
                            </v-row>
                            <v-row justify="center" class="ma-0 pa-0" no-gutters>
                                <v-col cols="auto" class="pa-0 ma-0 d-flex flex-column align-center">
                                    <v-icon>mdi-numeric-4-circle</v-icon>
                                </v-col>
                                <v-col class=" pa-0 ma-0">
                                    <v-col class="pa-0 ma-0 ">
                                        <v-icon>mdi-card-account-details-outline</v-icon>, 1 COPIA CARNET DISCAPACIDAD
                                        <v-icon>mdi-card-account-details-star</v-icon>, INFORME MEDICO ORIGINAL
                                        <v-icon>mdi-text-box-check-outline</v-icon>, CROQUIS
                                        DOMICILIO <v-icon>mdi-home-map-marker</v-icon></span>
                                    </v-col>
                                </v-col>
                            </v-row>
                            <v-row justify="center" class="ma-0 pa-0" no-gutters>
                                <v-col cols="auto" class="pa-0 ma-0 d-flex flex-column align-center">
                                    <v-icon>mdi-numeric-5-circle</v-icon>
                                </v-col>
                                <v-col class=" pa-0 ma-0">
                                    <v-col class="pa-0 ma-0 ">
                                        PRESENTAR SU DOCUMENTACION EN FOLDER AMARILLO TAMAÑO OFICIO
                                        CON FASTENER <v-icon color="yellow darken-1">mdi-folder</v-icon><br>
                                    </v-col>
                                </v-col>
                            </v-row>

                        </p>

                    </v-card>
                </v-col>

            </v-row>
            <!-- This image from <v-img> will not be visible on print -->

            <!-- This normal <img> would work properly on print -->
        </v-container>
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


    </v-app>
</template>
    
<script>
import moment from 'moment'
export default {
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


        }
    },
    created() {
        //this.cita = JSON.parse(localStorage.getItem('cita'))
        //this.$store.dispatch('')
        const usuarioString = localStorage.getItem("usuario");
        this.cita = JSON.parse(usuarioString);
    },
    destroyed() {
        //localStorage.setItem("usuario",  JSON.stringify(this.selectedEvent.fichas));
        localStorage.removeItem('usuario');
    },
    mounted() {
        // agregar evento beforeunload al objeto window
        window.addEventListener('beforeunload', () => {
            // eliminar el componente de la instancia de Vue
            this.$destroy();
        });
    },
    components: {
        
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
        getlugar(x){
            console.log(this.cita.id_municipio);
            if(this.empty(this.cita.id_municipio) ) return this.cita.direccion
            return this.cita.municipio
        }


    }

}
</script>
    