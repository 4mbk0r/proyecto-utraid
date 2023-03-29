<template>
    <v-dialog v-model="dialog">
        <v-card>
            <v-card-title>
                Cambiar Contraseña

            </v-card-title>
            <v-card-text>
                <v-form ref="nuevo_password">
                    <v-row>
                        <v-col>
                            <v-text-field dense outlined label="Nuevo Password" v-model="dato.password" type="password"
                                :rules="passwordRules" required class="mb-n4 pa-0" prepend-inner-icon="mdi-account-key" />
                        </v-col>

                    </v-row>
                    <v-row>
                        <v-col>
                            <v-text-field dense outlined label="Confirmar Password" type="password"
                                v-model="dato.conf_password" :rules="passwordRules" required class="mb-n4 pa-0"
                                prepend-inner-icon="mdi-account-key" />
                        </v-col>

                    </v-row>
                    <v-row>
                        <v-col>
                            <v-btn color="primary" variant="text" @click="cambiar_password()">
                                Cambiar contraseña
                            </v-btn>
                        </v-col>

                    </v-row>


                </v-form>
                <!--
                <v-row>
                    <v-col>
                        <v-btn color="success" variant="text" @click="password_default()">
                            Contraseña Predefinidas
                        </v-btn>

                    </v-col>

                </v-row>
                -->
                <v-spacer>

                </v-spacer>



            </v-card-text>
            <v-card-actions>

                <v-btn color="primary" variant="text" @click="close()">
                    Cerrar
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';
import { emit } from 'process';



export default {
    props: {
        paciente: Object,
    },
    data() {
        return {
            dato: {},
            dialog: true,

            passwordRules: [(v) => !!v || 'Se requiere Password', (v) => (v && v.length >= 8) || 'Se minimo 8 caracteres']
        }
    },
    mounted() {

    },
    components: {

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
        close() {
            this.$emit('close', false)
        },
        async cambiar_password() {

            if (this.$refs.nuevo_password.validate()) {
                if (this.dato.password != this.dato.conf_password) {

                    this.$alert('las contraseñas no son las mismas').then(res => this.$error("Cambiar contraseña!"));
                    return
                }
                var res = await axios({
                    method: 'post',
                    url: `/${process.env.MIX_CARPETA}/change_password`,
                    data: {
                        password: this.dato,
                        paciente: this.paciente
                    }
                }).then(
                    (response) => {
                        console.log(response);
                        if (response.data.redireccionar) {
                            alert('Se cambio la contraseña correctamente. Se cerra la sesión para verificar que este correctamente. ')
                            window.location.href = `/${process.env.MIX_CARPETA}/login`
                        }

                        this.$alert('Se a cambiado la contraseña correctamente.').then(res => this.$inform("Cambiar contraseña!"));
                    }
                ).catch(err => {
                    if (err.response.status == 401) {
                        window.location.href = `/${process.env.MIX_CARPETA}/login`

                    }
                    if (err.response.status == 419) {
                        window.location.href = `/${process.env.MIX_CARPETA}/login`

                    }
                    console.log(err)
                    console.log("err->", err.response.data)
                    this.$alert('Se fallo en cambio.').then(res => this.$err("Cambiar contraseña!"));
                    return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
                });
            }

        },
        async password_default() {

        }
    }

}
</script>
    


