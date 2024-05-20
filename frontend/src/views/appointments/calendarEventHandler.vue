<template>
  <div>
    <b-sidebar
      id="sidebar-add-new-event"
      sidebar-class="sidebar-lg"
      :visible="isEventHandlerSidebarActive"
      bg-variant="white"
      shadow
      backdrop
      no-header
      right
      @change="(val) => $emit('update:is-event-handler-sidebar-active', val)"
    >
      <template #default="{ hide }">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center content-sidebar-header px-2 py-1">
          <h5 class="mb-0">
            {{ eventLocal.id ? 'Update': 'Add' }} Availability
          </h5>
          <div>
            <feather-icon
              v-if="eventLocal.id"
              icon="TrashIcon"
              class="cursor-pointer"
              @click="$emit('remove-event'); hide();"
            />
            <feather-icon
              class="ml-1 cursor-pointer"
              icon="XIcon"
              size="16"
              @click="hide"
            />
          </div>
        </div>

        <!-- Body -->
        <validation-observer
          #default="{ handleSubmit }"
          ref="refFormObserver"
        >
          <!-- Form -->
          <b-form
            class="p-2"
            @submit.prevent="handleSubmit(onSubmit)"
            @reset.prevent="resetForm"
          >
          <!-- Start Date -->
          <validation-provider
            #default="validationContext"
            name="Start Date"
            rules="required"
          >
            <b-form-group
              label="Start Date"
              label-for="start-date"
            >
              <flat-pickr
                v-model="eventLocal.startDate"
                class="form-control"
                :config="{enableTime: false, dateFormat: 'd-m-Y'}"
              />


              <b-form-invalid-feedback>
                {{ validationContext.errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>

          <!-- End Date -->
          <validation-provider
            #default="validationContext"
            name="End Date"
            rules="required"
          >
            <b-form-group
              label="End Date"
              label-for="end-date"
            >
              <flat-pickr
                v-model="eventLocal.endDate"
                class="form-control"
                :config="{enableTime: false, dateFormat: 'd-m-Y'}"
              />
              <b-form-invalid-feedback>
                {{ validationContext.errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>

            <!-- Start Time -->
            <validation-provider
              #default="validationContext"
              name="Start Time"
              rules="required"
            >

              <b-form-group
                label="Start Time"
                label-for="start-time"
                :state="getValidationState(validationContext)"
              >
                <flat-pickr
                  v-model="eventLocal.startTime"
                  class="form-control"
                   :config="config.timePicker"
                />
                <b-form-invalid-feedback :state="getValidationState(validationContext)">
                  {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>

            <!-- End Time -->
            <validation-provider
              #default="validationContext"
              name="End Time"
              rules="required"
            >

              <b-form-group
                label="End Time"
                label-for="end-time"
                :state="getValidationState(validationContext)"
              >
                <flat-pickr
                  v-model="eventLocal.endTime"
                  class="form-control"
                  :config="config.timePicker"
                />
                <b-form-invalid-feedback :state="getValidationState(validationContext)">
                  {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>

            <!-- All Day -->
            <!-- <b-form-group>
              <b-form-checkbox
                v-model="eventLocal.allDay"
                name="check-button"
                switch
                inline
              >
                Apply to All Selected Day
              </b-form-checkbox>
            </b-form-group> -->

            <!-- Select Day -->
            <validation-provider
                #default="validationContext"
                name="days"
                v-if="!eventLocal.id"
            >
                <b-form-group
                    label="Days to Exclude"
                    label-for="days"
                    :state="getValidationState(validationContext)"
                >
                <v-select
                  v-model="eventLocal.selectedDays"
                  :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                  multiple
                  :options="weekDays"
                  index="id"
                  :reduce="day_name=> day_name.id"
                  label="day_name"

                />
                    <b-form-invalid-feedback :state="getValidationState(validationContext)">
                        {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                </b-form-group>
            </validation-provider>

              <validation-provider
                  #default="validationContext"
                  name="frequency"
              >
                  <b-form-group
                      label="Frequency Of Slot"
                      label-for="frequency"
                      :state="getValidationState(validationContext)"
                  >
                      <v-select
                          v-model="eventLocal.frequency"
                          :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                          :options="frequencyLists"
                          :reduce="val => val.value"
                          :clearable="true"
                      />
                      <b-form-invalid-feedback :state="getValidationState(validationContext)">
                          {{ validationContext.errors[0] }}
                      </b-form-invalid-feedback>
                  </b-form-group>
              </validation-provider>
              <validation-provider
                  #default="validationContext"
                  name="types"
              >
                  <b-form-group
                      label="Communication Types"
                      label-for="type"
                      :state="getValidationState(validationContext)"
                  >
                      <v-select
                          v-model="eventLocal.type"
                          :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                          :options="typeLists"
                          :reduce="val => val.value"
                          multiple
                          placeholder="Select Types"
                          :clearable="true"
                      />
                      <b-form-invalid-feedback :state="getValidationState(validationContext)">
                          {{ validationContext.errors[0] }}
                      </b-form-invalid-feedback>
                  </b-form-group>
              </validation-provider>
            <b-form-group>
              <b-form-checkbox
                v-model="eventLocal.group"
                name="check-button"
                :value="1"
                :unchecked-value="0"
                switch
                inline
              >
                Group
              </b-form-checkbox>
            </b-form-group>

               <validation-provider
                #default="validationContext"
                name="days"
                v-if="eventLocal.group"
            >
                <b-form-group
                    label="Maximum Attendee"
                    label-for="group-members"
                    :state="getValidationState(validationContext)"
                >
                  <b-form-input
                      id="group-members"
                      :state="
                          getValidationState(
                              validationContext
                          )
                      "
                      type="number"
                      :min="0"
                      v-model="eventLocal.attendee"
                      :class="errors.name ? 'is-invalid' : ''"
                      :area-invalid="errors.name ? true : false"
                  />
                    <b-form-invalid-feedback v-if="errors.name">
                      {{errors.name[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-else :state="getValidationState(validationContext)">
                        {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                </b-form-group>
            </validation-provider>


            <!-- Form Actions -->
            <div class="d-flex mt-2">
              <b-button
                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                variant="primary"
                class="mr-2"
                type="submit"
              >
                {{ eventLocal.id ? 'Update' : 'Add ' }}
              </b-button>
              <!-- <b-button
                v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                type="reset"
                variant="outline-secondary"
              >
                Reset
              </b-button> -->
            </div>
          </b-form>
        </validation-observer>
      </template>
    </b-sidebar>
  </div>
</template>

<script>
import {
  BSidebar, BForm, BFormGroup, BFormInput, BFormCheckbox, BAvatar, BFormTextarea, BButton, BFormInvalidFeedback,
} from 'bootstrap-vue'
import vSelect from 'vue-select'
import flatPickr from 'vue-flatpickr-component'
import Ripple from 'vue-ripple-directive'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { required, email, url } from '@validations'
import formValidation from '@core/comp-functions/forms/form-validation'
import { ref, toRefs } from '@vue/composition-api'
import useCalendarEventHandler from './useCalendarEventHandler'
import moment from 'moment'

export default {
  components: {
    BButton,
    BSidebar,
    BForm,
    BFormGroup,
    BFormInput,
    BFormCheckbox,
    BFormTextarea,
    BAvatar,
    vSelect,
    flatPickr,
    ValidationProvider,
    BFormInvalidFeedback,
    ValidationObserver,
  },
  directives: {
    Ripple,
  },
  model: {
    prop: 'isEventHandlerSidebarActive',
    event: 'update:is-event-handler-sidebar-active',
  },
  props: {
    isEventHandlerSidebarActive: {
      type: Boolean,
      required: true,
    },
    event: {
      type: Object,
      required: true,
    },
    clearEventData: {
      type: Function,
      required: true,
    },
    selectedDay : {
        default : function() {
        return moment().format('dddd');
        }
    },
  },
  data() {
    return {
        errors:{},
        required,
        email,
        url,
        selectedDays: [
          {id:6, day_name: 'Saturday' },
          {id:7, day_name: 'Sunday' },
        ],
        weekDays: [
          { id:1, day_name: 'Monday' },
          { id:2, day_name: 'Tuesday' },
          { id:3, day_name: 'Wednesday' },
          { id:4, day_name: 'Thursday' },
          { id:5, day_name: 'Friday' },
          { id:6, day_name: 'Saturday' },
          { id:7, day_name: 'Sunday' },
        ],
        config: {
            timePicker: {
                wrap: true,
                enableTime: true,
                enableSeconds: true,
                noCalendar: true,
            },
        },
        frequencyLists: [
          { value: 5, 'label': '5 Min'},
          { value: 10, 'label': '10 Min'},
          { value: 15, 'label': '15 Min'},
          { value: 20, 'label': '20 Min'},
          { value: 25, 'label': '25 Min'},
          { value: 30, 'label': '30 Min'},
          { value: 35, 'label': '35 Min'},
          { value: 40, 'label': '40 Min'},
          { value: 45, 'label': '45 Min'},
          { value: 50, 'label': '50 Min'},
          { value: 55, 'label': '55 Min'},
          { value: 60, 'label': '60 Min'},
        ],
        typeLists: [
          { value: 'video', 'label': 'Video Consultation'},
          { value: 'audio', 'label': 'Audio Consultation'}
        ]
    }
  },
  setup(props, { emit }) {
    /*
     ? This is handled quite differently in SFC due to deadlock of `useFormValidation` and this composition function.
     ? If we don't handle it the way it is being handled then either of two composition function used by this SFC get undefined as one of it's argument.
     * The Trick:

     * We created reactive property `clearFormData` and set to null so we can get `resetEventLocal` from `useCalendarEventHandler` composition function.
     * Once we get `resetEventLocal` function which is required by `useFormValidation` we will pass it to `useFormValidation` and in return we will get `clearForm` function which shall be original value of `clearFormData`.
     * Later we just assign `clearForm` to `clearFormData` and can resolve the deadlock. 😎

     ? Behind The Scene
     ? When we passed it to `useCalendarEventHandler` for first time it will be null but right after it we are getting correct value (which is `clearForm`) and assigning that correct value.
     ? As `clearFormData` is reactive it is being changed from `null` to corrent value and thanks to reactivity it is also update in `useCalendarEventHandler` composition function and it is getting correct value in second time and can work w/o any issues.
    */
    const clearFormData = ref(null)

    const {
      eventLocal,
      resetEventLocal,
      calendarOptions,

      // UI
      onSubmit,
      guestsOptions,

    } = useCalendarEventHandler(toRefs(props), clearFormData, emit)

    const {
      refFormObserver,
      getValidationState,
      resetForm,
      clearForm,
    } = formValidation(resetEventLocal, props.clearEventData)

    clearFormData.value = clearForm

    return {
      // Add New Event
      eventLocal,
      calendarOptions,
      onSubmit,
      guestsOptions,

      // Form Validation
      resetForm,
      refFormObserver,
      getValidationState,
    }
  },
}
</script>

<style lang="scss">
@import '@core/scss/vue/libs/vue-select.scss';
@import '@core/scss/vue/libs/vue-flatpicker.scss';
</style>
