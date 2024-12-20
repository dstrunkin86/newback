<template>
    <el-container>
        <el-main>
            <div class="panel-header">
                <span>Статьи</span>
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
                <el-table-column prop="language" label="Язык" :formatter="formatLanguage">
                </el-table-column>
                <el-table-column prop="title" label="Заголовок">
                </el-table-column>
                <el-table-column prop="is_published" label="Опубликована" :formatter="formatIsPublished">
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
                    <el-form-item label="Язык">
                        <el-radio-group v-model="editRowData.language">
                            <el-radio-button label="ru">Русский</el-radio-button>
                            <el-radio-button label="en">Английский</el-radio-button>
                            <el-radio-button label="cn">Китайский</el-radio-button>
                            <el-radio-button label="ar">Арабский</el-radio-button>
                        </el-radio-group>
                    </el-form-item>
                    <el-divider></el-divider>

                    <el-form-item label="Изображение">
                        <el-upload class="avatar-uploader" action="" :show-file-list="false"
                            :http-request="uploadImage">
                            <img v-if="editRowData.image" :src="editRowData.image" class="avatar">
                            <i v-if="editRowData.image" @click="deleteImage"
                                class="el-icon-delete avatar-delete-icon"></i>
                            <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                        </el-upload>
                    </el-form-item>
                    <el-divider></el-divider>

                    <el-form-item label="Заголовок">
                        <el-input v-model="editRowData.title" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="Ключевые слова">
                        <el-input v-model="editRowData.keywords" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="Описание">
                        <el-input v-model="editRowData.description" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-divider></el-divider>

                    <el-form-item label="Текст" style="height:290px">
                        <VueEditor id="editor" v-model="editRowData.text" useCustomImageHandler
                            @image-added="handleImageAdded" :editorToolbar="customToolbar" style="height: 200px" />
                    </el-form-item>
                    <el-form-item label="Ссылка">
                        <el-input v-model="editRowData.link" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-divider></el-divider>

                    <el-form-item label="Статья опубликована">
                        <el-radio-group v-model="editRowData.is_published">
                            <el-radio-button label="1">Да</el-radio-button>
                            <el-radio-button label="0">Нет</el-radio-button>
                        </el-radio-group>
                    </el-form-item>
                    <el-form-item label="Дата публикации">
                        <el-date-picker v-model="editRowData.publication_date" type="date" placeholder="Выберите дату" value-format="yyyy-MM-dd">
                        </el-date-picker>
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
import { posts, images } from "../../api_connectors";
import { VueEditor } from "vue2-editor";
export default {
    name: "PostsIndex",
    components: { VueEditor },
    data() {
        return {
            dataRows: [],
            editRowData: null,
            editDialogVisible: false,
            customToolbar: [
                [{ header: [false, 1, 2, 3] }],
                ["bold", "italic", "underline"],
                [{ list: "ordered" }, { list: "bullet" }],
                [
                    { align: "" },
                    { align: "center" },
                    { align: "right" },
                    { align: "justify" },
                ],
                ["image", "link"],
                ["clean"],
            ],
        };
    },
    mounted() {
        this.getData();

    },
    methods: {
        getData() {
            this.$loading();

            let promises = [];
            promises.push(posts.list());

            Promise.all(promises)
                .then((response) => {
                    this.dataRows = response[0].data;
                    this.$loading().close();
                }).catch((error) => {
                    this.$loading().close();

                    this.$message({
                        message: "Не удалось загрузить данные: " + error.response.data.message,
                        type: "error",
                        duration: 5000,
                        showClose: true,
                    });

                });
        },
        newRow() {
            this.editRowData = {
                language: 'ru',
                title: '',
                text: '',
                description: '',
                keywords: '',
                publication_date: null,
                is_published: 0,
                link: '',
                image: ''
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
            posts
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
                        message: "Не удалось удалить: " + error.response.data.message,
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
                    ? posts.update(
                        this.editRowData.id,
                        this.editRowData
                    )
                    : posts.create(this.editRowData);
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
                        message: "Не удалось сохранить данные: " + error.response.data.message,
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
                        message: "Не удалось загрузить картинку: " + error.response.data.message,
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
                        message: "Не удалось удалить картинку: " + error.response.data.message,
                        type: "error",
                        duration: 5000,
                        showClose: true,
                    });

                    this.$loading().close();
                });
        },
        formatLanguage(row, column, cellValue, index) {
            var arr = new Map([
                ['ru', 'Русский'],
                ['en', 'Английский'],
                ['cn', 'Китайский'],
                ['ar', 'Арабский'],
            ]);

            return arr.get(cellValue);
        },
        formatIsPublished(row, column, cellValue, index) {
            var arr = new Map([
                [0, 'Нет'],
                [1, 'Да'],
            ]);

            return arr.get(cellValue);
        },

        handleImageAdded(file, Editor, cursorLocation, resetUploader) {
            var formData = new FormData();
            formData.append("file", file);

            images
                .create(formData)
                .then((response) => {
                    console.log('image.create', response.data);
                    const url = response.data.url;
                    Editor.insertEmbed(cursorLocation, "image", url);
                    resetUploader();
                })
                .catch((error) => {
                    //console.log(error);
                });
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
