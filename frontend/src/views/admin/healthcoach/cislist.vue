<template>
  <div>
    <b-card no-body class="mb-0">
      <div class="m-2">
        <!-- Table Top -->
        <b-row>
          <!-- Per Page -->
          <b-col cols="12" md="6" class="d-flex align-items-center justify-content-start mb-1 mb-md-0">
          </b-col>

          <!-- Search -->
          <b-col cols="12" md="6">
            <div v-if="!id" class="d-flex align-items-center justify-content-end"
              v-permission="['create-healthcoaches']">
              <!-- <b-form-input
                v-model="searchQuery"
                class="d-inline-block mr-1"
                placeholder="Search..."
              /> -->
              <router-link :to="{ name: 'healthcoach-create' }">
                <b-button variant="primary" v-permission="['create-healthcoaches']">
                  <span class="text-nowrap">Add CIS</span>
                </b-button>
              </router-link>
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
              <b-avatar size="32" :src="data.item.avatar" :text="avatarText(data.item.name)" :variant="`light-primary`"
                :to="{ name: 'apps-users-view', params: { id: data.item.id } }" />
            </template>
            {{ data.item.name }}
          </b-media>
        </template>
        <template #cell(id)="data">
          {{ "CL-" + data.item.id }}
        </template>
        <!-- Column: Actions -->
        <template #cell(actions)="data">
          <div class="text-nowrap">
            <!-- Healthcoach Modules -->
            <b-dropdown variant="link" no-caret :right="$store.state.appConfig.isRTL"
              v-permission="['edit-healthcoaches']">
              <template #button-content>
                <feather-icon icon="MoreVerticalIcon" size="16" class="align-middle text-body"
                  v-permission="['edit-healthcoaches']" />
              </template>
              <!-- <b-dropdown-item :to="{ name: 'apps-users-view', params: { id: data.item.id } }" >
                <feather-icon icon="FileTextIcon" />
                <span class="align-middle ml-50">Details</span>
              </b-dropdown-item> -->

              <b-dropdown-item :to="{
                name: 'edit-healthcoach',
                params: { id: data.item.id, user: data.item },
              }" v-permission="['edit-healthcoaches']">
                <feather-icon icon="EditIcon" />
                <span class="align-middle ml-50">Edit</span>
              </b-dropdown-item>
              <b-dropdown-item v-if="$store.getters.user.role_id == 1" @click="deleteHC(data.item)">
                <feather-icon icon="EditIcon" />
                <span class="align-middle ml-50">Delete</span>
              </b-dropdown-item>
              <b-dropdown-item @click="viewAssignModal(data.item)" v-permission="['edit-healthcoaches']">
                <feather-icon icon="UsersIcon" />
                <span class="align-middle ml-50">Assign</span>
              </b-dropdown-item>
              <b-dropdown-item @click="viewLeadModal(data.item)" v-permission="['edit-healthcoaches']">
                <feather-icon icon="EyeIcon" />
                <span class="align-middle ml-50">View Leads</span>
              </b-dropdown-item>
              <!-- <b-dropdown-item  v-permission="['delete-users']">
                <feather-icon icon="TrashIcon"/>
                <span class="align-middle ml-50">Delete</span>
              </b-dropdown-item> -->
            </b-dropdown>
          </div>
        </template>
      </b-table>
      <b-pagination v-if="pagination.total" v-model="pagination.page" :page.sync="pagination.page"
        :total-rows="pagination.total" :index="1" :per-page="pagination.per_page" aria-controls="package-row"
        align="right" @change="handlePaginationChange">
      </b-pagination>
    </b-card>
    <b-modal v-permission="['edit-healthcoaches']" id="assign" ref="assign" ok-only ok-title="Assign" @show="getLeads"
      @hidden="resetData" @ok="handleOk" centered size="lg" title="Lead Assignment" no-close-on-backdrop>
      <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
        <form ref="form" @submit.prevent="handleSubmit(onAssign)">
          <b-row>
            <b-col cols="12" md="12">
              <validation-provider #default="validationContext" name="healthcoaches" rules="required">
                <b-form-group label="Select Leads" label-for="healthcoaches"
                  :state="getValidationState(validationContext)">
                  <v-select v-model="userData.leads" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="leads" :reduce="(val) => val.id" :clearable="false" multiple input-id="leads" />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
          </b-row>
        </form>
      </validation-observer>
    </b-modal>
    <b-modal v-permission="['edit-healthcoaches']" id="view-leads" hide-footer centered size="lg"
      @show="getAssignedLeads" @hidden="resetUsers" title="Assigned Consutants" ref="view-leads" no-close-on-backdrop>
      <b-table style="min-height: 250px" ref="refRolesTable" class="position-relative" :items="list" responsive
        :fields="ucolumns" :totalRows="totalRows" primaryKey="index" :sort-by.sync="sortBy" show-empty
        empty-text="No matching records found" :sort-desc.sync="isSortDirDesc" :sticky-header="true">
        <!-- Column: Actions -->
        <template #cell(index)="data">
          {{ data.index + 1 }}
        </template>
        <template #cell(name)="data">
          <span class="text-primary">{{ data.item.name.toUpperCase() }}</span>
        </template>
        <!-- Column: Actions -->
        <template #cell(actions)="data">
          <a @click="handleRemoveLead('healthcoach', data.item.healthcoach_lead_id)"
            v-permission="['edit-healthcoaches']" style="cursor: pointer" title="remove user">
            <feather-icon icon="TrashIcon" />
          </a>
        </template>
      </b-table>
    </b-modal>
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
  BFormGroup,
  BBadge,
  BDropdown,
  BDropdownItem,
  BPagination,
  BTooltip,
  VBModal,
  BModal,
  BFormInvalidFeedback,
} from "bootstrap-vue";

import { ValidationProvider, ValidationObserver } from "vee-validate";

import vSelect from "vue-select";

import formValidation from "@core/comp-functions/forms/form-validation";

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import { required } from "@validations";

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
    VBModal,
    BModal,
    BFormInvalidFeedback,
    BBadge,
    BDropdown,
    BDropdownItem,
    BPagination,
    BTooltip,
    vSelect,
    BFormGroup,
    BFormInput,

    ValidationProvider,
    ValidationObserver,
  },
  data() {
    const { refFormObserver, getValidationState, resetForm } = formValidation(
      this.resetData
    );
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
        { key: "id", label: "Id", sortable: true },
        { key: "name", label: "Full Name", sortable: true },
        { key: "gender", label: "Gender", sortable: true },
        { key: "email", label: "Email", sortable: true },
        { key: "phone", label: "Contact Number", sortable: true },
        { key: "actions" },
      ],
      ucolumns: [
        { key: "index", label: "Sr. No.", sortable: true },
        { key: "name", label: "Name", sortable: true },
        { key: "email", label: "Email", sortable: true },
        { key: "phone", label: "Contact Number", sortable: true },
        { key: "actions", label: "Action", sortable: false },
      ],
      userData: {
        leads: [],
      },
      pagination: {
        page: 1,
        per_page: 10,
        total: null,
      },
      roles: [],
      leads: [],
      healthcoach: null,
      list: [],
      refFormObserver,
      getValidationState,
      resetForm,
      required,
    };
  },
  props: {
    id: {
      type: [Number, String, null],
      default: null,
    },
    role: {
      type: [String, null],
      default: null,
    },
  },
  created() {
    this.getList();
    this.getColumns();
  },
  methods: {
    getColumns() {
      if (this.$store.state.auth.user.role_id != 1) {
        this.tableColumns = [
          { key: "id", label: "Id", sortable: true },
          { key: "name", label: "Full Name", sortable: true },
          { key: "gender", label: "Gender", sortable: true },
          { key: "email", label: "Email", sortable: true },
          { key: "phone", label: "Contact Number", sortable: true },
        ];
      }
    },
    async getList() {
      try {
        this.pagination.id = this.id;
        this.pagination.role = this.role;
        if (this.$route.name == "cis") {
          this.pagination.cis = 1;
        }
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
    handlePaginationChange(val) {
      this.pagination.page = val;
      this.getList();
    },
    viewAssignModal(healthcoach) {
      this.healthcoach = healthcoach;
      this.$refs["assign"].show();
    },

    viewLeadModal(healthcoach) {
      this.healthcoach = healthcoach;
      this.$refs["view-leads"].show();
    },

    async getRoles() {
      try {
        const { data } = await axios.get("/adminRoles/list");
        console.log(data);
        this.roles = data.roles;
      } catch (error) {
        console.log(error);
      }
    },
    async getLeads() {
      try {
        this.leads = this.userData.leads = [];
        const { data } = await axios.get("/lead/list", {});
        this.leads = data.leads;
      } catch (error) {
        console.log(error);
      }
    },

    handleOk(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.$refs.refFormObserver.handleSubmit(this.onAssign);
    },

    resetData() {
      this.userData = {
        leads: [],
      };
    },

    resetUsers() {
      this.list = [];
    },

    async onAssign() {
      try {
        this.userData.healthcoach = this.healthcoach.id;
        const { data } = await axios.post("/healthcoach/leadassignment", this.userData);
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "BellIcon",
            variant: data.success ? "success" : "danger",
          },
        });
        this.$nextTick(() => {
          this.$refs["assign"].hide();
        });
      } catch (err) {
        console.log(err);
      }
    },

    async getAssignedLeads() {
      try {
        let id = this.healthcoach.id;
        const { data } = await axios.get(`healthcoach/${id}/leads`, {
          params: this.pagination,
        });
        this.list = data.leads;
        this.totalRows = data.totalRows;
      } catch (err) {
        console.log(err);
      }
    },

    async handleRemoveLead(type, id) {
      try {
        const { data } = await axios.delete(`/healthcoach/removeLead/${id}`);
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "BellIcon",
            variant: data.success ? "success" : "danger",
          },
        });
        this.getAssignedLeads();
      } catch (error) {
        this.$toast({
          component: ToastificationContent,
          props: {
            title: error.response.data.message,
            icon: "BellIcon",
            variant: "danger",
          },
        });
      }
    },
    async deleteHC(hc) {
      if (confirm("Do you really want to delete?")) {
        try {
          let data;
          data = await axios.delete(`/healthcoach/${hc.id}`);
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

#assignUser___BV_modal_body_ {
  min-height: 200px !important;
  max-height: 300px !important;
  overflow-y: auto;
}
</style>
