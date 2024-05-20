<template>
  <div style="min-height: 300px">
    <b-overlay :show="$store.state.app.shallShowOverlay" no-wrap opacity="0.5" blur="1px"></b-overlay>
    <b-row class="mb-3" gap="2">
      <!-- Per Page -->
      <b-col cols="12" md="6">
        <b-row>
          <b-col cols="12" md="4" class="text-info">
            <v-select :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
              :options="['Wellness', 'Women Care', 'Lifeskill', 'Workshop']" :clearable="true" v-model="type"
              input-id="program_type" class="text-info border-info" placeholder="Program Type" @input="typeChanged" />
          </b-col>
          <b-col cols="12" md="4" class="text-info">
            <v-select :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
              :options="['Diet Program', 'Yoga / Fitness Program', 'Counselling Program', 'Arika Program', 'Umika Program']"
              :clearable="true" v-model="category" input-id="category" class="text-info border-info"
              placeholder="Program Category" @input="typeChanged" />
          </b-col>
          <b-col cols="12" md="4" class="text-info">
            <v-select v-model="plan" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
              :options="['1 Month', '3 Months', '6 Months', '12 Months', 'Free Session']" :clearable="true"
              input-id="duration" class="text-info border-info" placeholder="Plan Duration" @input="planChanged" />
          </b-col>
        </b-row>
      </b-col>

      <!-- Search -->
      <b-col cols="12" md="6" class="d-flex justify-content-end">
        <b-row>
          <b-col cols="12" md="4" class="text-info">
            <b-button @click="fetchRecent" variant="link" :class="recent">Recent</b-button>
          </b-col>
          <b-col cols="12" md="4" class="text-info">
            <b-button variant="link" @click="sortAlphabetically" :class="sort">Alphabetically</b-button>
          </b-col>
        </b-row>
      </b-col>
    </b-row>
    <!-- <b-card > -->
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
    <b-row :key="key">
      <b-col :sm="5" :md="4" :lg="3" :xl="3" v-for="program in packages" :key="program.id">
        <b-card class="ecommerce-card" no-body>
          <!-- Product Details -->
          <img :src="'/view/file?path=' + program.image" alt="Card image cap" class="card-img-top"
            style="height: 160px" />
          <b-card-body>
            <div class="align-items-center justify-content-center">
              <b-row class="mb-2">
                <b-col cols="9">
                  <h5 class="mb-0 text-info heading" style="font-weight: bolder" :title="program.title">
                    {{ program.title }}
                  </h5>
                </b-col>
                <b-col cols="2">
                  <b-dropdown variant="link" no-caret v-permission="['view-program']"
                    v-if="$store.state.auth.user.role_id != '11'">
                    <template #button-content>
                      <feather-icon icon="MoreVerticalIcon" size="16" class="align-middle text-body" />
                    </template>
                    <b-dropdown-item v-permission="['edit-program']"
                      :to="{ name: 'editPackage', params: { id: program.id } }">
                      <feather-icon icon="EditIcon" />
                      <span class="align-middle ml-50">Edit</span>
                    </b-dropdown-item>
                    <b-dropdown-item @click="viewAssignModal(program)" v-permission="['view-program']" v-if="$store.state.auth.user.role_id == '1' ||
                      $store.state.auth.user.role_id == '10'
                      ">
                      <feather-icon icon="UsersIcon" />
                      <span class="align-middle ml-50">Assign</span>
                    </b-dropdown-item>
                    <b-dropdown-item @click="viewCustomAssignModal(program)" v-permission="['view-program']" v-if="$store.state.auth.user.role_id == '1' ||
                      $store.state.auth.user.role_id == '10'
                      ">
                      <feather-icon icon="UsersIcon" />
                      <span class="align-middle ml-50">Custom Program Assignment</span>
                    </b-dropdown-item>
                    <b-dropdown-item @click="viewUserModal(program)" v-permission="['edit-program']">
                      <feather-icon icon="EyeIcon" />
                      <span class="align-middle ml-50">View Users</span>
                    </b-dropdown-item>
                    <b-dropdown-item @click="handleDeletePackage(program.id)" v-permission="['delete-program']">
                      <feather-icon icon="TrashIcon" />
                      <span class="align-middle ml-50">Delete</span>
                    </b-dropdown-item>
                  </b-dropdown>
                </b-col>
                <b-col cols="10" class="mb-1 heading">
                  <span class="text-primary">
                    {{ program.category }}
                  </span>
                </b-col>
                <b-col cols="12" class="d-flex">
                  <small class="plan-span" :title="program.plans ? program.plans.join('/') : ''">
                    <feather-icon icon="ClockIcon"></feather-icon>&nbsp;Plan:
                    {{ getPlanName(program) }}
                  </small>
                </b-col>
                <b-col cols="12" class="d-flex prices-block mt-2" v-if="program.get_package && (is_upgraded_consultant == true || $store.state.auth.user.role_id == 2)">
                  <ul class="prices">
                    <li v-for="(price, month) in program.get_package.data">
                      <span class="package-prices">&#8377;{{ price }} for {{ month }}</span>
                    </li>
                  </ul>
                </b-col>
              </b-row>
            </div>
            <div class="d-flex align-items-center item-rating justify-content-center">
              <b-button size="sm" :to="{ name: 'viewPackage', params: { id: program.id } }" variant="info mr-1">
                <span style="color: #fff" class="text-nowrap">Know More</span>
              </b-button>
              <b-button size="sm" v-if="false" variant="primary">
                <span style="color: #fff" class="text-nowrap">Pay Now</span>
              </b-button>
              <span></span>
            </div>
          </b-card-body>
        </b-card>
      </b-col>
    </b-row>
    <b-pagination v-if="pagination.total" v-model="pagination.page" :page.sync="pagination.page"
      :total-rows="pagination.total" :index="1" :per-page="pagination.per_page" aria-controls="video-row" align="right"
      @change="handlePaginationChange">
    </b-pagination>
    <b-modal v-permission="['edit-program']" id="assign" ref="assign" ok-only ok-title="Assign" @show="resetData"
      @hidden="resetData" @ok="handleOk" centered size="lg" title="Program Assignment" no-close-on-backdrop>
      <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
        <form ref="form" @submit.prevent="handleSubmit(onAssign)">
          <b-row>
            <b-col cols="12" md="6">
              <validation-provider #default="validationContext" name="alliance-partners" rules="required">
                <b-form-group label="Select Alliance Partners" label-for="partners"
                  :state="getValidationState(validationContext)">
                  <v-select v-model="mappingData.partners" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="partners" :reduce="(val) => val.id" :clearable="false" multiple input-id="partners" />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
          </b-row>
          <b-row>
            <table class="table">
              <thead>
                <tr>
                  <th>Plan</th>
                  <th>Basic Cost</th>
                  <th>Alliance Partner Cost</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(plan, index) in mappingData.plans" :key="plan">
                  <td>{{ plan }}</td>
                  <td>
                    <span v-if="mappingData.costs != null">{{
                      mappingData.costs[index]
                    }}</span>
                    <span v-else>Undefined</span>
                  </td>
                  <td>
                    <validation-provider #default="validationContext" rules="required">
                      <b-form-group label-for="cost" style="font-weight: bolder"
                        :state="getValidationState(validationContext)">
                        <b-form-input type="number" id="cost" :min="1" v-model="mappingData.cost[plan]"></b-form-input>
                        <b-form-invalid-feedback :state="getValidationState(validationContext)">
                          {{ validationContext.errors[0] }}
                        </b-form-invalid-feedback>
                      </b-form-group>
                    </validation-provider>
                  </td>
                </tr>
              </tbody>
            </table>

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
                                    <b-form-datepicker id="expiry_date" :min="new Date()" v-model="mappingData.expiry_date"></b-form-datepicker>
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
    <b-modal v-permission="['edit-program']" id="customassign" ref="customassign" ok-only ok-title="Assign"
      @show="resetCustomData" @hidden="resetCustomData" @ok="handleCustomOk" centered size="lg"
      title="Custom Program Assignment" no-close-on-backdrop>
      <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
        <form ref="form" @submit.prevent="handleSubmit(onCustomAssign)">
          <b-row>
            <b-col cols="12" md="6">
              <validation-provider #default="validationContext" name="client">
                <b-form-group label="Select Clients" label-for="client" :state="getValidationState(validationContext)">
                  <v-select v-model="custommappingData.clients" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="clients" :reduce="(val) => val.id" :clearable="false" multiple input-id="clients" />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col cols="12" md="6">
              <validation-provider #default="validationContext" name="lead">
                <b-form-group label="Select Leads" label-for="lead" :state="getValidationState(validationContext)">
                  <v-select v-model="custommappingData.leads" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="leads" :reduce="(val) => val.id" :clearable="false" multiple input-id="leads" />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
          </b-row>
          <b-row>
            <table class="table">
              <thead>
                <tr>
                  <th>Plan</th>
                  <th>Cost</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <validation-provider #default="validationContext" rules="required">
                      <b-form-group label-for="plan" style="font-weight: bolder"
                        :state="getValidationState(validationContext)">
                        <b-form-input type="text" id="plan" :min="1" v-model="custommappingData.plan"></b-form-input>
                        <b-form-invalid-feedback :state="getValidationState(validationContext)">
                          {{ validationContext.errors[0] }}
                        </b-form-invalid-feedback>
                      </b-form-group>
                    </validation-provider>
                  </td>
                  <td>
                    <validation-provider #default="validationContext" rules="required">
                      <b-form-group label-for="cost" style="font-weight: bolder"
                        :state="getValidationState(validationContext)">
                        <b-form-input type="number" id="cost" :min="1" v-model="custommappingData.cost"></b-form-input>
                        <b-form-invalid-feedback :state="getValidationState(validationContext)">
                          {{ validationContext.errors[0] }}
                        </b-form-invalid-feedback>
                      </b-form-group>
                    </validation-provider>
                  </td>
                </tr>
              </tbody>
            </table>
          </b-row>
        </form>
      </validation-observer>
    </b-modal>
    <b-modal v-permission="['edit-program']" id="view-users" hide-footer centered size="lg" @show="getAssignedUsers"
      @hidden="resetUsers" title="Assigned Users" ref="view-users" no-close-on-backdrop>
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
        <template #cell(cost)="{ item }">
          <span class="text-primary">
            {{ item.cost }}
          </span>
        </template>
        <template #cell(status)="{ item }">
          <span class="text-info" v-if="item.transaction_id"> Paid </span>
          <span class="text-danger" v-else> UnPaid </span>
        </template>
        <template #cell(enrolled)="{ item }">
          <span class="text-info" v-if="item.enrolled == 1"> YES </span>
          <span class="text-danger" v-else> NO </span>
        </template>

        <!-- Column: Actions -->
        <template #cell(actions)="data">
          <a @click="handleRemoveUser('package', data.item.package_user_id)" v-permission="['edit-program']"
            style="cursor: pointer" title="remove user">
            <feather-icon icon="TrashIcon" />
          </a>
        </template>
      </b-table>
    </b-modal>
    <!-- </b-card> -->
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
  BCardBody,
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
  BFormTextarea,
  BFormGroup,
  BOverlay,
  BFormCheckbox,
  BFormDatepicker,
  BMedia,
  BMediaAside,
  BMediaBody,
  BFormFile,
  BLink,
  BImg,
} from "bootstrap-vue";
import { ValidationProvider, ValidationObserver } from "vee-validate";

import formValidation from "@core/comp-functions/forms/form-validation";
import { required } from "@validations";

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

import vSelect from "vue-select";

import Ripple from "vue-ripple-directive";
import "quill/dist/quill.core.css";
import "quill/dist/quill.snow.css";
import "quill/dist/quill.bubble.css";
import { quillEditor } from "vue-quill-editor";

import { useInputImageRenderer } from "@core/comp-functions/forms/form-utils";

import { validateSize } from "@core/utils/utils";
import flatPickr from "vue-flatpickr-component";

import { ref } from "@vue/composition-api";
import FeatherIcon from "../../@core/components/feather-icon/FeatherIcon.vue";

export default {
  components: {
    BCard,
    BCardBody,
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
    BFormTextarea,
    BOverlay,
    BFormCheckbox,
    BFormDatepicker,
    quillEditor,
    BMedia,
    BMediaAside,
    BMediaBody,
    BFormFile,
    BLink,
    BImg,
    FeatherIcon,
    flatPickr,
  },
  data() {
    var toolbarOptions = [
      ["bold", "italic", "underline", "strike"], // toggled buttons
      [{ list: "ordered" }, { list: "bullet" }],
      [{ indent: "-1" }, { indent: "+1" }], // outdent/indent
      [{ direction: "rtl" }], // text direction
      [{ header: [1, 2, 3, 4, 5, 6, false] }],
      [{ color: [] }], // dropdown with defaults from theme
      [{ font: [] }],
      [{ align: [] }], // remove formatting button
    ];
    return {
      editorOption: {
        modules: {
          toolbar: toolbarOptions,
        },
        theme: "snow",
      },
      pagination: {
        page: 1,
        per_page: 10,
        total: null,
      },
      plan: "",
      list: [],
      package: null,
      required,
      type: "",
      is_upgraded_consultant: false,
      category: "",
      groupPackages: [],
      readonly: false,
      errors: [],
      pkg: null,
      mappingData: {
        partners: [],
        cost: {},
        plans: [],
        costs: [],
      },
      custommappingData: {
        clients: [],
        leads: [],
        cost: '',
      },
      totalRows: 0,
      partners: [],
      clients: [],
      leads: [],
      key: 0,
      image: null,
      showRange: false,
      plans: [
        "1 Month",
        "2 Months",
        "3 Months",
        "6 Months",
        "9 Months",
        "12 Months",
        "1 Session",
        "2 Sessions",
        "3 Sessions",
        "4 Sessions",
        "5 Sessions",
        "6 Sessions",
        "7 Sessions",
        "8 Sessions",
        "9 Sessions",
        "10 Sessions",
        "11 Sessions",
        "12 Sessions",
        "60 Minutes",
        "90 Minutes",
        "120 Minutes",
      ],
      sessions: [
        "Diet Session",
        "Yoga Session",
        "HITT Sessions",
        "Doctor Consultation",
        "Counseling",
        "Training",
        "Workshops",
      ],
    };
  },

  directives: {
    Ripple,
  },
  setup() {
    const packages = [];
    const loading = true;
    const sortBy = "1";
    const isSortDirDesc = false;
    const columns = [
      { key: "index", label: "Sr. No.", sortable: true },
      { key: "title", label: "Title", sortable: true },
      { key: "category", label: "Category", sortable: true },
      { key: "type", label: "Type", sortable: true },
      { key: "description", label: "Description", sortable: true },
      { key: "actions" },
    ];
    const ucolumns = [
      { key: "index", label: "Sr. No.", sortable: true },
      { key: "name", label: "Name", sortable: true },
      { key: "email", label: "Email", sortable: true },
      { key: "phone", label: "Contact Number", sortable: true },
      { key: "cost", label: "Program Cost", sortable: true },
      { key: "status", label: "Status", sortable: false },
      { key: "enrolled", label: "Enrolled", sortable: true },
      { key: "actions", label: "Action", sortable: false },
    ];
    const packageData = ref({
      title: "",
      description: "",
      category: "",
      id: null,
      duration: "Free Session",
      image: null,
      payment_type: "Free",
      communication_type: "Video Call",
      start_date: "",
      end_date: "",
      sessions: [{}],
      plans: ["10 min"],
    });

    const resetPackageData = function () {
      packageData.value = {
        title: "",
        description: "",
        category: "",
        id: null,
        duration: "Free Session",
        image: null,
        payment_type: "Free",
        communication_type: "Video Call",
        start_date: "",
        end_date: "",
        sessions: [{}],
        plans: ["10 min"],
      };
    };

    const refInputEl = ref(null);
    const previewEl = ref(null);

    const imageRenderer = useInputImageRenderer(refInputEl, previewEl);
    const { refFormObserver, getValidationState, resetForm } = formValidation(
      resetPackageData
    );
    return {
      packages,

      loading,
      sortBy,
      isSortDirDesc,
      columns,
      ucolumns,
      packageData,
      refFormObserver,
      getValidationState,
      resetPackageData,
      resetForm,
      imageRenderer,
      refInputEl,
      previewEl,
    };
  },
  computed: {
    id: function () {
      return this.package.id;
    },
  },

  watch: {
    "pagination.per_page": function (val) {
      this.handleFilterChange(val);
    },
  },
  created() {
    this.getList();
    this.checkIfUpgradedConsultant();
  },
  methods: {
    validateSize,
    async getList() {
      try {
        this.loading = true;
        const { data } = await axios.get("package", {
          params: this.pagination,
        });
        this.packages = data.packages;
        this.pagination.per_page = data.per_page;
        this.pagination.total = data.total;
        this.pagination.page = data.page;

        this.key++;
      } catch (err) {
        console.log(err);
      }
      this.loading = false;

      console.log(this.packages);
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
        let packageData = new FormData();
        packageData.append("title", this.packageData.title);
        packageData.append("description", this.packageData.description);
        packageData.append("category", this.packageData.category);
        packageData.append("image", this.image);
        packageData.append("type", this.packageData.type);
        packageData.append("payment_type", this.packageData.payment_type);
        packageData.append("communication_type", this.packageData.communication_type);
        packageData.append("sessions", JSON.stringify(this.packageData.sessions));
        packageData.append("plans", JSON.stringify(this.packageData.plans));
        packageData.append("start_date", this.packageData.start_date);
        packageData.append("end_date", this.packageData.end_date);

        if (this.packageData.id) {
          packageData.append("id", this.packageData.id);
          data = await axios.post(`/package/${this.packageData.id}`, packageData, {
            headers: {
              "content-type": "multipart/form-data",
            },
          });
        } else {
          data = await axios.post("/package", packageData, {
            headers: {
              "content-type": "multipart/form-data",
            },
          });
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
        } else {
          console.log(error);
        }
      }
    },

    async editPackage(data) {
      await window.scroll({
        top: 0,
        left: 0,
        behavior: "smooth",
      });
      this.type = "Update Program";
      // this.readonly = true;
      this.packageData.id = data.id;
      this.packageData.title = data.title;
      this.packageData.category = data.category;
      this.packageData.description = data.description;
      this.packageData.type = data.type;
      this.packageData.payment_type = data.payment_type;
      this.packageData.communication_type = data.communication_type;
      this.packageData.sessions = data.sessions;
      this.packageData.plans = data.plans ? data.plans : [];
      this.packageData.image = data.image;
      this.packageData.start_date = data.start_date;
      this.packageData.end_date = data.end_date;
    },

    async getPartners() {
      try {
        const { data } = await axios.get(
          "/package/alliance-partner/list?package_id=" + this.package.id
        );
        this.partners = data.partners;
      } catch (err) {
        console.log(err);
      }
    },
    async checkIfUpgradedConsultant() {
      const { data } = await axios.get(`/consultant/${this.$store.state.auth.user.id}/is_upgrade`);
      this.is_upgraded_consultant = data.is_upgraded;
    },
    async getUsers() {
      try {
        const { data } = await axios.get(
          "/package/users/list?package_id=" + this.package.id
        );
        this.clients = data.clients;
        this.leads = data.leads;
      } catch (err) {
        console.log(err);
      }
    },
    async getAssignedUsers() {
      try {
        let id = this.package.id;
        const { data } = await axios.get(`package/${id}/users`, {
          params: this.pagination,
        });
        this.list = data.users;
        this.totalRows = data.totalRows;
      } catch (err) {
        console.log(err);
      }
    },

    viewAssignModal(data) {
      this.package = data;
      this.mappingData.plans = data.plans;
      this.mappingData.costs = data.cost;
      this.getPartners();
      this.$refs["assign"].show();
    },
    viewCustomAssignModal(data) {
      this.package = data;
      this.custommappingData.cost = '';
      this.getUsers();
      this.$refs["customassign"].show();
    },

    viewUserModal(data) {
      this.package = data;
      this.$refs["view-users"].show();
    },

    async handleDeletePackage(id) {
      try {
        const data = await axios.delete(`/package/${id}`);
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.data.message,
            icon: "BellIcon",
            variant: data.data.success ? "success" : "danger",
          },
        });
        this.getList();
        this.key++;
      } catch (err) {
        console.log(err);
      }
    },

    resetFormCard() {
      this.resetForm();
      this.resetPackageData();
      this.type = "";
      this.readonly = false;
      this.errors = [];
      this.image = null;
      this.$refs.previewEl.src = "/view/file?path=" + this.packageData.image;
    },

    async onAssign() {
      try {
        this.mappingData.package = this.package.id;
        const { data } = await axios.post("/package/assignment", this.mappingData);
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
    async onCustomAssign() {
      try {
        this.custommappingData.package = this.package.id;
        const { data } = await axios.post("/package/customassignment", this.custommappingData);
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "BellIcon",
            variant: data.success ? "success" : "danger",
          },
        });
        this.$nextTick(() => {
          this.$refs["customassign"].hide();
        });
      } catch (err) {
        console.log(err);
      }
    },
    resetUsers() {
      this.list = [];
    },

    handleOk(bvModalEvt) {
      bvModalEvt.preventDefault();

      this.$refs.refFormObserver.handleSubmit(this.onAssign);
    },
    handleCustomOk(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.$refs.refFormObserver.handleSubmit(this.onCustomAssign);
    },
    resetData() {
      this.mappingData.partners = [];
      this.mappingData.cost = {};
    },
    resetCustomData() {
      this.custommappingData.clients = [];
      this.custommappingData.leads = [];
      this.custommappingData.cost = '';
      this.custommappingData.plan = '';
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

    addRow(field) {
      let val = field === "plans" ? "" : {};
      this.packageData[field].push(val);
    },
    removeRow(index, field) {
      this.packageData[field].splice(index, 1);
    },
    enableFields(a) {
      if (a === "Workshop" || a == "LifeSkill") this.showRange = true;
      else this.showRange = false;
    },

    validatePlan(value, index) {
      this.packageData.plans[index] = "";
      if (!this.packageData.plans.includes(value)) {
        this.packageData.plans[index] = value;
      }
    },
    getPlanName(program) {
      return program.plans ? program.plans.slice(0, 2).join("/") : "";
    },
    typeChanged() {
      this.pagination.type = this.type;
      this.pagination.category = this.category;
      this.getList();
    },
    planChanged() {
      this.pagination.plan = this.plan;
      this.getList();
    },
    fetchRecent() {
      this.pagination.recent = true;
      this.pagination.sort = false;
      this.recent = "border-info text-info";
      this.sort = "";
      this.getList();
    },
    sortAlphabetically() {
      this.pagination.sort = true;
      this.pagination.recent = false;
      this.sort = "border-info text-info";
      this.recent = "";
      this.getList();
    },
  },
};
</script>

<style lang="scss" scoped>
.per-page-selector {
  width: 90px;
}

.ql-container {
  min-height: 100px !important;
}

.prices-block ul.prices {
  list-style-type: circle;
  padding-left: 1.5rem;
}

</style>

<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";
@import "@core/scss/vue/libs/quill.scss";
@import "@core/scss/vue/libs/vue-flatpicker.scss";
</style>
