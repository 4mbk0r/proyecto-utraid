<template>
    <v-app>
        <section>
            <h3>Create XLSX</h3>
            <div>
                <input v-model="sheetName" placeholder="type a new sheet name" />
                <button v-if="sheetName" @click="addSheet">Add Sheet</button>
            </div>

            <div>Sheets: {{ sheets }}</div>

            <xlsx-workbook>
                <xlsx-sheet :collection="sheet.data" v-for="sheet in sheets" :key="sheet.name"
                    :sheet-name="sheet.name" />
                <xlsx-download>
                    <button>Download</button>
                </xlsx-download>
            </xlsx-workbook>
        </section>
        <hr />
        <section>
            <h3>Import XLSX</h3>
            <input type="file" @change="onChange" />
            <xlsx-read :file="file">
                <xlsx-sheets>
                    <template #default="{ sheets }">
                        <select v-model="selectedSheet">
                            <option v-for="sheet in sheets" :key="sheet" :value="sheet">
                                {{ sheet }}
                            </option>
                        </select>
                    </template>
                </xlsx-sheets>
                <xlsx-table :sheet="selectedSheet" />
                <xlsx-json :sheet="selectedSheet">
                    <template #default="{ collection }">


                        {{ mostrar(collection) }}

                    </template>
                </xlsx-json>
            </xlsx-read>
        </section>
        <div class="wrapper-dgxl">
            <div ref="dgxl" class="grid"></div>
            <input type="button" value="Add new row" @click="dgxlObj.insertEmptyRows()" />
            <input type="button" value="Download data as CSV" @click="dgxlObj.downloadDataAsCSV()" /><br />
        </div>
    </v-app>
</template>

<script>
import { XlsxRead, XlsxTable, XlsxSheets, XlsxJson, XlsxWorkbook, XlsxSheet, XlsxDownload } from "vue-xlsx"
import DataGridXL from "@datagridxl/datagridxl2";

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
        mostrar(x) {
            console.log(x)
            const dgxlObj = new DataGridXL(this.$refs.dgxl, {
                data: x
            });
            x = []
            //Object.assign(this, { dgxlObj })

        },
        onChange(event) {

            this.file = event.target.files ? event.target.files[0] : null;
            console.log(this.file)
            console.log(this.sheets)
            console.log(this.collection)
        },
        addSheet() {


            this.sheets.push({ name: this.sheetName, data: [...this.collection] });

            this.sheetName = null;
        }
    }
};
</script>
<style>
.grid {
    height: 400px;
}
</style>