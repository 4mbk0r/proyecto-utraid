<template>
  <v-card>


    <v-card>
      <v-card-title>
        <v-row>
          Lista Personal
          <v-divider class="mx-4" inset vertical></v-divider>

        </v-row>
      </v-card-title>
      <v-card-text>
        <v-row>
          <v-col>
            <v-text-field v-model="search" append-icon="mdi-magnify" label="Buscar"></v-text-field>

          </v-col>
          <v-col>
            <v-select v-model="caloriesFilterValue" :items="cargos" item-text='cargo' attach chips label="cargos" multiple
              :menu-props="{ offset: true, maxHeight: 200 }"></v-select>

          </v-col>

          <v-col>
            <v-switch v-model="valorEstado" :true-value="'activo'" :false-value="'inactivo'"
              :label="valorEstado"></v-switch>
          </v-col>
        </v-row>
        <V-row>
          <v-col>
            <v-data-table :headers="headers" :footer-props="{ itemsPerPageText: 'Personal' }" :items="desserts"
              :search="search" sort-by="nombre" class="elevation-1 blue lighten-3">
              <template v-slot:item.actions="{ item }">
                <v-icon v-if="item.estado === 'activo' || item.estado === null" small class="mr-2" @click="openAdminr(item)">
                  mdi-pencil
                </v-icon>
                <v-icon v-if="item.estado === 'activo' || item.estado === null" small @click="confirmDelete(item)">
                  mdi-delete
                </v-icon>
              </template>
              <template v-slot:no-data>
                No hay datos Personal
              </template>
              <template v-slot:no-results>
                <span>No se encontraron los datos.</span>
              </template>
              <template v-slot:item.estado="{ item }">
                <!-- Aquí puedes personalizar lo que se muestra en la columna "Estado" -->
                <span v-if="item.estado === 'activo' || item.estado === null">
                  Activo
                </span>
                <span v-if="item.estado === 'inactivo'">
                  <v-btn color="primary" @click="mostrarDialogo(item)">Activar Funcionario</v-btn>

                </span>
              </template>
            </v-data-table>

          </v-col>

        </V-row>
      </v-card-text>
    </v-card>

    <v-dialog v-model="mostrarConfirmacion" max-width="400">
      <v-card>
        <v-card-title>Confirmar Activación</v-card-title>
        <v-card-text>
          ¿Estás seguro de que quieres activar a este funcionario?
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="activarFuncionario">Confirmar</v-btn>
          <v-btn color="red darken-1" @click="cerrarDialogo">Cancelar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="dialogDelete" max-width="300">
      <v-card>
        <v-card-title class="headline">¿Estás seguro que deseas eliminar este elemento?</v-card-title>
        <v-card-actions>
          <v-btn color="red darken-1" text @click="deleteItem">Eliminar</v-btn>
          <v-btn color="blue darken-1" text @click="cancelDelete">Cancelar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">

      <v-card>
        <v-toolbar dark color="primary">
          <v-btn icon dark @click="close">
            <v-icon>mdi-close</v-icon>
          </v-btn>
          <v-toolbar-title>Cambiar Datos</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>

          </v-toolbar-items>
        </v-toolbar>
        <Cuenta :form="editedItem" :admin="true"></Cuenta>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'

import Cuenta from '@/Pages/Personal/CuentaAdministrar'

export default {
  props: {
    actulizar: Boolean,
    cargos: Array,
  },
  data: () => ({

    mostrarConfirmacion: false,
    valorEstado: 'activo',
    deshabilitado: false,
    showEstadoColumn: true,
    notifications: false,
    sound: true,
    widgets: false,
    search: '',
    dialog: false,
    dialogDelete: false,
    /*headers: [{
      text: 'Nombre',
      align: 'start',
      //sortable: false,
      value: 'nombre',
    },
    {
      text: 'Apellido Paterno',
      value: 'ap_paterno'
    },
    {
      text: 'Apellido Materno',
      value: 'ap_materno'
    },
    {
      text: 'Cedula de Identidad',
      value: 'ci'
    },
    {
      text: 'Cargo',
      value: 'cargo',
      filter: this.cargoFilter
    },
    {
      text: 'Tarea',
      value: 'actions',
      sortable: false
    },
    ],
    */
    desserts: [],
    editedIndex: -1,
    editedItem: {
      name: '',
      calories: 0,
      fat: 0,
      carbs: 0,
      protein: 0,
    },
    defaultItem: {
      name: '',
      calories: 0,
      fat: 0,
      carbs: 0,
      protein: 0,
    },
    items: ['foo', 'bar', 'fizz', 'buzz'],
    value: ['foo', 'bar', 'fizz', 'buzz'],
    caloriesFilterValue: [],
    item: {},
    valorEstado: 'activo'
    //roles: [] 
    //['Admin', 'Medico General', 'Trabajo Social', 'Operador Terapético', 'Psicologo', 'Psicologo', 'Secretaria', 'recepcionista'],
  }),
  mounted() {
    this.pedir_datos();
  },
  components: {
    AppLayout,
    Cuenta


  },
  created() {
    //console.log(cargos)
  },
  computed: {
    update(validaste) {

    },

    headers() {
      return [{
        text: 'Nombre',
        align: 'start',
        //sortable: false,
        value: 'nombres',
      },
      {
        text: 'Apellido Paterno',
        value: 'ap_paterno'
      },
      {
        text: 'Apellido Materno',
        value: 'ap_materno'
      },
      {
        text: 'Cedula de Identidad',
        value: 'ci'
      },
      {
        text: 'Cargo',
        value: 'cargo',
        filter: this.cargoFilter
      },
      {
        text: 'Estado',
        value: 'estado',
        filter: this.estadoFilter,
        hidden: true,
        class: 'hidden-column',
      },
      {
        text: 'Tarea',
        value: 'actions',
        sortable: false
      }

      ];
    }

  },
  watch: {

  },
  methods: {
    mostrarDialogo(item) {
      this.editedItem = structuredClone(item)
      this.mostrarConfirmacion = true; // Mostrar el diálogo de confirmación
    },
    cerrarDialogo() {
      this.mostrarConfirmacion = false; // Cerrar el diálogo de confirmación
      this.editedItem = {}
    },
    activarFuncionario() {
      // Realiza la activación del funcionario aquí
      // Puedes realizar la consulta y activación del funcionario aquí

      // Una vez que se haya activado el funcionario, cierra el diálogo de confirmación
      axios({
        method: 'post',
        url: `/${process.env.MIX_CARPETA}/api/lista_personal`,
        data: this.editedItem
      })
        .then((response) => {
          console.log(response);
          //this.desserts = response.data;
          //console.log(response.data);
          //alert('eliminado')
          this.pedir_datos()
          //this.dialogDelete = false

          this.cerrarDialogo();
        })
        .catch((error) => {
          if (error.response) {
            console.error("Error de respuesta:", error.response.data);
            // Puedes manejar el error de la manera que necesites aquí.
          } else if (error.request) {
            console.error("Error de solicitud:", error.request);
          } else {
            console.error("Error:", error.message);
          }
        });

    },
    toggleEstado() {
      console.log('==========')
      console.log(this.valorEstado);
      // Cambia el valor del estado entre "activo" e "inactivo"
      if (this.valorEstado == 'activo') {
        this.valorEstado = 'inactivo'
      } else[
        this.valorEstado = 'activo'
      ]
    },
    openAdminr(item) {
      console.log(item);
      this.editedItem = structuredClone(item)
      this.dialog = true
    },
    async pedir_datos() {
      axios({
        method: 'get',
        url: `/${process.env.MIX_CARPETA}/lista_personal`,
      })
        .then((response) => {
          console.log(response);
          this.desserts = response.data;
          console.log(response.data);
        })
        .catch((error) => {
          if (error.response) {
            console.error("Error de respuesta:", error.response.data);
            // Puedes manejar el error de la manera que necesites aquí.
          } else if (error.request) {
            console.error("Error de solicitud:", error.request);
          } else {
            console.error("Error:", error.message);
          }
        });

    },
    close() {
      this.dialog = false
      this.pedir_datos()
    },
    cargoFilter(value) {
      ///console.log(this.caloriesFilterValue);

      // If this filter has no value we just skip the entire filter.
      if (this.caloriesFilterValue.length == 0) {
        return true;
      }
      var index = this.caloriesFilterValue.findIndex(e => e === value);

      return index !== -1

    },
    estadoFilter(value) {
      console.log('////////////////////');
      console.log(this.valorEstado);
      console.log(value);
      if (this.valorEstado === 'activo') {
        if (value === null || value === this.valorEstado) {
          return true;
        }
        return false
      }
      if (value === this.valorEstado) {
        return true;
      }
      // If this filter has no value we just skip the entire filter.
      return false
      var index = this.caloriesFilterValue.findIndex(e => e === value);

      return index !== -1

    },
    confirmDelete(item) {
      this.editedItem = structuredClone(item)
      this.dialogDelete = true; // Muestra el diálogo de eliminación
    },
    deleteItem() {
      // Aquí debes implementar la lógica para eliminar el elemento
      // Luego, después de eliminarlo con éxito, puedes cerrar el diálogo.
      // Por ejemplo:
      // this.deleteElement();
      // this.dialogDelete = false;
      axios({
        method: 'delete',
        url: `/${process.env.MIX_CARPETA}/lista_personal/` + this.editedItem.username,
      })
        .then((response) => {
          //console.log(response);
          //this.desserts = response.data;
          //console.log(response.data);
          //alert('eliminado')
          this.pedir_datos()
          this.dialogDelete = false

        })
        .catch((error) => {
          if (error.response) {
            console.error("Error de respuesta:", error.response.data);
            // Puedes manejar el error de la manera que necesites aquí.
          } else if (error.request) {
            console.error("Error de solicitud:", error.request);
          } else {
            console.error("Error:", error.message);
          }
        });

    },
    cancelDelete() {
      this.dialogDelete = false; // Cierra el diálogo de eliminación
    },

  }

}
</script>
    


