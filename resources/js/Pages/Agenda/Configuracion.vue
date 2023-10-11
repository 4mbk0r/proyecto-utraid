<template>
        
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
9
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
