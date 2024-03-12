<template>
  <div>
    <section id="dashboard-analytics">
      <b-row>
        <b-col lg="8" sm="7">
          <b-row>
            <b-col md="12">
              <b-card title="Today's Appointments">
                <TodaySchedule />
              </b-card>
            </b-col>
            <b-col md="12">
              <b-card title="Alliance partner list">
                <Alliancepartnerlist />
              </b-card>
            </b-col>
            <b-col md="12">
              <b-card title="HC List">
                <HCList />
              </b-card>
            </b-col>
            <b-col md="12">
              <b-card title="CIS List">
                <CISList />
              </b-card>
            </b-col>
          </b-row>
        </b-col>
        <b-col lg="4" sm="5">
          <slider />
          <b-card title="Notifications">
            <ul class="m-0 p-0">
              <li
                class="ps-container scrollable-container media-list scroll-area ps--active-y"
                style="height: 350px; overflow: auto"
              >
                <a
                  href="#"
                  target="_self"
                  v-for="notification in notifications"
                  :key="notification.id"
                >
                  <div class="media">
                    <div class="media-body">
                      <small class="notification-text">
                        {{ notification.message }}
                        <b-link
                          v-if="notification.meta.consultant_id"
                          :to="{
                            name: 'book-appointments',
                            params: {
                              id: notification.meta.consultant_id,
                              role: notification.meta.role_id,
                            },
                          }"
                          class="badge badge-primary"
                          >Book Now</b-link
                        >
                        <b-link
                          v-if="notification.meta.client_id"
                          :to="{
                            name: 'client-modules',
                            params: { id: notification.meta.client_id },
                          }"
                          class="badge badge-primary"
                          >View</b-link
                        >
                      </small>
                    </div>
                  </div>
                </a>
              </li>
            </ul>
          </b-card>
        </b-col>
      </b-row>
    </section>
  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import { BRow, BCol, BCard, BCardText, BLink } from "bootstrap-vue";
import StatisticCardWithAreaChart from "@core/components/statistics-cards/StatisticCardWithAreaChart.vue";
import TodaySchedule from "./todayschedule.vue";
import Alliancepartnerlist from "./alliancepartnerlist.vue";
import HCList from "./hclist.vue";
import CISList from "./cislist.vue";
import Slider from "./slider.vue";


export default {
  components: {
    BRow,
    BCol,
    BCard,
    BCardText,
    BLink,
    StatisticCardWithAreaChart,
    TodaySchedule,
    Alliancepartnerlist,
    HCList,
    CISList,
    Slider
  },
  data() {
    return {
      data: {
        users: "22",
        packages: "5",
      },
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
