<template>
  <b-card title="Notifications">
    <ul class="m-0 p-0">
      <li
        class="ps-container scrollable-container media-list scroll-area ps--active-y"
        style="height: 210px; overflow: auto"
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
                  >View Client</b-link
                >
              </small>
            </div>
          </div>
        </a>
      </li>
    </ul>
  </b-card>
</template>
<script>
import { BCard, BLink } from "bootstrap-vue";

import { mapGetters } from "vuex";

export default {
  components: {
    BCard,
    BLink,
  },
  data() {
    return {
      notifications: [],
    };
  },
  computed: {
    ...mapGetters(["user"]),
  },
  async created() {
    const { data } = await axios.get(`notification/${this.user.id}`);
    this.notifications = data.notifications;
  },
};
</script>
