<template>
  <b-card no-body class="mb-0">
    <div class="m-2">
      <!-- Table Top -->
      <b-row>
        <!-- Per Page -->
        <b-col cols="12" md="6" class="d-flex align-items-center justify-content-start mb-1 mb-md-0" v-if="$store.state.auth.user.role_id != '11' &&
          $store.state.auth.user.role_id != '3'
          ">
          <ul class="nav nav-tabs nav-justified" id="myTab2" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="profile-tab-justified"> List</a>
            </li>
            <li class="nav-item">
              <router-link :to="{ name: 'workFlowCalendar', params: { id: this.id } }">
                <a class="" id="profile-tab-justified">Calendar</a>
              </router-link>
            </li>
          </ul>
        </b-col>
      </b-row>
      <b-table style="min-height: 250px; margin-top: 20px" ref="refRolesTable" class="position-relative" :items="list"
        responsive :fields="columns" :totalRows="pagination.total" primaryKey="index" :sort-by.sync="sortBy" show-empty
        empty-text="No matching records found" :sort-desc.sync="isSortDirDesc">
        <!-- Column: Actions -->
        <template #cell(index)="data">
          {{ data.index + 1 }}
        </template>
        <template #cell(status)="data">
          <b-form-group label="" label-cols="10" class="status_Text" v-if="data.item.user_id == user_id">
            <b-dropdown variant="link" no-caret :right="$store.state.appConfig.isRTL">
              <template #button-content>
                {{ data.item.status }}
              </template>
              <b-dropdown-item @click="updatestatus(data.item.id, 'Pending')">
                <span class="align-middle ml-50">Pending</span>
              </b-dropdown-item>
              <b-dropdown-item @click="updatestatus(data.item.id, 'Completed')">
                <span class="align-middle ml-50">Completed</span>
              </b-dropdown-item>
              <b-dropdown-item @click="updatestatus(data.item.id, 'No Show')">
                <span class="align-middle ml-50">No Show</span>
              </b-dropdown-item>
            </b-dropdown>
          </b-form-group>
          <span v-else> {{ data.item.status }} </span>
        </template>
        <template #cell(rating)="data">
          <div v-if="role_id == 3">
            <star-rating v-if="data.item.rating == null && data.item.status == 'Completed'" :rating="data.item.rating"
              :max-rating="10" :star-size="20" v-model="data.item.rating"
              @rating-selected="setRating(data.item)"></star-rating>
          </div>
          <div v-else>
            <star-rating :read-only="true" :rating="data.item.rating" :max-rating="10" :star-size="20"
              v-model="data.item.rating" @rating-selected="setRating(data.item)"></star-rating>
          </div>
        </template>
        <template #cell(video)="data">
          <b-button variant="info" v-if="!data.item.host_link && data.item.user_id == user_id
            //  &&
            // new Date(data.item.schedule_datettime).getTime() >= new Date().getTime()
            " @click="generateVideoLink(data.item)">
            <span class="text-nowrap">Create Meeting</span>
          </b-button>
          <a target="_blank" v-if="data.item.host_link" :href="data.item.client_id == user_id
            ? data.item.participant_link
            : data.item.host_link
            ">
            <b-button variant="info">
              <span class="text-nowrap primary">Join Now</span>
            </b-button>
          </a>
        </template>
        <template #cell(instruction)="data">
          <router-link v-if="data.item.instruction" target="_blank" :to="'/view/instruction/' + data.item.instruction">
            <b-button variant="primary">
              <span variant="primary" class="text-nowrap">Instruction</span>
            </b-button>
          </router-link>
        </template>
        <!-- Column: Voice -->
        <template #cell(voice)="data">
          <b-button variant="success" @click="callNow(data.item)">
            <span class="text-nowrap">Call Now</span>
          </b-button>
        </template>
        <template #cell(schedule_datettime)="data">
          {{ moment(data.item.schedule_datettime).format("DD-MM-YYYY hh:mm A") }}
        </template>
        <template #cell(actions)="data">
          <b-dropdown variant="link" no-caret :right="$store.state.appConfig.isRTL" v-permission="['view-workflows']">
            <template #button-content>
              <feather-icon icon="MoreVerticalIcon" size="16" class="align-middle text-body"
                v-permission="['view-workflows']" />
            </template>
            <b-dropdown-item @click="viewSchedule(data.item)" v-permission="['view-workflows']">
              <feather-icon icon="EyeIcon" />
              <span class="align-middle ml-50">View</span>
            </b-dropdown-item>
            <b-dropdown-item @click="sendMessageModal(data.item)" v-permission="['view-workflows']">
              <feather-icon icon="EyeIcon" />
              <span class="align-middle ml-50">Send Message</span>
            </b-dropdown-item>
            <b-dropdown-item @click="viewAssignModal(data.item)" v-permission="['view-workflows']" v-if="$store.state.auth.user.detail &&
              $store.state.auth.user.detail.incharge == '1' &&
              data.item.status != 'Completed'
              ">
              <feather-icon icon="EyeIcon" />
              <span class="align-middle ml-50">Assign</span>
            </b-dropdown-item>
            <b-dropdown-item @click="viewRescheduleModal(data.item)" v-permission="['view-workflows']"
              v-if="data.item.status == 'No Show'">
              <feather-icon icon="EyeIcon" />
              <span class="align-middle ml-50">Re-Schedule</span>
            </b-dropdown-item>
            <b-dropdown-item @click="viewmessageModal(data.item)" v-permission="['view-workflows']">
              <feather-icon icon="EyeIcon" />
              <span class="align-middle ml-50">View Message</span>
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
    <b-modal id="send-message" hide-footer>
      <template #modal-title> Send Message </template>
      <div class="d-block text-left">
        <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
          <!-- User Info: Input Fields -->
          <b-form @submit.prevent="handleSubmit(onSubmit)">
            <b-row class="d-flex justify-content-center">
              <b-col cols="20" md="12">
                <!-- First Name -->
                <validation-provider #default="validationContext" name="Message" rules="required">
                  <b-form-group label="Message" label-for="message">
                    <b-form-input id="message" v-model="messageData.message"
                      :state="getValidationState(validationContext)" trim placeholder="Message"
                      :class="errors.message ? 'is-invalid' : ''" :area-invalid="errors.message ? true : false" />
                    <b-form-invalid-feedback v-if="errors.message">
                      {{ errors.message[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-else>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
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
    </b-modal>
    <b-modal id="view-message" hide-footer>
      <div class="d-block text-left">
        <table class="table">
          <thead>
            <tr>
              <th>Message</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="message in messages" :key="message.id">
              <td>
                {{ message.message }}
              </td>
            </tr>
          </tbody>
        </table>
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

    <b-modal v-permission="['view-clients']" id="reschedule" ref="reschedule" ok-only ok-title="Reschedule"
      @hidden="resetRescheduleData" @ok="handleRescheduleOk" centered size="lg" title="Reschedule  Assignment"
      no-close-on-backdrop>
      <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
        <form ref="form" @submit.prevent="handleSubmit(onReschedule)">
          <b-row>
            <b-col cols="12" md="12">
              <validation-provider #default="validationContext" name="consultant" rules="required">
                <b-form-group label="Shedule Time" label-for="schedule-time"
                  :state="getValidationState(validationContext)">
                  <flat-pickr v-model="scheduleData.schedule_time" class="form-control" :config="config.timePicker" />
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
  BFormTextarea,
  BDropdown,
  BDropdownItem,
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
import StarRating from "vue-star-rating";
import moment from "moment";
export default {
  components: {
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
    StarRating,
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
      errors: [],
      list: [],
      schedule: {},
      messages: [],
      messageData: {
        message: "",
      },
      workflowData: {},
      workflow: {},
      consultants: [],
      config: {
        timePicker: {
          wrap: true,
          enableTime: true,
          enableSeconds: true,
          // noCalendar: true,
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
      scheduleData: {},
      moment: moment,
    };
  },
  setup(props) {
    const columns = [
      { key: "index", label: "Sr. No.", sortable: true },
      { key: "schedule_datettime", label: "Schedule Time", sortable: true },
      { key: "type", label: "Schedule Type", sortable: true },
      { key: "title", label: "Title", sortable: true },
      // { key: "description", label: "Description", sortable: false },
      { key: "client_name", label: "Client Name", sortable: false },
      // { key: "assignedto", label: "Assigned To", sortable: false },
      { key: "status", label: "Status", sortable: false },
      { key: "rating", label: "Rating", sortable: false },
      { key: "voice", label: "Call", sortable: false },
      { key: "video", label: "Video Call", sortable: false },
      { key: "actions", label: "Actions", sortable: false },
    ];

    const pagination = {
      page: 1,
      per_page: 8,
      total: null,
      keyword: null,
      filters: {},
    };
    const { refFormObserver, getValidationState, resetForm } = formValidation();

    return {
      columns,
      pagination,
      refFormObserver,
      getValidationState,
      resetForm,
    };
  },
  created() {
    this.getList();
    this.getColumns();
  },
  computed: {
    user_id: function () {
      return this.$store.getters.user.id;
    },
    role_id: function () {
      return this.$store.getters.user.role_id;
    },
  },
  methods: {
    validateSize,
    getColumns() {
      if (this.$store.getters.user.role_id == 3) {
        this.columns = [
          { key: "index", label: "Sr. No.", sortable: true },
          { key: "schedule_datettime", label: "Schedule Time", sortable: true },
          { key: "type", label: "Schedule Type", sortable: true },
          { key: "title", label: "Title", sortable: true },
          // { key: "description", label: "Description", sortable: false },
          { key: "assignedto", label: "Staff / Consultant", sortable: false },
          { key: "status", label: "Status", sortable: false },
          { key: "rating", label: "Rating", sortable: false },
          { key: "voice", label: "Call", sortable: false },
          { key: "video", label: "Video Call", sortable: false },
          { key: "instruction", label: "Instruction", sortable: false },
          { key: "actions", label: "Actions", sortable: false },
        ];
      }
    },
    async onSubmit() {
      try {
        this.errors = [];
        let data;
        let message = new FormData();
        message.append("message", this.messageData.message);
        message.append("client_id", this.schedule.client_id);
        message.append("schedule_id", this.schedule.id);
        data = await axios.post(
          `/workflowschedules/message/${this.schedule.id}`,
          message,
          {}
        );
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.data.message,
            icon: "EditIcon",
            variant: data.data.success ? "success" : "info",
          },
        });
        this.$bvModal.hide("send-message");
        this.messageData.message = "";
        //this.$router.replace(`/workflow/${this.id}/list`);
        this.getList();
      } catch (error) {
        if (error.response) {
          this.errors = error.response.data.errors;
        }
      }
    },
    async getList() {
      try {
        this.loading = true;
        if (this.$store.getters.user.role_id == "3") {
          this.pagination.filters.client_id = this.$store.getters.user.id;
        } else {
          this.pagination.filters.user_id = this.$store.getters.user.id;
        }
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
    async viewSchedule(schedule) {
      this.schedule = schedule;
      this.$bvModal.show("schedule-modal");
    },
    async sendMessageModal(schedule) {
      this.schedule = schedule;
      this.$bvModal.show("send-message");
    },
    async viewmessageModal(schedule) {
      const { data } = await axios.get(`workflowschedules/messages/${schedule.id}`, {});
      if (data.mesages) {
        this.messages = data.mesages;
      }
      this.$bvModal.show("view-message");
    },
    async handlePaginationChange(val) {
      this.pagination.page = val;
      this.getList();
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
        this.getList();
      } catch (error) {
        if (error.response) {
          this.errors = error.response.data.errors;
        }
      }
    },
    viewAssignModal(workflow) {
      this.workflow = workflow;
      this.$refs["assign"].show();
    },
    viewRescheduleModal(workflow) {
      this.workflow = workflow;
      this.$refs["reschedule"].show();
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
    handleRescheduleOk(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.$refs.refFormObserver.handleSubmit(this.onReschedule);
    },
    async onReschedule() {
      try {
        this.scheduleData.id = this.workflow.id;
        const { data } = await axios.post(
          "/workflowschedules/reschedule",
          this.scheduleData
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
          this.$refs["reschedule"].hide();
        });
      } catch (err) {
        console.log(err);
      }
    },
    async generateVideoLink(schedule) {
      try {
        const { data } = await axios.post(`/workflow/meeting/link/${schedule.id}`);
        if (data.success) {
          this.getList();
        }
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
            title: "Error generating video link",
            icon: "AlertTriangleIcon",
            variant: "danger",
          },
        });
      }
    },
    async setRating(item) {
      try {
        const { data } = await axios.post(`/feedback/store`, item);
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
            title: "Error while updating feedback",
            icon: "AlertTriangleIcon",
            variant: "danger",
          },
        });
      }
    },
    async callNow(item) {
      try {
        const { data } = await axios.post(`/workflow/call/now`, item);
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

.status_Text .col {
  white-space: nowrap;
  font-size: 13px;
  vertical-align: middle;
}

.status_Text .col span {
  vertical-align: super;
}
</style>
