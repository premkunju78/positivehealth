<template>
  <b-card no-body class="mb-0">

    <div class="m-2">

      <!-- 
      <ul class="nav nav-tabs nav-justified">
        <li class="nav-item">
          <a class="nav-link" @click.prevent="setActive('queries')" :class="{ active: isActive('queries') }" href="#queries">Queries</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" @click.prevent="setActive('enquiries')" :class="{ active: isActive('enquiries') }" href="#enquiries">Enquiries</a>
        </li>
      </ul>
      -->
    
      <div class="tab-content py-3" id="myTabContent">
        <div class="tab-pane fade" :class="{ 'active show': isActive('queries') }" id="queries">
          <!-- Table Top -->
          <div v-permission="['create-enquiries']">
            <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
              <!-- User Info: Input Fields -->
              <b-form @submit.prevent="handleSubmit(onSubmit)">
                <b-row class="d-flex justify-content-center">
                  <b-col cols="20" md="12">
                    <h4>Enquiry Details</h4>
                    <!-- First Name -->
                    <validation-provider #default="validationContext" name="Enquiry Details" rules="required">
                      <quill-editor id="description" v-model="enquiryData.enquiry_detail" :options="editorOption" rows="4" />
                      <b-form-invalid-feedback :state="getValidationState(validationContext)">
                        {{ validationContext.errors[0] }}
                      </b-form-invalid-feedback>
                    </validation-provider>
                  </b-col>
                </b-row>
                <b-row>
                  <b-col cols="12" class="mt-2">
                    <b-button variant="primary" class="mb-1 mb-sm-0 mr-0 mr-sm-1"
                      :block="$store.getters['app/currentBreakPoint'] === 'xs'" type="submit">
                      Save Changes
                    </b-button>
                  </b-col>
                </b-row>
              </b-form>
            </validation-observer>
          </div>
          <b-table style="min-height: 250px; margin-top: 20px" ref="refRolesTable" class="position-relative" :items="list"
            responsive :fields="computedFields" :totalRows="pagination.total" primaryKey="index" :sort-by.sync="sortBy"
            show-empty empty-text="No matching records found" :sort-desc.sync="isSortDirDesc" :tbody-tr-class="rowClass">
            <!-- Column: Actions -->
            <template #cell(index)="data">
              {{ data.index + 1 }}
            </template>

            <template #cell(enquiry_detail)="data">
              <div v-html="data.item.enquiry_detail"></div>
            </template>

            <template #cell(call_btn)="data">
              <b-button variant="success" class="btn btn-primary ml-2" @click="callNow(data.item)">
                <span class="text-nowrap">Call Now</span>
              </b-button>
            </template>

            <template #cell(client_name)="data">
              <div v-html="data.item.client_name"></div>
            </template>

            <template #cell(hc_name)="data">
              <div v-html="data.item.hc_name"></div>
            </template>
            <template #cell(created_at)="data">
              {{ moment(created_at).format('DD-MM-YYYY') }}
            </template>
            

            <template #cell(status)="data" class="d-flex align-items-center">
              <b-form-group label="" label-cols="10" v-permission="['edit-enquiries']">
                <label class="m-0">Open</label>
                <b-form-checkbox v-model="data.item.status" value="Closed" name="check-button" switch
                  @change="updatestatus(data.item.id)" class="mr-1 ml-1 mt-0" inline />
                <label class="m-0">Closed</label>
              </b-form-group>
                <b-button variant="default" class="status">{{ data.item.status }}</b-button>
            </template>
            <template #cell(actions)="data">
              <b-dropdown variant="link" no-caret v-permission="['view-enquiries']">
                <template #button-content>
                  <feather-icon icon="MoreVerticalIcon" size="16" class="align-middle text-body" />
                </template>
                <b-dropdown-item @click="viewEnquiry(data.item)" v-permission="['view-enquiries']">
                  <feather-icon icon="EyeIcon" />
                  <span class="align-middle ml-50">View</span>
                </b-dropdown-item>
                <b-dropdown-item @click="viewAssignUserRolesModal(data.item)" v-if="role == 1">
                  <feather-icon icon="EyeIcon" />
                  <span class="align-middle ml-50">Assign</span>
                </b-dropdown-item>
              </b-dropdown>
            </template>
          </b-table>
          <b-pagination v-if="pagination.total" v-model="pagination.page" :page.sync="pagination.page"
            :total-rows="pagination.total" :index="1" :per-page="pagination.per_page" aria-controls="package-row"
            align="right" @change="handlePaginationChange">
          </b-pagination>      
        </div>

        <div class="tab-pane fade" :class="{ 'active show': isActive('enquiries') }" id="enquiries">
          <!-- Table Top -->
          <div v-permission="['create-enquiries']">
            <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
              <!-- User Info: Input Fields -->
              <b-form @submit.prevent="handleSubmit(onSubmit)">
                <b-row class="d-flex justify-content-center">
                  <b-col cols="20" md="12">
                    <h4>Enquiry Details</h4>
                    <!-- First Name -->
                    <validation-provider #default="validationContext" name="Enquiry Details" rules="required">
                      <quill-editor id="description" v-model="enquiryData.enquiry_detail" :options="editorOption" rows="4" />
                      <b-form-invalid-feedback :state="getValidationState(validationContext)">
                        {{ validationContext.errors[0] }}
                      </b-form-invalid-feedback>
                    </validation-provider>
                  </b-col>
                </b-row>
                <b-row>
                  <b-col cols="12" class="mt-2">
                    <b-button variant="primary" class="mb-1 mb-sm-0 mr-0 mr-sm-1"
                      :block="$store.getters['app/currentBreakPoint'] === 'xs'" type="submit">
                      Save Changes
                    </b-button>
                  </b-col>
                </b-row>
              </b-form>
            </validation-observer>
          </div>
          <b-table style="min-height: 250px; margin-top: 20px" ref="refRolesTable" class="position-relative" :items="list"
            responsive :fields="computedFields" :totalRows="pagination.total" primaryKey="index" :sort-by.sync="sortBy"
            show-empty empty-text="No matching records found" :sort-desc.sync="isSortDirDesc" :tbody-tr-class="rowClass">
            <!-- Column: Actions -->
            <template #cell(index)="data">
              {{ data.index + 1 }}
            </template>

            <template #cell(enquiry_detail)="data">
              <div v-html="data.item.enquiry_detail"></div>
            </template>

            <template #cell(call_btn)="data">
              <b-button variant="success" class="btn btn-primary ml-2" @click="callNow(data.item)">
                <span class="text-nowrap">Call Now</span>
              </b-button>
            </template>

            <template #cell(client_name)="data">
              <div v-html="data.item.client_name"></div>
            </template>

            <template #cell(hc_name)="data">
              <div v-html="data.item.hc_name"></div>
            </template>
            <template #cell(created_at)="data">
              {{ moment(created_at).format('DD-MM-YYYY') }}
            </template>            

            <template #cell(status)="data" class="d-flex align-items-center">
              <b-form-group label="" label-cols="10" v-permission="['edit-enquiries']">
                <label class="m-0">Open</label>
                <b-form-checkbox v-model="data.item.status" value="Closed" name="check-button" switch
                  @change="updatestatus(data.item.id)" class="mr-1 ml-1 mt-0" inline />
                <label class="m-0">Closed</label>
              </b-form-group>
                <b-button variant="default" class="status">{{ data.item.status }}</b-button>
            </template>
            <template #cell(actions)="data">
              <b-dropdown variant="link" no-caret v-permission="['view-enquiries']">
                <template #button-content>
                  <feather-icon icon="MoreVerticalIcon" size="16" class="align-middle text-body" />
                </template>
                <b-dropdown-item @click="viewEnquiry(data.item)" v-permission="['view-enquiries']">
                  <feather-icon icon="EyeIcon" />
                  <span class="align-middle ml-50">View</span>
                </b-dropdown-item>
                <b-dropdown-item @click="viewAssignUserRolesModal(data.item)" v-if="role == 1">
                  <feather-icon icon="EyeIcon" />
                  <span class="align-middle ml-50">Assign</span>
                </b-dropdown-item>
              </b-dropdown>
            </template>
          </b-table>
          <b-pagination v-if="pagination.total" v-model="pagination.page" :page.sync="pagination.page"
            :total-rows="pagination.total" :index="1" :per-page="pagination.per_page" aria-controls="package-row"
            align="right" @change="handlePaginationChange">
          </b-pagination>      
        </div>      
      </div>

    </div>

    <b-modal id="enquiry-modal" hide-footer>
      <template #modal-title> Enquiry Info </template>
      <div class="d-block text-left">
        <div class="mb-2" v-if="role != '3'">
          <label>Client: </label>
          <div>{{ enquiry.client_name }}</div>
        </div>
        <div class="mb-2">
          <label>Enquiry Detail: </label>
          <div v-html="enquiry.enquiry_detail"></div>
        </div>
        <div class="mb-2">
          <label>Posted at: </label>
          <div>{{ enquiry.created_at }}</div>
        </div>
        <div class="mb-2">
          <label>Status: </label>
          <div>
            {{ enquiry.status }}
          </div>
        </div>
        <div class="mb-2" v-if="role == 1">
          <label>Replied Message: </label>
          <div>
            {{ enquiry.comment }}
          </div>
        </div>
      </div>
      <div class="d-block text-left">
        <div class="mb-2">
          <textarea v-model="enquiry.comment" class="form-control" v-if="role != 1" rows="4"></textarea>
        </div>
      </div>
      <div class="d-block text-left">
        <b-button class="btn-primary" v-if="role != 1" @click="saveReply">Reply</b-button>
      </div>
    </b-modal>

    <b-modal id="assign" ref="assign" ok-only ok-title="Assign" @show="getHealthcoaches" @hidden="resetData"
      @ok="handleOk" centered size="lg" title="Healthcoach Assignment" no-close-on-backdrop>
      <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
        <form ref="form" @submit.prevent="handleSubmit(onAssign)">
          <b-row>
            <b-col cols="12" md="12">
              <validation-provider #default="validationContext" name="healthcoach" rules="required">
                <b-form-group label="Select Healthcoach" label-for="healthcoach"
                  :state="getValidationState(validationContext)">
                  <v-select v-model="assignData.healthcoach" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="healthcoaches" :reduce="val => val.id" :clearable="false" input-id="healthcoach" />
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

    <b-modal id="assignToUserRoles" ref="assign-to-user-roles" ok-only ok-title="Assign" @show="getUsersAndRoles" @hidden="resetData"
      @ok="handleOk" centered size="lg" title="Assign Query" no-close-on-backdrop>
      <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
        <form ref="form" @submit.prevent="handleSubmit(onAssign)">
          <b-row>
            <b-col cols="6" md="6">
              <validation-provider #default="validationContext" name="user_role" rules="required">
                <b-form-group label="Select User Role" label-for="user_role"
                  :state="getValidationState(validationContext)">
                  <v-select v-model="assignData.user_role" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="user_roles" :reduce="val => val.id" :clearable="false" input-id="user_role" @input="getUsersForRole" />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col cols="6" md="6">
              <validation-provider #default="validationContext" name="user" rules="required">
                <b-form-group label="Select User" label-for="user"
                  :state="getValidationState(validationContext)">
                  <v-select v-model="assignData.user" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="users" :reduce="(val) => val.id" ref="user_id" input-id="user_id" />
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
  BButton,
  BMedia,
  BAvatar,
  BRow,
  BCol,
  BFormGroup,
  BFormInput,
  BForm,
  BTable,
  BCard,
  BCardHeader,
  BCardTitle,
  BFormCheckbox,
  BFormInvalidFeedback,
  BFormFile,
  BMediaAside,
  BMediaBody,
  BLink,
  BImg,
  BFormRadioGroup,
  BFormRadio,
  BFormTextarea,
  BDropdown,
  BDropdownItem,
} from "bootstrap-vue";
import { required, email } from "@validations";
import formValidation from "@core/comp-functions/forms/form-validation";
import vSelect from "vue-select";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { ref } from "@vue/composition-api";
import flatPickr from "vue-flatpickr-component";
import Ripple from "vue-ripple-directive";
import { validateSize } from "@core/utils/utils";

import VoerroTagsInput from "@voerro/vue-tagsinput";

require("@voerro/vue-tagsinput/dist/style.css");

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import "quill/dist/quill.snow.css";
import { quillEditor } from "vue-quill-editor";
import moment from 'moment';


export default {
  components: {
    BFormRadio,
    BButton,
    BMedia,
    BAvatar,
    BRow,
    BCol,
    BFormGroup,
    BFormInput,
    BForm,
    BTable,
    BCard,
    BCardHeader,
    BCardTitle,
    BFormCheckbox,
    BFormFile,
    vSelect,
    BFormInvalidFeedback,
    BMedia,
    BMediaAside,
    BMediaBody,
    BLink,
    BImg,
    flatPickr,
    BFormRadioGroup,
    BFormTextarea,
    "tags-input": VoerroTagsInput,
    ValidationProvider,
    ValidationObserver,
    quillEditor,
    BDropdown,
    BDropdownItem,
  },
  directives: {
    Ripple,
  },
  props: {
    id: {
      type: [Number, String, null],
      default: null,
    },
  },
  computed: {
    computedFields() {
      if (this.role == 3) {
        return this.columns.filter(field => (!field.client_name && !field.hc_name));
      } else if (this.role == 6) {
        return this.columns.filter(field => !field.hc_name);
      }
      else {
        return this.columns;
      }
    },
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
      enquiry: {},
      required,
      errors: [],
      list: [],
      role: null,
      healthcoaches: [],
      user_roles: [],
      users: [],
      assignData: {},
      moment:moment,
      activeItem: 'enquiries'
    };
  },
  setup(props) {
    const columns = [
      { key: "enquiry_id", label: "Enquiry ID", sortable: true },
      { key: "client_name", label: "Name", sortable: true, client_name: true },
      { key: "client_role", label: "Role", sortable: true, client_role: true },
      { key: "enquiry_detail", label: "Enquiry Details", sortable: true },
      { key: "call_btn", label: "Call", sortable: true, hc_name: true },
      { key: "status", label: "Status", sortable: false },
      { key: "actions", label: "Actions", sortable: false },
    ];
    const pagination = {
      page: 1,
      per_page: 8,
      total: null,
      keyword: null,
      filters: {},
    };

    const blankenquiryData = {
      schedule_id: "",
      title: "",
      description: "",
      schedule_time: "",
      type: "",
      user_id: "",
      assignedto: "self",
    };

    const enquiryData = ref(JSON.parse(JSON.stringify(blankenquiryData)));
    const resetenquiryData = () => {
      enquiryData.value = JSON.parse(JSON.stringify(blankenquiryData));
    };

    const { refFormObserver, getValidationState, resetForm } = formValidation(
      resetenquiryData
    );
    return {
      columns,
      pagination,
      enquiryData,
      refFormObserver,
      getValidationState,
      resetForm,
    };
  },
  created() {
    this.getList('enquiries');
    this.getColumns();
  },

  methods: {
    validateSize,
    async onSubmit() {
      try {
        this.errors = [];
        let data;
        let type = 'enquiries';
        let enquiryData = new FormData();
        enquiryData.append("enquiry_detail", this.enquiryData.enquiry_detail);
        data = await axios.post(`/enquiries?query_type=${type}`, enquiryData, {});
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.data.message,
            icon: "EditIcon",
            variant: data.data.success ? "success" : "info",
          },
        });
        this.getList();
      } catch (error) {
        if (error.response) {
          this.errors = error.response.data.errors;
        }
      }
    },
    getColumns() {
      if (this.$store.state.auth.user.role_id == 10) {
        this.columns = [
          { key: "enquiry_id", label: "Enquiry ID", sortable: true },
          { key: "created_at", label: "Date", sortable: true },
          { key: "role_name", label: "Role", sortable: true },
          { key: "client_name", label: "Name", sortable: true, client_name: true },
          { key: "enquiry_detail", label: "Enquiry Details", sortable: true },
          { key: "call_btn", label: "Call", sortable: true, hc_name: true },
          { key: "status", label: "Status", sortable: false },
          { key: "actions", label: "Actions", sortable: false },
        ];
      }
      if (this.$store.state.auth.user.role.is_consultant == 1) {
        this.columns = [
          { key: "enquiry_id", label: "Enquiry ID", sortable: true },
          { key: "enquiry_detail", label: "Enquiry Details", sortable: true },
          { key: "status", label: "Status", sortable: false },
        ];
      }
    },
    async getList(type = 'enquiries') {
      try {
        this.loading = true;
        this.pagination.filters.client_id = this.id;
        const { data } = await axios.get(`/enquiries?query_type=${type}`, {
          params: this.pagination, 
        });
        if (data.enquiries) {
          this.list = data.enquiries;
          this.role = data.role;
          this.pagination.per_page = data.per_page; 
          this.pagination.total = data.total;
          this.pagination.page = data.page;
        }
      } catch (err) {
        console.log(err);
      }
      this.loading = false;
    },
    async handlePaginationChange(val) {
      this.pagination.page = val;
      this.getList();
    },
    async getUsersAndRoles() {
      try {
        this.user_roles = [];
        const { data } = await axios.get(`/users/roles`, {
          params: {},
        });
        this.user_roles = data.user.role_list;
      } catch (error) {
        console.log(error);
      }
    },
    async getUsersForRole() {      
      let role_id = this.assignData.user_role;
      try {
        this.users = [];
        const { data } = await axios.get(`/users/roles`, {
          params: {
            role_id: role_id
          },
        });
        this.users = data.user.user_list;
      } catch (error) {
        console.log(error);
      }      
    },
    handleAssignTo() {
      if (this.enquiryData.assignedto === "consultant") {
        this.userlist = this.consultants;
      } else if (this.enquiryData.assignedto === "healthcoach") {
        this.userlist = this.healthcoaches;
      } else if (this.enquiryData.assignedto === "incharge") {
        this.userlist = this.incharges;
      }
    },
    async viewEnquiry(enquiry) {
      this.type = "View Enquiry";
      this.enquiry = enquiry;
      this.$bvModal.show("enquiry-modal");
    },
    async updatestatus(id) {
      try {
        const { data } = await axios.post("/enquiries/updatestatus", { id: id }, {});
        this.getList();
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "EditIcon",
            variant: data.success ? "success" : "info",
          },
        });
      } catch (error) {
        if (error.response) {
          this.errors = error.response.data.errors;
        }
      }
    },
    viewAssignModal(enquiry) {
      this.enquiry = enquiry;
      this.$refs['assign'].show();
    },
    viewAssignUserRolesModal(enquiry) {
      this.enquiry = enquiry;
      this.$refs['assign-to-user-roles'].show();
    },
    handleOk(bvModalEvt) {
      bvModalEvt.preventDefault()
      this.$refs.refFormObserver.handleSubmit(this.onAssign);
    },
    async onAssign() {
      try {
        this.assignData.enquiry_id = this.enquiry.id;
        const { data } = await axios.post('/enquiries/hcassignment', this.assignData);

        this.getList();
        this.$refs['assign-to-user-roles'].hide();

        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "BellIcon",
            variant: data.success ? "success" : "danger"
          }
        });

        this.$nextTick(() => {
          this.$refs['assign'].hide()
        })
      } catch (err) {
        console.log(err)
      }
    },
    resetData() {
      this.assignData = {
        healthcoach: "",
      }
    },
    isActive (menuItem) {
      return this.activeItem === menuItem
    },
    setActive (menuItem) {
      
      if(menuItem == 'queries') {
        this.getList('queries');
      }

      if(menuItem == 'enquiries') {
        this.getList('enquiries');        
      }

      this.activeItem = menuItem
    },
    async saveReply() {
      try {
        const { data } = await axios.post(`/enquiries/update`, {
          params: {
            enquiry_id: this.enquiry.id, 
            enquiry_comment: this.enquiry.comment
          },
        });

        this.getList('enquiries');
        this.$bvModal.hide("enquiry-modal");        

        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "AlertTriangleIcon",
            variant: data.success ? "success" : "danger",
          },
        });
        
      } catch (error) {
        console.log(error);
      }
    },
    rowClass (item, type) {
      if(item && type === 'row') {
        if(item.replied == 0 && item.assign_to == 0) {
          return 'box-danger';
        }else if(item.replied == 0 && item.assign_to != 0){
          return 'box-warning';
        }else if(item.replied != 0 && item.assign_to != 0){
          return 'box-success';
        }else{
          return null;
        }
      }else{
        return null;
      }
    }
  },
};
</script>
<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";
@import "@core/scss/vue/libs/vue-flatpicker.scss";

.custom-radio {
  margin-top: 0 !important;
}

.eventDate {
  background: #0d1570;
  color: #fff;
  padding: 13px;
  border-radius: 3px;
}

span.badge.badge-primary.small {
  font-size: 10px;
}

tr.box-danger button.status {
  background: #f00!important;
  color: #ffffff!important;
  min-width: 134px!important;
}

tr.box-success button.status {
  background: #008000!important;
  color: #000000!important;
  min-width: 134px!important;
}

tr.box-warning button.status {
  background: #FFFF00!important;
  color: #000000!important;
  min-width: 134px!important;
}

</style>
