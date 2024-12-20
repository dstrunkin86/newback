<template>
    <el-container>
        <el-main>
            <div class="panel-header">Художники</div>
            <div v-if="(typeof newDataRows.data !== 'undefined') && (newDataRows.data.length > 0)"
                class="panel-subheader">Требуют обработки</div>
            <el-table v-if="(typeof newDataRows.data !== 'undefined') && (newDataRows.data.length > 0)"
                :data="newDataRows.data" stripe>
                <el-table-column prop="id" label="ID" width="50">
                </el-table-column>
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
                            <el-popconfirm @confirm="deleteRow(scope.row.id, scope.$index, 'newDataRows')"
                                title="Точно хотите удалить?" confirm-button-text="Да" cancel-button-text="Нет">
                                <i slot="reference" style="color: red" class="el-icon-delete"></i>
                            </el-popconfirm>
                        </el-button-group>
                    </template>
                </el-table-column>
            </el-table>
            <el-pagination v-if="(typeof newDataRows.data !== 'undefined') && (newDataRows.data.length > 0)"
                layout="pager" :current-page="newDataRows.current_page" :total="newDataRows.total"
                :page-size="newDataRows.per_page" @current-change="newDataRowsPageChanged"> </el-pagination>



            <div v-if="(typeof dataRows.data !== 'undefined') && (dataRows.data.length > 0)" class="panel-subheader">
                Художники в
                галерее</div>
            <el-table v-if="(typeof dataRows.data !== 'undefined') && (dataRows.data.length > 0)" :data="dataRows.data"
                stripe>
                <el-table-column prop="id" label="ID" width="50">
                </el-table-column>
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
                            <el-popconfirm @confirm="deleteRow(scope.row.id, scope.$index, 'dataRows')"
                                title="Точно хотите удалить?" confirm-button-text="Да" cancel-button-text="Нет">
                                <i slot="reference" style="color: red" class="el-icon-delete"></i>
                            </el-popconfirm>
                        </el-button-group>
                    </template>
                </el-table-column>
            </el-table>
            <el-pagination v-if="(typeof dataRows.data !== 'undefined') && (dataRows.data.length > 0)" layout="pager"
                :current-page="dataRows.current_page" :total="dataRows.total" :page-size="dataRows.per_page"
                @current-change="dataRowsPageChanged"> </el-pagination>



            <el-dialog v-if="editRowData != null"
                :title="((editRowData != null) && (editRowData.id > 0)) ? 'Редактирование' : 'Создание'"
                :visible.sync="editDialogVisible">
                <el-form :model="editRowData" ref="editRowData" label-width="200px" size="mini">
                    <el-form-item label="Источник">
                        <span>{{ formatSource(null, null, editRowData.source) }}</span>
                    </el-form-item>
                    <el-form-item label="Информация при импорте">
                        <div v-for="[key, value] in Object.entries(editRowData.tech_info)">{{ key }} - {{ value }}</div>

                    </el-form-item>

                    <el-divider></el-divider>

                    <el-form-item label="Фотографии">
                        <el-upload action="" list-type="picture-card" :fileList="editRowData.images"
                            :http-request="uploadImage" :on-remove="deleteImage">
                            <i class="el-icon-plus"></i>
                        </el-upload>
                    </el-form-item>
                    <el-divider></el-divider>

                    <el-form-item label="Работы" v-if="editRowData.artworks.length > 0">
                        <el-carousel type="card" height="200px" indicator-position="none" :autoplay="false">
                            <el-carousel-item v-for="item in editRowData.artworks" :key="item.id">
                                <el-image style="height: 100%; width:100%" :src="item.images[0].url"></el-image>
                            </el-carousel-item>
                        </el-carousel>
                    </el-form-item>
                    <el-divider v-if="editRowData.artworks.length > 0"></el-divider>

                    <el-form-item v-for="lang in langs" :label="'Имя' + lang.lineEnding" :key="'fio' + lang.value"
                        :rules="requiredRule">
                        <el-input v-model="editRowData.fio[lang.value]" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="URL" :rules="(editRowData.status == 'accepted') ? requiredRule : null">
                        <el-input v-model="editRowData.url" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-divider></el-divider>

                    <el-form-item label="Email" :rules="requiredRule">
                        <el-input v-model="editRowData.email" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="Телефон" :rules="requiredRule">
                        <el-input v-model="editRowData.phone" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="vk" :rules="requiredRule">
                        <el-input v-model="editRowData.vk" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="telegram" :rules="requiredRule">
                        <el-input v-model="editRowData.telegram" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-divider></el-divider>

                    <el-form-item label="Город" :rules="requiredRule">
                        <el-input v-model="editRowData.city" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="Страна" :rules="requiredRule">
                        <el-select v-model="editRowData.country" width="100%">
                            <el-option v-for="country in countriesList" :label="country.text" :value="country.value"
                                :key="country.value"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-divider></el-divider>

                    <el-form-item label="Стилистика и тематика">
                        <el-select v-model="editRowData.tags" multiple placeholder="Выберите">
                            <el-option-group v-for="group in tags" :key="group.label" :label="group.label">
                                <el-option v-for="item in group.options" :key="item.value" :label="item.label"
                                    :value="item.value">
                                </el-option>
                            </el-option-group>
                        </el-select>
                    </el-form-item>
                    <el-form-item v-for="lang in langs" :label="'Творческая концепция' + lang.lineEnding"
                        :key="'description' + lang.value">
                        <el-input type="textarea" v-model="editRowData.creative_concept[lang.value]"
                            autocomplete="off"></el-input>
                    </el-form-item>
                    <el-divider></el-divider>

                    <el-form-item label="Образование">
                        <div v-for="(edu, eduIndex) in editRowData.education" :key="'edu' + eduIndex">
                            <el-row>
                                <el-col style="width: 90%;">
                                    <div class="form_subtitle">Место обучения</div>
                                    <el-input v-for="lang in langs" v-model="edu.place[lang.value]"
                                        :key="'edu' + eduIndex + 'place' + lang.value"
                                        :placeholder="lang.lineEnding"></el-input>
                                    <div class="form_subtitle">Специальность</div>
                                    <el-input v-for="lang in langs" v-model="edu.specialty[lang.value]"
                                        :key="'edu' + eduIndex + 'specialty' + lang.value"
                                        :placeholder="lang.lineEnding"></el-input>
                                    <div class="form_subtitle">Даты обучения</div>
                                    <el-date-picker v-model="edu.dates" type="daterange" range-separator=" - "
                                        :key="'edu' + eduIndex + 'dates'" start-placeholder="С" end-placeholder="По"
                                        style="width:100%;">
                                    </el-date-picker>
                                </el-col>
                                <el-col style="width: 10%; float:right">
                                    <i @click="deleteJSONRow('education', eduIndex)" class="el-icon-delete"
                                        style="color: red"></i>
                                </el-col>
                            </el-row>
                            <el-divider></el-divider>
                        </div>
                        <i @click="newJSONRow('education', ['place', 'specialty'], ['dates'])" class="el-icon-plus"
                            style="color: green"></i>
                    </el-form-item>
                    <el-divider></el-divider>

                    <el-form-item label="Квалификация">
                        <div v-for="(qual, qualIndex) in editRowData.qualification" :key="'qual' + qualIndex">
                            <el-row>
                                <el-col style="width: 90%;">
                                    <div class="form_subtitle">Место обучения</div>
                                    <el-input v-for="lang in langs" v-model="qual.place[lang.value]"
                                        :key="'qual' + qualIndex + 'place' + lang.value"
                                        :placeholder="lang.lineEnding"></el-input>
                                    <div class="form_subtitle">Специальность</div>
                                    <el-input v-for="lang in langs" v-model="qual.specialty[lang.value]"
                                        :key="'qual' + qualIndex + 'specialty' + lang.value"
                                        :placeholder="lang.lineEnding"></el-input>
                                    <div class="form_subtitle">Даты обучения</div>
                                    <el-date-picker v-model="qual.dates" type="daterange" range-separator=" - "
                                        :key="'qual' + qualIndex + 'dates'" start-placeholder="С" end-placeholder="По"
                                        style="width:100%;">
                                    </el-date-picker>
                                </el-col>
                                <el-col style="width: 10%; float:right">
                                    <i @click="deleteJSONRow('qualification', qualIndex)" class="el-icon-delete"
                                        style="color: red"></i>
                                </el-col>
                            </el-row>
                            <el-divider></el-divider>
                        </div>
                        <i @click="newJSONRow('qualification', ['place', 'specialty'], ['dates'])" class="el-icon-plus"
                            style="color: green"></i>
                    </el-form-item>
                    <el-divider></el-divider>

                    <el-form-item label="Выставочные проекты">
                        <div v-for="(ex, exIndex) in editRowData.exhibitions" :key="'ex' + exIndex">
                            <el-row>
                                <el-col style="width: 90%;">
                                    <div class="form_subtitle">Место проведения</div>
                                    <el-input v-for="lang in langs" v-model="ex.place[lang.value]"
                                        :key="'ex' + exIndex + 'place' + lang.value"
                                        :placeholder="lang.lineEnding"></el-input>
                                    <div class="form_subtitle">Название проекта</div>
                                    <el-input v-for="lang in langs" v-model="ex.title[lang.value]"
                                        :key="'ex' + exIndex + 'title' + lang.value"
                                        :placeholder="lang.lineEnding"></el-input>
                                    <div class="form_subtitle">Даты проведения</div>
                                    <el-date-picker v-model="ex.dates" type="daterange" range-separator=" - "
                                        :key="'ex' + exIndex + 'dates'" start-placeholder="С" end-placeholder="По"
                                        style="width:100%;">
                                    </el-date-picker>
                                </el-col>
                                <el-col style="width: 10%; float:right">
                                    <i @click="deleteJSONRow('exhibitions', exIndex)" class="el-icon-delete"
                                        style="color: red"></i>
                                </el-col>
                            </el-row>
                            <el-divider></el-divider>
                        </div>
                        <i @click="newJSONRow('exhibitions', ['place', 'title'], ['dates'])" class="el-icon-plus"
                            style="color: green"></i>
                    </el-form-item>
                    <el-divider></el-divider>

                    <el-form-item label="Публикации">
                        <div v-for="(pub, pubIndex) in editRowData.publications" :key="'pub' + pubIndex">
                            <el-row>
                                <el-col style="width: 90%;">
                                    <div class="form_subtitle">Место публикации</div>
                                    <el-input v-for="lang in langs" v-model="pub.place[lang.value]"
                                        :key="'pub' + pubIndex + 'place' + lang.value"
                                        :placeholder="lang.lineEnding"></el-input>
                                    <div class="form_subtitle">Название</div>
                                    <el-input v-for="lang in langs" v-model="pub.title[lang.value]"
                                        :key="'pub' + pubIndex + 'title' + lang.value"
                                        :placeholder="lang.lineEnding"></el-input>
                                    <div class="form_subtitle">Ссылка</div>
                                    <el-input v-model="pub.link" placeholder="https://"></el-input>
                                </el-col>
                                <el-col style="width: 10%; float:right">
                                    <i @click="deleteJSONRow('publications', pubIndex)" class="el-icon-delete"
                                        style="color: red"></i>
                                </el-col>
                            </el-row>
                            <el-divider></el-divider>
                        </div>
                        <i @click="newJSONRow('publications', ['place', 'title'], ['link'])" class="el-icon-plus"
                            style="color: green"></i>
                    </el-form-item>
                    <el-divider></el-divider>

                    <el-form-item label="Пользователь">
                        <span>{{ (editRowData.user) ? editRowData.user.email : 'не задан' }}</span>
                    </el-form-item>
                    <el-divider></el-divider>

                    <el-form-item label="Статус">
                        <el-select v-model="editRowData.status" width="100%">
                            <el-option label="Новый" value="new"></el-option>
                            <el-option label="Согласован" value="accepted"></el-option>
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
import { artists, appLangs, tags } from "../../api_connectors";
import { countriesList } from "../../countries_list";
export default {
    name: "ArtistsIndex",

    data() {
        return {
            dataRows: [],
            dataRowsPage: 1,
            newDataRows: [],
            newDataRowsPage: 1,
            editRowData: null,
            editDialogVisible: false,
            langs: appLangs,
            countriesList: countriesList,
            tags: [],
            requiredRule: [
                { required: true, message: 'Заполните это поле', trigger: 'blur' },
            ],
        };
    },
    mounted() {
        this.getData();
        console.log('appLangs', appLangs);
    },
    methods: {
        getData() {
            this.$loading();

            let promises = [];
            promises.push(artists.list({ 'status_in': ['new'] }, this.newDataRowsPage));
            promises.push(artists.list({ 'status_in': ['accepted', 'rejected'] }, this.dataRowsPage));
            promises.push(tags.forSelect({ 'type_in': ['theme', 'style'] }));

            Promise.all(promises)
                .then((response) => {
                    this.newDataRows = response[0].data;
                    this.dataRows = response[1].data;
                    this.tags = response[2].data;
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
        newDataRowsPageChanged(page) {
            this.$loading();
            this.newDataRowsPage = page;
            artists.
                list({ 'status_in': ['new'] }, this.newDataRowsPage)
                .then((response) => {
                    this.newDataRows = response.data;
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
        dataRowsPageChanged(page) {
            this.$loading();
            this.dataRowsPage = page;
            artists.
                list({ 'status_in': ['accepted', 'rejected'] }, this.dataRowsPage)
                .then((response) => {
                    this.dataRows = response.data;
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
        editRow(data, index) {
            this.editRowData = data;

            let tags = [];
            if (this.editRowData.tags) {
                this.editRowData.tags.forEach((element) => tags.push(element.id));
                this.editRowData.tags = tags;
            }

            let JSONFileds = ['education', 'qualification', 'exhibitions', 'publications'];
            JSONFileds.forEach(field => {
                if (this.editRowData[field] == null) {
                    this.editRowData[field] = [];
                }
            })


            console.log(this.editRowData);
            this.editDialogVisible = true;
        },
        newJSONRow(object, langColumns, columns) {
            let newObject = {};
            langColumns.forEach(col => {
                newObject[col] = {};
                this.langs.forEach(lang => {
                    newObject[col][lang.value] = "";
                })
            });
            columns.forEach(col => {
                newObject[col] = "";

            });
            //this.editRowData[object][lang].push(newObject);
            this.$set(this.editRowData[object], this.editRowData[object].length, newObject);
            console.log(this.editRowData);
        },
        deleteJSONRow(object, index) {
            this.editRowData[object].splice(index, 1);
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
                    this[varName].data.splice(position, 1);
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
            this.$refs['editRowData'].validate((valid) => {
                if (valid) {
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
                            console.log(error.response.data);
                            console.log(error.response.status);

                            this.$message({
                                message: "Не удалось сохранить данные: " + error.response.data.message,
                                type: "error",
                                duration: 5000,
                                showClose: true,
                            });
                        });
                } else {
                    console.log('error submit!!');
                    return false;
                }
            });
        },
        uploadImage(params) {
            console.log(params);
            var formData = new FormData();
            formData.append("file", params.file);

            artists
                .addImage(this.editRowData.id, formData)
                .then((response) => {

                    this.editRowData.images.push({
                        url: response.data.url,
                        status: "success"
                    });
                    console.log('current images', this.editRowData.images);
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
            console.log('current images', this.editRowData.images);
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
                ['arthall_sandbox', 'Песочница ArtHall'],
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

.form_subtitle {
    font-size: 12px;
}
</style>
