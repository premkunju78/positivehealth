<template>
  <div>
    <section id="dashboard-analytics">
      <b-row>
        <b-col lg="12" v-if="coordinator != null">
          <div class="card">
            <div class="card-body">
              <b>Coordinator</b>: <span>{{ coordinator.name }}</span>
              <b-button
                variant="success"
                class="btn btn-primary ml-2"
                @click="callNow(coordinator)"
              >
                <span class="text-nowrap">Call Now</span>
              </b-button>
              <router-link to="/chat" class="btn btn-primary ml-2">Message</router-link>
              <b class="ml-2">Email</b>: <span>{{ coordinator.email }}</span>
            </div>
          </div>
        </b-col>
        <b-col lg="8" sm="7">
          <b-row class="match-height">
            <b-col lg="4" sm="8">
              <statistic-card-with-area-chart
                icon="UsersIcon"
                :statistic="no_of_leads"
                statistic-title="No of Leads"
                :chart-data="[1, 22]"
              />
            </b-col>
            <b-col lg="4" sm="8">
              <statistic-card-with-area-chart
                icon="PackageIcon"
                color="warning"
                :statistic="no_of_clients"
                statistic-title="No of Clients"
                :chart-data="[3, 55]"
              />
            </b-col>
            <b-col lg="4" sm="8">
              <statistic-card-with-area-chart
                icon="UsersIcon"
                :statistic="'₹' + parseFloat(revenue).toFixed(2)"
                statistic-title="Revenue"
                :chart-data="[1, 22]"
              />
            </b-col>
          </b-row>
          <b-row>
            <b-col md="12">
              <b-card title="Feedback">
                <feedback />
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
    <section id="dashboard-contents"></section>
  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import { BRow, BCol, BCard, BCardText, BLink } from "bootstrap-vue";
import StatisticCardWithAreaChart from "@core/components/statistics-cards/StatisticCardWithAreaChart.vue";
import Slider from "./slider.vue";
import Feedback from "./feedback.vue";


export default {
  components: {
    BRow,
    BCol,
    BCard,
    BCardText,
    BLink,
    StatisticCardWithAreaChart,
    Slider,
    Feedback
  },
  data() {
    return {
      data: {
        users: "22",
        packages: "5",
      },
      affirmation: "",
      notifications: [],
      coordinator: null,
      no_of_leads: null,
      no_of_clients: null,
      revenue: null,
    };
  },
  computed: {
    ...mapGetters(["user"]),
  },
  created() {
    this.fetchNotifications();
    this.fetchAffirmation();
    this.fetchCoordinator();
    this.fetchDashboardData();
  },
  methods: {
    async fetchDashboardData() {
      const { data } = await axios.get(`alliance-partner/dashboard/`);
      console.log(data);
      this.no_of_leads = data.leads;
      this.no_of_clients = data.clients;
      this.revenue = data.revenue;
    },
    async fetchNotifications() {
      const { data } = await axios.get(`notification/${this.$store.state.auth.user.id}`);
      console.log(data);
      this.notifications = data.notifications;
    },
    async fetchCoordinator() {
      const { data } = await axios.get(
        `coordinators/getallianceCood/${this.$store.state.auth.user.id}`
      );
      console.log(data);
      this.coordinator = data.coordinator;
    },
    async fetchAffirmation() {
      const { data } = await axios.get(`affirmation/today`);
      this.affirmation = data.affirmation;
    },
    async callNow(coordinator) {
      try {
        const { data } = await axios.post(
          `/alliancepartner/callcoordinator`,
          coordinator
        );
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "AlertTriangleIcon",
            variant: data.success ? "success" : "danger",
          },
        });
      } catch {
        this.$toast({
          component: ToastificationContent,
          props: {
            title: "Error while initiating a call",
            icon: "AlertTriangleIcon",
            variant: "danger",
          },
        });
      }
    },
  },
};
</script>

<style></style>
