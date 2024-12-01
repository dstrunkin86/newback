<template>
    <el-container>
        <el-main>
            <div class="panel-header">Работы</div>
            <div v-if="(typeof newDataRows.data !== 'undefined')&&(newDataRows.data.length > 0)" class="panel-subheader">Требуют обработки</div>
            <el-table v-if="(typeof newDataRows.data !== 'undefined')&&(newDataRows.data.length > 0)" :data="newDataRows.data" stripe>
                <el-table-column prop="image" label="Изображение">
                    <template slot-scope="scope">
                        <el-image style="width: 150px; height: 150px" :src="scope.row.images[0].url" fit="cover"></el-image>
                    </template>
                </el-table-column>
                <el-table-column prop="id" label="ID" width="50">
                </el-table-column>
                <el-table-column prop="title.ru" label="Название">
                </el-table-column>
                <el-table-column prop="artist.fio.ru" label="Художник">
                </el-table-column>
                <el-table-column prop="artist.year" label="Год">
                </el-table-column>
                <el-table-column label="Действия" width="150">
                    <template slot-scope="scope">
                        <el-button-group style="font-size: 20px">
                            <i @click="editRow(scope.row, scope.$index)" class="el-icon-edit" style="color: gray"></i>
                            <el-popconfirm @confirm="deleteRow(scope.row.id, scope.$index, 'newDataRows')"
                                title="Точно хотите удалить?" confirm-button-text="Да" cancel-button-text="Нет">
                                <i slot="reference" style="color: red" class="el-icon-delete"></i>
                            </el-popconfirm>

                        </el-button-group>
                    </template>
                </el-table-column>
            </el-table>
            <el-pagination layout="pager" :current-page="newDataRows.current_page" :total="newDataRows.total" :page-size="newDataRows.per_page" @current-change="newDataRowsPageChanged"> </el-pagination>


            <div v-if="(typeof dataRows.data !== 'undefined')&&(dataRows.data.length > 0)" class="panel-subheader">Картины в галерее</div>
            <el-table v-if="(typeof dataRows.data !== 'undefined')&&(dataRows.data.length > 0)" :data="dataRows.data" stripe>
                <el-table-column prop="image" label="Изображение">
                    <template slot-scope="scope">
                        <el-image style="width: 150px; height: 150px" :src="(scope.row.images.length > 0) ? scope.row.images[0].url:''" fit="cover"></el-image>
                    </template>
                </el-table-column>
                <el-table-column prop="id" label="ID" width="50">
                </el-table-column>
                <el-table-column prop="title.ru" label="Название">
                </el-table-column>
                <el-table-column prop="artist.fio.ru" label="Художник">
                </el-table-column>
                <el-table-column prop="status" label="Статус" :formatter="formatStatus">
                </el-table-column>
                <el-table-column label="Действия" width="150">
                    <template slot-scope="scope">
                        <el-button-group style="font-size: 20px">
                            <i @click="editRow(scope.row, scope.$index)" class="el-icon-edit" style="color: blue"></i>
                            <el-popconfirm @confirm="deleteRow(scope.row.id, scope.$index, 'dataRows')"
                                title="Точно хотите удалить?" confirm-button-text="Да" cancel-button-text="Нет">
                                <i slot="reference" style="color: red" class="el-icon-delete"></i>
                            </el-popconfirm>
                        </el-button-group>
                    </template>
                </el-table-column>
            </el-table>
            <el-pagination layout="pager" :current-page="dataRows.current_page" :total="dataRows.total" :page-size="dataRows.per_page" @current-change="dataRowsPageChanged"> </el-pagination>



            <el-dialog v-if="editRowData != null"
                :title="((editRowData != null) && (editRowData.id > 0)) ? 'Редактирование' : 'Создание'"
                :visible.sync="editDialogVisible">
                <el-form :model="editRowData" label-width="200px" size="mini">
                    <el-form-item label="Изображения">
                        <el-upload action="" list-type="picture-card" :fileList="editRowData.images"
                            :http-request="uploadImage" :on-remove="deleteImage">
                            <i class="el-icon-plus"></i>
                        </el-upload>
                    </el-form-item>
                    <el-form-item label="Художник">
                        <span>{{ editRowData.artist.fio.ru }}</span>
                    </el-form-item>
                    <el-divider></el-divider>

                    <el-form-item v-for="lang in langs" :label="'Название' + lang.lineEnding"  :key="'title'+lang.value">
                        <el-input v-model="editRowData.title[lang.value]" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-divider></el-divider>

                    <el-form-item v-for="lang in langs" :label="'Описание' + lang.lineEnding"  :key="'description'+lang.value">
                        <el-input type="textarea" v-model="editRowData.description[lang.value]" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-divider></el-divider>

                    <el-form-item label="Год">
                        <el-input v-model="editRowData.year" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="Местоположение">
                        <el-input v-model="editRowData.location" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-divider></el-divider>
                    <el-form-item label="Высота (см.)">
                        <el-input v-model="editRowData.height" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="Ширина (см.)">
                        <el-input v-model="editRowData.width" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="Глубина (см.)">
                        <el-input v-model="editRowData.depth" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="Вес (гр.)">
                        <el-input v-model="editRowData.weight" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-divider></el-divider>
                    <el-form-item label="Работа продается">
                        <el-radio-group v-model="editRowData.in_sale">
                            <el-radio-button label="1">Да</el-radio-button>
                            <el-radio-button label="0">Нет</el-radio-button>
                        </el-radio-group>
                    </el-form-item>
                    <el-form-item label="Цена (руб.)" v-if="editRowData.in_sale > 0">
                        <el-input v-model="editRowData.price" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-divider></el-divider>
                    <el-form-item label="Тэги">
                        <el-select v-model="editRowData.tags" multiple placeholder="Выберите тэги">
                            <el-option-group v-for="group in tags" :key="group.label" :label="group.label">
                                <el-option v-for="item in group.options" :key="item.value" :label="item.label"
                                    :value="item.value">
                                </el-option>
                            </el-option-group>
                        </el-select>
                    </el-form-item>
                    <el-divider></el-divider>
                    <el-form-item label="Подборки">
                        <el-select v-model="editRowData.compilations" multiple placeholder="Выберите подборки">
                            <el-option v-for="item in compilations" :key="item.id" :label="item.title.ru"
                                :value="item.id"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-divider></el-divider>
                    <el-form-item label="Статус">
                        <el-select v-model="editRowData.status" width="100%">
                            <el-option label="Новая" value="new" disabled></el-option>
                            <el-option label="Допущена в галерею" value="accepted"></el-option>
                            <el-option label="Отклонена" value="rejected"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="Комментарий к статусу">
                        <el-input type="textarea" v-model="editRowData.status_comment" autocomplete="off"></el-input>
                    </el-form-item>
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
import { artworks, tags, compilations, appLangs } from "../../api_connectors";
export default {
    name: "ArtworksIndex",
    data() {
        return {
            dataRows: [],
            dataRowsPage: 1,
            newDataRows: [],
            newDataRowsPage: 1,
            editRowData: null,
            editDialogVisible: false,
            tags: [],
            compilations: [],
            langs: appLangs
        };
    },
    mounted() {
        this.getData();
    },
    methods: {
        getData() {
            this.$loading();

            let promises = [];
            promises.push(artworks.list({ 'status_in': ['new'] }));
            promises.push(artworks.list({ 'status_in': ['accepted', 'rejected'] }));
            promises.push(tags.forSelect());
            promises.push(compilations.list());

            Promise.all(promises)
                .then((response) => {
                    this.newDataRows = response[0].data;
                    this.dataRows = response[1].data;
                    console.log('incoming', this.dataRows);
                    this.tags = response[2].data;
                    this.compilations = response[3].data;
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
        newDataRowsPageChanged(page) {
            this.$loading();
            this.newDataRowsPage = page;
            artworks.
                list({ 'status_in': ['new'] }, this.newDataRowsPage)
                    .then((response) => {
                        this.newDataRows = response.data;
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
        dataRowsPageChanged(page) {
            this.$loading();
            this.dataRowsPage = page;
            artworks.
                list({ 'status_in': ['accepted', 'rejected'] }, this.dataRowsPage)
                    .then((response) => {
                        this.dataRows = response.data;
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
            //console.log('inFunction',data);
            this.editRowData = data;
            let tags = [];
            if (this.editRowData.tags) {
                this.editRowData.tags.forEach((element) => tags.push(element.id));
                this.editRowData.tags = tags;

            }

            let compilations = [];
            if (this.editRowData.compilations) {
                this.editRowData.compilations.forEach((element) => compilations.push(element.id));
                this.editRowData.compilations = compilations;
            }
            console.log(this.editRowData);
            this.editDialogVisible = true;
        },
        cancelEditRow() {
            this.editRowData = null;
            this.editDialogVisible = false;
        },
        deleteRow(id, position, varName = 'dataRows') {
            this.$loading();
            artworks
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
                    ? artworks.update(
                        this.editRowData.id,
                        this.editRowData
                    )
                    : artworks.create(this.editRowData);
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
        formatStatus(row, column, cellValue, index) {
            var arr = new Map([
                ['new', 'Новая'],
                ['accepted', 'Допущена в галерею'],
                ['rejected', 'Отклонена'],
            ]);

            return arr.get(cellValue);
        },
        uploadImage(params) {
            console.log(params);
            var formData = new FormData();
            formData.append("file", params.file);

            artworks
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
            artworks
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
