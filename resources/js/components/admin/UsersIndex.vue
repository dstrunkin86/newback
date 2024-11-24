<template>
    <el-container>
        <el-main>
            <div class="panel-header">Пользователи</div>
            <el-card class="box-card">
                <div class="filter">
                    <el-form ref="form" :model="filterFields" label-width="90px">
                        <el-row>
                            <el-col :span="8">
                                <el-form-item label="Email">
                                    <el-input v-model="filterFields.email"></el-input>
                                </el-form-item>
                            </el-col>
                            <el-col :span="8">
                                <el-form-item label="Роль">
                                    <el-select v-model="filterFields.role">
                                        <el-option label="Админ" value="admin" selected></el-option>
                                        <el-option label="Модератор" value="moderator"></el-option>
                                        <el-option label="Художник" value="artist"></el-option>
                                        <el-option label="Обычный пользователь" value="regular_user"></el-option>
                                    </el-select>
                                </el-form-item>
                            </el-col>
                            <el-col :span="8">
                                <el-button type="primary" @click="applyFilter()">Применить</el-button>
                                <el-button @click="newRow()" type="success" icon="el-icon-plus">Создать
                                    нового</el-button>
                            </el-col>
                        </el-row>
                    </el-form>
                </div>

            </el-card>
            <el-table :data="dataRows" stripe>
                <el-table-column prop="name" label="Имя">
                </el-table-column>
                <el-table-column prop="email" label="Email">
                </el-table-column>
                <el-table-column prop="role" label="Роль" :formatter="formatRole">
                </el-table-column>
                <el-table-column label="Actions" width="150">
                    <template slot-scope="scope">
                        <el-button-group style="font-size: 20px">
                            <i @click="editRow(scope.$index)" class="el-icon-edit" style="color: blue"></i>
                            <el-popconfirm @confirm="deleteRow(scope.row.id, scope.$index, 'dataRows')"
                                title="Точно хотите удалить?" confirm-button-text="Да" cancel-button-text="Нет">
                                <i slot="reference" style="color: red" class="el-icon-delete"></i>
                            </el-popconfirm>
                        </el-button-group>
                    </template>

                </el-table-column>
            </el-table>
            <el-dialog v-if="rowObject != null" title="Создать/Изменить" :visible.sync="createDialogVisible">
                <el-form :model="rowObject" label-width="160px">
                    <el-form-item label="Имя">
                        <el-input v-model="rowObject.name" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="Email">
                        <el-input v-model="rowObject.email" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="Роли">
                        <el-select v-model="rowObject.role" multiple>
                            <el-option label="Админ" value="admin"></el-option>
                            <el-option label="Модератор" value="moderator"></el-option>
                            <el-option label="Художник" value="artist"></el-option>
                            <el-option label="Обычный пользователь" value="regular_user"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="Пароль">
                        <el-input v-model="rowObject.password" autocomplete="off"></el-input>
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
import { users } from "../../api_connectors";
export default {
    name: "UsersIndex",

    data() {
        return {
            dataRows: [],
            filterFields: { 'role': 'admin' },
            rowObject: null,
            createDialogVisible: false,
        };
    },
    mounted() {
        this.getRows({ 'role': 'admin' }, 'dataRows');
    },
    methods: {
        getRows(filter = {}, varName = 'dataRows') {
            users
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
        deleteRow(id, position, varName = 'dataRows') {
            this.$loading();
            users
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
        newRow() {
            this.rowObject = {
                name: null,
                email: null,
                password: null,
            };
            this.createDialogVisible = true;
        },
        editRow($index) {
            this.rowObject = this.dataRows[$index];
            this.createDialogVisible = true;
        },
        saveRow() {
            this.$loading();
            let result =
                this.rowObject.id > 0
                    ? users.update(
                        this.rowObject.id,
                        this.rowObject
                    )
                    : users.create(this.rowObject);
            result
                .then((response) => {
                    this.$loading().close();
                    this.$message({
                        message: "Успешное сохранение!",
                        type: "success",
                        duration: 3000,
                        showClose: true,
                    });
                    this.applyFilter();
                    this.rowObject = null;
                    this.createDialogVisible = false;
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
        formatRole(row, column, cellValue, index) {
            //console.log(index, cellValue);

            var roles = [];

            var arr = new Map([
                ['admin', 'Админ'],
                ['moderator', 'Модератор'],
                ['artist', 'Художник'],
                ['regular_user', 'Пользователь'],
            ]);

            cellValue.forEach(role => {
                roles.push(arr.get(role))
            });



            return roles.join(', ')
        },
        applyFilter() {
            let activeFilter = {};
            for (let key in this.filterFields) {
                if (this.filterFields[key] != null && this.filterFields[key] != '') {
                    activeFilter[key] = this.filterFields[key]
                }
            }
            this.getRows(activeFilter, 'dataRows');
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
