<template>
  <b-card>
    <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
      <!-- User Info: Input Fields -->
      <b-form @submit.prevent="handleSubmit(onSubmit)">
        <b-row class="d-flex justify-content-center">
          <b-col cols="20" md="10">
            <h4>General Details</h4>
            <b-row class="mt-2 mb-2">
              <!-- Field: First Name -->
              <b-col cols="12" md="4">
                <!-- Description -->
                <validation-provider
                  #default="validationContext"
                  name="First Name"
                  rules="required"
                >
                  <b-form-group label="First Name" label-for="first_name">
                    <b-form-input
                      id="full-name"
                      v-model="userData.first_name"
                      :state="getValidationState(validationContext)"
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
              <b-col cols="12" md="4">
                <!-- Description -->
                <validation-provider
                  #default="validationContext"
                  name="Last Name"
                  rules="required"
                >
                  <b-form-group label="Last Name" label-for="last_name">
                    <b-form-input
                      id="last-name"
                      v-model="userData.last_name"
                      :state="getValidationState(validationContext)"
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
              <!-- Field: Email -->
              <b-col cols="12" md="4">
                <!-- Description -->
                <validation-provider
                  #default="validationContext"
                  name="Email"
                  rules="required|email"
                >
                  <b-form-group label="Email" label-for="email">
                    <b-form-input
                      id="email"
                      v-model="userData.email"
                      type="text"
                      :state="getValidationState(validationContext)"
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
              <b-col cols="12" md="4">
                <!-- Description -->
                <validation-provider
                  #default="validationContext"
                  name="Phone Number"
                  rules="required"
                >
                  <b-form-group label="Phone Number" label-for="contact number">
                    <b-form-input
                      id="contact_number"
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
              <!-- Field: Alliance Partner -->
              <b-col cols="12" md="4" v-if="role !== 2">
                <validation-provider
                  #default="validationContext"
                  name="Referred Partner"
                  rules="required"
                >
                  <b-form-group
                    label="Referred Partner"
                    label-for="referred-partner"
                    :state="getValidationState(validationContext)"
                  >
                    <v-select
                      v-model="userData.alliance_partner"
                      :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                      :options="alliancePartners"
                      :reduce="(val) => val.id"
                      :clearable="false"
                      input-id="referred-partner"
                    />
                    <b-form-invalid-feedback v-if="errors.alliance_partner">
                      {{ errors.alliance_partner[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback
                      :state="getValidationState(validationContext)"
                      v-else
                    >
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>
              <!-- Field: Consultant -->
              <b-col cols="12" md="4" v-if="role !== 2">
                <b-form-group label="Referred Consultant" label-for="referred-consultant">
                  <v-select
                    v-model="userData.consultant_id"
                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="consultants"
                    :reduce="(val) => val.id"
                    :clearable="false"
                    input-id="referred-consultant"
                  />
                  <b-form-invalid-feedback v-if="errors.consultant">
                    {{ errors.consultant[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </b-col>
            </b-row>
            <h4>Other Details</h4>
            <div class="row">
              <!-- DOB -->
              <!--allowed for Alliance partner and Clients-->
              <b-col cols="12" md="4">
                <b-form-group label="Date of Birth" label-for="dob">
                  <flat-pickr
                    v-model="userData.dob"
                    class="form-control"
                    :config="{ enableTime: false, dateFormat: 'd-m-Y' }"
                  />
                </b-form-group>
              </b-col>
              <!-- Gender -->
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
              </b-col>
              <b-col cols="12" md="4">
                <b-form-group label="Marital Status" label-for="marital_status">
                  <b-form-radio-group
                    v-model="userData.marital_status"
                    :options="maritalstatus"
                    class="demo-inline-spacing"
                    name="marital_status"
                    id="marital_status"
                  />
                </b-form-group>
              </b-col>
              <b-col cols="12" md="4" v-if="userData.marital_status == 'Married'">
                <b-form-group label="Wedding Date" label-for="wedding_date">
                  <flat-pickr
                    v-model="userData.wedding_date"
                    class="form-control"
                    :config="{ enableTime: false, dateFormat: 'd-m-Y' }"
                  />
                </b-form-group>
              </b-col>
              <!-- Occupation -->
              <!--allowed for Client-->
              <b-col cols="12" md="4">
                <b-form-group label-for="occupation" label="Occupation">
                  <b-form-input
                    id="occupation"
                    v-model="userData.occupation"
                    placeholder="Occupation"
                  />
                </b-form-group>
              </b-col>
              <!-- Guardian Mobile -->
              <!--allowed for Client-->
              <b-col cols="12" md="4">
                <b-form-group label-for="guard-mob" label="Guardian Mobile">
                  <b-form-input
                    id="guard-mob"
                    v-model="userData.guard_mob"
                    placeholder="Guardian Mobile"
                    :class="errors.guard_mob ? 'is-invalid' : ''"
                    :area-invalid="errors.guard_mob ? true : false"
                  />
                  <b-form-invalid-feedback v-if="errors.guard_mob">
                    {{ errors.guard_mob[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </b-col>

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
              <b-col cols="12" md="4">
                <b-form-group label-for="city" label="City">
                  <v-select
                    v-model="userData.city"
                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="cityOptions"
                    :reduce="(val) => val.id"
                    :clearable="false"
                    input-id="referred-city"
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
            </div>
          </b-col>
        </b-row>
        <b-row>
          <b-col cols="12" md="2"> </b-col>
        </b-row>
        <b-row>
          <b-col cols="12" md="2"> </b-col>
        </b-row>
        <b-row style="margin-top: 5%; margin-left: 40%">
          <!-- Action Buttons -->
          <router-link :to="{ name: 'clients' }">
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
            v-permission="['create-clients']"
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
  BFormTextarea,
  BFormRadioGroup,
} from "bootstrap-vue";
import { avatarText } from "@core/utils/filter";
import { required, email } from "@validations";
import formValidation from "@core/comp-functions/forms/form-validation";
import vSelect from "vue-select";
import { useInputImageRenderer } from "@core/comp-functions/forms/form-utils";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { ref } from "@vue/composition-api";

import flatPickr from "vue-flatpickr-component";

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
    vSelect,
    BFormInvalidFeedback,
    BFormTextarea,
    flatPickr,
    BFormRadioGroup,

    ValidationProvider,
    ValidationObserver,
  },
  data() {
    return {
      required,
      email,
      consultants: [],
      alliancePartners: [],
      errors: [],
      genders: [
        { text: "Male", value: "male" },
        { text: "Female", value: "female" },
      ],
      maritalstatus: [
        { text: "Married", value: "Married" },
        { text: "Single", value: "Single" },
      ],
      cityOptions: [
        { id: "Bengaluru", label: "Bengaluru" },
        { id: "Hyderabad", label: "Hyderabad" },
      ]
    };
  },
  computed: {
    role: function () {
      return this.$store.state.auth.user.role_id;
    },
  },
  props: {
    id: {
      type: [Number, String],
      default: null,
    },
  },
  setup(props) {
    // ? Demo Purpose => Update image on click of update
    const refInputEl = ref(null);
    const previewEl = ref(null);

    const { inputImageRenderer } = useInputImageRenderer(refInputEl, (base64) => {
      // eslint-disable-next-line no-param-reassign
      props.userData.avatar = base64;
    });

    const userData = ref({
      alliance_partner: "",
      role: 2,
      name: "",
      email: "",
      phone: null,
      first_name: "",
      last_name: "",
      consultant_id: null,
      marital_status: null,
      wedding_date:null,
    });

    const resetuserData = () => {
      userData.value = {
        alliance_partner: "",
        role: 2,
        name: "",
        email: "",
        phone: null,
        first_name: "",
        last_name: "",
        consultant_id: null,
        marital_status: null,
        wedding_date:null
      };
    };

    const { refFormObserver, getValidationState, resetForm } = formValidation(
      resetuserData
    );

    return {
      avatarText,
      userData,
      refFormObserver,
      getValidationState,
      resetForm,

      //  ? Demo - Update Image on click of update button
      refInputEl,
      previewEl,
      inputImageRenderer,
    };
  },

  created() {
    if (this.id) {
      this.fetchUserData();
    }
    if (this.role === 2) {
      this.userData.alliance_partner = this.$store.state.auth.user.id;
    } else {
      this.getAlliancePartners();
      this.getConsultants();
    }
  },

  methods: {
    async getAlliancePartners() {
      try {
        const { data } = await axios.get("/alliance-partner/list");
        this.alliancePartners = data.partners;
        console.log(data);
      } catch (err) {
        console.log(err);
      }
    },
    async getConsultants() {
      try {
        const { data } = await axios.get("/consultants/list", {
          params: {
            upgraded: true,
          },
        });
        this.consultants = data.users;
        console.log(data);
      } catch (err) {
        console.log(err);
      }
    },
    async onSubmit() {
      //console.log(this.userData.alliance_partner);
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
        if (this.id) {
          data = await axios.put(`/clients/${this.userData.id}`, this.userData);
        } else {
          data = await axios.post("/clients", this.userData);
        }
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.data.message,
            icon: "EditIcon",
            variant: data.data.success ? "success" : "info",
          },
        });

        if (data.data.success) {
          this.$router.replace("/clients/list");
        }
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
  },
};
</script>

<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";
@import "@core/scss/vue/libs/vue-select.scss";
@import "@core/scss/vue/libs/vue-flatpicker.scss";
.custom-radio {
  margin-top: 0 !important;
}
</style>
