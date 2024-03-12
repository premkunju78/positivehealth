<template>
  <div>
    <loading-spinner :show="showBooking" :label="label"></loading-spinner>

    <b-sidebar
      id="sidebar-add-new-event"
      sidebar-class="sidebar-lg"
      :visible="isEventHandlerSidebarActive"
      bg-variant="white"
      shadow
      backdrop
      no-header
      right
      @change="(val) => {detailFormShow=false;TimeSlotShow=true;$emit('update:is-event-handler-sidebar-active', val)}"
    >
      <template #default="{ hide }">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center content-sidebar-header px-2 py-1">
          <h5 class="mb-0">
            Select Time Slot
          </h5>
          <div>
            <feather-icon
              class="ml-1 cursor-pointer"
              icon="XIcon"
              size="16"
              @click="hide"
            />
          </div>
        </div>
         <transition name="fade" v-if="TimeSlotShow">
              <div class="offset-sm-1 col-sm-10 timeslotarea">
                <div class="slotpicker-times-subtitle mt-1">
                  <div class="d-flex flex-column"><div class="mb-1"><h4 class="mb-0">
                    {{event.consultant_name}}
                  </h4> <span class="card-text">{{event.consultant_type}}</span></div>
                </div>
                </div>
                <div class="slotpicker-times-list">
                    <div class="spot-list">
                        <div class="spot is-available" v-for="(time_slot, index) in slots" :id="'spot_' + index" :key="'spot_' + index">
                            <button class="time-button btn btn-outline-info" type="button" tabindex="-1" @click="handleTimeSlot(index)"><span class="time-button-title">{{ time_slot | timeslot_for_display }}</span></button>
                            <button class=" btn btn-outline-primary confirm-button confirm-button-enter-done" @click="handleDetailsForm(time_slot)" type="button" tabindex="0">Confirm</button>
                        </div>
                    </div>
                </div>
              </div>
          </transition>

              <!-- Body -->
        <validation-observer
          #default="{ handleSubmit }"
          ref="refFormObserver"
          v-if="detailFormShow"
        >
          <!-- Form -->
          <b-form
            class="p-2"
            @submit.prevent="handleSubmit(processPayment)"
            @reset.prevent="resetForm"
          >
            <div class="d-flex flex-column">
              <div class="mb-1">
                <h4 class="mb-0">
                  {{event.consultant_name}}
                </h4> 
                <span class="card-text">{{event.consultant_type}}</span>
              </div>
            </div>
            <p>{{selected_slot_for_display}}</p><br>
            <validation-provider
                #default="validationContext"
                name="Name"
            >
                <b-form-group
                    label="Name"
                    label-for="name"
                    :state="getValidationState(validationContext)"
                    rules="required"
                >
                  <b-form-input
                      id="name"
                      :state="
                          getValidationState(
                              validationContext
                          )
                      "
                      type="text"
                      v-model="booking.name"
                      :class="errors.name ? 'is-invalid' : ''"
                      :readonly="true"
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
              <validation-provider
                #default="validationContext"
                name="Name"
                rules="required"
            >
                <b-form-group
                    label="Description"
                    label-for="description"
                    :state="getValidationState(validationContext)"

                >
                  <b-form-textarea
                      id="description"
                      :state="
                          getValidationState(
                              validationContext
                          )
                      "
                      type="textarea"
                      :rows="3"
                      v-model="booking.description"
                      :class="errors.description ? 'is-invalid' : ''"
                      :area-invalid="errors.description ? true : false"
                      trim
                    />
                    <b-form-invalid-feedback v-if="errors.description">
                      {{errors.description[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-else :state="getValidationState(validationContext)">
                        {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                </b-form-group>
            </validation-provider><br>

            <p v-if="event.cost">Consultation Fee: <b>₹{{amount}}</b></p>
            <!-- <div class="form-group text-left">
              <div class="input-group">
                  <input type="text" class="form-control" placeholder="Coupon Code" v-model="coupon_code" />
                  <div class="input-group-append">
                      <button class="input-group-text btn btn-primary" style="color:#fff" @click.prevent="calculate_amount">Apply</button>
                  </div>
              </div>
              <span v-if="coupon_message"><small :style="'color:'+coupon_color">{{coupon_message}}</small></span>
            </div> -->
            <!-- Form Actions -->
            <div class="d-flex mt-2">
              <b-button
                v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                type="reset"
                 class="mr-1"
                variant="outline-danger"
                @click="detailFormShow=false;TimeSlotShow=true;"
              >
                Cancel
              </b-button>
              <b-button
                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                variant="primary"
                type="submit"
              >
                Book Slot
              </b-button>
            </div>
          </b-form>
        </validation-observer>
      </template>
    </b-sidebar>
  </div>
</template>
<style scoped>

.spot-list {
    overflow: hidden;
    white-space: nowrap;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

.spot-list .spot {
    margin-bottom: 10px;
    font-size: 16px;
}

.spot .time-button:hover {
    border: 2px solid #666666 !important;
}

.spot-list .time-button {
    vertical-align: top;
    width: 100%;
    border: 1px solid #97cefc;
}

.spot-list .spot.is-available.selected .confirm-button {
    -webkit-transform: translateX(0);
    transform: translateX(0);
}

.spot-list .confirm-button,
.spot-list .time-button {
    padding: 13px 10px;
    background-color: transparent;
    border-radius: 4px;
    text-align: center;
    cursor: pointer;
    outline: none;
    transition: all 0.3s ease;
    transition-property: width, background-color, -webkit-transform;
    transition-property: width, transform, background-color;
    transition-property: width, transform, background-color, -webkit-transform;
}

.spot-list .confirm-button {
    width: 48.5%;
    background-color: #08a0fb;
    border: 1px solid #08a0fa;
    color: #ffffff;
    font-weight: 600;
    box-shadow: 0 1px 6px 0 rgba(0, 0, 0, 0.1);
    -webkit-transform: translateX(40px);
    transform: translateX(40px);
}

.spot-list .spot.is-available.selected .time-button {
    width: 48.5%;
    background-color: #666666;
    border-color: transparent;
    color: white;
    font-weight: 600;
}

.back-button {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    padding: 0;
    background-color: transparent;
    border-radius: 50%;
    border: none;
    font-size: 24px;
    color: #3490dc;;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

.confirmation {
    margin: 30px auto;
    max-width: 450px;
    width: 100%;
}

.confirmation .header {
    margin-bottom: 20px;
    padding-bottom: 20px;
    text-align: center;
}
  .timeslotarea {
    display: flex;
    flex-direction: column;
}

.slotpicker-times-subtitle {
    margin-bottom: 10px;
    font-size: 15px;
    line-height: 38px;
}

.slotpicker-times-list {
    flex: 1 1 ;
    padding-right: 31px;
    overflow: hidden;
    overflow-x: hidden;
}

.spot-list {
    margin-top: 8px;
    overflow: hidden;
    white-space: nowrap;
}
</style>
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
import loadingSpinner from "vue-full-loading"; 
import moment from 'moment'
import $ from "jquery";

import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

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
    ToastificationContent,
    loadingSpinner,
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
    timeSlots: {
      type: Array,
      required: true,
    }
  },
  filters: {
    timeslot_for_display: function (date) {
      return moment(date, "HH:mm").format("hh:mm A");
    },
  },
  watch: {
    timeSlots:  function( val ){
      this.slots = val;
    }
  },
  data() {
    return {
        errors:{},
        required,
        rzp1: '',
        key:0,
        showBooking: false,
        label: "Booking Inprogress....",
        TimeSlotShow:true,
        detailFormShow: false,
        selected_slot: null,
        selected_slot_for_display: null,
        slots: this.timeSlots,
        booking: {
          name: this.$store.getters.user.name,
          booked_by: this.$store.getters.user.id,
          description: ''
        },

        rzpayment_id: '',
        paymentOptions:  {
            "key": process.env.MIX_RAZOR_KEY,
            "currency": "INR",
            "name": "Prajana",
            "description": "Test Transaction",
            "prefill": {
                "name": this.$store.state.auth.user.name,
                "email": this.$store.state.auth.user.email,
                "contact": this.$store.state.auth.user.phone
            },
            "theme": {
                "color": "#425b76",
                "width": "500px",
                "height": "900px"
            },
            
        },
        amount:0,
        coupon_code: null,
        coupon_amount: null,
        coupon_applied: false,
        coupon_message: '',
        coupon_color: 'red',

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
      guestsOptions,

      // Form Validation
      resetForm,
      refFormObserver,
      getValidationState,
    }
  },
  methods: {
     handleTimeSlot(id) {
      $(".spot").removeClass("selected");
      $("#spot_" + id).addClass("selected");
      this.resetBooking();
    },

    handleDetailsForm(timeSlot) {
      this.TimeSlotShow = false; //hide timeslot buttons
      this.detailFormShow = true;
      //selected slot for display
      this.selected_slot_for_display =
        moment(timeSlot, "HH:mm").format("hh:mm A") +
        " - " +
        moment(timeSlot, "HH:mm")
          .add(this.event.frequency, "minutes")
          .format("hh:mm A") +
        ", " +
        moment(this.event.date).format("dddd,MMMM D YYYY");
      this.selected_slot = moment(timeSlot, "HH:mm").format("HH:mm");
      this.booking.timeslot_from =  this.event.date + ' ' +this.selected_slot;
      this.booking.timeslot_to = this.event.date + ' ' + moment(timeSlot, "HH:mm")
          .add(this.event.frequency, "minutes").format("HH:mm");
      this.booking.user_id = this.event.user_id
      this.booking.date = this.event.date
      this.amount = parseFloat(this.event.cost);

    },


    processPayment() {
      if( !this.event.cost )
      {
        return this.onSubmit({});
      }
      this.paymentOptions.amount = this.amount *100;
      this.createOrder(this.amount )
      .then( ( {data} ) => {
          this.paymentOptions.order_id = data.order.id;
          let self = this;
          this.paymentOptions.handler = async  function (response) {
            response.cost = self.amount;
            response.coupon = self.coupon_code;
            response.coupon_amount= self.coupon_amount;
            await self.onSubmit(response);
          }
          this.rzp1 = new Razorpay(this.paymentOptions);
          this.rzp1.on('payment.success', function(){
              alert('hee')
          })
          this.rzp1.open();
      });
    },

    async createOrder( cost ) {
        return await axios.post('/razorpay/order',{
            "amount": cost*100,
            "currency": "INR"
        });
    },
    onSubmit( response ) {
       try {
          this.showBooking = true;
          let booking = {
            ...this.booking, ...response
          };
          axios.post('/booking', booking)
          .then( ( {data} ) =>  {
              if( data.success ) {
                  this.showBooking = false;
                  this.resetBooking();
                  this.$toast({
                    component: ToastificationContent,
                    props: {
                      title: data.message,
                      icon: 'EditIcon',
                      variant: data.success ? 'success' : 'info',
                    },
                  });
                this.$emit('update:is-event-handler-sidebar-active', false)
              }
          })

       } catch (error) {
          this.errors = error.response.data.errors;
       }
    },
    resetBooking(){
      this.booking = {
          name: this.$store.getters.user.name,
          booked_by: this.$store.getters.user.id,
          description: ''
      };
      this.resetCouponData();
    },

    async calculate_amount() {
      this.coupon_color = 'red';
      const {data} = await axios.get('coupon?code='+ this.coupon_code);
      let coupon = data.coupon;
      let cost = parseFloat(this.event.cost);
      if (coupon) {
         if (coupon.discount_type === "Percentage") {
            this.coupon_amount =
              cost *(parseInt(coupon.discount_value) / 100);
          } else {
            this.coupon_amount = parseInt(coupon.discount_value);
          }
          if( cost >= this.coupon_amount ) {
            this.amount = cost - this.coupon_amount;
            this.coupon_applied = true;
            this.coupon_message = `coupon applied with discount ₹${this.coupon_amount}`;
            this.coupon_color = '#67c7a5';
          } else {
            this.coupon_message = 'sorry can\'t use this coupon code';
          }
      }else {
        this.amount = cost;
        this.coupon_message = 'coupon is not valid';
      }
    },
    resetCouponData() {
      this.coupon_code =  null;
      this.coupon_amount =  null;
      this.coupon_applied =  false;
      this.coupon_message =  '';
      this.coupon_color =  'red';
    }
  }
}
</script>

<style lang="scss">
@import '@core/scss/vue/libs/vue-select.scss';
@import '@core/scss/vue/libs/vue-flatpicker.scss';

</style>
