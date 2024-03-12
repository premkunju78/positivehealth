<template>
  <div style="min-height: 300px">
    <b-overlay :show="$store.state.app.shallShowOverlay" no-wrap opacity="0.5" blur="1px"></b-overlay>
    <b-card>
      <b-row class="mb-2">
        <!-- Per Page -->
        <b-col cols="12" md="6" class="d-flex align-items-center justify-content-start mb-1 mb-md-0">
          <label>Show</label>
          <v-select v-model="qpagination.per_page" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="[10, 25, 50, 100, 1000]" :clearable="false" class="per-page-selector d-inline-block mx-50" />
          <label>entries</label>
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
          <b-dropdown variant="link" no-caret v-permission="['view-questionnaire']"
            v-if="$store.getters.user.role_id != 3">
            <template #button-content>
              <feather-icon icon="MoreVerticalIcon" size="16" class="align-middle text-body" />
            </template>
            <b-dropdown-item target="_blank" :to="{ name: 'preview-htmlquestionnaire', params: { id: data.item.id } }"
              v-permission="['view-questionnaire']">
              <feather-icon icon="EyeIcon" />
              <span class="align-middle ml-50">PreView</span>
            </b-dropdown-item>
            <b-dropdown-item v-if="data.item.result" :to="{ name: 'view-userhtmlquestionnaires', params: { id: data.item.id,userid: id } }"
              v-permission="['view-questionnaire']">
              <feather-icon icon="EyeIcon" />
              <span class="align-middle ml-50">View Result</span>
            </b-dropdown-item>
          </b-dropdown>
          <div v-else>
            <router-link v-if="!data.item.result" target="_blank"
              :to="'/view/htmlquestionnaire/' + data.item.id + '/' + $store.getters.user.role_id">
              <b-button variant="primary">
                <span variant="primary" class="text-nowrap">Start</span>
              </b-button>
            </router-link>
            <router-link v-else :to="{
              name: 'view-htmlquestionnaires',
              params: { id: data.item.id },
            }">
              <b-button variant="info">
                <span variant="primary" class="text-nowrap">View Questionnaire</span>
              </b-button>
            </router-link>
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
            <b-row>
              <b-col cols="12" md="6">
                <validation-provider #default="validationContext" name="alliance-partners" rules="required">
                  <b-form-group label="Select Alliance Partners" label-for="partners"
                    :state="getValidationState(validationContext)">
                    <v-select v-model="userData.partners" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                      :options="partners" :reduce="(val) => val.id" :clearable="false" multiple input-id="partners"
                      @input="getClients" />
                    <b-form-invalid-feedback :state="getValidationState(validationContext)">
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>
              <b-col cols="12" md="6">
                <validation-provider #default="validationContext" name="clients" rules="required">
                  <b-form-group label="Select Clients" label-for="clients" :state="getValidationState(validationContext)">
                    <v-select v-model="userData.clients" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                      :options="clients" :reduce="(val) => val.id" :clearable="false" multiple input-id="clients" />
                    <b-form-invalid-feedback :state="getValidationState(validationContext)">
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>
              <!-- <b-col cols="12" md="6">
                                      <validation-provider
                                          #default="validationContext"
                                          name="Expiry Date"
                                          rules="required"
                                      >
                                          <b-form-group
                                              label="Expiry Date"
                                              label-for="expiry_date"
                                              :state="getValidationState(validationContext)"
                                              >
                                              <b-form-datepicker id="expiry_date" :min="new Date()" v-model="userData.expiry_date"></b-form-datepicker>
                                              <b-form-invalid-feedback :state="getValidationState(validationContext)">
                                                  {{ validationContext.errors[0] }}
                                              </b-form-invalid-feedback>
                                          </b-form-group>
                                      </validation-provider>
                                  </b-col> -->
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
              v-permission="['edit-questionnaire']" style="cursor: pointer" title="remove user">
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
      { key: "name", label: "Questionnaire Name", sortable: true },
      { key: "description", label: "Questionnaire Description", sortable: true },
      { key: "status", label: "Questionnaire Status", sortable: false },
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
      clients: [],
      partners: [],
    };
    const resetUserData = function () {
      userData.value = {
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
  },
  methods: {
    async getList() {
      try {
        this.loading = true;
        this.qpagination.id = this.user_id;
        const { data } = await axios.get("/htmlquestionnaire", {
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
    async getClients() {
      if (this.userData.partners.length) {
        try {
          const { data } = await axios.get("/client/list", {
            params: {
              partners: this.userData.partners,
              questionnaire: this.questionnaire.id,
            },
          });
          this.clients = data.clients;
        } catch (err) {
          console.log(err);
        }
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
