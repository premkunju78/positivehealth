<template>
  <div>
    <section id="dashboard-analytics">
      <h3 class="pt-2 pb-3">Hello {{ $store.state.auth.user.first_name }} !</h3>
      <b-row>
        <b-col lg="12" md="12">
          <b-row>
            <b-col cols="12">
              <statics :data="data"></statics>
            </b-col>
          </b-row>
        </b-col>
      </b-row>
      <b-row>
        <b-col lg="9" md="12">
          <b-row>
            <b-col lg="6">
              <b-card title="Today’s appointment ">
                <TodaySchedule />
              </b-card>
            </b-col>
            <b-col lg="6" md="6">
              <prescriptions :data="data"></prescriptions>
            </b-col>
            <!-- <b-col cols="6">
              <line-chart :data="data"></line-chart>
            </b-col> -->
            <b-col lg="6" md="6">
              <investigations :data="data"></investigations>
            </b-col>
            <b-col lg="6" md="6">
              <b-card title="Feedbacks">
                <feedback />
              </b-card>
            </b-col>
          </b-row>
        </b-col>
        <b-col lg="3" md="6">
          <b-row>
            <b-col cols="12">
              <slider :data="data" />
            </b-col>
            <b-col cols="12">
              <Notifications :data="data" />
            </b-col>
          </b-row>
        </b-col>
      </b-row>
    </section>
    <section id="dashboard-contents"></section>
  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import { BRow, BCol, BCard, BCardText, BLink } from "bootstrap-vue";
import Statics from "./statics.vue";
import Appointment from "./appointment.vue";
import LineChart from "./chart.vue";

import Result from "./result.vue";
import Search from "./search.vue";
import Affirmation from "./affirmation.vue";

import Notifications from "./notifications.vue";
import Investigations from "./investigations.vue";

import Prescriptions from "./prescriptions.vue";

import Slider from "./slider.vue";
import Program from "./program.vue";
import TodaySchedule from "./todayschedule.vue";
import Feedback from "./feedback.vue";

export default {
  components: {
    BRow,
    BCol,
    BCard,
    BCardText,
    BLink,
    Statics,
    Appointment,
    LineChart,
    Result,
    Search,
    Affirmation,
    Notifications,
    Investigations,
    Prescriptions,
    Slider,
    Program,
    TodaySchedule,
    Feedback,
  },
  data() {
    return {
      data: [
        {
          title: "Appointments",
          subtitle: "150",
          color: "secondary",
          icon: "CalendarIcon",
          customClass: "",
        },
        {
          title: "Clients",
          subtitle: "100",
          color: "success",
          icon: "PlusCircleIcon",
          customClass: "",
        },
        {
          title: "Total Prescription",
          subtitle: "50",
          color: "warning",
          icon: "RefreshCwIcon",
          customClass: "",
        },
      ],
      companyTable: [],
      meetup: [],
      affirmation: "",
      notifications: [],
    };
  },
  computed: {
    ...mapGetters(["user"]),
  },
  created() {
    this.fetchNotifications();
    this.fetchAffirmation();
  },
  methods: {
    async fetchNotifications() {
      const { data } = await axios.get(`notification/${this.$store.state.auth.user.id}`);
      console.log(data);
      this.notifications = data.notifications;
    },
    async fetchAffirmation() {
      const { data } = await axios.get(`affirmation/today`);
      this.affirmation = data.affirmation;
    },
  },
};
</script>

<style></style>
