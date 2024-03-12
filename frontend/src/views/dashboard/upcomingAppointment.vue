<template>
   <b-card style="height:185px">
        <div>
            <b-card-title class="mb-25 ">
                <h5>Upcoming Appointment</h5>
            </b-card-title>
        </div>
        <div v-if="appointment">
            <h4 class="text-info pt-1">{{appointment.consultant}}</h4>
            <div class="d-flex justify-content-between pt-1">
                <span>{{moment(appointment.timeslot_from).format('ll')}}</span>
                <span>{{moment(appointment.timeslot_from).format('hh:mm a')}}</span>
                    <span></span>
            </div>
                <div class="d-flex justify-content-between pt-1">
                <a class="text-primary" style="text-decoration:underline">Edit Appointment</a>
                <a class="text-warning" style="text-decoration:underline">cancel Appointment</a>
            </div>
        </div>

      
    </b-card>
</template>
<script>

import { BCard, BLink, BCardTitle } from 'bootstrap-vue';

import { mapGetters } from "vuex";

import moment from 'moment';

export default {
    components: {
        BCard,
        BLink,
        BCardTitle
    },
    data() {
        return {
            appointment : null,
            moment
        }
    },
    computed: {
        ...mapGetters(["user"])
    },
    async created(){
        const {data} = await axios.get(`/client/upcomingAppointment`);
        this.appointment = data.appointment;
    },
}
</script>