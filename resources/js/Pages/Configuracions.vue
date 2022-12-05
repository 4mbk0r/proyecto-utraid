<template>
  <v-app>
    <div>
      <div>
        {{ fecha_server }}
      </div>

      <v-expansion-panels v-model="panel" focusable>
        <v-expansion-panel v-for="(item, i) in calendario" :key="i">
          <v-expansion-panel-header class="d-flex align-center" :color="item.color">
            <v-card>
              <v-form ref="calendario_lineal">

                <v-row>
                  <v-col align="left" justify="center">
                    {{ item.descripcion }}
                  </v-col>
                  <v-col align="left" class="d-flex align-center">
                    {{ item.fecha_inicio }} - <span class="d-flex align-center" v-if="item.fecha_final == '9999-12-30'">
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

                  <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn v-if="item.principal" class="ma-2" small :color="item.color" @click="openeditconfig(i)"
                        v-bind="attrs" v-on="on" dark>

                        <v-icon dark>
                          mdi-timer-cog
                        </v-icon>
                      </v-btn>
                    </template>
                    <span>Crear nueva linea de tiempo</span>
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

          </v-expansion-panel-content>
        </v-expansion-panel>
      </v-expansion-panels>
      <editconfig v-if='editarConfig' :dialog="editarConfig" :item="edit" @cerrar="closeeditconfig"></editconfig>
      <deleteconfig v-if='deleteConfig' :dialog="deleteConfig" :datos="edit" :mensaje="mensaje"
        @respuesta="deleteconfig_respuesta">
      </deleteconfig>
    </div>

  </v-app>
</template>
    
<script>
import AppLayout from '@/Layouts/AppLayout'
import Welcome from '@/Jetstream/Welcome'
import Editconfig from '@/Pages/Micomponet/AdicionConfiguracion'
import deleteconfig from '@/Pages/Micomponet/eleminarConfiguracion'

import moment from 'moment'
export default {
  components: {
    Editconfig,
    deleteconfig
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
    //console.log(this.$store.getters.getfecha_server);
  },
  data() {
    return {
      panel: [],
      items: 5,
      editarConfig: false,
      deleteConfig: false,
      edit: {},
      configuracion: [],
      calendario: [],
      mensaje: {},
    }
  },
  computed: {

  },
  methods: {
    async pedir_calendarioslineal() {
      var res = await axios({
        method: "get",
        url: `/${process.env.MIX_CARPETA}/` + "calendariolineal",
      }).then(
        (response) => {
          console.log(response);
          this.calendario = response.data
        },
        (error) => {
          console.log(error);
        }


      );
    },
    openeditconfig(item) {
      //console.log();
      console.log(this.panel);
      console.log(this.calendario);
      this.edit = structuredClone(this.calendario[this.panel])
      console.log(this.edit);
      setTimeout(() => {

      }, 0);
      this.editarConfig = true


    },
    opendeleteconfig(item) {
      //console.log();
      console.log(this.panel);
      console.log(this.calendario);
      this.edit = structuredClone(this.calendario[this.panel])
      console.log(this.edit);
      this.mensaje = {}
      this.mensaje.texto = 'Deseas eliminar la configuracion'
      this.mensaje.titulo = 'Eliminar Calendario'
      setTimeout(() => {

      }, 0);
      this.deleteConfig = true


    },
    closeeditconfig(value) {
      /*
      console.log(this.panel[this.items])
      console.log(value)
      */
      this.editarConfig = value
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
          console.log(response);
          this.calendario = response.data
        },
        (error) => {
          console.log(error);
        }


      );
      this.deleteConfig = false


    },


    paso1() {

    }

  },
}
</script>
    