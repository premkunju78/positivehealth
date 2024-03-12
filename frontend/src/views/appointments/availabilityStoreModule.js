export default {
    namespaced: true,
    state: {
        calendarOptions: [{
                color: 'success',
                label: 'Availability',
            },

        ],
        selectedCalendars: ['Availability'],
    },
    getters: {},
    mutations: {
        SET_SELECTED_EVENTS(state, val) {
            state.selectedCalendars = val
        },
    },
    actions: {
        fetchEvents(ctx, { calendars, id }) {
            return new Promise((resolve, reject) => {
                axios
                    .get('/availability', {
                        params: {
                            calendars: calendars.join(','),
                            id: id
                        },
                    })
                    .then(response => resolve(response))
                    .catch(error => reject(error))
            })
        },
        addEvent(ctx, { event }) {
            return new Promise((resolve, reject) => {
                axios
                    .post('/availability', event)
                    .then(response => resolve(response))
                    .catch(error => reject(error))
            })
        },
        updateEvent(ctx, { event }) {
            return new Promise((resolve, reject) => {
                axios
                    .put(`/availability/${event.id}`, event)
                    .then(response => resolve(response))
                    .catch(error => reject(error))
            })
        },
        removeEvent(ctx, { id }) {
            return new Promise((resolve, reject) => {
                axios
                    .delete(`/availability/${id}`)
                    .then(response => resolve(response))
                    .catch(error => reject(error))
            })
        },
    },
}