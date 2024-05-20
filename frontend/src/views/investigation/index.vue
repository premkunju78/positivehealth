<template>
  <b-card>
    <b-row class="mb-2">
      <!-- Per Page -->
      <b-col cols="12" md="6" class="d-flex align-items-center justify-content-start mb-1 mb-md-0">
        <label>Show</label>
        <v-select v-model="pagination.per_page" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
          :options="[10, 25, 50, 100, 1000]" :clearable="false" class="per-page-selector d-inline-block mx-50" />
        <label>entries</label>
      </b-col>

      <b-col cols="12" md="6">
        <div class="d-flex align-items-center justify-content-end" v-permission="['create-investigation']">
          <!-- <b-form-input
                    v-model="searchQuery"
                    class="d-inline-block mr-1"
                    placeholder="Search..."
                /> -->
          <router-link :to="{ name: 'newInvestigation', params: { id: this.id } }"
            v-permission="['create-investigation']">
            <b-button variant="primary">
              <span class="text-nowrap">New Investigation</span>
            </b-button>
          </router-link>
        </div>
      </b-col>
    </b-row>

    <b-row v-if="role === 18">
      <!-- Per Page -->
      <b-col cols="12" md="6" class="d-flex align-items-center justify-content-start mb-1 mb-md-0">
        <ul class="nav nav-tabs nav-justified">
          <li class="nav-item">
            <a class="nav-link" @click.prevent="setActive('todays')" :class="{ active: isActive('todays') }" href="#for_programs">Today's</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" @click.prevent="setActive('upcoming')" :class="{ active: isActive('upcoming') }" href="#enquiries">Upcoming</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" @click.prevent="setActive('past')" :class="{ active: isActive('past') }" href="#enquiries">Past</a>
          </li>
        </ul>
      </b-col>
    </b-row>

    <b-table style="min-height: 250px" ref="refRolesTable" class="position-relative" :items="investigations" responsive
      :fields="columns" :totalRows="pagination.total" primaryKey="index" :sort-by.sync="sortBy" show-empty
      empty-text="No matching records found" :sort-desc.sync="isSortDirDesc" :tbody-tr-class="rowClass">
      <template #cell(index)="data">
        {{ data.index + 1 }}
      </template>
      <template #cell(call)="data">
        <b-button variant="success" class="btn btn-primary ml-2 btn-sm" @click="callNow(data.item.phone)">
          <span class="text-nowrap">Call Now</span>
        </b-button>      
      </template>
      <template #cell(payment_status)="data">
        <b-button variant="warning" class="btn btn-warning ml-2 btn-sm" v-if="data.item.payment_status === 'pending'">
          <span class="text-nowrap">Pending</span>
        </b-button>      
        <b-button variant="success" class="btn btn-success ml-2 btn-sm" v-if="data.item.payment_status === 'completed'">
          <span class="text-nowrap">Completed</span>
        </b-button>      
      </template>
      <template #cell(created_at)="data">
        {{ moment(data.item.created_at).format("ll") }}
      </template>
      <template #cell(updated_at)="data">
        {{ moment(data.item.updated_at).format("ll") }}
      </template>
      <template #cell(reports)="data">
        <b-link title="upload files" :to="{ name: 'uploadReports', params: { id: data.item.id } }" v-if="role === 3">
          <feather-icon icon="UploadIcon" /> <span>Upload</span> </b-link>&nbsp;&nbsp;
        <b-link @click="viewFileDialog(data.item)" title="view files">
          <feather-icon icon="FileIcon" />
        </b-link>
      </template>
      <template #cell(view_report)="data">
        <b-link :to="{ name: 'viewInvestigation', params: { id: data.item.id, client_id: ( role === 3 ? $store.state.auth.user.id : ( $route.params.id ? $route.params.id : data.item.client_id ) ) } }" >
          <feather-icon icon="EyeIcon" /> <span>View</span>
        </b-link>
      </template>
      <template #cell(status)="data">
        <b-dropdown variant="link" no-caret :right="$store.state.appConfig.isRTL" v-if="role === 17 || role === 18">
          <template #button-content>
            <span class="badge badge-primary caps">{{ data.item.status }}</span>
          </template>
          <b-dropdown-item @click="updateStatus(data.item.id, 'picked-up')" v-if="data.item.status !== 'picked-up' && role === 17">
            <feather-icon icon="XCircleIcon" />
            <span class="align-middle ml-50">Picked-Up</span>
          </b-dropdown-item>
          <b-dropdown-item @click="updateStatus(data.item.id, 'reports-completed')" v-if="data.item.status !== 'reports-completed' && role === 17">
            <feather-icon icon="EyeIcon" />
            <span class="align-middle ml-50">Reports Completed</span>
          </b-dropdown-item>
          <b-dropdown-item @click="updateStatus(data.item.id, 'assigned')" v-if="data.item.status !== 'assigned' && role === 17">
            <feather-icon icon="EyeIcon" />
            <span class="align-middle ml-50">Assigned</span>
          </b-dropdown-item>
          <b-dropdown-item @click="updateStatus(data.item.id, 'collected')" v-if="data.item.status !== 'collected' && role === 18">
            <feather-icon icon="EyeIcon" />
            <span class="align-middle ml-50">Collect</span>
          </b-dropdown-item>
          <b-dropdown-item @click="updateStatus(data.item.id, 'rejected')" v-if="data.item.status !== 'rejected' && role === 18">
            <feather-icon icon="EyeIcon" />
            <span class="align-middle ml-50">Reject</span>
          </b-dropdown-item>
        </b-dropdown>        
        <p v-else >
          <span class="badge badge-default caps">{{ data.item.status }}</span>
        </p> 
      </template>
      <template #cell(actions)="data">
        <b-dropdown variant="link" no-caret >
          <template #button-content>
            <feather-icon icon="MoreVerticalIcon" size="16" class="align-middle text-body" />
          </template>
          <b-dropdown-item :to="{ name: 'editInvestigation', params: { id: data.item.id } }"
            v-permission="['edit-investigation']">
            <feather-icon icon="EditIcon" />
            <span class="align-middle ml-50">Edit</span>
          </b-dropdown-item>
          <b-dropdown-item @click="handleDeleteInvestigation(data.item.id)" v-permission="['delete-investigation']">
            <feather-icon icon="TrashIcon" />
            <span class="align-middle ml-50">Delete</span>
          </b-dropdown-item>
          <b-dropdown-item @click="viewInvestigation(data.item)">
            <feather-icon icon="EyeIcon" />
            <span class="align-middle ml-50">View</span>
          </b-dropdown-item>
          <b-dropdown-item @click="assignSampleCollectors(data.item.id)" v-if="role === 17">
            <feather-icon icon="UserIcon" />
            <span class="align-middle ml-50">Assign to Sample Collectors</span>
          </b-dropdown-item>
        </b-dropdown>
      </template>
    </b-table>
    <b-pagination v-if="pagination.total" v-model="pagination.page" :page.sync="pagination.page"
      :total-rows="pagination.total" :index="1" :per-page="pagination.per_page" aria-controls="package-row" align="right"
      @change="handlePaginationChange">
    </b-pagination>
    <b-modal id="view-files" hide-footer centered size="lg" @hidden="resetItem" title="Uploaded Files" ref="view-files"
      style="min-height: 300px" no-close-on-backdrop>
      <b-row>
        <b-col md="4" lg="3" v-for="file in item.files" :key="file.name" class="card m-2" style="background: #f9f9f9">
          <b-link :href="'/view/file?path=' + file.path" target="_blank">
            <div class="card-img-top file-logo-wrapper">
              <div class="d-flex align-items-center justify-content-center w-100" style="margin-top: 10%">
                <a :href="'/view/file?path=' + file.path" target="_blank">
                  <img :src="'/view/file?path=' + file.path" height="35" />
                </a>
              </div>
            </div>
            <div class="card-body">
              <div class="content-wrapper">
                <p class="card-text file-name mb-0">{{ file.name }}</p>
              </div>
            </div>
          </b-link>
        </b-col>
      </b-row>
    </b-modal>

    <b-modal id="view-investigation" ref="view-investigation" hide-footer centered size="md" title="Investigation Details"
      no-close-on-backdrop>

      <p><span>Invoice Date</span>: <strong>{{ moment(scViewData.created_at).format("ll") }}</strong></p>
      <p><span>Consultant Name</span>: <strong>{{ scViewData.consultant }}</strong></p>
      <p><span>Sample Collector</span>: <strong>{{ scViewData.sample_collector }}</strong></p>
      <p class="text-right">
        <b-link :to="{ name: 'viewInvestigation', params: { id: scViewData.id, client_id: ( role === 3 ? $store.state.auth.user.id : ( $route.params.id ? $route.params.id : scViewData.client_id ) ) } }" type="button" class="btn btn-success float-right" >
          <feather-icon icon="EyeIcon" /> <span>View</span>
        </b-link>
      </p>

    </b-modal>

    <b-modal v-permission="['view-clients']" id="assign-sc" ref="assign-sc" ok-only ok-title="Assign"
      @show="getSampleCollectors" @hidden="resetScData" @ok="handleScOk" centered size="sm" title="Sample Collector Assignment"
      no-close-on-backdrop>
      <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
        <form ref="form" @submit.prevent="handleSubmit(onScAssign)">
          <b-row>
            <b-col cols="12">
              <validation-provider #default="validationContext" name="sample-colletor" rules="required">
                <b-form-group label="Select Sample Collector" label-for="sample-colletor"
                  :state="getValidationState(validationContext)">
                  <v-select v-model="scData.sc_id" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="sampleCollectorOptions" :reduce="(val) => val.id" :clearable="false" input-id="sample-colletors" />
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

  </b-card>
</template>

<script>
import {
  BLink,
  BDropdown,
  BImg,
  BDropdownItem,
  BPagination,
  BTable,
  BCard,
  BCardText,
  BForm,
  BFormGroup,
  BFormInput,
  BFormInvalidFeedback,
  BButton,
  BRow,
  BCol,
  BFormCheckboxGroup,
  BOverlay,
} from "bootstrap-vue";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { required } from "@validations";
import { ref } from "@vue/composition-api";
import Ripple from "vue-ripple-directive";
import formValidation from "@core/comp-functions/forms/form-validation";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import moment from "moment";
import vSelect from "vue-select";

export default {
  components: {
    BTable,
    BPagination,
    BCard,
    BCardText,
    BForm,
    BFormGroup,
    BFormInput,
    BFormInvalidFeedback,
    BButton,
    ValidationProvider,
    ValidationObserver,
    BRow,
    BCol,
    BFormCheckboxGroup,
    BOverlay,
    vSelect,
    BDropdown,
    BDropdownItem,
    BLink,
    BImg,
  },
  directives: {
    Ripple,
  },
  props: {
    id: {
      type: [Number, String],
      defaul: null,
    },
  },
  computed: {
    role: function () {
      return this.$store.state.auth.user.role_id;
    },
    is_consultant: function () {
      return this.$store.state.auth.user.role.is_consultant;
    },
    client: function () {
      return this.id ? this.id : (this.$store.state.auth.user.role.id == 3 ? this.$store.state.auth.user.id : '');
    },
  },
  created() {
    if ([1, 17, 10, 18].includes(this.role)) {

      this.columns = [];

      this.columns.push({
        key: "patient",
        label: "Client Name",
        sortable: true,
      });

      this.columns.push({
        key: "ap_company",
        label: "AP Name",
        sortable: true,
      });

      this.columns.push({
        key: "dp_name",
        label: "DP Name",
        sortable: true,
      });

      this.columns.push({
        key: "call",
        label: "Call",
        sortable: false,
      });

      this.columns.push({
        key: "payment_status",
        label: "Payment Status",
        sortable: false,
      });

      this.columns.push({
        key: "status",
        label: "Status",
        sortable: false,
      });

      this.columns.push({
        key: "actions",
        label: "Actions",
        sortable: false,
      });

      this.columns.unshift({ key: "index", label: "Sr. No.", sortable: true });
    }
    this.getList();
  },
  data() {
    const { refFormObserver, getValidationState, resetForm, resetObserver, observer } = formValidation(
      this.resetData,
      this.resetHcData,
      this.resetDhData,
      this.resetInchargeData
    );
    return {
      pagination: {
        page: 1,
        per_page: 20,
        total: null,
      },
      moment: moment,
      item: {},
      scData: {},
      scViewData: {},
      sampleCollectorOptions: [],
      activeItem: 'todays',
      refFormObserver,
      getValidationState,
    };
  },
  setup() {
    const investigations = null;
    const loading = true;
    const sortBy = "1";
    const isSortDirDesc = false;
    const columns = [
      // { key: "patient", label: "Client name", sortable: true },
      // { key: "alliance_partner", label: "Alliance Partner", sortable: true },
      // { key: "no", label: "No", sortable: true },
      { key: "created_at", label: "Date of Advice", sortable: true },
      { key: "view_report", label: "View Report", sortable: false },
      // { key: "actions", label: "Actions", sortable: false },
      { key: "updated_at", label: "Date of Upload", sortable: true },
      { key: "reports", label: "View Document", sortable: true },
    ];

    return {
      investigations,

      loading,
      sortBy,
      isSortDirDesc,
      columns,
    };
  },
  methods: {
    async getList() {
      try {
        this.pagination.id = this.client;
        const { data } = await axios.get("investigation", {
          params: this.pagination,
        });
        this.investigations = data.investigations;
      } catch (err) {
        console.log(err);
      }
    },

    handlePaginationChange(val) {
      this.pagination.page = val;
      this.getList();
    },

    async handleDeleteInvestigation(id) {
      try {
        const data = await axios.delete(`/investigation/${id}`);
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
    viewFileDialog(item) {
      this.item = item;
      this.$refs["view-files"].show();
    },
    resetItem() {
      this.item = {};
    },
    async assignSampleCollectors(inv_id) {
      this.scData.inv_id = inv_id;
      this.$refs["assign-sc"].show();
    },
    async viewInvestigation(records){
      this.scViewData = records;
      this.$refs["view-investigation"].show();
    },
    async getSampleCollectors() {
      try {
        this.healthcoaches = [];
        const { data } = await axios.get("/samplecollector/list", {
          params: {},
        });
        this.sampleCollectorOptions = data.users;
      } catch (error) {
        console.log(error);
      }
    },
    async updateStatus(inv_id, status) {
      try {
        const data = await axios.post(`/investigation/updatestatus/${inv_id}`, {
          status: status
        });

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
    handleScOk(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.$refs.refFormObserver.handleSubmit(this.onScAssign);
    },
    async onScAssign() {
      try {
        const { data } = await axios.post("/investigation/scassignment", this.scData);
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "BellIcon",
            variant: data.success ? "success" : "danger",
          },
        });
        this.$nextTick(() => {
          this.$refs["assign-sc"].hide();
          this.scData = {};
        });
        this.getList();
      } catch (err) {
        console.log(err);
      }
    },
    setActive (menuItem) {
      this.activeItem = menuItem
      this.getList(this.activeItem);
    },
    isActive (menuItem) {
      return this.activeItem === menuItem
    },
    rowClass(item, type) {
      if (item && type === "row" && [1, 17, 10, 18].includes(this.role)) {
        if ( item.status === "reports-completed" ) {
          return "tr-completed";
        } else if (item.status === "assigned") {
          return "tr-assigned";
        } else if (item.status === "picked-up") {
          return "tr-picked-up";
        } else if (item.status === "collected") {
          return "tr-completed";
        } else {
          return "tr-pending";
        }
      } else {
        return null;
      }
    },
  },
};
</script>
<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";

.caps {
  text-transform: capitalize;
}

.badge {
  padding: 6px;
  min-width: 100px;
  line-height: normal;
}

.badge-default {
  color: #000000;
  background: #FFFFFF;
}

.tr-assigned {
  background: #b0e5e1;
}

.tr-picked-up {
  background: #ebc7a5;
  color: #000;
}

.tr-completed {
  background: #58cd5c54;
  color: #000;
}

</style>
