import Vue from "vue";
import Router from "vue-router";

import AdminIndex from "./components/admin/Index";




Vue.use(Router);

export default new Router({
    mode: "history",
    routes: [
        {
            path: "/admin",
            name: "transactions",
            component: AdminIndex,
        },


    ],
});
