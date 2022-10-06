<template>
    <v-app>

        <v-data-table :headers="headers" :items="desserts" sort-by="calories" class="elevation-1">
            <template v-slot:top>
                <v-toolbar flat>
                    <v-toolbar-title>Configuraciones</v-toolbar-title>
                    <v-divider class="mx-4" inset vertical></v-divider>
                    <v-spacer></v-spacer>
                    <v-dialog v-model="dialog" max-width="500px">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn color="primary" dark class="mb-2" v-bind="attrs" v-on="on">
                                Nueva configuarcion
                            </v-btn>
                        </template>

                        <v-stepper v-model="e1">
                            <v-stepper-header>
                                <v-stepper-step :complete="e1 > 1" step="1">
                                    Paso 1
                                </v-stepper-step>

                                <v-divider></v-divider>

                                <v-stepper-step :complete="e1 > 2" step="2">
                                    Paso 2
                                </v-stepper-step>

                                <v-divider></v-divider>

                                <v-stepper-step step="3">
                                    Paso 3
                                </v-stepper-step>
                            </v-stepper-header>

                            <v-stepper-items>
                                <v-stepper-content step="1">
                                    <v-card>

                                        <v-card-title>
                                            <span class="text-h5"> {{ formTitle }}</span>
                                        </v-card-title>

                                        <v-card-text>
                                            <v-container>
                                                <v-row>
                                                    <v-col>
                                                        <v-text-field v-model="editedItem.descripcion"
                                                            label="Descripcion">
                                                        </v-text-field>
                                                    </v-col>
                                                </v-row>
                                                <v-row>
                                                    <v-col>
                                                        <v-checkbox v-model="editedItem.atencion"
                                                            label="Se realiara la atencion">
                                                        </v-checkbox>
                                                    </v-col>

                                                </v-row>
                                                <v-row v-if="editedItem.atencion">
                                                    <v-radio-group v-model="editedItem.tipo" row>
                                                        <v-radio label="Temporal" value="temporal"></v-radio>
                                                        <v-radio label="Permanente" value="permanente"></v-radio>
                                                    </v-radio-group>
                                                    <v-row v-if='editedItem.tipo=="permanente"'>
                                                        <v-menu ref="menu" v-model="menu"
                                                            :close-on-content-click="false" :return-value.sync="date"
                                                            transition="scale-transition" offset-y min-width="auto">
                                                            <template v-slot:activator="{ on, attrs }">
                                                                <v-text-field v-model="date" label="Fecha de Vigencia"
                                                                    prepend-icon="mdi-calendar" readonly v-bind="attrs"
                                                                    v-on="on">
                                                                </v-text-field>
                                                            </template>
                                                            <v-date-picker v-model="date" no-title scrollable>
                                                                <v-spacer></v-spacer>
                                                                <v-btn text color="primary" @click="menu = false">
                                                                    Cancel
                                                                </v-btn>
                                                                <v-btn text color="primary"
                                                                    @click="$refs.menu.save(date)">
                                                                    OK
                                                                </v-btn>
                                                            </v-date-picker>
                                                        </v-menu>
                                                    </v-row>
                                                    <v-row v-if='editedItem.tipo=="temporal"'>
                                                        <v-menu ref="menu" v-model="menu"
                                                            :close-on-content-click="false"
                                                            :return-value.sync="date_temp" transition="scale-transition"
                                                            offset-y min-width="auto">
                                                            <template v-slot:activator="{ on, attrs }">
                                                                <v-text-field v-model="date_temp"
                                                                    label="Fecha de Configuracion temporal"
                                                                    prepend-icon="mdi-calendar" readonly v-bind="attrs"
                                                                    v-on="on">
                                                                </v-text-field>
                                                            </template>
                                                            <v-date-picker v-model="date_temp" multiple no-title
                                                                scrollable>
                                                                <v-spacer></v-spacer>
                                                                <v-btn text color="primary" @click="menu = false">
                                                                    Cancel
                                                                </v-btn>
                                                                <v-btn text color="primary"
                                                                    @click="$refs.menu.save(date_temp)">
                                                                    OK
                                                                </v-btn>
                                                            </v-date-picker>
                                                        </v-menu>
                                                    </v-row>
                                                </v-row>
                                                <v-row>

                                                </v-row>
                                            </v-container>
                                        </v-card-text>

                                        <v-card-actions>
                                            <v-spacer></v-spacer>

                                            <v-btn color="blue darken-1" text @click="close">
                                                Cancelar
                                            </v-btn>
                                            <v-btn color="primary" @click="step2">
                                                Continue
                                            </v-btn>
                                        </v-card-actions>
                                    </v-card>


                                </v-stepper-content>

                                <v-stepper-content step="2">
                                    <v-card>

                                        <v-card-title>
                                            <span class="text-h5"> {{ formTitle }}</span>
                                        </v-card-title>

                                        <v-card-text>
                                            <v-container>
                                                <sala ></sala>
                                            </v-container>
                                        </v-card-text>

                                        <v-card-actions>
                                            <v-btn color="primary" @click="e1 = 1">
                                                Atras
                                            </v-btn>
                                            <v-spacer></v-spacer>

                                            <v-btn color="blue darken-1" text @click="close">
                                                Cancelar
                                            </v-btn>
                                            <v-btn color="primary" @click="e1 = 3">
                                                Continue
                                            </v-btn>
                                        </v-card-actions>
                                    </v-card>

                                    
                                </v-stepper-content>

                                <v-stepper-content step="3">
                                    <v-card class="mb-12" color="grey lighten-1" height="200px"></v-card>

                                    <v-btn color="primary" @click="e1 = 1">
                                        Continue
                                    </v-btn>

                                    <v-btn text>
                                        Cancel
                                    </v-btn>
                                </v-stepper-content>
                            </v-stepper-items>
                        </v-stepper>

                    </v-dialog>
                    <v-dialog v-model="dialogDelete" max-width="500px">
                        <v-card>
                            <v-card-title class="text-h5">Estas seguro que quieres eliminar</v-card-title>
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

            <template v-slot:item.atencion="{ item }">
                <v-simple-checkbox v-model="item.atencion" disabled></v-simple-checkbox>
            </template>
            <template v-slot:item.principal="{ item }">
                <v-simple-checkbox v-model="item.principal" disabled></v-simple-checkbox>
            </template>


            <template v-slot:item.actions="{ item }">
                <v-icon small class="mr-2" @click="editItem(item)">
                    mdi-pencil
                </v-icon>
                <v-icon small @click="deleteItem(item)">
                    mdi-delete
                </v-icon>
            </template>
            <template v-slot:no-data>
                <span>No se tiene elementos </span>
            </template>
        </v-data-table>


    </v-app>
</template>
    
<script>
import AppLayout from '@/Layouts/AppLayout'
import Welcome from '@/Jetstream/Welcome'
import Sala from '@/Pages/Micomponet/Sala'

export default {
    components: {
        Sala
    },
    props: {
        configuracion: Array,
    },
    data: () => ({
        dialog: false,
        dialogDelete: false,
        headers: [
            {
                text: 'ID',
                align: 'start',
                sortable: true,
                value: 'id',
            },
            { text: 'Descripcion', value: 'descripcion' },
            { text: 'Se atendera', value: 'atencion' },
            { text: '', value: 'actions', sortable: false },
        ],
        desserts: [],
        editedIndex: -1,
        editedItem: {
            id: '',
            calories: 0,
            fat: 0,
            carbs: 0,
            protein: 0,
        },
        defaultItem: {
            id: '',
            calories: 0,
            fat: 0,
            carbs: 0,
            protein: 0,
        },
        menu: false,
        date: '',
        date_temp: [],

        e1: 1,
    }),
    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'Nueva Configuracion' : 'Editar Configuracion'
        },
    },
    watch: {
        dialog(val) {
            val || this.close()
        },
        dialogDelete(val) {
            val || this.closeDelete()
        },
    },
    created() {
        this.initialize()
    },
    methods: {
        initialize() {
            this.desserts = this.configuracion
        },
        editItem(item) {
            this.editedIndex = this.desserts.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialog = true
        },
        deleteItem(item) {
            this.editedIndex = this.desserts.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialogDelete = true
        },
        deleteItemConfirm() {
            this.desserts.splice(this.editedIndex, 1)
            this.closeDelete()
        },
        close() {
            this.dialog = false
            this.e1=1
            this.editedItem = {}
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
        save() {
            if (this.editedIndex > -1) {
                Object.assign(this.desserts[this.editedIndex], this.editedItem)
            } else {
                this.desserts.push(this.editedItem)
            }
            this.close()
        },
        async next_sala(){
            if( this.editedIndex === -1)
                return;
            
        },
        step1(){
            
            this.e1=1  
          },
        step2(){

          this.$store.id_config = this.editedItem.id
          console.log(this.$store.id_config)
          this.e1=2
        },
        step3(){

this.e1=3
}
    },

}
</script>
    