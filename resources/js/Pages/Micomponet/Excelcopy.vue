<!-- Use preprocessors via the lang attribute! e.g. <template lang="pug"> -->
<template>
    <AppLayout>
        <v-card dense>
            <v-row>
                <v-col>
                    <h1>Importar Pacientes</h1>
                </v-col>
                <v-col>
                    <v-btn tile color="info" @click="descargarbase()">
                        <v-icon left>
                            mdi-content-save-settings
                        </v-icon>
                        Plantilla
                    </v-btn>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <form method="POST" enctype="multipart/form-data">
                        <!--@change="excelExport"-->
                        <input type="file" ref="fileInput" @change="excelExport"
                            accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />
                        <!--<v-btn @click="excelExport">Enviar</v-btn>-->
                    </form>

                </v-col>

                <!--<form @submit.prevent="uploadFile">
                    <input type="file" ref="fileInput">
                    <button type="submit">Enviar archivo</button>
                                                                                                                                                                                                                                                                        </form>-->
            </v-row>

            <v-row>
                <v-col>
                    <v-select v-model="selectedSheet" :items="sheetList" item-text="nombre_sheet" item-value="id"
                        return-object label="Selecione Hoja" @change="onchangeSheet" outlined></v-select>
                </v-col>
            </v-row>
            <!--{{ mostrar(this.excelData) }}-->

            <!--</div>-->
            <!--{{ this.rowObj }}-->
            <!---<div class="wrapper-dgxl">
                                                                                                                <div ref="dgxl" class="grid"></div>-->
            <!--<input type="button" value="Add new row" @click="dgxlObj.insertEmptyRows()" />
                                                                                                                                                            <input type="button" value="Download data as CSV" @click="dgxlObj.downloadDataAsCSV()" /><br />
                                                                                                                                                                                                                                                                                                                        s                                                                                                                                                -->
            <v-row v-if="selectedSheet != null">
                <v-col>
                    <v-btn tile color="success" @click="save">
                        <v-icon left>
                            mdi-content-save-settings
                        </v-icon>
                        Subir Datos
                    </v-btn>
                </v-col>
                <v-col>
                    <v-btn tile color="info" @click="save2">
                        <v-icon left>
                            mdi-content-save-settings
                        </v-icon>
                        Seleccion
                    </v-btn>
                </v-col>
                <v-col>

                    <v-btn @click="descargarDatos">Descargar datos</v-btn>
                </v-col>
            </v-row>


        </v-card>
    </AppLayout>
</template>

<script>

import XLSX from 'xlsx'
import DataGridXL from "@datagridxl/datagridxl2";
import FormData from 'form-data';
import AppLayout from '@/Layouts/AppLayout'
//import fs from 'fs';
export default {
    data() {
        return {
            excelData: [{ a: 1, b: 3 }],
            file: null,
            input: null,
            selectedSheet: null,
            sheetName: null,
            sheetList: [],
            sheets: [{ name: "SheetOne", data: [{ c: 2 }] }],
            collection: [],
            dgxl_nl_NL: {

                // Context Menu Item Presets

                "Copy": "Copiar",
                "Cut": "Cortar",
                "Paste": "Pegar",
                "Delete Row(s)": "Eliminar Filas(s)",
                "Insert Row (up)": "Insertar Fila por Encima",
                "Insert Row (down)": "Insertar Fila por  Debajo",
                "Delete Column(s)": "Eliminar Columna(s)",
                "Insert Column (left)": "Insertar Columna Derecha",
                "Insert Column (right)": "Insertar Columna Izqu0ierda",
                "Deselect": "Deseleccionar",
                "Search": "Buscar",
                'Hide Column': 'Ocultar columna',
                'Hide Row': 'Ocultar fila',
                'Sort A to Z': 'Ordenar A a Z',
                'Sort Z to A': 'Ordenar Z a A',




            },
            Workerbook: null,
            WorkerHeader: null,
            header: [],
            DataGridXL: null,
            input: null,
            reader: null,
            fileData: null,
            rowObj: null,
            wb: null,
            rowObj: null,
            data: [
                ['Nombre', 'Apellido', 'Edad'],
                ['Juan', 'Pérez', 25],
                ['María', 'García', 30],
                ['Pedro', 'Martínez', 40]
            ],
        };
    },
    components: {
        AppLayout,

    },
    destroyed() {
        console.log('destroy');

        this.$destroy();
        //this.input = null
        //this.excelData = null
        this.$refs = null
        delete (this.DataGridXL)
        delete (this.excelData)
        delete (this.file)
        delete (this.input)
        delete (this.selectedSheet)
        delete (this.sheetName)
        delete (this.sheetList)
        delete (this.sheets)
        delete (this.collection)
        delete (this.dgxl_nl_NL)
        delete (this.$refs)
        this.Workerbook = null,
            this.WorkerHeader = null,
            this.header = null,
            this.DataGridXL = null,
            delete this.Workerbook
        delete this.WorkerHeader
        delete this.header
        delete this.DataGridXL
        delete this.input
        delete this.reader
        delete this.fileData
        delete this.rowObj

        delete this.wb
        this.rowObj = null

        this.DataGridXL = null
        this.input = null
        this.reader = null
        this.fileData = null
        this.rowObj = null
        this.wb = null
        this.rowObj = null
        //location.reload(true)
    },
    methods: {
        descargarDatos() {
            if (this.DataGridXL.getData() == null) return

            var keys = this.DataGridXL.getData().map(function (obj) {
                return Object.keys(obj);
            }).reduce(function (acc, val) {
                return acc.concat(val.filter(function (key) {
                    return acc.indexOf(key) === -1;
                }));
            }, []);
            const dataMatrix = this.DataGridXL.getData().map(item => Object.values(item));

            // Crear hoja de cálculo con la matriz de matrices
            console.log(dataMatrix);
            const ws = XLSX.utils.aoa_to_sheet([keys, ...dataMatrix]);
            const wb = XLSX.utils.book_new();
            //const ws = XLSX.utils.json_to_sheet(this.DataGridXL.getData());
            XLSX.utils.book_append_sheet(wb, ws, 'Datos');

            // Generar archivo y descargar
            XLSX.writeFile(wb, 'datos.xlsx', { bookType: 'xlsx', type: 'buffer' });
            const url = window.URL.createObjectURL(new Blob([wb], { type: 'application/octet-stream' }));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', 'datos.xlsx');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        },

        async uploadFile() {
            const file = this.$refs.fileInput.files[0];

            const formData = new FormData();
            formData.append('file', file);
            console.log(formData);
            try {
                let r = await axios.post(`/${process.env.MIX_CARPETA}/api/abrir_excel`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                console.log(r.data);
                console.log(r.data['file'])
                this.DataGridXL = new DataGridXL(this.$refs.dgxl, {
                    data: r.data['file'],
                    locale: this.dgxl_nl_NL,

                });
            } catch (error) {
                alert('Error al subir archivo');
            }
        },
        close() {

        },
        limpiar() {
            console.log('destroy');

            //this.$destroy();
            //this.input = null
            //this.excelData = null
            this.$refs = null
            delete (this.DataGridXL)
            delete (this.excelData)
            delete (this.file)
            delete (this.input)
            delete (this.selectedSheet)
            delete (this.sheetName)
            delete (this.sheetList)
            delete (this.sheets)
            delete (this.collection)
            delete (this.dgxl_nl_NL)
            delete (this.$refs)
            this.Workerbook = null,
                this.WorkerHeader = null,
                this.header = null,
                this.DataGridXL = null,
                delete this.Workerbook
            delete this.WorkerHeader
            delete this.header
            delete this.DataGridXL
            delete this.input
            delete this.reader
            delete this.fileData
            delete this.rowObj

            delete this.wb
            this.rowObj = null

            this.DataGridXL = null
            this.input = null
            this.reader = null
            this.fileData = null
            this.rowObj = null
            this.wb = null
            this.rowObj = null
        },
        async excelExport(event) {
            const file = this.$refs.fileInput.files[0];

            const formData = new FormData();
            formData.append('file', file);
            var res = await axios({
                method: 'post',
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
                url: `/${process.env.MIX_CARPETA}/api/get_sheet_excel`,
                data: formData,
                timeout: 1200000 

            }).then(
                (response) => {
                    console.log(response);
                    this.sheetList = response['data']; //Array of sheet names.
                    //console.log(this.sheetList[0]);


                    this.selectedSheet = this.sheetList[0];
                    //this.$alert('Se a cambiado la contraseña correctamente.').then(res => this.$inform("Cambiar contraseña!"));
                }
            ).catch(err => {
                if (err.response.status == 401) {
                    window.location.href = `/${process.env.MIX_CARPETA}/login`

                }
                if (err.response.status == 419) {
                    window.location.href = `/${process.env.MIX_CARPETA}/login`

                }
                console.log(err)
                console.log("err->", err.response.data)
                this.$alert('Hubo un error en el archivo ').then(res => this.$err("Cambiar contraseña!"));
                return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
            });
            /*var input = this.$refs.excelFile
            const file = input.files[0]
            const formData = new FormData();
            formData.append("file", file)
            console.log(formData);
     
            var res = await axios({
                method: 'post',
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
                url: `/${process.env.MIX_CARPETA}/api/abrir_excel`,
                data: {
                    archivo: formData,
                },
            }).then(
                (response) => {
                    console.log(response);
     
                    //this.$alert('Se a cambiado la contraseña correctamente.').then(res => this.$inform("Cambiar contraseña!"));
                }
            ).catch(err => {
                if (err.response.status == 401) {
                    window.location.href = `/${process.env.MIX_CARPETA}/login`
     
                }
                if (err.response.status == 419) {
                    window.location.href = `/${process.env.MIX_CARPETA}/login`
     
                }
                console.log(err)
                console.log("err->", err.response.data)
                this.$alert('Se fallo en cambio.').then(res => this.$err("Cambiar contraseña!"));
                return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
            });
            */
            /*
     
            this.reader = new FileReader();
     
            this.reader.onload = () => {
                //leemos todo el archivo 
                this.fileData = this.reader.result;
                this.wb = XLSX.read(this.fileData, { type: 'binary', dense: true });
                
                //obtenemos las hojas o sheetnames
                this.sheetList = this.wb.SheetNames; //Array of sheet names.
                //console.log(this.sheetList[0]);
     
     
     
                this.selectedSheet = this.sheetList[0];
                //console.log(this.selectedSheet);
                //console.log(this.Workerbook)
                //this.excelFile = XLSX.utils.sheet_to_json(wb.Sheets[this.selectedSheet]
                const workbookHeaders = XLSX.read(this.fileData, { type: 'binary', sheetRows: 1 });
                console.log(workbookHeaders);
                /*this.header = XLSX.utils.sheet_to_json(workbookHeaders.Sheets[this.selectedSheet], { header: 1, ram: true })[0];
                const range = {s:{c:0, r:0}, e:{c:2, r:100}}; 
                this.rowObj = XLSX.utils.sheet_to_json(this.wb.Sheets[this.selectedSheet], { defval: "", dense: true, ram: true, range })
                    .map(row =>
                        Object.keys(row).reduce((obj, key) => {
                            obj[key.trim()] = row[key];
                            return obj;
                        }, {})
                    );
                console.log(this.rowObj);
                this.DataGridXL = new DataGridXL(this.$refs.dgxl, {
                    data: this.rowObj,
                    locale: this.dgxl_nl_NL,
     
                });
                /*  
                //obtenermo los encabezados del archivo
                const workbookHeaders = XLSX.read(fileData, { type: 'binary', sheetRows: 1 });
                this.WorkerHeader = structuredClone(workbookHeaders)
                this.header = XLSX.utils.sheet_to_json(workbookHeaders.Sheets[this.selectedSheet], { header: 1 })[0];
     
                for (let i = 0; i < this.header.length; i++) {
                    this.header[i] = this.header[i].trim();
                }
     
     
     
     
     
                //obtenemos los datos en json  de la primiera hoja
                var rowObj = XLSX.utils.sheet_to_json(wb.Sheets[this.selectedSheet], { defval: "" })
                    .map(row =>
                        Object.keys(row).reduce((obj, key) => {
                            obj[key.trim()] = row[key];
                            return obj;
                        }, {})
                    );
     
                console.log(rowObj)
                this.excelData = JSON.stringify(rowObj)
                console.log('__________')
                console.log(JSON.parse(this.excelData))
     
     
     
     
     
     
     
     
                //adicionamos a datagridxl o la vista en excel 
                this.DataGridXL = new DataGridXL(this.$refs.dgxl, {
                    data: JSON.parse(this.excelData),
                    locale: this.dgxl_nl_NL
                });
                var eventHandler = function (gridEvent) {
                    console.log(gridEvent);
     
                };
     
                // add event handler
                this.DataGridXL.events.on('setselection', eventHandler);
                //console.log(this.$attrssheetList)
                /*wb.SheetNames.forEach((sheetName) => {
                    console.log(sheetName);
                    var rowObj = XLSX.utils.sheet_to_json(wb.Sheets[sheetName], { defval: "" });
                    this.excelData = JSON.stringify(rowObj)
                    console.log(JSON.parse(this.excelData))
                    const workbookHeaders = XLSX.read(fileData, { type: 'binary', sheetRows: 1  });
                    const columnsArray = XLSX.utils.sheet_to_json(workbookHeaders.Sheets[sheetName], { header: 1 });
                    console.log(columnsArray);
     
                    const dgxlObj = new DataGridXL(this.$refs.dgxl, {
                        data: JSON.parse(this.excelData),
                        locale: this.dgxl_nl_NL
                    });
     
                })*/
            //};
            //console.log(input.files)
            //this.reader.readAsBinaryString(input.files[0]);
            /*reader = null
            input = null
            fileData = null
            rowObj = null
            */

        },
        onchangeSheet(event) {
            //location.reload(true)
            /*delete (this.Workerbook)
            //this.limpiar()
            this.rowObj = null
            this.DataGridXL = null
            this.rowObj = XLSX.utils.sheet_to_json(this.wb.Sheets[this.selectedSheet], { defval: "", raw: true })
                .map(row =>
                    Object.keys(row).reduce((obj, key) => {
                        obj[key.trim()] = row[key];
                        return obj;
                    }, {})
                );
            console.log(this.rowObj);
    
            //console.log(Object.keys(this.rowObj).length)
            this.DataGridXL = new DataGridXL(this.$refs.dgxl, {
                data: this.rowObj,
                locale: this.dgxl_nl_NL,
    
            });*/


            /*
            var wb = this.Workerbook
            var rowObj = XLSX.utils.sheet_to_json(wb.Sheets[this.selectedSheet], { defval: "" });
            this.excelData = JSON.stringify(rowObj)
            console.log(JSON.parse(this.excelData))
     
            //obtenermo los encabezados del archivo
            const workbookHeaders = this.WorkerHeader
            const columnsArray = XLSX.utils.sheet_to_json(workbookHeaders.Sheets[this.selectedSheet], { header: 1 });
            console.log(columnsArray);
     
     
     
            //adicionamos a datagridxl o la vista en excel 
            this.DataGridXL = new DataGridXL(this.$refs.dgxl, {
                data: JSON.parse(this.excelData),
                locale: this.dgxl_nl_NL,
     
            });
            var eventHandler = function (gridEvent) {
                console.log(gridEvent);
     
            };
     
            // add event handler
            this.DataGridXL.events.on('setselection', eventHandler);
            // remove event handler
     
            //grid.events.off, eventHandler);
            */


        },
        mostrar(x) {
            //console.log(x)
            //const dgxlObj = new DataGridXL(this.$refs.dgxl, {data: x});
            //x = []
            //Object.assign(this, { dgxlObj })

        },
        onChange(event) {
            console.log(event)
            this.file = event.target.files ? event.target.files[0] : null;
            console.log("-----")
            console.log(event)
            this.DataGridXL = new DataGridXL(this.$refs.dgxl, {
                data: event,
                locale: this.dgxl_nl_NL
            });
            this.DataGridXL.events.on('setselection', eventHandler);
        },
        addSheet() {


            this.sheets.push({ name: this.sheetName, data: [...this.collection] });

            this.sheetName = null;
        },
        async save() {
            const file = this.$refs.fileInput.files[0];

            const formData = new FormData();
            formData.append('file', file);
            formData.append('sheet', JSON.stringify(this.selectedSheet));
            //console.log(this.selectedSheet);
            var res = await axios({
                method: 'post',
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
                url: `/${process.env.MIX_CARPETA}/api/update_sheet_excel`,
                data: formData,
                timeout: 1200000 // tiempo de espera en milisegundos (20 minutos)
            }).then(
                (response) => {
                    console.log(response);
                    alert('Se ha insertado correctamente.' + response['data']['nro_insertado']);

                    if (response['data']['nro_blancos'] > 0) {
                        alert('Existen una cantidad de ' + response['data']['nro_blancos'] + ' personas en blanco que no son insertados');

                    }
                    if (response['data']['nro_repetido'] > 0) {
                        alert('Existen una cantidad de ' + response['data']['nro_repetido'] + ' personas repetidas que no son insertadas');

                    }
                    //this.sheetList = response['data']; //Array of sheet names.
                    //console.log(this.sheetList[0]);



                    //this.selectedSheet = this.sheetList[0];
                    //this.$alert('Se a cambiado la contraseña correctamente.').then(res => this.$inform("Cambiar contraseña!"));
                }
            ).catch(err => {
                if (err.response.status == 401) {
                    window.location.href = `/${process.env.MIX_CARPETA}/login`

                }
                if (err.response.status == 419) {
                    window.location.href = `/${process.env.MIX_CARPETA}/login`

                }
                console.log(err)
                console.log("err->", err.response.data.message)
                this.$alert(err.response.data.message).then(res => this.$err("Cambiar contraseña!"));
                return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
            });
            /*console.log('...s');
            console.log(this.DataGridXL.getData());
            var objetos = {
                'datos': this.DataGridXL.getData(),
                'header': this.header
            }

            this.$emit('guardar_datos', objetos)*/
        },
        async save2() {
            const file = this.$refs.fileInput.files[0];

            const formData = new FormData();
            formData.append('file', file);
            formData.append('sheet', JSON.stringify(this.selectedSheet));
            //console.log(this.selectedSheet);
            var res = await axios({
                method: 'post',
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
                url: `/${process.env.MIX_CARPETA}/api/prueba_excel`,
                data: formData,
            }).then(
                (response) => {
                    console.log(response);
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    // utilizar window.open para descargar el archivo
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'archivo.xlsx'); // nombre del archivo
                    document.body.appendChild(link);
                    link.click();
                    //this.sheetList = response['data']; //Array of sheet names.
                    //console.log(this.sheetList[0]);



                    //this.selectedSheet = this.sheetList[0];
                    //this.$alert('Se a cambiado la contraseña correctamente.').then(res => this.$inform("Cambiar contraseña!"));
                }
            ).catch(err => {
                if (err.response.status == 401) {
                    window.location.href = `/${process.env.MIX_CARPETA}/login`

                }
                if (err.response.status == 419) {
                    window.location.href = `/${process.env.MIX_CARPETA}/login`

                }
                console.log(err)
                console.log("err->", err.response.data)
                this.$alert('Se fallo en cambio.').then(res => this.$err("Cambiar contraseña!"));
                return res.status(500).send({ ret_code: ReturnCodes.SOMETHING_WENT_WRONG });
            });
            /*console.log('...s');
            console.log(this.DataGridXL.getData());
            var objetos = {
                'datos': this.DataGridXL.getData(),
                'header': this.header
            }

            this.$emit('guardar_datos', objetos)*/
        },
    },
    computed: {

    },

};
</script>

<!-- Use preprocessors via the lang attribute! e.g. <style lang="scss"> -->
<style>
#app {
    font-family: Avenir, Helvetica, Arial, sans-serif;
    text-align: center;
    color: #2c3e50;
    margin-top: 60px;
}

.grid {
    height: 400px;
}
</style>