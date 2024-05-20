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

        <template #cell(name)="data">
          <div class="text-nowrap">
            <p>{{ data.item.name }}<br/>({{ data.item.company_name }})</p>
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

            <b-dropdown-item v-if="$store.getters.user.role_id == 10 || $store.getters.user.role_id == 1" @click="renderUpgradedConsultants(data.item.id)">
              <feather-icon icon="UserPlusIcon" />
              <span class="align-middle ml-50">Assign League Counsltants</span>
            </b-dropdown-item>

            <b-dropdown-item v-if="$store.getters.user.role_id == 10 || $store.getters.user.role_id == 1"
              @click="viewAssigndhModal(data.item)">
              <feather-icon icon="UsersIcon" />
              <span class="align-middle ml-50">Assign Diagnostic Partner</span>
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

    <b-modal id="assigndh" ref="assigndh" ok-only ok-title="Close"
      @show="getDiagnosticpartners" @hidden="resetDhData" centered size="lg" title="Diagnistic Partner Assignment"
      no-close-on-backdrop hide-footer>
      <validation-observer #default="{ handleSubmit }" ref="observer">
        <form ref="assigndhform" @submit.prevent="handleSubmit(onDhAssign)">
          <b-row>
            <!--
            <b-col cols="10">
              <b-form-group label="Search by address / pincode" label-for="searchdh">
                <b-form-input id="searchdh" v-model="dhData.search" />
              </b-form-group>
            </b-col>
            <b-col cols="2">
              <b-button variant="primary" class="mb-1 mb-sm-0 mr-0 mr-sm-1"
                :block="$store.getters['app/currentBreakPoint'] === 'xs'" type="button" @click="getDiagnosticpartners()">
                Search
              </b-button>
            </b-col>
            -->
            <b-col cols="12">
              <validation-provider #default="validationContext" name="diagnosticpartner" rules="required">
                <b-form-group label="Select Diagnistic Partner" label-for="diagnosticpartner"
                  :state="getValidationState(validationContext)">
                  <v-select v-model="dhData.dh_id" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="diagnosticpartners" :reduce="(val) => val.id" :clearable="false"
                    input-id="diagnosticpartners" />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
          </b-row>
          <b-button variant="primary" class="mb-1 mb-sm-0 mr-0 mr-sm-1"
            :block="$store.getters['app/currentBreakPoint'] === 'xs'" type="submit">
            Assign
          </b-button>
        </form>
      </validation-observer>
      <!--
      <b-table style="min-height: 250px;margin-top:10px" ref="refRolesTable" class="position-relative" :items="dhlist"
        responsive :fields="dhcolumns" :totalRows="totalRows" primaryKey="index" :sort-by.sync="sortBy" show-empty
        empty-text="No matching records found" :sort-desc.sync="isSortDirDesc" :sticky-header="true">
        <template #cell(index)="data">
          {{ data.index + 1 }}
        </template>
        <template #cell(name)="data">
          <span class="text-primary">{{ data.item.name.toUpperCase() }}</span>
        </template>
        <template #cell(actions)="data">
          <a @click="handleRemoveUser('dh', data.item.id)" style="cursor: pointer" title="remove">
            <feather-icon icon="TrashIcon" />
          </a>
        </template>
      </b-table>
      -->
    </b-modal>

    <b-modal size="xl" id="show-league-consultant-modal" ref="show-league-consultant-modal" title="Assign League Consultant" ok-only no-close-on-backdrop ok-title="Assign Consultant" @ok="doAssignConsultant">
      <table class="table">
        <thead>
          <tr>
            <th></th>
            <th>Consultant Name</th>
            <th>Role Type</th>
            <th>Sub Role</th>
            <th>Specialization</th>
            <th>Location</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(consultant, index) in upgraded_consultants">
            <td>
              <input type="checkbox" v-model="selected_consultants" :value="consultant.id">
            </td>
            <td>{{ consultant.name }}</td>
            <td>{{ consultant.specialization }}</td>
            <td>{{ consultant.role }}</td>
            <td>{{ consultant.specialized_in }}</td>
            <td>{{ consultant.location }}</td>
          </tr>
        </tbody>
      </table>
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
  BBadge,
  BDropdown,
  BDropdownItem,
  BPagination,
  BFormGroup,
  VBModal,
  BModal,
  BFormInvalidFeedback,
  BFormCheckbox,
} from "bootstrap-vue";

import { ValidationProvider, ValidationObserver } from "vee-validate";

import vSelect from "vue-select";

import formValidation from "@core/comp-functions/forms/form-validation";

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

import { required } from "@validations";

import moment from 'moment';

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
    VBModal,
    BModal,
    ValidationProvider,
    ValidationObserver,
    BFormInvalidFeedback,
    BFormCheckbox,
  },
  data() {
    const { refFormObserver, getValidationState, resetForm, resetObserver, observer } = formValidation(
      this.resetData,
      this.resetHcData,
      this.resetDhData,
      this.resetInchargeData
    );
    return {
      items: [],
      perPage: 10,
      perPageOptions: [10, 25, 50, 100],
      totalRows: "",
      search: "",
      selected_consultants: [],
      selected_aliance_id: false,
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
        // { key: "email", label: "Email", sortable: true },
        { key: "phone", label: "Phone", sortable: true },
        { key: "coordinators", label: "Coordinators", sortable: true },
        { key: "diagnostichead", label: "Diagnostic Partner", sortable: true },
        { key: "group", label: "Group", sortable: true, filterByFormatted: true },
        { key: "type", label: "Sub Group", sortable: true },
        { key: "actions" },
      ],
      upgraded_consultants: [],
      diagnosticpartners: [],
      dhlist: [],
      dhData: {},
      dhcolumns: [
        { key: "index", label: "Sr. No.", sortable: true },
        { key: "name", label: "Diagnostic Partner name", sortable: true },
        { key: "actions", label: "Action", sortable: false },
      ],
      getValidationState,
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
          { key: "actions" },
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
    async renderUpgradedConsultants(aliance_id) {
      const { data } = await axios.get("/consultants", {
        params: this.pagination,
      });

      this.selected_aliance_id = aliance_id;
      this.upgraded_consultants = data.users;
      this.$refs["show-league-consultant-modal"].show();      
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
    async doAssignConsultant() {
      try {
        const { data } = await axios.post(`/consultants/assign/${this.selected_aliance_id}`, 
          { 
            consultant_ids : this.selected_consultants 
          }
        );
        
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
            title: "Error while assign to AP",
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
    async getDiagnosticpartners() {
      try {
        this.diagnosticpartners = [];
        var search = (this.dhData.search) ? this.dhData.search : '';
        const { data } = await axios.get("/dh/list", {
          params: { alliance_id: this.alliance.id, search: search },
        });
        this.diagnosticpartners = data.users;
        this.getAssignedDiagnosticPartners();
      } catch (error) {
        console.log(error);
      }
    },
    async getAssignedDiagnosticPartners() {
      try {
        const { data } = await axios.get("/client/dhlist/" + this.alliance.id, {
          params: {},
        });
        this.dhlist = data.diagnosticpartners;
      } catch (error) {
        console.log(error);
      }
    },
    viewAssigndhModal(alliance) {
      this.alliance = alliance;
      this.$refs["assigndh"].show();
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
    resetDhData() {
      this.dhData = {
        dh_id: "",
      };
    },
    async onDhAssign() {
      try {
        this.dhData.alliance_partner = this.alliance.id;
        const { data } = await axios.post(`/coordinators/alliancepartners/${this.alliance.id}`, this.dhData);
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "BellIcon",
            variant: data.success ? "success" : "danger",
          },
        });

        this.$nextTick(() => {
          this.$refs["assigndh"].hide();
          this.getAssignedDiagnosticPartners();
          this.getDiagnosticpartners();
          this.resetDhData();
          this.$refs.assigndhform.reset()
          this.$refs.assigndhform.resetValidation();
          this.resetObserver();
          this.$refs.observer.reset();
        });
        this.getList();
      } catch (err) {
        console.log(err);
      }
    }
  },
};
</script>
<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";
</style>
