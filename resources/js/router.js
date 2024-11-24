import Vue from "vue";
import Router from "vue-router";

import AdminIndex from "./components/admin/AdminIndex";
import ArtistsIndex from "./components/admin/ArtistsIndex.vue";
import ArtworksIndex from "./components/admin/ArtworksIndex.vue";
import UsersIndex from "./components/admin/UsersIndex.vue";
import TagsIndex from "./components/admin/TagsIndex.vue";



Vue.use(Router);

export default new Router({
    mode: "history",
    routes: [
        {
            path: "/admin",
            name: "dashboard",
            component: AdminIndex,
        },
        {
            path: "/admin/artists",
            name: "artists",
            component: ArtistsIndex,
        },
        {
            path: "/admin/artworks",
            name: "artworks",
            component: ArtworksIndex,
        },

        {
            path: "/admin/tags",
            name: "tags",
            component: TagsIndex,
        },
        {
            path: "/admin/users",
            name: "users",
            component: UsersIndex,
        },


    ],
});
