<template>
    <el-container>
        <el-main>
            <div class="panel-header">Тэги</div>
            <div v-for="tagType in tagTypes">
                <div class="panel-subheader">
                    <span>{{ tagType.name }}</span>
                    <i @click="newRow(tagType.value)" class="el-icon-plus" style="color: green"></i>
                </div>
                <el-table :data="dataRows[tagType.value]">
                    <el-table-column prop="title.ru" label="Имя"></el-table-column>
                    <el-table-column prop="countArtworks" label="Кол-во работ"></el-table-column>
                    <el-table-column label="Действия" width="150">
                        <template slot-scope="scope">
                            <el-button-group style="font-size: 20px">
                                <i @click="editRow(scope.row)" class="el-icon-edit" style="color: blue"></i>
                                <el-popconfirm @confirm="deleteRow(scope.row, scope.$index, 'dataRows')"
                                    title="Точно хотите удалить?" confirm-button-text="Да" cancel-button-text="Нет">
                                    <i slot="reference" style="color: red" class="el-icon-delete"></i>
                                </el-popconfirm>
                            </el-button-group>
                        </template>

                    </el-table-column>
                </el-table>
            </div>
            <el-dialog v-if="rowObject != null" title="Создать/Изменить" :visible.sync="createDialogVisible">
                <el-form :model="rowObject" label-width="200px">
                    <el-form-item label="Тип">
                        <el-select v-model="rowObject.type">
                            <el-option v-for="tagType in tagTypes" :label="tagType.name"
                                :value="tagType.value" :key="tagType.value"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item v-for="lang in langs" :label="'Название' + lang.lineEnding"  :key="lang.value">
                        <el-input v-model="rowObject.title[lang.value]" autocomplete="off"></el-input>
                    </el-form-item>
                </el-form>
                <span slot="footer" class="dialog-footer">
                    <el-button @click="createDialogVisible = false">Отмена</el-button>
                    <el-button type="primary" @click="saveRow()">Сохранить</el-button>
                </span>
            </el-dialog>


        </el-main>
    </el-container>
</template>

<script>
import { tags, appLangs } from "../../api_connectors";
export default {
    name: "TagsIndex",

    data() {
        return {
            dataRows: [],
            rowObject: null,
            tagTypes: [
                { value: 'style', name: 'Стиль' },
                { value: 'material', name: 'Материал' },
                { value: 'theme', name: 'Тема' },
                { value: 'genre', name: 'Жанр' },
                { value: 'technique', name: 'Техника' },
                { value: 'color', name: 'Цвет' },
            ],
            createDialogVisible: false,
            langs: appLangs
        };
    },
    mounted() {
        this.getRows({}, 'dataRows');
    },
    methods: {
        getRows(filter = {}, varName = 'dataRows') {
            tags
                .treeList(filter)
                .then((response) => {
                    this[varName] = response.data;
                    console.log(this[varName]);
                }).catch((error) => {
                    this.$message({
                        message: "Не удалось загрузить данные: " + error.response.data.message,
                        type: "error",
                        duration: 5000,
                        showClose: true,
                    });
                });
        },
        deleteRow(tag, position, varName = 'dataRows') {
            if (tag.countArtworks > 0) {
                this.$message({
                    message: "Нельзя удалить тэг. Сначала удалите его у всех связанных работ",
                    type: "error",
                    duration: 5000,
                    showClose: true,
                });
            } else {
                this.$loading();
                tags
                    .delete(tag.id)
                    .then((response) => {
                        this.getRows({}, 'dataRows');
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
            }

        },
        newRow(type) {
            this.rowObject = {
                type: type,
                title: {
                    "ru": "",
                    "cn": "",
                    "ar": ""
                },
            };
            this.createDialogVisible = true;
        },
        editRow(rowItem) {
            console.log(rowItem);
            this.rowObject = rowItem;
            this.createDialogVisible = true;
        },
        saveRow() {
            this.$loading();
            let result =
                this.rowObject.id > 0
                    ? tags.update(
                        this.rowObject.id,
                        this.rowObject
                    )
                    : tags.create(this.rowObject);
            result
                .then((response) => {
                    this.$loading().close();
                    this.$message({
                        message: "Успешное сохранение!",
                        type: "success",
                        duration: 3000,
                        showClose: true,
                    });
                    this.getRows({}, 'dataRows');
                    this.rowObject = null;
                    this.createDialogVisible = false;
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
