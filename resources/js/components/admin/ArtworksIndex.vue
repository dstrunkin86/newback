<template>
    <el-container>
        <el-main>
            <div class="panel-header">
                <span>Работы</span>
                <div style="float: right;">
                    <el-button @click="newRow()" type="success" icon="el-icon-plus">Создать</el-button>
                </div>

            </div>


            <div v-if="(typeof newDataRows.data !== 'undefined') && (newDataRows.data.length > 0)"
                class="panel-subheader">Требуют обработки</div>
            <el-table v-if="(typeof newDataRows.data !== 'undefined') && (newDataRows.data.length > 0)"
                :data="newDataRows.data" stripe>
                <el-table-column prop="image" label="Изображение">
                    <template slot-scope="scope">
                        <el-image style="width: 150px; height: 150px"
                            :src="(scope.row.images.length > 0) ? scope.row.images[0].url : ''" fit="cover"></el-image>
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
            <el-pagination v-if="(typeof newDataRows.data !== 'undefined') && (newDataRows.data.length > 0)"
                layout="pager" :current-page="newDataRows.current_page" :total="newDataRows.total"
                :page-size="newDataRows.per_page" @current-change="newDataRowsPageChanged"> </el-pagination>


            <div v-if="(typeof dataRows.data !== 'undefined') && (dataRows.data.length > 0)" class="panel-subheader">
                Картины в галерее
            </div>

            <el-card class="box-card">
                <el-form ref="form" :model="filterFields" label-width="100px">
                    <el-row>
                        <el-col :span="6">
                            <el-form-item label="Статус" style="margin-bottom: 0px;">
                                <el-select v-model="filterFields.status_in" multiple collapse-tags>
                                    <el-option label="" value=""></el-option>
                                    <el-option label="Допущена" value="accepted"></el-option>
                                    <el-option label="Отклонен" value="rejected"></el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                        <el-col :span="6">
                            <el-form-item label="Тэги" style="margin-bottom: 0px;">
                                <el-select v-model="filterFields.having_tags" multiple collapse-tags>
                                    <el-option label="" value=""></el-option>
                                    <el-option-group v-for="group in tags" :key="group.label" :label="group.label">
                                        <el-option v-for="item in group.options" :key="item.value" :label="item.label"
                                            :value="item.value">
                                        </el-option>
                                    </el-option-group>
                                </el-select>
                            </el-form-item>
                        </el-col>
                        <el-col :span="6">
                            <el-form-item label="Название" style="margin-bottom: 0px;">
                                <el-input v-model="filterFields.title"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="6">
                            <el-form-item label="Автор" style="margin-bottom: 0px;">
                                <el-select v-model="filterFields.artist_id">
                                    <el-option label="" value=""></el-option>
                                    <el-option v-for="item in artists" :key="item.id" :label="item.fio.ru"
                                        :value="item.id">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row>
                        <el-col :span="6" style="padding-left:20px;">
                            <el-form-item label="В продаже" style="margin-bottom: 0px;">
                                <el-select v-model="filterFields.in_sale" multiple>
                                    <el-option label="" value=""></el-option>
                                    <el-option label="Да" value=1></el-option>
                                    <el-option label="Нет" value=0></el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                        <el-col :span="6">
                            <el-form-item label="Цена от" style="margin-bottom: 0px;">
                                <el-input v-model="filterFields.price_from"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="6">
                            <el-form-item label="Цена до" style="margin-bottom: 0px;">
                                <el-input v-model="filterFields.price_to"></el-input>
                            </el-form-item>
                        </el-col>


                        <el-col :span="6" style="padding-left:20px;">
                            <el-form-item label=" " style="margin-bottom: 0px;">
                                <el-button type="primary" @click="getData()">Применить</el-button>

                            </el-form-item>

                        </el-col>
                    </el-row>

                </el-form>
            </el-card>

            <el-table v-if="(typeof dataRows.data !== 'undefined') && (dataRows.data.length > 0)" :data="dataRows.data"
                stripe>
                <el-table-column prop="image" label="Изображение">
                    <template slot-scope="scope">
                        <el-image style="width: 150px; height: 150px"
                            :src="(scope.row.images.length > 0) ? scope.row.images[0].url : ''" fit="cover"></el-image>
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
            <el-pagination v-if="(typeof dataRows.data !== 'undefined') && (dataRows.data.length > 0)" layout="pager"
                :current-page="dataRows.current_page" :total="dataRows.total" :page-size="dataRows.per_page"
                @current-change="dataRowsPageChanged"> </el-pagination>



            <el-dialog v-if="editRowData != null"
                :title="((editRowData != null) && (editRowData.id > 0)) ? 'Редактирование' : 'Создание'"
                :visible.sync="editDialogVisible">
                <el-form :model="editRowData" ref="editRowData" label-width="200px" size="mini">
                    <el-form-item label="Изображения" v-if="editRowData.id > 0">
                        <el-upload action="" list-type="picture-card" :fileList="editRowData.images"
                            :http-request="uploadImage" :on-remove="deleteImage">
                            <i class="el-icon-plus"></i>
                        </el-upload>
                    </el-form-item>
                    <el-form-item label="Художник">
                        <span v-if="editRowData.id > 0">{{ editRowData.artist.fio.ru }}</span>
                        <el-select v-if="editRowData.id == undefined" v-model="editRowData.artist_id" placeholder="Выберите художника">
                            <el-option v-for="item in artists" :key="item.id" :label="item.fio.ru"
                                :value="item.id">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="Информация при импорте" v-if="editRowData.tech_info">
                        <div v-for="[key, value] in Object.entries(editRowData.tech_info)">{{ key }} - {{ value }}</div>
                    </el-form-item>
                    <el-divider></el-divider>

                    <el-form-item v-for="lang in langs" :label="'Название' + lang.lineEnding"
                        :key="'title' + lang.value" :rules="requiredRule">
                        <el-input v-model="editRowData.title[lang.value]" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-divider></el-divider>

                    <el-form-item v-for="lang in langs" :label="'Описание' + lang.lineEnding"
                        :key="'description' + lang.value" :rules="requiredRule">
                        <el-input type="textarea" v-model="editRowData.description[lang.value]"
                            autocomplete="off"></el-input>
                    </el-form-item>
                    <el-divider></el-divider>

                    <el-form-item label="Год">
                        <el-input v-model="editRowData.year" autocomplete="off"></el-input>
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
                    <el-form-item label="Цена (руб.)" v-if="editRowData.in_sale > 0" :rules="requiredRule">
                        <el-input v-model="editRowData.price" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-divider></el-divider>

                    <el-form-item label="Местоположение">
                        <el-autocomplete class="inline-input" v-model="editRowData.location.value"
                            :fetch-suggestions="getDadataCities" style="width: 100%;" placeholder="Начните ввод"
                            :trigger-on-focus="false" @select="selectCity"></el-autocomplete>
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
import { artists, artworks, tags, compilations, appLangs, dadata } from "../../api_connectors";

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
            artists: [],
            langs: appLangs,
            requiredRule: [
                { required: true, message: 'Заполните это поле', trigger: 'blur' },
            ],
            filterFields: {
                'status_in': ['accepted', 'rejected'],
                'title': '',
                'having_tags': [],
                'artist_id': "",
                "in_sale": "",
                "price_from": "",
                "price_to": "",
            },


        };
    },
    mounted() {
        this.getData();
    },
    methods: {
        getDadataCities(query, cb) {
            if ((query !== '') && (query.length > 3)) {
                dadata.address(query)
                    .then((response) => {
                        console.log('response', response);
                        let cities = response.data.suggestions.map(item => {
                            return {
                                fiasCode: item.data.fias_id,
                                postalCode: item.data.postal_code,
                                city: item.data.city,
                                region: item.data.region,
                                value: item.value,

                            };
                        });
                        cb(cities);
                    })
                    .catch((error) => {
                        this.$message({
                            message: "Не удалось получить адреса: " + error.response.data.message,
                            type: "error",
                            duration: 5000,
                            showClose: true,
                        });
                        this.addressLoading = false;
                    });
            }


        },
        selectCity(params) {
            this.editRowData.location = params;
        },
        getData() {
            this.$loading();

            let activeFilter = {};
            for (let key in this.filterFields) {
                if (this.filterFields[key] != null && this.filterFields[key] != '') {
                    activeFilter[key] = this.filterFields[key]
                }
            }

            let promises = [];
            promises.push(artworks.list({ 'status_in': ['new'] }, this.newDataRowsPage));
            promises.push(artworks.list(activeFilter, this.dataRowsPage));
            promises.push(tags.forSelect());
            promises.push(compilations.list());
            promises.push(artists.nameList());

            Promise.all(promises)
                .then((response) => {
                    this.newDataRows = response[0].data;
                    this.dataRows = response[1].data;
                    console.log('incoming', this.dataRows);
                    this.tags = response[2].data;
                    this.compilations = response[3].data;
                    this.artists = response[4].data;
                    console.log('artists', this.artists);
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
            artworks.
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

            let activeFilter = {};
            for (let key in this.filterFields) {
                if (this.filterFields[key] != null && this.filterFields[key] != '') {
                    activeFilter[key] = this.filterFields[key]
                }
            }

            this.dataRowsPage = page;
            artworks.
                list(activeFilter, this.dataRowsPage)
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
        newRow() {
            this.editRowData = {
                status: "accepted",
                title: {
                    ru: "",
                    en: ""
                },
                description: {
                    ru: "",
                    en: ""
                },
                "year": 2019,
                location: {
                    fiasCode: "",
                    postalCode: "",
                    city: "",
                    region: "",
                    value: ""
                },
                artist_id: null,
                width: null,
                height: null,
                depth: null,
                weight: null,
                in_sale: 0,
                price: null,
                tech_info: {},
                images: [],
                tags: [],
                compilations: []
            }

            this.editDialogVisible = true;
            console.log('id=', this.editRowData.id);
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

            if (this.editRowData.location === null) {
                this.editRowData.location = {
                    fiasCode: '',
                    postalCode: '',
                    city: '',
                    region: '',
                    value: '',
                };
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
                    console.log('form valid');
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
            console.log('current images', this.editRowData.images);
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
