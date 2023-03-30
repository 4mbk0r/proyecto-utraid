<template>
  <v-card>
    <v-data-table :headers="headers" :footer-props="{ itemsPerPageText: 'Personal' }" :items="desserts" :search="search"
      sort-by="nombre" class="elevation-1 blue lighten-3">
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>Lista Personal</v-toolbar-title>
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-text-field v-model="search" append-icon="mdi-magnify" label="Buscar"></v-text-field>
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-select v-model="caloriesFilterValue" :items="cargos" item-text='cargo' attach chips label="cargos"
            multiple></v-select>
          <v-spacer></v-spacer>
          <v-dialog v-model="dialogDelete" max-width="500px">
            <v-card>
              <v-card-title class="text-h5">Are you sure you want to delete this item?</v-card-title>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text>Cancel</v-btn>
                <v-btn color="blue darken-1" text>OK</v-btn>
                <v-spacer></v-spacer>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-toolbar>
      </template>
      <template v-slot:item.actions="{ item }">
        <v-icon small class="mr-2" @click="openAdminr(item)">
          mdi-pencil
        </v-icon>
        <v-icon small @click="">
          mdi-delete
        </v-icon>
      </template>
      <template v-slot:no-data>
        No hay datos Personal
      </template>
      <template v-slot:no-results>
        <span>No se encontraron los datos.</span>
      </template>

    </v-data-table>
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
    openAdminr(item) {
      console.log(item);
      this.editedItem = structuredClone(item)
      this.dialog = true
    },
    async pedir_datos() {
      try {
        var res = await axios({
          method: 'get',
          url: `/${process.env.MIX_CARPETA}/` + "lista_personal",
        }).then(
          (response) => {
            console.log(response);
            this.desserts = response.data
            console.log(response.data)

          }, (error) => {
            console.log(error);
          }
        );
      } catch (err) {
        console.log("err->", err.response.data)
        return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
      }

    },
    close() {
      this.dialog = false
      this.pedir_datos()
    },
    cargoFilter(value) {
      console.log(this.caloriesFilterValue);

      // If this filter has no value we just skip the entire filter.
      if (this.caloriesFilterValue.length == 0) {
        return true;
      }
      var index = this.caloriesFilterValue.findIndex(e => e === value);
      
      return index !== -1

    }
  }

}
</script>
    


