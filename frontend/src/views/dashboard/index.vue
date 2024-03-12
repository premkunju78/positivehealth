<template>
  <component :is="component"></component>
</template>
<script>
import { mapGetters } from "vuex";

export default {
  components: {
    Admin: () => import(/* webpackPrefetch: true */ "../admin/Dashboard.vue"),
    Consultant: () => import(/* webpackPrefetch: true */ "./consultant.vue"),
    Client: () => import(/* webpackPrefetch: true */ "./client.vue"),
    Coordinator: () => import(/* webpackPrefetch: true */ "./coordinator.vue"),
    Healthcoach: () => import(/* webpackPrefetch: true */ "./healthcoach.vue"),
    Cis: () => import(/* webpackPrefetch: true */ "./cis.vue"),
    Alliancepartner: () => import(/* webpackPrefetch: true */ "./alliancepartner.vue"),
    MarketingHead: () => import(/* webpackPrefetch: true */ "./MarketingHead.vue"),
    MarketingExecutive: () => import(/* webpackPrefetch: true */ "./MarketingExecutive.vue"),
    DiagnosticHead: () => import(/* webpackPrefetch: true */ "./DiagnosticHead.vue"),
  },
  computed: {
    ...mapGetters(["user"]),
    component: function () {
      if (this.user.role_id == 3) {
        return "Client";
      } else if (this.user.role_id == 10) {
        return "Coordinator";
      } else if (this.user.role_id == 11 && this.user.detail.cisf != 1) {
        console.log();
        return "Healthcoach";
      } else if (this.user.role_id == 11 && this.user.detail.cisf == 1) {
        return "Cis";
      } else if (this.user.role_id == 2) {
        return "Alliancepartner";
      } else if (this.user.role_id == 15) {
        return "MarketingHead";
      } else if (this.user.role_id == 16) {
        return "MarketingExecutive";
      } else if (this.user.role_id == 17) {
        return "DiagnosticHead";
      } else if (this.user.role_id > 3) {
        return "Consultant";
      } else {
        return "Admin";
      }
    },
  },
};
</script>
