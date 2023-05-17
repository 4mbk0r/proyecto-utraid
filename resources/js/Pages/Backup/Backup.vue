<template>
    <!--<welcome />-->
    <app-layout>
        <v-row>
            <v-col>
                <v-btn color="blue-grey" class="ma-2 white--text" @click="crear_backup()">
                    Crear Backup
                    <v-icon right dark>
                        mdi-cloud-upload
                    </v-icon>
                </v-btn>
            </v-col>
            <v-col>
                <v-form @submit.prevent="subir_archivo">
                    <v-file-input v-model="file" label="Seleccionar archivo"></v-file-input>
                    <v-btn type="submit">Enviar</v-btn>
                </v-form>
            </v-col>
        </v-row>
        <v-data-table :headers="headers" :items="lista_backup" class="elevation-1">
            <template v-slot:item.actions="{ item }">
                <v-icon small class="mr-2" @click="descargar_archivo(item)">
                    mdi-pencil
                </v-icon>
                <v-icon small @click="eliminar_archivo(item)">
                    mdi-delete
                </v-icon>
            </template>
            <template v-slot:no-data>
                <v-btn color="primary" @click="initialize">
                    Reset
                </v-btn>
            </template>
        </v-data-table>
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
                    console.log(response);

                }
            ).catch(err => {
                console.log(err)
                console.log("err->", err.response.data)
                return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
            });

        },


    },
    created() {
        this.initialize()

    },



}
</script>
