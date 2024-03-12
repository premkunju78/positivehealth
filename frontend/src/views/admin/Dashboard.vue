<template>
  <div>
  <section id="dashboard-analytics">
    <b-row>
      <b-col lg="8" sm="7">
        <b-row class="match-height">
          <b-col
            lg="6"
            sm="8"
          >
            <statistic-card-with-area-chart
              v-if="data.users"
              icon="UsersIcon"
              :statistic="30"
              statistic-title="Total Alliance Partners"
              :chart-data='[1, 22]'
            />
          </b-col>
          <b-col
            lg="6"
            sm="8"
          >
            <statistic-card-with-area-chart
              v-if="data.packages"
              icon="PackageIcon"
              color="warning"
              :statistic="50"
              statistic-title="Total Packages Running"
              :chart-data='[3, 55]'
            />
          </b-col>
        </b-row>
        <b-row>
          <b-col>
            <b-card title="Positive Health App">
              <b-card-text>We have wide variety of Health packages.</b-card-text>
              <b-card-text>Please make sure to read our <b-link
                href="https://pixinvent.com/demo/vuexy-vuejs-admin-dashboard-template/documentation/"
                target="_blank"
              >
                Our Documentation
              </b-link> to understand where to go from here and how to use our app.</b-card-text>
            </b-card>
          </b-col>
        </b-row>
      </b-col>
      <b-col lg="4" sm="5">
          <b-card title="Notifications">
            <ul class="m-0 p-0">
              <li class="ps-container scrollable-container media-list scroll-area ps--active-y" style="height:350px;overflow:auto">
                <a href="#" target="_self" v-for="notification in notifications" :key="notification.id">
                  <div class="media">
                    <div class="media-body">
                      <small class="notification-text">
                        {{notification.message}}
                        <b-link :to="{name:'book-appointments', params: {id: notification.meta.consultant_id, role:  notification.meta.role_id}}" class="badge badge-primary">Book Now</b-link>
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
                <blockquote style=" text-indent: -0.45em; font-weight:400">
                  {{affirmation}}
                </blockquote>
              </div>
            </div>
          </b-card>
      </b-col>
    </b-row>
  </section>
  <section id="dashboard-contents">
  </section>


  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import { BRow, BCol, BCard, BCardText, BLink } from 'bootstrap-vue'
import StatisticCardWithAreaChart from '@core/components/statistics-cards/StatisticCardWithAreaChart.vue'

export default {
  components: {
    BRow,
    BCol,
    BCard,
    BCardText,
    BLink,
    StatisticCardWithAreaChart,
  },
  data() {
    return {
      data: {
        users: "22",
        packages: "5",
      },
      affirmation: '',
      notifications: []
    }
  },
  computed: {
    ...mapGetters(["user"])
  },
  created(){
    this.fetchNotifications();
    this.fetchAffirmation();
  },
  methods: {
    async fetchNotifications(){
        const {data} = await axios.get(`notification/${this.$store.state.auth.user.id}`);
        console.log(data)
        this.notifications = data.notifications;
    },
    async fetchAffirmation(){
        const {data} = await axios.get(`affirmation/today`);
        this.affirmation = data.affirmation;
    }
  }
}

</script>

<style>

</style>
