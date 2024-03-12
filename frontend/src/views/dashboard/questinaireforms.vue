<template>
  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Description</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="questinaire in questionnaires">
          <td>{{ questinaire.name }}</td>
          <td>{{ questinaire.description }}</td>
          <td>
            <router-link
              :to="{ name: 'edit-questionnaire', params: { id: questinaire.id } }"
            >
              <feather-icon icon="EyeIcon" />
              <span class="align-middle ml-50">PreView</span>
            </router-link>
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
      questionnaires: [],
      qpagination: {
        page: 1,
        per_page: 10,
        total: null,
      },
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
    this.qpagination.id = this.user_id;
    const { data } = await axios.get("/questionnaire", {
      params: this.qpagination,
    });
    this.questionnaires = data.questionnaires.data;
    console.log(this.questionnaires);
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
