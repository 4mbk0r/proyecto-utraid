<template>
    <v-app>
        <v-content>
            <v-container class="green lighten-5" align="center" justify="center" id="section-to-print">
                <v-row>
                    <v-col class="pa-2" outlined tile align="center" justify="center">
                        <v-card>
                            <v-img max-height="80" max-width="150" src="assets/logo-sedes-lapaz.png"></v-img>
                        </v-card>
                    </v-col>
                    <v-col align="center" justify="center" style="font-size: 10px !important;">
                        Gobierno Autónomo Departamental de La Paz<br>
                        SERVICIO DEPARTAMENTAL DE SALUD<br>
                        UNIDAD DE TRATAMIENTO, REHABILITACION, INVESTIGACION SOCIAL EN DROGODEPENDENCIAS Y DISCAPACIDAD
                        UTRAID - LA PAZ <br>

                    </v-col>
                    <v-col align="center" justify="center">
                        <v-card>
                            <v-img max-height="80" max-width="150" src="assets/logo-sedes-lapaz.png"></v-img>
                        </v-card>
                    </v-col>

                </v-row>
                <v-row>

                    <v-col align="center" justify="center" style="font-size: 10px !important;">
                        PROGRAMACION PARA EVALUACION DE DISCAPACIDAD

                    </v-col>

                </v-row>
                <v-row>
                    <v-col class="pa-2" outlined tile align="center" justify="center">
                        <v-card>
                            Nombre Completo: {{ cita.nombres }} {{cita.ap_paterno}} {{cita.ap_materno}}

                        </v-card>
                    </v-col>

                </v-row>
                <v-row>
                    <v-col class="pa-2" outlined tile align="center" justify="center">
                        <v-card>
                            Fecha de evaluacion: {{ cita.fecha }}

                        </v-card>
                    </v-col>

                </v-row>
                <v-row>
                    <v-col class="pa-2" outlined tile align="center" justify="center">
                        <v-card>
                            hora: {{ cita.hora_inicio }}

                        </v-card>
                    </v-col>
                    <v-col class="pa-2" outlined tile align="center" justify="center">
                        <v-card>
                            Lugar: {{ cita.lugar }}

                        </v-card>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col class="pa-2" outlined tile align="center" justify="center"
                        style="font-size: 18px !important;">
                        <v-card>
                            <span style="font-size: 16px !important;">
                                LA FALTA DE DOCUMENTACION Y EL RETRASO DE 10 MINUTOS DARA LUGAR A REPROGRAMAR<br>
                                PRESENTARSE 10 MINUTOS ANTES DE LA HORA DE PROGRAMACION<br>
                                DE NO ASISTIR INFORMAR CON 48 HORAS DE ANTICIPACION TELEFONO 2412391 UTRAID<br>
                                TRAER: 2 FOTOCOPIAS C.I., 1 COPIA CARNET DISCAPACIDAD, INFORME MEDICO ORIGINAL, CROQUIS
                                DOMICILIO<br>
                                PRESENTAR SU DOCUMENTACION EN FOLDER AMARILLO TAMAÑO OFICIO CON FASTENER<br>
                            </span>

                        </v-card>
                    </v-col>

                </v-row>
                <!-- This image from <v-img> will not be visible on print -->

                <!-- This normal <img> would work properly on print -->
            </v-container>
        </v-content>
        <v-btn @click='print'>print
        </v-btn>
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
    