<template>
  <b-card no-body class="mb-0">
    <div class="m-2">
      <FullCalendar :options="calendarOptions" />
    </div>
    <b-modal id="event-modal" hide-footer>
      <template #modal-title> Schedule Info </template>
      <div class="d-block text-left">
        <div class="mb-2" v-if="eventinfo.extendedProps">
          <label>Title: </label>
          <div>{{ eventinfo.extendedProps.title }}</div>
        </div>
        <!-- <div class="mb-2" v-if="eventinfo.extendedProps">
          <label>Description: </label>
          <div>
            {{ eventinfo.extendedProps.description }}
          </div>
        </div> -->
        <div class="mb-2" v-if="eventinfo.extendedProps">
          <label>Type: </label>
          <div>{{ eventinfo.extendedProps.type }}</div>
        </div>
        <div class="mb-2" v-if="eventinfo.extendedProps">
          <label>Schedule Time & Date: </label>
          <div>
            {{ eventinfo.extendedProps.schedule_datettime }}
          </div>
        </div>
        <div class="mb-2" v-if="eventinfo.extendedProps">
          <label>Client Name: </label>
          <div>
            {{ eventinfo.extendedProps.client_name }}
          </div>
        </div>
        <div class="mb-2" v-if="eventinfo.extendedProps">
          <label>Assigned To: </label>
          <div>
            {{ eventinfo.extendedProps.assignedto }}
          </div>
        </div>
        <div class="mb-2" v-if="eventinfo.extendedProps">
          <label>Assigned To: </label>
          <div v-html="eventinfo.extendedProps.instruction">
          </div>
        </div>
       
      </div>
      <b-button class="mt-3" block @click="$bvModal.hide('event-modal')"
        >Close Me</b-button
      >
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
  BModal,
  BLink,
  BImg,
  BFormRadioGroup,
  BFormTextarea,
} from "bootstrap-vue";
import vSelect from "vue-select";
import { ValidationProvider, ValidationObserver } from "vee-validate";
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
    BModal,
    flatPickr,
    BFormRadioGroup,
    BFormTextarea,
    "tags-input": VoerroTagsInput,
    ValidationProvider,
    ValidationObserver,
    FullCalendar,
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
      client_id: this.id,
      consultation_type: 'regular',
      tabId: "calendar",
      list: [],
      calendarOptions: {
        plugins: [dayGridPlugin, interactionPlugin, timeGridPlugin, listPlugin],
        initialView: "dayGridMonth",
        eventClick: this.handleEventClick,
        events: [],
      },
      eventinfo: [],
      pagination: {
        filters: {},
      },
    };
  },
  created() {
    if(this.$route.name === 'workshopcalendars') {
      this.consultation_type = 'workshop';
    }

    this.getList();
  },
  watch:{
    $route (to, from){

      if(this.$route.name === 'workshopcalendars') {
        this.consultation_type = 'workshop';
      }else{
        this.consultation_type = 'regular';      
      }

      this.getList();
    }
  },
  methods: {
    validateSize,
    async getList() {
      try {
        this.loading = true;
        if (this.$store.getters.user.role_id == "3") {
          this.pagination.filters.client_id = this.$store.getters.user.id;
        } else {
          this.pagination.filters.user_id = this.$store.getters.user.id;
        }
        const { data } = await axios.get(`workflowschedules/events?consultation_type=${this.consultation_type}`, {
          params: this.pagination,
        });
        if (data.events) {
          this.list = data.events;
          this.calendarOptions.events = data.events;
        }
      } catch (err) {
        console.log(err);
      }
      this.loading = false;
    },
    handleEventClick: function (info) {
      this.eventinfo = info.event;
      this.$bvModal.show("event-modal");
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
</style>
