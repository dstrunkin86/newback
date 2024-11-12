<template>
    <el-container>
        <el-header>
            <el-row>
                <el-col :span="6">
                    <span style="font-size: 18px">Транзакции</span>
                </el-col>
                <el-col :span="6">
                    <el-button @click="getUser" type="warning">Импорт</el-button>
                </el-col>

            </el-row>
        </el-header>

        <el-main>
123


        </el-main>
    </el-container>
</template>

<script>
import { user } from "../../api_connectors";
export default {
    name: "AdminIndex",

    data() {
        return {};
    },
    mounted() {
    },
    methods: {
        getUser() {
            user
                .get()
                .then((response) => {
                    console.log(response)
                })
        },
    },
};
</script>

<style scoped></style>
