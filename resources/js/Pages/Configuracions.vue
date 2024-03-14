<template>
  <app-layout>
    <div>
      <div>
        {{ fecha_server }}
      </div>
      <v-card>
        <v-card-title>
          Configuracion General
          <v-spacer></v-spacer>
          <!--
          <v-btn @click="opennuevaconfig">
            Fecha proximas
          </v-btn>
          -->
          <v-btn @click="openferiado">
            Feriados
          </v-btn>
        </v-card-title>

      </v-card>
      <v-expansion-panels v-model="panel" focusable>
        <v-expansion-panel v-for="(item, i) in calendario" :key="i" @click="mostrar(item)">
          <v-expansion-panel-header class="d-flex align-center" :color="item.color">
            <v-card>
              <v-form ref="calendario_lineal">

                <v-row>
                  <v-col align="left" justify="center">
                    {{ item.descripcion }}
                  </v-col>
                  <v-col align="left" class="d-flex align-center">
                    {{ item.fecha_inicio }} - <span class="d-flex align-center" v-if="item.fecha_final == '9999-12-01'">
                      Indefinido </span>
                    <span v-else class="d-flex align-center"> {{ item.fecha_final }}</span>
                  </v-col>
                </v-row>
              </v-form>

            </v-card>
          </v-expansion-panel-header>


          <v-expansion-panel-content :color="item.color">
            <v-card class="d-flex align-center mb-6">
              <v-row class="d-flex align-center flat ">
                <v-col align="center" justify="center" cols="12" sm="4">
                  Descripcion: {{ item.descripcion }}
                </v-col>
                <v-col align="center" cols="12" sm="2">
                  <v-checkbox v-model="item.atencion" label="Atencion" value center disabled></v-checkbox>
                </v-col>
                <v-col align="center" cols="12" sm="6">
                  <v-tooltip v-if="item.principal" bottom>
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn class="ma-2" small :color="item.color" @click="openeditconfig(i)" v-bind="attrs" v-on="on"
                        dark>
                        <v-icon dark>
                          mdi-timer-cog
                        </v-icon>
                      </v-btn>
                    </template>
                    <span>Crear nueva linea de tiempo</span>
                  </v-tooltip>
                  <v-tooltip v-if="item.principal" bottom>

                    <template v-slot:activator="{ on, attrs }">
                      <v-btn class="ma-2" small :color="item.color" @click="openactulidad(i)" v-bind="attrs" v-on="on"
                        dark>
                        <v-icon dark>
                          mdi-clipboard-text-clock-outline
                        </v-icon>
                      </v-btn>
                    </template>
                    <span>Configuracion Actual</span>
                  </v-tooltip>


                  <v-tooltip bottom>

                    <template v-slot:activator="{ on, attrs }">
                      <v-btn class="ma-2" small :color="item.color" @click="opendeleteconfig(i)" v-bind="attrs"
                        v-on="on" dark>
                        <v-icon dark>
                          mdi-delete
                        </v-icon>
                      </v-btn>
                    </template>
                    <span>Eliminar</span>
                  </v-tooltip>
                </v-col>
              </v-row>
            </v-card>
            <v-row>

              <!--{{ item }}-->
              <v-col>
                <v-data-table :headers="headers" :footer-props="{
          itemsPerPageText: 'Fechas con configuracion',
          'items-per-page-options': [15, 30, 50, 100, -1], 'items-per-page-all-text': 'Todos'
        }" :items="fechas" item-key="ci" :header-props='{
          sortByText: "Ordenar por"
        }' class="elevation-1">

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
          </v-expansion-panel-content>
        </v-expansion-panel>
      </v-expansion-panels>
      <editconfig v-if='editarConfig' :dialog="editarConfig" :item="edit" @cerrar="closeeditconfig"></editconfig>
      <nuevaconfig v-if='nuevaConfig' :dialog="nuevaConfig" @cerrar="closenuevaconfig"></nuevaconfig>
      <feriado v-if='feriadoconf' :dialog="feriadoconf" @cerrar="closeferiadoconfig"></feriado>

      <deleteconfig v-if='deleteConfig' :dialog="deleteConfig" :datos="edit" :mensaje="mensaje"
        @respuesta="deleteconfig_respuesta">
      </deleteconfig>
      <v-dialog v-model="dialog_actulizar" scroll fullscreen hide-overlay persistent :scrim="false"
        transition="dialog-bottom-transition">
        <v-card>
          <v-toolbar dark color="black">
            <v-btn icon dark @click="dialog_actulizar = false">
              <v-icon>mdi-close</v-icon>
            </v-btn>
            <v-toolbar-title>Crear Nueva Sala</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
              <!--
                        <v-btn variant="text" @click="dialog_equipo = false">
                            Guardar
                        </v-btn>
                        -->
            </v-toolbar-items>
          </v-toolbar>
          <!--
          <v-calendar ref="calendar" v-model="fecha_calendario" color="error" type="category" category-show-all="true"
            :categories="categories" :events="events" :event-color="getEventColor" :weekday-format="getDay"
            @click:event="showEvent" :interval-minutes=60 :first-interval=7 :interval-count=14>
            
                    <templ
                    ate #day-body="{ date, category }">
                        <div class="v-current-time" :class="{ first: true }" :style="{ top: nowY }">
                        </div>
                    </template>
                    
          </v-calendar>
          -->

          <configurar v-if="dialog_actulizar" :datos_configuracion="datos_conf">
          </configurar>

        </v-card>
      </v-dialog>
    </div>

  </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import Welcome from '@/Jetstream/Welcome'
import Editconfig from '@/Pages/Micomponet/AdicionConfiguracion'
import nuevaconfig from '@/Pages/Micomponet/nuevaConfiguracion'
import deleteconfig from '@/Pages/Micomponet/eleminarConfiguracion'
import feriado from '@/Pages/Micomponet/feriadoConfiguracion'
import configurar from '@/Pages/Micomponet/ConfiguracionGeneral'

import moment from 'moment'
export default {
  components: {
    Editconfig,
    deleteconfig,
    AppLayout,
    nuevaconfig,
    feriado,
    configurar
  },
  props: {

    //calendario: Array,
    fecha_server: String,
  },
  created() {
    this.pedir_calendarioslineal()
  },
  mounted() {
    this.$store.dispatch('guardarfechaserver', this.fecha_server)
    ////console.log(this.$store.getters.getfecha_server);
  },
  data() {
    return {
      dialog_actulizar: false,
      panel: [],
      items: 5,
      editarConfig: false,
      feriadoconf: false,
      nuevaConfig: false,
      deleteConfig: false,
      edit: {},
      configuracion: [],
      calendario: [],
      mensaje: {},
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
      range: [],
      multiple: [],
      dates: [],
      config: [],
      menu: false,
      datos_conf: [],
      fechas: [],
    }
  },
  computed: {
    headers() {
      return [
        {
          text: 'Fecha',
          align: 'left',
          //sortable: false,
          value: 'fecha',

          //filter: this.nombreFilter,
          //filter: this.nombre_,
          //filter: this.nameFilter,
        }
      ]
    }

  },
  methods: {
    async pedir_datos(date) {

      console.log(this.fecha_calendario);

      var res = await axios({
        method: 'post',
        url: `/${process.env.MIX_CARPETA}/configuraciongeneral`,
        data: this.datos_configuracion
      }).then(
        (response) => {


          console.log(response);
          //return
          let salas = response.data['salas'];
          //this.equipos = response.data['equipo'];
          /*console.log('_equipo----');
          console.log(this.equipos);
          console.log(salas_disponibles);
          */
          //console.log('__'+salas)
          //alert('ssss')
          this.salas = salas
          this.categories = [];
          //let events = [];
          this.events = [];
          let start2 = new Date(this.fecha_calendario + 'T01:01:00-04:00')
          let end = new Date(this.fecha_calendario + 'T21:50:00-04:00')
          let fecha_server = moment(this.$store.getters.getfecha_server + 'T00:00:00-04:00')
          this.fecha_min = fecha_server.format('YYYY-MM-DD')
          for (const key in salas) {
            //console.log(start);
            //console.log(end);
            /*console.log('----')
            console.log(salas)
            console.log(salas_disponibles)
            */
            console.log('-000000---');
            console.log(salas);
            this.categories.push(salas[key]['descripcion'])
            this.events.push({
              name: 'Configuracion',//this.empty(salas[key]['id_municipio']) ? salas[key]['nombre_equipo'] : salas[key]['nombre_equipo'] + " " + salas[key]['municipio'],
              start: start2,
              end: end,
              color: 'black',
              timed: 0,
              category: this.categories[key],
              consultorio: salas[key],
              equipo: true
            })
            //}


            /*this.events.push({
                name: 'Cita',
                start: new Date(this.fecha_calendario + 'T08:01:00-04:00'),
                end: new Date(this.fecha_calendario + 'T09:01:00-04:00'),
                color: 'blue',
                timed: 1,
                category: this.categories[key],
            })*/
          }
          console.log(response.data['salas_disponibles']);
          let salas_disponibles = response.data['salas_disponibles'];

          console.log('--------------');
          console.log(salas_disponibles)
          for (const key in salas_disponibles) {
            let fichas = salas_disponibles[key];

            for (const x in fichas) {
              let ficha = fichas[x];
              /*console.log(ficha.nombres);
              console.log(ficha.id);
              console.log(ficha.id_equipo);
              console.log(ficha.id_sala);
              console.log(this.fecha_calendario);*/
              console.log(ficha)
              console.log(this.categories[key]);
              this.events.push({

                name: 'Sin asignar',
                //paciente.nombres + " " + paciente.ap_paterno + " " + paciente.ap_materno,
                start: new Date(this.fecha_calendario + 'T' + ficha.hora_inicio + '-04:00'),
                end: new Date(this.fecha_calendario + 'T' + ficha.hora_final + '-04:00'),
                color: '#595959',
                timed: 1,
                category: this.categories[key],
                fichas: fichas[x],
                //atencion: fichas[atencion]
                //paciente: structuredClone(paciente)
              })

            }
            //console.log(paciente.fecha + 'T'+paciente.hora_inicio+'-04:00')//new Date(),);

            /*if (Object.hasOwnProperty.call(object, key)) {
                const element = object[key];
                
            }**/
          }
          //alert()
          //console.log(this.type);

          //this.fetchEvents()
          //console.log(this.events)
        }
      ).catch(err => {
        console.log(err)
        console.log("err->", err.response.data)
        //return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
      });

    },
    async openactulidad(i) {
      this.datos_conf = this.calendario[i]
      this.dialog_actulizar = true
    },
    async mostrar($c) {
      console.log($c);
      var res = await axios({
        method: 'post',
        url: `/${process.env.MIX_CARPETA}/conf_prueba`,
        data: {
          fechas: $c,
        }//equipo: this.selectequipo.equipo

      }).then(
        (response) => {
          //var r = response.data.seleccion
          console.log(response.data);
          this.fechas = response.data
          //this.calendario = response.data


        }
      ).catch(err => {
        console.log(err)

      });
    },
    async pedir_calendarioslineal() {
      var res = await axios({
        method: "get",
        url: `/${process.env.MIX_CARPETA}/` + "calendariolineal",
      }).then(
        (response) => {
          //console.log(response);
          this.calendario = response.data
        },
        (error) => {
          //console.log(error);
        }


      );
    },
    openferiado(item) {
      ////console.log();
      //console.log(this.panel);
      //console.log(this.calendario);
      this.edit = structuredClone(this.calendario[this.panel])
      //console.log(this.edit);
      setTimeout(() => {

      }, 0);
      this.feriadoconf = true


    },
    openeditconfig(item) {
      ////console.log();
      //console.log(this.panel);
      //console.log(this.calendario);
      this.edit = structuredClone(this.calendario[this.panel])
      //console.log(this.edit);
      setTimeout(() => {

      }, 0);
      this.editarConfig = true


    },
    opennuevaconfig() {
      ////console.log();
      //console.log(this.panel);
      //console.log(this.calendario);
      //this.edit = structuredClone(this.calendario[this.panel])
      //console.log(this.edit);
      setTimeout(() => {

      }, 0);
      this.nuevaConfig = true


    },
    opendeleteconfig(item) {
      ////console.log();
      //console.log(this.panel);
      //console.log(this.calendario);
      this.edit = structuredClone(this.calendario[this.panel])
      //console.log(this.edit);
      this.mensaje = {}
      this.mensaje.texto = 'Deseas eliminar la configuracion'
      this.mensaje.titulo = 'Eliminar Calendario'
      setTimeout(() => {

      }, 0);
      this.deleteConfig = true


    },
    closeeditconfig(value) {
      /*
      //console.log(this.panel[this.items])
      //console.log(value)
      */
      this.editarConfig = value
      this.pedir_calendarioslineal()
    },
    closeferiadoconfig(value) {
      /*
      //console.log(this.panel[this.items])
      //console.log(value)
      */
      this.feriadoconf = value
      this.pedir_calendarioslineal()
    },
    closenuevaconfig(value) {
      /*
      //console.log(this.panel[this.items])
      //console.log(value)
      */
      this.nuevaConfig = value
      this.pedir_calendarioslineal()
    },

    delete_config() {
      this.deletcConfig = true
    },
    async deleteconfig_respuesta(val) {
      if (val == false) {
        this.deleteConfig = val
        return;
      }

      var res = await axios({
        method: "delete",
        url: `/${process.env.MIX_CARPETA}/` + "calendariolineal" + '/' + this.edit.id_calendario,
      }).then(
        (response) => {
          //console.log(response);

          this.calendario = response.data
          setTimeout(() => {

          }, 3);

        }



      ).catch((error) => {
        //console.log(error)
      });
      this.deleteConfig = false


    },

    probar() {
      //alert(this.dates[this.dates.length - 1])
      var ult = this.dates[this.dates.length - 1]
      /*for (x in this.dates) {
        ///var f = this.dates[x]

      }*/
    },
    async subir_datos() {
      var res = await axios({
        method: 'post',
        url: `/${process.env.MIX_CARPETA}/conf_prueba`,
        data: {
          fechas: this.dates.sort(),
        }//equipo: this.selectequipo.equipo

      }).then(
        (response) => {
          //var r = response.data.seleccion
          console.log(response.data);
          this.calendario = response.data


        }
      ).catch(err => {
        console.log(err)

      });
    }
  },
}
</script>