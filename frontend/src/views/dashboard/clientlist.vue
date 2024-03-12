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
    :tbody-tr-class="rowClass"
  >
    <template v-slot:cell(selected)="data">
      <b-form-group>
        <input type="checkbox" :value="data.item.id" v-model="clientgroupData.selected" />
      </b-form-group>
    </template>
    <!-- <template #head(selected)="data">
          <b-form-group>
            <input type="checkbox" />
          </b-form-group>
        </template> -->
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
    <template #cell(call)="data">
      <b-button
        variant="success"
        class="btn btn-primary ml-2"
        @click="callNow(data.item.phone)"
      >
        <span class="text-nowrap">Call Now</span>
      </b-button>
    </template>
    <template #cell(status)="data">
      {{ data.item.client_status }}
    </template>
    <template #cell(questinaire)="data">
      <router-link :to="'questionnaires/' + data.item.id">Questionnaire</router-link>
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
      items: [],
      perPage: 10,
      perPageOptions: [10, 25, 50, 100],
      totalRows: "",
      search: "",
      searchQuery: "",
      limit: "10",
      sortBy: "1",
      isSortDirDesc: false,
      tableColumns: [
        { key: "id", label: "User Id", sortable: true },
        { key: "name", label: "Full Name", sortable: true },
        { key: "group_name", label: "Group", sortable: true },
        { key: "referred_partner", label: "Referred Partner", sortable: true },
        { key: "status", label: "Status", sortable: true },
      ],
      pagination: {
        page: 1,
        per_page: 10000,
        total: null,
      },
    };
  },
  created() {
    this.getList();
    this.columns();
  },
  methods: {
    columns() {
      if (
        this.$store.getters.user.role_id == 11 &&
        this.$store.getters.user.detail.cisf == 1
      ) {
        this.tableColumns = [
          { key: "id", label: "User Id", sortable: true },
          { key: "name", label: "Full Name", sortable: true },
          { key: "group_name", label: "Group", sortable: true },
          { key: "referred_partner", label: "Referred Partner", sortable: true },
          { key: "status", label: "Status", sortable: true },
          { key: "questinaire", label: "Questinaires", sortable: true },
        ];
      }
    },
    rowClass(item, type) {
      if (item && type === "row") {
        if (item.client_status === "Pending") {
          return "tr-Pending";
        } else if (item.client_status === "Completed" && !item.hc_id) {
          return "tr-Completed";
        } else {
          return "";
        }
      } else {
        return null;
      }
    },
    async getList() {
      try {
        this.pagination.id = this.id;
        this.pagination.role = this.role;
        const { data } = await axios.get("/clients", {
          params: this.pagination,
        });
        console.log(data.clients);
        this.items = data.clients;
        this.pagination.per_page = data.per_page;
        this.pagination.total = data.total;
        this.pagination.page = data.page;
      } catch {
        toast({
          component: ToastificationContent,
          props: {
            title: "Error fetching client list",
            icon: "AlertTriangleIcon",
            variant: "danger",
          },
        });
      }
    },
  },
};
</script>
