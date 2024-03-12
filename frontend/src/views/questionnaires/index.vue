<template>
  <div>
    <b-card>
      <b-row class="mb-2">
        <!-- Per Page -->
        <b-col cols="12" md="6" class="d-flex align-items-center justify-content-start mb-1 mb-md-0">
          <label>Show</label>
          <v-select v-model="qpagination.per_page" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="[10, 25, 50, 100, 1000]" :clearable="false" class="per-page-selector d-inline-block mx-50" />
          <label>entries</label>
        </b-col>

        <!-- Search -->
        <b-col cols="12" md="6">
          <div v-if="!id" class="d-flex align-items-center justify-content-end" v-permission="['create-questionnaire']">
            <router-link :to="{ name: 'create-questionnaires' }">
              <b-button variant="primary">
                <span variant="primary" class="text-nowrap">Create Questionnaire</span>
              </b-button>
            </router-link>
          </div>
        </b-col>
      </b-row>

      <b-table style="min-height: 250px" ref="refRolesTable" class="position-relative" :items="questionnaires" responsive
        :fields="qcolumns" :totalRows="qpagination.total" primaryKey="index" :sort-by.sync="sortBy" show-empty
        empty-text="No matching records found" :sort-desc.sync="isSortDirDesc">
        <!-- Column: Actions -->
        <template #cell(index)="data">
          {{ data.index + 1 }}
        </template>
        <template #cell(name)="data">
          <span class="text-primary">{{ data.item.name.toUpperCase() }}</span>
        </template>
        <template #cell(status)="data">
          <span class="text-success" v-if="data.item.result"> COMPLETED</span>
          <span class="text-primary" v-else> NOT-COMPLETE</span>
        </template>
        <!-- Column: Actions -->
        <template #cell(actions)="data">
          <div>
            <div v-if="id">
              <router-link v-if="data.item.result" :to="{
                name: 'view-questionnaire',
                params: { id: data.item.questionnaire_user_id },
              }">
                <b-button variant="info">
                  <span variant="primary" class="text-nowrap">View Questionnaire</span>
                </b-button>
              </router-link>
            </div>
            <div v-else>
              <b-dropdown variant="link" no-caret v-permission="['edit-questionnaire']"
                v-if="$store.getters.user.role_id != 3">
                <template #button-content>
                  <feather-icon icon="MoreVerticalIcon" size="16" class="align-middle text-body" />
                </template>
                <b-dropdown-item :to="{ name: 'preview-questionnaire', params: { id: data.item.id } }"
                  v-permission="['create-questionnaire']">
                  <feather-icon icon="EyeIcon" />
                  <span class="align-middle ml-50">PreView</span>
                </b-dropdown-item>
                <b-dropdown-item :to="{ name: 'update-questionnaires', params: { id: data.item.id } }"
                  v-permission="['edit-questionnaire']">
                  <feather-icon icon="EditIcon" />
                  <span class="align-middle ml-50">Edit</span>
                </b-dropdown-item>
                <b-dropdown-item v-if="!user_id" @click="viewAssignModal(data.item)"
                  v-permission="['edit-questionnaire']">
                  <feather-icon icon="UsersIcon" />
                  <span class="align-middle ml-50">Assign</span>
                </b-dropdown-item>
                <b-dropdown-item @click="viewUserModal(data.item)" v-permission="['edit-questionnaire']">
                  <feather-icon icon="EyeIcon" />
                  <span class="align-middle ml-50">View Users</span>
                </b-dropdown-item>
                <b-dropdown-item @click="handleDeleteQuestionnaire(data.item.id)" v-permission="['delete-questionnaire']"
                  v-if="$store.getters.user.role.id == 1">
                  <feather-icon icon="TrashIcon" />
                  <span class="align-middle ml-50">Delete</span>
                </b-dropdown-item>
              </b-dropdown>
              <div v-else>
                <router-link v-if="!data.item.result" :to="{ name: 'fill-questionnaire', params: { id: data.item.id } }">
                  <b-button variant="primary">
                    <span variant="primary" class="text-nowrap">Start</span>
                  </b-button>
                </router-link>
                <router-link v-else :to="{
                  name: 'view-questionnaire',
                  params: { id: data.item.questionnaire_user_id },
                }">
                  <b-button variant="info">
                    <span variant="primary" class="text-nowrap">View Questionnaire</span>
                  </b-button>
                </router-link>
              </div>
            </div>
          </div>

        </template>
      </b-table>
      <b-pagination v-if="qpagination.total" v-model="qpagination.page" :page.sync="qpagination.page"
        :total-rows="qpagination.total" :index="1" :per-page="qpagination.per_page" align="right"
        @change="handlePaginationChange">
      </b-pagination>
      <b-modal v-permission="['edit-questionnaires']" id="assign" ref="assign" ok-only ok-title="Assign" @show="resetData"
        @hidden="resetData" @ok="handleOk" centered size="lg" title="Questionnaire Assignment" no-close-on-backdrop>
        <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
          <form ref="form" @submit.prevent="handleSubmit(onAssign)">
            <b-form-group label="Assign To">
              <b-row>
                <b-col cols="12" md="2" v-if="$store.getters.user.role.is_consultant != 1">
                  <b-form-radio v-model="userData.assignedto" name="assignedto" value="consultant"
                    @change="handleAssignTo">Incharge / Consultant</b-form-radio>
                </b-col>
                <b-col cols="12" md="2">
                  <b-form-radio v-model="userData.assignedto" name="assignedto" value="clients" @change="handleAssignTo">
                    Clients
                  </b-form-radio>
                </b-col>
              </b-row>
            </b-form-group>
            <b-row>
              <b-col cols="12" md="6">
                <validation-provider #default="validationContext" name="clients" rules="required">
                  <b-form-group label="Select Users" label-for="clients" :state="getValidationState(validationContext)">
                    <v-select v-model="userData.clients" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                      :options="userlist" :reduce="(val) => val.id" :clearable="false" multiple input-id="clients" />
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
      <b-modal v-permission="['edit-questionnaires']" id="view-users" hide-footer centered size="lg"
        @show="getAssignedUsers" @hidden="resetUsers" title="Assigned Users" ref="view-users" no-close-on-backdrop>
        <b-table style="min-height: 250px" ref="refRolesTable" class="position-relative" :items="list" responsive
          :fields="columns" :totalRows="totalRows" primaryKey="index" :sort-by.sync="sortBy" show-empty
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
            <a @click="handleRemoveUser('questionnaire', data.item.questionnaire_user_id)"
              v-permission="['edit-questionnaire']" style="cursor: pointer" title="remove">
              <feather-icon icon="TrashIcon" />
            </a>
            <router-link v-if="data.item.result" v-permission="['edit-questionnaire']" :to="{
              name: 'view-questionnaire',
              params: { id: data.item.questionnaire_user_id },
            }" title="view result">
              <feather-icon icon="EyeIcon" />
            </router-link>
          </template>
        </b-table>
      </b-modal>
    </b-card>
  </div>
</template>
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
  VBModal,
  BModal,
  BFormInvalidFeedback,
  BFormInput,
  BFormGroup,
  BOverlay,
  BFormCheckbox,
  BFormDatepicker,
  BFormRadioGroup,
  BFormRadio,
} from "bootstrap-vue";

import { ValidationProvider, ValidationObserver } from "vee-validate";

import formValidation from "@core/comp-functions/forms/form-validation";
import { required } from "@validations";

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

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
    VBModal,
    BModal,
    ValidationProvider,
    ValidationObserver,
    BFormInvalidFeedback,
    BFormInput,
    BFormGroup,
    BOverlay,
    BFormCheckbox,
    BFormDatepicker,
    BFormRadioGroup,
    BFormRadio
  },
  props: {
    id: {
      type: [Number, String],
      default: null,
    },
  },
  data() {
    return {
      totalRows: 0,
      qpagination: {
        page: 1,
        per_page: 10,
        total: null,
      },
      questionnaire: null,
      required,
      users: [],
      readonly: false,
      errors: [],
      list: [],
      userlist: [],
      consultants: [],
      clients: [],
    };
  },
  directives: {
    Ripple,
  },
  setup() {
    const questionnaires = null;
    const loading = true;
    const sortBy = "1";
    const isSortDirDesc = false;
    const qcolumns = [
      { key: "index", label: "Sr. No.", sortable: true },
      { key: "name", label: "Name", sortable: true },
      { key: "description", label: "Description", sortable: true },
      { key: "status", label: "Status", sortable: false },
      { key: "actions" },
    ];
    const columns = [
      { key: "index", label: "Sr. No.", sortable: true },
      { key: "name", label: "Name", sortable: true },
      { key: "email", label: "Email", sortable: true },
      { key: "phone", label: "Contact Number", sortable: true },
      { key: "actions", label: "Action", sortable: false },
    ];
    const userData = {
      assignedto: "",
      clients: [],
      partners: [],
    };
    const resetUserData = function () {
      userData.value = {
        assignedto: "",
        clients: [],
        partners: [],
      };
    };
    const { refFormObserver, getValidationState, resetForm } = formValidation(
      resetUserData
    );

    return {
      questionnaires,

      loading,
      sortBy,
      isSortDirDesc,
      qcolumns,
      columns,
      refFormObserver,
      getValidationState,
      resetForm,
    };
  },
  computed: {
    user_id: function () {
      return this.id ? this.id : "";
    },
  },

  watch: {
    "pagination.per_page": function (val) {
      this.handleFilterChange(val);
    },
  },
  created() {
    this.getList();
    this.getPartners();
    this.qcolumnsupdate();
    this.getConsultants();
    this.getClients();
  },
  methods: {
    qcolumnsupdate() {
      if (this.$store.getters.user.role.is_consultant == 1 || this.$store.getters.user.role.id == 1 || this.$store.getters.user.role.id == 11) {
        this.qcolumns = [
          { key: "name", label: "Name", sortable: true },
          { key: "description", label: "Description", sortable: true },
          { key: "type", label: "Type", sortable: true },
          { key: "actions" },
        ];
      }
      if (this.id && this.$store.getters.user.role.is_consultant == 1) {
        this.qcolumns = [
          { key: "name", label: "Name", sortable: true },
          { key: "description", label: "Description", sortable: true },
          { key: "status", label: "Status", sortable: false },
          { key: "actions" },
        ];
      }
    },
    async getList() {
      try {
        this.loading = true;
        this.qpagination.id = this.user_id;
        const { data } = await axios.get("/questionnaire", {
          params: this.qpagination,
        });
        this.questionnaires = data.questionnaires.data;
        this.qpagination.per_page = data.questionnaires.per_page;
        this.qpagination.total = data.questionnaires.total;
        this.qpagination.page = data.questionnaires.page;
      } catch (err) {
        console.log(err);
      }
      this.loading = false;
    },

    handlePaginationChange(val) {
      this.gpagination.page = val;
      this.getList();
    },
    handleFilterChange(val) {
      this.pagination.per_page = val;
      this.getList();
    },
    async getPartners() {
      try {
        const { data } = await axios.get("/alliance-partner/list");
        this.partners = data.partners;
      } catch (err) {
        console.log(err);
      }
    },
    handleAssignTo() {
      this.userlist = [];
      this.userData.clients = [];
      if (this.userData.assignedto == "consultant") {
        this.userlist = this.consultants;
      } else if (this.userData.assignedto == "clients") {
        this.userlist = this.clients;
      }
      console.log(this.userlist);
    },
    async getConsultants() {
      try {
        this.consultants = [];
        var params = {};
        const { data } = await axios.get("/consultants/list", {
          params: params,
        });
        this.consultants = data.users;
      } catch (error) {
        console.log(error);
      }
    },
    async getClients() {
      try {
        this.clients = [];
        if (this.$store.getters.user.role.is_consultant == 1) {
          var params = { consultant: [this.$store.getters.user.id] };
        }
        if (this.$store.getters.user.role_id == 2) {
          var params = { partners: [this.$store.getters.user.id] };
        }
        const { data } = await axios.get("/client/list", {
          params: params,
        });
        this.clients = data.clients;
      } catch (error) {
        console.log(error);
      }
    },
    async getAssignedUsers() {
      try {
        let id = this.questionnaire.id;
        const { data } = await axios.get(`/questionnaire/${id}/users`, {
          params: this.pagination,
        });
        this.list = data.users;
        this.totalRows = data.totalRows;
      } catch (err) {
        console.log(err);
      }
    },

    viewAssignModal(questionnaire) {
      this.questionnaire = questionnaire;
      this.$refs["assign"].show();
    },
    viewUserModal(questionnaire) {
      this.questionnaire = questionnaire;
      this.$refs["view-users"].show();
    },

    async handleDeleteQuestionnaire(id) {
      try {
        const data = await axios.delete(`/questionnaire/${id}`);
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.data.message,
            icon: "BellIcon",
            variant: data.data.success ? "success" : "danger",
          },
        });
        this.getList();
      } catch (err) {
        console.log(err);
      }
    },

    resetFormCard() {
      this.errors = [];
    },

    async onAssign() {
      try {
        this.userData.questionnaire = this.questionnaire.id;
        const { data } = await axios.post("/questionnaire/assignment", this.userData);
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
    handleOk(bvModalEvt) {
      bvModalEvt.preventDefault();

      this.$refs.refFormObserver.handleSubmit(this.onAssign);
    },

    resetUsers() {
      this.list = [];
      this.pagination = {
        page: 1,
        per_page: 10,
        total: null,
      };
    },
    resetData() {
      this.userlist = [];
      this.userData = {
        clients: [],
        partners: [],
      };
    },
    async handleRemoveUser(type, id) {
      try {
        const { data } = await axios.delete(`/assignment/${id}/user?type=${type}`);
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "BellIcon",
            variant: data.success ? "success" : "danger",
          },
        });
        this.getAssignedUsers();
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
