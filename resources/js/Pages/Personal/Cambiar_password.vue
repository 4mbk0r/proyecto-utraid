<template>
    <v-dialog v-model="dialog">
        <v-card>
            <v-card-title>
                Cambiar Contraseña
            </v-card-title>
            <v-card-text>
                <v-form>
                    <v-row>
                        <v-col>
                            <v-text-field dense outlined label="Nuevo Password" type="text" v-model="dato.password"
                                :rules="[v => !!v || 'Se requiere Password']" required class="mb-n4 pa-0"
                                prepend-inner-icon="mdi-account-key" />
                        </v-col>

                    </v-row>
                    <v-row>
                        <v-col>
                            <v-text-field dense outlined label="Confirmar Password" type="text" v-model="dato.conf_password"
                                :rules="[v => !!v || 'Se requiere Confirmar Password']" required class="mb-n4 pa-0"
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
                <v-row>
                    <v-col>
                        <v-btn color="success" variant="text" @click="cambiar_password_default()">
                            Contraseña Predefinidas
                        </v-btn>

                    </v-col>

                </v-row>

                <v-spacer>

                </v-spacer>



            </v-card-text>
            <v-card-actions>

                <v-btn color="primary" variant="text" @click="close()">
                    Close
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import { emit } from 'process';



export default {
    props: {
    },
    data() {
        return {
            dato: {},
            dialog: true,
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
        }
    }

}
</script>
    


