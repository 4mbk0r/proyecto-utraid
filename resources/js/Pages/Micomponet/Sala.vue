<template>
  <v-data-table :headers="headers" :items="desserts" sort-by="calories" class="elevation-1" item-key="sala">
    <template v-slot:top>
      <v-toolbar flat>
        <v-toolbar-title>Sala</v-toolbar-title>
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-spacer></v-spacer>
        <v-dialog v-model="dialog" max-width="500px">
          <template v-slot:activator="{ on, attrs }">
            <v-btn color="primary" dark class="mb-2" v-bind="attrs" v-on="on">
              Adicionar Salas
            </v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="text-h5">{{ formTitle }}</span>
            </v-card-title>

            <v-card-text>
              <v-form>
                <v-row>
                  <v-col>
                    <v-text-field v-model="editedItem.descripcion"></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="12" sm="4">
                    <v-text-field v-model="editedItem.tiempo_apertura" type="time" label="Hora de aperuta">
                    </v-text-field>
                  </v-col>
                  <v-col cols="12" sm="4">
                    <v-text-field v-model="editedItem.tiempo_cierre" type="time" label="Hora de cierre"></v-text-field>
                  </v-col>
                  <v-col cols="12" sm="4">
                    <v-text-field v-model="editedItem.min_promedio_atencion" label="Minutos promedio de atencio">
                    </v-text-field>
                  </v-col>
                </v-row>

              </v-form>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="close">
                Cancelar
              </v-btn>
              <v-btn color="blue darken-1" text @click="save">
                Guardar
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
        <v-dialog v-model="dialogDelete" max-width="500px">
          <v-card>
            <v-card-title class="text-h5">Deseas Eliminar la Consulta</v-card-title>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="closeDelete">Cancelar</v-btn>
              <v-btn color="blue darken-1" text @click="deleteItemConfirm">Aceptar</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
    <template v-slot:item.actions="{ item }">
      <v-icon small class="mr-2" @click="editItem(item)">
        mdi-pencil
      </v-icon>
      <v-icon v-if="editar_consulta" small @click="deleteItem(item)">
        mdi-delete
      </v-icon>
      <v-icon v-if="!editar_consulta" small @click="deleteItem(item)">
        mdi-delete
      </v-icon>
    </template>
    <template v-slot:no-data>
      <span>No hay datos</span>
    </template>
  </v-data-table>
</template>
    
<script>
import AppLayout from '@/Layouts/AppLayout'
import Welcome from '@/Jetstream/Welcome'
import Barrasu from '@/Pages/Micomponet/Barrasu'
import moment from 'moment'
export default {
  props: {

    id_configuracion: '',
    editar_consulta: false,

  },
  data: () => ({
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
    editedIndex: -1,
    editedItem: {
      id: '',
      
    },
    defaultItem: {
      id: '',
    },
  }),
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'Nueva Cansulta' : 'Editar Consulta'
    },
  },
  watch: {
    dialog(val) {
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
    async initialize() {
      if (this.id_configuracion) {
        var res = await this.axios({
          method: 'get',
          url: '/main/configuracion2/' + this.id_configuracion,

        }).then();
        console.log(res);
        this.desserts = await res.data['salas']
        //this.id_configuracion = $store.state.getid_config()

      }
    },
    editItem(item) {
      this.editedIndex = this.desserts.indexOf(item)
      this.editedItem = Object.assign({}, item)
      
      this.dialog = true  
      this.editedItem.tiempo_apertura = moment(this.editedItem.tiempo_apertura,'hh:mm:ss').format('HH:mm');
      this.editedItem.tiempo_cierre = moment(this.editedItem.tiempo_cierre,'hh:mm:ss').format('HH:mm');
      
      
      
    },
    deleteItem(item) {
      this.editedIndex = this.desserts.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialogDelete = true
    },
    async deleteItemConfirm() {
      if (this.editar_consulta) {
        var res = await this.axios({
          method: 'post',
          url: `/${process.env.MIX_CARPETA}/api/delete_sala`,
          data: {
            dato: this.editedItem
          },

        }).then(
          (response) => {
            //this.headers = response.data
            console.log(response.data);
            this.desserts = response.data

          }, (error) => {
            console.log(error);
          });


      }
      /*
      */
      this.desserts.splice(this.editedIndex, 1)
      this.closeDelete()
    },
    close() {
      this.dialog = false

      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },
    closeDelete() {
      this.dialogDelete = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },
    async save() {
      
      if (this.editedIndex > -1) {
        if (this.editar_consulta) {
          var res = await this.axios({
          method: 'put',
          url: `/${process.env.MIX_CARPETA}/sala/`+this.editedItem.id,
          data: {
            datos: this.editedItem
          },

        }).then(
          (response) => {
            //this.headers = response.data
            console.log(response.data);
            //this.desserts = response.data

          }, (error) => {
            console.log(error);
          });
        
        }
        
        Object.assign(this.desserts[this.editedIndex], this.editedItem)
        
      } else {
        if(this.editar_consulta){
          var res = await this.axios({
          method: 'post',
          url: `/${process.env.MIX_CARPETA}/sala`,
          data: {
            datos: this.editedItem
          },

        }).then(
          (response) => {
            //this.headers = response.data
            console.log(response.data);
            //this.desserts = response.data

          }, (error) => {
            console.log(error);
          });
        }
        this.desserts.push(this.editedItem)
      }
      this.close()
    },
    getId_config(e) {
      console.log('---' + e)
      this.id_configuracion = e
      console.log('---' + this.id_configuracion)
    },
    clear_time(val){
      return moment(val,'hh:mm:ss').format('h:mm a');
    }
  },


}
</script>
    