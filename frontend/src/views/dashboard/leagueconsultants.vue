<template>
  <b-table
    ref="refUserListTable"
    class="position-relative"
    :items="users"
    responsive
    :fields="tableColumns"
    primary-key="id"
    :sort-by.sync="sortBy"
    show-empty
    empty-text="No matching records found"
    :sort-desc.sync="isSortDirDesc"
  >
    <!-- Column: User -->
    
    <template #cell(id)="data">
      {{ "CN-" + data.item.id }}
    </template>
  </b-table>
</template>
<script>
import {
  BCard,
  BLink,
  BCardTitle,
  BCarousel,
  BCarouselSlide,
  BTable,
  BDropdown,
  BDropdownItem,
  BButton,
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
    BButton,
  },
  data() {
    return {
      tableColumns: [
        { key: "name", label: "Full Name", sortable: true },
        { key: "role", label: "Sub Role", sortable: true },
        { key: "specialized_in", label: "Specialization", sortable: true },
      ],
      sortBy: "1",
      isSortDirDesc: false,
      pagination: {
        page: 1,
        per_page: 10000,
        total: null,
        filters: {},
      },
      users: [],
      loading: false,
    };
  },
  created() {
    this.getList();
  },
  methods: {
    async getList() {
      try {
        this.loading = true;
        this.pagination.filters.is_upgraded = 1;
        const { data } = await axios.get("/consultants", {
          params: this.pagination,
        });
        this.users = data.users;
        this.pagination.per_page = data.per_page;
        this.pagination.total = data.total;
        this.pagination.page = data.page;
        
      } catch (err) {
        console.log(err);
      }
      this.loading = false;
    },
  },
};
</script>
