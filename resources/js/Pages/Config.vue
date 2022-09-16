<template>
    <app-layout>
        <!--<welcome />-->
        <v-card>
            <v-form>
                <v-row align="center">
                    <v-col cols="12" sm="6">
                        <v-select v-model="datos.dias" :items="items" attach chips label="Dias de no atencion" multiple>
                        </v-select>
                    </v-col>
                </v-row>
                <v-row align="center">
                    <v-col cols="12">
                        <v-data-table :headers="headers" :items="desserts" sort-by="calories" class="elevation-1">
                            <template v-slot:top>
                                <v-toolbar flat>
                                    <v-toolbar-title>Feriados</v-toolbar-title>
                                    <v-divider class="mx-4" inset vertical></v-divider>
                                    <v-spacer></v-spacer>
                                    <v-dialog v-model="dialog" max-width="500px">
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-btn color="primary" dark class="mb-2" v-bind="attrs" v-on="on">
                                                Adicionar Fecha
                                            </v-btn>
                                        </template>
                                        <v-card>
                                            <v-card-title>
                                                <span class="text-h5">Feriados</span>
                                            </v-card-title>

                                            <v-card-text>
                                                <v-container>
                                                    <v-row>
                                                        <v-col cols="12">
                                                            <v-text-field type="date" v-model="editedItem.fecha"
                                                                label="Fecha">
                                                            </v-text-field>
                                                        </v-col>
                                                        <v-col cols="12">
                                                            <v-text-field v-model="editedItem.titulo" label="Titulo">
                                                            </v-text-field>
                                                        </v-col>


                                                    </v-row>
                                                </v-container>
                                            </v-card-text>

                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn color="blue darken-1" text @click="close">
                                                    Cancel
                                                </v-btn>
                                                <v-btn color="blue darken-1" text @click="save">
                                                    Save
                                                </v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                                    <v-dialog v-model="dialogDelete" max-width="500px">
                                        <v-card>
                                            <v-card-title class="text-h5">Are you sure you want to delete this item?
                                            </v-card-title>
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn color="blue darken-1" text @click="closeDelete">Cancel
                                                </v-btn>
                                                <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK
                                                </v-btn>
                                                <v-spacer></v-spacer>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                                </v-toolbar>
                            </template>
                            <template v-slot:[`item.actions`]="{ item }">
                                <v-icon small class="mr-2" @click="editItem(item)">
                                    mdi-pencil
                                </v-icon>
                                <v-icon small @click="deleteItem(item)">
                                    mdi-delete
                                </v-icon>
                            </template>
                            <template v-slot:no-data>
                                <v-btn color="primary" @click="initialize">
                                    Reset
                                </v-btn>
                            </template>
                        </v-data-table>
                    </v-col>
                </v-row>

                <v-row align="center">
                    <v-col cols="12" sm="6">
                        <v-btn @click="guardar_datos">
                            Guardar
                        </v-btn>
                    </v-col>
                </v-row>

            </v-form>

        </v-card>
        <!--:datos_cita="fechas"-->
        <!--<barrasu/>-->
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import Welcome from '@/Jetstream/Welcome'
import Barrasu from '@/Pages/Micomponet/Barrasu'
import Agenda from '@/Pages/Micomponet/Agenda'


export default {
    data() {
        return {
            datos: {
                feriados: {},
                dias: {}

            },
            items: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"],
            value: ["dom", "lun", "mar", "mie", "jue", "vie", "sab"],
            dialog: false,
            dialogDelete: false,
            headers: [
                {
                    text: 'Fecha',
                    align: 'start',
                    sortable: true,
                    value: 'fecha',
                },
                {
                    text: 'Titulo',
                    align: 'start',
                    sortable: true,
                    value: 'titulo',
                },
                { text: 'Accion', value: 'actions', sortable: false },
            ],
            desserts: [],
            editedIndex: -1,
            editedItem: {

            },
            defaultItem: {

            },
        }
    },
    props: {
        // fechas: Array,
    },
    components: {
        AppLayout,
        Welcome,
        Barrasu,
        Agenda,
    },
    watch: {
        dialog(val) {
            val || this.close()
        },
        dialogDelete(val) {
            val || this.closeDelete()
        },
    },
    methods: {
        menuItems() {
            return this.menu
        },
        async initialize() {
            await this.$store.dispatch('pedirConfig')
            this.datos.dias = await JSON.parse(this.$store.getters.getConfig.dias);
            this.desserts = await JSON.parse(this.$store.getters.getConfig.feriados);
        },
        async guardar_datos() {
            this.datos.feriados = this.desserts;
            this.datos.lugar = 'utraid'
            var res = await axios({
                method: 'put',
                url: 'configs/' + this.datos,
                data: this.datos,

            }).then();
            console.log('-----')
            console.log(res)

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
    },
    created() {
        this.initialize()

    },



}
</script>
