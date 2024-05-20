<template>
  <b-card>
    <!--/ media -->
    <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
      <!-- form -->
      <b-form class="mt-2" @submit.prevent="handleSubmit(onSubmit)">
        <b-row>
          <b-col sm="6" v-if="[17].includes(role)">
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

          <b-col sm="6">
            <validation-provider #default="validationContext"
              :name="role === 2 || role === 17 ? 'Contact Person First Name' : 'First Name'" rules="required">
              <b-form-group :label="role === 2 || role === 17 ? 'Contact Person First Name' : 'First Name'"
                label-for="account-firstname">
                <b-form-input v-model="userData.first_name" :state="getValidationState(validationContext)"
                  :placeholder="role === 2 || role === 17 ? 'Contact Person First Name' : 'First Name'"
                  name="firstname" />
                <b-form-invalid-feedback>
                  {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>
          </b-col>
          <b-col sm="6">
            <validation-provider #default="validationContext"
              :name="role === 2 || role === 17 ? 'Contact Person Last Name' : 'Last Name'" rules="required">
              <b-form-group :label="role === 2 || role === 17 ? 'Contact Person Last Name' : 'Last Name'"
                label-for="account-last_name">
                <b-form-input v-model="userData.last_name" :state="getValidationState(validationContext)" name="lastname"
                  :placeholder="role === 2 || role === 17 ? 'Contact Person Last Name' : 'Last Name'" />
                <b-form-invalid-feedback>
                  {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>
          </b-col>

          <b-col sm="6">
            <b-form-group :label="role === 2 || role === 17 ? 'Contact Person Email' : 'Email'" label-for="email">
              <b-form-input v-model="userData.email" disabled name="email"
                :placeholder="role === 2 || role === 17 ? 'Contact Person Email' : 'Email'" />
            </b-form-group>
          </b-col>

          <!-- DOB -->
          <!--allowed for Alliance partner and Clients-->
          <b-col md="6" v-if="![1, 2, 17].includes(role)">
            <validation-provider #default="validationContext" name="Date of Birth" rules="required">
              <b-form-group label="Date of Birth" label-for="dob">
                <flat-pickr v-model="userData.dob" class="form-control"
                  :config="{ enableTime: false, dateFormat: 'd-m-Y' }" @on-change="dobChanged" />
                <b-form-invalid-feedback :state="getValidationState(validationContext)">
                  {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>
          </b-col>
          <!-- Gender -->
          <!--allowed for Alliance partner and Clients-->
          <b-col md="6" v-if="![1, 2, 17].includes(role)">
            <validation-provider #default="validationContext" name="Gender" rules="required">
              <b-form-group label="Gender" label-for="gender">
                <b-form-radio-group v-model="userData.gender" :options="genders" class="demo-inline-spacing" name="gender"
                  id="gender" />
                <b-form-invalid-feedback :state="getValidationState(validationContext)">
                  {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>
          </b-col>

          <!-- Occupation -->
          <!--allowed for Client-->
          <b-col md="6" v-if="role === 3">
            <validation-provider #default="validationContext" name="Occupation" rules="required">
              <b-form-group label-for="occupation" label="Occupation">
                <b-form-input id="occupation" v-model="userData.occupation" placeholder="Occupation" />
                <b-form-invalid-feedback :state="getValidationState(validationContext)">
                  {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>
          </b-col>

          <!-- Phone -->
          <b-col sm="6">
            <validation-provider #default="validationContext" name="Phone Number" rules="required">
              <b-form-group label="Phone Number" label-for="account-phone">
                <b-form-input v-model="userData.phone" :state="getValidationState(validationContext)" name="account-phone"
                  type="number" placeholder="Phone Number" :class="errors.phone ? 'is-invalid' : ''"
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

          <!-- Guardian Mobile -->
          <!--allowed for Client-->
          <b-col md="6" v-if="role === 3">
            <validation-provider #default="validationContext" name="Guardian Mobile" :rules="isRequired">
              <b-form-group label-for="guard-mob" label="Guardian Mobile">
                <b-form-input id="guard-mob" v-model="userData.guard_mob" :state="getValidationState(validationContext)"
                  placeholder="Guardian Mobile" :class="errors.guard_mob ? 'is-invalid' : ''"
                  :area-invalid="errors.guard_mob ? true : false" />
                <b-form-invalid-feedback v-if="errors.guard_mob">
                  {{ errors.guard_mob[0] }}
                </b-form-invalid-feedback>
                <b-form-invalid-feedback>
                  {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>
          </b-col>

          <!-- Clinic Name -->
          <!--allowed for consultants and alliancepartner-->
          <b-col md="6" v-if="role == 4">
            <b-form-group label-for="clinic-name" label="Clinic Name">
              <b-form-input id="clinic-name" v-model="userData.clinic_name" placeholder="Clinic Name" />
            </b-form-group>
          </b-col>

          <!-- company -->
          <!--allowed for consultants-->
          <b-col md="6" v-if="role === 2">
            <validation-provider #default="validationContext" name="Company/Institute Name" rules="required">
              <b-form-group label-for="company" label="Company/Institute Name">
                <b-form-input id="company" :state="getValidationState(validationContext)" v-model="userData.company_name"
                  placeholder="Company/Institute Name" />
                <b-form-invalid-feedback>
                  {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>
          </b-col>

          <!-- website -->
          <!--allowed for consultants and alliancepartner-->
          <b-col md="6" v-if="![1, 2, 3].includes(role)">
            <b-form-group label-for="website" label="Website">
              <b-form-input id="website" v-model="userData.website_name" placeholder="Website address" />
            </b-form-group>
          </b-col>
          <!--/ website -->

          <!-- address -->
          <!--allowed for all-->
          <b-col md="6" v-if="role !== 1">
            <validation-provider #default="validationContext" name="Address" rules="required">
              <b-form-group label-for="address" label="Address">
                <b-form-textarea id="address" v-model="userData.address" rows="4" placeholder="address"
                  :state="getValidationState(validationContext)" />
                <b-form-invalid-feedback>
                  {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>
          </b-col>

          <!-- City -->
          <!--allowed for all-->
          <b-col md="6" v-if="role !== 1">
            <validation-provider #default="validationContext" name="City" rules="required">
              <b-form-group label-for="city" label="City">
                <b-form-input id="city" v-model="userData.city" :state="getValidationState(validationContext)"
                  placeholder="city" />
                <b-form-invalid-feedback>
                  {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>

            <validation-provider #default="validationContext" name="ReferralCode" rules="required">
              <b-form-group label-for="referralcode" label="ReferralCode">
                <b-form-input id="referralcode" v-model="userData.referralcode" :state="getValidationState(validationContext)"
                  placeholder="Referral Code" readonly="true" />
                <b-form-invalid-feedback>
                  {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>
          </b-col>

          <!-- pincode -->
          <!--allowed for all-->
          <b-col md="6" v-if="role !== 1">
            <validation-provider #default="validationContext" name="Pincode" rules="required">
              <b-form-group label-for="pin" label="Pincode">
                <b-form-input id="pin" v-model="userData.pincode" :state="getValidationState(validationContext)"
                  placeholder="pin" />
                <b-form-invalid-feedback>
                  {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>
          </b-col>

          <!-- state -->
          <!--allowed for all-->
          <b-col md="6" v-if="role !== 1">
            <validation-provider #default="validationContext" name="State" rules="required">
              <b-form-group label-for="state" label="State">
                <b-form-input id="state" v-model="userData.state" :state="getValidationState(validationContext)"
                  placeholder="State" />
                <b-form-invalid-feedback>
                  {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>
          </b-col>
          <b-col sm="8 mt-1" md="6">
            <!-- avatar -->
            <b-media no-body :class="errors.avatar ? 'is-invalid' : ''" :area-invalid="errors.avatar ? true : false">
              <b-media-aside>
                <a :href="'/view/file?path=' + userData.avatar" target="_blank">
                  <b-img ref="previewEl" rounded :src="'/view/file?path=' + userData.avatar" height="80" />
                </a>
                <!--/ avatar -->
              </b-media-aside>

              <b-media-body class="mt-75 ml-75">
                <!-- upload button -->
                <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" size="sm" class="mb-75 mr-75"
                  @click="$refs.refInputEl.$el.click()">
                  Upload Profile Picture
                </b-button>
                <b-form-file ref="refInputEl" v-model="avatar" accept=".jpg, .png, .gif" :hidden="true" plain
                  @input="validateSize(avatar, 'avatar', imageRenderer)" />
                <!--/ upload button -->

                <!-- reset -->
                <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" variant="outline-secondary" size="sm"
                  class="mb-75 mr-75" v-if="avatar" @click="
                    $refs.previewEl.src = '/view/file?path=' + userData.avatar;
                  avatar = null;
                  ">
                  Reset
                </b-button>
                <!--/ reset -->
                <b-card-text>Allowed JPG, GIF or PNG. Max size of 2MB</b-card-text>
              </b-media-body>
            </b-media>
            <b-form-invalid-feedback v-if="errors.avatar">
              {{ errors.avatar[0] }}
            </b-form-invalid-feedback>
          </b-col>

          <b-col sm="8 mt-1" md="6">
            <!-- avatar -->
            <b-media no-body :class="errors.idproof ? 'is-invalid' : ''" :area-invalid="errors.idproof ? true : false">
              <b-media-aside>
                <a :href="'/view/file?path=' + userData.idproof" target="_blank">
                  <b-img ref="previewIdproofEl" rounded :src="'/view/file?path=' + userData.idproof" height="80" />
                </a>
                <!--/ avatar -->
              </b-media-aside>

              <b-media-body class="mt-75 ml-75">
                <!-- upload button -->
                <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" size="sm" class="mb-75 mr-75"
                  @click="$refs.refInputIdproofEl.$el.click()">
                  ID Proof
                </b-button>
                <b-form-file ref="refInputIdproofEl" v-model="idproof" accept=".jpg, .png, .gif" :hidden="true" plain
                  @input="validateSize(idproof, 'idproof', imageIdproofRenderer)" />
                <!--/ upload button -->

                <!-- reset -->
                <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" variant="outline-secondary" size="sm"
                  class="mb-75 mr-75" v-if="userData.idproof" @click="
                    $refs.previewIdproofEl.src = '/view/file?path=' + userData.idproof;
                  idproof = null;
                  ">
                  Reset
                </b-button>
                <!--/ reset -->
                <b-card-text>Allowed JPG, GIF or PNG. Max size of 2MB</b-card-text>
              </b-media-body>
            </b-media>
            <b-form-invalid-feedback v-if="errors.idproof">
              {{ errors.idproof[0] }}
            </b-form-invalid-feedback>
          </b-col>
          <b-col md="12" v-if="role == 17" class="mt-4">

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
          <b-col md="12" v-if="role == 3" class="mt-4">
            <validation-provider #default="validationContext" name="Termsandcondition" rules="required">
              <b-form-group label-for="Termsandcondition" label="">
                <label>Click here to agree our <a target="_blank" href="/view/terms-conditions">Terms & Conditions</a> &
                  <a target="_blank" href="/view/disclaimer">Disclaimer policy</a></label>
                <b-form-checkbox id="terms" :state="getValidationState(validationContext)"
                  v-model="userData.terms_condition" name="terms_condition" value="1" unchecked-value="0">
                </b-form-checkbox>
                <b-form-invalid-feedback>
                  {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>
          </b-col>


          <!-- alert -->
          <!-- <b-col
            cols="12"
            class="mt-75"
          >
            <b-alert
              show
              variant="warning"
              class="mb-50"
            >
              <h4 class="alert-heading">
                Your email is not confirmed. Please check your inbox.
              </h4>
              <div class="alert-body">
                <b-link class="alert-link">
                  Resend confirmation
                </b-link>
              </div>
            </b-alert>
          </b-col> -->
          <!--/ alert -->

          <b-col cols="12">
            <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" class="mt-2 mr-1" type="submit">
              Update
            </b-button>
            <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" variant="outline-secondary" type="reset" class="mt-2"
              @click.prevent="resetData">
              Reset
            </b-button>
          </b-col>
        </b-row>
      </b-form>
    </validation-observer>
  </b-card>
</template>

<script>
import {
  BFormFile,
  BButton,
  BForm,
  BFormGroup,
  BFormInput,
  BRow,
  BCol,
  BAlert,
  BCard,
  BCardText,
  BMedia,
  BMediaAside,
  BMediaBody,
  BLink,
  BImg,
  BFormInvalidFeedback,
  BFormRadioGroup,
  BFormTextarea,
  BFormCheckbox,
} from "bootstrap-vue";
import Ripple from "vue-ripple-directive";

import { required, email } from "@validations";
import formValidation from "@core/comp-functions/forms/form-validation";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { useInputImageRenderer } from "@core/comp-functions/forms/form-utils";
import { ref } from "@vue/composition-api";

import { validateSize } from "@core/utils/utils";

import flatPickr from "vue-flatpickr-component";

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

export default {
  components: {
    BButton,
    BForm,
    BImg,
    BFormFile,
    BFormGroup,
    BFormInput,
    BRow,
    BCol,
    BAlert,
    BCard,
    BCardText,
    BMedia,
    BMediaAside,
    BMediaBody,
    BLink,
    BFormInvalidFeedback,
    BFormRadioGroup,
    flatPickr,
    BFormTextarea,
    BFormCheckbox,

    ValidationProvider,
    ValidationObserver,
  },
  directives: {
    Ripple,
  },
  props: {
    generalData: {
      type: Object,
      default: () => { },
    },
  },
  computed: {
    id: function () {
      return this.$store.state.auth.user.id;
    },
    role: function () {
      return this.$store.state.auth.user.role_id;
    },
  },
  data() {
    return {
      avatar: null,
      idproof: null,
      required,
      email,
      errors: [],
      genders: [
        { text: "Male", value: "male" },
        { text: "Female", value: "female" },
      ],
      isRequired: null,

      maxSize: 800000,
    };
  },
  methods: {
    validateSize,
    async onSubmit() {
      try {
        if (this.userData.phone) {
          if (this.userData.phone.length < 10 || this.userData.phone.length > 11) {
            this.errors = {
              phone: ["please provide a valid number"],
            };
            return false;
          }
        }
        if (this.userData.guard_mob) {
          if (
            this.userData.guard_mob.length < 10 ||
            this.userData.guard_mob.length > 11
          ) {
            this.errors = {
              guard_mob: ["please provide a valid number"],
            };
            return false;
          }
        }
        if (this.role != 1 && !this.idproof && !this.userData.idproof) {
          // this.errors = {
          //   idproof: ["please provide an id proof"],
          // };
          // return false;
        }
        let userData = new FormData();
        userData.append("phone", this.userData.phone);
        userData.append("first_name", this.userData.first_name);
        userData.append("last_name", this.userData.last_name);
        userData.append("avatar", this.avatar);
        userData.append("idproof", this.idproof);
        userData.append("clinic_name", this.userData.clinic_name);
        userData.append("website_name", this.userData.website_name);
        userData.append("dob", this.userData.dob);
        userData.append("gender", this.userData.gender);
        userData.append("occupation", this.userData.occupation);
        userData.append("guard_mob", this.userData.guard_mob);
        userData.append("address", this.userData.address);
        userData.append("city", this.userData.city);
        userData.append("pincode", this.userData.pincode);
        userData.append("state", this.userData.state);
        userData.append("company_name", this.userData.company_name);
        userData.append("terms_condition", this.userData.terms_condition);
        userData.append("service_areas", JSON.stringify(this.userData.service_areas));

        const { data } = await axios.post("/account/general", userData, {
          headers: {
            "content-type": "multipart/form-data",
          },
        });

        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "BellIcon",
            variant: data.success ? "success" : "info",
          },
        });
        this.resetForm();
        this.$emit("change");
      } catch (error) {
        if (error.response) {
          this.errors = error.response.data.errors;
        }
      }
    },
    dobChanged() {
      //console.log(new Date(this.userData.dob).getTime())
      var diff = (new Date().getTime() - new Date(this.userData.dob).getTime()) / 1000;
      diff /= 60 * 60 * 24;
      const age = Math.abs(Math.round(diff / 365.25));
      if (age < 18 && this.role === 3) {
        this.isRequired = "required";
      } else {
        this.isRequired = null;
      }
    },
    resetData() {
      this.resetForm();
      this.avatar = null;
      this.idproof = null;
      this.$refs.previewIdproofEl.src = "/view/file?path=" + this.userData.idproof;
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
  setup(props) {
    const refInputEl = ref(null);
    const previewEl = ref(null);

    const imageRenderer = useInputImageRenderer(refInputEl, previewEl);

    const refInputIdproofEl = ref(null);
    const previewIdproofEl = ref(null);

    const imageIdproofRenderer = useInputImageRenderer(
      refInputIdproofEl,
      previewIdproofEl
    );

    const userData = ref(JSON.parse(JSON.stringify(props.generalData)));
    const resetuserData = () => {
      userData.value = JSON.parse(JSON.stringify(props.generalData));
    };
    const { refFormObserver, getValidationState, resetForm } = formValidation(
      resetuserData
    );

    return {
      refInputEl,
      previewEl,
      imageRenderer,
      refInputIdproofEl,
      previewIdproofEl,
      imageIdproofRenderer,
      refFormObserver,
      getValidationState,
      resetForm,
      userData,
    };
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
<style lang="scss">
.custom-checkbox .custom-control-label::before,
.custom-checkbox .custom-control-label::after,
.custom-radio .custom-control-label::before,
.custom-radio .custom-control-label::after {
  width: 25px;
  height: 25px;
}

.custom-control.custom-checkbox {
  float: left;
  margin-right: 10px;
}
</style>