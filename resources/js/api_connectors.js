import axios from "axios";

//Language settings

const envLanguages = (process.env.MIX_APP_LANGUAGES != undefined) ? process.env.MIX_APP_LANGUAGES.split(','): ['ru'];
const langSettings = {
    ru: {
        value: 'ru',
        lineEnding: ' на русском'
    },
    cn: {
        value: 'cn',
        lineEnding: ' на китайском'
    },
    ar: {
        value: 'ar',
        lineEnding: ' на арабском'
    },
    en: {
        value: 'en',
        lineEnding: ' на английском'
    },
};
const appLanguages = [];
envLanguages.forEach(element => {
    appLanguages.push(langSettings[element]);
});

export const appLangs = appLanguages;


// API connectors
const host_path = process.env.MIX_HOST_PATH;
const urls = {
    users: "/api/admin/users",
    artists: "/api/admin/artists",
    artworks: "/api/admin/artworks",
    compilations: "/api/admin/compilations",
    tags: "/api/admin/tags",
    orders: "/api/admin/orders",

    posts: "/api/admin/posts",

    general: "/api/general",

};

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

export const dadata = {


    // options: {
    //     token: 'f5dae7899554e6a48120f640d89c035366b57ffc',
    //     type:'ADDRESS',
    //     options: {
    //         "count": 5,
    //         "from_bound": { "value": "city" },
    //         "to_bound": { "value": "city" },
    //         "deferRequestBy": 100
    //     }
    // },

    address: (query, onlyCity = false) => {

        const config = {
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "Authorization": "Token f5dae7899554e6a48120f640d89c035366b57ffc"
            },
        };

        let params = {};
        if (onlyCity) {
            params = {
                "query": query,
                "count": 5,
                "from_bound": { "value": "city" },
                "to_bound": { "value": "city" },
            }
        } else {
            params = {
                "query": query,
                "count": 5,
            }
        }

        console.log(params);


        return axios.post('https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address', params, config);
    },
};

export const artists = {
    nameList: () => {
        return axios.get(host_path + urls.artists + '/list');
    },
    list: (filter = {}, page = 1) => {
        console.log(filter);
        const params = new URLSearchParams(filter);
        return axios.get(host_path + urls.artists + '/?page=' + page, { params });
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
    addImage: (id, data) => {
        return axios.post(host_path + urls.artists + "/" + id + "/add-image", data);
    },
    deleteImage: (artistId, imageId) => {
        return axios.delete(host_path + urls.artists + "/" + artistId + "/delete-image/" + imageId);
    }
};

export const artworks = {
    list: (filter = {}, page = 1) => {
        const params = new URLSearchParams(filter);
        return axios.get(host_path + urls.artworks + '/?page=' + page, { params });
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
    addImage: (id, data) => {
        return axios.post(host_path + urls.artworks + "/" + id + "/add-image", data);
    },
    deleteImage: (artworkId, imageId) => {
        return axios.delete(host_path + urls.artworks + "/" + artworkId + "/delete-image/" + imageId);
    }
};

export const orders = {
    list: (filter = {}, page = 1) => {
        const params = new URLSearchParams(filter);
        return axios.get(host_path + urls.orders + '/?page=' + page, { params });
    },
};

export const posts = {
    list: (filter = {}) => {
        const params = new URLSearchParams(filter);
        return axios.get(host_path + urls.posts, { params });
    },
    show: (id) => {
        return axios.get(host_path + urls.posts + "/" + id);
    },
    create: (data) => {
        return axios.post(host_path + urls.posts, data);
    },
    update: (id, data) => {
        return axios.patch(host_path + urls.posts + "/" + id, data);
    },
    delete: (id) => {
        return axios.delete(host_path + urls.posts + "/" + id);
    },
    addImage: (id, data) => {
        return axios.post(host_path + urls.posts + "/" + id + "/add-image", data);
    },
    deleteImage: (postId, imageId) => {
        return axios.delete(host_path + urls.posts + "/" + postId + "/delete-image/" + imageId);
    }
};

export const tags = {
    treeList: (filter = {}) => {
        const params = new URLSearchParams(filter);
        return axios.get(host_path + urls.tags + "/tree", { params });
    },
    forSelect: (filter = {}) => {
        const params = new URLSearchParams(filter);
        return axios.get(host_path + urls.tags + "/for-select", { params });
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
        const params = new URLSearchParams(data);
        return axios.delete(host_path + urls.general + '/delete-image', params);
    },
};
