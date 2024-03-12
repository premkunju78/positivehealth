<template>
  <div class="auth-wrapper auth-v2">
    <b-row class="auth-inner m-0">
      <!-- Brand logo-->
      <b-link class="brand-logo">
        <b-img
          fluid
          v-if="logo"
          :src="'/view/file?path=' + logo"
          alt="Logo"
          height="50"
          width="50"
        />
        <!-- <vuexy-logo  v-else/>-->

        <b-img v-if="!logo" :src="appLogoImage" alt="logo" />
      </b-link>
      <!-- /Brand logo-->

      <!-- Left Text-->
      <b-col lg="8" class="d-none d-lg-flex align-items-center p-5">
        <div class="w-100 d-lg-flex align-items-center justify-content-center px-5">
          <b-img
            fluid
            v-if="banner_img"
            :src="'/view/file?path=' + banner_img"
            alt="Login V2"
          />
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
          <b-form class="auth-login-form mt-2" @submit.prevent>
            <!-- email -->
            <b-form-group label="Email/Phone" label-for="login-email">
              <validation-provider
                #default="{ errors }"
                name="Email/Phone"
                rules="required"
              >
                <b-form-input
                  id="login-email"
                  v-model="email"
                  :state="errors.length > 0 ? false : null"
                  name="login-email"
                  placeholder="john@example.com"
                />
                <small v-if="allerrors.email" class="text-danger">{{
                  allerrors.email[0]
                }}</small>
                <small v-if="allerrors.email_msg" class="text-danger">{{
                  allerrors.email_msg[0]
                }}</small>
              </validation-provider>
            </b-form-group>

            <!-- forgot password -->
            <b-form-group>
              <div class="d-flex justify-content-between">
                <label for="login-password">Password</label>
                <b-link :to="{ name: 'auth-forgot-password-v2' }">
                  <small>Forgot Password?</small>
                </b-link>
              </div>
              <validation-provider #default="{ errors }" name="Password" rules="required">
                <b-input-group
                  class="input-group-merge"
                  :class="errors.length > 0 ? 'is-invalid' : null"
                >
                  <b-form-input
                    id="login-password"
                    v-model="password"
                    :state="errors.length > 0 ? false : null"
                    class="form-control-merge"
                    :type="passwordFieldType"
                    name="login-password"
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
                <small v-if="allerrors.password" class="text-danger">{{
                  allerrors.password[0]
                }}</small>
                <small v-if="allerrors.error_msg" class="text-danger">{{
                  allerrors.error_msg[0]
                }}</small>
              </validation-provider>
            </b-form-group>

            <!-- checkbox -->
            <b-form-group>
              <b-form-checkbox id="remember-me" v-model="status" name="checkbox-1">
                Remember Me
              </b-form-checkbox>
            </b-form-group>

            <!-- submit buttons -->
            <b-button type="submit" variant="primary" block @click="login">
              Sign in
            </b-button>
          </b-form>

          <b-card-text class="text-center mt-2">
            <span>New on our platform? </span>
            <b-link :to="{ name: 'page-auth-register-v2' }">
              <span>&nbsp;Create an account</span>
            </b-link>
          </b-card-text>
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
import { required, email } from "@validations";
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
    ValidationProvider,
    ValidationObserver,
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
    return {
      appName,
      appLogoImage,
    };
  },
  data() {
    return {
      allerrors: [],
      status: "",
      email: "",
      password: "",
      device_name: "browser",
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
  methods: {
    ...mapActions({
      signIn: "login",
    }),
    async login() {
      this.processing = true;
      var this_this = this;
      //console.log('logged');

      this.allerrors = [];
      await axios.get("/sanctum/csrf-cookie");
      await axios
        .post("/login", {
          email: this.email,
          password: this.password,
          device_name: this.device_name,
          url: this.url,
        })
        .then((response) => {
          this.signIn(response);

          this.$toast({
            component: ToastificationContent,
            props: {
              title: "logged in successfully",
              icon: "EditIcon",
              variant: "success",
            },
          });
        })
        .catch(({ response }) => {
          this_this.allerrors = response.data;
          this.signIn(response);
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
        this.logo = data.user.logo;
        this.welcome_message = data.user.welcome_message;
      } catch (error) {
        console.log(error);
      }
    },
  },
};
</script>

<style lang="scss">
@import "@core/scss/vue/pages/page-auth.scss";
</style>
