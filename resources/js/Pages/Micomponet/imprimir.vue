<template>
    <v-card>
        <v-container class="teal lighten-3" align="center" justify="center" id="section-to-print">
            <v-row class="pa-0 ma-0">
                <v-col class="pa-0 ma-0 d-flex align-center justify-center" outlined tile cols="2">
                    <img height="80" width="100" contain src="assets/logo-sedes-lapaz.png" />
                </v-col>
                <v-col class="pa-0 ma-0 tamano-letra" align="center" cols="8" style="font-size: 10px !important;">
                    <v-card>
                        <p class="subtitle-2 pa-0 ma-0">Gobierno Autónomo Departamental de La Paz</p>
                        <p class="subtitle-3 pa-0 ma-0">SERVICIO DEPARTAMENTAL DE SALUD
                            UNIDAD DE TRATAMIENTO, REHABILITACION, INVESTIGACION SOCIAL EN DROGODEPENDENCIAS Y
                            DISCAPACIDAD</p>
                        <p class="subtitle-3 pa-0 ma-0">UTRAID - LA PAZ</p>
                    </v-card>
                </v-col>
                <v-col class="pa-0 ma-0 d-flex align-center justify-center" cols="2">
                    <img height="80" width="100" contain src="assets/GOBIERNO.png" />
                </v-col>
            </v-row>
            <v-row class="pa-0 ma-0">
                <v-col class="pa-1 ma-0 " cols="4" align="center" justify="center">
                    <p class="pa-1 ma-0 tamano-codigo"> Codigo: {{ cita.id }}</p>
                </v-col>

                <v-col class="pa-1 ma-0" cols="4" align="center" justify="center">
                    <p class="pa-1 ma-0 tamano-letra">PROGRAMACION PARA EVALUACION DE DISCAPACIDAD</p>
                </v-col>

                <v-col class="pa-1 ma-0 " cols="4" align="center" justify="center">
                    <div class="centered-select">
                        <v-select v-model="selectedOption" :items="options" label="Selecciona una opción"
                            class="text-center-select text-h6   " :menu-props="{ auto: true }" hide-details dense
                            solo></v-select>
                    </div>
                    <!--<p class="pa-1 ma-0 tamano-codigo"> NUEVO </p>-->
                </v-col>
            </v-row>
            <v-row class="pa-2 ma-0">
                <v-col class="pa-0 ma-0" outlined align="center" justify="center">
                    <v-card class="subtitle-1">
                        Nombre Completo: {{ cita.nombres }} {{ cita.ap_paterno }} {{ cita.ap_materno }}

                    </v-card>
                </v-col>

            </v-row>
            <v-row class="pa-2 ma-0">
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
            <v-row class="pa-2 ma-0">

                <v-col class="pa-0 ma-0" cols="12" outlined tile align="center" justify="center">
                    <v-card>
                        Lugar: {{ getlugar(cita) }}

                    </v-card>
                </v-col>
            </v-row>
            <v-row class="pa-2 ma-0">
                <v-col class="pa-2 ma-0" align="center" justify="center">

                    <v-textarea class="pa-0 ma-0" label="Observaciones" auto-grow outlined hide-details rows="1">
                    </v-textarea>
                </v-col>
            </v-row>
            <v-row class="pa-0 ma-0">
                <v-col class="pa-0 ma-0" outlined tile justify="center" style="font-size: 18px !important;">
                    <v-card class="pa-0 ma-0">
                        <v-card-text :dense="true" style="white-space: pre-line; font-size: 1rem"
                            v-html="textoConSaltosDeLinea()" class="pa-0 ma-0">
                            {{ this.requisitos }}
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
            <!-- This image from <v-img> will not be visible on print -->

            <!-- This normal <img> would work properly on print -->
        </v-container>
        <v-container>
            <v-row>
                <v-col class="pa-2" outlined tile align="center" justify="center" colo>
                    <v-btn @click='print' align="center" justify="center" color="primary">IMPRIMIR 
                        <v-icon>mdi-printer</v-icon>
                    </v-btn>
                </v-col>
            </v-row>
        </v-container>
    </v-card>
</template>
<style scoped>
.tamano-letra {
    font-size: 9px !important;
    /* O el tamaño que desees */
}

.tamano-codigo {
    font-size: 20px !important;
    /* O el tamaño que desees */
}

.text-center-select .v-input__control {
    text-align: center;
}

.centered-select {
    display: flex;
    justify-content: center;
    align-items: center;
}

.centered-select .v-select__selection {
    width: auto;
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
}
</style>
<script>
import moment from 'moment'
export default {
    data() {
        return {
            selectedOption: null,
            options: ["Nuevo", "Recalificado"],
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
            requisitos: '',
            boleta: {}

        }
    },
    created() {
        //this.cita = JSON.parse(localStorage.getItem('cita'))
        //this.$store.dispatch('')
        
        const usuarioString = localStorage.getItem("usuario");
        this.cita = JSON.parse(usuarioString);
        console.log('console sss');
        
        console.log(this.cita);
        console.log(this.cita.id_persona);
        console.log(this.isEmpty(this.cita.id_persona));
        if (this.isEmpty(this.cita.id_persona)) {
            this.selectedOption = 'Nuevo';
        } else {
            this.selectedOption = 'Recalificado';
        }
        this.cambiar_form()

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
        isEmpty(variable) {
            if (variable === null || variable === undefined || 
                variable === '' || (Array.isArray(variable) && variable.length === 0)) {
                return true;
            } else {
                return false;
            }
        },
        textoConSaltosDeLinea() {

            //this.boleta.requisitos = structuredClone(this.requisitos)
            return this.requisitos.replace(/\n/g, '<br>');

            if (!this.empty(this.requisitos)) {

                this.boleta.requisitos = this.requisitos
                console.log(this.requisitos);
                console.log('boleta');
                console.log(this.boleta)

            }
            return this.requisitos
        },
        async cambiar_form() {
            var res = await axios({
                method: "get",
                url:
                    `/${process.env.MIX_CARPETA}/boleta/` + '01',
            }).then(
                (response) => {

                    this.boleta = structuredClone(response.data[0])
                    this.requisitos = this.boleta.requisitos

                    //this.boleta = response['data']

                    /*if (response["data"]["mensaje"] == "SQLSTATE[23505]:") {
                      //let rep = response['data']['persona']
                      this.msm_existe = true;
                      this.paciente_existen = response["data"]["persona"];
                    }*/
                },).catch((error) => {
                    //console.log(error.response.data.mensaje);

                });

        },

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
        }


    }

}
</script>
    