<template>
    <el-container>
        <el-main>
            <div class="panel-header">
                <span>Подборки</span>
                <div style="float: right;">
                    <el-button @click="newRow()" type="success" icon="el-icon-plus">Создать</el-button>
                </div>

            </div>
            <el-table :data="dataRows" stripe>
                <el-table-column prop="image" label="Изображение">
                    <template slot-scope="scope">
                        <el-image style="width: 150px; height: 150px" :src="scope.row.image" fit="cover"></el-image>
                    </template>
                </el-table-column>
                <el-table-column prop="priority" label="Приоритет">
                </el-table-column>
                <el-table-column prop="title.ru" label="Название">
                </el-table-column>
                <el-table-column prop="is_published" label="Опубликована" :formatter="formatIsPublished">
                </el-table-column>
                <el-table-column prop="artworks" label="Количество картин" :formatter="formatArtworks">
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

            <el-dialog v-if="editRowData != null"
                :title="((editRowData != null) && (editRowData.id > 0)) ? 'Редактирование' : 'Создание'"
                :visible.sync="editDialogVisible">
                <el-form :model="editRowData" label-width="200px" size="mini">

                    <el-form-item label="Изображение подборки">
                        <el-upload class="avatar-uploader" action="" :show-file-list="false"
                            :http-request="uploadImage">
                            <img v-if="editRowData.image" :src="editRowData.image" class="avatar">
                            <i v-if="editRowData.image" @click="deleteImage"
                                class="el-icon-delete avatar-delete-icon"></i>
                            <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                        </el-upload>
                    </el-form-item>
                    <el-divider></el-divider>

                    <el-form-item label="Работы в подборке" v-if="(typeof editRowData.artworks !== 'undefined')&&(editRowData.artworks.length > 0)">
                        <el-carousel :interval="5000" type="card" height="200px">

                            <el-carousel-item v-for="item in editRowData.artworks" :key="item.id">
                                <el-image style="height: 100%; width:100%" :src="item.images[0].url"></el-image>
                            </el-carousel-item>
                        </el-carousel>
                    </el-form-item>
                    <el-divider v-if="(typeof editRowData.artworks !== 'undefined')&&(editRowData.artworks.length > 0)"></el-divider>

                    <el-form-item v-for="lang in langs" :label="'Название' + lang.lineEnding"  :key="'title'+lang.value">
                        <el-input v-model="editRowData.title[lang.value]" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-divider></el-divider>

                    <el-form-item v-for="lang in langs" :label="'Описание' + lang.lineEnding"  :key="'description'+lang.value">
                        <el-input type="textarea" v-model="editRowData.description[lang.value]" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-divider></el-divider>

                    <el-form-item label="Подборка опубликована">
                        <el-radio-group v-model="editRowData.is_published">
                            <el-radio-button label="1">Да</el-radio-button>
                            <el-radio-button label="0">Нет</el-radio-button>
                        </el-radio-group>
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
import { compilations, images, appLangs } from "../../api_connectors";
export default {
    name: "CompilationsIndex",
    data() {
        return {
            dataRows: [],
            editRowData: null,
            editDialogVisible: false,
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
            promises.push(compilations.list());

            Promise.all(promises)
                .then((response) => {
                    this.dataRows = response[0].data;
                    console.log(this.dataRows);
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
        newRow() {
            this.editRowData = {
                priority: 0,
                title: {
                    ru: '',
                    cn: '',
                    ar: ''
                },
                description: {
                    ru: '',
                    cn: '',
                    ar: ''
                },
                image: '',
                is_published: 0,
            };
            this.editDialogVisible = true;
        },
        editRow(data, index) {
            //console.log('inFunction',data);
            this.editRowData = data;
            this.editDialogVisible = true;
        },
        cancelEditRow() {
            this.editRowData = null;
            this.editDialogVisible = false;
        },
        deleteRow(id, position, varName = 'dataRows') {
            this.$loading();
            compilations
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
                    ? compilations.update(
                        this.editRowData.id,
                        this.editRowData
                    )
                    : compilations.create(this.editRowData);
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
            this.$loading();

            console.log(params);
            var formData = new FormData();
            formData.append("file", params.file);

            images
                .create(formData)
                .then((response) => {
                    console.log('image.create', response.data);
                    this.editRowData.image = response.data.url;

                    this.$loading().close();
                })
                .catch((error) => {
                    this.$message({
                        message: "Не удалось загрузить картинку: " + error,
                        type: "error",
                        duration: 5000,
                        showClose: true,
                    });

                    this.$loading().close();
                });



        },
        deleteImage() {
            this.$loading();

            console.log('delete');

            images
                .delete(this.editRowData.image)
                .then((response) => {
                    console.log('image.delete', response.data);
                    this.editRowData.image = null;

                    this.$loading().close();
                })
                .catch((error) => {
                    this.$message({
                        message: "Не удалось удалить картинку: " + error,
                        type: "error",
                        duration: 5000,
                        showClose: true,
                    });

                    this.$loading().close();
                });
        },
        formatIsPublished(row, column, cellValue, index) {
            var arr = new Map([
                [0, 'Нет'],
                [1, 'Да'],
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
.avatar-uploader .el-upload {
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.avatar-uploader .el-upload:hover {
    border-color: #409EFF;
}

.avatar-uploader-icon {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    font-size: 28px;
    color: #8c939d;
    width: 178px;
    height: 178px;
    line-height: 178px;
    text-align: center;
}

.avatar-delete-icon {
    position: absolute;
    top: 0px;
    left: 0px;
    z-index: 1000;
    font-size: 28px;
    color: #8c939d;
    width: 178px;
    height: 178px;
    line-height: 178px;
    text-align: center;
}

.avatar {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    width: 178px;
    height: 178px;
    display: block;
}
</style>
