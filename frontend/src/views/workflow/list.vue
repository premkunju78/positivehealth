<template>
  <b-card no-body class="mb-0">
    <div class="m-2">
      <!-- Table Top -->
      <b-row>
        <!-- Per Page -->
        <!-- Search -->
        <b-col cols="12" md="6">
          <div class="d-flex align-items-center justify-content-end" v-permission="['create-users']">
            <!-- <b-form-input
                v-model="searchQuery"
                class="d-inline-block mr-1"
                placeholder="Search..."
              /> -->
            <!-- <b-button
                variant="primary"
              >
                <span class="text-nowrap">Export</span>
              </b-button> -->
          </div>
        </b-col>
      </b-row>
      <div>
        <h4>Program Details</h4>
        <table v-if="programs.length" class="table table-striped">
          <thead>
            <tr>
              <th>Program Name</th>
              <th>Plan</th>
              <th>Sessions</th>
              <th>Start Date</th>
              <th>End Date</th>
              <th v-if="$store.state.auth.user.role_id == 11">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="userprogram in programs">
              <td>
                {{ userprogram.program_name }}
              </td>
              <td>
                {{ userprogram.plan }}
              </td>
              <td>
                <div v-for="session in userprogram.sessions">
                  <p>{{ session.type }} - {{ session.no_of_sessions }}</p>
                </div>
              </td>
              <td>
                {{ userprogram.start_date }}
              </td>
              <td>
                {{ userprogram.end_date }}
              </td>
              <td v-if="$store.state.auth.user.role_id == 11">
                <b-button variant="primary" class="mb-1 mb-sm-0 mr-0 mr-sm-1" @click="updateProgramModal(userprogram)">
                  Update
                </b-button>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-if="!programs.length">
          <p>No programs found</p>
        </div>
      </div>
      <div v-permission="['create-workflows']">
        <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
          <!-- User Info: Input Fields -->
          <b-form @submit.prevent="handleSubmit(onSubmit)">
            <b-row class="d-flex justify-content-center">
              <b-col cols="20" md="12">
                <h4>Create Schedule</h4>
                <b-row class="mt-2">
                  <b-col cols="12" md="4">
                    <!-- First Name -->
                    <validation-provider #default="validationContext" name="Start Time" rules="required">
                      <b-form-group label="Schedule Time & Date" label-for="schedule-time"
                        :state="getValidationState(validationContext)">
                        <flat-pickr v-model="scheduleData.schedule_time" class="form-control"
                          :config="config.timePicker" />
                        <b-form-invalid-feedback :state="getValidationState(validationContext)">
                          {{ validationContext.errors[0] }}
                        </b-form-invalid-feedback>
                      </b-form-group>
                    </validation-provider>
                  </b-col>
                  <b-col cols="12" md="4">
                    <!-- First Name -->
                    <validation-provider #default="validationContext" name="Schedule Type" rules="required">
                      <b-form-group label="Schedule Type" label-for="schedule_type"
                        :state="getValidationState(validationContext)">
                        <v-select v-model="scheduleData.type" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                          :options="scheduleTypes" :reduce="(val) => val" :clearable="false" />
                        <b-form-invalid-feedback :state="getValidationState(validationContext)">
                          {{ validationContext.errors[0] }}
                        </b-form-invalid-feedback>
                      </b-form-group>
                    </validation-provider>
                  </b-col>
                  <b-col cols="12" md="4">
                    <!-- First Name -->
                    <validation-provider #default="validationContext" name="Title" rules="required">
                      <b-form-group label="Title" label-for="first_name">
                        <b-form-input id="title" v-model="scheduleData.title"
                          :state="getValidationState(validationContext)" trim placeholder="Schedule Title"
                          :class="errors.title ? 'is-invalid' : ''" :area-invalid="errors.title ? true : false" />
                        <b-form-invalid-feedback v-if="errors.title">
                          {{ errors.title[0] }}
                        </b-form-invalid-feedback>
                        <b-form-invalid-feedback v-else>
                          {{ validationContext.errors[0] }}
                        </b-form-invalid-feedback>
                      </b-form-group>
                    </validation-provider>
                  </b-col>
                  <b-col cols="8" md="4" sm="6" class="justify-content-start mb-1 mb-md-0">
                    <b-form-group label="Role" label-for="role">
                      <v-select v-model="scheduleData.role" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                        :options="roles" :reduce="(val) => val.id" :clearable="true" />
                    </b-form-group>
                  </b-col>
                  <b-col cols="8" md="4" sm="6" class="justify-content-start mb-1 mb-md-0">
                    <b-form-group label="Instruction" label-for="instruction">
                      <v-select v-model="scheduleData.instruction" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                        :options="instructions" :reduce="(val) => val.id" :clearable="true" />
                    </b-form-group>
                  </b-col>
                  <!-- <b-col cols="12" md="4">
                    <validation-provider
                      #default="validationContext"
                      name="Description"
                      rules=""
                    >
                      <b-form-group label="Description" label-for="description">
                        <b-form-input
                          id="description"
                          v-model="scheduleData.description"
                          :state="getValidationState(validationContext)"
                          trim
                          placeholder="Schedule Description"
                          :class="errors.description ? 'is-invalid' : ''"
                          :area-invalid="errors.description ? true : false"
                        />
                        <b-form-invalid-feedback v-if="errors.description">
                          {{ errors.description[0] }}
                        </b-form-invalid-feedback>
                        <b-form-invalid-feedback v-else>
                          {{ validationContext.errors[0] }}
                        </b-form-invalid-feedback>
                      </b-form-group>
                    </validation-provider>
                  </b-col> -->
                </b-row>
                <b-form-group label="Assign To">
                  <b-row>
                    <b-col cols="12" md="2">
                      <b-form-radio v-model="scheduleData.assignedto" name="assignedto" value="self"
                        @change="handleAssignTo">Self</b-form-radio>
                    </b-col>
                    <b-col cols="12" md="2">
                      <b-form-radio v-model="scheduleData.assignedto" name="assignedto" value="incharge"
                        @change="handleAssignTo">Incharge</b-form-radio>
                    </b-col>
                    <b-col cols="12" md="2">
                      <b-form-radio v-model="scheduleData.assignedto" name="assignedto" value="consultant"
                        @change="handleAssignTo">
                        League Consultant
                      </b-form-radio>
                    </b-col>
                    <b-col cols="12" md="2">
                      <b-form-radio v-model="scheduleData.assignedto" name="assignedto" value="internalconsultant"
                        @change="handleAssignTo">
                        Internal Consultant
                      </b-form-radio>
                    </b-col>
                    <b-col cols="12" md="2" v-if="$store.state.auth.user.role_id != 11">
                      <b-form-radio v-model="scheduleData.assignedto" name="assignedto" value="healthcoach"
                        @change="handleAssignTo">Healthcoach</b-form-radio>
                    </b-col>
                  </b-row>
                  <b-form-invalid-feedback v-if="errors.assignedto">
                    {{ errors.assignedto[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>

                <b-row v-if="scheduleData.assignedto != 'self'">
                  <b-col cols="12" md="4">
                    <validation-provider #default="validationContext" name="assignedto" rules="required">
                      <b-form-group label="Assign To" label-for="assignedto"
                        :state="getValidationState(validationContext)">
                        <v-select v-model="scheduleData.user_id" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                          :options="userlist" :reduce="(val) => val.id" :clearable="false" input-id="assignedto"
                          @input="getClientlist" />
                        <b-form-invalid-feedback :state="getValidationState(validationContext)">
                          {{ validationContext.errors[0] }}
                        </b-form-invalid-feedback>
                      </b-form-group>
                    </validation-provider>
                  </b-col>
                  <b-col cols="12" md="4">
                    <validation-provider #default="validationContext" name="assignedtoclients">
                      <b-form-group label="Assign To Other Clients" label-for="assignedtoclients"
                        :state="getValidationState(validationContext)">
                        <v-select v-model="scheduleData.clients" :multiple="true"
                          :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="clientlist"
                          :reduce="(val) => val.id" :clearable="false" input-id="assignedtoclients" />
                        <b-form-invalid-feedback :state="getValidationState(validationContext)">
                          {{ validationContext.errors[0] }}
                        </b-form-invalid-feedback>
                      </b-form-group>
                    </validation-provider>
                  </b-col>
                </b-row>
              </b-col>
            </b-row>
            <b-row>
              <b-col cols="12" class="mt-2">
                <!-- Action Buttons -->
                <router-link :to="{ name: 'coordinators' }">
                  <b-button variant="outline-secondary" type="reset" class="mb-1 mb-sm-0 mr-0 mr-sm-1"
                    :block="$store.getters['app/currentBreakPoint'] === 'xs'">
                    Back
                  </b-button>
                </router-link>

                <b-button variant="primary" class="mb-1 mb-sm-0 mr-0 mr-sm-1"
                  :block="$store.getters['app/currentBreakPoint'] === 'xs'" type="submit">
                  Save Changes
                </b-button>
              </b-col>
            </b-row>
          </b-form>
        </validation-observer>
      </div>
      <h4>Schedule Details</h4>
      <b-table style="min-height: 250px; margin-top: 20px" ref="refRolesTable" class="position-relative" :items="list"
        responsive :fields="columns" :totalRows="pagination.total" primaryKey="index" :sort-by.sync="sortBy" show-empty
        empty-text="No matching records found" :sort-desc.sync="isSortDirDesc">
        <!-- Column: Actions -->
        <template #cell(index)="data">
          {{ data.index + 1 }}
        </template>
        <template #cell(schedule_datettime)="data">
          {{ moment(data.item.schedule_datettime).format("hh:mm A DD-MM-YYYY") }}
        </template>
        <template #cell(status)="data">
          <b-form-group label="" label-cols="10" class="status_Text">
            {{ data.item.status }}
          </b-form-group>
        </template>
        <template #cell(actions)="data">
          <b-dropdown variant="link" no-caret v-permission="['view-workflows']">
            <template #button-content>
              <feather-icon icon="MoreVerticalIcon" size="16" class="align-middle text-body" />
            </template>
            <b-dropdown-item @click="viewSchedule(data.item)" v-permission="['view-workflows']">
              <feather-icon icon="EyeIcon" />
              <span class="align-middle ml-50">View</span>
            </b-dropdown-item>
            <b-dropdown-item @click="viewAssignModal(data.item)" v-permission="['view-workflows']" v-if="$store.state.auth.user.detail &&
              $store.state.auth.user.detail.incharge == '1' &&
              data.item.status != 'Completed'
              ">
              <feather-icon icon="EyeIcon" />
              <span class="align-middle ml-50">Assign</span>
            </b-dropdown-item>
            <b-dropdown-item @click="editSchedule(data.item)" v-permission="['create-workflows']">
              <feather-icon icon="EditIcon" />
              <span class="align-middle ml-50">Edit</span>
            </b-dropdown-item>
          </b-dropdown>
        </template>
      </b-table>
      <b-pagination v-if="pagination.total" v-model="pagination.page" :page.sync="pagination.page"
        :total-rows="pagination.total" :index="1" :per-page="pagination.per_page" aria-controls="package-row"
        align="right" @change="handlePaginationChange">
      </b-pagination>
    </div>
    <b-modal id="schedule-modal" hide-footer>
      <template #modal-title> Schedule Info </template>
      <div class="d-block text-left">
        <div class="mb-2">
          <label>Title: </label>
          <div>{{ schedule.title }}</div>
        </div>
        <!-- <div class="mb-2">
          <label>Description: </label>
          <div>
            {{ schedule.description }}
          </div>
        </div> -->
        <div class="mb-2">
          <label>Type: </label>
          <div>{{ schedule.type }}</div>
        </div>
        <div class="mb-2">
          <label>Schedule Time & Date: </label>
          <div>
            {{ moment(schedule.schedule_datettime).format("DD-MM-YYYY hh:mm A") }}
          </div>
        </div>
        <div class="mb-2">
          <label>Client Name: </label>
          <div>
            {{ schedule.client_name }}
          </div>
        </div>
        <div class="mb-2">
          <label>Assigned To: </label>
          <div>
            {{ schedule.assignedto }}
          </div>
        </div>
        <div class="mb-2">
          <label>Instructions: </label>
          <div v-html="schedule.instruction">
          </div>
        </div>
      </div>
      <b-button class="mt-3" block @click="$bvModal.hide('schedule-modal')">Close Me</b-button>
    </b-modal>


    <b-modal v-permission="['view-clients']" id="assign" ref="assign" ok-only ok-title="Assign" @show="getConsultants"
      @hidden="resetData" @ok="handleOk" centered size="lg" title="Consultant Assignment" no-close-on-backdrop>
      <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
        <form ref="form" @submit.prevent="handleSubmit(onAssign)">
          <b-row>
            <b-col cols="12" md="12">
              <validation-provider #default="validationContext" name="consultant" rules="required">
                <b-form-group label="" label-for="consultant" :state="getValidationState(validationContext)">
                  <v-select v-model="workflowData.consultant_id" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="consultants" :reduce="(val) => val.id" :clearable="false" input-id="consultants" />
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


    <b-modal v-permission="['view-clients']" id="update_program_modal" ref="update_program_modal" ok-only
      ok-title="Update" @hidden="resetuserPrgmData" @ok="handleUpdatePrgmOk" centered size="lg" title="Update Details"
      no-close-on-backdrop>
      <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
        <form ref="form" @submit.prevent="handleSubmit(onUpdatePrgm)">
          <validation-provider #default="validationContext" name="start_date" rules="required">
            <b-form-group label="Start Date" :state="getValidationState(validationContext)">
              <b-form-datepicker id="program_start_date" v-model="userprogram.start_date" class="d-inline-block"
                :reset-button="true"></b-form-datepicker>
              <b-form-invalid-feedback :state="getValidationState(validationContext)">
                {{ validationContext.errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>
          <validation-provider #default="validationContext" name="healthcoach" rules="required">
            <b-form-group label="End Date" :state="getValidationState(validationContext)">
              <b-form-datepicker id="program_end_date" v-model="userprogram.end_date" class="d-inline-block"
                :reset-button="true"></b-form-datepicker>
              <b-form-invalid-feedback :state="getValidationState(validationContext)">
                {{ validationContext.errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>
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
  BFormDatepicker,
  BPagination
} from "bootstrap-vue";
import { required, email } from "@validations";
import formValidation from "@core/comp-functions/forms/form-validation";
import vSelect from "vue-select";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { ref } from "@vue/composition-api";
import flatPickr from "vue-flatpickr-component";
import Ripple from "vue-ripple-directive";
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";
import interactionPlugin from "@fullcalendar/interaction";

import { validateSize } from "@core/utils/utils";

import VoerroTagsInput from "@voerro/vue-tagsinput";

require("@voerro/vue-tagsinput/dist/style.css");

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import moment from "moment";
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
    FullCalendar,
    BDropdown,
    BDropdownItem,
    BFormDatepicker,
    BPagination
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
  data() {
    return {
      tabId: "listview",
      required,
      email,
      errors: [],
      consultants: [],
      incharges: [],
      healthcoaches: [],
      userlist: [],
      clientlist: [],
      roles: [],
      list: [],
      programs: [],
      instructions: [],
      schedule: {},
      scheduleTypes: [
        "Call",
        "Video assignment",
        "Assessment",
        // "Case sheet",
        "Review",
        //"Live consultation",
        "Diet plan",
        "Video consultation",
        "Training session",
      ],
      config: {
        timePicker: {
          wrap: true,
          enableTime: true,
          enableSeconds: true,
          // noCalendar: true,
          dateFormat: "d-m-Y h:i K",
        },
      },
      calendarOptions: {
        plugins: [dayGridPlugin, interactionPlugin, timeGridPlugin, listPlugin],
        initialView: "dayGridMonth",
        eventClick: this.handleEventClick,
        events: [
          { title: "event 1", date: "2022-07-01" },
          { title: "event 2", date: "2022-07-02" },
        ],
      },
      workflowData: {},
      moment: moment,
    };
  },
  setup(props) {
    const columns = [
      { key: "index", label: "Sr. No.", sortable: true },
      { key: "schedule_datettime", label: "Schedule Date & Time", sortable: true },
      { key: "type", label: "Schedule Type", sortable: true },
      { key: "title", label: "Title", sortable: true },
      { key: "assignedto_user", label: "Assigned To", sortable: false },
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

    const blankScheduleData = {
      id: "",
      schedule_id: "",
      title: "",
      instruction: "",
      // description: "",
      schedule_time: "",
      type: "",
      user_id: "",
      assignedto: "self",
      role: "",
      status: "Pending",
      clients: []
    };

    const scheduleData = ref(JSON.parse(JSON.stringify(blankScheduleData)));
    const resetscheduleData = () => {
      scheduleData.value = JSON.parse(JSON.stringify(blankScheduleData));
    };

    const { refFormObserver, getValidationState, resetForm } = formValidation(
      resetscheduleData
    );
    return {
      columns,
      pagination,
      scheduleData,
      refFormObserver,
      getValidationState,
      resetForm,
    };
  },
  computed: {
    createworkflow: function () {
      if (
        this.$store.state.auth.user.role_id > 3 &&
        this.$store.state.auth.user.detail.incharge != 1
      ) {
        return false;
      } else {
        return true;
      }
    },
  },
  created() {
    this.getList();
    this.getConsultants();
    this.getInternalConsultants();
    this.getHealthcoaches();
    this.getIncharges();
    this.getRoles();
    this.getPrograms();
    this.geInstructions();
  },

  methods: {
    validateSize,
    async onSubmit() {
      try {
        this.errors = [];
        let data;
        let scheduleData = new FormData();
        scheduleData.append("id", this.scheduleData.id);
        scheduleData.append("schedule_time", this.scheduleData.schedule_time);
        scheduleData.append("type", this.scheduleData.type);
        scheduleData.append("title", this.scheduleData.title);
        scheduleData.append("instruction", this.scheduleData.instruction);
        // scheduleData.append("description", this.scheduleData.description);
        scheduleData.append("user_id", this.scheduleData.user_id);
        scheduleData.append("assignedto", this.scheduleData.assignedto);
        scheduleData.append("client_id", this.id);
        scheduleData.append("clients", this.scheduleData.clients);
        scheduleData.append("role", this.scheduleData.role);
        scheduleData.append("status", this.scheduleData.status);
        if (this.schedule_id) {
          data = await axios.post(`/workflowschedules/${this.id}`, scheduleData, {
            headers: {
              "content-type": "multipart/form-data",
            },
          });
        } else {
          data = await axios.post("/workflowschedules", scheduleData, {
            headers: {
              "content-type": "multipart/form-data",
            },
          });
        }
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.data.message,
            icon: "EditIcon",
            variant: data.data.success ? "success" : "info",
          },
        });
        this.resetForm();
        this.$emit("change");
        //this.$router.replace(`/workflow/${this.id}/list`);
        this.getList();
      } catch (error) {
        if (error.response) {
          this.errors = error.response.data.errors;
        }
      }
    },
    async viewSchedule(schedule) {
      this.type = "Update Affirmation";
      this.schedule = schedule;
      this.$bvModal.show("schedule-modal");
    },
    async getList() {
      try {
        this.loading = true;
        this.pagination.filters.client_id = this.id;
        const { data } = await axios.get("workflowschedules", {
          params: this.pagination,
        });
        if (data.schedules) {
          this.list = data.schedules;
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

    async getInternalConsultants() {
      try {
        this.internalconsultants = [];
        var params = { internal: "1" };
        const { data } = await axios.get("/consultants/list", {
          params: params,
        });
        this.internalconsultants = data.users;
      } catch (error) {
        console.log(error);
      }
    },
    async getConsultants() {
      try {
        this.consultants = [];
        var params = {};
        if (
          this.$store.state.auth.user.role_id > 3 &&
          this.$store.state.auth.user.detail.incharge == 1
        ) {
          var params = { incharge: this.$store.state.auth.user.id };
        }
        const { data } = await axios.get("/consultants/list", {
          params: params,
        });
        this.consultants = data.users;
      } catch (error) {
        console.log(error);
      }
    },
    async getClientlist() {
      try {
        this.clientlist = [];
        var params = { consultant: [this.scheduleData.user_id] };
        const { data } = await axios.get("/client/list", {
          params: params,
        });
        this.clientlist = data.clients;
      } catch (error) {
        console.log(error);
      }
    },
    async getHealthcoaches() {
      try {
        this.healthcoaches = [];
        const { data } = await axios.get("/healthcoaches/list", {
          params: {},
        });
        this.healthcoaches = data.users;
      } catch (error) {
        console.log(error);
      }
    },
    async getIncharges() {
      try {
        this.incharges = [];
        const { data } = await axios.get("/incharges/list", {
          params: {},
        });
        this.incharges = data.users;
      } catch (error) {
        console.log(error);
      }
    },
    handleAssignTo() {
      this.userlist = [];
      this.clientlist = [];
      this.scheduleData.user_id = "";
      if (this.scheduleData.assignedto === "consultant") {
        this.userlist = this.consultants;
      } else if (this.scheduleData.assignedto === "internalconsultant") {
        this.userlist = this.internalconsultants;
      } else if (this.scheduleData.assignedto === "healthcoach") {
        this.userlist = this.healthcoaches;
      } else if (this.scheduleData.assignedto === "incharge") {
        this.userlist = this.incharges;
      }
    },
    async updatestatus(id, status) {
      try {
        const { data } = await axios.post(
          "/workflowschedules/updatestatus",
          { id: id, status: status },
          {}
        );
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
    async getRoles() {
      try {
        const { data } = await axios.get("/roles/list?allusers=1");
        this.roles = data.roles;
        console.log(this.roles);
      } catch (err) {
        console.log(err);
      }
    },
    async getPrograms() {
      try {
        this.programs = [];
        const { data } = await axios.get("/client/programlist/" + this.id, {
          params: {},
        });
        this.programs = data.userprograms;
      } catch (error) {
        console.log(error);
      }
    },
    async geInstructions() {
      try {
        this.programs = [];
        const { data } = await axios.get("/client/instructions/", {
          params: {},
        });
        this.instructions = data.instructions;
      } catch (error) {
        console.log(error);
      }
    },
    editSchedule(schedule) {
      this.scheduleData = schedule;
      this.scheduleData.schedule_time = moment(schedule.schedule_datettime).format("DD-MM-YYYY hh:mm A");
      // if (schedule.user_id == this.$store.state.auth.user.id) {
      //   this.scheduleData.assignedto = "self";
      // } else if (schedule.role == "11") {
      //   this.scheduleData.assignedto = "healthcoach";
      //   this.userlist = this.healthcoaches;
      // } else if (schedule.role == "4" && schedule.incharge == "1") {
      //   this.scheduleData.assignedto = "incharge";
      //   this.userlist = this.incharges;
      // } else if (schedule.role == "4" && schedule.incharge == "0") {
      //   this.scheduleData.assignedto = "consultant";
      //   this.userlist = this.consultants;
      // }
      
      if (schedule.assignedto == "incharge") {
        this.userlist = this.incharges;
      } else if (schedule.assignedto == "consultant") {
        this.userlist = this.consultants;
      }
      this.scheduleData.user_id = schedule.user_id;
      this.getClientlist();
    },
    viewAssignModal(workflow) {
      this.workflow = workflow;
      this.$refs["assign"].show();
    },
    async getConsultants() {
      try {
        this.consultants = [];
        var params = {};
        if (
          this.$store.state.auth.user.role_id > 3 &&
          this.$store.state.auth.user.detail &&
          this.$store.state.auth.user.detail.incharge == 1
        ) {
          var params = { incharge: this.$store.state.auth.user.id };
        }
        const { data } = await axios.get("/consultants/list", {
          params: params,
        });
        this.consultants = data.users;
      } catch (error) {
        console.log(error);
      }
    },
    handleOk(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.$refs.refFormObserver.handleSubmit(this.onAssign);
    },
    async onAssign() {
      try {
        this.workflowData.id = this.workflow.id;
        const { data } = await axios.post(
          "/workflowschedules/assign_consultant",
          this.workflowData
        );
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
    updateProgramModal(userprogram) {
      this.userprogram = userprogram;
      this.$refs["update_program_modal"].show();
    },
    handleUpdatePrgmOk(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.$refs.refFormObserver.handleSubmit(this.onUpdatePrgm);
    },
    async onUpdatePrgm() {
      try {
        const { data } = await axios.post(
          "/client/update_userprogram",
          this.userprogram
        );
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
          this.$refs["update_program_modal"].hide();
        });
      } catch (err) {
        console.log(err);
      }
    },
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

.status_Text .col {
  white-space: nowrap;
  font-size: 13px;
  vertical-align: middle;
}

.status_Text .col span {
  vertical-align: super;
}
</style>
