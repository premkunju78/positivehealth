import Vue from 'vue';

export default {
    namespaced: true,
    state: {},
    getters: {},
    mutations: {},
    actions: {
        fetchRoles(ctx, queryParams) {
            console.log(Vue.$http)
            return new Promise((resolve, reject) => {
                axios
                    .get('/roles', { params: queryParams })
                    .then(response => resolve(response))
                    .catch(error => reject(error))
            })
        },
        fetchRole(ctx, { id }) {
            return new Promise((resolve, reject) => {
                axios
                    .get(`/roles/${id}`)
                    .then(response => resolve(response))
                    .catch(error => reject(error))
            })
        },
        addRole(ctx, roleData) {
            return new Promise((resolve, reject) => {
                axios
                    .post('/roles', { user: roleData })
                    .then(response => resolve(response))
                    .catch(error => reject(error))
            })
        },
        updateRole(ctx, roleData) {
            return new Promise((resolve, reject) => {
                axios
                    .put('/roles', { user: roleData })
                    .then(response => resolve(response))
                    .catch(error => reject(error))
            })
        },
        deleteRole(ctx, { id }) {
            return new Promise((resolve, reject) => {
                axios
                    .delete(`/roles/${id}`)
                    .then(response => resolve(response))
                    .catch(error => reject(error))
            })
        },
    },
}