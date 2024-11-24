<template>
    <el-container>
        <el-aside width="240px">
            <left-menu></left-menu>
        </el-aside>
        <router-view></router-view>
    </el-container>
</template>


<script>
import LeftMenu from "./components/admin/LeftMenu";
export default {
    components: {
        LeftMenu
    },
    mounted() {
    },
};
</script>

<style>
html,
body,
#app,
.el-container {
    height: 100%;
}
.panel-header {
    font-size: 24px;
    margin-bottom:20px;
}
.panel-subheader {
    font-size: 18px;
    margin:20px 0px;
    border-bottom: 1px solid #000;
}
.panel-subheader i {
    float:right;
}

.filter .el-form-item {
    margin-bottom: 0px;
}

</style>
