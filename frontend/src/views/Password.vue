<template>
  <div class="auth-wrapper auth-v2">
    <b-row class="auth-inner m-0">
      <!-- Brand logo-->
      <b-link class="brand-logo">
        <b-img :src="appLogoImage" alt="logo" />
      </b-link>
      <!-- /Brand logo-->

      <!-- Left Text-->
      <b-col lg="8" class="d-none d-lg-flex align-items-center p-5">
        <div class="w-100 d-lg-flex align-items-center justify-content-center px-5">
          <b-img fluid :src="imgUrl" alt="Login V2" />
        </div>
      </b-col>
      <!-- /Left Text-->

      <!-- Login-->
      <b-col lg="4" class="d-flex align-items-center auth-bg px-2 p-lg-5">
        <b-col sm="8" md="6" lg="12" class="px-xl-2 mx-auto">
          <b-card-title title-tag="h2" class="font-weight-bold mb-1">
            Welcome to Positive Health Wellness Coach! 👋
          </b-card-title>
          <b-card-text class="mb-2">
            Please generate your account password and start the adventure
          </b-card-text>
          <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
            <!-- form -->
            <b-form
              class="auth-login-form mt-2"
              @submit.prevent="handleSubmit(generatePassword)"
            >
              <!-- email -->
              <b-form-group label="Password" label-for="password">
                <validation-provider
                  #default="{ errors }"
                  name="Password"
                  data-vv-as="password"
                  rules="required|password"
                >
                  <b-form-input
                    id="password"
                    v-model="password"
                    :state="errors.length > 0 ? false : null"
                    name="password"
                    class="form-control-merge"
                    :type="passwordFieldType"
                    placeholder="············"
                  />
                  <b-form-invalid-feedback class="text-danger" v-if="errors">
                    {{ errors[0] }}
                  </b-form-invalid-feedback>
                  <small v-if="allerrors.password" class="text-danger">{{
                    allerrors.password[0]
                  }}</small>
                </validation-provider>
              </b-form-group>

              <!-- confirm password -->
              <b-form-group label="Confirm Password" label-for="password_confirmation">
                <validation-provider
                  #default="{ errors }"
                  name="password confirmation"
                  rules="required|confirmed:Password"
                >
                  <b-input-group
                    class="input-group-merge"
                    :class="errors.length > 0 ? 'is-invalid' : null"
                  >
                    <b-form-input
                      id="password_confirmation"
                      v-model="password_confirmation"
                      :state="errors.length > 0 ? false : null"
                      class="form-control-merge"
                      :type="passwordFieldType"
                      name="password_confirmation"
                      placeholder="············"
                    />
                    <b-input-group-append is-text>
                      <feather-icon
                        class="cursor-pointer"
                        :icon="passwordToggleIcon"
                        @click="togglePasswordVisibility"
                      />
                    </b-input-group-append>
                  </b-input-group>
                  <b-form-invalid-feedback class="text-danger" v-if="errors">
                    {{ errors[0] }}
                  </b-form-invalid-feedback>
                  <small v-if="allerrors.password_confirmation" class="text-danger">{{
                    allerrors.password_confirmation[0]
                  }}</small>
                  <small v-if="allerrors.error_msg" class="text-danger">{{
                    allerrors.error_msg[0]
                  }}</small>
                </validation-provider>
              </b-form-group>

              <!-- submit buttons -->
              <b-button type="submit" variant="primary" block>
                Generate Password
              </b-button>
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

import { ValidationProvider, ValidationObserver } from "vee-validate";
import VuexyLogo from "@core/layouts/components/Logo.vue";
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
  BButton,
} from "bootstrap-vue";
import { required, password, confirmed } from "@validations";
import { togglePasswordVisibility } from "@core/mixins/ui/forms";
import store from "@/store/index";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import { $themeConfig } from "@themeConfig";

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
    VuexyLogo,
    ValidationProvider,
    ValidationObserver,
  },
  mixins: [togglePasswordVisibility],
  data() {
    return {
      allerrors: [],
      status: "",
      password_confirmation: "",
      password: "",
      device_name: "browser",
      sideImg: require("@/assets/images/pages/login-v2.svg"),
      // validation rulesimport store from '@/store/index'
      required,
      password,
      confirmed,
      processing: false,
    };
  },
  setup() {
    // App Name
    const { appName, appLogoImage } = $themeConfig.app;
    return {
      appName,
      appLogoImage,
    };
  },
  computed: {
    passwordToggleIcon() {
      return this.passwordFieldType === "password" ? "EyeIcon" : "EyeOffIcon";
    },
    imgUrl() {
      if (store.state.appConfig.layout.skin === "dark") {
        // eslint-disable-next-line vue/no-side-effects-in-computed-properties
        this.sideImg = require("@/assets/images/pages/login-v2-dark.svg");
        return this.sideImg;
      }
      return this.sideImg;
    },
  },
  created() {
    this.checkPasswordGenerateLink();
  },
  methods: {
    async checkPasswordGenerateLink() {
      this.processing = true;
      var this_this = this;

      this.allerrors = [];
      await axios
        .post("/checkuserpassword?signature=" + this.$route.query.signature, {
          user: this.$route.query.user,
        })
        .then((response) => {
          if (response.data.type == "danger") {
            this.$toast({
              component: ToastificationContent,
              props: {
                title: response.data.message,
                icon: "BellIcon",
                variant: response.data.type,
              },
            });
            this.$router.replace("/login");
          }
        })
        .catch(({ response }) => {
          console.log(response);
          this.allerrors = response.data.errors;
          this.signIn(response);
        })
        .finally(() => {
          this.processing = false;
        });
    },
    ...mapActions({
      signIn: "login",
    }),
    async generatePassword() {
      this.processing = true;
      var this_this = this;

      this.allerrors = [];
      await axios
        .post("/generate/password?signature=" + this.$route.query.signature, {
          user: this.$route.query.user,
          password: this.password,
          password_confirmation: this.password_confirmation,
        })
        .then((response) => {
          this.$toast({
            component: ToastificationContent,
            props: {
              title: response.data.message,
              icon: "BellIcon",
              variant: response.data.type,
            },
          });
          if (response.data.user) {
            this.$router.replace("/login");
          }
        })
        .catch(({ response }) => {
          console.log(response);
          this.allerrors = response.data.errors;
          this.signIn(response);
        })
        .finally(() => {
          this.processing = false;
        });
    },
  },
};
</script>

<style lang="scss">
@import "@core/scss/vue/pages/page-auth.scss";
</style>
