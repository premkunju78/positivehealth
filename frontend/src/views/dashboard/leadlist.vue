<template>
  <b-table style="min-height: 250px" ref="refRolesTable" class="position-relative" :items="leads" responsive
    :fields="columns" :totalRows="pagination.total" primaryKey="index" :sort-by.sync="sortBy" show-empty
    empty-text="No matching records found" :sort-desc.sync="isSortDirDesc" :tbody-tr-class="rowClass">
    <!-- Column: Actions -->
    <template #cell(index)="data">
      {{ data.index + 1 }}
    </template>
    <template #cell(name)="data">
      <b-button variant="link" class="ml-2 text-primary" @click="viewInfo(data.item)">
        <span class="text-nowrap">{{ data.item.name }}</span>
      </b-button>
    </template>
    <template #cell(status)="data">
      {{ data.item.status }}
      <span v-if="data.item.status == 'Call back'">(Date: {{ moment(data.item.callback_date).format('DD-MM-YYY') }}
        )</span>
    </template>
    <template #cell(call)="data">
      <b-button variant="success" class="btn btn-primary ml-2" @click="callNow(data.item)">
        <span class="text-nowrap">Call Now</span>
      </b-button>
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
} from "bootstrap-vue";
import StarRating from "vue-star-rating";
import moment from 'moment';

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
      pagination: {
        page: 1,
        per_page: 10,
        total: null,
        filter: {},
      },
      type: "New Lead",
      list: [],
      totalRows: 0,
      lead: null,
      moment: moment
    };
  },
  setup() {
    const leads = null;
    const loading = true;
    const sortBy = "1";
    const isSortDirDesc = false;
    const columns = [
      { key: "name", label: "Lead Name", sortable: true },
      { key: "alliancepartner_name", label: "Alliance Partner Name", sortable: true },
      { key: "concern", label: "Concern", sortable: true },
      { key: "status", label: "Status", sortable: true },
      { key: "call", label: "Call", sortable: true },
    ];
    return {
      leads,
      loading,
      sortBy,
      isSortDirDesc,
      columns,
    };
  },
  created() {
    this.getList();
  },
  methods: {
    rowClass(item, type) {
      if (item && type === "row") {
        if (item.status === "Interested") {
          return "tr-Interested";
        }
      } else {
        return null;
      }
    },
    adjustColumns() {
      if (this.$store.getters.user.role_id == 1) {
        this.columns = [
          { key: "index", label: "Sr. No.", sortable: true },
          // { key: "consultant", label: "Referred partner", sortable: true },
          { key: "name", label: "Lead Name", sortable: true },
          { key: "alliancepartner_name", label: "Alliance Partner Name", sortable: true },
          { key: "email", label: "Email", sortable: true },
          { key: "phone", label: "Phone Number", sortable: true },
          { key: "concern", label: "Concern", sortable: true },
          { key: "status", label: "Status", sortable: true },
          { key: "call", label: "Call", sortable: true },
          { key: "actions", label: "Action", sortable: true },
        ];
      } else if (this.$store.getters.user.role.is_consultant == 1) {
        this.columns = [
          { key: "index", label: "Sr. No.", sortable: true },
          { key: "name", label: "Name", sortable: true },
          { key: "concern", label: "Concern", sortable: true },
          { key: "status", label: "Status", sortable: true },
          { key: "call", label: "Call", sortable: true },
          { key: "actions", label: "Action", sortable: true },
        ];
      } else if (this.$store.getters.user.role_id == 15) {
        this.columns = [
          { key: "name", label: "Lead Name", sortable: true },
          { key: "alliancepartner_name", label: "Alliance Partner Name", sortable: true },
          { key: "status", label: "Status", sortable: true },
        ];
      }else if (this.$store.getters.user.role_id == 16) {
        this.columns = [
          { key: "name", label: "Lead Name", sortable: true },
          { key: "alliancepartner_name", label: "Alliance Partner Name", sortable: true },
          { key: "status", label: "Status", sortable: true },
        ];
      } else {
        this.columns.push({ key: "actions", label: "Action", sortable: false });
      }
    },
    async getList() {
      this.adjustColumns();
      try {
        if (this.$route.name === "leads") {
          this.pagination.user_id = this.id;
        }
        this.pagination.type = this.page;
        const { data } = await axios.get("/lead", {
          params: this.pagination,
        });
        this.leads = data.leads;
        this.pagination.per_page = data.per_page;
        this.pagination.total = data.total;
        this.pagination.page = data.page;
      } catch (err) {
        console.log(err);
      }
    },
  },
};
</script>
