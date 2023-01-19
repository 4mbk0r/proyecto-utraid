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
            </div>
            <div class="wrapper-dgxl">
                <div ref="dgxl" class="grid"></div>
                <input type="button" value="Add new row" @click="dgxlObj.insertEmptyRows()" />
                <input type="button" value="Download data as CSV" @click="dgxlObj.downloadDataAsCSV()" /><br />
            </div>
        </div>
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
            sheets: [{ name: "SheetOne", data: [{ c: 2 }] }],
            collection: [{ a: 1, b: 3 }],
        };
    },
    methods: {
        excelExport(event) {
            var input = event.target;
            var reader = new FileReader();
            reader.onload = () => {
                var fileData = reader.result;
                var wb = XLSX.read(fileData, { type: 'binary' });
                const sheetList = wb.SheetNames; //Array of sheet names.
                console.log(sheetList)
                wb.SheetNames.forEach((sheetName) => {
                    var rowObj = XLSX.utils.sheet_to_json(wb.Sheets[sheetName], { defval: "" });
                    this.excelData = JSON.stringify(rowObj)
                    console.log(JSON.parse(this.excelData))
                    const dgxlObj = new DataGridXL(this.$refs.dgxl, {
                        data: JSON.parse(this.excelData)
                    });

                })
            };

            reader.readAsBinaryString(input.files[0]);


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
                locale: 'es'
            });
        },
        addSheet() {


            this.sheets.push({ name: this.sheetName, data: [...this.collection] });

            this.sheetName = null;
        }
    },
    computed: {

    },
    mounted: function () {
        var dgxl_nl_NL = {

            // Context Menu Item Presets

            "Copy": "Copiar",
            "Cut": "Cortar",
            "Paste": "Pegar",
            "Delete Row(s)": "Eliminar Filas(s)",
            "Insert Row(s) (up)": "Insertar Fila(s) Encima",
            "Insert Row(s) (down)": "Insertar Fila(s) Debajo",
            "Delete Column(s)": "Eliminar Columna(s)",
            "Insert Column(s) (left)": "Insertar Columna(s) Derecha",
            "Insert Column(s) (right)": "Insertar Columna(s) Izquierda",
            "Deselect": "Deseleccionar",
            "Search": "Buscar",

        };
        const dgxlObj = new DataGridXL(this.$refs.dgxl, {
            data: this.excelData,
            locale: dgxl_nl_NL
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