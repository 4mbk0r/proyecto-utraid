
<template>
    <v-card>
        <v-form ref="salas_nueva">
            <V-row>
                <!-- Botón Guardar a la izquierda -->
                <v-col class="text-left" cols="6">
                    Configuracion para crear una Sala
                </v-col>
            </V-row>

            <v-row>

                <v-col cols="12">
                    <v-text-field v-model="s.descripcion" :rules="[validarTexto,validar]" label="Nombre de la Sala">
                    </v-text-field>
                </v-col>
            </v-row>
            <v-row>

                <v-col cols="12" sm="3">
                    <v-text-field v-model="s.tiempo_apertura" type="time" :rules="nombreRules" @change="calcular_horario"
                        label="Hora de apertura">
                    </v-text-field>
                </v-col>

                <v-col cols="12" sm="3">
                    <v-text-field v-model="s.tiempo_cierre" type="time" :rules="nombreRules" @change="calcular_horario"
                        label="Hora de cierre">
                    </v-text-field>
                </v-col>
                <v-col cols="12" sm="3">
                    <v-text-field v-model="s.min_promedio_atencion" type="number" :rules="nombreRules"
                        @change="calcular_horario" label="T iempo de consulta (minutos)">
                    </v-text-field>
                </v-col>

                <!-- Botón Cancelar a la derecha 
                class="text-right"
                -->
                <v-col cols="12" sm="3">
                    <v-btn @click="guardar">Guardar</v-btn>
                </v-col>
            </v-row>

            <v-divider></v-divider>
            <v-row no-gutters>

                <v-col>
                    <v-data-table :items="horarios" :headers="encabezado"></v-data-table>
                </v-col>
            </v-row>
        </v-form>

        
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
        salas_actuales: Array,
        //equipo: Array,
        //cargos: Array,
    },
    created() {
        //this.pedir_datos();
    },
    data: () => ({
        dialogAceptoCreacionSala: true,
        s: {},
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
        
        validarTexto(v){
            if(this.empty(v)){
                return 'Se requiere valor'
            }
            return true;
        },
        validar(v) {
            if (this.salas_actuales.find(sala => sala.descripcion === v)) {
                return 'Sala ya existe'

            }
            return true

        },
        async pedir_datos() {
            console.log('/////;;;');
            console.log(this.fecha)
            this.datos.fecha = this.fecha
            console.log(this.datos.fecha)

            var res = await axios({
                method: 'post',
                url: `/${process.env.MIX_CARPETA}/api/equipos_sala`,
                data:
                    this.datos,
                ///equipo: this.selectMunicipio.equipo

            }).then(
                (response) => {
                    //var r = response.data.seleccion
                    console.log('municipios de la sala')
                    console.log(response.data);
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
        async guardar() {
            console.log('sss')
            console.log(this.datos);
            if (this.$refs.salas_nueva.validate()) {
                //this.datos.fecha = this.fecha
                var res = await axios({
                    method: 'post',
                    url: `/${process.env.MIX_CARPETA}/crear_sala`,
                    data: {
                        datos: this.datos,
                        sala: this.salas_actuales,
                        nueva_sala: this.s,
                        horarios: this.horarios
                    }


                    //equipo: this.selectMunicipio.equipo

                }).then(
                    (response) => {
                        //var r = response.data.seleccion
                        console.log('------municipios de la sala')
                        console.log(response);
                        console.log(response.data);
                        
                        console.log('------------');
                    console.log(this.dialogAceptoCreacionSala);
                    this.dialogAceptoCreacionSala = true
                    
                        //this.horarios = response.data
                        //this.municipios = response.data['municipios']
                        //this.selectMunicipio = response.data['municipio']

                    }
                ).catch(err => {
                    console.log(err)

                });

                // El formulario es válido, puedes enviar los datos aquí
            }

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

