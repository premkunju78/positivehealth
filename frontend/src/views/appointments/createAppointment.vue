<template>
  <b-card title="Book your Appointments">
    <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
        <!-- User Info: Input Fields -->
        <b-form @submit.prevent="handleSubmit(onSubmit)">
          <b-row>
            <!-- Field: Roles -->
            <b-col cols="12" md="4">
              <validation-provider
                #default="validationContext"
                name="Consultant Type"
                rules="required"
              >
                <b-form-group
                  label="Consultant Type"
                  label-for="consultant_type"
                  :state="getValidationState(validationContext)"
                >
                  <v-select
                    v-model="AppointmentData.selectedRole"
                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="roleOptions"
                    :reduce="val => val.id"
                    :clearable="false"
                    @input="getRoleUsers"
                    input-id="consultant_type"
                  />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>

            <!-- Field: Role Users -->
            <b-col cols="12" md="4">
              <validation-provider
                #default="validationContext"
                name="Consultant Name"
                rules="required"
              >
                <b-form-group
                  label="Consultant Name"
                  label-for="consultant_name"
                  :state="getValidationState(validationContext)"
                >
                  <v-select
                    v-model="AppointmentData.selectedUser"
                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                     label="name"
                    :options="roleUsers"
                    :reduce="val => val.id"
                    :clearable="false"
                    input-id="consultant_name"
                  />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col cols="12" md="4">
              <!-- Action Buttons -->
              <b-button
                  variant="primary"
                  :block="
                  $store.getters['app/currentBreakPoint'] === 'xs'"
                  type="submit"
                   class="mr-2 mt-2"
                  v-permission="['create-booking']"
              >
              Go
              </b-button>
            </b-col>
          </b-row>
        </b-form>
    </validation-observer>

    <div v-if="showCalendar">
      <full-calendar
        ref="refCalendar"
        :options="calendarOptions"
        class="full-calendar"
      />
       <!-- Sidebar Overlay -->
      <div
        class="body-content-overlay"
        :class="{'show': isCalendarOverlaySidebarActive}"
        @click="isCalendarOverlaySidebarActive = false"
      />
      <calendar-booking-event-handler
        v-model="isEventHandlerSidebarActive"
        :event="event"
        :clear-event-data="clearEventData"
        :timeSlots="slots"
        @remove-event="removeEvent"
        @add-event="addEvent"
        @update-event="updateEvent"
      />


    </div>

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
    BFormInvalidFeedback } from 'bootstrap-vue'

  import store from '@/store'
  import { onUnmounted } from '@vue/composition-api'
  import FullCalendar from '@fullcalendar/vue'
  import calendarStoreModule from './availabilityStoreModule'
  import CalendarBookingEventHandler from './calendarBookingEventHandler.vue'
  import bookingCalendar from './bookingCalendar'

  import { ValidationProvider, ValidationObserver } from "vee-validate";
  import formValidation from "@core/comp-functions/forms/form-validation";
  import { required } from "@validations";
  import vSelect from "vue-select";

  import { ref } from "@vue/composition-api";

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
    BFormInvalidFeedback,
    vSelect,
    ValidationProvider,
    ValidationObserver,
    FullCalendar,
    CalendarBookingEventHandler,
  },
  props: {
    id: {
      type : [String,Number,null],
      defalt: null
    },
    role: {
      type : [String,Number,null],
      defalt: null
    }
  },
  data() {

    return {
      roleOptions:[],
      roleUsers:[],
      showCalendar: false,
    }

  },
  setup() {
    const CALENDAR_APP_STORE_MODULE_NAME = 'calendar'

    // Register module
    if (!store.hasModule(CALENDAR_APP_STORE_MODULE_NAME)) store.registerModule(CALENDAR_APP_STORE_MODULE_NAME, calendarStoreModule)

    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(CALENDAR_APP_STORE_MODULE_NAME)) store.unregisterModule(CALENDAR_APP_STORE_MODULE_NAME)
    })
    const {
      refCalendar,
      isCalendarOverlaySidebarActive,
      event,
      clearEventData,
      addEvent,
      updateEvent,
      removeEvent,
      fetchEvents,
      refetchEvents,
      calendarOptions,
      setUser,
      TimeSlotShow,
      timeSlots,
      slots,
      setColor,

      // ----- UI ----- //
      isEventHandlerSidebarActive,
    } = bookingCalendar()

    fetchEvents()
    const AppointmentData = ref( {
        selectedRole: null,
        selectedUser: null,
        roleOptions: [],
        roleUsers:[],
    });
    const resetAppointmentData = function() {
        AppointmentData.value = {
          selectedRole: "",
          selectedUser: "",
          roleOptions: [],
          roleUsers:[],
        };
    };
    const {
        refFormObserver,
        getValidationState,
        resetForm
    } = formValidation(resetAppointmentData);
    return {
      AppointmentData,
      refFormObserver,
      getValidationState,
      resetAppointmentData,
      resetForm,
      refCalendar,
      isCalendarOverlaySidebarActive,
      event,
      clearEventData,
      addEvent,
      updateEvent,
      removeEvent,
      refetchEvents,
      fetchEvents,
      calendarOptions,
      setUser,
      setColor,
      isEventHandlerSidebarActive,
      TimeSlotShow,
      timeSlots,
      slots
    }
  },
  created() {
    this.getRoles().then(() => {
      if( this.id && this.role ){
        this.AppointmentData.selectedRole = this.role;
        this.getRoleUsers()
        .then( () => {
          this.AppointmentData.selectedUser = this.id;
          this.onSubmit();
        })
      }
    })
   
  },
  methods: {
    async getRoles() {
      try {

        const {data} = await axios.get('/roles/list');
        this.roleOptions = data.roles;
      } catch( err ) {
        console.log(err)
      }
    },
    onSubmit() {
      this.setUser(this.AppointmentData.selectedUser);

      let role = this.roleOptions.filter( role => {
        return role.id === (this.AppointmentData.selectedRole || 1)
      })[0]
      this.setColor( role ? role.color :  '#1CA085');

      this.showCalendar=true;
    },
    async getRoleUsers() {
        if (this.AppointmentData.selectedRole) {
            try {
              this.AppointmentData.selectedUser = "";

              const { data } = await axios.get('/roleUsers/list', {
                  params: { role_id: this.AppointmentData.selectedRole }
              });
              this.roleUsers = data.users;

            } catch (err) {
                console.log(err)
            }
        }

    },
  }
}
</script>
<style lang="scss">
  @import "@core/scss/vue/libs/vue-select.scss";
</style>
