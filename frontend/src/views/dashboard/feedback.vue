<template>
  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th>Patient</th>
          <th>Session</th>
          <th v-if="role_id == 3 || role_id == 2">Staff / Consultant</th>
          <th>Rating</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="feedback in feedbacks">
          <td>{{ feedback.client_name }}</td>
          <td>{{ feedback.session_title }}</td>
          <td v-if="role_id == 3 || role_id == 2">{{ feedback.user_name }}</td>
          <td>
            <star-rating :read-only="true" :rating="feedback.rating" :max-rating="10" :star-size="20" />
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script>
import { BCard, BLink, BCardTitle, BCarousel, BCarouselSlide } from "bootstrap-vue";
import StarRating from "vue-star-rating";

export default {
  components: {
    BCard,
    BLink,
    BCardTitle,
    BCarousel,
    BCarouselSlide,
    StarRating,
  },
  data() {
    return {
      feedbacks: null,
    };
  },
  computed: {
    user_id: function () {
      return this.$store.getters.user.id;
    },
    role_id: function () {
      return this.$store.getters.user.role_id;
    },
  },
  async created() {
    const { data } = await axios.get(`feedback/list`);
    this.feedbacks = data.feedbacks;
  },
};
</script>

<style lang="scss">
.carousel-caption {
  position: unset;
  text-align: left;
}

.carousel-indicators {
  position: unset;
  text-align: center;
  margin-top: -32px;
  display: block !important;
}

.carousel-indicators li {
  background-color: #afabab;
  display: inline-block;
}
</style>
