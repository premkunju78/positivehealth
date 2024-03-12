// Full Calendar Plugins
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import listPlugin from '@fullcalendar/list'
import interactionPlugin from '@fullcalendar/interaction'

// Notification
import { useToast } from 'vue-toastification/composition'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

// eslint-disable-next-line object-curly-newline
import { ref, computed, watch, onMounted } from '@vue/composition-api'
import store from '@/store'

import moment from 'moment';


export default function bookingCalendar() {
    // Use toast
    const toast = useToast()
        // ------------------------------------------------
        // refCalendar
        // ------------------------------------------------
    const refCalendar = ref(null)

    // ------------------------------------------------
    // calendarApi
    // ------------------------------------------------
    let calendarApi = null
    let userId = null;
    const color = ref(null);

    onMounted(() => {
        calendarApi = refCalendar.value ? refCalendar.value.getApi() : null
    })

    // ------------------------------------------------
    // calendars
    // ------------------------------------------------
    const calendarsColor = {
        Bookings: 'primary',
        Availability: 'success'
    }

    // ------------------------------------------------
    // event
    // ------------------------------------------------
    const blankEvent = {
        user_id: null,
        title: '',
        startDate: '',
        endDate: '',
        startTime: '',
        endTime: '',
        consultant_name: '',
        consultant_type: '',
        allDay: false,
        selectedDays: [6, 7],
        group: 0,
        attendee: 0,
        frequency: 30,
        extendedProps: {
            calendar: 'Availability'
        },
    }
    const event = ref(JSON.parse(JSON.stringify(blankEvent)))

    const clearEventData = () => {
        event.value = JSON.parse(JSON.stringify(blankEvent))
    }

    const setUser = async(id) => {
        userId = id;

        calendarApi = refCalendar.value ? await refCalendar.value.getApi() : null;

        if (calendarApi) {
            refetchEvents();
        }
    }

    const setColor = (val) => {
        color.value = val;
        console.log(color)
    }


    // *===========================================================================---*
    // *--------- Calendar API Function/Utils --------------------------------------------*
    // Template Future Update: We might move this utils function in its own file
    // *===========================================================================---*

    // ------------------------------------------------
    // (UI) addEventInCalendar
    // ? This is useless because this just add event in calendar and not in our data
    // * If we try to call it on new event then callback & try to toggle from calendar we get two events => One from UI and one from data
    // ------------------------------------------------
    // const addEventInCalendar = eventData => {
    //   toast({
    //     component: ToastificationContent,
    //     position: 'bottom-right',
    //     props: {
    //       title: 'Event Added',
    //       icon: 'CheckIcon',
    //       variant: 'success',
    //     },
    //   })
    //   calendarApi.addEvent(eventData)
    // }

    // ------------------------------------------------
    // (UI) updateEventInCalendar
    // ------------------------------------------------
    const updateEventInCalendar = (updatedEventData, propsToUpdate, extendedPropsToUpdate) => {
        toast({
            component: ToastificationContent,
            props: {
                title: 'Event Updated',
                icon: 'CheckIcon',
                variant: 'success',
            },
        })

        refetchEvents();
    }



    // ------------------------------------------------
    // (UI) removeEventInCalendar
    // ------------------------------------------------
    const removeEventInCalendar = eventId => {
        toast({
            component: ToastificationContent,
            props: {
                title: 'Event Removed',
                icon: 'TrashIcon',
                variant: 'danger',
            },
        })
        calendarApi.getEventById(eventId).remove()
    }

    // ------------------------------------------------
    // grabEventDataFromEventApi
    // ? It will return just event data from fullCalendar's EventApi which is not required for event mutations and other tasks
    // ! You need to update below function as per your extendedProps
    // ------------------------------------------------
    const grabEventDataFromEventApi = async(eventApi) => {

        const {
            id
        } = eventApi
        //  console.log(eventApi)

        const { data } = await axios.get(`/availability/${id}`)
        return data;
    }

    const grabBookingDataFromApi = async(date, user_id) => {
            const { data } = await axios.get(`/booking?user=${user_id}&date=${date}`)
            return data;
        }
        // ------------------------------------------------
        // addEvent
        // ------------------------------------------------
    const addEvent = eventData => {
        console.log("add Events");
        store.dispatch('calendar/addEvent', { event: eventData }).then(({ data }) => {
            toast({
                component: ToastificationContent,
                props: {
                    title: data.message,
                    icon: "BellIcon",
                    variant: data.success ? "success" : "danger"
                }
            });
            // eslint-disable-next-line no-use-before-define
            refetchEvents()
        })
    }

    // ------------------------------------------------
    // updateEvent
    // ------------------------------------------------
    const updateEvent = eventData => {
        console.log("Update Events");
        store.dispatch('calendar/updateEvent', { event: eventData }).then(response => {
            const updatedEvent = response.data.event

            const propsToUpdate = ['id', 'startTime', 'endTime']
            const extendedPropsToUpdate = ['calendar']

            updateEventInCalendar(updatedEvent, propsToUpdate, extendedPropsToUpdate)
        })
    }

    // ------------------------------------------------
    // removeEvent
    // ------------------------------------------------
    const removeEvent = () => {
        const eventId = event.value.id
        store.dispatch('calendar/removeEvent', { id: eventId }).then(() => {
            removeEventInCalendar(eventId)
        })
    }

    // ------------------------------------------------
    // refetchEvents
    // ------------------------------------------------
    const refetchEvents = () => {
        calendarApi.refetchEvents()
    }

    const slots = ref([]);



    var TimeSlotShow = false;


    // ------------------------------------------------
    // selectedCalendars
    // ------------------------------------------------
    const selectedCalendars = computed(() => store.state.calendar.selectedCalendars)

    watch(selectedCalendars, () => {
        refetchEvents()
    })

    // --------------------------------------------------------------------------------------------------
    // AXIOS: fetchEvents
    // * This will be called by fullCalendar to fetch events. Also this can be used to refetch events.
    // --------------------------------------------------------------------------------------------------
    const fetchEvents = (info, successCallback) => {

        // If there's no info => Don't make useless API call
        if (!info) return

        // Fetch Events from API endpoint
        store
            .dispatch('calendar/fetchEvents', {
                calendars: selectedCalendars.value,
                id: userId
            })
            .then(response => {
                successCallback(response.data)
            })
            .catch((error) => {
                console.log(error)
                toast({
                    component: ToastificationContent,
                    props: {
                        title: 'Error fetching calendar events',
                        icon: 'AlertTriangleIcon',
                        variant: 'danger',
                    },
                })
            })
    }

    // ------------------------------------------------------------------------
    // calendarOptions
    // * This isn't considered in UI because this is the core of calendar app
    // For headerToolbar end we can setup dayGridMonth,timeGridWeek,timeGridDay,listMonth
    // ------------------------------------------------------------------------
    const calendarOptions = ref({
        plugins: [dayGridPlugin, interactionPlugin, timeGridPlugin, listPlugin],
        initialView: 'dayGridMonth',

        events: fetchEvents,

        /*
          Automatically scroll the scroll-containers during event drag-and-drop and date selecting
          ? Docs: https://fullcalendar.io/docs/dragScroll
        */
        dragScroll: true,

        /*
          Max number of events within a given day
          ? Docs: https://fullcalendar.io/docs/dayMaxEvents
        */
        dayMaxEvents: 2,

        /*
          Determines if day names and week names are clickable
          ? Docs: https://fullcalendar.io/docs/navLinks
        */
        navLinks: true,

        eventColor: color,

        eventTextColor: '#fff',

        eventBackgrounColor: '#03a9f4' || '#28c76f',

        eventDisplay: {
            backgroundColor: color || '#28c76f',
        },

        eventClick({ event: clickedEvent }) {

            // * Only grab required field otherwise it goes in infinity loop
            // ! Always grab all fields rendered by form (even if it get `undefined`) otherwise due to Vue3/Composition API you might get: "object is not extensible"
            grabEventDataFromEventApi(clickedEvent)
                .then(data => {
                    var appointments = [];
                    let selecteddate = moment(data.date).format("YYYY-MM-DD");
                    let temp_selected_day = moment(selecteddate).format("dddd MMMM D");
                    if (
                        moment(this.selecteddate).isSameOrAfter(moment().format("YYYY-MM-DD"))
                    ) {

                        grabBookingDataFromApi(data.date, data.user_id)
                            .then(data => {
                              
                                let timeSlots = [];

                                //collecting the from & to times for finding max and min timeslot
                                data.slots.forEach((value, key) => {
                                    
                                    timeSlots.push(value.from)
                                    
                                });
                                slots.value = timeSlots;
                                return 
                            })
                        event.value = data;
                        // eslint-disable-next-line no-use-before-define
                        isEventHandlerSidebarActive.value = true
                    } else {
                        // For no slot avail message
                        TimeSlotShow = false;
                        toast({
                            component: ToastificationContent,
                            props: {
                                title: "Sorry ! No time Slots available",
                                icon: "BellIcon",
                                variant: "warning"
                            }
                        });
                    }

                })



        },
        // Get direction from app state (store)
        direction: computed(() => (store.state.appConfig.isRTL ? 'rtl' : 'ltr')),
        rerenderDelay: 350,
    })

    // ------------------------------------------------------------------------

    // *===============================================---*
    // *--------- UI ---------------------------------------*
    // *===============================================---*

    const isEventHandlerSidebarActive = ref(false)

    const isCalendarOverlaySidebarActive = ref(false)

    return {
        refCalendar,
        isCalendarOverlaySidebarActive,
        calendarOptions,
        event,
        clearEventData,
        addEvent,
        updateEvent,
        removeEvent,
        refetchEvents,
        fetchEvents,
        setUser,
        TimeSlotShow,
        slots,
        setColor,

        // ----- UI ----- //
        isEventHandlerSidebarActive,
    }
}