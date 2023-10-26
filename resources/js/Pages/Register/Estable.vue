 <template>
    <v-container>

        <!--<welcome />-->
        <v-row>
            <v-col>
                <v-btn color="primary" @click="agregarItem()">
                    <v-icon>mdi-plus</v-icon>
                    Agregar
                </v-btn>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <v-data-table :headers="headers" :footer-props="{ itemsPerPageText: 'Personal' }" :items="desserts"
                    :items-per-page="totalItems" sort-by="nombre" class="elevation-1 blue lighten-3">
                    <template v-slot:[`header({props})`]="{ props }">
                        <span>{{ props.header.text }}</span>
                        <v-icon small @click="sortColumn(props.header.value)">mdi-arrow-up-down</v-icon>
                    </template>
                    <template v-slot:item.actions="{ item }">
                        <v-icon small class="mr-2" @click="edit_item(item)">
                            mdi-pencil
                        </v-icon>
                        <v-icon small @click="confirmacion_item(item)">
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

            </v-col>

        </v-row>
        <!--:datos_cita="fechas"-->
        <!--<barrasu/>-->
        <v-dialog v-model="dialog_agregar" transition="dialog-bottom-transition">
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click="cerrar_agregar()">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Agregar Cargo</v-toolbar-title>
                    <v-spacer></v-spacer>

                </v-toolbar>
                <v-card-text>
                    <v-form ref="form">
                        <v-row>
                            <v-col>
                                <v-text-field v-model="form.nombre" label="Ingrese nombre del cargo"
                                    :rules="nameRules"></v-text-field>
                            </v-col>
                        </v-row>
                        <v-card-actions>
                            <v-btn v-if="item_update" @click="updateCargo()" color="primary">Actualizar</v-btn>
                            <v-btn v-if="!item_update" @click="crear_cargo()" color="primary">Crear</v-btn>
                        </v-card-actions>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-dialog v-model="showDialog" max-width="400px">
            <v-card>
                <v-alert type="error">
                    Ha ocurrido un error al procesar los datos.
                </v-alert>
                <v-card-text>
                    {{ mensaje_error }}
                </v-card-text>

                <v-card-actions>
                    <v-btn color="primary" @click="dismissDialog">OK</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="showDialogSuccess" max-width="400px">
            <v-card>
                <v-card-title>Todo Correcto</v-card-title>

                <v-card-text>
                    ¡Los datos se procesaron correctamente!
                </v-card-text>

                <v-card-actions>
                    <v-btn color="primary" @click="dismissDialogSucess">OK</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="eliminarConfirmado" max-width="400px">
            <v-card>
                <v-card-title>Confirmación de eliminación</v-card-title>
                <v-card-text>¿Estás seguro de que deseas eliminar este elemento
                    {{ item_edit.nombre }}
                    ?</v-card-text>
                <v-card-actions>
                    <!-- Botón para confirmar la eliminación -->
                    <v-btn color="red" text @click="eliminar_item()">Confirmar</v-btn>
                    <!-- Botón para cancelar la eliminación -->
                    <v-btn text @click="cancelar_eliminar()">Cancelar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import Agenda from '@/Pages/Micomponet/Agenda2'


export default {
    data() {
        return {
            nameRules: [
                v => !!v || 'El nombre del Cargo',
                v => (v && v.length > 2) || 'El nombre debe tener al menos 2 caracteres',
            ],
            desserts: [],
            totalItems: 0,
            dialog_agregar: false,
            form: {
                nombre: '',

            },
            showDialog: false,
            showDialogSuccess: false,
            mensaje_error: '',
            eliminarConfirmado: false,
            item_edit: {},
            item_update: false
        }
    },
    props: {
        //fecha_server: String,
    },
    components: {
        //AppLayout,
        //Agenda,
    },
    computed: {
        update(validaste) {

        },

        headers() {
            return [{
                text: 'Nombre de cargo',
                align: 'start',
                //sortable: false,
                value: 'nombre',
            },
            { text: 'Acciones', value: 'actions', sortable: false },
            ];
        },
    },
    methods: {
        edit_item(item){
            this.item_edit = structuredClone(item)
            this.form = structuredClone(item)
            this.item_update = true
            this.dialog_agregar = true
        },
        cancelar_eliminar(){
            this.eliminarConfirmado =  false
            //delete this.item_edit
            
        },
        async eliminar_item() {
            var res = await this.axios({
                method: 'delete',
                url: `/${process.env.MIX_CARPETA}/establecimiento/` + JSON.stringify(this.item_edit),
            }).then(
                (response) => {
                    //this.headers = response.data
                    //console.log('Responder');
                    console.log(response.data)
                    this.showDialogSuccess = true
                    this.cerrar_agregar()
                    this.get_cargos()
                    this.eliminarConfirmado = false
                    //this.desserts = response.data
                    //this.totalItems = this.desserts.length;

                }).catch((error) => {
                    this.showDialog = true
                    this.mensaje_error = error.data
                    console.log(error.data);

                });

        },
        confirmacion_item(item) {
            this.item_edit = structuredClone(item)
            this.eliminarConfirmado = true
        },
        dismissDialogSucess() {
            this.showDialogSuccess = false;
        },
        dismissDialog() {
            this.showDialog = false;
        },
        async updateCargo() {
            if (!this.$refs.form.validate()) {
                // Lógica para enviar el formulario si pasa la validación
                //this.showDialog = true
                //this.mensaje_error = error.data       
                return;
            }
            var res = await this.axios({
                method: 'put',
                url: `/${process.env.MIX_CARPETA}/establecimiento/`+this.form.nombre,
                data: {
                    formulario: this.form,
                    anterior: this.item_edit
                },
            }).then(
                (response) => {
                    //this.headers = response.data
                    //console.log('Responder');
                    console.log(response.data)
                    this.showDialogSuccess = true
                    this.cerrar_agregar()
                    this.get_cargos()
                    //this.desserts = response.data
                    //this.totalItems = this.desserts.length;

                }).catch((error) => {
                    console.log(error   )
                    this.showDialog = true
                    this.mensaje_error = error.data
                    console.log(error.data);

                });

            //this.cerrar_agregar()
        },
        async crear_cargo() {
            if (!this.$refs.form.validate()) {
                // Lógica para enviar el formulario si pasa la validación
                //this.showDialog = true
                //this.mensaje_error = error.data       
                return;
            }
            var res = await this.axios({
                method: 'post',
                url: `/${process.env.MIX_CARPETA}/establecimiento`,
                data: {
                    formulario: this.form
                },
            }).then(
                (response) => {
                    //this.headers = response.data
                    //console.log('Responder');
                    console.log(response.data)
                    this.showDialogSuccess = true
                    this.cerrar_agregar()
                    
                    this.get_cargos()
                    
                    //this.desserts = response.data
                    //this.totalItems = this.desserts.length;

                }).catch((error) => {
                    this.showDialog = true
                    this.mensaje_error = error.data
                    console.log(error.data);

                });

            //this.cerrar_agregar()
        },
        cerrar_agregar() {
            this.dialog_agregar = false
            this.item_update = false
            this.form = {}
            this.item_edit = {}
        },
        menuItems() {
            return this.menu
        },
        async initialize() {
            this.get_cargos()
            //console.log('inicio')
            //console.log(this.fecha_server)
            //console.log(this.$store.state.fecha_server )
            //this.$store.dispatch('guardarfechaserver', this.fecha_server)
            //console.log(this.$store.state.fecha_server)
        },
        async get_cargos() {
            var res = await this.axios({
                method: 'get',
                url: `/${process.env.MIX_CARPETA}/establecimiento`,

            }).then(
                (response) => {
                    //this.headers = response.data
                    //console.log('Responder');
                    console.log(response.data)
                    this.desserts = response.data
                    this.totalItems = this.desserts.length;

                }).catch((error) => {
                    console.log(error.data);

                });
        },
        agregarItem() {
            this.dialog_agregar = true
        }
    },
    created() {
        this.initialize()
    }
}

</script>
