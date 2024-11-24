<template>
    <div class="cont">
        <el-menu>
        <router-link v-if="$hasRoles(['admin','moderator'])" tag="el-menu-item" to="/admin">
            <i class="el-icon-edit"></i>
            <span>Художники</span>
        </router-link>
        <router-link v-if="$hasRoles(['admin','moderator'])" tag="el-menu-item" to="/admin/artworks">
            <i class="el-icon-picture-outline"></i>
            <span>Работы</span>
        </router-link>
        <router-link v-if="$hasRoles(['admin','moderator'])" tag="el-menu-item" to="/admin/compilations">
            <i class="el-icon-finished"></i>
            <span>Подборки</span>
        </router-link>
        <router-link v-if="$hasRoles(['admin','moderator'])" tag="el-menu-item" to="/admin/posts">
            <i class="el-icon-tickets"></i>
            <span>Статьи</span>
        </router-link>
        <router-link v-if="$hasRoles(['admin','moderator'])" tag="el-menu-item" to="/admin/tags">
            <i class="el-icon-share"></i>
            <span>Тэги</span>
        </router-link>
        <router-link v-if="$hasRoles(['admin'])" tag="el-menu-item" to="/admin/users">
            <i class="el-icon-user"></i>
            <span>Пользователи</span>
        </router-link>

        <div class="el-menu-item"></div>
        <a href="/logout">
            <el-menu-item>
                <i class="el-icon-top-left"></i>
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
