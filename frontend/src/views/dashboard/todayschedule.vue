<template>
  <b-table style="min-height: 250px; margin-top: 20px" ref="refRolesTable" class="position-relative" :items="list"
    responsive :fields="columns" :totalRows="pagination.total" primaryKey="index" :sort-by.sync="sortBy" show-empty
    empty-text="No matching records found" :sort-desc.sync="isSortDirDesc">
    <template #cell(index)="data">
      {{ data.index + 1 }}
    </template>
    <template #cell(status)="data">
      <span> {{ data.item.status }} </span>
    </template>
  </b-table>
</template>
<script>
import {
  BCard, BLink, BCardTitle, BCarousel, BCarouselSlide, BTable, BDropdown,
  BDropdownItem,
} from "bootstrap-vue";
import StarRating from "vue-star-rating";

export default {
  components: {
    BCard,
    BLink,
    BCardTitle,
    BCarousel,
    BCarouselSlide,
    StarRating,
    BTable,
    BDropdown,
    BDropdownItem,
  },
  data() {
    return {
      list: [],
      perPage: 10,
      perPageOptions: [10, 25, 50, 100],
      totalRows: "",
      search: "",
      searchQuery: "",
      limit: "10",
      sortBy: "1",
      isSortDirDesc: false,
    };
  },
  setup(props) {
    const columns = [
      { key: "schedule_datettime", label: "Schedule Time", sortable: true },
      { key: "type", label: "Schedule Type", sortable: true },
      { key: "title", label: "Title", sortable: true },
      { key: "client_name", label: "Client Name", sortable: false },
      { key: "status", label: "Status", sortable: false },
    ];

    const pagination = {
      page: 1,
      per_page: 8,
      total: null,
      keyword: null,
      filters: {},
      today: false,
    };

    return {
      columns,
      pagination,
    };
  },
  computed: {},
  created() {
    this.getList();
  },
  methods: {
    async getList() {
      try {
        if (this.$store.getters.user.role_id == "3") {
          this.pagination.filters.client_id = this.$store.getters.user.id;
        } else {
          this.pagination.filters.user_id = this.$store.getters.user.id;
        }
        this.pagination.today = 1;
        const { data } = await axios.get("workflowschedules", {
          params: this.pagination,
        });
        if (data.schedules) {
          this.list = data.schedules;
          this.pagination.per_page = data.per_page;
          this.pagination.total = data.total;
          this.pagination.page = data.page;
        }
      } catch (err) {
        console.log(err);
      }
    },
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
