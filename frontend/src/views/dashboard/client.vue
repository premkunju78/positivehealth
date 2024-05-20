<template>
  <div>
    <section id="dashboard-analytics">
      <h3 class="pt-2 pb-3">Hello {{ $store.state.auth.user.first_name }} !</h3>
      <b-row>
        <b-col lg="9" md="6">
          <b-row class="match-height">
            <b-col lg="6" md="12">
              <!-- <upcoming-appointment :data="meetup" /> -->
              <b-card title="Today's Appointments">
                <TodaySchedule />
              </b-card>
            </b-col>
            <b-col lg="6" md="12">
              <b-card title="League consultants">
                <Leagueconsultants />
              </b-card>
            </b-col>
            <b-col lg="6" md="12">
              <investigations :data="data" />
            </b-col>
            <b-col lg="6" md="12">
              <prescriptions :data="data" />
            </b-col>
          </b-row>
          <b-row>
            <b-col md="12">
              <b-card title="Feedback">
                <feedback />
              </b-card>
            </b-col>
          </b-row>
          <b-row>
            <b-col md="12">
              <b-card title="Questinaires">
                <questinaires />
              </b-card>
            </b-col>
          </b-row>
          <b-row>
            <b-col md="12">
              <b-card title="Assessment / Feedback forms">
                <Questinaireforms />
              </b-card>
            </b-col>
          </b-row>
        </b-col>
        <b-col lg="3" md="6">
          <b-row>
            <b-col cols="12">
              <slider :data="data" />
            </b-col>
            <b-col cols="12">
              <b-button
                size="sm"
                class="btn btn-primary btn-block"
                variant="btn-primary mr-1"
                @click="referaFriend()"
              >
                <span class="ml-25 align-middle">Refer a Friend</span>
              </b-button>
            </b-col>
            <b-col cols="12">
              <Notifications :data="data" />
            </b-col>
          </b-row>
        </b-col>
      </b-row>
    </section>
    <section id="dashboard-contents"></section>

    <b-modal
      id="refer"
      ref="refer"
      ok-only
      ok-title="Submit"
      @hidden="resetData"
      @ok="handleOk"
      centered
      size="lg"
      title="Refer a Friend"
      no-close-on-backdrop
    >
      <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
        <form ref="form" @submit.prevent="handleSubmit(onRefer)">
          <b-row>
            <b-col cols="12" md="6">
              <validation-provider
                #default="validationContext"
                name="Full Name"
                rules="required"
              >
                <b-form-group
                  label="Full Name"
                  label-for="fullname"
                  :state="getValidationState(validationContext)"
                >
                  <b-form-input
                    id="name"
                    v-model="referData.name"
                    :state="errors.length > 0 ? false : null"
                    name="email"
                    placeholder="Enter Full Name"
                  />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col cols="12" md="6">
              <validation-provider
                #default="validationContext"
                name="EmailID"
                rules="required"
              >
                <b-form-group
                  label="Email ID"
                  label-for="partners"
                  :state="getValidationState(validationContext)"
                >
                  <b-form-input
                    id="email"
                    v-model="referData.email"
                    :state="errors.length > 0 ? false : null"
                    name="email"
                    placeholder="Enter Email ID"
                  />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col cols="12" md="6">
              <validation-provider
                #default="validationContext"
                name="Mobile"
                rules="required"
              >
                <b-form-group
                  label="Mobile"
                  label-for="mobile"
                  :state="getValidationState(validationContext)"
                >
                  <b-form-input
                    id="name"
                    v-model="referData.mobile"
                    :state="errors.length > 0 ? false : null"
                    name="mobile"
                    placeholder="Enter Mobile"
                  />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <!-- age -->
            <b-col cols="12" md="6">
              <validation-provider
                #default="validationContext"
                name="Date of Birth"
                rules="required"
              >
                <b-form-group label="Date of Birth" label-for="dob">
                  <flat-pickr
                    v-model="referData.dob"
                    class="form-control"
                    :config="{ enableTime: false, dateFormat: 'd-m-Y' }"
                    @on-change="dobChanged"
                  />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col cols="12" md="6">
              <validation-provider
                #default="validationContext"
                name="Concern"
                rules="required"
              >
                <b-form-group
                  label="Concern"
                  label-for="concern"
                  :state="getValidationState(validationContext)"
                >
                  <b-form-input
                    id="name"
                    v-model="referData.concern"
                    :state="errors.length > 0 ? false : null"
                    name="concern"
                    placeholder="Enter Concern"
                  />
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

    <b-modal id="view-reffered-to-modal" ref="view-reffered-to-modal" size="lg" hide-footer title="" no-close-on-backdrop>
        <template #modal-title></template>
        <div class="image-block-flex">
          <div class="left-content">
            <p>Dear {{ $store.state.auth.user.name }} <br/><b>{{ referred_detail.referred_by_name }}</b> had referred <b>{{ referred_detail.referred_to_name }}</b> for a specialist opinion.</p>
            <p>You can proceed to book an appointment by clicking below button</p>
            <p><strong>Specialization</strong><br/>{{ referred_detail.referred_to_specialized_in }}</p>
            <ul>
              <li v-if="$store.state.auth.user.role_id === 3">
                <b-link class="btn btn-primary text-nowrap" :to="{name:'book-appointments', params: {id: referred_detail.referred_to_id, role: 6} }" >Consult</b-link>
              </li>
              <li v-if="$store.state.auth.user.role_id === 3">
                <b-button class="btn btn-danger text-nowrap" @click="cancelReferral">Cancel</b-button>
              </li>
            </ul>
          </div>
          <div class="right-image">
            <img :src="'/view/file?path=' + referred_detail.avatar" alt="Card image cap" class="card-img-top" style="height:200px; min-width: 150px;">
          </div>
        </div>
    </b-modal>

  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import {
  BRow,
  BCol,
  BCard,
  BCardText,
  BLink,
  BCardTitle,
  BFormInput,
  BButton,
  BModal,
  BFormGroup,
  BFormInvalidFeedback,
} from "bootstrap-vue";

import Statics from "./statics.vue";

import Appointment from "./appointment.vue";
import UpcomingAppointment from "./upcomingAppointment.vue";
import LineChart from "./chart.vue";

import Result from "./result.vue";
import Search from "./search.vue";
import Affirmation from "./affirmation.vue";

import Consultations from "./consultations.vue";
import ProgramSchedules from "./programSchedules.vue";
import Notifications from "./notifications.vue";

import Prescriptions from "./prescriptions.vue";
import Investigations from "./investigations.vue";
import Slider from "./slider.vue";
import Explore from "./explore.vue";

import { ValidationProvider, ValidationObserver } from "vee-validate";
import formValidation from "@core/comp-functions/forms/form-validation";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import { required } from "@validations";

import flatPickr from "vue-flatpickr-component";
import TodaySchedule from "./todayschedule.vue";
import Leagueconsultants from "./leagueconsultants.vue";
import Feedback from "./feedback.vue";
import Questinaires from "./questinaires.vue";
import Questinaireforms from "./questinaireforms.vue";

export default {
  components: {
    BRow,
    BCol,
    BCard,
    BCardText,
    BLink,
    BCardTitle,
    BFormInput,
    BButton,
    BFormGroup,
    BModal,
    BFormInvalidFeedback,
    Statics,
    Appointment,
    LineChart,
    Result,
    Search,
    Affirmation,
    Notifications,
    ProgramSchedules,
    UpcomingAppointment,
    Consultations,
    Investigations,
    Prescriptions,
    Slider,
    Explore,
    ValidationProvider,
    ValidationObserver,
    flatPickr,
    TodaySchedule,
    Leagueconsultants,
    Feedback,
    Questinaires,
    Questinaireforms
  },
  data() {
    const { refFormObserver, getValidationState, resetForm } = formValidation(
      this.resetData
    );
    return {
      data: [
        {
          title: "Appointments",
          subtitle: "150",
          color: "danger",
          icon: "calendar",
          customClass: "",
        },
        {
          title: "New Clients",
          subtitle: "100",
          color: "success",
          icon: "plus-circle",
          customClass: "",
        },
        {
          title: "Re Visit Clients",
          subtitle: "50",
          color: "warning",
          icon: "refresh-cw",
          customClass: "",
        },
      ],
      companyTable: [],
      meetup: [],
      affirmation: "",
      refFormObserver,
      getValidationState,
      resetForm,
      required,
      notifications: [],
      errors: [],
      referData: {
        email: "",
        age: "",
      },
      referred_detail: {},
    };
  },
  computed: {
    ...mapGetters(["user"]),
  },
  created() {
    this.fetchNotifications();
    this.fetchAffirmation();
  },
  methods: {
    async fetchNotifications() {
      const { data } = await axios.get(`notification/${this.$store.state.auth.user.id}`);
      this.notifications = data.notifications;
    },
    async fetchAffirmation() {
      const { data } = await axios.get(`affirmation/today`);
      this.affirmation = data.affirmation;
      
      if(data.referred_detail) {
        this.referred_detail = data.referred_detail;
        console.log(this.referred_detail);
        this.$refs["view-reffered-to-modal"].show();      
      }
    },
    referaFriend(client) {
      this.$refs["refer"].show();
    },
    handleOk(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.$refs.refFormObserver.handleSubmit(this.onRefer);
    },
    resetData() {
      this.referData = {
        email: "",
      };
    },
    dobChanged() {
      //console.log(new Date(this.userData.dob).getTime())
      var diff = (new Date().getTime() - new Date(this.referData.dob).getTime()) / 1000;
      diff /= 60 * 60 * 24;
      const age = Math.abs(Math.round(diff / 365.25));
      alert(age);
      this.referData.age = age;
    },
    async cancelReferral() {
      try {
        const { data } = await axios.post("/client/cancel_referral", {
          referred_to : this.referred_detail.referred_to_id
        });

        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "BellIcon",
            variant: data.success ? "success" : "danger",
          },
        });
        this.$nextTick(() => {
          this.$refs["view-reffered-to-modal"].hide();
        });
      } catch (err) {
        console.log(err);
      }      
    },
    async onRefer() {
      try {
        const { data } = await axios.post("/client/referfriend", this.referData);
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "BellIcon",
            variant: data.success ? "success" : "danger",
          },
        });
        this.$nextTick(() => {
          this.$refs["refer"].hide();
        });
      } catch (err) {
        console.log(err);
      }
    },
  },
};
</script>

<style lang="scss">
@import "@core/scss/vue/libs/vue-flatpicker.scss";
.custom-radio {
  margin-top: 0 !important;
}

#view-reffered-to-modal ul {
  padding-left: 0;
  list-style-type: none;
  display: flex;
  gap: 2rem;
  margin-top: 2rem;
}

#view-reffered-to-modal ul li {
  line-height: normal;
}

#view-reffered-to-modal ul li .btn {
  max-width: 200px;
  margin-left: auto;
  margin-right: auto;
  display: block;
}

#view-reffered-to-modal .image-block-flex {
  display: flex;
  gap: 2rem;
}

#view-reffered-to-modal .image-block-flex .right-image img {
  max-width: 200px;
}
</style>
