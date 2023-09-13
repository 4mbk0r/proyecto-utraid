<template>
    <v-container>
        <v-row>
            <v-col>
                <h1>Importar Personal</h1>
            </v-col>
        </v-row>
        <v-divider vertical></v-divider>
        <!-- Mensaje para cargar el archivo -->
        <v-row>
            <v-col>
                <p>Aquí debe subir un archivo Excel con los datos de personal:</p>
                <ul>
                    <li>Asegúrese de que el archivo esté en formato Excel (xlsx).</li>
                    <li>Seleccione el archivo haciendo clic en el botón de carga a continuación.</li>
                    <li>Después de cargar el archivo, haga clic en "Guardar" para guardar los datos.</li>
                </ul>
            </v-col>
            <v-col>
                <!-- Columna para mostrar información de la plantilla y el botón de descarga -->
                <div class="template-info">
                    <p>Descargue la plantilla de Excel necesaria con el formato requerido:</p>

                    <v-btn tile color="primary" @click="downloadTemplate">Descargar Plantilla</v-btn>
                </div>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <form @submit.prevent="uploadFile">
                    <input type="file" ref="fileInput" @change="uploadFile">
                </form>
            </v-col>
            <v-col>
                <v-btn tile color="success" @click="save">
                    <v-icon left>
                        mdi-content-save-settings
                    </v-icon>
                    Guardar
                </v-btn>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <v-select v-model="selectedSheet" :items="sheetList" label="Seleccione Hoja" @change="onchangeSheet"
                    outlined>
                </v-select>
            </v-col>
        </v-row>
        <div class="wrapper-dgxl">
            <div ref="dgxl" class="grid"></div>
        </div>

    </v-container>
</template>
<script>

import XLSX from 'xlsx'
import DataGridXL from "@datagridxl/datagridxl2";
import FormData from 'form-data';
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
        };
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
        async uploadFile() {
            const file = this.$refs.fileInput.files[0];

            const formData = new FormData();
            formData.append('file', file);
            console.log(formData);
            try {
                let r = await axios.post(`/${process.env.MIX_CARPETA}/api/personal_excel`, formData, {
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
                if (r.data['header']) {
                    this.$alert('Todos los datos fueron ingresados correctamente').then((res) => this.$inform("Cambios guardados!"));
                } else {
                    this.$alert('Existen errores en cargar los datos').then((res) => this.$error("Datos no guardados!"));
                }
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

            var input = this.$refs.excelFile
            const file = input.files[0]
            const formData = new FormData();
            formData.append("file", file)
            console.log(formData);

            var res = await this.axios({
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
                    //this.headers = response.data
                    //console.log('Responder');
                    console.log(response.data)
                    this.DataGridXL = new DataGridXL(this.$refs.dgxl, {
                        data: r.data['file'],
                        locale: this.dgxl_nl_NL,

                    });
                    if (r.data['verificar']) {
                        this.$alert('Todos los datos fueron ingresados correctamente').then((res) => this.$inform("Cambios guardados!"));
                    } else {
                        this.$alert('Existen errores en cargar los datos').then((res) => this.$error("Datos no guardados!"));
                    }
                    /*input = null
                    file = null
                    formData = null*/

                }).catch((error) => {
                    console.log(error.data);

                });

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
            delete (this.Workerbook)
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

            });


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
            var res = await this.axios({
                method: 'post',

                url: `/${process.env.MIX_CARPETA}/api/personal_excel_json`,
                data: {
                    archivo: this.DataGridXL.getData(),
                },

            }).then(
                (response) => {
                    //this.headers = response.data
                    //console.log('Responder');
                    console.log(response.data)
                    this.DataGridXL = new DataGridXL(this.$refs.dgxl, {
                        data: response.data['datos'],
                        locale: this.dgxl_nl_NL,

                    });
                    if (response.data['verificar']) {
                        this.$alert('Todos los datos fueron ingresados correctamente').then((res) => this.$inform("Cambios guardados!"));
                    } else {
                        this.$alert('Existen errores en cargar los datos').then((res) => this.$error("Datos no guardados!"));
                    }
                    /*input = null
                    file = null
                    formData = null*/

                }).catch((error) => {
                    console.log(error.data);

                });
        },
        save2() {
            console.log("________________");
            console.log(this.DataGridXL.getSelection());
            return;
        },
        downloadTemplate() {
            // Aquí puedes agregar el código para descargar la plantilla de Excel.
            // Puedes usar un enlace de descarga o una solicitud al servidor para obtener la plantilla.
            // Por ejemplo:
            // window.location.href = '/ruta/de/la/plantilla.xlsx';
            axios({
                url: `/${process.env.MIX_CARPETA}/api/descargarPantilla`,
                method: 'GET',
                responseType: 'blob', // Indica que la respuesta es un archivo binario
            })
                .then((response) => {
                    // Crea un objeto URL para el archivo Excel
                    const blob = new Blob([response.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                    const url = window.URL.createObjectURL(blob);

                    // Crea un elemento de enlace para descargar el archivo Excel
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'usuarios.xlsx'; // Nombre del archivo Excel

                    // Simula hacer clic en el enlace para iniciar la descarga
                    a.click();

                    // Limpia el objeto URL y el elemento de enlace
                    window.URL.revokeObjectURL(url);
                })
                .catch((error) => {
                    console.error('Error al descargar la plantilla:', error);
                });
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