<template>
    <app-layout>

        <v-card class="pa-2 justify-center" outlined tile>
            <jet-validation-errors />
            <v-alert v-if="status" border="bottom" colored-border type="warning" elevation="2">
                {{ status }}
            </v-alert>

            <v-row align="center" class="pa-4" justify="space-around">

                <v-img max-height="80" max-width="150" src="./assets/logo-sedes-lapaz.png"></v-img>

            </v-row>
            <v-form @submit.prevent="submit">
                <v-container>
                    <v-text-field label="Nombre de usuario" id="email" type="text" v-model="form.username" required
                        autofocus />
                    <v-text-field id="password" type="password" label="Contraseña" v-model="form.password" required />


                    <v-row align="center" justify="space-around">
                        <!--<inertia-link v-if="canResetPassword" :href="route('password.request')"
                        class="underline text-sm text-gray-600 hover:text-gray-900">
                        Forgot your password?
                    </inertia-link>-->
                        <label class="flex items-center">
                            <jet-checkbox name="remember" v-model="form.remember" />
                            <span class="ml-2 text-sm text-gray-600">Recordar</span>
                        </label>
                        <v-btn type="submit" depressed color="primary" absolute:disabled="form.processing">
                            Login
                        </v-btn>
                    </v-row>
                </v-container>
            </v-form>
        </v-card>
    </app-layout>
</template>

<script>
import JetAuthenticationCard from '@/Jetstream/AuthenticationCard'
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo'
import JetButton from '@/Jetstream/Button'
import JetInput from '@/Jetstream/Input'
import JetCheckbox from '@/Jetstream/Checkbox'
import JetLabel from '@/Jetstream/Label'
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import WelcomeVue from '../Welcome.vue'
import AppLayout from '@/Layouts/AppLayout.vue'

export default {
    components: {
        JetAuthenticationCard,
        JetAuthenticationCardLogo,
        JetButton,
        JetInput,
        JetCheckbox,
        JetLabel,
        JetValidationErrors,
        WelcomeVue,
        AppLayout
    },

    props: {
        canResetPassword: Boolean,
        status: String
    },

    data() {
        return {
            form: this.$inertia.form({
                username: '',
                password: '',
                remember: false
            })
        }
    },

    methods: {
        submit() {
            this.form
                .transform(data => ({
                    ...data,
                    remember: this.form.remember ? 'on' : ''
                }))
                .post(this.route('login'), {
                    onFinish: () => this.form.reset('password'),
                })
        }
    }
}
</script>
