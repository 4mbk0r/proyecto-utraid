<template>
    <v-dialog v-model="dialog" width="600px">

        <v-card>
            <v-card-title>
                <span class="text-h5">Error</span>
            </v-card-title>
            <v-card-text>
                <v-row class="justify-center">
                    <v-col >
                        {{ mensaje }}
                
                    </v-col>
                </v-row>
                <v-row  class="justify-center">
                    <v-col class="justify-center">
                        <v-icon color="red" size="50">mdi-alert-circle-outline</v-icon>
                    </v-col>

                </v-row>
                
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="green darken-1" text @click="dialog = false">
                    Aceptar
                </v-btn>
                <!--
                <v-btn color="green darken-1" text @click="dialog = false">
                    Agree
                </v-btn>
            -->
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    props: {
        mensaje: String
    },
    data() {
        return {
            dialog: false,
        }
    },
}
</script>


