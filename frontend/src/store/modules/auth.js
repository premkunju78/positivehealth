import Vue from 'vue'
import Vuex from 'vuex'
import router from '../../router'
import { setToken, getToken, removeToken, isUserLoggedIn, userData } from '../../auth/utils';

Vue.use(Vuex)


// strict: process.env.DEV,
// namespaced: true,
const state = {
    authenticated: isUserLoggedIn(),
    user: {},
    token: getToken(),
    Permissions: []
};

const getters = {
    authenticated(state) {
        return state.authenticated
    },
    user(state) {
        return state.user
    },
    token(state) {
        return state.token
    },
    currentUserPermissions: state => state.Permissions,

};

const mutations = {
    SET_AUTHENTICATED(state, value) {
        state.authenticated = value
    },
    SET_USER(state, value) {
        state.user = value
    },
    SET_TOKEN(state, value) {
        state.token = value
        window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + value;
    },

    SET_PERMISSION(state, Permissions) {
        state.Permissions = Permissions;
    },
    logout(state) {
        state.user = null;
        state.Permissions = [];
    }

};

const actions = {
    login({ commit }, response) {
        if (response.data.user) {
            setToken(response.data.token)
            //console.log(response.data);
            commit('SET_PERMISSION', response.data.permissions)
            commit('SET_USER', response.data.user)
            commit('SET_TOKEN', response.data.token)
            commit('SET_AUTHENTICATED', true)
            router.push({ name: 'dashboard' })

        } else {
            removeToken()
            commit('SET_USER', {})
            commit('SET_TOKEN', null)
            commit('SET_AUTHENTICATED', false)
        }
    },
    async getInfo({ commit, state }) {

        const data = await userData();
        if (data) {
            commit('SET_PERMISSION', data.permissions)
            commit('SET_USER', data.user)
            commit('SET_AUTHENTICATED', true)
            return data;
        } else {
            return data.permissions = false;
        }
    },
    async setPermissions({ commit }) {
        try {
            const { permissions, user } = await userData();
            commit('SET_PERMISSION', permissions)
            commit('SET_USER', user)
            commit('SET_AUTHENTICATED', true)
        } catch {
            removeToken()
            commit('SET_USER', {})
            commit('SET_TOKEN', null)
            commit('SET_AUTHENTICATED', false)
            router.push({ name: 'login' })
        }
    },

    logout({ commit }) {
        removeToken()
        commit('SET_USER', {})
        commit('SET_TOKEN', null)
        commit('SET_AUTHENTICATED', false)
    }
};

export default {
    state,
    getters,
    mutations,
    actions
};
