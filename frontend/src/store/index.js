import Vuex from "vuex";
import Vue from "vue";
import createPersistedState from 'vuex-persistedstate'

// Modules
import app from './app'
import appConfig from './app-config'
import verticalMenu from './vertical-menu'
import auth from "./modules/auth"

// Load Vuex
Vue.use(Vuex);

// Create store
export default new Vuex.Store({
    plugins: [
        createPersistedState()
    ],
    modules: {
        app,
        appConfig,
        verticalMenu,
        auth
    }
});