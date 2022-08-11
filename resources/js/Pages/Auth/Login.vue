<template>
    <WelcomeVue>
        

            <v-card class="pa-2 justify-center"  outlined tile>
                <jet-validation-errors />
                <v-alert v-if="status" border="bottom" colored-border type="warning" elevation="2">
                    {{ status }}
                </v-alert>
            

           
                    <form @submit.prevent="submit">
                        <div  class="justify-center">
                            <jet-label for="email" value="Email" />
                            <jet-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                                autofocus />
                        </div>

                        <div  class="justify-center">
                            <jet-label for="password" value="Password" />
                            <jet-input id="password" type="password" class="mt-1 block w-full" v-model="form.password"
                                required autocomplete="current-password" />
                        </div>

                        <div  class="justify-center">
                            <label class="flex items-center">
                                <jet-checkbox name="remember" v-model="form.remember" />
                                <span class="ml-2 text-sm text-gray-600">Remember me</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <inertia-link v-if="canResetPassword" :href="route('password.request')"
                                class="underline text-sm text-gray-600 hover:text-gray-900">
                                Forgot your password?
                            </inertia-link>

                            <jet-button class="ml-4" :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing">
                                Login
                            </jet-button>
                        </div>
                    </form>
           </v-card>
       
    </WelcomeVue>
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

export default {
    components: {
        JetAuthenticationCard,
        JetAuthenticationCardLogo,
        JetButton,
        JetInput,
        JetCheckbox,
        JetLabel,
        JetValidationErrors,
        WelcomeVue
    },

    props: {
        canResetPassword: Boolean,
        status: String
    },

    data() {
        return {
            form: this.$inertia.form({
                email: '',
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
