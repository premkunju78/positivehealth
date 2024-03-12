<template>
  <div class="auth-wrapper auth-v2">
    <b-row class="auth-inner m-0">
      <!-- Brand logo-->
      <b-link class="brand-logo">
        <b-img fluid v-if="logo" :src="'/view/file?path=' + logo" alt="Logo" height="50" width="50" />
        <!-- <vuexy-logo  v-else/>-->

        <b-img v-if="!logo" :src="appLogoImage" alt="logo" />
      </b-link>
      <!-- /Brand logo-->

      <!-- Left Text-->
      <b-col lg="8" class="d-none d-lg-flex align-items-center p-5">
        <div class="w-100 d-lg-flex align-items-center justify-content-center px-5">
          <b-img fluid v-if="banner_img" :src="'/view/file?path=' + banner_img" alt="Login V2" />
          <b-img fluid v-else :src="imgUrl" alt="Login V2" />
        </div>
      </b-col>
      <!-- /Left Text-->

      <!-- Login-->
      <b-col lg="4" class="d-flex align-items-center auth-bg px-2 p-lg-5">
        <b-col sm="8" md="6" lg="12" class="px-xl-2 mx-auto">
          <div v-if="welcome_message" v-html="welcome_message"></div>
          <div v-if="!welcome_message">
            <b-card-title title-tag="h2" class="font-weight-bold mb-1">
              Welcome to Positive Health Wellness Coach! 👋
            </b-card-title>
          </div>

          <!-- form -->
          <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
            <b-form class="auth-login-form mt-2" @submit.prevent="handleSubmit(login)">
              <div v-if="showdefault">


                <b-form-group label="Full Name" label-for="name">
                  <validation-provider #default="validationContext" name="FullName" rules="required">
                    <b-form-input id="name" v-model="leadData.name" :state="getValidationState(
                      validationContext
                    )
                      " name="name" :class="errors && errors.name ? 'is-invalid' : ''" placeholder="John" />
                    <b-form-invalid-feedback v-if="errors && errors.name">
                      {{ errors.name[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-else>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </validation-provider>
                </b-form-group>

                <!-- email -->
                <b-form-group label="Email" label-for="login-email">
                  <validation-provider #default="validationContext" name="Email" rules="required">
                    <b-form-input id="login-email" v-model="leadData.email" :state="getValidationState(
                      validationContext
                    )
                      " name="login-email" :class="errors && errors.email ? 'is-invalid' : ''"
                      placeholder="john@example.com" />
                    <b-form-invalid-feedback v-if="errors && errors.email">
                      {{ errors.email[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-else>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </validation-provider>
                </b-form-group>



                <!-- phone -->
                <b-form-group label="Phone" label-for="phone">
                  <validation-provider #default="validationContext" name="Phone" rules="required">
                    <b-form-input id="phone" v-model="leadData.phone" :state="getValidationState(
                      validationContext
                    )
                      " name="phone" :class="errors && errors.phone ? 'is-invalid' : ''" placeholder="+91 9876543210" />
                    <b-form-invalid-feedback v-if="errors && errors.phone">
                      {{ errors.phone[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-else>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </validation-provider>
                </b-form-group>

                <!-- age -->
                <b-form-group label="DOB" label-for="dob">
                  <validation-provider #default="validationContext" name="Date of Birth" rules="required">
                    <b-form-datepicker id="expiry_date"
                      :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }" :state="getValidationState(
                        validationContext
                      )
                        " @input="dobChanged" v-model="leadData.dob" :class="errors && errors.dob ? 'is-invalid' : ''"
                      placeholder="DOB"></b-form-datepicker>

                    <b-form-invalid-feedback v-if="errors && errors.dob">
                      {{ errors.dob[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-else>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </validation-provider>
                </b-form-group>

                <!-- gender -->
                <b-form-group label="Gender" label-for="gender">
                  <validation-provider #default="validationContext" name="Gender" rules="required">
                    <b-form-radio-group v-model="leadData.gender" :class="errors && errors.gender ? 'is-invalid' : ''"
                      :options="genders" class="demo-inline-spacing" name="gender" id="gender" />
                    <b-form-invalid-feedback v-if="errors && errors.gender">
                      {{ errors.gender[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-else>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </validation-provider>
                </b-form-group>

                <!-- concern -->
                <b-form-group label="Concern" label-for="concern">
                  <validation-provider #default="validationContext" name="concern" rules="required">
                    <b-form-input id="concern" v-model="leadData.concern" :state="getValidationState(
                      validationContext
                    )
                      " name="concern" :class="errors && errors.concern ? 'is-invalid' : ''"
                      placeholder="Enter Concern" />
                    <b-form-invalid-feedback v-if="errors && errors.concern">
                      {{ errors.concern[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-else>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </validation-provider>
                </b-form-group>

                <!-- submit buttons -->
                <b-button type="button" @click="sendOtp()" variant="primary" block>
                  SUBMIT
                </b-button>
              </div>

              <div v-if="showOtp">
                <!-- OTP -->
                <b-form-group label="OTP" label-for="otp">
                  <validation-provider #default="validationContext" name="otp" rules="required">
                    <b-form-input id="otp" v-model="leadData.otp" :state="getValidationState(
                      validationContext
                    )
                      " name="otp" :class="errors && errors.otp ? 'is-invalid' : ''" placeholder="Enter OTP" />
                    <b-form-invalid-feedback v-if="errors && errors.otp">
                      {{ errors.otp[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-else>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </validation-provider>
                </b-form-group>

                <!-- submit buttons -->
                <b-button type="button" @click="verifyOTP()" variant="primary" block>
                  VERIFY
                </b-button>
              </div>

              <div v-if="showPassword">
                <!-- email -->
                <b-form-group label="Password" label-for="login-password">
                  <validation-provider #default="validationContext" name="Password" rules="required">
                    <b-form-input id="login-password" v-model="leadData.password" :type="'password'" :state="getValidationState(
                      validationContext
                    )
                      " name="login-password" :class="errors && errors.password ? 'is-invalid' : ''" placeholder="" />
                    <b-form-invalid-feedback v-if="errors && errors.password">
                      {{ errors.password[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-else>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </validation-provider>
                </b-form-group>

                <!-- submit buttons -->
                <b-button type="submit" variant="primary" block>
                  SUBMIT
                </b-button>
              </div>
            </b-form>
          </validation-observer>
        </b-col>
      </b-col>
      <!-- /Login-->
    </b-row>
  </div>
</template>

<script>
/* eslint-disable global-require */
import { mapActions } from "vuex";
import flatPickr from "vue-flatpickr-component";

import { ValidationProvider, ValidationObserver } from "vee-validate";
import {
  BRow,
  BCol,
  BLink,
  BFormGroup,
  BFormInput,
  BInputGroupAppend,
  BInputGroup,
  BFormCheckbox,
  BCardText,
  BCardTitle,
  BImg,
  BForm,
  BFormRadioGroup,
  BButton,
  BFormDatepicker,
  BFormInvalidFeedback
} from "bootstrap-vue";
import { required, email } from "@validations";
import { togglePasswordVisibility } from "@core/mixins/ui/forms";
import store from "@/store/index";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import { $themeConfig } from "@themeConfig";
import formValidation from "@core/comp-functions/forms/form-validation";
import { ref } from "@vue/composition-api";

export default {
  components: {
    BRow,
    BCol,
    BLink,
    BFormGroup,
    BFormInput,
    BInputGroupAppend,
    BInputGroup,
    BFormCheckbox,
    BCardText,
    BCardTitle,
    BImg,
    BForm,
    BButton,
    ValidationProvider,
    ValidationObserver,
    BFormRadioGroup,
    flatPickr,
    BFormDatepicker,
    BFormInvalidFeedback
  },
  mixins: [togglePasswordVisibility],
  props: {
    url: {
      type: [String, null],
      default: null,
    },
  },
  created() {
    if (this.url) {
      this.getBannerLogo(this.url);
    }
  },
  setup() {
    // App Name
    const { appName, appLogoImage } = $themeConfig.app;
    const leadData = ref({
      status: "",
      alliancepartner_id: "",
      name: "",
      email: "",
      phone: "",
      dob: "",
      age: "",
      gender: "",
      concern: "",
      device_name: "browser",
    });
    const resetuserData = function () {
      leadData.value = {
        status: "",
        alliancepartner_id: "",
        name: "",
        email: "",
        phone: "",
        dob: "",
        age: "",
        gender: "",
        concern: "",
        device_name: "browser",
      };
    };
    const { refFormObserver, getValidationState, resetForm } = formValidation(resetuserData);
    return {
      appName,
      appLogoImage,
      refFormObserver,
      getValidationState,
      resetForm,
      leadData,
      resetuserData,
    };
  },
  data() {

    return {
      errors: {},
      validaForm: false,
      showdefault: true,
      showOtp: false,
      showPassword: false,
      genders: [
        { text: "Male", value: "male" },
        { text: "Female", value: "female" },
      ],
      banner_img: null,
      logo: null,
      welcome_message: " ",
      sideImg: require("@/assets/images/pages/login-v2.svg"),
      // validation rulesimport store from '@/store/index'
      required,
      processing: false,

    };
  },
  computed: {
    imgUrl() {
      if (store.state.appConfig.layout.skin === "dark") {
        // eslint-disable-next-line vue/no-side-effects-in-computed-properties
        this.sideImg = require("@/assets/images/pages/login-v2-dark.svg");
        return this.sideImg;
      }
      return this.sideImg;
    },
  },
  methods: {

    async sendOtp() {
      const provider = this.$refs['refFormObserver'];
      provider.validate().then(async result => {
        if (result) {
          this.processing = true;
          var this_this = this;
          this.allerrors = [];
          await axios.get("/sanctum/csrf-cookie");
          await axios
            .post("/lead/sendotp", this.leadData)
            .then((response) => {
              if (response.data.success) {
                this.errors = {};
                this.$toast({
                  component: ToastificationContent,
                  props: {
                    title: "OTP sent successfully",
                    icon: "EditIcon",
                    variant: "success",
                  },
                });
                this.errors = [];
                this.showOtp = true;
                this.showdefault = false;
              } else {
                this.$toast({
                  component: ToastificationContent,
                  props: {
                    title: "Invalid mobile number",
                    icon: "EditIcon",
                    variant: "error",
                  },
                });
              }
            })
            .catch(({ response }) => {
              this.errors = response.data.errors;
            })
            .finally(() => {
              this.processing = false;
            });
        } else {
        }
      });


    },
    async verifyOTP() {
      this.processing = true;
      var this_this = this;
      this.allerrors = [];
      await axios.get("/sanctum/csrf-cookie");
      await axios
        .post("/lead/verifyotp", this.leadData)
        .then((response) => {
          if (response.data.success) {
            this.errors = {};
            this.$toast({
              component: ToastificationContent,
              props: {
                title: "OTP verified successfully",
                icon: "EditIcon",
                variant: "success",
              },
            });
            this.errors = [];
            this.showPassword = true;
            this.showOtp = false;
          } else {
            this.$toast({
              component: ToastificationContent,
              props: {
                title: "Invalid OTP response",
                icon: "EditIcon",
                variant: "error",
              },
            });
          }
        })
        .catch(({ response }) => {
          this.errors = response.data.errors;
        })
        .finally(() => {
          this.processing = false;
        });
    },
    async login() {
      this.processing = true;
      var this_this = this;
      this.allerrors = [];
      await axios.get("/sanctum/csrf-cookie");
      await axios
        .post("/lead/register", this.leadData)
        .then((response) => {
          this.errors = {};
          this.$toast({
            component: ToastificationContent,
            props: {
              title: "Registered successfully",
              icon: "EditIcon",
              variant: "success",
            },
          });
          this.resetForm();
          this.resetuserData();
          this.errors = [];
          this.$router.push({ name: 'auth-login' })
        })
        .catch(({ response }) => {
          this.errors = response.data.errors;
        })
        .finally(() => {
          this.processing = false;
        });
    },
    async getBannerLogo() {
      try {
        const { data } = await axios.get("alliance-partners/bannerlogo", {
          params: {
            url: this.url,
          },
        });

        this.banner_img = data.user.banner_img;
        this.leadData.alliancepartner_id = data.user.user_id;
        this.logo = data.user.logo;
        this.welcome_message = data.user.welcome_message;
      } catch (error) {
        console.log(error);
      }
    },
    dobChanged() {
      //console.log(new Date(this.userData.dob).getTime())
      var diff = (new Date().getTime() - new Date(this.leadData.dob).getTime()) / 1000;
      diff /= 60 * 60 * 24;
      const age = Math.abs(Math.round(diff / 365.25));
      this.leadData.age = age;
      console.log(this.leadData);
    },
  },
};
</script>

<style lang="scss">
@import "@core/scss/vue/pages/page-auth.scss";
@import "@core/scss/vue/libs/vue-flatpicker.scss";

.custom-radio {
  margin-top: 0 !important;
}
</style>
