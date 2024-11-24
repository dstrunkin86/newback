import Vue from "vue";
import Router from "vue-router";

import ArtistsIndex from "./components/admin/ArtistsIndex.vue";
import ArtworksIndex from "./components/admin/ArtworksIndex.vue";
import UsersIndex from "./components/admin/UsersIndex.vue";
import TagsIndex from "./components/admin/TagsIndex.vue";
import PostsIndex from "./components/admin/PostsIndex.vue";
import CompilationsIndex from "./components/admin/CompilationsIndex.vue";



Vue.use(Router);

export default new Router({
    mode: "history",
    routes: [
        {
            path: "/admin",
            name: "artists",
            component: ArtistsIndex,
        },
        {
            path: "/admin/artworks",
            name: "artworks",
            component: ArtworksIndex,
        },

        {
            path: "/admin/compilations",
            name: "compilations",
            component: CompilationsIndex,
        },
        {
            path: "/admin/posts",
            name: "posts",
            component: PostsIndex,
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
