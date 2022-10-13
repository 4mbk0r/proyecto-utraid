<template>
    <v-app>
        <v-row class="fill-height">
            <v-col>
                <v-sheet height="64">
                    <v-toolbar flat>
                        <v-btn outlined class="mr-4" color="grey darken-2" @click="setToday">
                            Hoy
                        </v-btn>
                        <v-btn fab text small color="grey darken-2" @click="prev">
                            <v-icon small>
                                mdi-chevron-left
                            </v-icon>
                        </v-btn>
                        <v-btn fab text small color="grey darken-2" @click="next">
                            <v-icon small>
                                mdi-chevron-right
                            </v-icon>
                        </v-btn>
                        <v-toolbar-title v-if="$refs.calendar">
                            {{ $refs.calendar.title }}
                        </v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-menu bottom right>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn outlined color="grey darken-2" v-bind="attrs" v-on="on">
                                    <span>{{ typeToLabel[type] }}</span>
                                    <v-icon right>
                                        mdi-menu-down
                                    </v-icon>
                                </v-btn>
                            </template>
                            <v-list>
                                <v-list-item @click="type = 'category'">
                                    <v-list-item-title>Dia</v-list-item-title>
                                </v-list-item>
                                <v-list-item @click="type = 'week'">
                                    <v-list-item-title>Semana</v-list-item-title>
                                </v-list-item>
                                <v-list-item @click="type = 'month'">
                                    <v-list-item-title>Mes</v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </v-toolbar>
                </v-sheet>
                <v-sheet height="600">
                    <v-calendar ref="calendar" v-model="fecha_calendario" color="primary" :events="events"
                        :categories="categories" :event-color="getEventColor" :type="type" @click:event="showEvent"
                        @click:more="viewDay" @click:date="viewDay" @change="updateRange" :max-days=5
                        weekdays="1, 2, 3, 4, 5" :weekday-format="getDay" :first-interval=7 :interval-minutes=60
                        :interval-count=12></v-calendar>
                    <v-menu v-model="selectedOpen" :close-on-content-click="false" :activator="selectedElement"
                        offset-x>
                        <v-card color="grey lighten-4" min-width="350px" flat>
                            <v-toolbar :color="selectedEvent.color" dark>
                                <v-btn icon>
                                    <v-icon>mdi-pencil</v-icon>
                                </v-btn>
                                <v-toolbar-title v-html="selectedEvent.name"></v-toolbar-title>
                                <v-spacer></v-spacer>
                                <v-btn icon>
                                    <v-icon>mdi-heart</v-icon>
                                </v-btn>
                                <v-btn icon>
                                    <v-icon>mdi-dots-vertical</v-icon>
                                </v-btn>
                            </v-toolbar>
                            <v-card-text>
                                <span v-html="selectedEvent.details"></span>
                            </v-card-text>
                            <v-card-actions>
                                <v-btn text color="secondary" @click="selectedOpen = false">
                                    Cancel
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-menu>
                </v-sheet>
            </v-col>
        </v-row>
    </v-app>
</template>

<script>
export default {
    data: () => ({
        fecha_calendario: '',
        type: 'month',
        typeToLabel: {
            month: 'Mes',
            week: 'Semana',
            day: 'category',
            'category': 'Dia'
        },
        selectedEvent: {},
        selectedElement: null,
        selectedOpen: false,
        events: [],
        colors: ['blue', 'indigo', 'deep-purple', 'cyan', 'green', 'orange', 'grey darken-1'],
        names: ['Meeting', 'Holiday', 'PTO', 'Travel', 'Event', 'Birthday', 'Conference', 'Party'],
        categories: [],
    }),
    mounted() {
        this.$refs.calendar.checkChange()
    },
    methods: {
        async viewDay({ date }) {
            this.fecha_calendario = date
            alert(date)
            /*this.type = 'category'
            var res = await axios({
                method: 'get',
                url: `/${process.env.MIX_CARPETA}/cita/` + this.date,
            }).then(
                (response) => {
                    console.log(response);
                    
                }, (error) => {
                    console.log(error);
                }
            );*/
        },
        getEventColor(event) {
            return event.color
        },
        setToday() {
            this.fecha_calendario = ''
        },
        prev() {
            if (this.type == 'category' || this.type == 'day') {
                var valor = -1;
                var contador = this.contarvalidos(valor);
                this.$refs.calendar.prev(contador);
            } else {
                this.$refs.calendar.prev();
            }
        },
        next() {
            this.$refs.calendar.next()
            if (this.type == 'category' || this.type == 'day') {
                var valor = 1;
                var contador = this.contarvalidos(valor);
                this.$refs.calendar.next(contador);

            } else {
                this.$refs.calendar.next();
            }
        },
        diavalido(day) {
            if (day.getUTCDay() == 0 || day.getUTCDay() == 6) {
                return true;
            }
            /*feriados*/
            return false;
        },
        contarvalidos(valor) {
            let fecha = this.$store.state.fecha_actual
            var dateStr = fecha + 'T00:00:00-04:00';
            var f = new Date(dateStr);

            var contador = 1;
            f.setDate(f.getDate() + valor);
            while (this.diavalido(f)) {
                f.setDate(f.getDate() + valor);
                contador++;
            }
            this.$store.state.fecha_actual = f.toISOString().substr(0, 10);
            this.anterior_dia = this.$store.state.fecha_actual;
            return contador;
        },
        showEvent({ nativeEvent, event }) {
            const open = () => {
                this.selectedEvent = event
                this.selectedElement = nativeEvent.target
                requestAnimationFrame(() => requestAnimationFrame(() => this.selectedOpen = true))
            }

            if (this.selectedOpen) {
                this.selectedOpen = false
                requestAnimationFrame(() => requestAnimationFrame(() => open()))
            } else {
                open()
            }

            nativeEvent.stopPropagation()
        },
        updateRange({ start, end }) {
            /*const events = []
      
            const min = new Date(`${start.date}T00:00:00`)
            const max = new Date(`${end.date}T23:59:59`)
            const days = (max.getTime() - min.getTime()) / 86400000
            const eventCount = this.rnd(days, days + 20)
      
            for (let i = 0; i < eventCount; i++) {
              const allDay = this.rnd(0, 3) === 0
              const firstTimestamp = this.rnd(min.getTime(), max.getTime())
              const first = new Date(firstTimestamp - (firstTimestamp % 900000))
              const secondTimestamp = this.rnd(2, allDay ? 288 : 8) * 900000
              const second = new Date(first.getTime() + secondTimestamp)
      
              events.push({
                name: this.names[this.rnd(0, this.names.length - 1)],
                start: first,
                end: second,
                color: this.colors[this.rnd(0, this.colors.length - 1)],
                timed: !allDay,
              })
            }
      
            this.events = events*/
        },
        rnd(a, b) {
            return Math.floor((b - a + 1) * Math.random()) + a
        },
        getDay(date) {

            const WeekDays = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];

            return WeekDays[date.weekday];

        }
    }
}

</script>