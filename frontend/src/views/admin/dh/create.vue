<template>
  <b-card>
    <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
      <!-- User Info: Input Fields -->
      <b-form @submit.prevent="handleSubmit(onSubmit)">
        <b-row class="d-flex justify-content-center">
          <b-col cols="20" md="10">
            <h4>General Details</h4>
            <b-row class="mt-2 mb-2">
              <b-col cols="12" md="4">
                <validation-provider #default="validationContext" name="Company Name" rules="required">
                  <b-form-group label="Company/Institute Name" label-for="company-name">
                    <b-form-input id="company-name" v-model="userData.company_name"
                      :state="getValidationState(validationContext)" placeholder="ABC Solutions PVT. LTD" />
                    <b-form-invalid-feedback>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>
              <!-- first name -->
              <b-col cols="12" md="4">
                <!-- First Name -->
                <validation-provider #default="validationContext" name="Contact Person First Name" rules="required">
                  <b-form-group label="Contact Person First Name" label-for="first_name">
                    <b-form-input id="first_name" v-model="userData.first_name"
                      :state="getValidationState(validationContext)" trim placeholder="John"
                      :class="errors.first_name ? 'is-invalid' : ''" :area-invalid="errors.first_name ? true : false" />
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
                <validation-provider #default="validationContext" name="Contact Person Last Name" rules="required">
                  <b-form-group label="Contact Person Last Name" label-for="last_name">
                    <b-form-input id="last_name" v-model="userData.last_name"
                      :state="getValidationState(validationContext)" trim placeholder="Doe"
                      :class="errors.last_name ? 'is-invalid' : ''" :area-invalid="errors.last_name ? true : false" />
                    <b-form-invalid-feedback v-if="errors.last_name">
                      {{ errors.last_name[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-else>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>
              <b-col cols="12" md="4">
                <!-- Email -->
                <validation-provider #default="validationContext" name="Email" rules="required|email">
                  <b-form-group label="Email" label-for="email">
                    <b-form-input id="email" v-model="userData.email" :state="getValidationState(validationContext)" trim
                      :class="errors.email ? 'is-invalid' : ''" :area-invalid="errors.email ? true : false" />
                    <b-form-invalid-feedback v-if="errors.email">
                      {{ errors.email[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-else>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>
              <b-col cols="12" md="4">
                <!-- Phone Number -->
                <validation-provider #default="validationContext" name="Phone Number" rules="required">
                  <b-form-group label="Phone Number" label-for="phone number">
                    <b-form-input id="phone_number" v-model="userData.phone" type="number" placeholder="9877766556"
                      :state="getValidationState(validationContext)" :class="errors.phone ? 'is-invalid' : ''"
                      :area-invalid="errors.phone ? true : false" />
                    <b-form-invalid-feedback v-if="errors.phone">
                      {{ errors.phone[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-else>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>

              <b-col cols="12" md="4">
                <!-- Percentage -->
                <validation-provider #default="validationContext" name="Percentage" rules="required">
                  <b-form-group label="Percentage" label-for="percentage">
                    <b-form-input id="percentage" v-model="userData.percentage" type="number" placeholder="%"
                      :state="getValidationState(validationContext)" :class="errors.percentage ? 'is-invalid' : ''"
                      :area-invalid="errors.percentage ? true : false" />
                    <b-form-invalid-feedback v-if="errors.percentage">
                      {{ errors.percentage[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-else>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>

              <!-- idproof -->
              <b-col cols="12" md="6" class="mt-2 mb-2">
                <!-- media -->
                <b-media no-body :class="errors.idproof ? 'is-invalid' : ''"
                  :area-invalid="errors.idproof ? true : false">
                  <b-media-aside>
                    <a :href="'/view/file?path=' + userData.idproof" target="_blank">
                      <b-img ref="previewIDProofEl" rounded :src="'/view/file?path=' + userData.idproof" height="80" />
                    </a>
                    <!--/ avatar -->
                  </b-media-aside>

                  <b-media-body class="mt-75 ml-75">
                    <!-- upload button -->
                    <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" size="sm" class="mb-75 mr-75"
                      @click="$refs.refIDProofEl.$el.click()">
                      ID Proof
                    </b-button>
                    <b-form-file ref="refIDProofEl" v-model="idproof" accept=".jpg, .png, .gif" :hidden="true" plain
                      @input="validateSize(idproof, 'idproof', inputIDProofRenderer)" />
                    <!--/ upload button -->

                    <!-- reset -->
                    <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" variant="outline-secondary" size="sm"
                      class="mb-75 mr-75" v-if="idproof" @click="
                        $refs.previewIDProofEl.src =
                        '/view/file?path=' + userData.idproof;
                      idproof = null;
                      ">
                      Reset
                    </b-button>
                    <!--/ reset -->
                    <small>Allowed JPG, GIF or PNG. Max size 2mb</small>
                  </b-media-body>
                </b-media>
                <b-form-invalid-feedback v-if="errors.idproof">
                  {{ errors.idproof[0] }}
                </b-form-invalid-feedback>
              </b-col>
            </b-row>
            <h4>Bank Details</h4>
            <b-row class="mt-2 mb-2">
              <!-- Bank Name -->
              <b-col cols="12" md="4">
                <b-form-group label-for="bank" label="Bank Name">
                  <b-form-input id="bank" v-model="userData.bank_name" placeholder="Bank Name" />
                </b-form-group>
              </b-col>

              <!-- account no -->
              <b-col cols="12" md="4">
                <b-form-group label-for="account_no" label="Account Number">
                  <b-form-input id="account_no" v-model="userData.account_no" placeholder="Account Number" />
                </b-form-group>
              </b-col>

              <!-- IFSC Code -->
              <b-col cols="12" md="4">
                <b-form-group label-for="ifsc" label="IFSC Code">
                  <b-form-input id="ifsc" v-model="userData.ifsc_code" placeholder="IFSC Code" />
                </b-form-group>
              </b-col>

              <!-- cancelled cheque -->
              <b-col cols="12" md="6" class="mt-2 mb-2">
                <!-- media -->
                <b-media no-body :class="errors.cheque ? 'is-invalid' : ''" :area-invalid="errors.cheque ? true : false">
                  <b-media-aside>
                    <a :href="'/view/file?path=' + userData.cancelled_cheque" target="_blank">
                      <b-img ref="previewChequeEl" rounded :src="'/view/file?path=' + userData.cancelled_cheque"
                        height="80" />
                    </a>
                    <!--/ avatar -->
                  </b-media-aside>

                  <b-media-body class="mt-75 ml-75">
                    <!-- upload button -->
                    <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" size="sm" class="mb-75 mr-75"
                      @click="$refs.refChequeEl.$el.click()">
                      Cancelled Cheque
                    </b-button>
                    <b-form-file ref="refChequeEl" v-model="cheque" accept=".jpg, .png, .gif" :hidden="true" plain
                      @input="validateSize(cheque, 'cheque', inputChequeRenderer)" />
                    <!--/ upload button -->

                    <!-- reset -->
                    <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" variant="outline-secondary" size="sm"
                      class="mb-75 mr-75" v-if="cheque" @click="
                        $refs.previewChequeEl.src =
                        '/view/file?path=' + userData.cancelled_cheque;
                      cheque = null;
                      ">
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
              <b-col cols="12" md="4">
                <b-form-group label="Website" label-for="website">
                  <b-form-input id="website" v-model="userData.website_name" />
                </b-form-group>
              </b-col>
              <b-col cols="12" md="4">
                <b-form-group label="Area of interest" label-for="area_of_interest">
                  <b-form-input id="area_of_interest" v-model="userData.area_of_interest" />
                </b-form-group>
              </b-col>
              <!-- <b-col cols="12" md="4">
                <b-form-group label="Date of Birth" label-for="dob">
                  <flat-pickr
                    v-model="userData.dob"
                    class="form-control"
                    :config="{ enableTime: false, dateFormat: 'd-m-Y' }"
                  />
                </b-form-group>
              </b-col>
              <b-col cols="12" md="4">
                <b-form-group label="Gender" label-for="gender">
                  <b-form-radio-group
                    v-model="userData.gender"
                    :options="genders"
                    class="demo-inline-spacing"
                    name="gender"
                    id="gender"
                  />
                </b-form-group>
              </b-col> -->
              <b-col cols="12" md="4">
                <b-form-group label-for="address" label="Address">
                  <b-form-textarea id="address" v-model="userData.address" rows="3" placeholder="address" />
                </b-form-group>
              </b-col>
              <b-col cols="12" md="4">
                <b-form-group label-for="city" label="City">
                  <b-form-input id="city" v-model="userData.city" placeholder="city" />
                </b-form-group>
              </b-col>
              <b-col cols="12" md="4">
                <b-form-group label-for="city-presence" label="City Presence">
                  <v-select
                    v-model="userData.available_cities"
                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="cityOptions"
                    :reduce="(val) => val.id"
                    :clearable="false"
                    input-id="referred-presence-city" 
                    multiple
                  />
                </b-form-group>
              </b-col>
              <b-col cols="12" md="4">
                <b-form-group label-for="pin" label="Pincode">
                  <b-form-input id="pin" v-model="userData.pincode" placeholder="pin" />
                </b-form-group>
              </b-col>
              <b-col cols="12" md="4">
                <b-form-group label-for="state" label="State">
                  <b-form-input id="state" v-model="userData.state" placeholder="State" />
                </b-form-group>
              </b-col>
              <!-- Logo  -->
              <b-col cols="12" md="6" class="mt-2 mb-2">
                <!-- media -->
                <b-media no-body :class="errors.logo ? 'is-invalid' : ''" :area-invalid="errors.logo ? true : false">
                  <b-media-aside>
                    <a :href="'/view/file?path=' + userData.logo" target="_blank">
                      <b-img ref="previewLogoEl" rounded :src="'/view/file?path=' + userData.logo" height="80" />
                    </a>
                    <!--/ avatar -->
                  </b-media-aside>

                  <b-media-body class="mt-75 ml-75">
                    <!-- upload button -->
                    <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" size="sm" class="mb-75 mr-75"
                      @click="$refs.refLogoEl.$el.click()">
                      Logo
                    </b-button>
                    <b-form-file ref="refLogoEl" v-model="logo" accept=".jpg, .png, .gif" :hidden="true" plain
                      @input="validateSize(logo, 'logo', inputLogoRenderer)" />
                    <!--/ upload button -->

                    <!-- reset -->
                    <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" variant="outline-secondary" size="sm"
                      class="mb-75 mr-75" v-if="logo" @click="
                        $refs.previewLogoEl.src = '/view/file?path=' + userData.logo;
                      logo = null;
                      ">
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
                <b-media no-body :class="errors.banner ? 'is-invalid' : ''" :area-invalid="errors.banner ? true : false">
                  <b-media-aside>
                    <a :href="'/view/file?path=' + userData.banner_img" target="_blank">
                      <b-img ref="previewBannerEl" rounded :src="'/view/file?path=' + userData.banner_img" height="80" />
                    </a>
                    <!--/ avatar -->
                  </b-media-aside>

                  <b-media-body class="mt-75 ml-75">
                    <!-- upload button -->
                    <!--allowed for alliancepartner-->
                    <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" size="sm" class="mb-75 mr-75"
                      @click="$refs.refBannerEl.$el.click()">
                      Banner
                    </b-button>
                    <b-form-file ref="refBannerEl" v-model="banner" accept=".jpg, .png, .gif" :hidden="true" plain
                      @input="validateSize(banner, 'banner', inputBannerRenderer)" />
                    <!--/ upload button -->

                    <!-- reset -->
                    <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" variant="outline-secondary" size="sm"
                      class="mb-75 mr-75" v-if="banner" @click="
                        $refs.previewBannerEl.src =
                        '/view/file?path=' + userData.banner_img;
                      banner = null;
                      ">
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
            <b-button style="float: right" @click=" addRow('service_areas')" size="sm" variant="primary mr-1">
              <feather-icon icon="PlusIcon"></feather-icon>
              <span style="color: #fff" class="text-nowrap">Add Service Area</span>
            </b-button>
            <h4>Service Area</h4>
            <table class="table table-striped table-bordered">
              <tr>
                <th style="width: 45%">Address</th>
                <th>Pincode</th>
                <th style="width: 10%"></th>
              </tr>
              <tbody>
                <tr v-for="( area, aindex ) in  userData.service_areas" :key="aindex">
                  <td>
                    <b-form-input id="name-input" type="text" v-model="area.address"
                      :class="errors.title ? 'is-invalid' : ''" />
                  </td>
                  <td>
                    <b-form-input id="name-input" type="text" v-model="area.pincode"
                      :class="errors.title ? 'is-invalid' : ''" />
                  </td>
                  <td>
                    <b-button style="float: right" @click="removeRow(aindex, 'service_areas')" size="sm"
                      variant="link mr-1">
                      <feather-icon class="text-danger" icon="DeleteIcon"></feather-icon>
                    </b-button>
                  </td>
                </tr>
              </tbody>
            </table>
          </b-col>
        </b-row>
        <b-row style="margin-top: 5%; margin-left: 40%">
          <!-- Action Buttons -->
          <router-link :to="{ name: 'dh' }">
            <b-button variant="outline-secondary" type="reset" class="mb-1 mb-sm-0 mr-0 mr-sm-1"
              :block="$store.getters['app/currentBreakPoint'] === 'xs'">
              Back
            </b-button>
          </router-link>

          <b-button variant="primary" class="mb-1 mb-sm-0 mr-0 mr-sm-1"
            :block="$store.getters['app/currentBreakPoint'] === 'xs'" type="submit" v-permission="['create-users']">
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
} from "bootstrap-vue";
import { required, email } from "@validations";
import formValidation from "@core/comp-functions/forms/form-validation";
import vSelect from "vue-select";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { ref } from "@vue/composition-api";
import flatPickr from "vue-flatpickr-component";
import Ripple from "vue-ripple-directive";

import { validateSize } from "@core/utils/utils";

import VoerroTagsInput from "@voerro/vue-tagsinput";

require("@voerro/vue-tagsinput/dist/style.css");

import { useInputImageRenderer } from "@core/comp-functions/forms/form-utils";

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
    flatPickr,
    BFormRadioGroup,
    BFormTextarea,
    "tags-input": VoerroTagsInput,

    ValidationProvider,
    ValidationObserver,
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
      required,
      email,
      errors: [],
      signature: null,
      idproof: null,
      cheque: null,
      genders: [
        { text: "Male", value: "male" },
        { text: "Female", value: "female" },
      ],
      maxSize: 2097152,
      cityOptions: [
        { id: "Bengaluru", label: "Bengaluru" },
        { id: "Hyderabad", label: "Hyderabad" },
      ],
    };
  },
  setup(props) {
    const blankUserData = {
      name: "",
      email: "",
      role: null,
      phone: "",
      first_name: "",
      last_name: "",
      idproof: null,
      cancelled_cheque: null,
      // dob: null,
      // gender: null,
      address: null,
      city: "",
      pincode: null,
      state: null,
      logo: null,
      website_name: "",
      area_of_interest: "",
      banner_img: null,
      company_name: "",
      company_contact: "",
      service_areas: [],
    };

    const userData = ref(JSON.parse(JSON.stringify(blankUserData)));
    const resetuserData = () => {
      userData.value = JSON.parse(JSON.stringify(blankUserData));
    };

    const refLogoEl = ref(null);
    const previewLogoEl = ref(null);
    const inputLogoRenderer = useInputImageRenderer(refLogoEl, previewLogoEl);

    const refBannerEl = ref(null);
    const previewBannerEl = ref(null);
    const inputBannerRenderer = useInputImageRenderer(refBannerEl, previewBannerEl);


    const refIDProofEl = ref(null);
    const previewIDProofEl = ref(null);
    const refChequeEl = ref(null);
    const previewChequeEl = ref(null);


    const inputIDProofRenderer = useInputImageRenderer(refIDProofEl, previewIDProofEl);
    const inputChequeRenderer = useInputImageRenderer(refChequeEl, previewChequeEl);

    const roles = [];

    const { refFormObserver, getValidationState, resetForm } = formValidation(
      resetuserData
    );

    return {
      roles,
      userData,
      refFormObserver,
      logo: null,
      banner: null,
      getValidationState,
      resetForm,
      refIDProofEl,
      previewIDProofEl,
      inputIDProofRenderer,
      refChequeEl,
      previewChequeEl,
      inputChequeRenderer,
      refLogoEl,
      previewLogoEl,
      inputLogoRenderer,
      refBannerEl,
      previewBannerEl,
      inputBannerRenderer,
    };
  },
  created() {
    this.getRoles();
    if (this.id) {
      this.fetchUserData();
    }
  },

  methods: {
    validateSize,
    async getRoles() {
      try {
        const { data } = await axios.get("/roles/list");
        this.roles = data.roles;
      } catch (err) {
        console.log(err);
      }
    },
    setFile(e, prop) {
      let supportedTypes = ["image/jpg", "image/png", "image/jpeg", "image/gif"];
      let image = e.target.files[0];
      let type = image.type;
      if (!supportedTypes.includes(type)) {
        this.$toast({
          component: ToastificationContent,
          props: {
            title: "Please select valid image file",
            icon: "EditIcon",
            variant: "danger",
          },
        });
        return;
      } else {
        this.userData[prop] = e.target.files[0];
      }
    },
    async onSubmit() {
      try {
        this.errors = [];
        if (this.userData.phone) {
          if (this.userData.phone.length < 10 || this.userData.phone.length > 11) {
            this.errors = {
              phone: ["please provide a valid number"],
            };
            return false;
          }
        }
        let data;
        let userData = new FormData();
        userData.append("role", this.userData.role);
        userData.append("name", this.userData.name);
        userData.append("email", this.userData.email);
        userData.append("phone", this.userData.phone);
        userData.append("first_name", this.userData.first_name);
        userData.append("last_name", this.userData.last_name);
        userData.append("idproof", this.idproof);
        userData.append("cancelled_cheque", this.cheque);
        userData.append("bank_name", this.userData.bank_name);
        userData.append("account_no", this.userData.account_no);
        userData.append("ifsc_code", this.userData.ifsc_code);
        userData.append("dob", this.userData.dob);
        userData.append("gender", this.userData.gender);
        userData.append("address", this.userData.address);
        userData.append("city", this.userData.city);
        userData.append("pincode", this.userData.pincode);
        userData.append("state", this.userData.state);
        userData.append("banner_img", this.banner);
        userData.append("company_contact", this.userData.company_contact);
        userData.append("logo", this.logo);
        userData.append("company_name", this.userData.company_name);
        userData.append("website_name", this.userData.website_name);
        userData.append("area_of_interest", this.userData.area_of_interest);
        userData.append("service_areas", JSON.stringify(this.userData.service_areas));
        userData.append("percentage", this.userData.percentage);
        userData.append("available_cities", this.userData.available_cities);

        if (this.id) {
          data = await axios.post(`/dh/${this.id}`, userData, {
            headers: {
              "content-type": "multipart/form-data",
            },
          });
        } else {
          data = await axios.post("/dh", userData, {
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
        this.$router.replace("/dh/list");
      } catch (error) {
        if (error.response) {
          this.errors = error.response.data.errors;
        }
      }
    },

    async fetchUserData() {
      try {
        const { data } = await axios.get(`/dh/${this.id}`);
        this.userData = data.dh;
        if (this.userData.service_areas) {
          let available_cities = JSON.parse(data.dh.available_cities)
          
          this.userData.available_cities = [];
          available_cities.forEach((value, index) => {
            this.userData.available_cities.push(value.city)
          });

          console.log(this.userData.available_cities)
          
          this.userData.service_areas = JSON.parse(data.dh.service_areas)
        } else {
          this.userData.service_areas = []
        }
      } catch (err) {
        console.log(err);
      }
    },
    remove(index) {
      this.certificate.splice(index, 1);
    },
    async removeFile(index) {
      try {
        const { data } = await axios.delete(`/account/certificate/${index}`, {
          params: {
            id: this.id,
          },
        });
        if (data.success) {
          this.userData.certificates.splice(index, 1);
        }
      } catch (error) {
        if (error.response) {
          this.errors = error.response.data.errors;
        }
      }
    },
    addRow(field, index = null) {
      this.userData["service_areas"].push({ address: '', pincode: '' });
    },
    removeRow(index, field) {
      console.log(this.userData["service_areas"]);
      this.userData["service_areas"].splice(index, 1);
      //this.userData["service_areas"].push("");
      console.log(this.userData["service_areas"]);
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
