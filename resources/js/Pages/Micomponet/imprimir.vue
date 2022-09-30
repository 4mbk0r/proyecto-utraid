<template>
    <v-app>
        <v-content>
            <v-container class="green lighten-5" align="center" justify="center" id="section-to-print">
                <v-row class="pa-0 ma-0">
                    <v-col class="pa-0 ma-0" outlined tile align="center" cols="2" justify="center">
                        <v-card>
                            <v-img height="100" width="100" contain src="assets/logo-sedes-lapaz.png"></v-img>
                        </v-card>

                    </v-col>
                    <v-col class="pa-0 ma-0" align="center" justify="center" cols="8"
                        style="font-size: 10px !important;">
                        <v-card>
                            <p class="subtitle-2 pa-0 ma-0">Gobierno Autónomo Departamental de La Paz</p>
                            <p class="subtitle-2 pa-0 ma-0">SERVICIO DEPARTAMENTAL DE SALUD
                                UNIDAD DE TRATAMIENTO, REHABILITACION, INVESTIGACION SOCIAL EN DROGODEPENDENCIAS Y
                                DISCAPACIDAD</p>
                            <p class="subtitle-2 pa-0 ma-0">UTRAID - LA PAZ</p>
                        </v-card>

                    </v-col>
                    <v-col class="pa-0 ma-0" align="center" cols="2" justify="center">
                        <v-card>
                            <v-img height="100" width="100" contain src="assets/GOBIERNO.png"></v-img>
                        </v-card>
                    </v-col>

                </v-row>
                <v-row class="pa-0 ma-0">

                    <v-col class="pa-1 ma-1" align="center" justify="center" style="font-size: 10px !important;">
                        <p class="pa-0 ma-0">PROGRAMACION PARA EVALUACION DE DISCAPACIDAD</p>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col class="pa-0 ma-0" outlined tile align="center" justify="center">
                        <v-card class="subtitle-1">
                            Nombre Completo: {{ cita.nombres }} {{cita.ap_paterno}} {{cita.ap_materno}}

                        </v-card>
                    </v-col>

                </v-row>
                <v-row>
                    <v-col cols="8" outlined tile class="subtitle-1" align="center" justify="center">
                        <v-card>
                            Fecha de evaluacion: {{ cita.fecha }}

                        </v-card>
                    </v-col>
                    <v-col cols="4" outlined class="subtitle-1" tile align="center" justify="center">
                        <v-card>
                            hora: {{ cita.hora_inicio }}

                        </v-card>
                    </v-col>

                </v-row>
                <v-row class="pa-0 ma-0">

                    <v-col class="pa-2" outlined tile align="center" justify="center">
                        <v-card>
                            Lugar: {{ cita.lugar }}

                        </v-card>
                    </v-col>
                </v-row>
                <v-row class="pa-0 ma-0">
                    <v-col class="pa-0 ma-0" align="center" justify="center">

                        <v-textarea class="pa-0 ma-0" label="Descripcion" auto-grow outlined rows="1">
                        </v-textarea>
                    </v-col>
                </v-row>
                <v-row class="pa-0 ma-0">
                    <v-col class="pa-0 ma-0" outlined tile justify="center" style="font-size: 18px !important;">
                        <v-card class="pa-0 ma-0">
                            <p class="pa-0 ma-0" style="font-size: 14px !important;">
                                LA FALTA DE DOCUMENTACION Y EL RETRASO DE 10 MINUTOS DARA LUGAR A REPROGRAMAR<br>
                                PRESENTARSE 10 MINUTOS ANTES DE LA HORA DE PROGRAMACION<br>
                                DE NO ASISTIR INFORMAR CON 48 HORAS DE ANTICIPACION TELEFONO 2412391 UTRAID<br>
                                TRAER: 2 FOTOCOPIAS C.I., 1 COPIA CARNET DISCAPACIDAD, INFORME MEDICO ORIGINAL, CROQUIS
                                DOMICILIO<br>
                                PRESENTAR SU DOCUMENTACION EN FOLDER AMARILLO TAMAÑO OFICIO CON FASTENER<br>
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
            </v-row>
        </v-content>

    </v-app>
</template>
    
<script>
import AppLayout from '@/Layouts/AppLayout'
import Welcome from '@/Jetstream/Welcome'
import Barrasu from '@/Pages/Micomponet/Barrasu'

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
            cita: {},
        }
    },
    created() {
        this.cita = JSON.parse(localStorage.getItem('cita'))
        console.log(typeof this.cita)
    },
    components: {
        AppLayout,
        Welcome,
        Barrasu,
    },
    methods:
    {
        print() {
            print()
        }

    }

}
</script>
    