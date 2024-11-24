import axios from "axios";

const host_path = process.env.MIX_HOST_PATH;
const app_languages = process.env.MIX_APP_LANGUAGES;

const urls = {
    users: "/api/admin/users",
    artists: "/api/admin/artists",
    artworks: "/api/admin/artworks",
    compilations: "/api/admin/compilations",
    tags: "/api/admin/tags",
    general: "/api/general",


};
export const app_langs = (app_languages != undefined) ? app_languages.split(',') : ['ru'];

export const users = {
    list: (filter = {}) => {
        const params = new URLSearchParams(filter);
        return axios.get(host_path + urls.users, { params });
    },
    show: (id) => {
        return axios.get(host_path + urls.users + "/" + id);
    },
    create: (data) => {
        return axios.post(host_path + urls.users, data);
    },
    update: (id, data) => {
        return axios.patch(host_path + urls.users + "/" + id, data);
    },
    delete: (id) => {
        return axios.delete(host_path + urls.users + "/" + id);
    },
};

export const artists = {
    list: (filter = {}) => {
        const params = new URLSearchParams(filter);
        return axios.get(host_path + urls.artists, { params });
    },
    show: (id) => {
        return axios.get(host_path + urls.artists + "/" + id);
    },
    create: (data) => {
        return axios.post(host_path + urls.artists, data);
    },
    update: (id, data) => {
        return axios.patch(host_path + urls.artists + "/" + id, data);
    },
    delete: (id) => {
        return axios.delete(host_path + urls.artists + "/" + id);
    },
};

export const artworks = {
    list: (filter = {}) => {
        const params = new URLSearchParams(filter);
        return axios.get(host_path + urls.artworks, { params });
    },
    show: (id) => {
        return axios.get(host_path + urls.artworks + "/" + id);
    },
    create: (data) => {
        return axios.post(host_path + urls.artworks, data);
    },
    update: (id, data) => {
        return axios.patch(host_path + urls.artworks + "/" + id, data);
    },
    delete: (id) => {
        return axios.delete(host_path + urls.artworks + "/" + id);
    },
};

export const tags = {
    treeList: (filter = {}) => {
        const params = new URLSearchParams(filter);
        return axios.get(host_path + urls.tags + "/tree", { params });
    },
    forSelect: (filter = {}) => {
        return axios.get(host_path + urls.tags + "/for-select");
    },
    list: (filter = {}) => {
        const params = new URLSearchParams(filter);
        return axios.get(host_path + urls.tags, { params });
    },
    show: (id) => {
        return axios.get(host_path + urls.tags + "/" + id);
    },
    create: (data) => {
        return axios.post(host_path + urls.tags, data);
    },
    update: (id, data) => {
        return axios.patch(host_path + urls.tags + "/" + id, data);
    },
    delete: (id) => {
        return axios.delete(host_path + urls.tags + "/" + id);
    },
};

export const compilations = {
    list: (filter = {}) => {
        const params = new URLSearchParams(filter);
        return axios.get(host_path + urls.compilations, { params });
    },
    show: (id) => {
        return axios.get(host_path + urls.compilations + "/" + id);
    },
    create: (data) => {
        return axios.post(host_path + urls.compilations, data);
    },
    update: (id, data) => {
        return axios.patch(host_path + urls.compilations + "/" + id, data);
    },
    delete: (id) => {
        return axios.delete(host_path + urls.compilations + "/" + id);
    },
};

export const images = {
    create: (data) => {
        return axios.post(host_path + urls.general + '/store-image', data);
    },
    delete: (url) => {
        // console.log('data',data);
        let data = {
            url: url
        };
        return axios.post(host_path + urls.general + '/delete-image', data);
    },
};
