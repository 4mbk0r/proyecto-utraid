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
                                <v-list-item @click="changeType('category2')">
                                    <v-list-item-title>Atencion</v-list-item-title>
                                </v-list-item>

                                <v-list-item @click="changeType('category')">
                                    <v-list-item-title>Citas</v-list-item-title>
                                </v-list-item>

                                <v-list-item @click="changeType('month')">
                                    <v-list-item-title>Mes</v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </v-toolbar>
                </v-sheet>
                <v-sheet>
                    <v-calendar v-if="estado == 'calendario'" ref="calendar" v-model="fecha_calendario" :type="type"
                        color="error" :events="events" :categories="categories" :event-color="getEventColor"
                        @click:event="showEvent" @click:more="viewDay" @click:date="viewDay" @change="updateRange"
                        :max-days=5 weekdays="1, 2, 3, 4, 5" :weekday-format="getDay" :first-interval=7
                        :interval-minutes=60 :interval-count=12>
                    </v-calendar>
                    <v-calendar v-if="estado == 'cita'" ref="calendar" v-model="fecha_calendario" color="error"
                        type="category" category-show-all :categories="categories" :events="events"
                        :event-color="getEventColor" :weekday-format="getDay" @click:event="showEvent"
                        :interval-minutes=60 :first-interval=7 :interval-count=14></v-calendar>

                    <v-calendar v-if="estado == 'atencion'" ref="calendar" v-model="fecha_calendario" color="error"
                        type="category" category-show-all :categories="categories" :events="events"
                        :event-color="getEventColor" :weekday-format="getDay" @click:event="showEvent"
                        :interval-minutes=60 :first-interval=7 :interval-count=14></v-calendar>
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
                                <V-row no-gutters>
                                    <v-col>
                                        CI: 
                                    
                                    </v-col>
                                    <v-col>
                                        {{ valores(selectedEvent.paciente, 'ci') }}
                                    </v-col>
                                </v-row>
                                <v-row no-gutters>
                                    <v-col>
                                    <span> Nombre Completo:</span>
                                    </v-col>
                                    <v-col>
                                        {{ selectedEvent.name }} 
                                    </v-col>
                                </v-row>
                                <v-row no-gutters>
                                    <v-col>
                                    <span> Fecha:</span>
                                    </v-col>
                                    <v-col>
                                        {{ valores(selectedEvent.paciente, 'fecha') }} 
                                    </v-col>
                                </v-row>

                            </v-card-text>
                            <v-card-actions>
                                <v-btn text color="secondary" @click="selectedOpen = false">
                                    Cancelar
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-menu>
                </v-sheet>

            </v-col>

        </v-row>
        <datos @pedir='actualizador'  ref="dato">
        </datos>
        <atencion ref="atender"></atencion>
        
        
    </v-app>
</template>

<script>
import moment from 'moment'
import buscar from '@/Pages/Micomponet/Buscar'
import datos from '@/Pages/Micomponet/Datospersonales'
import atencion from '@/Pages/Micomponet/Atencion'


export default {
    components: {
        buscar,
        datos,
        atencion,
    },
    data: () => ({
        dialog: false,
        estado: 'calendario',
        fecha_calendario: new Date().toISOString().substr(0, 10),
        type: 'month',
        typeToLabel: {
            month: 'Mes',
            week: 'Semana',
            day: 'Category',
            'category': 'Citas',
            'category2': 'Atencion'
        },
        selectedEvent: {},
        selectedElement: null,
        selectedOpen: false,
        events: [],
        colors: ['blue', 'indigo', 'deep-purple', 'cyan', 'green', 'orange', 'grey darken-1'],
        names: ['Reunion', 'Cita', 'Viaje'],
        categories: [],
        fecha_min: '',
    }),
    created() {
        

    },
    updated(){
       
    },  
    mounted() {

        this.$refs.calendar.checkChange()
        console.log(this.$root.$refs);

        //console.log();
    },
    computed: {
          
  },
    methods:{
        actualizador(fecha){
            console.log('.+.+.+.+')
            this.fecha_calendario = fecha
            this.pedir_datos(fecha)
        },  
        changeType(nombre) {
            if (nombre == 'category2') {
                this.estado = 'atencion'
                this.categories = ['Doctor 1', 'Doctor 2']
                this.pedir_doctores(this.fecha_calendario)
                //this.pedir_datos(this.fecha_calendario)
                return;
            }
            if (nombre == 'category') {
                this.estado = 'cita'
                this.type = nombre
                this.pedir_datos(this.fecha_calendario)
            } else {
                this.estado = 'calendario'
                this.type = nombre
                
            }
        },
        async viewDay({ date }) {
            this.fecha_calendario = date
            //alert(date)

            this.type = 'category'
            this.pedir_datos(date);

        },
        async pedir_doctores(date) {
            try {
                var res = await axios({
                    method: 'get',
                    url: `/${process.env.MIX_CARPETA}/lista_configuracion/` + date,
                }).then(
                    (response) => {
                        console.log(response);
                        let start = moment(`${this.fecha_calendario}T00:00:00`),
                            intervalos = 1440 / 45;
                        let list_intervalo = []
                        for (let i = 0; i < intervalos; i++) {
                            //console.log(start.format('hh:mm A'))
                            list_intervalo.push(start.add(45, 'm').format('hh:mm A'))

                        }
                        this.categories = [];
                        let events = [];
                        this.events = [];
                        let start2 = new Date(this.fecha_calendario + 'T01:01:00-04:00')
                        let end = new Date(this.fecha_calendario + 'T21:50:00-04:00')
                        let fecha_server = moment(this.$store.getters.getfecha_server + 'T00:00:00-04:00')
                        this.fecha_min = fecha_server.format('YYYY-MM-DD')
                        for (const key in response.data) {
                            //console.log(start);
                            //console.log(end);
                            //console.log(response.data[key]['descripcion'])
                            this.categories.push(response.data[key]['descripcion'])
                           
                                this.events.push({
                                    name: 'Atencion',
                                    start: start2,
                                    end: end,
                                    color: 'green',
                                    timed: 0,
                                    category: this.categories[key],
                                    consultorio: response.data[key],
                                })
                        


                            /*this.events.push({
                                name: 'Cita',
                                start: new Date(this.fecha_calendario + 'T08:01:00-04:00'),
                                end: new Date(this.fecha_calendario + 'T09:01:00-04:00'),
                                color: 'blue',
                                timed: 1,
                                category: this.categories[key],
                            })*/
                        }
                        //console.log(this.type);

                        //this.fetchEvents()
                        //console.log(this.events)
                    }, (error) => {
                        console.log(error);
                    }
                );
            } catch (err) {
                console.log("err->", err.response.data)
                return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
            }
            /*try {
                var res = await axios({
                    method: 'get',
                    url: `/${process.env.MIX_CARPETA}/lista_agenda/` + date,
                }).then(
                    (response) => {
                        let pacientes = response.data;
                        for (const key in pacientes) {
                            let paciente = pacientes[key];
                            //console.log(paciente.fecha + 'T'+paciente.hora_inicio+'-04:00')//new Date(),);
                            this.events.push({
                                name: paciente.nombres + " " + paciente.ap_paterno + " " + paciente.ap_materno,
                                start: new Date(paciente.fecha + 'T' + paciente.hora_inicio + '-04:00'),
                                end: new Date(paciente.fecha + 'T' + paciente.hora_final + '-04:00'),
                                color: 'blue',
                                timed: 1,
                                category: this.categories[paciente.consultorio-1],
                                paciente: structuredClone(paciente)
                            })
                            /*if (Object.hasOwnProperty.call(object, key)) {
                                const element = object[key];
                                
                            }*//*
                        }

                    }, (error) => {
                        console.log(error);
                    }
                );
            } catch (err) {
                console.log("err->", err.response.data)
                return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
            }*/
        },
        async pedir_datos(date) {
            try {
                var res = await axios({
                    method: 'get',
                    url: `/${process.env.MIX_CARPETA}/lista_configuracion/` + date,
                }).then(
                    (response) => {
                        console.log(response);
                        let salas = response.data['salas'];
                        let salas_disponibles = response.data['salas_diponibles'];
                        
                        //console.log('__'+salas)
                        
                        
                        this.categories = [];
                        let events = [];
                        this.events = [];
                        let start2 = new Date(this.fecha_calendario + 'T01:01:00-04:00')
                        let end = new Date(this.fecha_calendario + 'T21:50:00-04:00')
                        let fecha_server = moment(this.$store.getters.getfecha_server + 'T00:00:00-04:00')
                        this.fecha_min = fecha_server.format('YYYY-MM-DD')
                        for (const key in salas) {
                            //console.log(start);
                            //console.log(end);
                            console.log('----')
                            console.log(salas)
                            console.log(salas_disponibles)
                            this.categories.push(salas[key]['descripcion'])
                            if (this.fecha_calendario > this.fecha_min  && salas_disponibles.filter(e => e.descripcion === salas[key]['descripcion']).length > 0)
                            {
                                this.events.push({
                                    name: 'Agendar',
                                    start: start2,
                                    end: end,
                                    color: 'red',
                                    timed: 0,
                                    category: this.categories[key],
                                    consultorio: salas[key],
                                })
                            }


                            /*this.events.push({
                                name: 'Cita',
                                start: new Date(this.fecha_calendario + 'T08:01:00-04:00'),
                                end: new Date(this.fecha_calendario + 'T09:01:00-04:00'),
                                color: 'blue',
                                timed: 1,
                                category: this.categories[key],
                            })*/
                        }
                        //console.log(this.type);

                        //this.fetchEvents()
                        //console.log(this.events)
                    }, (error) => {
                        console.log(error);
                    }
                );
            } catch (err) {
                console.log("err->", err.response.data)
                return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
            }
            try {
                var res = await axios({
                    method: 'get',
                    url: `/${process.env.MIX_CARPETA}/lista_agenda/` + date,
                }).then(
                    (response) => {
                        let pacientes = response.data;
                        for (const key in pacientes) {
                            let paciente = pacientes[key];
                            //console.log(paciente.fecha + 'T'+paciente.hora_inicio+'-04:00')//new Date(),);
                            this.events.push({
                                name: paciente.nombres + " " + paciente.ap_paterno + " " + paciente.ap_materno,
                                start: new Date(paciente.fecha + 'T' + paciente.hora_inicio + '-04:00'),
                                end: new Date(paciente.fecha + 'T' + paciente.hora_final + '-04:00'),
                                color: 'blue',
                                timed: 1,
                                category: this.categories[paciente.consultorio-1],
                                paciente: structuredClone(paciente)
                            })
                            /*if (Object.hasOwnProperty.call(object, key)) {
                                const element = object[key];
                                
                            }*/
                        }

                    }, (error) => {
                        console.log(error);
                    }
                );
            } catch (err) {
                console.log("err->", err.response.data)
                return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
            }
        },

        valores(objecto, x) {
            if (typeof objecto == "undefined") return
            console.log(objecto)
            return objecto['' + x]
        },
        getEventColor(event) {
            return event.color
        },
        setToday() {
            var f = new Date();
            this.fecha_calendario = ''
            this.fecha_calendario = f.toISOString().substr(0, 10);
            if(this.estado == 'atencion'){
                this.pedir_doctores(this.fecha_calendario);
                return
            }
            this.pedir_datos(this.fecha_calendario)
            
        },
        prev() {
            console.log(this.type);
            if (this.estado == 'atencion') {
                var valor = -1;
                var contador = this.contarvalidos(valor);
                this.$refs.calendar.prev(contador);
                this.pedir_doctores(this.fecha_calendario)
                return;
            }
            if (this.type == 'category' || this.type == 'day') {
                var valor = -1;
                var contador = this.contarvalidos(valor);
                this.$refs.calendar.prev(contador);
                this.pedir_datos(this.fecha_calendario)

            } else {
                this.$refs.calendar.prev();
            }

        },
        next(){

            if (this.estado == 'atencion') {
                var valor = +1;
                var contador = this.contarvalidos(valor);
                this.$refs.calendar.next(contador);
                this.pedir_doctores(this.fecha_calendario)
                return
            }
            if (this.type == 'category' || this.type == 'day') {
                var valor = +1;
                var contador = this.contarvalidos(valor);
                this.$refs.calendar.next(contador);
                this.pedir_datos(this.fecha_calendario)

            } else {
                this.$refs.calendar.next();
            }
        },
        diavalido(day) {
            console.log(day);
            console.log(day.getUTCDay());
            if (day.getUTCDay() == 0 || day.getUTCDay() == 6) {
                return true;
            }
            /*feriados*/
            return false;
        },
        contarvalidos(valor) {
            let fecha = structuredClone(this.fecha_calendario);
            console.log(fecha);
            var dateStr = fecha + 'T00:00:00-04:00';
            var f = new Date(dateStr);
            var contador = 0;
            contador++;
            f.setDate(f.getDate() + valor);

            while (this.diavalido(f)) {
                f.setDate(f.getDate() + valor);
                contador++;

            }
            console.log("saltar", contador);
            this.fecha_calendario = f.toISOString().substr(0, 10);
            return contador;
        },
        showEvent({ nativeEvent, event }) {

            console.log(event);
            if (event.name == 'Atencion') {
                //this.dialog = true
                //this.selectedEvent = event

                console.log('datos');
                console.log(event.consultorio)

                //this.$refs.atender.op1 = 1;
                //this.$refs.a.fecha_cita = this.fecha_calendario
                //this.$refs.dato.consultorio = event.consultorio.sala
                this.$refs.atender.open()
                return;
                //this.selectedElement = nativeEvent.target
                //nativeEvent.stopPropagation()
            } 
            if (event.name == 'Agendar') {
                //this.dialog = true
                //this.selectedEvent = event

                console.log('datos');
                console.log(event.consultorio)

                this.$refs.dato.op1 = 1;
                this.$refs.dato.fecha_cita = this.fecha_calendario
                this.$refs.dato.consultorio = event.consultorio.sala
                this.$refs.dato.open()
                //this.selectedElement = nativeEvent.target
                //nativeEvent.stopPropagation()
            } else {
                const open = () => {
                    this.selectedEvent = structuredClone(event)
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
            }



        },
        updateRange({ start, end }) {
            let events = []
            this.events = []
            /*
            const min = new Date(`${start.date}T00:00:00`)
            const max = new Date(`${end.date}T23:59:59`)
            console.log(min)
            console.log(max)
            const days = (max.getTime() - min.getTime()) / 86400000
            const eventCount = this.rnd(days, days + 20)

            for (let i = 0; i < eventCount; i++) {
                const allDay = this.rnd(1, 3) === 0
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
                    category: this.categories[this.rnd(0, this.categories.length - 1)],
                })
            }

            this.events = events
            console.log(this.events)*/

        },
        rnd(a, b) {
            return Math.floor((b - a + 1) * Math.random()) + a
        },
        getDay(date) {

            const WeekDays = ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'];

            return WeekDays[date.weekday];

        },
        fetchEvents() {
            const events = []
            console.log('---' + this.fecha_calendario)
            const min = new Date(this.fecha_calendario + ' T00:00')
            const max = new Date(this.fecha_calendario + ' T23:59')
            const days = (max.getTime() - min.getTime()) / 86400000
            const eventCount = this.rnd(days, days + 20)

            for (let i = 0; i < eventCount; i++) {
                const allDay = this.rnd(1, 3) === 0
                const firstTimestamp = this.rnd(min.getTime(), max.getTime())
                const first = new Date(firstTimestamp - (firstTimestamp % 900000))
                const secondTimestamp = this.rnd(2, allDay ? 288 : 8) * 900000
                const second = new Date(first.getTime() + secondTimestamp)

                events.push({
                    name: this.names[this.rnd(0, this.names.length - 1)],
                    start: first,
                    end: second,
                    color: this.colors[this.rnd(0, this.colors.length - 1)],
                    timed: 1,
                    category: this.categories[this.rnd(0, this.categories.length - 1)],
                })
            }
            //this.events.push(events)
        },
    }
}

</script>