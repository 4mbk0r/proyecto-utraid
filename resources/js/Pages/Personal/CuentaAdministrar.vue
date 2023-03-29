<template>

    <v-card class="pa-2 justify-center">


        <v-form ref="form" @submit.prevent="submit" class="elevation-5 rounded-lg px-5 py-7">
            <v-card-title class="justify-center">
                <h1>Datos Personales </h1>
            </v-card-title>

            <v-row style="background-color: #00a5a0">

                <v-col cols="12" style="background-color: #a2315a">

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
                    <v-text-field dense outlined id="item" label="Item" type="text" v-model="form.item"
                        :rules="[v => !!v || 'Se requiere completar Item']" required class="mb-n4 pa-0"
                        prepend-inner-icon="mdi-account-key" />
                </v-col>
            </v-row>
            <v-row>
                <!--<v-text-field dense outlined 
                        item-value="cargo" 
                        item-text="cargo"
                        :items="cargos" 
                        v-model="form.cargo"
                        :rules="[v => !!v || 'Se requiere el Cargo']" filled label="Asigne un cargo" class="mb-n4 pa-0"
                        required prepend-inner-icon="mdi-clipboard-account">
                    </v-text-field>
                    -->
                <v-select v-if="admin == true" dense outlined item-value="cargo" item-text="cargo" :items="cargos"
                    v-model="form.cargo" :rules="[v => !!v || 'Se requiere el Cargo']" filled label="Asigne un cargo"
                    class="mb-n4 pa-0" required prepend-inner-icon="mdi-clipboard-account">
                </v-select>
                <v-text-field v-else dense outlined v-model="form.cargo" :rules="[v => !!v || 'Se requiere el Cargo']"
                    filled label="Asigne un cargo" class="mb-n4 pa-0" required
                    prepend-inner-icon="mdi-clipboard-account">
                </v-text-field>
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
            <v-row>
                <v-col>
                    <!--<inertia-link :href="route('login')"
                            class="underline text-sm text-gray-600 hover:text-gray-900">
                            Already registered?
                        </inertia-link>
                        -->
                    <v-btn class="ml-4" color="primary" :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing" @click="updateCargo">
                        Guardar Datos de perfil
                    </v-btn>
                </v-col>
                <v-col>
                    <!--<inertia-link :href="route('login')"
                            class="underline text-sm text-gray-600 hover:text-gray-900">
                            Already registered?
                        </inertia-link>
                        -->
                    <v-btn class="ml-4" color="primary" type="sumbit" :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="change_password"
                        >
                        Cambiar Contraseña
                    </v-btn>
                </v-col>
            </v-row>
        </v-form>
        <cambiar_password v-if="dialog_cambio" @close="close_dialog($event)" :paciente="form"></cambiar_password>
    </v-card>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import Cambiar_password from './Cambiar_password.vue'
/*
import JetAuthenticationCard from '@/Jetstream/AuthenticationCard'
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo'
import JetButton from '@/Jetstream/Button'
import JetInput from '@/Jetstream/Input'
import JetCheckbox from "@/Jetstream/Checkbox";
import JetLabel from '@/Jetstream/Label'
import JetValidationErrors from '@/Jetstream/ValidationErrors'
*/
//import Cuenta from '@/Pages/Personal/CuentaAdministrar'

export default {
    props: {
        form: Object,
        admin: Boolean
        
    },
    data() {
        return {
            cargos: ['assa'],
            form_edit: {}, 
            dialog_cambio: false
            //roles: [] 
            //['Admin', 'Medico General', 'Trabajo Social', 'Operador Terapético', 'Psicologo', 'Psicologo', 'Secretaria', 'recepcionista'],
        }
    },
    mounted() {
        //this.form =  this.$page.props.user
        if (this.admin) {
            this.perdir_cargos()
        }
        this.form_edit = structuredClone(this.form)
    },
    components: {
        AppLayout,
        Cambiar_password


    },
    created() {
        //console.log(cargos)
    },
    computed: {
        update(validate) {

        }

    },
    watch: {

    },
    methods: {
        async perdir_cargos() {
            try {

                var res = await axios({
                    method: "get",
                    url: `/${process.env.MIX_CARPETA}/cargo`,

                }).then(
                    (response) => {
                        console.log(response);
                        this.cargos = response.data
                    },
                    (error) => {
                        console.log(error);
                    }
                );
            } catch (err) {
                console.log("err->", err.response.data);
                return res
                    .status(500)
                    .send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
            }
        },
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
                console.log(this.form.username)
                this.form.post(this.route('register'), {
                    onFinish: () => this.form.reset('password', 'password_confirmation'),
                    onSuccess: () => {
                        this.form = this.$inertia.form({
                            nombre: '',
                            ap_paterno: '',
                            ap_materno: '',
                            ci: '',
                            item: '',
                            cargo: '',
                            email: '',
                            celular: '',
                            username: '',
                            password: '',
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
        async updateCargo() {
            console.log(this.form_edit.ci)
            this.form.username = this.form.ci
            try {

                var res = await axios({
                    method: 'put',
                    url: `/${process.env.MIX_CARPETA}/personal/`+this.form_edit.ci,
                    data: {
                        usuario: this.form,
                        //usuario_inicio: this.form_edit
                    }

                }).then(
                    (response) => {
                        console.log(response);
                        //this.cargos = response.data
                        this.form_edit = structuredClone(this.form)
                    },
                    (error) => {
                        console.log(error);
                    }
                );
            } catch (err) {
                console.log("err->", err.response.data);
                return res
                    .status(500)
                    .send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
            }
        },
        close_dialog($e) {
            this.dialog_cambio = false
        },
        async change_password() {
            this.dialog_cambio = true
        }
    }

}
</script>
    


