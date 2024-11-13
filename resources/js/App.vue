<template>
    <el-container>
        <el-aside width="240px">
            <left-menu></left-menu>
        </el-aside>


        <el-main>
            <div v-if="$hasRoles(['admin'])">User is admin</div>
            <div v-if="$hasRoles(['admin', 'moderator'])">User is moderator</div>
            <div v-if="$hasRoles(['moderator', 'admin'])">User is artist</div>
        </el-main>

        <!-- <router-view></router-view> -->
    </el-container>
</template>


<script>
import LeftMenu from "./components/leftMenu/LeftMenu";
export default {
    components: {
        LeftMenu
    },
    mounted() { },
};
</script>

<style>
html,
body,
#app,
.el-container {
    height: 100%;
}
.el-main {
    padding: 0 20px;
}

</style>
