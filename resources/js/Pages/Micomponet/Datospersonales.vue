<template>
    <v-card>
        <v-dialog v-model="dialog" fullscreen hide-overlay class="fill-height" transition="dialog-bottom-transition">
            <v-toolbar>
                <v-btn icon @click="close">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
                <v-toolbar-title>datos</v-toolbar-title>
                <v-spacer></v-spacer>
            </v-toolbar>

            <v-card>
                <v-tabs v-model="datos_informacion" centered>
                    <v-tab v-for="n in 3" :key="n">
                        Item {{ n }}
                    </v-tab>
                </v-tabs>

                <v-tabs-items v-model="datos_informacion" touchless>
                    <v-tab-item>
                        <v-card flat>

                            <v-form v-model="valid">
                                <v-container>
                                    <v-row no-gutters>
                                        <v-col cols="12" sm="6">
                                            <v-text-field v-model="paciente.ci" :rules="nombreRules" @change="valorar"
                                                label="Cedula de Identidad" required>
                                            </v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="6">
                                            <v-select v-model="paciente.expedido" :rules="nombreRules"
                                                persistent-placeholder placeholder="No se tiene datos"
                                                :items="departamentos" label="Expedido">
                                            </v-select>
                                        </v-col>
                                    </v-row>
                                    <v-row no-gutters>
                                        <v-col cols="12" sm="4" md="4">
                                            <v-text-field v-model="paciente.nombres" :rules="nombreRules" label="Nombre"
                                                @input="v => { paciente.nombres = v.toUpperCase() }" required>
                                            </v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="4" md="4">
                                            <v-text-field v-model="paciente.ap_paterno" :rules="nombreRules"
                                                @input="v => { paciente.ap_paterno = v.toUpperCase() }"
                                                label="Apellido Paterno" required>
                                            </v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="4" md="4">
                                            <v-text-field v-model="paciente.ap_materno" :rules="nombreRules"
                                                @input="v => { paciente.ap_materno = v.toUpperCase() }"
                                                label="Apellido Materno" required>
                                            </v-text-field>
                                        </v-col>
                                    </v-row>
                                    <v-row no-gutters>
                                        <v-col cols="12" sm="4" md="4">
                                            <v-text-field v-model="paciente.correo" label="Correo">
                                            </v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="4" md="4">
                                            <v-text-field v-model="paciente.celular" label="Numero Celular">
                                            </v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="4" md="4">
                                            <v-text-field v-model="paciente.direccion" label="Direccion">
                                            </v-text-field>
                                        </v-col>
                                    </v-row>
                                    <v-row no-gutters>
                                        <v-col cols="12" sm="6">
                                            <v-text-field v-model="paciente.fecha_nacimiento" :min="minFechaNac"
                                                :max="maxFechaNac" type="date" label="Fecha de nacimiento">
                                            </v-text-field>


                                        </v-col>
                                        <v-col cols="12" sm="6">
                                            <v-select v-model="paciente.sexo" persistent-placeholder
                                                placeholder="No se tiene datos" :items="ssexo" color="purple darken-3"
                                                label="Sexo" pa-0 solo>
                                            </v-select>
                                        </v-col>
                                    </v-row>
                                    <v-row no-gutters>
                                        <v-col cols="12" sm="6">
                                            <v-btn color="primary" class="mr-4" @click="cambiar_datos">
                                                Guardar Datos
                                            </v-btn>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-form>
                        </v-card>
                    </v-tab-item>
                    <v-tab-item>
                        <v-card flat>
                            <v-btn color="primary" class="mb-2" @click="v_agendar = true">
                                Agendar Nueva cita
                            </v-btn>
                            <v-data-table :headers="encabezado" :items="las_citas" :sort-by.sync="sortBy"
                                :sort-desc.sync="sortDesc" hide-default-footer disable-pagination>
                                <template v-slot:item="i">
                                    <!-- Since v-slot:item overrides how each row is rendered, I rebuild the row starting from <tr>. This allows me to add a class to <tr> based on any condition I want (in this case, the calorie count) -->
                                    <tr :color="{
                                        'primary': i.item.fecha > fechacitaMin,
                                        'secondary': i.item.fecha < fechacitaMin
                                    }">
                                        <td>{{ i.item.fecha }}</td>
                                        <td>{{ i.item.hora_inicio }}</td>
                                        <td>{{ i.item.ci_doctor }}</td>
                                        <td>{{ i.item.equipo }}</td>
                                        <td>{{ i.item.se_presento }}</td>
                                        <td>{{ i.item.observacion }}</td>
                                    </tr>
                                </template>
                            </v-data-table>
                        </v-card>
                    </v-tab-item>
                    <v-tab-item>

                    </v-tab-item>
                </v-tabs-items>
            </v-card>
        </v-dialog>
        <v-dialog v-model="v_agendar" max-width="600">
            <v-toolbar dark color="#1CA698">
                <v-btn icon dark @click="v_agendar = false">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
                <v-toolbar-title>Agendar Cita</v-toolbar-title>
                <v-spacer></v-spacer>
            </v-toolbar>
            <template>
                <v-card>

                    <v-form ref="form_cita" v-model="validacion">

                        <v-container>
                            <v-row no-gutters>
                                <v-col cols="12" sm="4" md="4">

                                    <v-menu v-model="menu2" :close-on-content-click="false" :nudge-right="40"
                                        transition="scale-transition" offset-y min-width="auto">

                                        <template v-slot:activator="{ on, attrs }">
                                            <!--<v-text-field v-model="date" label="Picker without buttons"
                                                        prepend-icon="mdi-calendar" readonly v-bind="attrs" v-on="on">
                                                    </v-text-field>-->
                                            <v-text-field v-model="cita_nueva.fecha" :rules="nombreRules"
                                                placeholder="Selecione fecha de cita" required
                                                prepend-icon="mdi-calendar" readonly v-bind="attrs" v-on="on">
                                            </v-text-field>
                                        </template>
                                        <v-date-picker v-model="cita_nueva.fecha" :allowed-dates="allowedDates"
                                            @input="menu2 = false" @change="change_fecha2" :min="fechacitaMin"
                                            locale="es-ES">
                                        </v-date-picker>
                                    </v-menu>
                                </v-col>
                                <v-col cols="12" sm="4" md="4">
                                    <v-select v-model="cita_nueva.equipo" :items="equipos_actuales" :rules="nombreRules"
                                        persistent-placeholder placeholder="Selecione el equipo" @change="cambioequipo"
                                        color="purple darken-3" label="Equipo" required>
                                    </v-select>
                                </v-col>
                                <v-col cols="12" sm="4" md="4">
                                    <v-select v-model="cita_nueva.hora_inicio" :items="tiempos_actuales"
                                        :rules="nombreRules" persistent-placeholder placeholder="Selecione hora de cita"
                                        color="purple darken-3" label="Hora de inicio" required>
                                    </v-select>
                                </v-col>
                            </v-row>
                            <v-row no-gutters>
                                <v-col cols="12" sm="4" md="4">

                                    <v-select v-model="cita_nueva.tipo_cita" :items="tipo_cita" color="purple darken-3"
                                        persistent-placeholder :rules="nombreRules" placeholder="Selecione tipo de cita"
                                        label="Tipo de cita">
                                    </v-select>
                                </v-col>
                                <v-col cols="12" sm="4" md="4">

                                    <v-select v-model="cita_nueva.lugar" :items="lugares" color="purple darken-3"
                                        :rules="nombreRules" persistent-placeholder
                                        placeholder="Selecione lugar de cita" label="Lugar" required>
                                    </v-select>
                                </v-col>
                                <v-col cols="12" sm="4" md="4">

                                    <v-text-field v-model="cita_nueva.observacion" type="text" persistent-placeholder
                                        placeholder="Agregar observaciones" label="Observacion">
                                    </v-text-field>
                                </v-col>
                            </v-row>
                            <v-btn color="primary" class="mr-4" @click="guardar_cita">
                                Guardar Cita
                            </v-btn>
                            <v-btn color="primary" class="mr-4" @click="imprimir_directo"
                                @click.stop="v_agendar = false">
                                Boleta
                            </v-btn>

                        </v-container>

                    </v-form>
                </v-card>
            </template>
        </v-dialog>
    </v-card>
</template>

<script>
import {
    objectExpression
} from '@babel/types'
import axios from 'axios'
import moment from 'moment'
const day1 = new Date().getFullYear() + '-' + ("0" + (new Date().getMonth() + 1)).slice(-2) + '-' + ("0" + new Date().getDate()).slice(-2)

export default {

    props: {


    },
    pro(strx) {
        if (strx == '-1') {
            return 'No hay'
        }
        return strx;
    },
    data: () => ({
        validacion: false,
        paciente: {},
        op: Number,
        citas: [],
        v_agendar: false,
        menu2: false,
        datos_informacion: "1",
        op1: Number,
        valid: false,
        dialog: false,
        paciente_edit: {},
        las_citas: [],
        cita_nueva: {},
        fechacitaMin: moment(day1).add(1, 'd').format('YYYY-MM-DD'),
        //equipos
        equipos_actuales: [],
        tiempos_actuales: [],
        lista_tiempos: {},
        t_equipo: null,
        sortBy: 'fecha',
        sortDesc: true,
        selectRules: [v => v != 'No se tiene registro' || 'Se requiere el dato'],
        tipo_cita: ['RECALIFICADO', 'NUEVO'],
        
        lugares: ["CALLE MUÑOZ CORNEJO NRO 2702 - ESQUINA MENDEZ ARCOS - SOPOCACHI",
            "TELEFERICO MORADO - FARO MURILLO",
            "TELEFERICO PLATEADO - CIUDAD SATELITE",
            "IXIAMAS",
            "SAN BUENAVENTURA",
            "APOLO",
            "AUCAPATA",
            "AYATA",
            "CHUMA",
            "CURVA",
            "Gral. J.J. Pérez(CHARAZANI",
            "PELECHUCO",
            "ESCOMA",
            "HUMANATA",
            "MOCOMOCO",
            "PUERTO CARABUCO",
            "ACHACACHI",
            "ANCORAIMES",
            "CHUA COCANI",
            "COMBAYA",
            "HUARINA",
            "HUATAJATA",
            "QUIABAYA",
            "SANTIAGO DE HUATA",
            "SORATA",
            "PUERTO ACOSTA",
            "TACACOMA",
            "BATALLAS",
            "COPACABANA",
            "PUCARANI",
            "PUERTO PEREZ",
            "SAN PEDRO DE TIQUINA",
            "TITO YUPANQUI (Parquipujio)",
            "ACHOCALLA",
            "MECAPACA",
            "PALCA",
            "VIACHA",
            "ALTO BENI",
            "CARANAVI",
            "GUANAY",
            "MAPIRI",
            "PALOS BLANCOS",
            "TEOPONTE",
            "TIPUANI",
            "CHULUMANI (V. De La Libertad)",
            "CORIPATA",
            "COROICO",
            "IRUPANA",
            "LA ASUNTA",
            "YANACACHI",
            "DESAGUADERO",
            "GUAQUI",
            "JESUS DE MACHACA",
            "LAJA",
            "SAN ANDRES DE MACHACA",
            "TARACO",
            "TIAHUANACU",
            "CALACOTO",
            "CAQUIAVIRI",
            "CATACORA",
            "CHARAÑA",
            "COMANCHE",
            "COROCORO",
            "NAZACARA DE PACAJES",
            "SANTIAGO DE MACHACA",
            "CHACARILLA",
            "PAPEL PAMPA",
            "PATACAMAYA",
            "SAN PEDRO CURAHUARA",
            "SANTIAGO DE CALLAPA",
            "SICA SICA (Villa Aroma)",
            "UMALA",
            "AYO AYO",
            "CALAMARCA",
            "COLLANA",
            "COLQUENCHA",
            "SAPAHAQUI",
            "WALDO BALLIVIAN",
            "CAIROMA",
            "LURIBAY",
            "MALLA",
            "YACO",
            "CAJUATA",
            "COLQUIRI",
            "ICHOCA",
            "INQUISIVI",
            "QUIME",
            "VILLA LIBERTAD LICOMA"
        ],
        encabezado: [{
            text: 'Fecha',
            align: 'start',
            value: 'fecha',
        },
        {
            text: 'Hora de Cita',
            value: 'hora_inicio'
        },
        {
            text: 'doctor',
            value: 'ci_doctor',
        },
        {
            text: 'Equipo',
            value: 'equipo',
        },
        {
            text: 'Se presento',
            value: 'se_presento',
        },
        {
            text: 'Observaciones',
            value: 'observacion',
        },
            /*
                    { text: 'Fat (g)', value: 'fat' },
                    { text: 'Carbs (g)', value: 'carbs' },
                    { text: 'Protein (g)', value: 'protein' },
                    { text: 'Iron (%)', value: 'iron' },*/
        ],
        nombreRules: [
            v => !!v || 'Dato requerido',
            //v => v.length <= 10 || 'Name must be less than 10 characters',
        ],
        email: '',
        emailRules: [
            v => !!v || 'E-mail is required',
            v => /.+@.+/.test(v) || 'E-mail must be valid',
        ],
        departamentos: ['LA PAZ', 'ORURO', 'POTOSI', 'CHUQUISACA', 'BENI', 'TARIJA', 'SANTA CRUZ', 'COCHABAMBA', 'PANDO'],
        minFechaNac: '1899-01-01',
        maxFechaNac: day1,
        ssexo: ['MASCULINO', 'FEMENINO'],

    }),
    unmounted() {

    },
    beforeUpdate() {
        //this.paciente_edit = structuredClone(this.paciente);
        console.log("++++" + moment(this.fechacitaMin).add(1, 'd').format('YYYY-MM-DD'))
    },

    methods: {
        alert(text) {
            this.$alert(text).then(res => this.$inform("Cambios guardados!"));
        },
        pro(strx) {
            if (strx == '-1') {
                return 'No hay'
            }
            return strx;
        },
        forceUpper(value) {
            value = value.toUpperCase();
        },
        open() {
            this.dialog = true
        },
        async tabselect(a) {
           this.datos_informacion = a
            
        },
        close() {
            this.dialog = false
            this.cita_nueva = {}
            this.paciente = {}
            this.datos_informacion = ""
        },
        async cambiar_datos() {
            console.log(this.paciente)
            console.log(" antiguo: ")
            console.log(this.paciente_edit)
            var res = await axios({
                method: 'post',
                url: 'api/guardar_persona',
                data: {
                    nuevo: this.paciente,
                    antiguo: this.paciente_edit,
                    opcion: this.op1,
                }
            }).then();
            console.log(res);
            if (res['data'] == 'ok') {
                console.log('inserccion correcta')
                this.alert('Inserccion Correcta')
                this.op1 = 2;
            }
            if (res['data'] == 'ok update') {
                console.log('update correcto')
                this.alert('Actulizacion Correcta')

                this.paciente_edit = structuredClone(this.paciente)
                this.op1 = 2;
            }
            if (res['data'] == 'SQLSTATE[23505]:') {
                console.log('Error la nueva ci ya existe');
            }

        },
        async valorar() {

        },
        allowedDates(val) {
            var d = new Date(val).getDay()
            if (d == 5) return false;
            if (d == 6) return false;
            return true;
        },
        async change_fecha2() {
            if (this.cita_nueva.fecha < this.fechacitaMin) {
                this.cita_nueva = {}
                return;
            }
            console.log(this.cita_nueva.fecha)
            var a = await axios.post('api/citas_actuales/' + this.cita_nueva.fecha).then();
            console.log(a['data']);
            var array = a['data']
            this.equipos_actuales = [];
            var primera = true;
            var p, w = "";
            this.lista_tiempos = {};
            for (let i = 0; i < array.length; i++) {
                if (Object.keys(array[i]).length >= 1) {
                    this.equipos_actuales.push(i + 1);
                    var aux = [];
                    for (const key in array[i]) {
                        aux.push(array[i][key]);
                        if (primera) {
                            p = i,
                                w = array[i][key]
                            primera = false;
                        }
                    }
                    this.lista_tiempos['' + (i + 1)] = aux;
                }
            }
            this.cita_nueva.equipo = ""
            this.cita_nueva.hora_inicio = ""

        },
        cambioequipo() {
            this.tiempos_actuales = this.lista_tiempos['' + this.cita_nueva.equipo];
            this.cita_nueva.hora_inicio = this.tiempos_actuales[0];
            console.log(this.tiempos_actuales)
        },
        cambioequipos() {
            this.tiempos_actuales = this.lista_tiempos['' + this.editar.equipo];
            this.editar.hora = this.tiempos_actuales[0];
        },
        async guardar_cita() {
            if (this.$refs.form_cita.validate()) {
                let hof = moment(this.cita_nueva.fecha + 'T' + this.cita_nueva.hora_inicio).add(1, "h");

                var datos = {
                    hora_final: hof.format("HH:mm:ss"),
                    ci: this.paciente.ci,
                    ci_doctor: '-1',
                }
                datos = Object.assign(this.cita_nueva, datos);
                var res = await axios({
                    method: 'post',
                    url: 'api/dar_cita',
                    data: {
                        cita: datos,
                    }
                }).then();
                console.log(res);
            }

        },
        imprimir_directo() {
            this.selectedEvent = {
                fecha: this.fecha_cita_actual,
                hora: this.t_equipo,
                equipo: this.equipo_actual,
                tipo_cita: this.ttipocita,
                se_presento: '',
                observacion: this.tobservacion,
                lugar: this.tlugar,
                ci: this.ci,
                name: this.nombre,
                ap_paterno: this.paterno,
                ap_materno: this.materno,
            }
            console.log(this.tobservacion);

            this.v_imprimir = true;

        },

    },

}
</script>
