<template>
    <!--<app-layout>-->
    <v-card class="pa-2 justify-center">


        <jet-validation-errors />
        <v-form ref="form" @submit.prevent="submit" class="elevation-5 rounded-lg px-5 py-7">
            <v-row class="my-2">
                <v-card-title class="justify-center">
                    Registrar Usuarios
                </v-card-title>
                <v-divider vertical></v-divider>
                <v-btn color="success" @click="excel_dialog = true">
                    <v-icon>mdi-account-card-outline</v-icon>
                </v-btn>
            </v-row>
            <v-row style="background-color: #00a5a0">
                <v-col cols="8">
                    <div class="headline">Datos Personales</div>
                </v-col>
                <v-col cols="4" style="background-color: #a2315a">

                    <v-img contain max-height="40" max-width="100%" class="ml-auto" src="./assets/logo-sedes-lapaz.png">
                    </v-img>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-text-field dense outlined id="nombre" label="Nombres" type="text" v-model="form.nombre"
                        :rules="[v => !!v || ' Se requiere completar Nombre']" required autofocus
                        prepend-inner-icon="mdi-account-arrow-right-outline" class="mb-n5 pa-0"
                        @input="(val) => (form.nombre = val.toUpperCase())" pattern="[a-zA-Z]+" />
                </v-col>
                <v-col>
                    <v-text-field dense outlined id="paterno" label="Apellido Paterno" type="text"
                        v-model="form.ap_paterno" :rules="[v => !!v || 'Se requiere Completar Apellido Paterno']"
                        prepend-inner-icon="mdi-account-arrow-right-outline" required class="mb-n5 pa-0"
                        @input="(val) => (form.ap_paterno = val.toUpperCase())" pattern="[a-zA-Z]+" />
                </v-col>
                <v-col>
                    <v-text-field dense outlined id="materno" label="Apellido Materno" type="text"
                        v-model="form.ap_materno" :rules="[v => !!v || 'Se requiere Completar Apellido Materno']"
                        @input="(val) => (form.ap_materno = val.toUpperCase())"
                        prepend-inner-icon="mdi-account-arrow-right-outline" required class="mb-n5 pa-0"
                        pattern="[a-zA-Z]+" />
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-text-field dense outlined id="CI" label="Cedula de Identidad" type="number" v-model="form.ci"
                        :rules="[v => !!v || 'Se requiere completar Cedula de Identidad']" required class="mb-n4 pa-0"
                        prepend-inner-icon="mdi-card-account-details-outline" />
                </v-col>
                <v-col>
                    <!---<v-text-field dense outlined id="item" label="Item" type="text" v-model="form.item"
                        :rules="[v => !!v || 'Se requiere completar Item']" required class="mb-n4 pa-0"
                        prepend-inner-icon="mdi-account-key" />
                    --->
                    <v-select dense outlined item-value="codigo_ine" item-text="departamento" :items="departamentos" v-model="form.expedido"
                    :rules="[v => !!v || 'Se requiere seleccionar departamento expedicion']" filled label="Seleccione departemento de expacion" class="mb-n4 pa-0"
                    required prepend-inner-icon="mdi-clipboard-account">
                </v-select>
                </v-col>
            </v-row>
            <v-row>
                <v-select dense outlined item-value="cargo" item-text="cargo" :items="cargos" v-model="form.cargo"
                    :rules="[v => !!v || 'Se requiere el Cargo']" filled label="Asigne un cargo" class="mb-n4 pa-0"
                    required prepend-inner-icon="mdi-clipboard-account">
                </v-select>
            </v-row>
            <v-row>
                <!---dense outlined-->
                <v-select item-value="id" item-text="nombre" :items="establecimiento" v-model="form.establecimiento"
                    :rules="[v => !!v || 'Se requiere el establecimierto que pertenece']" filled
                    label="Seleccione la Estableciemiento de trabajo" class="mb-n4 pa-0" required
                    prepend-inner-icon="mdi-clipboard-account">
                </v-select>
            </v-row>
            <v-row>
                <v-col>
                    <v-text-field dense outlined id="email" label="Email" type="email" v-model="form.email"
                        class="mb-n4 pa-0" prepend-inner-icon="mdi-email">
                    </v-text-field>
                </v-col>
                <v-col>
                    <v-text-field dense outlined id="celular" label="Celular" type="number" class="mb-n4 pa-0"
                        v-model="form.celular" prepend-inner-icon="mdi-cellphone" />
                </v-col>
            </v-row>



            <!--
                <div class="mt-4" v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature">
                    <jet-label for="terms">
                        <div >
                            <jet-checkbox name="terms" id="terms" v-model="form.terms" />
                            <div class="ml-2">
                                I agree to the <a target="_blank" :href="route('terms.show')"
                                    class="underline text-sm text-gray-600 hover:text-gray-900">Terms of Service</a> and
                                <a target="_blank" :href="route('policy.show')"
                                    class="underline text-sm text-gray-600 hover:text-gray-900">Privacy Policy</a>
                            </div>
                        </div>
                    </jet-label>
                </div>
                -->
            <v-row class="flex items-center justify-center mt-4">
                <!--<inertia-link :href="route('login')"
                            class="underline text-sm text-gray-600 hover:text-gray-900">
                            Already registered?
                        </inertia-link>
                        -->
                <v-btn class="ml-4" color="primary" type="sumbit" :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing">
                    Registrar
                </v-btn>
            </v-row>

        </v-form>

        <v-dialog v-model="excel_dialog" fullscreen hide-overlay transition="excel_dialog-bottom-transition">

            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click="excel_dialog = false">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Settings</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn dark text @click="excel_dialog = false">
                            Save
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <excel @guardar_datos="save($event)">

                </excel>
            </v-card>
        </v-dialog>
    </v-card>
    <!--</app-layout>-->
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import JetAuthenticationCard from '@/Jetstream/AuthenticationCard'
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo'
import JetButton from '@/Jetstream/Button'
import JetInput from '@/Jetstream/Input'
import JetCheckbox from "@/Jetstream/Checkbox";
import JetLabel from '@/Jetstream/Label'
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import Excel from '../../Pages/Micomponet/Excel.vue'


export default {
    props: {
        cargos: Array,
        establecimiento: Array,
        departamentos: Array,
    },
    data() {
        return {
            notifications: false,
            sound: true,
            widgets: false,
            excel_dialog: false,
            form: this.$inertia.form({
                nombre: '',
                ap_paterno: '',
                ap_materno: '',
                ci: '',
                expedido: '',
                cargo: '',
                email: '',
                celular: '',
                username: '',
                password: '',
                password_confirmation: '',
                establecimiento: '',
                terms: false,

            }),
            //roles: [] 
            //['Admin', 'Medico General', 'Trabajo Social', 'Operador Terapético', 'Psicologo', 'Psicologo', 'Secretaria', 'recepcionista'],
        }
    },
    components: {
        AppLayout,
        JetAuthenticationCard,
        JetAuthenticationCardLogo,
        JetButton,
        JetInput,
        JetCheckbox,
        JetLabel,
        JetValidationErrors,
        Excel,

    },
    created() {
        console.log(this.cargos)
        console.log(this.establecimiento)
        console.log(this.departamentos);
    },
    computed: {
        update(validate) {

        }

    },
    watch: {

    },
    methods: {
        alert(text) {
            this.$alert(text).then(res => this.$inform("Cambios guardados!"));
        },
        submit() {
            this.form.nombre = this.form.nombre.trimStart().toUpperCase()
            this.form.ap_materno = this.form.ap_materno.trimStart().toUpperCase()
            this.form.ap_paterno = this.form.ap_paterno.trimStart().toUpperCase()
            if (this.$refs.form.validate()) {
                this.form.username = this.form.ci
                this.form.password = this.form.ci + this.form.nombre[0] + this.form.ap_paterno[0] + this.form.ap_materno[0]
                this.form.password_confirmation = this.form.password
                console.log(this.form)
                this.form.post(this.route('register'), {
                    onFinish: () => this.form.reset('password', 'password_confirmation'),
                    onSuccess: () => {
                        this.form = this.$inertia.form({
                            nombre: '',
                            ap_paterno: '',
                            ap_materno: '',
                            ci: '',
                            expedido: '',
                            cargo: '',
                            email: '',
                            celular: '',
                            username: '',
                            password: '',
                            establecimiento: '',
                            password_confirmation: '',
                            terms: false,
                        }),
                        this.alert('Se registro de forma correcta')
                        this.$refs.form.resetValidation()
                        //this.$inertia.get(route('regitrar'))

                    },
                })
            }
        },
        async save($event) {
            //console.log($event);
            
            var res = await this.axios({
                method: 'post',
                url: `/${process.env.MIX_CARPETA}/api/subir_personal`,
                data: {
                    datos: $event.datos,
                    header: $event.header
                },

            }).then(
                (response) => {
                    //this.headers = response.data
                    //console.log('Responder');
                    console.log(response.data)
                    
                }).catch((error) => {
                    console.log(error.data);

                });
        }
    }

}
</script>
    


