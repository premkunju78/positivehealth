<template>
  <div>
    <b-card no-body class="mb-0">
      <div class="m-2">
        <b-row class="mb-2">
          <b-col cols="8" md="4" sm="6" class="justify-content-start mb-1 mb-md-0">
            <b-form-group label="Group" label-for="group">
              <v-select v-model="pagination.filters.group" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="groupOptions" :clearable="true" id="group" @input="applyFilters" />
            </b-form-group>
          </b-col>
          <b-col cols="8" md="4" sm="6" class="justify-content-start mb-1 mb-md-0">
            <b-form-group label="Sub Group" label-for="sub_group">
              <v-select v-model="pagination.filters.type" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="partnerTypes" :clearable="true" id="sub_group" @input="applyFilters" />
            </b-form-group>
          </b-col>
          <b-col cols="8" md="4" sm="6" class="justify-content-start mb-1 mb-md-0">
            <b-form-group label="Name" label-for="name">
              <b-form-input id="name" v-model="pagination.filters.name" placeholder="Search by Name..."
                @input="applyFilters" />
            </b-form-group>
          </b-col>
        </b-row>
        <!-- Table Top -->
        <b-row>
          <!-- Per Page -->
          <b-col cols="12" md="6" class="d-flex align-items-center justify-content-start mb-1 mb-md-0">
            <label>Show</label>
            <v-select v-model="pagination.per_page" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
              :options="[10, 25, 50, 100, 1000]" :clearable="false" class="per-page-selector d-inline-block mx-50" />
            <label>entries</label>
          </b-col>

          <!-- Search -->
          <b-col cols="12" md="6">
            <div class="d-flex align-items-center justify-content-end">
              <!-- <b-form-input
                v-model="searchQuery"
                class="d-inline-block mr-1"
                placeholder="Search..."
              /> -->
              <b-link :to="{ name: 'create-partner' }" v-permission="['create-users', 'create-alliance-partners']">
                <b-button variant="primary">
                  <span class="text-nowrap">Add Alliance Partner</span>
                </b-button>
              </b-link>

              <!-- <b-button
                variant="primary"
                  @click="addUser"
                  v-permission="['create-users']"
              >
                <span class="text-nowrap">Add New</span>
              </b-button> -->
            </div>
          </b-col>
        </b-row>
      </div>

      <b-table ref="refUserListTable" class="position-relative" :items="items" responsive :fields="tableColumns"
        primary-key="id" :sort-by.sync="sortBy" :totalRows="totalRows" show-empty empty-text="No matching records found"
        :sort-desc.sync="isSortDirDesc">
        <!-- Column: User -->
        <template #cell(user)="data">
          <b-media vertical-align="center">
            <template #aside>
              <b-avatar size="32" :src="data.item.avatar" :text="avatarText(data.item.name)"
                :variant="`light-${resolveUserRoleVariant(data.item.role)}`"
                :to="{ name: 'apps-users-view', params: { id: data.item.id } }" />
            </template>
            {{ data.item.name }}
          </b-media>
        </template>
        <template #cell(id)="data">
          {{ "AP-" + data.item.id }}
        </template>
        <!-- Column: Role -->
        <template #cell(role)="data">
          <div class="text-nowrap">
            <feather-icon :icon="resolveUserRoleIcon(data.item.role)" size="18" class="mr-50"
              :class="`text-${resolveUserRoleVariant(data.item.role)}`" />
            <span class="align-text-top text-capitalize">{{ data.item.role }}</span>
          </div>
        </template>

        <template #cell(coordinators)="data">
          <div class="text-nowrap">
            <p class="align-text-top text-capitalize m-0" v-for="name in data.item.coordinators">
              {{ name }}
            </p>
          </div>
        </template>

        <template #cell(call)="data">
          <b-button variant="success" class="btn btn-primary ml-2" @click="callNow(data.item.phone)">
            <span class="text-nowrap">Call Now</span>
          </b-button>
        </template>

        <template #cell(added_by)="data">
          <div class="text-nowrap">
            {{ data.item.added_by }}
          </div>
        </template>
        <template #cell(clients)="data">
          <div class="text-nowrap">
            <p> No of clients:{{ data.item.clients_count }} </p>
            <p> Revenue:{{ data.item.revenue }} </p>
          </div>
        </template>

        <!-- Column: Actions -->
        <template #cell(actions)="data">
          <b-dropdown variant="link" no-caret :right="$store.state.appConfig.isRTL" v-permission="['view-users']">
            <template #button-content>
              <feather-icon icon="MoreVerticalIcon" size="16" class="align-middle text-body" />
            </template>
            <b-dropdown-item :to="{
              name: 'mapped-clients',
              params: { id: data.item.id, role: 'alliance-partner' },
            }">
              <feather-icon icon="EyeIcon" />
              <span class="align-middle ml-50">View Clients</span>
            </b-dropdown-item>

            <b-dropdown-item :to="{
              name: 'edit-partner',
              params: { id: data.item.id, user: data.item },
            }" v-permission="['edit-users', 'edit-alliance-partners']">
              <feather-icon icon="EditIcon" />
              <span class="align-middle ml-50">Edit</span>
            </b-dropdown-item>
            <b-dropdown-item v-if="$store.getters.user.role_id == 1" @click="deleteAlliancePartner(data.item)">
              <feather-icon icon="EditIcon" />
              <span class="align-middle ml-50">Delete</span>
            </b-dropdown-item>
            <b-dropdown-item v-if="$store.getters.user.role_id == 15" @click="approveAlliancePartner(data.item)">
              <feather-icon icon="EditIcon" />
              <span class="align-middle ml-50">Approve</span>
            </b-dropdown-item>
            <!-- <b-dropdown-item :to="{ name: 'AllianConsultantList', params: { id: data.item.id } }"  v-permission="['edit-users']">
              <feather-icon icon="UserIcon" />
              <span class="align-middle ml-50">Consultants</span>
            </b-dropdown-item> -->

            <!-- <b-dropdown-item  v-permission="['delete-users']">
              <feather-icon icon="TrashIcon"/>
              <span class="align-middle ml-50">Delete</span>
            </b-dropdown-item> -->
          </b-dropdown>
        </template>
      </b-table>
      <b-pagination v-if="pagination.total" v-model="pagination.page" :page.sync="pagination.page"
        :total-rows="pagination.total" :index="1" :per-page="pagination.per_page" aria-controls="package-row"
        align="right" @change="handlePaginationChange">
      </b-pagination>
    </b-card>
  </div>
</template>

<script>
import {
  BCard,
  BRow,
  BCol,
  BFormInput,
  BButton,
  BTable,
  BMedia,
  BAvatar,
  BLink,
  BBadge,
  BDropdown,
  BDropdownItem,
  BPagination,
  BFormGroup,
} from "bootstrap-vue";

import vSelect from "vue-select";

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

export default {
  components: {
    BCard,
    BRow,
    BCol,
    BFormInput,
    BButton,
    BTable,
    BMedia,
    BAvatar,
    BLink,
    BBadge,
    BDropdown,
    BDropdownItem,
    BPagination,
    vSelect,
    BFormGroup,
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
      groupOptions: [
        "Doctors",
        "Healthcare Professionals",
        "Institute",
        "Company",
        "Corporates",
        "Hospitals",
      ],
      partnerTypes: [],
      pagination: {
        page: 1,
        per_page: 10,
        total: null,
        filters: {},
      },
      tableColumns: [
        { key: "id", label: "Id", sortable: true },
        { key: "name", label: "Name", sortable: true },
        { key: "email", label: "Email", sortable: true },
        { key: "phone", label: "Phone", sortable: true },
        { key: "coordinators", label: "Coordinators", sortable: true },
        { key: "group", label: "Group", sortable: true, filterByFormatted: true },
        { key: "type", label: "Sub Group", sortable: true },
        { key: "actions" },
      ],
    };
  },
  created() {
    this.getList();
    this.getPartnerTypes();
    this.columns();
  },
  methods: {
    columns() {
      if (this.$store.getters.user.role_id === 10) {
        this.tableColumns = [
          { key: "id", label: "Id", sortable: true },
          { key: "name", label: "Name", sortable: true },
          { key: "email", label: "Email", sortable: true },
          { key: "group", label: "Group", sortable: true, filterByFormatted: true },
          { key: "type", label: "Sub Group", sortable: true },
          { key: "call", label: "Call", sortable: true },
        ];
      } else if (this.$store.getters.user.role_id === 15) {
        this.tableColumns = [
          { key: "id", label: "Id", sortable: true },
          { key: "name", label: "Name", sortable: true },
          { key: "email", label: "Email", sortable: true },
          { key: "group", label: "Group", sortable: true, filterByFormatted: true },
          { key: "type", label: "Sub Group", sortable: true },
          { key: "added_by", label: "Added By", sortable: true },
          { key: "clients", label: "Clients", sortable: true },
          { key: "actions" },
        ];
      }
      else if (this.$store.getters.user.role_id === 16) {
        this.tableColumns = [
          { key: "id", label: "Id", sortable: true },
          { key: "name", label: "Name", sortable: true },
          { key: "email", label: "Email", sortable: true },
          { key: "group", label: "Group", sortable: true, filterByFormatted: true },
          { key: "type", label: "Sub Group", sortable: true },
          { key: "clients", label: "Clients", sortable: true },
          { key: "actions" },
        ];
      }
    },
    async getPartnerTypes(val = null) {
      try {
        this.loading = true;
        const { data } = await axios.get("alliance-partners/partnerTypes", {
          params: {
            type: val,
          },
        });
        this.partnerTypes = data.partnerTypes;
      } catch (err) {
        console.log(err);
      }
    },
    async getList() {
      try {
        const { data } = await axios.get("/alliance-partners", {
          params: this.pagination,
        });
        this.items = data.partners;
        this.pagination.per_page = data.per_page;
        this.pagination.total = data.total;
        this.pagination.page = data.page;
      } catch {
        this.$toast({
          component: ToastificationContent,
          props: {
            title: "Error fetching client list",
            icon: "AlertTriangleIcon",
            variant: "danger",
          },
        });
      }
    },
    handlePaginationChange(val) {
      this.pagination.page = val;
      this.getList();
    },
    resolveUserRoleVariant(role) {
      if (role === "subscriber") return "primary";
      if (role === "author") return "warning";
      if (role === "maintainer") return "success";
      if (role === "editor") return "info";
      if (role === "admin") return "danger";
      return "primary";
    },
    resolveUserRoleIcon(role) {
      if (role === "subscriber") return "UserIcon";
      if (role === "author") return "SettingsIcon";
      if (role === "maintainer") return "DatabaseIcon";
      if (role === "editor") return "Edit2Icon";
      if (role === "admin") return "ServerIcon";
      return "UserIcon";
    },
    applyFilters() {
      this.getList();
    },
    async callNow(phone) {
      try {
        const { data } = await axios.post(`/coordinator/call/`, { phone: phone });
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "AlertTriangleIcon",
            variant: data.success ? "success" : "danger",
          },
        });
      } catch {
        this.$toast({
          component: ToastificationContent,
          props: {
            title: "Error while initiating a call",
            icon: "AlertTriangleIcon",
            variant: "danger",
          },
        });
      }
    },
    async deleteAlliancePartner(alliancepartner) {
      if (confirm("Do you really want to delete?")) {
        try {
          let data;
          data = await axios.delete(`/alliance-partner/${alliancepartner.id}`);
          this.$toast({
            component: ToastificationContent,
            props: {
              title: data.data.message,
              icon: "EditIcon",
              variant: data.data.success ? "success" : "info"
            }
          });
          this.getList();
        } catch (error) {

        }
      }
    },
    async approveAlliancePartner(alliancepartner) {
      if (confirm("Do you really want to approve?")) {
        try {
          let data;
          data = await axios.post(`/alliance-partner/approve/${alliancepartner.id}`);
          this.$toast({
            component: ToastificationContent,
            props: {
              title: data.data.message,
              icon: "EditIcon",
              variant: data.data.success ? "success" : "info"
            }
          });
          this.getList();
        } catch (error) {

        }
      }
    },
  },
};
</script>
<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";
</style>
