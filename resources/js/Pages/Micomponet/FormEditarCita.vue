<template>
  <v-card>
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
                  <v-menu
                    v-model="menu2"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    transition="scale-transition"
                    offset-y
                    min-width="auto"
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <!--<v-text-field v-model="date" label="Picker without buttons"
                                                        prepend-icon="mdi-calendar" readonly v-bind="attrs" v-on="on">
                                                    </v-text-field>-->
                      <v-text-field
                        v-model="cita_nueva.fecha"
                        :rules="nombreRules"
                        placeholder="Selecione fecha de cita"
                        required
                        prepend-icon="mdi-calendar"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                      >
                      </v-text-field>
                    </template>
                    <v-date-picker
                      v-model="cita_nueva.fecha"
                      :allowed-dates="allowedDates"
                      @input="menu2 = false"
                      @change="change_fecha2"
                      :min="fechacitaMin"
                      locale="es-ES"
                    >
                    </v-date-picker>
                  </v-menu>
                </v-col>
                <v-col cols="12" sm="4" md="4">
                  <v-select
                    v-model="cita_nueva.equipo"
                    :items="equipos_actuales"
                    :rules="nombreRules"
                    persistent-placeholder
                    placeholder="Selecione el equipo"
                    @input="cambioequipo"
                    color="purple darken-3"
                    label="Equipo"
                    required
                  >
                  </v-select>
                </v-col>
                <v-col cols="12" sm="4" md="4">
                  <v-select
                    v-model="cita_nueva.hora_inicio"
                    :items="tiempos_actuales"
                    :rules="nombreRules"
                    persistent-placeholder
                    placeholder="Selecione hora de cita"
                    color="purple darken-3"
                    label="Hora de inicio"
                    required
                  >
                  </v-select>
                </v-col>
              </v-row>
              <v-row no-gutters>
                <v-col cols="12" sm="4" md="4">
                  <v-select
                    v-model="cita_nueva.tipo_cita"
                    :items="tipo_cita"
                    color="purple darken-3"
                    persistent-placeholder
                    :rules="nombreRules"
                    placeholder="Selecione tipo de cita"
                    label="Tipo de cita"
                  >
                  </v-select>
                </v-col>
                <v-col cols="12" sm="4" md="4">
                  <v-select
                    v-model="cita_nueva.lugar"
                    :items="lugares"
                    color="purple darken-3"
                    :rules="nombreRules"
                    persistent-placeholder
                    placeholder="Selecione lugar de cita"
                    label="Lugar"
                    required
                  >
                  </v-select>
                </v-col>
                <v-col cols="12" sm="4" md="4">
                  <v-text-field
                    v-model="cita_nueva.observacion"
                    type="text"
                    persistent-placeholder
                    placeholder="Agregar observaciones"
                    label="Observacion"
                  >
                  </v-text-field>
                </v-col>
              </v-row>
              <v-btn color="primary" class="mr-4" @click="guardar_cita">
                Guardar Cita
              </v-btn>
              <v-btn
                color="primary"
                class="mr-4"
                @click.stop="v_agendar = false"
              >
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
import AppLayout from "@/Layouts/AppLayout";
import Welcome from "@/Jetstream/Welcome";
import moment from "moment";
const day1 =
  new Date().getFullYear() +
  "-" +
  ("0" + (new Date().getMonth() + 1)).slice(-2) +
  "-" +
  ("0" + new Date().getDate()).slice(-2);

export default {
  props: {
    pacientes: Array,
  },
  data: () => ({
    cita_anterior: {},
    cita_nueva: {},
    v_agendar: false,
    validacion: false,
    menu2: false,
    equipos_actuales: [],
    tiempos_actuales: [],
    lista_tiempos: {},
    fechacitaMin: moment(day1).add(1, "d").format("YYYY-MM-DD"),
    nombreRules: [(v) => !!v || "Se requiere el dato"],
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
  }),
  components: {
    AppLayout,
    Welcome,
    //Tablapaciente,
  },
  methods: {
    async change_fecha2() {
      /*if (this.cita_nueva.fecha < this.fechacitaMin) {
        this.cita_nueva = {};
        return;
      }*/
      console.log(this.cita_nueva.fecha);
      var a = await axios
        .post("api/citas_actuales/" + this.cita_nueva.fecha)
        .then();
      console.log(a["data"]);
      var datos = a["data"];
      this.equipos_actuales = [];
      this.tiempos_actuales = [];
      this.lista_tiempos = {};

      for (let i = 0; i < datos.length; i++) {
        let datos_array = Object.values(datos[i]);
        if (datos_array.length > 0) {
          var aux = []
          this.equipos_actuales.push(i + 1);
          for (const key in datos[i]) {
            aux.push(datos[i][key]);
          }
          this.lista_tiempos["" + (i + 1)] = aux;
        }
        
      }
      if( ! this.lista_tiempos.hasOwnProperty(''+this.cita_nueva.equipo) ){
        var aux = [this.cita_nueva.hora_inicio]
        this.lista_tiempos[this.cita_nueva.equipo] = aux
      }else{
         this.lista_tiempos[this.cita_nueva.equipo].push(this.cita_nueva.hora_inicio)
         this.lista_tiempos[this.cita_nueva.equipo].sort()
      }
      this.tiempos_actuales=this.lista_tiempos[this.cita_nueva.equipo]
      
    },
    cambioequipo() {
      this.tiempos_actuales = this.lista_tiempos["" + this.cita_nueva.equipo];
      this.cita_nueva.hora_inicio = this.tiempos_actuales[0];
      console.log(this.tiempos_actuales);
    },
    allowedDates(val) {
      var d = new Date(val).getDay();
      if (d == 5) return false;
      if (d == 6) return false;
      return true;
    },
    open() {
      this.v_agendar = true;
    },
    async guardar_cita() {
      if (this.$refs.form_cita.validate()) {
        let hof = moment(
          this.cita_nueva.fecha + "T" + this.cita_nueva.hora_inicio
        ).add(1, "h").format("HH:mm:ss")
        console.log(hof)
        this.cita_nueva.hora_final=hof
        var res = await axios({
          method: "post",
          url: "api/update_cita",
          data: {
            cita_nueva: this.cita_nueva,
            cita_anterior: this.cita_anterior
            },
        }).then();
        console.log(res);
      }
    },
  },
};
</script>
