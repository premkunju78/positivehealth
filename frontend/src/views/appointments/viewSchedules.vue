<template>
  <div style="min-height: 300px">
    <b-overlay
      :show="$store.state.app.shallShowOverlay"
      no-wrap
      opacity="0.5"
      blur="1px"
    ></b-overlay>
    <b-card>
      <b-row class="mb-2">
        <b-col cols="8" md="4" sm="6" class="justify-content-start mb-1 mb-md-0">
          <b-form-group label="From Date" label-for="from_date">
            <b-form-datepicker
              id="from_date"
              v-model="pagination.filters.from"
              class="d-inline-block"
              @input="applyFilters"
              :reset-button="true"
              :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
            ></b-form-datepicker>
          </b-form-group>
        </b-col>
        <b-col cols="8" md="4" sm="6" class="justify-content-start mb-1 mb-md-0">
          <b-form-group label="To Date" label-for="to_date">
            <b-form-datepicker
              id="to_date"
              v-model="pagination.filters.to"
              class="d-inline-block"
              @input="applyFilters"
              :reset-button="true"
              :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
            ></b-form-datepicker>
          </b-form-group>
        </b-col>
        <b-col cols="8" md="4" sm="6" class="justify-content-start mb-1 mb-md-0">
          <b-form-group label="Consultant Type" label-for="role">
            <v-select
              v-model="pagination.filters.role"
              :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
              :options="roles"
              :reduce="(val) => val.id"
              :clearable="true"
              @input="applyFilters"
            />
          </b-form-group>
        </b-col>
      </b-row>
      <b-row class="mb-2">
        <!-- Per Page -->
        <b-col
          cols="12"
          md="6"
          class="d-flex align-items-center justify-content-start mb-1 mb-md-0"
        >
          <label>Show</label>
          <v-select
            v-model="pagination.per_page"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="[10, 25, 50, 100, 1000]"
            :clearable="false"
            class="per-page-selector d-inline-block mx-50"
          />
          <label>entries</label>
        </b-col>

        <!-- Search -->
        <b-col cols="12" md="6"> </b-col>
      </b-row>

      <b-table
        style="min-height: 250px"
        ref="refRolesTable"
        class="position-relative"
        :items="packages"
        responsive
        :fields="columns"
        :totalRows="pagination.total"
        primaryKey="index"
        :sort-by.sync="sortBy"
        show-empty
        empty-text="No matching records found"
        :sort-desc.sync="isSortDirDesc"
      >
        <!-- Column: Actions -->
        <template #cell(index)="data">
          {{ data.index + 1 }}
        </template>
        <template #cell(name)="data">
          <span class="text-primary">{{ data.item.name.toUpperCase() }}</span>
        </template>

        <!-- Column: Actions -->
        <template #cell(cost)="data">
          <span class="text-primary" v-if="data.item.transaction_id"
            >PAID &nbsp; ₹ {{ data.item.cost }}</span
          >
          <b-button
            variant="success"
            :block="$store.getters['app/currentBreakPoint'] === 'xs'"
            type="submit"
            class="mr-2"
            v-permission="['view-pkgs']"
          >
            Pay &nbsp; ₹ {{ data.item.cost }}
          </b-button>
        </template>
      </b-table>
      <b-pagination
        v-if="pagination.total"
        v-model="pagination.page"
        :page.sync="pagination.page"
        :total-rows="pagination.total"
        :index="1"
        :per-page="pagination.per_page"
        aria-controls="video-row"
        align="right"
        @change="handlePaginationChange"
      >
      </b-pagination>
    </b-card>
  </div>
</template>
<style>
iframe {
  width: 100% !important;
  height: 200px !important;
}
</style>
<script>
import {
  BCard,
  BCardText,
  BTable,
  BRow,
  BCol,
  BPagination,
  BSpinner,
  BButton,
  BDropdown,
  BDropdownItem,
  BFormInput,
  BFormTextarea,
  BFormGroup,
  BOverlay,
  BFormCheckbox,
  BFormDatepicker,
} from "bootstrap-vue";

import { required } from "@validations";

import vSelect from "vue-select";

import Ripple from "vue-ripple-directive";

export default {
  components: {
    BCard,
    BCardText,
    BTable,
    BRow,
    BCol,
    BPagination,
    BSpinner,
    vSelect,
    BButton,
    BDropdown,
    BDropdownItem,
    BFormInput,
    BFormGroup,
    BFormTextarea,
    BOverlay,
    BFormCheckbox,
    BFormDatepicker,
  },

  data() {
    const today = new Date();
    return {
      pagination: {
        page: 1,
        per_page: 10,
        total: null,
        filters: {
          from: today,
          to: new Date(today.getFullYear(), today.getMonth() + 1, 0),
        },
      },
      list: [],
      package: null,
      required,
      type: "Create Pakage",
      groupPackages: [],
      readonly: false,
      errors: [],
      mappingData: {
        partners: [],
        cost: null,
      },
      totalRows: 0,
      partners: [],
      filters: {},
      roles: [],
    };
  },
  directives: {
    Ripple,
  },
  setup() {
    const packages = null;
    const loading = true;
    const sortBy = "1";
    const isSortDirDesc = false;
    const columns = [
      { key: "index", label: "Sr. No.", sortable: true },
      { key: "consultant", label: "Consultant Name", sortable: true },
      { key: "role", label: "Type", sortable: true },
      { key: "name", label: "Name", sortable: true },
      { key: "timeslot_from", label: "From", sortable: true },
      { key: "timeslot_to", label: "To", sortable: true },
      { key: "description", label: "Desription", sortable: true },
    ];

    return {
      packages,

      loading,
      sortBy,
      isSortDirDesc,
      columns,
    };
  },
  watch: {
    "pagination.per_page": function (val) {
      this.handleFilterChange(val);
    },
  },
  created() {
    this.getList();
    this.getRoles();
  },
  methods: {
    async getRoles() {
      try {
        this.loading = true;
        const { data } = await axios.get("adminRoles/list");
        this.roles = data.roles;
      } catch (err) {
        console.log(err);
      }
      this.loading = false;
    },
    async getList() {
      try {
        this.loading = true;
        const { data } = await axios.post("schedules", this.pagination);
        this.packages = data.schedules;
        this.pagination.per_page = data.per_page;
        this.pagination.total = data.total;
        this.pagination.page = data.page;
      } catch (err) {
        console.log(err);
      }
      this.loading = false;
    },

    handlePaginationChange(val) {
      this.pagination.page = val;
      this.getList();
    },
    handleFilterChange(val) {
      this.pagination.per_page = val;
      this.getList();
    },
    applyFilters() {
      this.getList();
    },
  },
};
</script>

<style lang="scss" scoped>
.per-page-selector {
  width: 90px;
}
</style>

<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";
</style>
