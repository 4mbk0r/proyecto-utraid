import { update } from 'lodash';
import Vue from 'vue';
import Vuex from 'vuex'
Vue.use(Vuex);
const day1 = new Date().getFullYear() + '-' + ("0" + (new Date().getMonth() + 1)).slice(-2) + '-' + ("0" + new Date().getDate()).slice(-2)

const store = new Vuex.Store({
    state: {
        //estado inicial de las variables
        fecha_server: '',



        //variables de agenda 2 
        fecha_calendario: '',
        
        //configuraciones 
        config_data: {},
        fecha_hoy: day1,
        fecha_actual: day1,
        fechas: {},
        fecha: [],
        events: [],
        event: {},
        listevent: [],
        categories: ['Equipo 1', 'Equipo 2', 'Equipo 3', 'Equipo 4'],
        feriados: {},
        dias: {},
        lugar: '',
        id_config: '',



    },
    mutations: {
        update_fecha_server(state, fecha){
            
            state.fecha_server = fecha
        },
        updateid_config(state, dato){
            state.id_config = dato
        },
        addconfig(state) {

        },
        addfecha(state) {
            console.log("stores")

            if (typeof state.fecha == 'undefined') {
                return;
            }


            if (state.fecha['data'].length <= 0) {
                return;
            }

            var key = state.fecha['data'][0]['fecha'];
            state.fechas[key] = state.fecha['data']

            //state.fechas.push(state.fecha['data'])

        },
        addevent(state) {
            console.log('store')
            console.log(state.event)
            state.events.push(state.event)
        },
        clearevent(state) {
            console.log('store')
            console.log(state.event)
            state.events = []
        },
        listevent(state) {
            if (state.listevent == undefined) return;
            state.events = []
            for (var element of state.listevent) {
                const first = new Date(element.fecha + 'T' + element.hora_inicio + '-04:00')
                console.log(element);
                //alert(first)
                const second = new Date(element.fecha + 'T' + element.hora_final + '-04:00')
                var datos = {
                    name: element.nombres,
                    start: first,
                    end: second,
                    color: 'black',
                    timed: 1,
                    category: state.categories[element.equipo - 1],
                    cita: element
                }

                datos = Object.assign(datos, element);
                state.events.push(datos)
                this.datos = {}
                //this.$store.events.push(datos)
            }
        },
        addConfig(state, datos) {
            console.log('stores config')

            state.config_data = datos[0];
            console.log(state.config_data.feriados);
        }
    },
    actions: {
        guardarfechaserver(constext, fecha){
            constext.commit('update_fecha_server', fecha)
        },
        updateid_configAction(context) {
            context.commit('updateid_config')
        },
        addFechaAction(context) {
            context.commit('addfecha')
        },
        addEventAction(context) {
            context.commit('addevent')
        },
        clearEventAction(context) {
            context.commit('clearevent')
        },
        listEventsAction(context) {
            context.commit('listevent')
        },
        async pedirConfig(context) {
            try {

                var res = await axios({
                    method: 'get',
                    url: 'configs/'

                }).then();
                context.commit('addConfig', res['data'])
            } catch (ex) {
                return console.error(ex);
            }
        }
    },
    getters: {
        gethoy() {
            return "Hoy es " + store.state.fecha_hoy;
        },
        getfechas() {
            return store.state.fechas;
        },
        getfecha() {
            return store.state.events;
        },
        getConfig() {
            return store.state.config_data
        },
        getid_config(){
            return store.state.id_config
        },
        getfecha_server(){
            return store.state.fecha_server
        }
    }
});
export default store