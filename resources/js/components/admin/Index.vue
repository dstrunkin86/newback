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
            <div v-if="$hasRoles(['admin'])">User is admin</div>
            <div v-if="$hasRoles(['admin','moderator'])">User is moderator</div>
            <div v-if="$hasRoles(['moderator','admin'])">User is artist</div>
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
