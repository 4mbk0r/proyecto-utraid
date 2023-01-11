<template>
  <v-app>

    <v-card>
      <v-card-title>
        <v-row>
          <span class="text-h5">{{ formTitle }}</span>
        </v-row>
        <v-row>
          <v-col cols="6">
            <v-btn color="blue darken-1" text @click="close">
              Cancelar
            </v-btn>
          </v-col>
          <v-col cols="3">
            <v-btn tile color="success" @click="save">
              <v-icon left>
                mdi-pencil
              </v-icon>
              Guardar
            </v-btn>
          </v-col>
        </v-row>
      </v-card-title>

      <v-card-text>
        <v-form ref="salas">
          <v-row>
            <v-col>
              <v-text-field v-model="editedItem.descripcion" :rules="nombreRules"
              @change="validar_descripcion" 
              :error-messages="errordescripcion"
                label="Nombre de la Sala"></v-text-field>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" sm="4">
              <v-text-field v-model="editedItem.tiempo_apertura" type="time" @change="calcular_horario"
                :rules="nombreRules" label="Hora de apertura">
              </v-text-field>
            </v-col>
            <v-col cols="12" sm="4">
              <v-text-field v-model="editedItem.tiempo_cierre" :rules="nombreRules" type="time"
                @change="calcular_horario" label="Hora de cierre"></v-text-field>
            </v-col>
            <v-col cols="12" sm="4">
              <v-text-field v-model="editedItem.min_promedio_atencion" :rules="nombreRules"
                label="Minutos promedio de atencion" @change="calcular_horario">
              </v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col cols="12" sm="4">
              <v-text-field v-model="editedItem.tiempo_descanso" @change="calcular_horario" type="time"
                label="Hora de descanso">
              </v-text-field>
            </v-col>

          </v-row>
          <v-data-table :headers="headers_consultorio"
            :footer-props="{ 'items-per-page-text': '', 'items-per-page-all-text': 'Todos' }" :items="horario"
            sort-by="calories" class="elevation-1">

          </v-data-table>

        </v-form>
      </v-card-text>
      <!--
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="close">
          Cancelar
        </v-btn>
        <v-btn color="blue darken-1" text @click="save">
          Guardar
        </v-btn>
      </v-card-actions>
      -->
    </v-card>
  </v-app>

</template>
<script>
import moment from 'moment'
export default {
  props: {

    configuracion: {},
    editar_consulta: false,
    editedIndex: Number,
    editedItem: {},
  },
  data: () => ({
    addconfiguracion: '',
    dialog: false,
    dialogDelete: false,
    headers: [


      {
        text: 'Descrpcion',
        align: 'start',
        value: 'descripcion',
      },
      { text: 'Opciones', value: 'actions', sortable: false },
    ],
    desserts: [],

    defaultItem: {
      id: '',
    },
    headers_consultorio: [

      {
        text: 'Hora inicio',
        align: 'start',
        sortable: true,
        value: 'hora_inicio',
      },
      {
        text: 'Hora final',
        align: 'start',
        sortable: true,
        value: 'hora_final',
      },
    ],
    horario: [],
    nombreRules: [v => !!v || 'Se requiere el dato'],
    errordescripcion: []
  }),
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'Nueva Cansulta' : 'Editar Consulta'
    },
  },
  watch: {
    dialog(val) {
      //console.log(val)
      //console.log(this.editedIndex);

      val || this.close()
    },
    dialogDelete(val) {
      val || this.closeDelete()
    },
    salas: function () {
      this.$emit('salas', this.salas)
    }
  },
  created() {
    this.initialize()
  },
  methods: {
    initialize(){
      this.calcular_horario()
    },  
    async save() {
      //console.log("editar " + this.editar_consulta);
      //console.log("editar " + this.editedIndex);
      //console.log(this.editedItem);
      if (!this.$refs.salas.validate()) {
        //console.log("razones");
        this.horario = []
        return
      }
      var res = await this.axios({
        method: 'post',
        url: `/${process.env.MIX_CARPETA}/sala`,
        data: {
          sala: this.editedItem,
          horario: this.horario
        },

      }).then(
        (response) => {
          //this.headers = response.data
          //console.log('Responder');
          //console.log(response.data)
          if (this.editedIndex <= -1) {
            this.$emit('lista', response.data)
          }
          else {
            var v = {
              'index': this.editedIndex,
              'sala': response.data
            }
            this.$emit('eliminar', v);

            //this.$emit('adicionar_sala', this.editedItem)
          }
          this.close();
          //this.editedIndex = response.data['id'];


        }).catch((error) => {
          //console.log(error);

        });


    },
    close(){
      this.$emit('cerrar', false);
      this.horario =[]

    },
    validar_descripcion(){
      this.errordescripcion = []
      //console.log('_______sdfASD_')
      this.$emit('validar_descripcion', this.editedItem.descripcion);
    },
    calcular_horario() {

      try {
        ////console.log(this.fin_atencion, this.fin_atencion)
        let tiempo_i = moment(this.editedItem.tiempo_apertura, 'hh:mm')
        let tiempo_f = moment(this.editedItem.tiempo_cierre, 'hh:mm')
        ////console.log(tiempo_i, tiempo_f)
        this.horario = []
        let i = 0;
        let tiempo_descanso = true
        if (!tiempo_i.isBefore(tiempo_f)) return
        ////console.log('calcular_horario', this.editedItem.tiempo_atencion);

        if (this.editedItem.min_promedio_atencion > 0 && this.editedItem.min_promedio_atencion != '') {

          while (tiempo_i.isBefore(tiempo_f)) {
            let op = {}
            op.hora_inicio = tiempo_i.format('HH:mm')

            let s = tiempo_i.add(this.editedItem.min_promedio_atencion, 'minutes')
            op.hora_final = s.format('HH:mm')
            op.sala = this.editedItem.sala
            this.horario.push(op);
            if (tiempo_i.isSameOrAfter(moment(this.editedItem.tiempo_descanso, 'HH:mm')) && tiempo_descanso == true) {
              let mensaje = 'hora de descanso sera ' + tiempo_i.format('HH:mm')
              tiempo_i = tiempo_i.add(30, 'minutes')
              mensaje += ' - ' + tiempo_i.format('HH:mm')
              //alert(mensaje)
              tiempo_descanso = false
            } else {
              tiempo_i = s
              ////console.log(tiempo_i, tiempo_f)
            }

          }
        }

      } catch (error) {
        this.horario = []
      }

    },
    existedescripcion(){
      this.errordescripcion.push('Ya existe Nombre de sala en la lista')
    }
  },


}
</script>
    