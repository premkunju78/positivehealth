<template>
  <b-card>
    <validation-observer
        #default="{ handleSubmit }"
        ref="refFormObserver"
      >
      <!-- form -->
      <b-form  @submit.prevent="handleSubmit(onSubmit)">
        <b-row>
          <!-- Specialization --> <!--allowed for consultants-->
          <b-col md="6" v-if="![1,2,3].includes(role)">
            <validation-provider
              #default="validationContext"
              name="Specialization"
              rules="required"
            >
              <b-form-group
                label-for="specialization"
                label="Specialization"
              >
                <v-select
                  id="specialization"
                  :state="getValidationState(validationContext)"
                  v-model="userData.specialization"
                  :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                  label="title"
                  :options="specializations"
                  :reduce="val => val.id"
                />
                <b-form-invalid-feedback>
                {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>
          </b-col>
          <!--/ birth date -->

          <!-- Specialized In --> <!--allowed for consultants-->
          <b-col md="6" v-if="![1,2,3].includes(role)">
            <validation-provider
              #default="validationContext"
              name="Specialized In"
              rules="required"
            >
              <b-form-group
                label-for="specialized_in"
                label="Specialized In"
              >
                <b-form-input
                  id="specialized_in"
                  :state="getValidationState(validationContext)"
                  v-model="userData.specialized_in"
                  placeholder="specialized in"
                />
                <b-form-invalid-feedback>
                {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
             </validation-provider>
          </b-col>
          <!--/ Specialized In -->

          <!-- cost --> <!--allowed for consultants-->
          <b-col md="6" v-if="![1,2,3].includes(role)">
            <b-form-group
              label-for="cost"
              label="Cost"
            >
              <b-form-input
                id="cost"
                v-model="userData.cost"
                placeholder="Cost"
              />
            </b-form-group>
          </b-col>


          <!-- company --> <!--allowed for consultants-->
          <b-col md="6" v-if="role === 2">
            <validation-provider
              #default="validationContext"
              name="Company/Institute Name"
              rules="required"
            >
              <b-form-group
                label-for="company"
                label="Company/Institute Name"
              >
                <b-form-input
                  id="company"
                  :state="getValidationState(validationContext)"
                  v-model="userData.company_name"
                  placeholder="Company/Institute Name"
                />
                <b-form-invalid-feedback>
                {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>
          </b-col>

        <!-- Contact Person --> <!--allowed for Alliance partners-->
          <b-col md="6"  v-if="role === 2">
            <b-form-group
              label-for="contact-person"
              label="Contact Person "
            >
              <b-form-input
                id="contact-person"
                v-model="userData.contact_person"
                placeholder="Contact Person "
              />
            </b-form-group>
          </b-col>
          <!--/ contact Person -->


          <!-- company contact --> <!--allowed for Alliance partners-->
          <b-col md="6"  v-if="role === 2">
            <validation-provider
              #default="validationContext"
              name="Company Contact Number"
              rules="required"
            >
              <b-form-group
                label-for="company_cotact"
                label="Company Contact Number"
              >
                <b-form-input
                  id="company_cotact"
                  :state="getValidationState(validationContext)"
                  v-model="userData.company_contact"
                  placeholder="Company Contact Number"
                />
                <b-form-invalid-feedback>
                {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>
          </b-col>

          <!-- Clinic Name --> <!--allowed for consultants and alliancepartner-->
          <b-col md="6" v-if="role > 3">
            <b-form-group
              label-for="clinic-name"
              label="Clinic Name"
            >
              <b-form-input
                id="clinic-name"
                v-model="userData.clinic_name"
                placeholder="Clinic Name"
              />
            </b-form-group>
          </b-col>

          <!-- website --> <!--allowed for consultants and alliancepartner-->
          <b-col md="6" v-if="![1,3].includes(role)">
            <b-form-group
              label-for="website"
              label="Website"
            >
              <b-form-input
                id="website"
                v-model="userData.website_name"
                placeholder="Website address"
              />
            </b-form-group>
          </b-col>
          <!--/ website -->

        
          <!-- website url --> <!--allowed for consultants-->
          <b-col md="6"  v-if="role === 2">
            <b-form-group
              label-for="url"
              label="Enter URL"
            >
              <b-input-group prepend="https://positivehealth.app/">
                  <b-form-input id="url" v-model="userData.url" placeholder="Url"/>
              </b-input-group>
            </b-form-group>
          </b-col>

          <!-- group --> <!--allowed for consultants-->
          <b-col md="6"  v-if="role === 2">
            <validation-provider
              #default="validationContext"
              name="Group"
              rules="required"
            >
              <b-form-group
                label-for="group"
                label="Group"
                :state="getValidationState(validationContext)"
              >
                <v-select
                  id="group"
                  v-model="userData.group"
                  :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                  :options="groups"
                />
                <b-form-invalid-feedback :state="getValidationState(validationContext)">
                {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>
          </b-col>

          <!-- partner type -->  <!--allowed for consultants-->
          <b-col md="6"  v-if="role === 2">
            <validation-provider
              #default="validationContext"
              name="Partner Type"
              rules="required"
            >
              <b-form-group
                label-for="partner_type"
                label="Partner Type"
                :state="getValidationState(validationContext)"
              >
                <v-select
                  id="partner_type"
                  v-model="userData.type"
                  :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                  :options="types"
                />
                <b-form-invalid-feedback  :state="getValidationState(validationContext)">
                {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>
          </b-col>

          <!-- message --> <!--allowed for consultants-->
          <b-col md="6"  v-if="role === 2">
            <validation-provider
              #default="validationContext"
              name="Welcome Message"
              rules="required"
            >
              <b-form-group
                label-for="message"
                label="Welcome Message"
              >
                <b-form-input
                  id="message"
                  :state="getValidationState(validationContext)"
                  v-model="userData.welcome_message"
                  placeholder="welome message"
                />
                <b-form-invalid-feedback>
                {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>
          </b-col>

          <!-- DOB --> <!--allowed for Alliance partner and Clients-->
          <b-col md="6" v-if="![1,2,3].includes(role)">
              <b-form-group
              label="Date of Birth"
              label-for="dob"
            >
              <flat-pickr
                v-model="userData.dob"
                class="form-control"
                :config="{enableTime: false, dateFormat: 'd-m-Y'}"
              />

            </b-form-group>
          </b-col> 
          <!-- Gender --> <!--allowed for Alliance partner and Clients-->
          <b-col md="6" v-if="![1,2,3].includes(role)"> 
            <div class="demo-inline-spacing">
              <b-form-group
              label="Gender"
              label-for="gender"
              >
                <b-form-radio
                  v-model="userData.gender"
                  plain
                  name="male"
                  value="male"
                >Male
                </b-form-radio>
                
                <b-form-radio
                  v-model="userData.gender"
                  plain
                  name="female"
                  value="female"
                >Female
                </b-form-radio>
              </b-form-group>
            </div>
          </b-col>

          <!-- Occupation --> <!--allowed for Client-->
          <b-col md="6"  v-if="role===3">
            <b-form-group
              label-for="occupation"
              label="Occupation"
            > 
              <b-form-input
                id="occupation"
                v-model="userData.occupation"
                placeholder="Occupation"
              />
            </b-form-group>
          </b-col>

          <!-- Guardian Mobile --> <!--allowed for Client-->
          <b-col md="6" v-if="role===3">
            <b-form-group
              label-for="guard-mob"
              label="Guardian Mobile"
            > 
              <b-form-input
                id="guard-mob"
                v-model="userData.guard_mob"
                placeholder="Guardian Mobile"
              />
            </b-form-group>
          </b-col>

          <!-- address --> <!--allowed for all-->
          <b-col md="6" >
            <b-form-group
              label-for="address"
              label="Address"
            >
              <b-form-textarea id="address" v-model="userData.address" rows="3" placeholder="address" />
            </b-form-group>
          </b-col>

          <!-- City --> <!--allowed for all-->
          <b-col md="6" >
            <b-form-group
              label-for="city"
              label="City"
            >
              <b-form-input
                id="city"
                v-model="userData.city"
                placeholder="city"
              />
            </b-form-group>
          </b-col>

          <!-- pincode --> <!--allowed for all-->
          <b-col md="6" >
            <b-form-group
              label-for="pin"
              label="Pincode"
            >
              <b-form-input
                id="pin"
                v-model="userData.pincode"
                placeholder="pin"
              />
            </b-form-group>
          </b-col>

        <!-- state --> <!--allowed for all-->
          <b-col md="6" >
            <b-form-group
              label-for="state"
              label="State"
            >
              <b-form-input
                id="state"
                v-model="userData.state"
                placeholder="State"
              />
            </b-form-group>
          </b-col>

          <!-- logo --> <!--allowed for consultants and alliancepartner-->
          <b-col md="6" class="mt-2 mb-2" v-if="![1,3].includes(role)">
            <!-- media -->
            <b-media no-body>
              <b-media-aside>
                <a :href="'/view/file?path='+userData.logo" target="_blank">
                  <b-img
                    ref="previewLogoEl"
                    rounded
                    :src="'/view/file?path='+userData.logo"
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
                  @input="inputLogoRenderer.inputImageRenderer"
                />
                <!--/ upload button -->

                <!-- reset -->
                <b-button
                  v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                  variant="outline-secondary"
                  size="sm"
                  class="mb-75 mr-75"
                  v-if="userData.logo"
                  @click="$refs.previewLogoEl.src = '/view/file?path='+userData.logo"
                >
                  Reset
                </b-button>
                <!--/ reset -->
                <small>Allowed JPG, GIF or PNG.</small>
              </b-media-body>
            </b-media>
          </b-col>

          <!-- signature --> <!--allowed for consultants and alliancepartner-->
          <b-col md="6"  class="mt-2 mb-2" v-if="![1,3].includes(role)">
            <!-- media -->
            <b-media no-body>
              <b-media-aside>
                <a :href="'/view/file?path='+userData.signature_img" target="_blank">
                  <b-img
                    ref="previewSignatureEl"
                    rounded
                    :src="'/view/file?path='+userData.signature_img"
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
                  @click="$refs.refSignatureEl.$el.click()"
                >
                  Signature
                </b-button>
                <b-form-file
                  ref="refSignatureEl"
                  v-model="signature"
                  accept=".jpg, .png, .gif"
                  :hidden="true"
                  plain
                  @input="inputSignatureRenderer.inputImageRenderer"
                />
                <!--/ upload button -->

                <!-- reset -->
                <b-button
                  v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                  variant="outline-secondary"
                  size="sm"
                  class="mb-75 mr-75"
                  v-if="userData.signature_img"
                  @click="$refs.previewSignatureEl.src = '/view/file?path='+userData.signature_img"
                >
                  Reset
                </b-button>
                <!--/ reset -->
                <small>Allowed JPG, GIF or PNG.</small>
              </b-media-body>
            </b-media>
          </b-col>

          <!-- banner --> <!--allowed for alliancepartner-->
          <b-col md="6"  class="mt-2 mb-2" v-if="role===2">
            <!-- media -->
            <b-media no-body>
              <b-media-aside>
                <a :href="'/view/file?path='+userData.banner_img" target="_blank">
                  <b-img
                    ref="previewBannerEl"
                    rounded
                    :src="'/view/file?path='+userData.banner_img"
                    height="80"
                  />
                </a>
                <!--/ avatar -->
              </b-media-aside>

              <b-media-body class="mt-75 ml-75">
                <!-- upload button --> <!--allowed for alliancepartner-->
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
                  @input="inputBannerRenderer.inputImageRenderer"
                />
                <!--/ upload button -->

                <!-- reset -->
                <b-button
                  v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                  variant="outline-secondary"
                  size="sm"
                  class="mb-75 mr-75"
                  v-if="userData.banner_img"
                  @click="$refs.previewBannerEl.src ='/view/file?path='+ userData.banner_img"
                >
                  Reset
                </b-button>
                <!--/ reset -->
                <small>Allowed JPG, GIF or PNG.</small>
              </b-media-body>
            </b-media>
          </b-col>

        

          <b-col cols="12">
            <b-button
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              variant="primary"
              class="mt-1 mr-1"
              type="submit"
            >
             Update
            </b-button>

            <b-button
              v-ripple.400="'rgba(186, 191, 199, 0.15)'"
              type="reset"
              class="mt-1"
              variant="outline-secondary"
              @click.prevent="resetForm"
            >
              Reset
            </b-button>
          </b-col>
        </b-row>
      </b-form>
    </validation-observer>
  </b-card>
</template>

<script>
import { BInputGroup,BButton, BForm, BFormGroup, BFormRadio, BCardText, BFormFile,BFormInput, BRow, BCol, 
          BCard, BFormTextarea,BMedia, BMediaAside, BMediaBody, BLink, BImg, BFormInvalidFeedback
        } from 'bootstrap-vue'
import vSelect from 'vue-select'
import flatPickr from 'vue-flatpickr-component' 
import Ripple from 'vue-ripple-directive'
import Cleave from 'vue-cleave-component'

import { useInputImageRenderer } from '@core/comp-functions/forms/form-utils';
// eslint-disable-next-line import/no-extraneous-dependencies
import 'cleave.js/dist/addons/cleave-phone.us'
import { ref } from '@vue/composition-api'


import { required,email,size } from '@validations'
import formValidation from '@core/comp-functions/forms/form-validation'
import { ValidationProvider, ValidationObserver } from 'vee-validate'

import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default {
  components: {
    BButton,
    BForm,
    BFormGroup,
    BFormRadio,
    BFormInput,
    BRow,
    BCol,
    BCard,
    BFormTextarea,
    vSelect,
    flatPickr,
    Cleave,
    BMedia,
    BMediaAside,
    BMediaBody,
    BFormFile,
    BCardText,
    BInputGroup,
    BLink,
    BImg,
    BFormInvalidFeedback,
  
    ValidationProvider,
    ValidationObserver
  },
  directives: {
    Ripple,
  },
  props: {
    informationData: {
      type: Object,
      default: () => {},
    },
  },
  computed:{
    id: function() {
      return this.$store.state.auth.user.id;
    },
    role: function() {
      return this.$store.state.auth.user.role_id;
    }
  },
  mounted() {
    console.log(this.informationData);
  },
  data() {
    return {
      specializations: ['Doctor','Health Care Workers', 'Others'],
      groups: ['Doctor Group', 'Physician Group'],
      types: ['Doctors', 'Healthcare Professionals'],
      logo: null,
      signature: null,
      banner: null,
      errors: [],
    }
  },
  setup(props) {
    const refLogoEl = ref(null)
    const previewLogoEl = ref(null)
    const refSignatureEl = ref(null)
    const previewSignatureEl = ref(null)
    const refBannerEl = ref(null)
    const previewBannerEl = ref(null)

    const inputLogoRenderer = useInputImageRenderer(refLogoEl, previewLogoEl)
    const inputSignatureRenderer = useInputImageRenderer(refSignatureEl, previewSignatureEl)
    const inputBannerRenderer = useInputImageRenderer(refBannerEl, previewBannerEl)

    const userData = ref(JSON.parse(JSON.stringify(props.informationData)))
    const resetuserData = () => {
      userData.value = JSON.parse(JSON.stringify(props.informationData))
    } 
    const {
      refFormObserver,
      getValidationState,
      resetForm,
    } = formValidation(resetuserData)

    return {
      refLogoEl,
      previewLogoEl,
      refSignatureEl,
      previewSignatureEl,
      inputLogoRenderer,
      inputSignatureRenderer,
      refBannerEl,
      previewBannerEl,
      inputBannerRenderer,

      userData,
      refFormObserver,
      getValidationState,
      resetForm,

    }
  },
  methods: {
      async onSubmit() { 
      try {
        let userData  = new FormData();
        userData.append('logo', this.logo);
        userData.append('signature_img', this.signature);
        if( this.role > 3 ) {
          userData.append('specialization', this.userData.specialization);
          userData.append('specialized_in', this.userData.specialized_in);
          userData.append('cost', this.userData.cost);
        } else {
          userData.append('banner_img', this.banner);
          userData.append('company_name', this.userData.company_name);
          userData.append('contact_person', this.userData.contact_person);
          userData.append('company_contact', this.userData.company_contact);
          userData.append('welcome_message',this.userData.welcome_message);
          userData.append('group',this.userData.group);
          userData.append('type',this.userData.type);
          userData.append('url', this.userData.url);
          userData.append('clinic_name', this.userData.clinic_name);
          userData.append('website_name', this.userData.website_name);
          userData.append('dob',this.userData.dob);
          userData.append('gender',this.userData.gender);
          userData.append('occupation',this.userData.occupation);
          userData.append('guard_mob',this.userData.guard_mob);
          userData.append('address', this.userData.address);
          userData.append('city',this.userData.city);
          userData.append('pincode', this.userData.pincode);
          userData.append('state', this.userData.state);
          userData.append('group',this.userData.group); 

        }
     

        const {data} = await axios.post(
            '/account/information',
            userData,
            {
              headers: {
                  'content-type': 'multipart/form-data'
              }
            }
        ); 

        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: 'BellIcon',
            variant: data.success ? 'success' : 'info',
          },
        });
        this.resetForm();
        this.$emit('change')
        
      } catch( error ) {
        if( error.response) {
          this.errors = error.response.data.errors;
        }
        console.log(error)
      }
    },
  },
}
</script>

<style lang="scss">
@import '@core/scss/vue/libs/vue-select.scss';
@import '@core/scss/vue/libs/vue-flatpicker.scss';
</style>