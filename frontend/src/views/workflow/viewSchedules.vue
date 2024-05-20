<template>
  <b-card no-body class="mb-0">
    <div class="m-2">
      <!-- Table Top -->

      <b-row>
        <!-- Per Page -->
        <b-col cols="12" md="6" class="d-flex align-items-center justify-content-start mb-1 mb-md-0">
          <ul class="nav nav-tabs nav-justified">
            <li class="nav-item">
              <a class="nav-link" @click.prevent="setActive('for_programs')" :class="{ active: isActive('for_programs') }" href="#for_programs">Programs</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" @click.prevent="setActive('for_consultations')" :class="{ active: isActive('for_consultations') }" href="#enquiries">Consultations</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" @click.prevent="setActive('for_group_consultations')" :class="{ active: isActive('for_group_consultations') }" href="#enquiries">Workshops</a>
            </li>
          </ul>
        </b-col>
      </b-row>
      <b-table style="min-height: 250px; margin-top: 20px" ref="refRolesTable" class="position-relative" :items="list"
        responsive :fields="columns" :totalRows="pagination.total" primaryKey="index" :sort-by.sync="sortBy" show-empty
        empty-text="No matching records found" :sort-desc.sync="isSortDirDesc">
        <!-- Column: Actions -->
        <template #cell(index)="data">
          {{ data.item.scheduled_no }}
        </template>
        <template #cell(status)="data">
          <b-form-group label="" label-cols="10" class="status_Text" v-if="data.item.user_id == user_id">
            <b-dropdown variant="link" no-caret :right="$store.state.appConfig.isRTL" v-if="data.item.status != 'cancelled'">
              <template #button-content>
                {{ data.item.status }}
              </template>
              <b-dropdown-item @click="updatestatus(data.item.id, 'pending')">
                <span class="align-middle ml-50">Pending</span>
              </b-dropdown-item>
              <b-dropdown-item @click="updatestatus(data.item.id, 'completed')">
                <span class="align-middle ml-50">Completed</span>
              </b-dropdown-item>
              <b-dropdown-item @click="updatestatus(data.item.id, 'cancelled')">
                <span class="align-middle ml-50">No Show</span>
              </b-dropdown-item>
            </b-dropdown>
          </b-form-group>
          <span v-else-if="data.item.status == 'cancelled'">
            <b-button variant="danger" class="btn-sm">
              <span class="text-nowrap danger">cancelled</span>
            </b-button>            
          </span>
          <span v-else> {{ data.item.status }} </span>

          <span v-if="data.item.status == 'cancelled' && data.item.user_id == user_id">
            <b-button variant="danger" class="btn-sm">
              <span class="text-nowrap danger">cancelled</span>
            </b-button>            
          </span>
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
        <template #cell(instruction)="data">
          <router-link v-if="data.item.instruction" target="_blank" :to="'/view/instruction/' + data.item.instruction">
            <b-button variant="primary">
              <span variant="primary" class="text-nowrap">Instruction</span>
            </b-button>
          </router-link>
        </template>
        <!-- Column: Voice -->
        <template #cell(meeting_link)="data">
          <b-link :href="data.item.meeting_link" target="_blank" class="btn btn-primary btn-sm" type="button">
            <span class="text-nowrap">Start Meeting</span>
          </b-link>
        </template>
        <template #cell(schedule_type)="data">

          <b-button variant="success" @click="callNow(data.item)" v-if="data.item.type == 'audio'" class="btn-sm">
            <span class="text-nowrap">Call Now</span>
          </b-button>

          <b-button variant="info" class="btn-sm" v-if="data.item.type == 'video' && !data.item.host_link && data.item.is_call_end == 0" @click="generateVideoLink(data.item)">
            <span class="text-nowrap" v-if="$store.state.auth.user.role_id == '3'">Call Waiting...</span>
            <span class="text-nowrap" v-else>Start Video Meeting</span>
          </b-button>
          <a target="_blank" v-if="data.item.host_link && data.item.is_call_end == 0" :href="data.item.client_id == user_id
            ? data.item.participant_link
            : data.item.host_link
            ">
            <b-button variant="info" class="btn-sm">
              <span class="text-nowrap primary">Join Now</span>
            </b-button>
          </a>
          
          <b-button class="btn-sm" variant="danger" v-if="data.item.is_call_end == 1">
            <span class="text-nowrap primary">Call Ended !</span>
          </b-button>

        </template>
        <template #cell(scheduled_date)="data">
          {{ data.item.scheduled_date }}
        </template>
        <template #cell(scheduled_time)="data">
          {{ data.item.scheduled_time }}
        </template>
        <template #cell(ap_info)="data">
          {{ data.item.ap_name }}
        </template>
        <template #cell(group_details)="data">
          <div v-html="data.item.group_details"></div>
        </template>
        <template #cell(client_info)="data">
          <ul>
            <li>
              <span>Name: {{ data.item.client_name }}</span>
            </li>
            <li>
              <span>Age: {{ data.item.client_age }}</span>
            </li>
            <li>
              <span>Gender: {{ data.item.client_gender }}</span>
            </li>
          </ul>
        </template>
        <template #cell(actions)="data">
          <b-dropdown variant="link" no-caret :right="$store.state.appConfig.isRTL">
            <template #button-content>
              <feather-icon icon="MoreVerticalIcon" size="16" class="align-middle text-body"/>
            </template>
            <b-dropdown-item @click="cancelScheduleModal(data.item)" v-if="schedule_type == 'upcoming' && data.item.status != 'cancelled'">
              <feather-icon icon="XCircleIcon" />
              <span class="align-middle ml-50">Cancel</span>
            </b-dropdown-item>
            <b-dropdown-item @click="viewSchedule(data.item)">
              <feather-icon icon="EyeIcon" />
              <span class="align-middle ml-50">View</span>
            </b-dropdown-item>
            <b-dropdown-item @click="sendMessageModal(data.item)" v-permission="['view-workflows']" v-if="data.item.status != 'cancelled'">
              <feather-icon icon="SendIcon" />
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
              v-if="data.item.status == 'cancelled'">
              <feather-icon icon="CalendarIcon" />
              <span class="align-middle ml-50">Re-Schedule</span>
            </b-dropdown-item>
            <b-dropdown-item @click="viewmessageModal(data.item)" v-permission="['view-workflows']" v-if="data.item.status != 'cancelled'">
              <feather-icon icon="MailIcon" />
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
      <div class="d-block text-left" v-if="activeItem == 'for_group_consultations'">
        <div class="mb-2">
          <label>Workshop: </label>
          <div v-html="schedule.group_details"></div>
        </div>
        <div class="mb-2">
          <label>Type: </label>
          <div>{{ schedule.session_type }}</div>
        </div>
        <div class="mb-2">
          <label>Alians Partner: </label>
          <div>{{ schedule.ap_name }}</div>
        </div>
        <div class="mb-2" v-if="schedule.session_type === 'paid'">
          <label>Cost: </label>
          <div>&#8377;{{ schedule.cost }}</div>
        </div>
        <div class="mb-2">
          <label>Schedule Time & Date: </label>
          <div>
            {{ schedule.scheduled_date }} {{ schedule.scheduled_time }}
          </div>
        </div>
        <div class="mb-2">
          <label>Description: </label>
          <div v-html="schedule.description"></div>
        </div>
      </div>
      <div class="d-block text-left" v-if="activeItem == 'for_consultations'">
        <div class="mb-2">
          <label>Cost: </label>
          <div>&#8377;{{ schedule.cost }}</div>
        </div>
        <div class="mb-2">
          <label>Payment ID: </label>
          <div>{{ schedule.razorpay_payment_id }}</div>
        </div>
        <div class="mb-2">
          <label>Description: </label>
          <div>{{ schedule.description }}</div>
        </div>
      </div>
      <div class="d-block text-left" v-if="activeItem == 'for_programs'">
        <div class="mb-2">
          <label>Title: </label>
          <div>{{ schedule.title }}</div>
        </div>
        <div class="mb-2">
          <label>Session: </label>
          <div>
            {{ schedule.session }}
          </div>
        </div>
        <div class="mb-2">
          <label>Type: </label>
          <div>{{ schedule.type }}</div>
        </div>
        <div class="mb-2">
          <label>Schedule Time & Date: </label>
          <div>
            {{ schedule.scheduled_date }} {{ schedule.scheduled_time }}
          </div>
        </div>
        <div class="mb-2" v-if="schedule_type != 'upcoming'">
          <label>Ratings: </label>
          <div v-if="role_id == 3">
            <star-rating v-if="schedule.rating == null && schedule.status == 'Completed'" :rating="schedule.rating"
              :max-rating="10" :star-size="20" v-model="schedule.rating"
              @rating-selected="setRating(schedule)"></star-rating>
          </div>
          <div v-else>
            <star-rating :read-only="true" :rating="schedule.rating" :max-rating="10" :star-size="20"
              v-model="schedule.rating" @rating-selected="setRating(schedule)"></star-rating>
          </div>
        </div>
        <div class="mb-2" v-if="schedule.user_id != user_id">
          <label>Assigned To: </label>
          <div>
            {{ schedule.assignedto_user }} ({{ schedule.assignedto }})
          </div>
        </div>
        <div class="mb-2">
          <label>Instructions: </label>
          <div v-html="schedule.instruction">
          </div>
        </div>
        <div class="mb-2" v-if="schedule.status == 'cancelled'">
          <label class="control-label">Cancelled by</label>
          <div>
            {{ schedule.assignedto_user }}
            <br/>
            Reason: {{ schedule.cancellation_reason }}
          </div>
        </div>
      </div>
    </b-modal>

    <b-modal id="call-ended-modal" ref="init-call-ended-modal" ok-only no-close-on-backdrop hide-header hide-footer>
      <template #modal-title> Send Message </template>
      <b-img
        fluid
        :src="callEndImg"
        alt="Call Ended"
      />
      <h4 class="header">Call has ended</h4>
      <p>Update the call status to continue</p>
      <ul class="action-btns">
        <li><button type="button" class="btn btn-default st-design" @click="changeScheduleStatus('incomplete')">Incomplete</button></li>
        <li><button type="button" class="btn btn-primary st-design" @click="changeScheduleStatus('completed')">Completed</button></li>
      </ul>
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

    <b-modal id="schedule-cancel-modal" title="Cancel Schedule" ok-only ok-title="Cancel" @ok="initCancelSchedule" no-close-on-backdrop>
      <div class="d-block text-left">
        <p class="control-label">Cancellation reason</p>
        <textarea class="form-control" v-model="schedule.cancellation_reason"></textarea>
      </div>
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
      schedule_type: null,
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
      activeItem: 'for_programs',
      calendarOptions: {
        plugins: [dayGridPlugin, interactionPlugin, timeGridPlugin, listPlugin],
        initialView: "dayGridMonth",
        eventClick: this.handleEventClick,
        events: [
          { title: "event 1", date: "2022-07-01" },
          { title: "event 2", date: "2022-07-02" },
        ],
      },
      callEndImg: require('@/assets/images/alerts/callended.png'),
      scheduleData: {},
      running_sch_id: false,
      moment: moment,
    };
  },
  setup(props) {
    const columns = [
      { key: "index", label: "Sr. No.", sortable: true },
      { key: "scheduled_date", label: "Schedule Date", sortable: true },
      { key: "scheduled_time", label: "Schedule Time", sortable: true },
      { key: "client_info", label: "Client Info", sortable: true },
      { key: "status", label: "Status", sortable: false },
      { key: "schedule_type", label: "Type", sortable: false },
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
    if(this.$route.name == 'todays-schedules') {
      this.schedule_type = 'todays';
    }else if(this.$route.name == 'past-schedules') {
      this.schedule_type = 'past';
    }else if(this.$route.name == 'upcoming-schedules') {
      this.schedule_type = 'upcoming';
    }

    this.getList(this.schedule_type);
    this.getColumns(this.schedule_type);
  },
  watch:{
    $route (to, from){
      if(this.$route.name == 'todays-schedules') {
        this.schedule_type = 'todays';
      }else if(this.$route.name == 'past-schedules') {
        this.schedule_type = 'past';
      }else if(this.$route.name == 'upcoming-schedules') {
        this.schedule_type = 'upcoming';
      }

      this.getList(this.schedule_type);
      this.getColumns(this.schedule_type);
    },
    "pagination.per_page": function (val) {
      this.handleFilterChange(val);
    }
  },
  todo: function(){
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
    getColumns(schedule_type) {

      if (this.$store.getters.user.role_id == 3 || schedule_type == 'todays') {
        this.columns = [
          { key: "index", label: "Sr. No.", sortable: true },
          { key: "scheduled_date", label: "Schedule Date", sortable: true },
          { key: "scheduled_time", label: "Schedule Time", sortable: true },
          { key: "ap_info", label: "AP Info", sortable: true },
          { key: "client_info", label: "Client Info", sortable: true },
          { key: "schedule_type", label: "Type", sortable: false },
        ];
      }

      if(schedule_type == 'past') {
        this.columns = [
          { key: "index", label: "Sr. No.", sortable: true },
          { key: "scheduled_date", label: "Schedule Date", sortable: true },
          { key: "scheduled_time", label: "Schedule Time", sortable: true },
          { key: "ap_info", label: "AP Info", sortable: true },
          { key: "client_info", label: "Client Info", sortable: true },
        ];
      }

      if(schedule_type == 'upcoming') {
        this.columns = [
          { key: "index", label: "Sr. No.", sortable: true },
          { key: "scheduled_date", label: "Schedule Date", sortable: true },
          { key: "scheduled_time", label: "Schedule Time", sortable: true },
          { key: "ap_info", label: "AP Info", sortable: true },
          { key: "client_info", label: "Client Info", sortable: true },
        ];
      }

      if(this.activeItem == 'for_group_consultations') {
        this.columns = [
          { key: "index", label: "GP No.", sortable: true },
          { key: "group_details", label: "Workshop Information", sortable: false },
          { key: "scheduled_date", label: "Schedule Date", sortable: true },
          { key: "scheduled_time", label: "Schedule Time", sortable: true },
          { key: "session_title", label: "Session Title", sortable: true },
        ];
      }

      if(this.activeItem == 'for_group_consultations' && schedule_type === 'todays') {
        this.columns.push(
          { key: "meeting_link", label: "Call", sortable: false },
        )
      }

      this.columns.push(
        { key: "status", label: "Status", sortable: false },
        { key: "actions", label: "Actions", sortable: false },
      )

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
    async getList(schedule_type=this.schedule_type) {
      try {
        this.loading = true;
        if (this.$store.getters.user.role_id == "3") {
          this.pagination.filters.client_id = this.$store.getters.user.id;
        } else if(this.$store.getters.user.role_id == "11" || this.$store.getters.user.role_id == "10" || this.$store.getters.user.role_id == "1") {
          this.pagination.filters.created_by = this.$store.getters.user.id;
        } else {
          this.pagination.filters.user_id = this.$store.getters.user.id;        
        }
        const { data } = await axios.get(`workflowschedules?type=${schedule_type}&appointment_type=${this.activeItem}`, {
          params: this.pagination,
        });

        if (data.schedules) {
          this.list = data.schedules;
          this.pagination.per_page = data.per_page;
          this.pagination.total = data.total;
          this.pagination.page = data.page;
        }

        var calEndedInterval = setInterval(async () => {
          this.running_sch_id = localStorage.getItem('call_started_for_schedule');          
          if(this.running_sch_id && this.$route.name == 'todays-schedules') {
            const { data } = await axios.get(`workflowschedules/check-running-schedule-status/${this.running_sch_id}`);
            if(data.trigger_call_ended_screen) {
              this.$refs["init-call-ended-modal"].show();                                  
              clearInterval(calEndedInterval);
            }
          }
        }, 3000);         

      } catch (err) {
        console.log(err);
      }
      this.loading = false;
    },
    async changeScheduleStatus(status) {
      try {
        const { data } = await axios.post(`workflowschedules/updatestatus`, {
          'id': this.running_sch_id,
          'status': status
        });

        localStorage.removeItem('call_started_for_schedule');
        this.$refs["init-call-ended-modal"].hide();                                  
        location.reload();

        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.data.message,
            icon: "EditIcon",
            variant: data.data.success ? "success" : "info",
          },
        });        

      } catch (err) {
        console.log(err);
      }
    },
    async viewSchedule(schedule) {
      this.schedule = schedule;
      this.$bvModal.show("schedule-modal");
    },
    async sendMessageModal(schedule) {
      this.schedule = schedule;
      this.$bvModal.show("send-message");
    },
    async cancelScheduleModal(schedule) {
      this.schedule.id = schedule.id;
      this.$bvModal.show("schedule-cancel-modal");
    },
    async initCancelSchedule() {            
      try {
        const { data } = await axios.post(
          "/workflowschedules/updatestatus",
          { id: this.schedule.id, status: 'cancelled', cancellation_reason: this.schedule.cancellation_reason }
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

      this.$bvModal.hide("schedule-cancel-modal");
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
      window.location.href = '/workflow/' + workflow.client_id + '/list?schedule_id=' + workflow.id
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

        localStorage.setItem('call_started_for_schedule', schedule.id);

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
    isActive (menuItem) {
      return this.activeItem === menuItem
    },
    setActive (menuItem) {

      if(this.$route.name == 'todays-schedules') {
        this.schedule_type = 'todays';
      }else if(this.$route.name == 'past-schedules') {
        this.schedule_type = 'past';
      }else if(this.$route.name == 'upcoming-schedules') {
        this.schedule_type = 'upcoming';
      }

      this.activeItem = menuItem
      this.getList(this.schedule_type);
      this.getColumns(this.schedule_type);
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

td ul {
  padding-left: 0;
  list-style-type: none;
  line-height: normal;
}

td ul li {
  line-height: normal;
  padding: 5px;
  color: #000000;
}

#call-ended-modal .modal-body {
  text-align: center;
}

ul.action-btns .btn.st-design {
  border: 2px solid #1a27b6;
  min-width: 200px;
  min-height: 44px;
}

#call-ended-modal .modal-body img {
  max-height: 400px;
  margin-left: auto;
  margin-right: auto;
  display: block;
}

#call-ended-modal .modal-header {
  background: transparent;
  margin-bottom: -2.5rem;
}

#call-ended-modal .modal-header h5 {
  display: none;  
}

ul.action-btns {
  display: flex;
  padding-left: 0;
  list-style-type: none;
  align-items: center;
  align-content: center;
  gap: 2rem;
  justify-content: center;
}


</style>
