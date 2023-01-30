<!-- Use preprocessors via the lang attribute! e.g. <template lang="pug"> -->
<template>
    <v-app>
        <div id="app">
            <h1>Importar</h1>
            <div>
                <input type="file" ref="excelFile" @change="excelExport"
                    accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />
            </div>
            <div>
                {{ mostrar(this.excelData) }}
                <v-select v-model="selectedSheet" :items="sheetList" label="Selecione Hoja" @change="onchangeSheet"
                    outlined></v-select>
            </div>
            <div class="wrapper-dgxl">
                <div ref="dgxl" class="grid"></div>
                <!--<input type="button" value="Add new row" @click="dgxlObj.insertEmptyRows()" />
                <input type="button" value="Download data as CSV" @click="dgxlObj.downloadDataAsCSV()" /><br />
                -->
            </div>

        </div>
        <v-btn tile color="success" @click="save">
            <v-icon left>
                mdi-content-save-settings
            </v-icon>
            Guardar
        </v-btn>
    </v-app>
</template>

<script>

import XLSX from 'xlsx'
import DataGridXL from "@datagridxl/datagridxl2";
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
                "Insert Column (right)": "Insertar Columna Izquierda",
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
        };
    },
    methods: {
        excelExport(event) {

            var input = this.$refs.excelFile
            var reader = new FileReader();
            reader.onload = () => {
                //leemos todo el archivo 
                var fileData = reader.result;
                var wb = XLSX.read(fileData, { type: 'binary' });
                this.Workerbook = wb
                //obtenemos las hojas o sheetnames
                this.sheetList = wb.SheetNames; //Array of sheet names.
                //console.log(this.sheetList[0]);



                this.selectedSheet = this.sheetList[0];
                console.log(this.selectedSheet);


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
            };
            console.log(input.files)
            reader.readAsBinaryString(input.files[0]);


        },
        onchangeSheet(event) {
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
                locale: this.dgxl_nl_NL
            });


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
        },
        addSheet() {


            this.sheets.push({ name: this.sheetName, data: [...this.collection] });

            this.sheetName = null;
        },
        save() {
            console.log(typeof this.DataGridXL.data);
            var objetos = {
                'datos': this.DataGridXL.data,
                'header': this.header
            }

            this.$emit('guardar_datos', objetos)
        }
    },
    computed: {

    },
    mounted: function () {

        this.DataGridXL =  new DataGridXL(this.$refs.dgxl, {
            data: this.excelData,
            locale: this.dgxl_nl_NL
        });
        //Object.assign(this, { dgxlObj }); // tucks all methods under dgxlObj object in component instance
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