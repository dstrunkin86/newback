<template>
    <el-container>
        <el-main>
            <div class="panel-header">Заказы</div>

            <el-card class="box-card">
                <el-form ref="form" :model="filterFields" label-width="80px">
                    <el-row>
                        <el-col :span="21">
                            <el-form-item label="Статус" style="margin-bottom: 0px;">
                                <el-select v-model="filterFields.status_in" multiple style="width: 100%;">
                                    <el-option label="" value=""></el-option>
                                    <el-option label="Новый" value="new"></el-option>
                                    <el-option label="Оформлена доставка" value="delivery_created"></el-option>
                                    <el-option label="Создан платеж" value="payment_created"></el-option>
                                    <el-option label="Средства заморожены" value="hold"></el-option>
                                    <el-option label="Принят автором" value="accepted_by_artist"></el-option>
                                    <el-option label="Вызван курьер" value="courier"></el-option>
                                    <el-option label="Средства списаны" value="paid"></el-option>
                                    <el-option label="Доставлен" value="delivered"></el-option>
                                    <el-option label="Отменен автором" value="cancelled_by_artist"></el-option>
                                    <el-option label="Отменен пользователем" value="cancelled_by_user"></el-option>
                                    <el-option label="Отменен системой" value="cancelled_by_system"></el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>

                        <el-col :span="3" style="padding-left:20px;">
                            <el-button type="primary" @click="getData()">Применить</el-button>
                        </el-col>
                    </el-row>

                </el-form>
            </el-card>



            <el-table v-if="(typeof dataRows.data !== 'undefined') && (dataRows.data.length > 0)" :data="dataRows.data"
                stripe>
                <el-table-column prop="id" label="ID" width="50">
                </el-table-column>
                <el-table-column prop="status" label="Статус" :formatter="formatStatus">
                </el-table-column>
                <el-table-column prop="created_at" label="Создан" :formatter="formatCreatedAt">
                </el-table-column>
                <el-table-column prop="artwork.artist.fio.ru" label="Автор">
                </el-table-column>
                <el-table-column prop="artwork.title.ru" label="Работа">
                </el-table-column>
                <el-table-column prop="total_price" label="Стоимость">
                    <template slot-scope="scope">
                        {{ scope.row.total_price }} ({{ scope.row.artwork_price }} / {{ scope.row.insurance_price }} / {{ scope.row.delivery_price }})
                    </template>
                </el-table-column>

            </el-table>
            <el-pagination v-if="(typeof dataRows.data !== 'undefined') && (dataRows.data.length > 0)" layout="pager"
                :current-page="dataRows.current_page" :total="dataRows.total" :page-size="dataRows.per_page"
                @current-change="dataRowsPageChanged"> </el-pagination>

        </el-main>
    </el-container>
</template>

<script>
import { orders } from "../../api_connectors";
export default {
    name: "OrdersIndex",
    data() {
        return {
            dataRows: [],
            dataRowsPage: 1,
            filterFields: {
                'status_in': "",
            },
        };
    },
    mounted() {
        this.getData();
    },
    methods: {
        getData() {
            this.$loading();

            let activeFilter = {};
            for (let key in this.filterFields) {
                if (this.filterFields[key] != null && this.filterFields[key] != '') {
                    activeFilter[key] = this.filterFields[key]
                }
            }

            let promises = [];
            promises.push(orders.list(activeFilter, this.dataRowsPage));

            Promise.all(promises)
                .then((response) => {
                    this.dataRows = response[0].data;
                    console.log('get data', this.dataRows);

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
            orders.
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
        formatStatus(row, column, cellValue, index) {

            var arr = new Map([
                ['new', 'Новый'],
                ['delivery_created', 'Оформлена доставка'],
                ['payment_created', 'Создан платеж'],
                ['hold', 'Средства заморожены'],
                ['accepted_by_artist', 'Принят автором'],
                ['courier', 'Вызван курьер'],
                ['paid', 'Средства списаны'],
                ['delivered', 'Доставлен'],
                ['cancelled_by_artist', 'Отменен автором'],
                ['cancelled_by_user', 'Отменен пользователем'],
                ['cancelled_by_system', 'Отменен системой'],
            ]);

            return arr.get(cellValue);

        },
        formatCreatedAt(row, column, cellValue, index) {

            let created_at = new Date(cellValue);

            return created_at.toLocaleDateString() + ' ' + created_at.toLocaleTimeString();

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
