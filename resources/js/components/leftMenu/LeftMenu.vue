<template>
    <div class="cont">
        <el-menu>
        <router-link tag="el-menu-item" to="/admin/">
            <i class="el-icon-s-finance"></i>
            <span>Художники</span>
        </router-link>
        <router-link tag="el-menu-item" to="/admin/">
            <i class="el-icon-s-finance"></i>
            <span>Художники</span>
        </router-link>
        <div class="el-menu-item"></div>
        <a href="/logout">
            <el-menu-item>
                <i class="el-icon-s-finance"></i>
                <span>Выход</span>
            </el-menu-item>
        </a>

        </el-menu>
    </div>

</template>
<style scoped>
    .el-menu, .cont {
        height: 100%;
        border-right: 1px solid #000;

    }
    .el-menu-item {
        height: 40px;
        line-height: 40px;
    }
    .el-menu-item span {
        font-size: 16px;;
    }

</style>
