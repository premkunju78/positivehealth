import Vue from 'vue'

// axios
import axios from 'axios'
import { getToken } from '@/auth/utils';
import store from '@/store';

const axiosIns = axios.create({
    // You can add your headers here
    // ================================
    // baseURL: 'http://127.0.0.1:8000/api',
    // baseURL: 'http://healthinfinit.moziztech.com/api',
    baseURL: 'https://staging.positivehealth.app/api',
    // baseURL: 'https://positivehealth.app/api',
    timeout: 10000, // Request timeout
    // headers: {'X-Custom-Header': 'foobar'}
})

// Request intercepter
axiosIns.interceptors.request.use(
    config => {
        const token = getToken();
        if (token) {
            config.headers['Authorization'] = 'Bearer ' + getToken(); // Set JWT token
        }
        store.commit('app/TOGGLE_OVERLAY', Boolean(true));
        return config;
    },
    error => {
        // Do something with request error
        console.log(error); // for debug
        store.commit('app/TOGGLE_OVERLAY', Boolean(false));
        Promise.reject(error);
    }
);
// Add a response interceptor
axiosIns.interceptors.response.use(function (response) {
    store.commit('app/TOGGLE_OVERLAY', Boolean(false));
    return response;
}, function (error) {
    // Any status codes that falls outside the range of 2xx cause this function to trigger
    // Do something with response error
    store.commit('app/TOGGLE_OVERLAY', Boolean(false));//
    return Promise.reject(error);
});
Vue.prototype.$http = axiosIns

export default axiosIns