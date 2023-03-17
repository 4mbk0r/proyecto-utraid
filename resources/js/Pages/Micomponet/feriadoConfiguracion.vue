<template>
    <v-dialog v-if="dialog" v-model="dialog" class="text-left" persistent>


    </v-dialog>
</template>

<script>
import axios from 'axios'
import moment from 'moment'

import Salas from '@/Pages/Micomponet/Sala'
import Equipo from '@/Pages/Configuracion/Equipo'
import { emit } from 'process'

const day1 = new Date().getFullYear() + '-' + ("0" + (new Date().getMonth() + 1)).slice(-2) + '-' + ("0" + new Date().getDate()).slice(-2)

export default {
    components: {
        Salas,
        Equipo,
    },
    props: {
        dialog: Boolean,
        item: Object
    },
    data: () => ({
        //dialog: false,
        date: '',
        menu1: false,
        configuracion: {},
        calendario: {},
        color: '#1976D2FF',
        mask: '',
        menu: false,
        menu2: false,
        paso: 1,
        alert: false,
        errors_descripcion: [],
        equipo: [],
        salas: [],
        dates: [],
        arrayEvents: [],
        fechas_vigentes: [],

    }),
    created() {
        console.log('inicio');
        this.get_fechas();
    },
    mounted() {
        console.log('sdfasdklfjasf');
    },
    beforeMount() {
       
    },
    computed: {
        computedDateFormattedMomentjs() {
            moment.locale('es');
            this.calendario.fecha_inicio = this.date
            return this.date ? moment(this.date).format('dddd, MMMM Do YYYY') : ''
        },
        swatchStyle() {
            const { color, menu } = this
            return {
                backgroundColor: color,
                cursor: 'pointer',
                height: '30px',
                width: '30px',
                borderRadius: menu ? '50%' : '4px',
                transition: 'border-radius 200ms ease-in-out'
            }
        }
    },
    watch: {
        limpiar_errors() {
            this.errors_descripcion = []
        }
    },

    methods: {

        limpiar_errors() {
            this.errors_descripcion = []
        },
        close() {
            //this.dialog = false
                       ////console.log(this.date);
            this.$emit('cerrar', false)
        },
        async crear() {
            //this.dialog = false
            ////console.log(this.date);

            try {
                this.configuracion.color = this.color
                var res = await axios({
                    method: "post",
                    url: `/${process.env.MIX_CARPETA}/calendariolineal`,
                    data: {
                        calendario: this.calendario,
                        configuracion_nueva: this.configuracion,
                        configuracion_antigua: this.item,

                    },
                }).then(
                    (response) => {
                        //console.log(response);
                    },
                    (error) => {
                        //console.log(error);
                    }
                );
            } catch (err) {
                //console.log("err->", err.response.data);
                return res
                    .status(500)
                    .send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
            }
            this.$emit('cerrar', false)
        },

        paso2() {
            if (this.$refs.fecha_rango.validate()) {
                this.paso = 2
            }

        },

        async paso3() {

            if (this.$refs.form_configuracion.validate()) {
                this.paso = 3
                //this.validar_configuracion()
                //console.log("_________")
                //console.log(this.item)


            }



        },
        minfecha() {

            ////console.log(item)
            /*moment('2010-10-20').isBefore('2010-12-31', 'year'); // false <
            
            moment('2010-10-20').isBefore('2011-01-01', 'year'); // true    <
            moment('2010-10-20').isSame('2009-12-31', 'year');  // false =
            moment('2010-10-20').isSame('2010-01-01', 'year');  // true
            moment('2010-10-20').isSame('2010-12-31', 'year');  // true
            moment('2010-10-20').isSame('2011-01-01', 'year');  // false
            
            moment('2010-10-20').isAfter('2010-01-01', 'year'); // false >
            moment('2010-10-20').isAfter('2009-12-31', 'year'); // true>*/
            ////console.log(this.item);
            ////console.log(this.item)
            let f = this.$store.getters.gethoy
            return f
            if (moment(f).isSameOrAfter(this.item.fecha_inicio)) {
                return f
            }
            return this.item.fecha_inicio

        },
        maxfecha() {

            ////console.log(item)
            /*moment('2010-10-20').isBefore('2010-12-31', 'year'); // false <
            
            moment('2010-10-20').isBefore('2011-01-01', 'year'); // true    <
            moment('2010-10-20').isSame('2009-12-31', 'year');  // false =
            moment('2010-10-20').isSame('2010-01-01', 'year');  // true
            moment('2010-10-20').isSame('2010-12-31', 'year');  // true
            moment('2010-10-20').isSame('2011-01-01', 'year');  // false
            
            moment('2010-10-20').isAfter('2010-01-01', 'year'); // false >
            moment('2010-10-20').isAfter('2009-12-31', 'year'); // true>*/
            ////console.log(this.item);
            ////console.log(this.item)
            let t_year = moment(this.$store.getters.gethoy).add(3, 'year')
            return t_year.format('yyyy-mm-dd')
            ////console.log(t_year);
            if (t_year.isSameOrAfter(this.item.fecha_final)) {//>
                return this.item.fecha_final
            }
            return t_year.format('yyyy-mm-dd')

        },
        desavilitarFecha(val) {
            //return true
            console.log('desavilitar');
            console.log(this.fechas_vigentes)
            for (const key in this.fechas_vigentes) {
                let element =this.fechas_vigentes[key];
                console.log(val, '=', element.fe
                );
                if(val ==  element.fecha  && element.nro_citas > 0){
                    return false
                }
            }
            return true
        },
        paso4() {
            //this.paso+=1
            /*setTimeout(() => {
                
            }, 30);*/
            console.log(this.salas
            );
            this.$refs.sala.generar_horario()
            this.salas = structuredClone(this.$refs.sala.desserts)

            if (!(this.salas.length > 0)) {
                alert('no se puede crear configuracion con salas 0')
                return
            }
            let k = 1
            this.equipo = [...Array(this.salas.length).fill(0).map(x => ({ equipo: 'Equipo ' + (k), descripcion: this.salas[k - 1].descripcion + ' => Equipo ' + (k++), lista: [] }))]
            this.paso = 4

            /*
            this.$refs.equipo.equipo = [{
                equipo: 'Equipo 1',
                lista: [
                ]
            },
            {
                equipo: 'Equipo 2',
                lista: [

                ]
            },
            {
                equipo: 'Equipo 3',
                lista: [

                ]
            },]*/

        },
        paso5(valor) {
            //console.log("______________");
            //console.log(this.$refs.equipo.equipo);
            /** */
            if (valor.resp) {
                console.log("______");
                console.log(valor)
                console.log(this.paso);
                this.equipo = valor.datos
                this.paso = 5;


            }
            return;
            /*this.equipo = this.$refs.equipo.equipo
            //this.paso6()
            if (this.validar_equipo()) {
                var res = await axios({
                    method: "post",
                    url: `/${process.env.MIX_CARPETA}/equipo2`,
                    data: {

                        equipo: this.equipo
                    },
                }).then(
                    (response) => {
                        ////console.log('validat');
                        console.log(response);
                        //console.log('__configuracion ___');
                        //console.log(response.data);
                        //this.close()


                    },
                ).catch((error) => {
                    //console.log(error.response.data.mensaje);

                });
            }*/
            ////console.log(this.$refs.equipo.items);
            //let lista = this.$refs.equipo.items
            //this.equipo = [...Array(this.salas.length).fill(0).map(x => ({ equipo: 'Equipo '+(k++), lista: [] }))]
            //let lista = this.equipo[this.selected_equipo].lista


        },
        async paso6() {
            console.log(this.equipo);
            this.configuracion.color = this.color
            this.configuracion.institucion = '01'
            var res = await axios({
                method: "post",
                url: `/${process.env.MIX_CARPETA}/configuracion_rango`,
                data: {

                    fecha_nueva: this.dates.sort(),
                    configuracion: this.configuracion,
                    configuracion_antigua: this.item,
                    salas: this.salas,
                    equipo: this.equipo
                },
            }).then(
                (response) => {
                    console.log(response);
                    ////console.log('validat');
                    ////console.log(response);
                    //console.log('__configuracion ___');
                    //console.log(response.data);
                    this.close()


                },
            ).catch((error) => {
                //console.log(error.response.data.mensaje);

            });

            /**/
        },
        validar_equipo() {
            for (let index = 0; index < this.equipo.length; index++) {
                if (this.equipo[index].lista.length == 0) {
                    alert('falta elementos en ' + this.equipo[index].equipo)
                    return false
                }

            }
            return true
        },
        async validar_configuracion() {
            try {
                this.configuracion.color = this.color
                this.configuracion.institucion = '01'

                var res = await axios({
                    method: "post",
                    url: `/${process.env.MIX_CARPETA}/api/validar_configuracion`,
                    data: {
                        configuracion: this.configuracion,
                    },
                }).then(
                    (response) => {
                        ////console.log('validat');
                        ////console.log(response);
                        if (response.data['validar'] == false) {
                            this.errors_descripcion = ['ya existe esta descripcion']
                            return
                        }

                        this.paso = 3
                        //console.log(this.paso);

                    },
                    (error) => {
                        console.log(error);
                        return false


                    }
                );
            } catch (err) {
                console.error("err->", err.response.data);
                return false

                return res
                    .status(500)
                    .send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
            }
        },
        cancelar() {
            this.close()
        },
        atras() {
            this.paso = (this.paso > 1) ? this.paso - 1 : this.paso
        },
        equipo_update(valor) {
            this.equipo = valor
        },
        async get_fechas() {
            var res = await axios({
                method: 'get',
                url: `/${process.env.MIX_CARPETA}/api/fechas_vigentes`,
            }).then(
                (response) => {
                    console.log(response);
                    this.fechas_vigentes = response.data
                    this.arrayEvents = [];
                    for (const key in response.data) {
                        let element = response.data[key];
                        this.arrayEvents.push(element.fecha)
                    }

                }
            ).catch(err => {
                console.log(err)
                console.log("err->", err.response.data)
                return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
            });
        }

    }
}
</script>