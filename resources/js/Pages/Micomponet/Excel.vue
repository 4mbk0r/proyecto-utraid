<!-- Use preprocessors via the lang attribute! e.g. <template lang="pug"> -->
<template>
    <v-app>
        <div id="app">
            <h1>Importar</h1>
            <div>
                <input type="file" id="excelFile" @change="excelExport"
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




            }
        };
    },
    methods: {
        excelExport(event) {
            console.log(event);
            var input = event.target;
            var reader = new FileReader();
            reader.onload = () => {
                var fileData = reader.result;
                var wb = XLSX.read(fileData, { type: 'binary' });
                this.sheetList = wb.SheetNames; //Array of sheet names.

                console.log(this.$attrssheetList)
                wb.SheetNames.forEach((sheetName) => {
                    console.log(sheetName);
                    var rowObj = XLSX.utils.sheet_to_json(wb.Sheets[sheetName], { defval: "" });
                    this.excelData = JSON.stringify(rowObj)
                    console.log(JSON.parse(this.excelData))
                    const dgxlObj = new DataGridXL(this.$refs.dgxl, {
                        data: JSON.parse(this.excelData),
                        locale: this.dgxl_nl_NL
                    });

                })
            };

            reader.readAsBinaryString(input.files[0]);


        },
        onchangeSheet(event) {
            var input = event.target;
            var reader = new FileReader();
            const header = []
            reader.onload = () => {
                var fileData = reader.result;

                //const header = []


                var wb = XLSX.read(fileData, { type: 'binary' });
                this.sheetList = wb.SheetNames; //Array of sheet names.

                //console.log(this.$attrssheetList)
                var rowObj = XLSX.utils.sheet_to_json(wb.Sheets[this.selectedSheet], { defval: "" });
                this.excelData = JSON.stringify(rowObj)
               //console.log(JSON.parse(this.excelData))

                var range = XLSX.utils.decode_range(sheet['!ref']);
                console.log('----'+range);
                var C, R = range.s.r; /* start in the first row */
                /* walk every column in the range */
                for (C = range.s.c; C <= range.e.c; ++C) {
                    var cell = sheet[XLSX.utils.encode_cell({ c: C, r: R })] /* find the cell in the first row */

                    var hdr = "UNKNOWN " + C; // <-- replace with your desired default 
                    if (cell && cell.t) hdr = XLSX.utils.format_cell(cell);

                    headers.push(hdr);
                }
                
                const dgxlObj = new DataGridXL(this.$refs.dgxl, {
                    data: JSON.parse(this.excelData),
                    locale: this.dgxl_nl_NL
                });
            };

            console.log(this.selectedSheet)
            console.log(header);

        },
        mostrar(x) {
            //console.log(x)
            //const dgxlObj = new DataGridXL(this.$refs.dgxl, {data: x});
            //x = []
            //Object.assign(this, { dgxlObj })

        },
        onChange(event) {

            this.file = event.target.files ? event.target.files[0] : null;
            console.log("-----")
            console.log(event)
            const dgxlObj = new DataGridXL(this.$refs.dgxl, {
                data: event,
                locale: this.dgxl_nl_NL
            });
        },
        addSheet() {


            this.sheets.push({ name: this.sheetName, data: [...this.collection] });

            this.sheetName = null;
        },
        save() {
            this.$emit('guardar_datos', JSON.parse(this.excelData))
        }
    },
    computed: {

    },
    mounted: function () {

        const dgxlObj = new DataGridXL(this.$refs.dgxl, {
            data: this.excelData,
            locale: this.dgxl_nl_NL
        });
        Object.assign(this, { dgxlObj }); // tucks all methods under dgxlObj object in component instance
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