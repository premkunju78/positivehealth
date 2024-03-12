export default {
  namespaced: true,
  state: {},
  getters: {},
  mutations: {},
  actions: {
    fetchInvestigations(ctx, queryParams) {
      return new Promise((resolve, reject) => {
        axios
          .get('/investigation', { params: queryParams })
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    async fetchInvestigation(ctx, { id }) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/investigation/${id}`)
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
    fetchClients() {
      return new Promise((resolve, reject) => {
        axios
          .get('/apps/invoice/clients')
          .then(response => resolve(response))
          .catch(error => reject(error))
      })
    },
  },
}
