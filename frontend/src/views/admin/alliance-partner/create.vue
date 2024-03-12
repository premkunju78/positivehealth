<template>
  <b-card>
    <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
      <!-- User Info: Input Fields -->
      <b-form @submit.prevent="handleSubmit(onSubmit)">
        <b-row class="d-flex justify-content-center">
          <b-col cols="20" md="10">
            <h4>General Details</h4>
            <b-row class="mt-2 mb-2">
              <!-- company name -->
              <b-col cols="12" md="4">
                <validation-provider
                  #default="validationContext"
                  name="Company Name"
                  rules="required"
                >
                  <b-form-group label="Company/Institute Name" label-for="company-name">
                    <b-form-input
                      id="company-name"
                      v-model="userData.company_name"
                      :state="getValidationState(validationContext)"
                      placeholder="ABC Solutions PVT. LTD"
                    />
                    <b-form-invalid-feedback>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>
              <!-- first name -->
              <b-col cols="12" md="4">
                <!-- First Name -->
                <validation-provider
                  #default="validationContext"
                  name="Contact Person First Name"
                  rules="required"
                >
                  <b-form-group label="Contact Person First Name" label-for="first_name">
                    <b-form-input
                      id="first_name"
                      v-model="userData.first_name"
                      :state="getValidationState(validationContext)"
                      trim
                      placeholder="John"
                      :class="errors.first_name ? 'is-invalid' : ''"
                      :area-invalid="errors.first_name ? true : false"
                    />
                    <b-form-invalid-feedback v-if="errors.first_name">
                      {{ errors.first_name[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-else>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>
              <!-- last name -->
              <b-col cols="12" md="4">
                <!-- Last Name -->
                <validation-provider
                  #default="validationContext"
                  name="Contact Person Last Name"
                  rules="required"
                >
                  <b-form-group label="Contact Person Last Name" label-for="last_name">
                    <b-form-input
                      id="last_name"
                      v-model="userData.last_name"
                      :state="getValidationState(validationContext)"
                      trim
                      placeholder="Doe"
                      :class="errors.last_name ? 'is-invalid' : ''"
                      :area-invalid="errors.last_name ? true : false"
                    />
                    <b-form-invalid-feedback v-if="errors.last_name">
                      {{ errors.last_name[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-else>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>
              <!-- email -->
              <b-col cols="12" md="4">
                <!-- Email -->
                <validation-provider
                  #default="validationContext"
                  name="Email"
                  rules="required|email"
                >
                  <b-form-group label="Email" label-for="email">
                    <b-form-input
                      id="email"
                      v-model="userData.email"
                      :state="getValidationState(validationContext)"
                      trim
                      placeholder="john@gmail.com"
                      :class="errors.email ? 'is-invalid' : ''"
                      :area-invalid="errors.email ? true : false"
                    />
                    <b-form-invalid-feedback v-if="errors.email">
                      {{ errors.email[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-else>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>
              <!-- Phone -->
              <b-col cols="12" md="4">
                <!-- Phone Number -->
                <validation-provider
                  #default="validationContext"
                  name="Phone Number"
                  rules="required"
                >
                  <b-form-group label="Phone Number" label-for="phone number">
                    <b-form-input
                      id="phone_number"
                      v-model="userData.phone"
                      type="number"
                      placeholder="9877766556"
                      :state="getValidationState(validationContext)"
                      :class="errors.phone ? 'is-invalid' : ''"
                      :area-invalid="errors.phone ? true : false"
                    />
                    <b-form-invalid-feedback v-if="errors.phone">
                      {{ errors.phone[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-else>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>
              <!-- Group -->
              <b-col cols="12" md="4">
                <validation-provider
                  #default="validationContext"
                  name="Group"
                  rules="required"
                >
                  <b-form-group
                    label="Group"
                    label-for="group"
                    :state="getValidationState(validationContext)"
                  >
                    <v-select
                      v-model="userData.group"
                      :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                      :options="groupOptions"
                      :clearable="false"
                      input-id="group"
                    />
                    <b-form-invalid-feedback
                      :state="getValidationState(validationContext)"
                    >
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>
              <!-- Sub Group -->
              <b-col cols="12" md="4">
                <b-form-group label="Sub Group" label-for="sub_group">
                  <b-form-input
                    id="sub_group"
                    v-model="userData.type"
                    trim
                    placeholder="Sub Group"
                  />
                </b-form-group>
              </b-col>
              <!-- URL -->
              <b-col cols="12" md="6">
                <validation-provider
                  #default="validationContext"
                  name="URL"
                  rules="required"
                >
                  <b-form-group label="Enter URL" label-for="url">
                    <b-input-group
                      :prepend="url"
                      append="/login"
                      class="input-group-merge"
                    >
                      <!--  <b-input-group>-->
                      <b-form-input
                        id="url"
                        ref="url"
                        v-model="userData.url"
                        :state="getValidationState(validationContext)"
                      />
                    </b-input-group>
                    <b-form-invalid-feedback
                      :state="getValidationState(validationContext)"
                    >
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
                <b-button
                  v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                  variant="primary"
                  size="sm"
                  class="mb-75 mr-75"
                  @click="copyUrl"
                >
                  <a
                    href="#"
                    slot="append"
                    class="input-group-append"
                    @click="copyUrl"
                  ></a>
                  Copy
                </b-button>
              </b-col>
            </b-row>
            <h4>Bank Details</h4>
            <b-row class="mt-2 mb-2">
              <!-- Bank Name -->
              <b-col cols="12" md="4">
                <b-form-group label-for="bank" label="Bank Name">
                  <b-form-input
                    id="bank"
                    v-model="userData.bank_name"
                    placeholder="Bank Name"
                  />
                </b-form-group>
              </b-col>
              <!-- account no -->
              <b-col cols="12" md="4">
                <b-form-group label-for="account_no" label="Account Number">
                  <b-form-input
                    id="account_no"
                    v-model="userData.account_no"
                    placeholder="Account Number"
                  />
                </b-form-group>
              </b-col>
              <!-- IFSC Code -->
              <b-col cols="12" md="4">
                <b-form-group label-for="ifsc" label="IFSC Code">
                  <b-form-input
                    id="ifsc"
                    v-model="userData.ifsc_code"
                    placeholder="IFSC Code"
                  />
                </b-form-group>
              </b-col>
              <!-- cancelled cheque -->
              <b-col cols="12" md="6" class="mt-2 mb-2">
                <!-- media -->
                <b-media
                  no-body
                  :class="errors.cheque ? 'is-invalid' : ''"
                  :area-invalid="errors.cheque ? true : false"
                >
                  <b-media-aside>
                    <a
                      :href="'/view/file?path=' + userData.cancelled_cheque"
                      target="_blank"
                    >
                      <b-img
                        ref="previewChequeEl"
                        rounded
                        :src="'/view/file?path=' + userData.cancelled_cheque"
                        height="80"
                      />
                    </a>
                    <!--/ avatar -->
                  </b-media-aside>

                  <b-media-body class="mt-75 ml-75">
                    <!-- upload button -->
                    <b-button
                      v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                      variant="primary"
                      size="sm"
                      class="mb-75 mr-75"
                      @click="$refs.refChequeEl.$el.click()"
                    >
                      Cancelled Cheque
                    </b-button>
                    <b-form-file
                      ref="refChequeEl"
                      v-model="cheque"
                      accept=".jpg, .png, .gif"
                      :hidden="true"
                      plain
                      @input="validateSize(cheque, 'cheque', inputChequeRenderer)"
                    />
                    <!--/ upload button -->

                    <!-- reset -->
                    <b-button
                      v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                      variant="outline-secondary"
                      size="sm"
                      class="mb-75 mr-75"
                      v-if="cheque"
                      @click="
                        $refs.previewChequeEl.src =
                          '/view/file?path=' + userData.cancelled_cheque;
                        cheque = null;
                      "
                    >
                      Reset
                    </b-button>
                    <!--/ reset -->
                    <small>Allowed JPG, GIF or PNG. Max size 2mb</small>
                  </b-media-body>
                </b-media>
                <b-form-invalid-feedback v-if="errors.cheque">
                  {{ errors.cheque[0] }}
                </b-form-invalid-feedback>
              </b-col>
            </b-row>
            <h4>Other Details</h4>
            <b-row class="mt-2 mb-2">
              <!-- Website -->
              <b-col cols="12" md="4">
                <b-form-group label="Website" label-for="website">
                  <b-form-input id="website" v-model="userData.website_name" />
                </b-form-group>
              </b-col>
              <!-- Address -->
              <b-col cols="12" md="4">
                <b-form-group label-for="address" label="Address">
                  <b-form-textarea
                    id="address"
                    v-model="userData.address"
                    rows="3"
                    placeholder="address"
                  />
                </b-form-group>
              </b-col>
              <!-- City -->
              <b-col cols="12" md="4">
                <b-form-group label-for="city" label="City">
                  <b-form-input id="city" v-model="userData.city" placeholder="city" />
                </b-form-group>
              </b-col>
              <!-- Pincode -->
              <b-col cols="12" md="4">
                <b-form-group label-for="pin" label="Pincode">
                  <b-form-input id="pin" v-model="userData.pincode" placeholder="pin" />
                </b-form-group>
              </b-col>
              <!-- State -->
              <b-col cols="12" md="4">
                <b-form-group label-for="state" label="State">
                  <b-form-input id="state" v-model="userData.state" placeholder="State" />
                </b-form-group>
              </b-col>
              <!-- Address -->
              <b-col cols="12" md="12">
                <b-form-group label-for="welcome_message" label="Welcome Message">
                  <quill-editor
                    id="welcome_message"
                    v-model="userData.welcome_message"
                    :options="editorOption"
                    rows="4"
                  />
                </b-form-group>
              </b-col>
              <!-- Logo  -->
              <b-col cols="12" md="6" class="mt-2 mb-2">
                <!-- media -->
                <b-media
                  no-body
                  :class="errors.logo ? 'is-invalid' : ''"
                  :area-invalid="errors.logo ? true : false"
                >
                  <b-media-aside>
                    <a :href="'/view/file?path=' + userData.logo" target="_blank">
                      <b-img
                        ref="previewLogoEl"
                        rounded
                        :src="'/view/file?path=' + userData.logo"
                        height="80"
                      />
                    </a>
                    <!--/ avatar -->
                  </b-media-aside>

                  <b-media-body class="mt-75 ml-75">
                    <!-- upload button -->
                    <b-button
                      v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                      variant="primary"
                      size="sm"
                      class="mb-75 mr-75"
                      @click="$refs.refLogoEl.$el.click()"
                    >
                      Logo
                    </b-button>
                    <b-form-file
                      ref="refLogoEl"
                      v-model="logo"
                      accept=".jpg, .png, .gif"
                      :hidden="true"
                      plain
                      @input="validateSize(logo, 'logo', inputLogoRenderer)"
                    />
                    <!--/ upload button -->

                    <!-- reset -->
                    <b-button
                      v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                      variant="outline-secondary"
                      size="sm"
                      class="mb-75 mr-75"
                      v-if="logo"
                      @click="
                        $refs.previewLogoEl.src = '/view/file?path=' + userData.logo;
                        logo = null;
                      "
                    >
                      Reset
                    </b-button>
                    <!--/ reset -->
                    <small>Allowed JPG, GIF or PNG. Max size 2mb</small>
                  </b-media-body>
                </b-media>
                <b-form-invalid-feedback v-if="errors.logo">
                  {{ errors.logo[0] }}
                </b-form-invalid-feedback>
              </b-col>
              <!-- Banner  -->
              <b-col cols="12" md="6" class="mt-2 mb-2">
                <!-- media -->
                <b-media
                  no-body
                  :class="errors.banner ? 'is-invalid' : ''"
                  :area-invalid="errors.banner ? true : false"
                >
                  <b-media-aside>
                    <a :href="'/view/file?path=' + userData.banner_img" target="_blank">
                      <b-img
                        ref="previewBannerEl"
                        rounded
                        :src="'/view/file?path=' + userData.banner_img"
                        height="80"
                      />
                    </a>
                    <!--/ avatar -->
                  </b-media-aside>

                  <b-media-body class="mt-75 ml-75">
                    <!-- upload button -->
                    <!--allowed for alliancepartner-->
                    <b-button
                      v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                      variant="primary"
                      size="sm"
                      class="mb-75 mr-75"
                      @click="$refs.refBannerEl.$el.click()"
                    >
                      Banner
                    </b-button>
                    <b-form-file
                      ref="refBannerEl"
                      v-model="banner"
                      accept=".jpg, .png, .gif"
                      :hidden="true"
                      plain
                      @input="validateSize(banner, 'banner', inputBannerRenderer)"
                    />
                    <!--/ upload button -->

                    <!-- reset -->
                    <b-button
                      v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                      variant="outline-secondary"
                      size="sm"
                      class="mb-75 mr-75"
                      v-if="banner"
                      @click="
                        $refs.previewBannerEl.src =
                          '/view/file?path=' + userData.banner_img;
                        banner = null;
                      "
                    >
                      Reset
                    </b-button>
                    <!--/ reset -->
                    <small>Allowed JPG, GIF or PNG. Max size 2mb</small>
                  </b-media-body>
                </b-media>
                <b-form-invalid-feedback v-if="errors.banner">
                  {{ errors.banner[0] }}
                </b-form-invalid-feedback>
              </b-col>
            </b-row>
          </b-col>
        </b-row>
        <b-row style="margin-top: 5%; margin-left: 40%">
          <!-- Action Buttons -->
          <router-link :to="{ name: 'alliance-partners' }">
            <b-button
              variant="outline-secondary"
              type="reset"
              class="mb-1 mb-sm-0 mr-0 mr-sm-1"
              :block="$store.getters['app/currentBreakPoint'] === 'xs'"
            >
              Back
            </b-button>
          </router-link>
          <b-button
            variant="primary"
            :block="$store.getters['app/currentBreakPoint'] === 'xs'"
            type="submit"
            v-permission="['create-users','create-alliance-partners']"
          >
            Save Changes
          </b-button>
        </b-row>
      </b-form>
    </validation-observer>
  </b-card>
</template>

<script>
import {
  BButton,
  BAvatar,
  BRow,
  BCol,
  BFormGroup,
  BFormInput,
  BForm,
  BInputGroup,
  BTable,
  BCard,
  BCardHeader,
  BCardTitle,
  BCardText,
  BFormCheckbox,
  BFormTextarea,
  BFormInvalidFeedback,
  BMedia,
  BMediaAside,
  BMediaBody,
  BFormFile,
  BLink,
  BImg,
} from "bootstrap-vue";
import { avatarText } from "@core/utils/filter";
import { required, email } from "@validations";
import formValidation from "@core/comp-functions/forms/form-validation";
import vSelect from "vue-select";
import { useInputImageRenderer } from "@core/comp-functions/forms/form-utils";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { ref } from "@vue/composition-api";

import Ripple from "vue-ripple-directive";

import { validateSize } from "@core/utils/utils";

import "quill/dist/quill.snow.css";
import { quillEditor } from "vue-quill-editor";

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

export default {
  directives: {
    Ripple,
  },
  components: {
    BButton,
    BAvatar,
    BRow,
    BCol,
    BFormGroup,
    BFormInput,
    BForm,
    BInputGroup,
    BTable,
    BCard,
    BCardHeader,
    BCardTitle,
    BCardText,
    BFormCheckbox,
    BFormTextarea,
    vSelect,
    BFormInvalidFeedback,
    BMedia,
    BMediaAside,
    BMediaBody,
    BFormFile,
    BLink,
    BImg,

    ValidationProvider,
    ValidationObserver,
    quillEditor,
  },
  data() {
    const { refFormObserver, getValidationState, resetForm } = formValidation(
      this.resetuserData
    );
    var toolbarOptions = [
      ["bold", "italic", "underline", "strike"], // toggled buttons
      [{ list: "ordered" }, { list: "bullet" }],
      [{ indent: "-1" }, { indent: "+1" }], // outdent/indent
      [{ direction: "rtl" }], // text direction
      [{ header: [1, 2, 3, 4, 5, 6, false] }],
      [{ color: [] }], // dropdown with defaults from theme
      [{ font: [] }],
      [{ align: [] }], // remove formatting button
    ];
    return {
      editorOption: {
        modules: {
          toolbar: toolbarOptions,
        },
        theme: "snow",
      },
      url: process.env.MIX_URL,
      required,
      email,
      groupOptions: [
        "Doctors",
        "Healthcare Professionals",
        "Institute",
        "Company",
        "Corporates",
        "Hospitals",
      ],
      partnerTypes: ["Doctors", "Healthcare Professionals"],
      userData: {
        role: 2,
        name: "",
        email: "",
        phone: null,
        fist_name: "",
        last_name: "",
        logo: null,
        url: "",
        website_name: "",
        group: "",
        type: "",
        banner_img: null,
        signature_img: null,
        cancelled_cheque: null,
        company_name: "",
        company_contact: "",
        welcome_message: "",
        address: "",
        city: "",
        state: "",
        pincode: "",
      },
      refFormObserver,
      getValidationState,
      resetForm,
      logo: null,
      signature: null,
      banner: null,
      cheque: null,
      errors: [],
      maxSize: 2097152,
    };
  },
  props: {
    user: {
      type: Object,
      default: () => {},
    },
    id: {
      type: [Number, String],
      default: null,
    },
  },
  setup(props) {
    // ? Demo Purpose => Update image on click of update
    const refLogoEl = ref(null);
    const previewLogoEl = ref(null);
    const refSignatureEl = ref(null);
    const previewSignatureEl = ref(null);
    const refBannerEl = ref(null);
    const previewBannerEl = ref(null);
    const refChequeEl = ref(null);
    const previewChequeEl = ref(null);

    const inputLogoRenderer = useInputImageRenderer(refLogoEl, previewLogoEl);
    const inputSignatureRenderer = useInputImageRenderer(
      refSignatureEl,
      previewSignatureEl
    );
    const inputBannerRenderer = useInputImageRenderer(refBannerEl, previewBannerEl);
    const inputChequeRenderer = useInputImageRenderer(refChequeEl, previewChequeEl);

    return {
      refLogoEl,
      previewLogoEl,
      inputLogoRenderer,
      refSignatureEl,
      previewSignatureEl,
      inputSignatureRenderer,
      refBannerEl,
      previewBannerEl,
      inputBannerRenderer,
      refChequeEl,
      previewChequeEl,
      inputChequeRenderer,
    };
  },

  created() {
    if (this.id) {
      this.fetchUserData();
      // this.userData = Object.assign({}, this.user);
    }
  },

  methods: {
    validateSize,
    async onSubmit() {
      try {
        this.errors = [];
        let data;
        if (this.userData.phone) {
          if (this.userData.phone.length < 10 || this.userData.phone.length > 11) {
            this.errors = {
              phone: ["please provide a valid number"],
            };
            return false;
          }
        }
        let userData = new FormData();
        userData.append("role", this.userData.role);
        userData.append("name", this.userData.name);
        userData.append("email", this.userData.email);
        userData.append("phone", this.userData.phone);
        userData.append("first_name", this.userData.first_name);
        userData.append("last_name", this.userData.last_name);
        userData.append("url", this.userData.url);
        userData.append("logo", this.logo);
        userData.append("signature_img", this.signature);
        userData.append("banner_img", this.banner);
        userData.append("company_name", this.userData.company_name);
        userData.append("website_name", this.userData.website_name);
        userData.append("group", this.userData.group);
        userData.append("type", this.userData.type);
        userData.append("company_contact", this.userData.company_contact);
        userData.append("welcome_message", this.userData.welcome_message);
        userData.append("bank_name", this.userData.bank_name);
        userData.append("account_no", this.userData.account_no);
        userData.append("ifsc_code", this.userData.ifsc_code);
        userData.append("cancelled_cheque", this.cheque);
        userData.append("address", this.userData.address);
        userData.append("city", this.userData.city);
        userData.append("pincode", this.userData.pincode);
        userData.append("state", this.userData.state);

        if (this.id) {
          data = await axios.post(`/alliance-partners/${this.userData.id}`, userData, {
            headers: {
              "content-type": "multipart/form-data",
            },
          });
        } else {
          data = await axios.post("/alliance-partners", userData, {
            headers: {
              "content-type": "multipart/form-data",
            },
          });
        }
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.data.message,
            icon: "EditIcon",
            variant: data.data.success ? "success" : "info",
          },
        });
        this.$router.replace("/alliance-partners/list");
      } catch (error) {
        if (error.response) {
          this.errors = error.response.data.errors;
        }
      }
    },
    async fetchUserData() {
      try {
        const { data } = await axios.get(`/consultant/${this.id}`);
        this.userData = data.user;
      } catch (err) {
        console.log(err);
      }
    },
    setFile(e, prop) {
      this.userData[prop] = e.target.files[0];
    },
    resetuserData() {
      this.userData.value = {
        role: 2,
        name: "",
        email: "",
        phone: null,
        fist_name: "",
        last_name: "",
        logo: null,
        url: "",
        website_name: "",
        group: "",
        type: "",
        banner_img: null,
        signature_img: null,
        company_name: "",
        company_contact: "",
        welcome_message: "",
        address: "",
        pincode: "",
      };
    },
    async copyUrl() {
      var Url = this.url + "" + this.userData.url + "/login";

      // await navigator.clipboard.writeText(Url);

      if (navigator.clipboard && window.isSecureContext) {
        // navigator clipboard api method'
        await navigator.clipboard.writeText(textToCopy);
      } else {
        let element = document.createElement("input");
        document.body.appendChild(element);
        element.value = Url;
        element.focus();
        element.select();
        await document.execCommand("copy");
        document.body.removeChild(element);
      }
      alert("Copied!");
    },
  },
};
</script>

<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";
.input-group-text {
  padding-right: 0 !important;
  margin-right: 0 !important;
}
</style>
