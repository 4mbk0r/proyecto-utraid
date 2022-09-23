<template>
    <v-app>
        <v-row>
            <v-col cols="12" sm="6">
                <v-date-picker v-model="dates" range></v-date-picker>
            </v-col>
            <v-col cols="12" sm="6">
                <v-menu ref="menu" v-model="menu" :close-on-content-click="false" :return-value.sync="dates"
                    transition="scale-transition" offset-y min-width="auto">
                    <template v-slot:activator="{ on, attrs }">
                        <v-combobox v-model="dates" multiple chips small-chips label="Multiple picker in menu"
                            prepend-icon="mdi-calendar" readonly v-bind="attrs" v-on="on"></v-combobox>
                    </template>
                    <v-date-picker v-model="dates" @input="probar" multiple no-title scrollable>
                        <v-spacer></v-spacer>
                        <v-btn text color="primary" @click="menu = false">
                            Cancel
                        </v-btn>
                        <v-btn text color="primary" @click="$refs.menu.save(dates)">
                            OK
                        </v-btn>
                    </v-date-picker>
                </v-menu>
            </v-col>
        </v-row>
        <v-btn @click="posts()">
            pedir
        </v-btn>
    </v-app>
</template>
    
<script>
import AppLayout from '@/Layouts/AppLayout'
import Welcome from '@/Jetstream/Welcome'
import Barrasu from '@/Pages/Micomponet/Barrasu'
import axios from 'axios'
import moment from 'moment'
import shortOut from 'lodash/_shortOut'

export default {
    data() {
        return {
            drawer: null,
            items: [{
                title: 'Home',
                icon: 'mdi-view-dashboard'
            },
            {
                title: 'About',
                icon: 'mdi-forum'
            },
            ],
            cita: {},
            range: [],
            multiple: [],
            dates: [],
            config: [],
            menu: false,

        }
    },
    created() {
        this.cita = JSON.parse(localStorage.getItem('cita'))
        console.log(typeof this.cita)
    },
    components: {
        AppLayout,
        Welcome,
        Barrasu,
    },
    methods:
    {
        async posts() {
            this.dates.sort()
            var inicioserie = moment('9999-01-01');
            var serie = []
            for (const key in this.dates) {
                var f = this.dates[key];
                if (key == 0) {
                    serie.push(f)
                } else {

                    console.log(f + "  " + inicioserie.format('YYYY-MM-DD'))
                    if (f == inicioserie.format('YYYY-MM-DD').toString()) {
                        serie.push(f)

                    } else {
                        this.config.push(serie)
                        serie = []
                        serie.push(f)
                    }

                }
                if (key == this.dates.length - 1) {
                    this.config.push(serie)
                }
                inicioserie = moment(f).add(1, 'days');



            }
            console.log(this.config)
            var res = await axios({
                method: 'post',
                url: 'configurar/',
                data: {
                    cita: this.dates,
                }
            }).then();
            console.log(res);
            this.config = []
        },
        probar() {
            alert(this.dates[this.dates.length - 1])
            var ult = this.dates[this.dates.length - 1]
            for (x in this.dates) {
                var f = this.dates[x]

            }
        }

    }

}
</script>
    