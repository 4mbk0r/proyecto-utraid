<template>
  <v-dialog v-model="dialog" ref="ventana" fullscreen hide-overlay @keydown.enter.stop.prevent="onEnter" persistent
    class="fill-height" color="blue" transition="dialog-bottom-transition">
    <v-toolbar :color="op1 === 1 ? 'green' : 'blue'">
      <v-btn icon @click="close">
        <v-icon>mdi-close</v-icon>
      </v-btn>
      <v-toolbar-title>Agendar Cita</v-toolbar-title>
      <v-spacer></v-spacer>
    </v-toolbar>

    <v-card>
      <v-tabs :color="op1 === 1 ? 'green' : 'blue'" v-model="datos_informacion" icons-and-text centered>
        <v-tab :color="op1 === 1 ? 'errror' : 'blue'" key="0">
          {{ op1 === 1 ? "Paciente Nuevo" : "Paciente" }}
          <v-icon color="poobrown">{{ icon_ci }}</v-icon>
        </v-tab>
        <v-tab v-if="paciente.register" @click="buscar_citas" key="1">
          Citas
          <v-icon color="poobrown">{{ "mdi-calendar-account" }}</v-icon>
        </v-tab>
      </v-tabs>

      <v-tabs-items v-model="datos_informacion" touchless>
        <v-tab-item>
          <v-card flat>
            <v-form v-model="valid" ref="formDatopersonales">
              <v-container>
                <v-row no-gutters>
                  <v-col cols="12" sm="8" class="pr-4">
                    <v-text-field v-model="paciente.ci" :rules="ciRules" :color="op1 === 1 ? 'green' : 'blue'"
                      label="Cedula de Identidad" @keydown.enter="buscadorporvalor()" @change="buscadorporci()" required>
                    </v-text-field>
                  </v-col>
                  <v-col cols="12" sm="4">
                    <v-select v-model="paciente.expedido" :rules="nombreRules" persistent-placeholder
                      placeholder="No se tiene datos" :items="departamentos" label="Expedido">
                    </v-select>
                  </v-col>
                </v-row>
                <v-row no-gutters>
                  <v-col cols="12" sm="4" md="4">
                    <v-text-field v-model="paciente.nombres" :rules="nombreRules" label="Nombre" @input="
                      (v) => {
                        paciente.nombres = v.toUpperCase().trim();
                      }"
                       @change="buscadorporvalor()" required>
                    </v-text-field>
                  </v-col>
                  <v-col cols="12" sm="4" md="4">
                    <v-text-field v-model="paciente.ap_paterno" @input="
                      (v) => {
                        paciente.ap_paterno = v.toUpperCase().trim();
                        validar_apellido(v)
                      }" 
                      :error-messages="errorpaterno" @keydown.enter="buscadorporvalor()" @change="buscadorporvalor()"
                      
                      label="Apellido Paterno" required>
                    </v-text-field>
                  </v-col>
                  <v-col cols="12" sm="4" md="4">
                    <v-text-field v-model="paciente.ap_materno" @input="
                      (v) => {
                        paciente.ap_materno = v.toUpperCase().trim();
                        validar_apellido(v)
                      }" 
                      :error-messages="errormaterno" @keydown.enter="buscadorporvalor()" @change="buscadorporvalor()"
                      label="Apellido Materno" required>
                    </v-text-field>
                  </v-col>

                </v-row>
                <v-row no-gutters>
                  <v-col cols="12" sm="1" md="1">
                    <v-icon @click="ver_apellido_casada = !ver_apellido_casada">
                      mdi-human-male-female
                    </v-icon>

                  </v-col>
                  <v-col v-show="ver_apellido_casada" cols="12" sm="4" md="4">
                    <v-text-field v-model="paciente.ap_casada" @input="
                      (v) => {
                        paciente.ap_casada = v.toUpperCase();
                      }
                    " @keydown.enter="buscadorporvalor()" @change="buscadorporvalor()" label="Apellido Casado"
                      required>
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
                    <v-text-field v-model="paciente.fecha_nacimiento" :min="minFechaNac" :max="maxFechaNac" type="date"
                      label="Fecha de nacimiento">
                    </v-text-field>
                  </v-col>
                  <v-col cols="12" class="d-flex justify-center" sm="6">
                    <!--<v-select v-model="paciente.sexo" persistent-placeholder placeholder="No se tiene datos"
                      :items="ssexo" color="purple darken-3" label="Sexo" pa-0 solo>
                    </v-select>-->

                    <v-radio-group v-model="paciente.sexo" row>
                      <v-radio label="Masculino" value="Masculino"></v-radio>
                      <v-radio label="Femenino" value="Femenino"></v-radio>
                    </v-radio-group>
                  </v-col>

                </v-row>
                <v-row no-gutters>
                  <v-col cols="12" sm="4">
                    <v-btn color="primary" class="mr-4" @click="cambiar_datos">
                      Guardar Datos
                    </v-btn>
                  </v-col>
                  <v-col cols="12" sm="4">
                    <v-btn v-if="op1 == 2" color="primary" class="mr-4" @click="dar_cita()">
                      Dar cita
                      <v-icon end icon>mdi-calendar</v-icon>
                    </v-btn>
                  </v-col>
                  <v-col v-if="op1 == 2" cols="12" sm="4">
                    <v-btn class="ma-2" color="primary">
                      Imprimir
                      <v-icon end icon> mdi-printer</v-icon>
                    </v-btn>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="12">
                    <v-data-table v-if="op1 == 1" :headers="headers" :footer-props="{
                      itemsPerPageText: 'Pacientes por pagina',
                      'items-per-page-options': [15, 30, 50, 100, -1], 'items-per-page-all-text': 'Todos'
                    }" :items="persona" item-key="ci" :search="search" :header-props='{
  sortByText: "Ordenar por"
}' @click:row="seleccion_paciente($event)" class="elevation-1">
                      <template v-slot:no-results>
                        <span>No existen datos</span>
                      </template>
                      <!--
                    <template v-slot:top>
                       v-container, v-col and v-row are just for decoration purposes
                      <v-container>
                        <v-row>

                          <v-col cols="6">
                            <v-row class="pa-6">
                              Filter for dessert name
                              <v-text-field v-model="search" append-icon="mdi-magnify" label="Buscar" single-line
                                hide-details></v-text-field>

                            </v-row>
                          </v-col>



                        </v-row>
                      </v-container>

                    </template>
                    -->
                    </v-data-table>
                  </v-col>
                </v-row>
                <v-card v-if="buscador == true"> buscar datos </v-card>
              </v-container>
            </v-form>
          </v-card>
        </v-tab-item>
        <v-tab-item>
          <v-card flat>
            <v-btn color="primary" class="mb-2" @click="openAgendar()">
              Agendar Nueva cita
            </v-btn>
            <v-data-table :headers="encabezado" :items="las_citas" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc"
              hide-default-footer disable-pagination>
              <template v-slot:item="i">
                <!-- Since v-slot:item overrides how each row is rendered, I rebuild the row starting from <tr>. This allows me to add a class to <tr> based on any condition I want (in this case, the calorie count) -->
                <tr :color="{
                  primary: i.item.fecha > fechacitaMin,
                  secondary: i.item.fecha < fechacitaMin,
                }">
                  <td>{{ i.item.fecha }}</td>
                  <td>{{ i.item.consultorio }}</td>
                  <td>{{ i.item.ci_paciente }}</td>
                  <td>{{ i.item.horario }}</td>
                  <td>{{ i.item.tipo_cita }}</td>
                  <td>{{ i.item.observacion }}</td>
                </tr>
              </template>
            </v-data-table>
          </v-card>
        </v-tab-item>
        <v-tab-item> </v-tab-item>
      </v-tabs-items>
    </v-card>
    <v-dialog v-model="v_agendar" max-width="600">
      <v-toolbar dark color="#1CA698">
        <v-btn icon dark @click="close_v_agendar()">
          <v-icon>mdi-close</v-icon>
        </v-btn>
        <v-toolbar-title>Agendar Cita</v-toolbar-title>
        <v-spacer></v-spacer>
      </v-toolbar>
      <template>
        <v-card id="section-to-print">
          <v-form ref="form_cita" v-model="validacion">
            <v-container>
              <v-row no-gutters>
                <v-col cols="12" sm="4" md="4">
                  <v-menu v-model="menu2" :close-on-content-click="false" :nudge-right="40" transition="scale-transition"
                    offset-y min-width="auto">
                    <template v-slot:activator="{ on, attrs }">
                      <!--<v-text-field v-model="date" label="Picker without buttons"
                                                                    prepend-icon="mdi-calendar" readonly v-bind="attrs" v-on="on">
                                                                </v-text-field>-->
                      <v-text-field v-model="cita_nueva.fecha" :rules="nombreRules" placeholder="Selecione fecha de cita"
                        required prepend-icon="mdi-calendar" readonly v-bind="attrs" v-on="on">
                      </v-text-field>
                    </template>
                    <v-date-picker v-model="cita_nueva.fecha" :allowed-dates="allowedDates" @input="menu2 = false"
                      @change="buscar_consultorios" :min="fechacitaMin" :max="fechacitaMax" locale="es-ES">
                    </v-date-picker>
                  </v-menu>
                </v-col>
                <v-col cols="12" sm="4" md="4">
                  <v-select v-model="cita_nueva.consultorio" item-text="descripcion" item-value="sala"
                    :items="consultorios" :rules="[validar_seleccion]" persistent-placeholder
                    placeholder="Selecione el Consultorio" color="purple darken-3" @change="buscar_horario"
                    label="Consultorio" required>
                  </v-select>
                </v-col>
                <v-col cols="12" sm="4" md="4">
                  <v-select v-model="cita_nueva.horario" :item-text="(item) => ver_horario(item)" item-value="id_horario"
                    :items="horario" :rules="nombreRules" persistent-placeholder placeholder="Selecione hora de cita"
                    color="purple darken-3" label="Hora de inicio" required>
                  </v-select>
                </v-col>
              </v-row>
              <v-row no-gutters>
                <v-col cols="12" sm="4" md="6">
                  <v-select v-model="cita_nueva.tipo_cita" :items="tipo_cita" color="purple darken-3"
                    persistent-placeholder :rules="nombreRules" placeholder="Selecione tipo de cita" label="Tipo de cita">
                  </v-select>
                </v-col>
                <v-col cols="12" sm="4" md="6">
                  <v-select v-model="cita_nueva.lugar" :items="lugares" color="purple darken-3" :rules="nombreRules"
                    persistent-placeholder placeholder="Selecione lugar de cita" label="Lugar" required>
                  </v-select>
                </v-col>
              </v-row>
              <v-row no-gutters>
                <v-col cols="12" sm="12" md="12">
                  <v-text-field v-model="cita_nueva.observacion" type="text" persistent-placeholder
                    placeholder="Agregar observaciones" label="Observacion">
                  </v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col>

                  <v-btn color="primary" class="mr-4" @click="guardar_cita">
                    Guardar Cita
                  </v-btn>

                </v-col>
                <v-col>
                  <v-btn color="primary" class="mr-4" @click="imprimir_directo" @click.stop="v_agendar = false">
                    Boleta
                  </v-btn>
                </v-col>
              </v-row>

            </v-container>
          </v-form>
        </v-card>
      </template>
    </v-dialog>

    <v-dialog v-model="msm_existe" persistent max-width="290">
      <v-card>
        <v-card-title class="text-h5">
          Usuario existe
        </v-card-title>
        <v-card-text>
          El usuario con
          <p>cedula de identidad: {{ paciente_existen.ci }}</p>
          <p>Nombres:{{ paciente_existen.nombres }}</p>
          <p>Apellido Paterno:{{ paciente_existen.ap_paterno }}</p>
          <p>Apellido Materno:{{ paciente_existen.ap_materno }}</p>
          Puedes user los datos ya tienes
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" text @click="msm_existe = false">
            Cancelar
          </v-btn>
          <v-btn color="green darken-1" text @click="persona_existente()">
            Usar datos
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="msm_update" persistent max-width="300">
      <v-card>
        <v-card-title class="text-h5">
          La cedula de identidad {{ paciente_edit.ci }}<br />
          fue cambiada por {{ paciente.ci }}
        </v-card-title>
        <v-card-text>
          Desea realizar una nueva busqueda o actulizar del cedula de identidad?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" text @click="nueva_busqueda">
            Nueva Busqueda
          </v-btn>
          <v-btn color="green darken-1" text @click="update_ci">
            Actualizar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="msm_update" persistent max-width="300">
      <v-card>
        <v-card-title class="text-h5">
          La cedula de identidad {{ paciente_edit.ci }}<br />
          fue cambiada por {{ paciente.ci }}
        </v-card-title>
        <v-card-text>
          Desea realizar una nueva busqueda o actulizar del cedula de identidad?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" text @click="nueva_busqueda">
            Nueva Busqueda
          </v-btn>
          <v-btn color="green darken-1" text @click="update_ci">
            Actualizar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="msm_imprimir" persistent max-width="300">
      <v-card>
        <v-card-title class="text-h5">
          El paciente con cedula de identidad {{ paciente_edit.ci }}<br />
          fue cambiada guradado correctamente {{ paciente.ci }}
        </v-card-title>
        <v-card-text>
          Desea imprimir boleta de cita?
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" text @click="close_imprimir">
            Cancelar
          </v-btn>
          <v-btn color="green darken-1" text @click="direccion_imprimir">
            imprimir
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-dialog>
</template>

<script>
//import { thisTypeAnnotation } from "@babel/types";
import axios from "axios";
import moment from "moment";
const _ = require('lodash');
const day1 =
  new Date().getFullYear() +
  "-" +
  ("0" + (new Date().getMonth() + 1)).slice(-2) +
  "-" +
  ("0" + new Date().getDate()).slice(-2);

export default {
  props: {},
  pro(strx) {
    if (strx == "-1") {
      return "No hay";
    }
    return strx;
  },
  data: () => ({
    validacion: false,
    items: [],
    search: '',
    paciente: {},
    paciente_existen: {},
    icon_ci: "mdi-account",
    consultorios: [],
    op: Number,
    citas: [],
    v_agendar: false,
    menu2: false,
    datos_informacion: "1",
    consultorio: "",
    sala: [],
    op1: Number,
    valid: false,
    dialog: false,
    buscador: false,
    msm_existe: false,
    msm_update: false,
    msm_imprimir: false,
    ver_apellido_casada: false,
    paciente_edit: {},
    las_citas: [],
    cita_nueva: {},
    fecha_cita: "",
    fechacitaMin: "",
    fechacitaMax: "",
    //equipos
    equipos_actuales: [],
    horario: [],
    lista_tiempos: {},
    t_equipo: null,
    sortBy: "fecha",
    sortDesc: true,
    selectRules: [(v) => v != "No se tiene registro" || "Se requiere el dato"],
    tipo_cita: ["RECALIFICADO", "NUEVO"],

    lugares: [
      "CALLE MUÑOZ CORNEJO NRO 2702 - ESQUINA MENDEZ ARCOS - SOPOCACHI",
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
      "VILLA LIBERTAD LICOMA",
    ],
    encabezado: [
      {
        text: "Fecha",
        align: "start",
        value: "fecha",
      },
      {
        text: "Consultorio",
        value: "consultorio",
      },
      {
        text: "Paciente",
        value: "ci_paciente",
      },
      /*
                    { text: 'Fat (g)', value: 'fat' },
                    { text: 'Carbs (g)', value: 'carbs' },
                    { text: 'Protein (g)', value: 'protein' },
                    { text: 'Iron (%)', value: 'iron' },*/
    ],
    nombreRules: [
      (v) => !!v || "Dato requerido",
      //(v) => (v.length >= 6) || "CI debe de tener mas de 6 caracteres",
      //v => v.length <= 10 || 'CI debe de tener mas de 10 caracteres',
    ],
    ciRules: [
      (v) => !!v || "Dato requerido",
      (v) => (v && v.length >= 6) || "CI debe de tener mas de 6 caracteres",
      //v => v.length <= 10 || 'CI debe de tener mas de 10 caracteres',
    ],
    rules: {
      select: [(v) => !!v || "Item is required"],
      select2: [(v) => validar_seleccion(v)],


    },
    email: "",
    emailRules: [
      (v) => !!v || "E-mail is required",
      (v) => /.+@.+/.test(v) || "E-mail must be valid",
    ],
    rulesApellido: [
      (v) => !!v || "Dato requerido",
      //this.validar_apellido

      //this.validar_apellido()
    ],
    departamentos: [
      "LA PAZ",
      "ORURO",
      "POTOSI",
      "CHUQUISACA",
      "BENI",
      "TARIJA",
      "SANTA CRUZ",
      "COCHABAMBA",
      "PANDO",
    ],
    minFechaNac: "1899-01-01",
    maxFechaNac: day1,
    ssexo: ["MASCULINO", "FEMENINO"],
    errorpaterno: [],
    errormaterno: [],

  }),

  beforeMount() {


  },
  destroyed() {
    this.items = []
  },
  created() {
    //this.paciente_edit = structuredClone(this.paciente);
    //console.log("++++" + moment(this.fechacitaMin).add(1, 'd').format('YYYY-MM-DD')
    //this.buscar_consultorios()
    this.fechacitaMin = moment(this.$store.getters.getfecha_server)
      .add(1, "d")
      .format("YYYY-MM-DD");
    this.fechacitaMax = moment(this.$store.getters.getfecha_server)
      .add(1, "Y")
      .format("YYYY-MM-DD");
    //this.datos_filtrado();
  },
  computed: {
    persona() { return this.items },
    headers() {
      return [
        {
          text: 'Nombres',
          align: 'left',
          //sortable: false,
          value: 'nombres',

          filter: this.nombreFilter,
          //filter: this.nombre_,
          //filter: this.nameFilter,
        },
        {
          text: 'Apellido Paterno',
          value: 'ap_paterno',
          filter: this.paternoFilter,
        },
        {
          text: 'Apellido Materno',
          value: 'ap_materno',
          filter: this.maternoFilter,
          //filter: this.caloriesFilter,
        },
        {
          text: 'Cedula de identidad',
          value: 'ci',
          filter: this.ciFilter,
          //filter: this.esta_en_equipo
          //filter: this.caloriesFilter,
        },
        {
          text: 'Expedido',
          value: 'expedido',
          //filter: this.ciFilter,
          //filter: this.esta_en_equipo
          //filter: this.caloriesFilter,
        },




        /*{
            text: 'Cargo',
            value: 'cargo',
            filter: this.cargoFilter
            //filter: this.caloriesFilter,
        },

        {
            text: 'Equipo',
            value: 'equipo',
            filter: this.esta_en_equipo,
            show: false,
            //filter: this.caloriesFilter,
        },*/


      ]
    }

  },
  methods: {

    validar_apellido(v) {



      if ( !_.isEmpty(this.paciente.ap_paterno) || !_.isEmpty(this.paciente.ap_materno) ) {
        
        this.errorpaterno = []
        this.errormaterno = []
        return true
      }
      console.log('______')
      this.errorpaterno = 'Este campo es requerido'
      this.errormaterno = 'Este campo es requerido'

      return false


    },

    seleccion_paciente(value) {
      this.paciente_existen = value
      this.msm_existe = true
    },
    ciFilter(value) {
      //console.log(this.paciente.nombres);
      if (typeof this.paciente.ci == 'undefined') return true
      if (this.paciente.ci == '') return true
      if (value.includes(this.paciente.ci)) return true;
      return false;
    },

    nombreFilter(value) {
      //console.log(this.paciente.nombres);
      if (typeof this.paciente.nombres == 'undefined') return true
      if (this.paciente.nombres == '') return true
      if (value.includes(this.paciente.nombres)) return true;
      return false;
    },
    paternoFilter(value) {
      //console.log(this.paciente.ap_paterno);
      if (typeof this.paciente.ap_paterno == 'undefined') return true
      if (this.paciente.ap_paterno == '') return true
      if (value.includes(this.paciente.ap_paterno)) return true;
      return false;
    },
    maternoFilter(value) {
      console.log(this.paciente.ap_materno);
      if (typeof this.paciente.ap_materno == 'undefined') return true
      if (this.paciente.ap_materno == '') return true
      if (value.includes(this.paciente.ap_materno)) return true;
      return false;
    },
    /*  inicialiazar fecha minima*/
    async datos_filtrado() {
      if (this.op1 == 1) {
        var res = await axios({
          method: 'get',
          url: `/${process.env.MIX_CARPETA}/persona`,
          /*data: {
            ficha: this.selectedEvent.fichas,
            equipo: this.selectequipo.equipo
          }*/
        }).then(
          (response) => {
            console.log("---_:.-.-.-.-.-.-.::::::::----");
            console.log(response);
            this.items = response.data
            /*this.selectedEvent.fichas = structuredClone(response.data);
            //this.selectedEvent.color = 'yellow'
            this.pedir_datos()
            this.selectedOpen = false
            */
          }
        ).catch(err => {
          console.log(err)

        });
      }


    },
    validar_seleccion() {
      /*console.log("___________");
      console.log(this.consultorios);
      
      console.log(this.cita_nueva.consultorio);
      console.log(this.consultorios.filter(e => e.sala === this.cita_nueva.consultorio).length>0);*/
      if (this.consultorios.filter(e => e.sala === this.cita_nueva.consultorio).length > 0) {
        return true;
      } else {
        //this.consultorio = null
        //this.cita_nueva.consultorio =null
        return 'valor requerido'
      }
    },
    direccion_imprimir() {
      console.log(".....");
      console.log(this.horario)
      console.log(this.cita_nueva)

      for (const K in this.horario) {
        if (this.horario[K].id_horario == this.cita_nueva.horario) {
          console.log(this.horario[K].hora_inicio + " - " + this.horario[K].hora_final);
          this.cita_nueva.hora_inicio = this.horario[K].hora_inicio + " - " + this.horario[K].hora_final

        }
      }
      let cita = Object.assign(this.paciente, this.cita_nueva)
      localStorage.setItem('cita', JSON.stringify(cita));
      window.open(`/${process.env.MIX_CARPETA}/imprimir`);
      this.close_imprimir()
    },
    open_imprimir() {
      this.msm_imprimir = true

    },
    close_imprimir() {
      this.msm_imprimir = false

    },
    fecha_min() {
      console.log("-----");
      console.log(this.$store.getters.getfecha_server);
      let fecha = moment(this.$store.getters.getfecha_server)
        .add(1, "d")
        .format("YYYY-MM-DD");
      return fecha;
    },
    fecha_max() {
      let fecha = moment(this.$store.getters.getfecha_server)
        .add(6, "M")
        .format("YYYY-MM-DD");
      console.log("hohooh");
      console.log(fecha);

      return fecha;
    },
    openAgendar() {
      //this.fecha_cita;
      this.v_agendar = true;
      this.cita_nueva.fecha = this.fecha_cita;
      this.cita_nueva.consultorio = this.consultorio;
      this.buscar_consultorios();
      console.log(this.cita_nueva);
      /*var res = await axios({
                method: 'get',
                url: `/${process.env.MIX_CARPETA}/api/buscar_persona/` + this.paciente.ci,
            }).then(
                (response) => {
                    console.log(response);
                    if (response['data']['mensaje'] == 'SQLSTATE[23505]:') {
                        //let rep = response['data']['persona']
                        this.msm_existe = true
                        this.paciente_existen = response['data']['persona']
                    }
                }, (error) => {
                    console.log(error);
                }*/
    },
    nueva_busqueda() {
      this.op1 = 1;
      let datos = structuredClone(this.paciente.ci);
      this.paciente = {};
      this.paciente_edit = {};
      this.paciente_existen = {};
      this.cita = {};
      this.paciente.ci = datos;
      this.msm_update = false;
      this.buscadorporci();
      this.$refs.formDatopersonales.resetValidation();
    },
    update_ci() {
      this.msm_update = false;
      this.cambiar_datos();
    },
    ver_horario(item) {
      return (
        moment(item.hora_inicio, "hh:mm:ss").format("HH:mm") +
        " - " +
        moment(item.hora_final, "hh:mm:ss").format("HH:mm")
      );
    },
    async buscar_horario() {
      console.log(this.cita_nueva);

      try {
        var res = await axios({
          method: "post",
          url: `/${process.env.MIX_CARPETA}/api/horario_disponible`,
          data: {
            cita_nueva: this.cita_nueva,
          },
        }).then(
          (response) => {
            console.log(response);
            this.horario = response.data;
            this.horario.sort()
            this.horario[0];
            //this.horario =  response.data['horario']
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
    },

    async buscadorporci() {
      if (this.paciente.ci == "") {
        return;
      }
      if (this.op1 == 1) {
        console.log(this.paciente.ci);
        var res = await axios({
          method: "get",
          url:
            `/${process.env.MIX_CARPETA}/api/buscar_persona/` +
            this.paciente.ci,
        }).then(
          (response) => {
            console.log(response);
            if (response["data"]["mensaje"] == "SQLSTATE[23505]:") {
              //let rep = response['data']['persona']
              this.msm_existe = true;
              this.paciente_existen = response["data"]["persona"];
            }
          },
          (error) => {
            console.log(error);
          }
        );
      } else {
        this.paciente_existen = {};
      }
      if (this.op1 == 2) {
        this.msm_update = true;
      }
    },
    async buscadorporvalor(valor) {
      console.log('--------');
      console.log(this.paciente);
      if (this.op1 == 1) {
        console.log(this.paciente.ci);
        var res = await axios({
          method: "post",
          url:
            `/${process.env.MIX_CARPETA}/api/buscar_valor`,
          data: {
            paciente: this.paciente
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
    persona_existente() {
      this.paciente = this.paciente_existen;
      this.paciente_existen = {};
      this.msm_existe = false;
      this.paciente_edit = structuredClone(this.paciente);
      this.op1 = 2;
    },
    alert(text) {
      this.$alert(text).then((res) => this.$inform("Cambios guardados!"));
    },
    pro(strx) {
      if (strx == "-1") {
        return "No hay";
      }
      return strx;
    },
    forceUpper(value) {
      value = value.toUpperCase();
    },
    open() {
      this.dialog = true;
      //this.datos_filtrado()
    },
    async tabselect(a) {
      this.datos_informacion = a;
    },
    buscadoractivate() { },
    close(event) {
      console.log(this.cita_nueva.fecha);
      console.log("----");
      this.$emit("pedir", this.fecha_cita);
      this.items = []
      this.dialog = false;
      this.cita_nueva = {};
      this.paciente = {};
      this.datos_informacion = "";
    },
    close_v_agendar() {
      this.v_agendar = false;
    },
    async cambiar_datos() {
      var  a = this.validar_apellido()
      if (this.$refs.formDatopersonales.validate() && a ) {
        console.log(this.paciente);
        console.log(" antiguo: ");
        console.log(this.paciente_edit);
        var res = await axios({
          method: "post",
          url: `/${process.env.MIX_CARPETA}/configuracion`,
          data: {
            nuevo: this.paciente,
            antiguo: this.paciente_edit,
            opcion: this.op1,
          },
        }).then(
          (response) => {
            console.log(response);
            ////console.log('validat');
            ////console.log(response);
            //console.log('__configuracion ___');
            //console.log(response.data);
            this.activar(response)



          },
        ).catch((error) => {
          //console.log(error.response.data.mensaje);

        });
        var res = await axios({
          method: "post",
          url: "api/guardar_persona",
          data: {
            nuevo: this.paciente,
            antiguo: this.paciente_edit,
            opcion: this.op1,
          },
        }).then();
        console.log(res);
        if (res["data"]["mensaje"] == "ok") {
          console.log("inserccion correcta");
          this.alert("Inserccion Correcta");
          this.paciente = res["data"]["persona"];
          this.paciente_edit = structuredClone(this.paciente);
          this.op1 = 2;
          return;
        }
        if (res["data"]["mensaje"] == "ok update") {
          console.log("update correcto");
          this.alert("Actulizacion Correcta");
          this.paciente = res["data"]["persona"];
          this.paciente_edit = structuredClone(this.paciente);
          this.op1 = 2;
          return;
        }
        if (res["data"]["mensaje"] == "SQLSTATE[23505]:" && this.op1 == 1) {
          this.msm_existe = true;
          this.paciente_existen = structuredClone(res["data"]["persona"]);
          return;
          //this.paciente_edit = structuredClone(this.paciente)
          //this.paciente = res['data']['persona']
        }
        if (res["data"]["mensaje"] == "SQLSTATE[23505]:" && this.op1 == 2) {
          this.alert(
            "No se puede cambiar la cedula de identidad " +
            this.paciente_edit.ci +
            " por " +
            this.paciente.ci +
            ". Por que esta (" +
            this.paciente_edit.ci +
            ") ya existe. Se volvera a la antigua configuracion"
          );
          this.paciente = structuredClone(this.paciente_edit);

          this.paciente_existen = {};
          this.op1 = 2;
          return;
          //this.paciente_edi t = structuredClone(this.paciente)
          //this.paciente = res['data']['persona']
        }
        if (this.op1 == 2) {
        }
      }
    },
    async buscar_citas() {
      /*console.log('......');
      console.log(this.paciente);
      try {
        var res = await axios({
          method: "get",
          url:
            `/${process.env.MIX_CARPETA}/api/buscar_persona_citas/` +
            this.paciente.id,
        }).then(
          (response) => {
            console.log(response);
            this.las_citas = response.data;
          },
          (error) => {
            console.log(error);
          }
        );
      } catch (error) { }*/
    },
    async valorar() { },
    allowedDates(val) {
      return true;
    },
    async buscar_consultorios() {
      try {
        var res = await axios({
          method: "post",
          url: `/${process.env.MIX_CARPETA}/api/buscar_consultorios`,
          data: {
            datos_agenda: this.cita_nueva,
          },
        }).then(
          (response) => {
            console.log("......");
            console.log(response);
            this.consultorios = response.data;
            this.buscar_horario();
            //this.horario =  response.data['horario']
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
    },
    async guardar_cita() {
      if (this.$refs.form_cita.validate()) {
        console.log("--------")
        console.log(this.cita_nueva.horario)
        let hof = moment(
          this.cita_nueva.fecha + "T" + this.cita_nueva.hora_inicio
        ).add(1, "h");

        this.cita_nueva.ci_paciente = this.paciente.ci;
        var res = await axios({
          method: "post",
          url: `/${process.env.MIX_CARPETA}/agendar`,
          data: {
            cita: this.cita_nueva,
            paciente: this.paciente,
          },
        }).then(
          (response) => {
            console.log(response);
            this.v_agendar = false;
            this.buscar_citas();
            this.open_imprimir()


          }
        ).catch((error) => {
          console.log(error.response)
          this.alert("occurio un error")
          return;
        });


        /*console.log(res['data']);
                this.las_citas = res['data'];
                this.v_agendar = false;*/
      }
    },
    imprimir_directo() {
      this.selectedEvent = {
        fecha: this.fecha_cita_actual,
        hora: this.t_equipo,
        equipo: this.equipo_actual,
        tipo_cita: this.ttipocita,
        se_presento: "",
        observacion: this.tobservacion,
        lugar: this.tlugar,
        ci: this.ci,
        name: this.nombre,
        ap_paterno: this.paterno,
        ap_materno: this.materno,
      };
      console.log(this.tobservacion);

      this.v_imprimir = true;
    },
    activar(response) {
      this.close()
      console.log(response);
      let res = response
      if (res["data"]["mensaje"] == "ok") {
        console.log("inserccion correcta");
        this.alert("Inserccion Correcta");
        this.paciente = res["data"]["persona"];
        this.paciente_edit = structuredClone(this.paciente);
        this.op1 = 2;
        return;
      }
      if (res["data"]["mensaje"] == "ok update") {
        console.log("update correcto");
        this.alert("Actulizacion Correcta");
        this.paciente = res["data"]["persona"];
        this.paciente_edit = structuredClone(this.paciente);
        this.op1 = 2;
        return;
      }
      if (res["data"]["mensaje"] == "SQLSTATE[23505]:" && this.op1 == 1) {
        this.msm_existe = true;
        this.paciente_existen = structuredClone(res["data"]["persona"]);
        return;
        //this.paciente_edit = structuredClone(this.paciente)
        //this.paciente = res['data']['persona']
      }
      if (res["data"]["mensaje"] == "SQLSTATE[23505]:" && this.op1 == 2) {
        this.alert(
          "No se puede cambiar la cedula de identidad " +
          this.paciente_edit.ci +
          " por " +
          this.paciente.ci +
          ". Por que esta (" +
          this.paciente_edit.ci +
          ") ya existe. Se volvera a la antigua configuracion"
        );
        this.paciente = structuredClone(this.paciente_edit);

        this.paciente_existen = {};
        this.op1 = 2;
      }
    },
    onEnter(event) {
      // evitar que la tecla Enter haga algo dentro del diálogo
      event.preventDefault();
    },
    async dar_cita() {
      console.log(this.cita_nueva);
      var res = await axios({
        method: "post",
        url: `/${process.env.MIX_CARPETA}/dar_ficha`,
        data: {
          cita: this.cita_nueva,
          paciente: this.paciente,
          fecha: this.fecha_cita
        },
      }).then(
        (response) => {
          console.log(response);



        }
      ).catch((error) => {
        console.log(error.response)
        this.alert("occurio un error")
        return;
      });
    }
  },
};
</script>
