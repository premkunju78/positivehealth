<template>
  <b-table
        ref="refUserListTable"
        class="position-relative"
        :items="items"
        responsive
        :fields="tableColumns"
        primary-key="id"
        :sort-by.sync="sortBy"
        :totalRows="totalRows"
        show-empty
        empty-text="No matching records found"
        :sort-desc.sync="isSortDirDesc"
      >
        <!-- Column: User -->
        <template #cell(user)="data">
          <b-media vertical-align="center">
            <template #aside>
              <b-avatar
                size="32"
                :src="data.item.avatar"
                :text="avatarText(data.item.name)"
                :variant="`light-primary`"
                :to="{ name: 'apps-users-view', params: { id: data.item.id } }"
              />
            </template>
            {{ data.item.name }}
          </b-media>
        </template>
        <template #cell(id)="data">
          {{ "CL-" + data.item.id }}
        </template>
        <!-- Column: Actions -->
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
      items: [],
      tableColumns: [
        { key: "id", label: "User Id", sortable: true },
        { key: "name", label: "Full Name", sortable: true },
        { key: "email", label: "Email", sortable: true },
        { key: "phone", label: "Contact Number", sortable: true },
      ],
      pagination: {
        page: 1,
        per_page: 10,
        total: null,
      },
    };
  },
  created() {
    this.getList();
  },
  methods: {
    async getList() {
      try {
        this.pagination.id = this.id;
        this.pagination.role = this.role;
        this.pagination.cis = 1;
        const { data } = await axios.get("/healthcoaches", {
          params: this.pagination,
        });
        console.log(data.healthcoaches);
        this.items = data.healthcoaches;
        this.pagination.per_page = data.per_page;
        this.pagination.total = data.total;
        this.pagination.page = data.page;
      } catch {
        toast({
          component: ToastificationContent,
          props: {
            title: "Error fetching healthcoach list",
            icon: "AlertTriangleIcon",
            variant: "danger",
          },
        });
      }
    },
  },
};
</script>

