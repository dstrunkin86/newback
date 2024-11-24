<template>
    <el-container>
        <el-main>
            <h2>Требуют обработки</h2>
            <el-table :data="newArtistsRows" stripe>
                <el-table-column prop="fio.ru" label="ФИО">
                </el-table-column>
                <el-table-column prop="status" label="Статус" :formatter="formatStatus">
                </el-table-column>
                <el-table-column prop="artworks" label="Количество работ" :formatter="formatArtworks">
                </el-table-column>
                <el-table-column label="Actions">
                    <template slot-scope="scope">
                        <el-button-group style="font-size: 20px">
                            <i @click="editArtist(scope.row.id)" class="el-icon-edit" style="color: blue"></i>
                            <el-popconfirm @confirm="deleteArtist(scope.row.id, scope.$index, 'newArtistsRows')" title="Точно хотите удалить?"
                                confirm-button-text="Да" cancel-button-text="Нет">
                                <i slot="reference" style="color: red" class="el-icon-delete"></i>
                            </el-popconfirm>
                        </el-button-group>
                    </template>
                </el-table-column>
            </el-table>
            <h2>Художники в галерее</h2>
            <el-table :data="dataRows" stripe>
                <el-table-column prop="fio.ru" label="ФИО">
                </el-table-column>
                <el-table-column prop="status" label="Статус" :formatter="formatStatus">
                </el-table-column>
                <el-table-column prop="artworks" label="Количество работ" :formatter="formatArtworks">
                </el-table-column>
                <el-table-column label="Actions">
                    <template slot-scope="scope">
                        <el-button-group style="font-size: 20px">
                            <i @click="editRow(scope.$index)" class="el-icon-edit" style="color: blue"></i>
                            <el-popconfirm @confirm="deleteArtist(scope.row.id, scope.$index, 'dataRows')" title="Точно хотите удалить?"
                                confirm-button-text="Да" cancel-button-text="Нет">
                                <i slot="reference" style="color: red" class="el-icon-delete"></i>
                            </el-popconfirm>
                        </el-button-group>
                    </template>
                </el-table-column>
            </el-table>
        </el-main>
    </el-container>
</template>

<script>
import { artists, app_langs } from "../../api_connectors";
export default {
    name: "ArtistsIndex",

    data() {
        return {
            dataRows: [],
            newArtistsRows: [],
        };
    },
    mounted() {
        this.getArtists({'status_in': ['new']},'newArtistsRows');
        this.getArtists({'status_in': ['accepted','rejected']},'dataRows');
        //console.log(app_langs);

    },
    methods: {
        getArtists(filter = {}, varName = 'dataRows') {
            artists
                .list(filter)
                .then((response) => {
                    this[varName] = response.data;
                    //console.log(this.dataRows);
                }).catch((error) => {
                    this.$message({
                        message: "Не удалось загрузить данные: " + error,
                        type: "error",
                        duration: 5000,
                        showClose: true,
                    });
                });
        },
        deleteArtist(id, position, varName = 'dataRows') {
            this.$loading();
            artists
                .delete(id)
                .then((response) => {
                    this[varName].splice(position, 1);
                    this.$loading().close();
                    this.$message({
                        message: "Успешное удаление!",
                        type: "success",
                        duration: 3000,
                        showClose: true,
                    });
                })
                .catch((error) => {
                    this.$loading().close();
                    this.$message({
                        message: "Не удалось удалить: " + error,
                        type: "error",
                        duration: 5000,
                        showClose: true,
                    });
                });
        },
        formatStatus(row, column, cellValue, index) {
            var arr = new Map([
                ['new', 'Новый'],
                ['accepted', 'Согласован'],
                ['rejected', 'Отклонен'],
            ]);

            return arr.get(cellValue);
        },
        formatArtworks(row, column, cellValue, index) {
            return cellValue.length;
        },

    },
};
</script>

<style scoped>
.el-main {
    padding: 10px 20px;
}
h2 {
    margin: 20px 0;
}
h2:first-child {
    margin: 0;
}
</style>
