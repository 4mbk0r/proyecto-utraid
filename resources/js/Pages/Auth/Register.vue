<template>
    <app-layout>
        <v-card class="pa-2 justify-center">

            <jet-validation-errors />

            <v-form ref="form" @submit.prevent="submit">
                <v-container>
                    <v-row>

                        <v-text-field id="Nombres" label="Nombres" type="text" class="mt-1 block w-full"
                            v-model="form.name" :rules="[v => !!v || 'Se requiere el Nombre']" required autofocus autocomplete="name" />
                    </v-row>

                    <v-row>

                        <v-text-field id="email" label="Email" type="email" class="mt-1 block w-full"
                            v-model="form.email" :rules="[v => !!v || 'Se requiere el Email']" required />
                    </v-row>

                    <v-row class="mt-4">

                        <v-text-field id="password" label="Contraseña" type="password" class="mt-1 block w-full"
                            v-model="form.password" :rules="[v => !!v || 'Se requiere el Contraseña']" required autocomplete="new-password" />
                    </v-row>

                    <v-row class="mt-4">
                        <v-text-field id="password_confirmation" label="Repita Contraseña" type="password"
                            class="mt-1 block w-full" :rules="[v => !!v || 'Se requiere el Contraseña']" v-model="form.password_confirmation" required
                            autocomplete="new-password" />
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
                            Register
                        </v-btn>
                    </v-row>
                </v-container>
            </v-form>
        </v-card>
    </app-layout>
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


export default {
    data() {
        return {
            tab: null,
            items: [
                'agendar', 'atender',
            ],
            text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
            dark: true,
        }
    },
    props: {

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

    },
    data() {
        return {
            form: this.$inertia.form({
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
                terms: false,
            })
        }
    },

    methods: {
        submit() {

            if(this.$refs.form.validate())

            this.form.post(this.route('register'), {
                onFinish: () => this.form.reset('password', 'password_confirmation'),
            })
        }
    }

}
</script>
    


