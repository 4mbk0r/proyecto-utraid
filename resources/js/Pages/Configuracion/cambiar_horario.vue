<template>
    <v-card>
        <!--
        ...............
        {{ datos }}
        ................
        {{ configuracion }}
        -->
        <v-form ref="seleccion_municipio">
            <V-row>
                <!-- Botón Guardar a la izquierda -->
                <v-col class="text-left" cols="6">
                    Configuracion
                </v-col>
                <v-col class="text-right" cols="6">

                    <v-tooltip bottom color="error">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn color="error" dark v-bind="attrs" v-on="on" @click="mensaje_eliminar = true">
                                Eliminar sala
                            </v-btn>
                        </template>
                        <span>Se eliminara la sala.</span>
                    </v-tooltip>
                </v-col>
            </V-row>
            <v-row>
                <v-col>
                    Esta es un configuracion de {{ datos.descripcion }} puedes cambiar el horario de manera general.
                </v-col>
            </v-row>


            <v-row>
                <v-col class="text-center" cols="12" sm="3">
                    <v-text-field v-model="s.tiempo_apertura" type="time" :rules="nombreRules"
                        @change="calcular_horario" label="Hora de apertura">
                    </v-text-field>
                </v-col>

                <v-col class="text-center" cols="12" sm="3">
                    <v-text-field v-model="s.tiempo_cierre" type="time" :rules="nombreRules" @change="calcular_horario"
                        label="Hora de final">
                    </v-text-field>
                </v-col>
                <v-col class="text-center" cols="12" sm="3">
                    <v-text-field v-model="s.min_promedio_atencion" type="number" :rules="nombreRules"
                        @change="calcular_horario" label="Tiempo de atencion. (Minutos)">
                    </v-text-field>
                </v-col>

                <!-- Botón Cancelar a la derecha 
                class="text-right"
                -->
                <v-col class="text-center" cols="12" sm="1">
                    <v-tooltip top color="primary">

                        <template v-slot:activator="{ on, attrs }">
                            <v-btn @click="guardar" color="primary" dark v-bind="attrs" v-on="on">
                                Guardar
                            </v-btn>
                        </template>
                        <span>Se creara horario para esta sala </span>
                    </v-tooltip>
                </v-col>
            </v-row>

            <v-divider></v-divider>
            <v-row no-gutters>

                <v-col>
                    <v-data-table :items="horarios" :headers="encabezado" :footer-props="{
                            'items-per-page-options': [15, 30, 50, 100, -1], 'items-per-page-all-text': 'Todos',
                            'items-per-page-text': 'Horarios'
                        }"></v-data-table>
                </v-col>
            </v-row>
        </v-form>

        <v-dialog v-model="mensaje_eliminar">
            <v-card>
                <v-card-title>Eliminar {{ datos.descripcion }}</v-card-title>
                <v-card-text>¿Estás seguro de que deseas eliminar este {{ datos.descripcion }} de la configuracion?</v-card-text>
                <v-card-actions  style="display: flex; justify-content: flex-end;">
                    <v-btn class="float-right" @click="mensaje_eliminar = false">Cancelar</v-btn>
                    <v-btn class="float-right" @click="eliminar_sala"  color="error">Eliminar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!--{{ datos }}-->
    </v-card>
</template>

<script>
import axios from 'axios'
import moment from 'moment'


const day1 = new Date().getFullYear() + '-' + ("0" + (new Date().getMonth() + 1)).slice(-2) + '-' + ("0" + new Date().getDate()).slice(-2)

export default {
    props: {
        //dialog: Boolean,
        //mensaje: Object,
        fecha: String,
        datos: Object,
        configuracion: Object,
        //equipo: Array,
        //cargos: Array,
    },
    created() {
        this.pedir_datos();
    },
    data: () => ({
        mensaje_eliminar: false,
        s: {},
        horario_sala: {},
        tiempo_apertura: '',
        selectMunicipio: '',
        municipios: [],
        horarios: [],
        selecionRules: [
            v => !!v || "Se requiere seleccion"],
        encabezado: [
            {
                text: "Hora final",
                align: "start",
                value: "hora_inicio",
            },
            {
                text: "Hora final",
                value: "hora_final",
            }

        ],
        nombreRules: [v => !!v || 'Se requiere el dato'],

        itemsPerPage: -1, // Mostrar todos los elementos
        pagination: {
            descending: false,
            page: 1,
            rowsPerPage: -1, // Mostrar todos los elementos
            sortBy: null,
            totalItems: 0,
        },
    }),
    mounted() {
        //console.log('sssss', this.item);
        /*if(this.cargos.length > 0 ){
            for (const key in this.cargos) {
                if (Object.hasOwnProperty.call(this.cargos, key)) {
                    //const element = this.cargos[key];
                    this.caloriesList.push(this.cargos[key])        
                }
            }
            
        }*/

    },
    components: {
        //AppLayout,
        //mequipo
    },
    computed: {

    },
    methods: {
        async pedir_datos() {
            console.log('/////;;;');
            console.log(this.fecha)
            this.datos.fecha = this.fecha
            console.log(this.datos.fecha)

            var res = await axios({
                method: 'post',
                url: `/${process.env.MIX_CARPETA}/api/ver_configuracion`,
                data: {
                    salas: this.datos,
                    config: this.configuracion
                }

                ///equipo: this.selectMunicipio.equipo

            }).then(
                (response) => {
                    //var r = response.data.seleccion
                    console.log('municipios de la sala')
                    console.log(response.data);
                    this.horario_sala = response.data
                    console.log('------+++++----');
                    var r = {}
                    //r.horarios = response.data
                    r.tiempo_apertura = response.data[0].tiempo_apertura
                    r.tiempo_cierre = response.data[0].tiempo_cierre
                    r.min_promedio_atencion = response.data[0].min_promedio_atencion
                    r.tiempo_descanso = response.data[0].tiempo_descanso
                    this.s = structuredClone(r)
                    this.calcular_horario()
                    //this.s.tiempo_atencion = response.data[0].min_promedio_atencion
                    //this.municipios = response.data['municipios']
                    //this.selectMunicipio = response.data['municipio']

                }
            ).catch(err => {
                console.log(err)

            });
        },
        
        async eliminar_sala() {
            this.datos.fecha = this.fecha

            console.log(this.horarios)
            var res = await axios({
                method: 'post',
                url: `/${process.env.MIX_CARPETA}/api/eliminarhorariogeneral`,
                data: {
                    nueva_sala: this.datos,
                    datos: this.configuracion,
                    horarios: this.horarios,
                    config_nueva: this.s
                }


                //equipo: this.selectMunicipio.equipo

            }).then(
                (response) => {
                    /*//var r = response.data.seleccion
                    console.log('municipios de la sala')
                    console.log(response.data);
                    //this.horarios = response.data
                    this.municipios = response.data['municipios']
                    this.selectMunicipio = response.data['municipio']
                    */
                   console.log('=====AAAAA')
                   console.log(response.data);
                    
                   console.log(response);
                   this.mensaje_eliminar = false
                   this.$emit('cerrar_ventana', false)
                }
            ).catch(err => {
                console.log(err)

            });
        },
        async guardar() {
            this.datos.fecha = this.fecha

            console.log(this.horarios)
            var res = await axios({
                method: 'post',
                url: `/${process.env.MIX_CARPETA}/api/cambiarhorariogeneral`,
                data: {
                    nueva_sala: this.datos,
                    datos: this.configuracion,
                    horarios: this.horarios,
                    config_nueva: this.s
                }


                //equipo: this.selectMunicipio.equipo

            }).then(
                (response) => {
                    //var r = response.data.seleccion
                    console.log('municipios de la sala')
                    console.log(response.data);
                    //this.horarios = response.data
                    this.municipios = response.data['municipios']
                    this.selectMunicipio = response.data['municipio']

                }
            ).catch(err => {
                console.log(err)

            });
        },
        get_nombre_equipo($value) {
            /*console.log("------");
            console.log($value)*/
            return $value.nombre_equipo
        },
        select_nombre_equipo($value) {
            /*console.log("--slect ----");
            console.log($value)*/
            return $value
        },
        empty(variable) {
            if (
                variable === null ||
                variable === undefined ||
                (typeof variable === 'number' && isNaN(variable)) ||
                (typeof variable === 'string' && variable.trim() === '') ||
                (Array.isArray(variable) && variable.length === 0)
            ) {
                return true;
            } else {
                return false;
            }
        },
        calcular_horario() {
            try {


                console.log(this.s)
                if (this.empty(this.s.tiempo_apertura)
                    || this.empty(this.s.tiempo_cierre)
                    || this.empty(this.s.min_promedio_atencion)
                ) {
                    //alert('null')
                    return
                }
                if (this.s.min_promedio_atencion <= 0) {
                    //alert('cero')
                    return
                }

                this.horarios = []
                let horarios = [];
                let tiempo_i = moment(this.s.tiempo_apertura, 'hh:mm')
                let tiempo_f = moment(this.s.tiempo_cierre, 'hh:mm')
                let tiempo_descanso = true

                // Bucle para generar los horarios
                //let horaInicio = new Date(apertura);
                while (tiempo_i.isBefore(tiempo_f)) {
                    let op = {}

                    let a = moment(tiempo_i.format('HH:mm'), 'hh:mm')
                    let s = a.add(this.s.min_promedio_atencion, 'minutes')
                    //console.log(tiempo_i.format('HH:mm'));
                    //console.log(s.format('HH:mm'));
                    if (s.isAfter(tiempo_f)) {
                        this.$alert('existe un tiempo libre que no encaja con tu configuracion')
                        break
                    }

                    op.hora_inicio = tiempo_i.format('HH:mm')
                    op.hora_final = s.format('HH:mm')
                    //op.sala = this.s.sala
                    horarios.push(op);

                    tiempo_i = s
                    ////console.log(tiempo_i, tiempo_f)


                }
                /*do {
                    
                    // Verificar si la hora final no excede el tiempo de cierre
                   
                    // Agregar al array de horarios
                    horarios.push({
                        hora_inicio: horaInicio.format("HH:mm"),
                        hora_final: horaFinal.format("HH:mm")
                    });
    
                    // Mover la hora de inicio al siguiente intervalo
                    horaInicio.add(this.s.min_promedio_atencion, 'minutes');
                } while (horaInicio.isSameOrBefore(cierre));
                */

                console.log(horarios);
                this.horarios = structuredClone(horarios)
            } catch {
                this.horarios = []
            }

            /*
            var descanso = moment(this.s.tiempo_descanso, 'HH:mm')
            console.log(descanso)
            if (!descanso.isValid()) this.s.tiempo_descanso = null
            this.s.horario = structuredClone(this.horario)*/
        },
        verificar_table() {
            return this.horarios.length > 0
        }
    },
}
</script>
