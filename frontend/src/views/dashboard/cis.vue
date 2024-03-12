<template>
  <div>
    <section id="dashboard-analytics">
      <b-row>
        <b-col lg="8" sm="7">
          <b-row>
            <b-col lg="12" v-if="coordinators">
              <div class="card" v-for="coordinator in coordinators">
                <div class="card-body">
                  <b>Coordinator</b>: <span>{{ coordinator.name }}</span>
                  <b-button
                    variant="success"
                    class="btn btn-primary ml-2"
                    @click="callNow(coordinator)"
                  >
                    <span class="text-nowrap">Call Now</span>
                  </b-button>
                  <router-link to="/chat" class="btn btn-primary ml-2"
                    >Message</router-link
                  >
                  <b class="ml-2">Email</b>: <span>{{ coordinator.email }}</span>
                </div>
              </div>
            </b-col>
            <b-col md="12">
              <b-card title="Todays schedule" style="height: 350px; overflow: auto">
                <TodaySchedule />
              </b-card>
            </b-col>
            <b-col md="12">
              <b-card title="Client list" style="height: 350px; overflow: auto">
                <ClientList />
              </b-card>
            </b-col>
            <b-col md="12">
              <b-card title="Lead list" style="height: 350px; overflow: auto">
                <LeadList />
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
        <b-col lg="8" sm="7"></b-col>
        <b-col lg="4" sm="5">
          <b-card>
            <div class="media">
              <div class="media-body">
                <blockquote style="text-indent: -0.45em; font-weight: 400">
                  {{ affirmation }}
                </blockquote>
              </div>
            </div>
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
import ClientList from "./clientlist.vue";
import LeadList from "./leadlist.vue";
import Slider from "./slider.vue";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

export default {
  components: {
    BRow,
    BCol,
    BCard,
    BCardText,
    BLink,
    StatisticCardWithAreaChart,
    TodaySchedule,
    ClientList,
    Slider,
    LeadList
  },
  data() {
    return {
      data: {
        users: "22",
        packages: "5",
      },
      affirmation: "",
      notifications: [],
      coordinators: [],
    };
  },
  computed: {
    ...mapGetters(["user"]),
  },
  created() {
    this.fetchNotifications();
    this.fetchAffirmation();
    this.fetchCoordinators();
  },
  methods: {
    async fetchNotifications() {
      const { data } = await axios.get(`notification/${this.$store.state.auth.user.id}`);
      console.log(data);
      this.notifications = data.notifications;
    },
    async fetchCoordinators() {
      let pagination = {
        page: 1,
        per_page: 1000,
        total: null,
      };
      const { data } = await axios.get(`coordinators/`, {
        params: pagination,
      });
      console.log(data);
      this.coordinators = data.coordinators;
    },
    async fetchAffirmation() {
      const { data } = await axios.get(`affirmation/today`);
      this.affirmation = data.affirmation;
    },
    async callNow(coordinator) {
      try {
        const { data } = await axios.post(
          `/coordinator/call`,
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
