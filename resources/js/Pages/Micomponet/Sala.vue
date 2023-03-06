<template>
  <v-data-table :headers="headers" :items="desserts" sort-by="calories" class="elevation-1" item-key="sala">
    <template v-slot:top>
      <v-toolbar flat>
        <v-toolbar-title>Sala</v-toolbar-title>
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-spacer></v-spacer>
        <v-btn color="primary" @click="dialog = true">
          Adicionar Sala
        </v-btn>
        <v-dialog v-if="dialog" v-model="dialog" max-width="500px">
          <salaespera ref="salaespera" @validar_descripcion="evaluar_descripcion($event)" @lista="mostrarsalas($event)"
            @eliminar="eliminarsalas($event)" @cerrar="cerrar($event)" :editedIndex="editedIndex"
            :editedItem="editedItem"></salaespera>
        </v-dialog>
        <v-dialog v-if="editgrupo" v-model="editgrupo" max-width="500px">
          <v-card>
            <v-card-title class="text-h5"> 
              {{ elemento.descripcion }}</v-card-title>

            <v-card-text>
              <equipos_seleccion :datos="elemento"></equipos_seleccion>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="">Cancelar</v-btn>
              <v-btn color="blue darken-1" text @click="">Aceptar</v-btn>
              <v-spacer></v-spacer>
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
      <v-icon small class="mr-2" @click="opengrupo(item)">
        mdi-account-group
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
import salaespera from '@/Pages/Micomponet/SalaEspera'

import equipos_seleccion from '@/Pages/Configuracion/Equipos_seleccion'
export default {
  props: {

    configuracion: Object,
    editar_consulta: false,

  },
  data: () => ({
    addconfiguracion: '',
    dialog: false,
    dialogDelete: false,
    editgrupo:  false,
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
    elemento: {},
  }),
  components: {
    salaespera,
    equipos_seleccion
  },
  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'Nueva Cansulta' : 'Editar Consulta'
    },
  },
  watch: {
    dialog(val) {
      //console.log('---> ' + val)
      //console.log(this.editedIndex);
      //this.editedIndex = val
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
    console.log(':::::::::::: ')
    console.log(this.configuracion.id_configuracion);
    this.initialize()
  },
  methods: {
    opengrupo(item){
      this.editgrupo =  true
      this.elemento = item
    },  
    async initialize() {

      console.log("----", this.configuracion);
      try {
        var res = await axios({
          method: 'get',
          url: `/${process.env.MIX_CARPETA}/api/` + "lista_salas" + '/' + this.configuracion.id_configuracion,
        }).then(
          (response) => {
            //console.log(response);
            this.desserts = response.data
            //console.log(response.data)

          }, (error) => {
            //console.log(error);
          }
        );
      } catch (err) {
        //console.log("err->", err.response.data)
        return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
      }

      //this.id_configuracion = $store.state.getid_config()
    },
    async editItem(item) {
      //this.dialog = true
      /*try {
          
          let pedido = {
            id_sala: item.id,
            id_institucion: '01',
            id_configuracion: this.id_configuracion
          }
          //console.log(pedido);
          var res = await axios({
            method: 'post',
            url: `/${process.env.MIX_CARPETA}/api/horario_sala`,
            data: pedido
          }).then(
            (response) => {
              ////console.log(response);
              //this.horario = response.data
              //console.log(response.data)
    
            }, (error) => {
              //console.log(error);
            }
          );
        } catch (err) {
          //console.log("err->", err.response.data)
          return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
        }*/
      //console.log('itemem')
      //console.log(item)
      //console.log(this.desserts)
      this.editedIndex = this.desserts.indexOf(item)
      //console.log(this.editedIndex)
      this.editedItem = Object.assign({}, item)
      this.editedItem.tiempo_apertura = moment(this.editedItem.tiempo_apertura, 'hh:mm:ss').format('HH:mm');
      this.editedItem.tiempo_cierre = moment(this.editedItem.tiempo_cierre, 'hh:mm:ss').format('HH:mm');
      //console.log(this.editedItem)
      this.editedItem.tiempo_descanso = moment(this.editedItem.tiempo_descanso, 'hh:mm:ss').format('HH:mm');
      this.calcular_horario()
      this.dialog = true



    },
    deleteItem(item) {
      this.editedIndex = this.desserts.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialogDelete = true
    },
    async deleteItemConfirm() {
      if (this.editar_consulta) {
        if (this.editedIndex >= 0) {
          var res = await this.axios({
            method: 'post',
            url: `/${process.env.MIX_CARPETA}/api/delete_sala`,
            data: {
              dato: this.editedItem
            },

          }).then(
            (response) => {
              //this.headers = response.data
              //console.log(response.data);
              this.desserts = response.data
              this.closeDelete();

            }, (error) => {
              //console.log(error);
            });
        }
      } else {

        this.desserts.splice(this.editedIndex, 1)
        this.closeDelete()
      }
      /*
      */
    },
    cerrar(val) {
      this.dialog = val
    },
    close() {
      this.dialog = false
      this.horario = []
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

    getId_config(e) {
      //console.log('---' + e)
      this.id_configuracion = e
      //console.log('---' + this.id_configuracion)
    },
    clear_time(val) {
      return moment(val, 'hh:mm:ss').format('h:mm a');
    },
    ///genera los horarios de todas las salas 
    generar_horario() {
      ////console.log('----genearara')
      ////console.log(this.desserts);
      let salas = this.desserts
      for (const i in salas) {

        this.editedItem = salas[i]
        this.calcular_horario()
        ////console.log(this.editedItem)
        salas[i].generar_horario = structuredClone(this.horario)
      }
    },
    calcular_horario() {
      if (this.editedItem.tiempo_apertura == '') {
        return
      }

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
    },
    mostrarsalas(valor) {
      //console.log('***-ç-ç-ç-ç-ç-ç-ç-ç-ç-******')
      //console.log(valor)
      this.desserts.push(valor)
      //console.log(this.desserts)
    },
    eliminarsalas(valor) {
      //console.log('*********')
      ////console.log(valor)
      //console.log(valor)
      //const ret = this.desserts.slice(valor.index);

      this.desserts.splice(valor.index, 1, valor.sala)
      //this.desserts[valor.index]= structuredClone(valor.sala)
    },
    evaluar_descripcion(val) {
      //console.log('validar desripcion')
      ////console.log(val)
      for (const i in this.desserts) {

        var editedX = this.desserts[i]

        if (editedX.descripcion === val) {
          //console.log(editedX.descripcion) 

          this.$refs.salaespera.existedescripcion()

        }
      }
    }
  },


}
</script>
