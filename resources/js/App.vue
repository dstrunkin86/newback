<template>
  <el-container style="border: 1px solid #eee">
    <el-aside width="240px" style="background-color: rgb(238, 241, 246)">
        <left-menu></left-menu>
    </el-aside>

      <router-view></router-view>
  </el-container>
</template>


<script>
import LeftMenu from "./components/leftMenu/LeftMenu";
export default {
  components: {
    LeftMenu
  },
  mounted() {},
};
</script>

<style>
html,
body,
#app,
.el-container {
  height: 100%;
}
.el-header {
  background-color: #b3c0d1;
  color: #333;
  line-height: 60px;
}
.el-aside {
  color: #333;
}
</style>
