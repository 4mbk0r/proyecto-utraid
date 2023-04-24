<template>
    <v-form>
        <v-file-input v-model="selectedFile" accept=".xlsx" label="Seleccionar archivo XLSX"></v-file-input>
        <v-btn @click="uploadFile">Enviar</v-btn>
    </v-form>
</template>

<script>
import { XlsxRead, XlsxTable, XlsxSheets, XlsxJson, XlsxWorkbook, XlsxSheet, XlsxDownload } from "vue-xlsx"
import DataGridXL from "@datagridxl/datagridxl2";
import XLSX from 'xlsx';
export default {
    name: "DataGrid",
    components: {
        XlsxRead,
        XlsxTable,
        XlsxSheets,
        XlsxJson,
        XlsxWorkbook,
        XlsxSheet,
        XlsxDownload
    },
    data() {
        return {
            selectedFile: null,
            file: null,
            selectedSheet: null,
            sheetName: null,
            sheets: [{ name: "SheetOne", data: [{ c: 2 }] }],
            collection: [{ a: 1, b: 3 }],

        };
    },
    computed: {
        dgxlOptions() {
            return {
                data: this.collection
            };
        },
    },
    mounted: function () {
        const dgxlObj = new DataGridXL(this.$refs.dgxl, this.dgxlOptions);
        Object.assign(this, { dgxlObj }); // tucks all methods under dgxlObj object in component instance
    },
    methods: {
        uploadFile() {
            const file = this.selectedFile;
            if (!file) {
                return;
            }
            var data;
            var workbook;
            var sheet_name_list
            var sheet
            var json;
            const reader = new FileReader();
            reader.onload = () => {
                data = new Uint8Array(reader.result);
                workbook = XLSX.read(data, { type: 'array' });
                sheet_name_list = workbook.SheetNames;
                sheet = workbook.Sheets[sheet_name_list[0]];
                json = XLSX.utils.sheet_to_json(sheet);
                console.log(json);
                workbook.Sheets = null;
                workbook.SheetNames = null;
                workbook.Props = null;
                workbook.Custprops = null;
                //workbook = null;
                // Limpiar el buffer o el input de la RAM
                //data = null;
                //reader = null;

                //delete workbook;
                //delete workbook;
            };

            reader.readAsArrayBuffer(file);
            workbook = null;
            // Limpiar el buffer o el input de la RAM
            data = null;
            //reader = null;

        }
        /*mostrar(x) {
            console.log(x)
            const dgxlObj = new DataGridXL(this.$refs.dgxl, {
                data: x
            });
            x = []
            //Object.assign(this, { dgxlObj })

        },
        onChange(event) {

            this.file = event.target.files ? event.target.files[0] : null;
            console.log("-----")
            console.log(event)
            const dgxlObj = new DataGridXL(this.$refs.dgxl, {
                data: event
            });
        },
        addSheet() {


            this.sheets.push({ name: this.sheetName, data: [...this.collection] });

            this.sheetName = null;
        }*/
    }
};
</script>
<style>
.grid {
    height: 400px;
}
</style>