<template>
    <!--<welcome />-->
    <app-layout>
        <v-row align="center" justify="center">
            <!-- Contenido dentro del v-row -->
            <v-col>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-btn v-on="on" @click="showRow = !showRow">
                            <v-icon>{{ showRow ? 'mdi-window-minimize' : 'mdi-window-maximize' }}</v-icon>
                            Restauracion y Crearcion de Backup
                        </v-btn>
                    </template>
                    <span>{{ showRow ? 'Minimizar el contenido' : 'Maximizar el contenido' }}</span>
                </v-tooltip>
            </v-col>
        </v-row>
        <v-row v-if="showRow">
            <v-col>
                <v-card>
                    <v-card-text>
                        <h2 class="headline">Crear Backup</h2>
                        <p>
                            ¡Bienvenido a la sección de creación de backups! Aquí puedes generar una copia de seguridad de
                            tus datos.
                            Cuando hagas clic en el botón "Crear Backup", se actualizará la lista de backups disponibles.
                        </p>
                    </v-card-text>
                    <v-divider></v-divider>
                    <v-card-actions>
                        <v-tooltip top>
                            <template v-slot:activator="{ on }">
                                <v-btn class="ma-4" color="primary" dark v-on="on" @click="crear_backup">
                                    Crear Backup
                                    <v-icon>mdi-download</v-icon>
                                </v-btn>
                            </template>
                            <span>Crear una copia de seguridad</span>
                        </v-tooltip>
                    </v-card-actions>
                </v-card>
            </v-col>
            <v-col><v-card>
                    <v-card-text>
                        <h2 class="headline">Restauración de Backup</h2>
                        <p>
                            ¡Bienvenido a la restauración de backups! Aquí puedes restaurar una copia de seguridad de tus
                            datos utilizando un archivo zip.

                        </p>
                    </v-card-text>
                    <v-divider></v-divider>
                    <v-card-actions>
                        <v-form @submit.prevent="subir_archivo">
                            <p>Haga clic en el botón "Seleccionar Archivo", podrás buscar el archivo en tu
                                dispositivo
                                y seleccionarlo.</p>
                            <v-file-input v-model="file" label="Seleccionar archivo"></v-file-input>
                            <p> Luego, haz clic en el botón "Subir Archivo" para comenzar la restauración.</p>
                            <v-tooltip top>
                                <template v-slot:activator="{ on }">
                                    <v-btn type="submit" class="ma-2" color="primary" dark v-on="on" :disabled="!file">
                                        Subir Archivo
                                        <v-icon>mdi-upload</v-icon>
                                    </v-btn>
                                </template>
                                <span>Restaurar copia de seguridad</span>
                            </v-tooltip>
                        </v-form>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-divider></v-divider>

        <v-row align="center" justify="center">
            <!-- Contenido dentro del v-row -->
            <v-col>
                <v-btn-toggle>
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on }">
                            <v-btn v-on="on" @click="showRow2 = !showRow2">
                                <v-icon>{{ showRow2 ? 'mdi-window-minimize' : 'mdi-window-maximize' }}</v-icon>
                                Lista de Backup
                            </v-btn>
                        </template>
                        <span>{{ showRow2 ? 'Minimizar el contenido' : 'Maximizar el contenido' }}</span>
                    </v-tooltip>
                </v-btn-toggle>

            </v-col>
        </v-row>

        <v-divider></v-divider>
        <v-row v-if="showRow2">
            <v-col>
                <v-data-table :headers="headers" :items="lista_backup" class="elevation-1">
                    <template v-slot:item.actions="{ item }">
                        <v-tooltip top>
                            <template v-slot:activator="{ on }">
                                <v-btn class="ma-2" color="primary" dark v-on="on" @click="descargar_archivo(item)">
                                    <v-icon>
                                        mdi-download
                                    </v-icon>
                                </v-btn>
                            </template>
                            <span>Descargar Backup</span>
                        </v-tooltip>
                        <v-tooltip top>
                            <template v-slot:activator="{ on }">
                                <v-btn class="ma-2" color="primary" dark v-on="on" @click="eliminar_archivo(item)">
                                    <v-icon>
                                        mdi-delete
                                    </v-icon>
                                </v-btn>
                            </template>
                            <span>Eliminar Backup</span>
                        </v-tooltip>
                    </template>
                    <template v-slot:no-data>
                        <v-btn color="primary" @click="initialize">
                            Reset
                        </v-btn>
                    </template>
                </v-data-table>

            </v-col>
        </v-row>

        <v-dialog v-model="errorDialog" max-width="400">
            <v-card>
                <v-card-title class="error-dialog-title">
                    <v-icon color="error">mdi-alert</v-icon>
                    <span class="headline">¡Error!</span>
                </v-card-title>
                <v-card-text>
                    <v-row align="center" justify="center">
                        <v-col cols="12" class="text-center">
                            <v-icon x-large color="red">mdi-alert</v-icon>
                            <p class="error-dialog-message red--text text--darken-4 headline">
                                {{ errorMessage }}
                            </p>
                        </v-col>
                    </v-row>

                </v-card-text>
                <v-card-actions class="text-center">
                    <v-row>
                        <v-col class="text-center">
                            <v-btn color="primary" text @click="errorDialog = !errorDialog">Cerrar</v-btn>

                        </v-col>
                    </v-row>

                </v-card-actions>
            </v-card>
        </v-dialog>
    </app-layout>
    <!--:datos_cita="fechas"-->
    <!--<barrasu/>-->
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import Agenda from '@/Pages/Micomponet/Agenda2'
import axios from 'axios'


export default {
    data() {
        return {
            showRow: true,
            showRow2: true,
            errorMessage: '',
            errorDialog: false,
            search: '',
            tab: null,

            text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
            dark: true,
            lista_backup: [],
            file: null,
            headers: [
                {
                    text: 'Nombre de archivo',
                    align: 'left',
                    //sortable: false,
                    value: 'filename',
                    //filter: this.nameFilter,
                },
                {
                    text: 'Tamaño',
                    value: 'size',
                    //filter: this.caloriesFilter,
                },
                { text: 'Actions', value: 'actions', sortable: false },
            ]

        }
    },
    props: {
        //fecha_server: String,
    },
    components: {
        AppLayout,
        Agenda,
    },
    methods: {

        async initialize() {
            this.pedir_lista()
            //console.log('inicio')
            //console.log(this.fecha_server)
            //console.log(this.$store.state.fecha_server )
            this.$store.dispatch('guardarfechaserver', this.fecha_server)
            //console.log(this.$store.state.fecha_server)
        },
        async pedir_lista() {
            var res = await axios({
                method: 'get',
                url: `/${process.env.MIX_CARPETA}/backup_`,
            }).then(
                (response) => {
                    console.log(response);
                    console.log(response.data.lista);
                    this.lista_backup = response.data.lista
                }
            ).catch(err => {
                console.log(err)
                console.log("err->", err.response.data)
                return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
            });

        },
        async descargar_archivo(item) {


            const baseUrl = `/${process.env.MIX_CARPETA}/backup_`

            const link = `${baseUrl}/` + item.filename;
            console.log(link);
            axios.get(link, {
                params: {
                    archivo: item
                },
                responseType: 'blob'
            }).then(
                (response) => {
                    console.log(response);
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    // agregar un elemento <a> al DOM con el atributo download para permitir la descarga del archivo
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', item.filename);
                    document.body.appendChild(link);
                    // simular un clic en el elemento <a> para iniciar la descarga del archivo
                    link.click();

                }
            ).catch(err => {
                console.log(err)
                console.log("err->", err.response.data)
                return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
            });

        },

        async eliminar_archivo(item) {


            const baseUrl = `/${process.env.MIX_CARPETA}/backup_`

            const link = `${baseUrl}/` + item.filename;
            console.log(link);
            axios.delete(link, {
                params: {
                    archivo: item
                },
                //responseType: 'blob'
            }).then(
                (response) => {
                    console.log(response);
                    this.lista_backup = response.data.lista
                }
            ).catch(err => {
                console.log(err)
                console.log("err->", err.response.data)
                return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
            });

        },
        async crear_backup(item) {


            const baseUrl = `/${process.env.MIX_CARPETA}/backup_/create`

            const link = `${baseUrl}`// + item.filename;
            console.log(link);
            axios.get(link, {

                //responseType: 'blob'
            }).then(
                (response) => {
                    console.log(response);
                    this.lista_backup = response.data.lista
                }
            ).catch(err => {
                console.log(err)
                console.log("err->", err.response.data)
                return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
            });

        },
        async subir_archivo() {
            const baseUrl = `/${process.env.MIX_CARPETA}/backup_`
            const link = `${baseUrl}` ///+ item.filename;
            console.log(link);
            const formData = new FormData();
            formData.append('archivo', this.file);

            axios.post(link, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            }).then(
                (response) => {
                    console.log(response.data);
                    if (response.data.success) {
                        window.location.href = `/${process.env.MIX_CARPETA}/login`
                    }

                }
            ).catch(err => {
                //console.log(err)
                this.errorDialog = true
                this.errorMessage = err.response.data.error
                //console.log("err->", err.response.data.error)

                //return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
            });

        },


    },
    created() {
        this.initialize()

    },



}
</script>
