<template>
  <div style="min-height: 300px">
    <b-overlay :show="$store.state.app.shallShowOverlay" no-wrap opacity="0.5" blur="1px"></b-overlay>
    <b-card :title="type" v-permission="['create-leads']" v-if="createLead">
      <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
        <form ref="form" @submit.prevent="handleSubmit(onSubmit)">
          <b-row>
            <b-col cols="12" md="4">
              <validation-provider #default="validationContext" name="Name" rules="required">
                <b-form-group label="Name" label-for="name">
                  <b-form-input id="name" :state="getValidationState(validationContext)" v-model="leadData.name"
                    :readonly="readonly" :class="errors.name ? 'is-invalid' : ''"
                    :area-invalid="errors.name ? true : false" />
                  <b-form-invalid-feedback v-if="errors.name">
                    {{ errors.name[0] }}
                  </b-form-invalid-feedback>
                  <b-form-invalid-feedback v-else>
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col cols="12" md="4">
              <validation-provider #default="validationContext" name="Email" rules="required">
                <b-form-group label="Email" label-for="email">
                  <b-form-input id="email" v-model="leadData.email" :state="getValidationState(validationContext)"
                    :readonly="readonly" :class="errors.email ? 'is-invalid' : ''"
                    :area-invalid="errors.email ? true : false" />
                  <b-form-invalid-feedback v-if="errors.email">
                    {{ errors.email[0] }}
                  </b-form-invalid-feedback>
                  <b-form-invalid-feedback>
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col cols="12" md="4">
              <validation-provider #default="validationContext" name="Phone Number" rules="required">
                <b-form-group label="Phone Number" label-for="phone">
                  <b-form-input id="phone" type="number" v-model="leadData.phone"
                    :state="getValidationState(validationContext)" :readonly="readonly"
                    :class="errors.phone ? 'is-invalid' : ''" :area-invalid="errors.phone ? true : false" />
                  <b-form-invalid-feedback v-if="errors.phone">
                    {{ errors.phone[0] }}
                  </b-form-invalid-feedback>
                  <b-form-invalid-feedback>
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col cols="12" md="4">
              <validation-provider #default="validationContext" name="DOB" rules="required">
                <b-form-group label="DOB" label-for="dob">
                  <flat-pickr v-model="leadData.dob" :state="getValidationState(validationContext)" class="form-control"
                    :class="errors.dob ? 'form-control is-invalid' : 'form-control'"
                    :area-invalid="errors.dob ? true : false" @input="dobChanged"
                    :config="{ enableTime: false, dateFormat: 'Y-m-d' }" />
                  <b-form-invalid-feedback v-if="errors.dob">
                    {{ errors.dob[0] }}
                  </b-form-invalid-feedback>
                  <b-form-invalid-feedback>
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col cols="12" md="4">
              <validation-provider #default="validationContext" name="Gender" rules="required">
                <b-form-group label="Gender" label-for="gender">
                  <b-form-radio-group v-model="leadData.gender" :options="genders" class="demo-inline-spacing"
                    name="gender" id="gender" :class="errors.gender ? 'is-invalid' : ''"
                    :area-invalid="errors.gender ? true : false" />
                  <b-form-invalid-feedback v-if="errors.dob">
                    {{ errors.dob[0] }}
                  </b-form-invalid-feedback>
                  <b-form-invalid-feedback>
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col cols="12" md="4">
              <validation-provider #default="validationContext" name="Concern" rules="required">
                <b-form-group label="Concern" label-for="concern" :state="getValidationState(validationContext)">
                  <b-form-textarea rows="3" id="concern" v-model="leadData.concern"
                    :class="errors.concern ? 'is-invalid' : ''" :area-invalid="errors.concern ? true : false"
                    :state="getValidationState(validationContext)"></b-form-textarea>
                  <b-form-invalid-feedback v-if="errors.concern">
                    {{ errors.instructions[0] }}
                  </b-form-invalid-feedback>
                  <b-form-invalid-feedback>
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
          </b-row>
          <b-row style="margin-top: 2%">
            <b-button variant="primary" :block="$store.getters['app/currentBreakPoint'] === 'xs'" type="submit"
              class="mr-2" v-permission="['create-leads']">
              Save Changes
            </b-button>
            <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" type="button" @click="resetFormCard"
              variant="outline-secondary">
              Cancel
            </b-button>
          </b-row>
        </form>
      </validation-observer>
    </b-card>
    <b-card>
      <b-row class="mb-2">
        <!-- Per Page -->
        <b-col cols="12" md="6" class="d-flex align-items-center justify-content-start mb-1 mb-md-0">
          <label>Show</label>
          <v-select v-model="pagination.per_page" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="[10, 25, 50, 100, 1000]" :clearable="false" class="per-page-selector d-inline-block mx-50" />
          <label>entries</label>
        </b-col>

        <!-- Search -->
        <b-col cols="12" md="6"> </b-col>
      </b-row>
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
          <span v-if="data.item.status == 'Call back'">(Date: {{ moment(data.item.callback_date).format('DD-MM-YYYY') }}
            )</span>
          <span v-if="data.item.status == 'Not interested'"><a class="btn btn-success btn-sm"
              @click="viewRemarkModel(data.item)">Remark</a></span>
        </template>
        <template #cell(call)="data">
          <b-button variant="success" class="btn btn-primary ml-2" @click="callNow(data.item)">
            <span class="text-nowrap">Call Now</span>
          </b-button>
        </template>
        <template #cell(actions)="data">
          <b-dropdown variant="link" no-caret v-permission="['view-leads']">
            <template #button-content>
              <feather-icon icon="MoreVerticalIcon" size="16" class="align-middle text-body" />
            </template>
            <b-dropdown-item @click="editLead(data.item)" v-permission="['edit-leads']">
              <feather-icon icon="EditIcon" />
              <span class="align-middle ml-50">Edit</span>
            </b-dropdown-item>
            <b-dropdown-item v-if="!data.item.hc_assigned &&
              ($store.state.auth.user.role_id == '10' ||
                $store.state.auth.user.role_id == '11')
              " @click="viewAssignModal(data.item)" v-permission="['view-leads']">
              <feather-icon icon="UserIcon" />
              <span class="align-middle ml-50">Assign</span>
            </b-dropdown-item>
            <b-dropdown-item v-if="!data.item.payment_link &&
              ($store.state.auth.user.role_id == '10' ||
                $store.state.auth.user.role_id == '11')
              " @click="ViewPaymentModal(data.item)" v-permission="['view-leads']">
              <feather-icon icon="DollarSignIcon" />
              <span class="align-middle ml-50">Payment</span>
            </b-dropdown-item>
            <b-dropdown-item @click="viewStatusModal(data.item)" v-permission="['view-leads']" v-if="$store.state.auth.user.role_id == '10' ||
              $store.state.auth.user.role_id == '11'
              ">
              <feather-icon icon="Edit2Icon" />
              <span class="align-middle ml-50">Update Status</span>
            </b-dropdown-item>
            <b-dropdown-item @click="viewQueryModal(data.item)" v-permission="['view-leads']" v-if="$store.state.auth.user.role_id == '10' ||
              $store.state.auth.user.role_id == '11'
              ">
              <feather-icon icon="HelpCircleIcon" />
              <span class="align-middle ml-50">Update Queries</span>
            </b-dropdown-item>
            <b-dropdown-item @click="handleDeleteLead(data.item.id)" v-permission="['view-leads']" v-if="$store.state.auth.user.role_id == '1' ||
              $store.state.auth.user.role_id == '10' ||
              $store.state.auth.user.role_id == '11'
              ">
              <feather-icon icon="TrashIcon" />
              <span class="align-middle ml-50">Delete</span>
            </b-dropdown-item>
          </b-dropdown>
        </template>
      </b-table>
      <b-pagination v-if="pagination.total" v-model="pagination.page" :page.sync="pagination.page"
        :total-rows="pagination.total" :index="1" :per-page="pagination.per_page" aria-controls="package-row"
        align="right" @change="handlePaginationChange">
      </b-pagination>
    </b-card>

    <b-modal v-permission="['view-clients']" id="assign" ref="assign" ok-only ok-title="Assign" @show="getHealthcoaches"
      @hidden="resetData" @ok="handleOk" centered size="lg" title="Healthcoash / CIS Assignment" no-close-on-backdrop>
      <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
        <form ref="form" @submit.prevent="handleSubmit(onAssign)">
          <b-form-group label="Assign To">
            <b-row>
              <b-col cols="12" md="6">
                <b-form-radio v-model="userData.assignedto" name="assignedto" value="healthcoach"
                  @change="handleAssignTo">
                  Health Coach</b-form-radio>
              </b-col>
              <b-col cols="12" md="6">
                <b-form-radio v-model="userData.assignedto" name="assignedto" value="cisf" @change="handleAssignTo">
                  CIS Member</b-form-radio>
              </b-col>
            </b-row>
            <b-form-invalid-feedback v-if="errors.assignedto">
              {{ errors.assignedto[0] }}
            </b-form-invalid-feedback>
          </b-form-group>
          <b-row>
            <b-col cols="12" md="12">
              <validation-provider #default="validationContext" name="healthcoach" rules="required">
                <b-form-group label="" label-for="healthcoach" :state="getValidationState(validationContext)">
                  <v-select v-model="userData.healthcoach_id" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="userlist" :reduce="(val) => val.id" :clearable="false" input-id="healthcoaches" />
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

    <b-modal :ok-disabled="paymentLoading" v-permission="['view-clients']" id="payment" ref="payment" ok-only
      ok-title="Generate link" @show="getHealthcoaches" @hidden="resetData" @ok="handleOkProgram" centered size="lg"
      title="Payment" no-close-on-backdrop>
      <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
        <form ref="form" @submit.prevent="handleSubmit(onPayment)">
          <b-row>
            <b-col cols="12" md="6">
              <validation-provider #default="validationContext" name="program" rules="required">
                <b-form-group label="Select Program" label-for="program" :state="getValidationState(validationContext)">
                  <v-select v-model="userData.program_id" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="programs" :reduce="(val) => val.id" :clearable="false" input-id="programs"
                    @input="getPlans" />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col cols="12" md="6">
              <validation-provider #default="validationContext" name="plan" rules="required">
                <b-form-group label="Select the plan" label-for="Plan" :state="getValidationState(validationContext)">
                  <v-select v-model="userData.plan" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="plans"
                    :reduce="(val) => val.id" :clearable="false" input-id="plans" @input="getPrice" />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col cols="12" md="6">
              <validation-provider #default="validationContext" name="plan" rules="required">
                <b-form-group label="Price" label-for="Price" :state="getValidationState(validationContext)">
                  <b-form-input v-model="userData.price" class="d-inline-block mr-1" placeholder="Enter Price"
                    />
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

    <b-modal v-permission="['view-clients']" id="status" ref="status" ok-only ok-title="Update Status"
      @show="getStatusList" @hidden="resetStatusData" @ok="handleStatusOk" centered size="lg" title="Update Status"
      no-close-on-backdrop>
      <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
        <form ref="form" @submit.prevent="handleSubmit(onStatusUpdate)">
          <b-row>
            <b-col cols="12">
              <validation-provider #default="validationContext" name="status" rules="required">
                <b-form-group label="" label-for="status" :state="getValidationState(validationContext)">
                  <v-select v-model="leadstatusData.status" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="statuslist" :reduce="(val) => val.id" :clearable="false" input-id="healthcoaches"
                    @input="statusUpdate" />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col cols="12" v-if="showCallbackDate">
              <validation-provider #default="validationContext" name="callback_date" rules="required">
                <b-form-group label="" label-for="callback_date" :state="getValidationState(validationContext)">
                  <b-form-datepicker id="callback_date" v-model="leadstatusData.callback_date" class="d-inline-block"
                    :reset-button="true"></b-form-datepicker>
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

    <b-modal v-permission="['view-clients']" id="queries" ref="queries" ok-only ok-title="Update" @show="getQueriesList"
      @hidden="resetQueryData" @ok="handleQueryOk" centered size="lg" title="Lead Queries / Notes" no-close-on-backdrop>
      <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
        <table class="table table-bordered mb-1">
          <tr v-for="query in queriesList">
            <td>
              {{ query.query }}
            </td>
          </tr>
        </table>
        <form ref="form" @submit.prevent="handleSubmit(onQueryUpdate)">
          <b-row>
            <b-col cols="12">
              <validation-provider #default="validationContext" name="status" rules="required">
                <b-form-group label="" label-for="query" :state="getValidationState(validationContext)">
                  <b-form-input v-model="queryData.query" class="d-inline-block mr-1" placeholder="Enter Notes" />
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
    <b-modal v-permission="['view-clients']" id="info" ref="info" ok-only ok-title="" centered size="lg" title="Lead Info"
      hide-footer="true" no-close-on-backdrop>
      <table class="table table-bordered mb-1" v-if="lead">
        <tr>
          <td>Email ID</td>
          <td>
            {{ lead.email }}
          </td>
        </tr>
        <tr>
          <td>Age</td>
          <td>
            {{ lead.age }}
          </td>
        </tr>
        <tr>
          <td>Gender</td>
          <td>
            {{ lead.gender }}
          </td>
        </tr>
        <tr>
          <td>Concern</td>
          <td>
            {{ lead.concern }}
          </td>
        </tr>
        <tr>
          <td>CIS</td>
          <td>
            {{ lead.healthcoach_name }}
          </td>
        </tr>
        <tr>
          <td>Payment Link</td>
          <td>
            {{ lead.payment_link }}
          </td>
        </tr>
      </table>
    </b-modal>
    <b-modal id="remark" ref="remark" ok-only ok-title="Update" centered size="lg" title="Lead Remark"
      no-close-on-backdrop @hidden="resetRemarkData" @ok="handleRemarkOk">
      <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
        <form ref="form" @submit.prevent="handleSubmit(onRemarkUpdate)">
          <b-row>
            <b-col cols="12">
              <validation-provider #default="validationContext" name="remark" rules="required">
                <b-form-group label="" label-for="remark" :state="getValidationState(validationContext)">
                  <b-form-input v-model="remarkData.remark" class="d-inline-block mr-1" placeholder="Enter Remark" />
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
  </div>
</template>
<script>
import moment from 'moment';
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
  BFormFile,
  BFormTextarea,
  BOverlay,
  BFormCheckbox,
  BFormDatepicker,
  BImg,
  BFormRadioGroup,
  BFormRadio,
} from "bootstrap-vue";
import { ValidationProvider, ValidationObserver } from "vee-validate";

import formValidation from "@core/comp-functions/forms/form-validation";
import { required } from "@validations";

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

import vSelect from "vue-select";

import Ripple from "vue-ripple-directive";

import { ref } from "@vue/composition-api";
import flatPickr from "vue-flatpickr-component";

export default {
  name: "DietPlan",
  components: {
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
    BFormFile,
    BFormTextarea,
    BOverlay,
    BFormCheckbox,
    BFormDatepicker,
    ValidationProvider,
    ValidationObserver,
    vSelect,
    BImg,
    BFormRadioGroup,
    BFormRadio,
    flatPickr
  },
  directives: {
    "b-modal": VBModal,
  },
  data() {
    return {
      pagination: {
        page: 1,
        per_page: 10,
        total: null,
        filter: {},
      },
      required,
      type: "New Lead",
      readonly: false,
      errors: [],
      list: [],
      totalRows: 0,
      userData: {
        price: null,
      },
      leadstatusData: {
        status: null,
      },
      isReadOnly: true,
      lead: null,
      partners: [],
      clients: [],
      healthcoaches: [],
      userlist: [],
      cisfs: [],
      programs: [],
      plans: [],
      statuslist: [],
      queryData: {
        query: "",
      },
      remarkData: {
        remark: "",
      },
      queriesList: [],
      showCallbackDate: false,
      genders: [
        { text: "Male", value: "Male" },
        { text: "Female", value: "Female" },
      ],
      createLead: false,
      paymentLoading: false,
      moment: moment
    };
  },
  directives: {
    Ripple,
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
      { key: "actions", label: "Action", sortable: true },
    ];
    const leadData = ref({
      name: null,
      email: null,
      phone: "",
      concern: null,
      age: null,
      dob: null,
      gender: null,
    });

    const resetLeadData = () => {
      leadData.value = {
        name: null,
        email: null,
        phone: "",
        concern: null,
        age: null,
        dob: null,
        gender: null,
      };
    };
    const { refFormObserver, getValidationState, resetForm } = formValidation(
      resetLeadData
    );

    return {
      leads,

      loading,
      sortBy,
      isSortDirDesc,
      columns,
      leadData,
      refFormObserver,
      getValidationState,
      resetForm,
      resetLeadData,
    };
  },
  computed: {
    id: function () {
      return this.$store.state.auth.user.id;
    },
    page: function () {
      return this.$route.name === "websiteLeads" ? "Website" : "General";
    },
  },
  beforeRouteEnter(to, from, next) {
    next((vm) => {
      vm.getList();
    });
  },
  created() {
    //this.getList();
    this.getPrograms();
    this.checkCreateLead();
  },
  methods: {
    async checkCreateLead() {
      try {
        const { data } = await axios.get("/lead/checkCreateLead", {});
        if (data.createLead == '1') {
          this.createLead = true;
        } else {
          this.createLead = false;
        }
      } catch (error) {
        console.log(error);
      }
    },
    rowClass(item, type) {
      if (item && type === "row") {
        if (item.status === "Interested") {
          return "tr-Interested";
        }
      } else {
        return null;
      }
    },
    viewAssignModal(lead) {
      this.lead = lead;
      this.$refs["assign"].show();
    },
    ViewPaymentModal(lead) {
      this.lead = lead;
      this.$refs["payment"].show();
    },
    handleOk(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.$refs.refFormObserver.handleSubmit(this.onAssign);
    },

    async getHealthcoaches() {
      try {
        this.healthcoaches = [];
        this.userlist = [];
        this.userData.healthcoach_id = "";
        const { data } = await axios.get("/healthcoaches/list", {});
        this.healthcoaches = data.healthcoaches;
        this.cisfs = data.cisfs;
        if (this.userData.assignedto === "healthcoach") {
          this.userlist = this.healthcoaches;
        } else if (this.userData.assignedto === "cisf") {
          this.userlist = this.cisfs;
        }
      } catch (error) {
        console.log(error);
      }
    },
    handleAssignTo() {
      this.userlist = [];
      this.userData.healthcoach_id = "";
      if (this.userData.assignedto === "healthcoach") {
        this.userlist = this.healthcoaches;
      } else if (this.userData.assignedto === "cisf") {
        this.userlist = this.cisfs;
      }
    },
    async getPrograms() {
      try {
        this.programs = [];
        const { data } = await axios.get("/packages/list", {});
        this.programs = data.packages;
      } catch (error) {
        console.log(error);
      }
    },
    async getPlans() {
      try {
        const { data } = await axios.get(
          "/package/planslist/" + this.userData.program_id,
          {}
        );
        this.plans = data.plans;
        this.alliance_partners = data.alliance_partners;
      } catch (error) {
        console.log(error);
      }
    },
    async getPrice() {
      try {
        const { data } = await axios.get(
          "/package/getPrice/?package_id=" +
          this.userData.program_id +
          "&lead_id=" +
          this.lead.id +
          "&plan=" +
          this.userData.plan,
          {}
        );
        this.userData.price = data.price;
        if (!data.success) {
          this.$toast({
            component: ToastificationContent,
            props: {
              title: data.message,
              icon: "BellIcon",
              variant: data.success ? "success" : "danger",
            },
          });
        }
      } catch (error) {
        console.log(error);
      }
    },
    async onAssign() {
      try {
        this.userData.lead_id = this.lead.id;
        const { data } = await axios.post("/lead/assignhc", this.userData);
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "BellIcon",
            variant: data.success ? "success" : "danger",
          },
        });
        this.getList();
        this.$nextTick(() => {
          this.$refs["assign"].hide();
        });
      } catch (err) {
        console.log(err);
      }
    },
    handleOkProgram(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.$refs.refFormObserver.handleSubmit(this.onPayment);
    },
    async onPayment() {
      try {
        this.paymentLoading = true;
        this.userData.lead_id = this.lead.id;
        const { data } = await axios.post("/lead/assignprogram", this.userData);
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "BellIcon",
            variant: data.success ? "success" : "danger",
          },
        });
        this.getList();
        this.$nextTick(() => {
          this.$refs["payment"].hide();
        });
        this.paymentLoading = false;
      } catch (err) {
        console.log(err);
      }
    },
    async getAssignedUsers() {
      try {
        let id = this.client.id;
        const { data } = await axios.get(`client/${id}/users`, {
          params: this.pagination,
        });
        this.list = data.users;
        this.totalRows = data.totalRows;
      } catch (err) {
        console.log(err);
      }
    },
    async getList() {
      this.adjustColumns();

      try {
        this.loading = true;
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
      this.loading = false;
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
          { key: "name", label: "Name" },
          { key: "alliancepartner_name", label: "Alliance Partner" },
          { key: "status", label: "Status" },
        ];
      } else {
        this.columns.push({ key: "actions", label: "Action", sortable: false });
      }
    },

    handlePaginationChange(val) {
      this.pagination.page = val;
      this.getList();
    },

    handleFilterChange(val) {
      this.pagination.per_page = val;
      this.getList();
    },

    async onSubmit() {
      try {
        let data;
        if (!this.leadData.user_id) {
          this.leadData.user_id = this.id;
        }
        if (this.leadData.id) {
          data = await axios.put(`/lead/${this.leadData.id}`, this.leadData);
        } else {
          data = await axios.post("/lead", this.leadData);
        }
        this.resetFormCard();

        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.data.message,
            icon: "BellIcon",
            variant: data.data.success ? "success" : "danger",
          },
        });

        this.getList();
      } catch (error) {
        if (error.response) {
          this.errors = error.response.data.errors;
        }
        console.log(error);
      }
    },

    editLead(data) {
      this.type = "Update Lead";
      this.lead = data;
      // this.readonly = true;
      this.leadData.id = data.id;
      this.leadData.name = data.name;
      this.leadData.email = data.email;
      this.leadData.phone = data.phone;
      this.leadData.concern = data.concern;
      this.leadData.user_id = data.user_id;
      this.leadData.dob = data.dob;
      this.leadData.age = data.age;
      this.leadData.gender = data.gender;
    },
    viewRemarkModel(data) {
      this.remarkData.remark = data.remark;
      this.lead = data;
      this.$refs["remark"].show();
    },
    async handleDeleteLead(id) {
      try {
        const data = await axios.delete(`/lead/${id}`);
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
      this.resetLeadData();
      this.type = "New Lead";
      this.resetForm();
    },

    async callNow(lead) {
      try {
        const { data } = await axios.post(
          `/coordinator/call`,
          lead
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
            title: "Error while initiating a call",
            icon: "AlertTriangleIcon",
            variant: "danger",
          },
        });
      }
    },
    viewStatusModal(lead) {
      this.lead = lead;
      this.$refs["status"].show();
    },
    handleStatusOk(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.$refs.refFormObserver.handleSubmit(this.onStatusUpdate);
    },
    async getStatusList() {
      try {
        const { data } = await axios.get("/lead/getLeadStatus");
        this.statuslist = data.status;
      } catch (err) {
        console.log(err);
      }
    },
    async onStatusUpdate() {
      try {
        this.leadstatusData.lead_id = this.lead.id;
        const { data } = await axios.post("/lead/updatestatus", this.leadstatusData);
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "BellIcon",
            variant: data.success ? "success" : "danger",
          },
        });
        this.$nextTick(() => {
          this.$refs["status"].hide();
        });
        this.getList();
      } catch (err) {
        console.log(err);
      }
    },
    viewQueryModal(lead) {
      this.lead = lead;
      this.$refs["queries"].show();
    },
    handleQueryOk(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.$refs.refFormObserver.handleSubmit(this.onQueryUpdate);
    },
    async getQueriesList() {
      try {
        const { data } = await axios.get(`/lead/getQueriesList/${this.lead.id}`);
        this.queriesList = data.queries;
      } catch (err) {
        console.log(err);
      }
    },
    async onQueryUpdate() {
      try {
        this.queryData.lead_id = this.lead.id;
        const { data } = await axios.post("/lead/storequery", this.queryData);
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "BellIcon",
            variant: data.success ? "success" : "danger",
          },
        });
        this.$nextTick(() => {
          this.$refs["status"].hide();
        });
        this.getList();
      } catch (err) {
        console.log(err);
      }
    },
    handleRemarkOk(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.$refs.refFormObserver.handleSubmit(this.onRemarkUpdate);
    },
    async onRemarkUpdate() {
      try {
        this.remarkData.lead_id = this.lead.id;
        const { data } = await axios.post("/lead/storeRemark", this.remarkData);
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "BellIcon",
            variant: data.success ? "success" : "danger",
          },
        });
        this.$nextTick(() => {
          this.$refs["remark"].hide();
        });
        this.getList();
      } catch (err) {
        console.log(err);
      }
    },
    viewInfo(lead) {
      this.lead = lead;
      this.$refs["info"].show();
    },
    statusUpdate() {
      if (this.leadstatusData.status == "Call back") {
        this.showCallbackDate = true;
      }
    },
    resetStatusData() {
      this.leadstatusData.status = "";
      this.showCallbackDate = false;
      this.leadstatusData.callback_date = "";
    },
    dobChanged() {
      //console.log(new Date(this.userData.dob).getTime())
      var diff = (new Date().getTime() - new Date(this.leadData.dob).getTime()) / 1000;
      diff /= 60 * 60 * 24;
      const age = Math.abs(Math.round(diff / 365.25));
      this.leadData.age = age;
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
@import "@core/scss/vue/libs/vue-flatpicker.scss";

#assignUser___BV_modal_body_ {
  min-height: 200px !important;
  max-height: 300px !important;
  overflow-y: auto;
}

tr.tr-Interested {
  background: beige;
}
</style>
