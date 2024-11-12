import axios from "axios";

const host_path = process.env.MIX_HOST_PATH;

const urls = {
    user: "/api/user",
};

export const user = {
    get: () => {
        return axios.get(host_path + urls.user);
    },
};
