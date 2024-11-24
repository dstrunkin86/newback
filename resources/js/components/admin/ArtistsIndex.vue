<template>
    <el-container>
        <el-main>
            <div class="panel-header">Художники</div>
            <div v-if="newDataRows.length > 0" class="panel-subheader">Требуют обработки</div>
            <el-table :data="newDataRows" stripe>
                <el-table-column prop="fio.ru" label="ФИО">
                </el-table-column>
                <el-table-column prop="source" label="Источник" :formatter="formatSource">
                </el-table-column>
                <el-table-column prop="status" label="Статус" :formatter="formatStatus">
                </el-table-column>
                <el-table-column prop="artworks" label="Количество работ" :formatter="formatArtworks">
                </el-table-column>
                <el-table-column label="Действия" width="150">
                    <template slot-scope="scope">
                        <el-button-group style="font-size: 20px">
                            <i @click="editRow(scope.row, scope.$index)" class="el-icon-edit" style="color: blue"></i>
                            <el-popconfirm @confirm="deleteRow(scope.row.id, scope.$index, 'newDataRows')" title="Точно хотите удалить?"
                                confirm-button-text="Да" cancel-button-text="Нет">
                                <i slot="reference" style="color: red" class="el-icon-delete"></i>
                            </el-popconfirm>
                        </el-button-group>
                    </template>
                </el-table-column>
            </el-table>
            <div class="panel-subheader">Художники в галерее</div>
            <el-table :data="dataRows" stripe>
                <el-table-column prop="fio.ru" label="ФИО">
                </el-table-column>
                <el-table-column prop="source" label="Источник" :formatter="formatSource">
                </el-table-column>
                <el-table-column prop="status" label="Статус" :formatter="formatStatus">
                </el-table-column>
                <el-table-column prop="artworks" label="Количество работ" :formatter="formatArtworks">
                </el-table-column>
                <el-table-column label="Действия" width="150">
                    <template slot-scope="scope">
                        <el-button-group style="font-size: 20px">
                            <i @click="editRow(scope.row, scope.$index)" class="el-icon-edit" style="color: blue"></i>
                            <el-popconfirm @confirm="deleteRow(scope.row.id, scope.$index, 'dataRows')" title="Точно хотите удалить?"
                                confirm-button-text="Да" cancel-button-text="Нет">
                                <i slot="reference" style="color: red" class="el-icon-delete"></i>
                            </el-popconfirm>
                        </el-button-group>
                    </template>
                </el-table-column>
            </el-table>

            <el-dialog v-if="editRowData != null" :title="((editRowData != null) && (editRowData.id > 0)) ? 'Редактирование' : 'Создание'" :visible.sync="editDialogVisible">
                <el-form :model="editRowData" label-width="200px" size="mini">
                    <el-form-item label="Источник">
                        <span>{{ formatSource(null,null,editRowData.source) }}</span>
                    </el-form-item>
                    <el-divider></el-divider>

                    <el-form-item label="Фотографии">
                        <el-upload action="" list-type="picture-card" :fileList="editRowData.images"
                            :http-request="uploadImage" :on-remove="deleteImage">
                            <i class="el-icon-plus"></i>
                        </el-upload>
                    </el-form-item>
                    <el-divider></el-divider>

                    <el-form-item v-for="lang in langs" :label="'Имя' + lang.lineEnding"  :key="'fio'+lang.value">
                        <el-input v-model="editRowData.fio[lang.value]" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-divider></el-divider>

                    <el-form-item label="Email">
                        <el-input v-model="editRowData.email" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="Телефон">
                        <el-input v-model="editRowData.phone" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="vk">
                        <el-input v-model="editRowData.vk" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="telegram">
                        <el-input v-model="editRowData.telegram" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-divider></el-divider>

                    <el-form-item label="Город">
                        <el-input v-model="editRowData.city" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="Страна">
                        <el-input v-model="editRowData.country" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-divider></el-divider>

                    <el-form-item label="Пользователь">
                        <span>{{ (editRowData.user) ? editRowData.user.email : 'не задан' }}</span>
                    </el-form-item>
                    <el-divider></el-divider>

                    <el-form-item label="Статус">
                        <el-select v-model="editRowData.status" width="100%">
                            <el-option label="Новый" value="new"></el-option>
                            <el-option label="Допущен в галерею" value="accepted"></el-option>
                            <el-option label="Отклонен" value="rejected"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="Комментарий к статусу">
                        <el-input type="textarea" v-model="editRowData.status_comment" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-divider></el-divider>
                </el-form>

                <span slot="footer" class="dialog-footer">
                    <el-button @click="cancelEditRow">Отмена</el-button>
                    <el-button type="primary" @click="saveRow()">Сохранить</el-button>
                </span>
            </el-dialog>

        </el-main>
    </el-container>
</template>

<script>
import { artists, appLangs } from "../../api_connectors";
export default {
    name: "ArtistsIndex",

    data() {
        return {
            dataRows: [],
            newDataRows: [],
            editRowData: null,
            editDialogVisible: false,
            langs: appLangs
        };
    },
    mounted() {
        this.getData();
        console.log('appLangs',appLangs);
    },
    methods: {
        getData() {
            this.$loading();

            let promises = [];
            promises.push(artists.list({ 'status_in': ['new'] }));
            promises.push(artists.list({ 'status_in': ['accepted', 'rejected'] }));

            Promise.all(promises)
                .then((response) => {
                    this.newDataRows = response[0].data;
                    this.dataRows = response[1].data;
                    this.$loading().close();
                }).catch((error) => {
                    this.$loading().close();
                    this.$message({
                        message: "Не удалось загрузить данные: " + error,
                        type: "error",
                        duration: 5000,
                        showClose: true,
                    });
                });
        },
        editRow(data, index) {
            this.editRowData = data;
            console.log(this.editRowData);
            this.editDialogVisible = true;
        },
        cancelEditRow() {
            this.editRowData = null;
            this.editDialogVisible = false;
        },
        deleteRow(id, position, varName = 'dataRows') {
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
        saveRow() {
            this.$loading();
            let result =
                this.editRowData.id > 0
                    ? artists.update(
                        this.editRowData.id,
                        this.editRowData
                    )
                    : artists.create(this.editRowData);
            result
                .then((response) => {
                    this.$loading().close();
                    this.$message({
                        message: "Успешное сохранение!",
                        type: "success",
                        duration: 3000,
                        showClose: true,
                    });
                    this.getData();
                    this.editRowData = null;
                    this.editDialogVisible = false;
                })
                .catch((error) => {
                    this.$loading().close();
                    this.$message({
                        message: "Не удалось сохранить данные: " + error,
                        type: "error",
                        duration: 5000,
                        showClose: true,
                    });
                });
        },
        uploadImage(params) {
            console.log(params);
            var formData = new FormData();
            formData.append("file", params.file);

            artists
                .addImage(this.editRowData.id,formData)
                .then((response) => {

                    this.editRowData.images.push({
                        url: response.data.url,
                        status: "success"
                    });
                    console.log('current images',this.editRowData.images);
                })
                .catch((error) => {
                    console.log(error);
                });


        },
        deleteImage(params) {
            artists
                .deleteImage(this.editRowData.id, params.id)
                .then((response) => {
                    console.log('file delete result', response.data);
                })
                .catch((error) => {
                    console.log(error);
                });
            this.editRowData.images = this.editRowData.images.filter(function (obj) {
                return obj.url !== params.url;
            });
            console.log('current images',this.editRowData.images);
        },
        formatStatus(row, column, cellValue, index) {

            var arr = new Map([
                ['new', 'Новый'],
                ['accepted', 'Согласован'],
                ['rejected', 'Отклонен'],
            ]);

            return arr.get(cellValue);

        },
        formatSource(row, column, cellValue, index) {
            var arr = new Map([
                ['arthall', 'ArtHall'],
                ['synergy', 'Synergy'],
                ['old_arthall', 'Старый ArtHall'],
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
