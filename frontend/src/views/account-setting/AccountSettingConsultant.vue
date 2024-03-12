<template>
  <b-card>
    <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
      <!-- form -->
      <b-form @submit.prevent="handleSubmit(onSubmit)">
        <h4 v-if="role > 3 && is_consultant == 1">Education Details</h4>
        <b-row v-if="role > 3 && is_consultant == 1" style="margin-bottom:2%">
          <!-- Qualifications --> <!--allowed for consultants-->
          <b-col md="6">
            <validation-provider #default="validationContext" name="Qualifications" rules="required">
              <b-form-group label-for="qualification" label="Qualification">
                <v-select id="specialization" :state="getValidationState(validationContext)"
                  v-model="userData.qualification" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                  label="qualification" :options="qualifications" />
                <b-form-invalid-feedback>
                  {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>
          </b-col>


          <!-- Specialization --> <!--allowed for consultants-->
          <b-col md="6">
            <validation-provider #default="validationContext" name="Specialization" rules="required">
              <b-form-group label-for="specialized_in" label="Specialization">
                <b-form-input id="specialized_in" :state="getValidationState(validationContext)"
                  v-model="userData.specialized_in" placeholder="Specialization" />
                <b-form-invalid-feedback>
                  {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>
          </b-col>
          <!--/ Specialization -->

          <!-- Cost per session --> <!--allowed for consultants-->
          <b-col md="6">
            <validation-provider #default="validationContext" name="Cost per session" rules="required">
              <b-form-group label-for="cost" label="Cost per session">
                <b-form-input id="cost" v-model="userData.cost" placeholder="Cost per session"
                  :state="getValidationState(validationContext)" />
                <b-form-invalid-feedback>
                  {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>
          </b-col>

          <!-- certificates --> <!--allowed for consultants -->
          <b-col md="6" class="mt-2 mb-2">
            <!-- media -->
            <b-media no-body :class="errors.certificate ? 'is-invalid' : ''"
              :area-invalid="errors.certificate ? true : false">
              <b-media-aside>
                <ul ref="previewCertificateEl" v-if="!certificate.length" style="list-style:none">
                  <li v-for="(certificate, index) in userData.certificates" :key="certificate">
                    <a class="heading" :href="'/view/file?path=' + certificate" target="_blank">
                      <feather-icon size="20" icon="FileTextIcon"></feather-icon>
                    </a>

                    <feather-icon icon="XIcon" style="cursor:pointer" @click="removeFile(index)"></feather-icon>
                  </li>
                </ul>
                <ul ref="previewCertificateEl" v-else>
                  <li v-for="(certificate, index) in certificate" :key="certificate.name">
                    <small>{{ certificate.name }}
                      <feather-icon icon="XIcon" style="cursor:pointer" @click="remove(index)"></feather-icon>
                    </small>
                  </li>
                </ul>

                <!--/ avatar -->
              </b-media-aside>

              <b-media-body class="mt-75 ml-75">
                <!-- upload button -->
                <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" size="sm" class="mb-75 mr-75"
                  @click="$refs.refCertificateEl.$el.click()">
                  Certificates
                </b-button>
                <b-form-file ref="refCertificateEl" v-model="certificate" accept=".jpg, .png, .gif, .pdf" :hidden="true"
                  plain multiple @input="validateSize(certificate, 'certificate', inputCertificateRenderer)" />
                <!--/ upload button -->

                <!-- reset -->
                <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" variant="outline-secondary" size="sm"
                  class="mb-75 mr-75" v-if="certificate.length" @click="certificate = []">
                  Reset
                </b-button>
                <!--/ reset -->
                <small>Allowed JPG, GIF or PNG. Max Size 2mb</small>
              </b-media-body>
            </b-media>
            <b-form-invalid-feedback v-if="errors.certificate">
              {{ errors.certificate[0] }}
            </b-form-invalid-feedback>
          </b-col>

          <!-- signature --> <!--allowed for consultants and alliancepartner-->
          <b-col md="6" class="mt-2 mb-2">
            <!-- media -->
            <b-media no-body :class="errors.signature ? 'is-invalid' : ''"
              :area-invalid="errors.signature ? true : false">
              <b-media-aside>
                <a :href="'/view/file?path=' + userData.signature_img" target="_blank">
                  <b-img ref="previewSignatureEl" rounded :src="'/view/file?path=' + userData.signature_img"
                    height="80" />
                </a>
                <!--/ avatar -->
              </b-media-aside>

              <b-media-body class="mt-75 ml-75">
                <!-- upload button -->
                <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" size="sm" class="mb-75 mr-75"
                  @click="$refs.refSignatureEl.$el.click()">
                  Signature
                </b-button>
                <b-form-file ref="refSignatureEl" v-model="signature" accept=".jpg, .png, .gif" :hidden="true" plain
                  @input="validateSize(signature, 'signature', inputSignatureRenderer)" />
                <!--/ upload button -->

                <!-- reset -->
                <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" variant="outline-secondary" size="sm"
                  class="mb-75 mr-75" v-if="signature"
                  @click="$refs.previewSignatureEl.src = '/view/file?path=' + userData.signature_img; signature = null">
                  Reset
                </b-button>
                <!--/ reset -->
                <small>Allowed JPG, GIF or PNG. Max Size 2mb</small>
              </b-media-body>
            </b-media>
            <b-form-invalid-feedback v-if=" errors.signature ">
              {{ errors.signature[0] }}
            </b-form-invalid-feedback>
          </b-col>

        </b-row>
        <h4>Bank Details</h4>
        <b-row style="margin-bottom:2%">

          <!-- Bank Name --> <!--allowed for consultants-->
          <b-col md="6">
            <validation-provider #default=" validationContext " name="Bank Name" rules="required">
              <b-form-group label-for="bank" label="Bank Name">
                <b-form-input id="bank" v-model=" userData.bank_name " :state=" getValidationState(validationContext) "
                  placeholder="Bank Name" />
                <b-form-invalid-feedback>
                  {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>
          </b-col>


          <!-- account no --> <!--allowed for consultants-->
          <b-col md="6">
            <validation-provider #default=" validationContext " name="Account Number" rules="required">
              <b-form-group label-for="account_no" label="Account Number">
                <b-form-input id="account_no" :state=" getValidationState(validationContext) "
                  v-model=" userData.account_no " placeholder="Account Number" />
                <b-form-invalid-feedback>
                  {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>
          </b-col>

          <!-- IFSC Code --> <!--allowed for Alliance partners-->
          <b-col md="6">
            <validation-provider #default=" validationContext " name="IFSC Code" rules="required">
              <b-form-group label-for="ifsc" label="IFSC Code">
                <b-form-input id="ifsc" v-model=" userData.ifsc_code " :state=" getValidationState(validationContext) "
                  placeholder="IFSC Code" />
                <b-form-invalid-feedback>
                  {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>
          </b-col>

          <!-- cancelled cheque --> <!--allowed for consultants and alliancepartner-->
          <b-col md="6" class="mt-2 mb-2">
            <!-- media -->
            <b-media no-body :class=" errors.cheque ? 'is-invalid' : '' " :area-invalid=" errors.cheque ? true : false ">
              <b-media-aside>
                <a :href=" '/view/file?path=' + userData.cancelled_cheque " target="_blank">
                  <b-img ref="previewChequeEl" rounded :src=" '/view/file?path=' + userData.cancelled_cheque "
                    height="80" />
                </a>
                <!--/ avatar -->
              </b-media-aside>

              <b-media-body class="mt-75 ml-75">
                <!-- upload button -->
                <b-button v-ripple.400=" 'rgba(255, 255, 255, 0.15)' " variant="primary" size="sm" class="mb-75 mr-75"
                  @click=" $refs.refChequeEl.$el.click() ">
                  Cancelled Cheque
                </b-button>
                <b-form-file ref="refChequeEl" v-model=" cheque " accept=".jpg, .png, .gif" :hidden=" true " plain
                  @input=" validateSize(cheque, 'cheque', inputChequeRenderer) " />
                <!--/ upload button -->

                <!-- reset -->
                <b-button v-ripple.400=" 'rgba(186, 191, 199, 0.15)' " variant="outline-secondary" size="sm"
                  class="mb-75 mr-75" v-if=" cheque "
                  @click=" $refs.previewChequeEl.src = '/view/file?path=' + userData.cancelled_cheque; cheque = null ">
                  Reset
                </b-button>
                <!--/ reset -->
                <small>Allowed JPG, GIF or PNG. Max Size 2mb</small>
              </b-media-body>

            </b-media>
            <b-form-invalid-feedback v-if=" errors.cheque ">
              {{ errors.cheque[0] }}
            </b-form-invalid-feedback>

          </b-col>
        </b-row>
        <h4 v-if=" role === 2 ">Other Details</h4>
        <b-row v-if=" ![1, 3, 15, 16, 18, 19].includes(role) ">
          <!-- logo --> <!--allowed for alliancepartner-->
          <b-col md="6" class="mt-2 mb-2">
            <!-- media -->
            <b-media no-body :class=" errors.logo ? 'is-invalid' : '' " :area-invalid=" errors.logo ? true : false ">
              <b-media-aside>
                <a :href=" '/view/file?path=' + userData.logo " target="_blank">
                  <b-img ref="previewLogoEl" rounded :src=" '/view/file?path=' + userData.logo " height="80" />
                </a>
                <!--/ avatar -->
              </b-media-aside>

              <b-media-body class="mt-75 ml-75">
                <!-- upload button -->
                <b-button v-ripple.400=" 'rgba(255, 255, 255, 0.15)' " variant="primary" size="sm" class="mb-75 mr-75"
                  @click=" $refs.refLogoEl.$el.click() ">
                  Logo
                </b-button>
                <b-form-file ref="refLogoEl" v-model=" logo " accept=".jpg, .png, .gif" :hidden=" true " plain
                  @input=" validateSize(logo, 'logo', inputLogoRenderer) " />
                <!--/ upload button -->

                <!-- reset -->
                <b-button v-ripple.400=" 'rgba(186, 191, 199, 0.15)' " variant="outline-secondary" size="sm"
                  class="mb-75 mr-75" v-if=" logo "
                  @click=" $refs.previewLogoEl.src = '/view/file?path=' + userData.logo; logo = null ">
                  Reset
                </b-button>
                <!--/ reset -->
                <small>Allowed JPG, GIF or PNG. Max Size 2mb</small>
              </b-media-body>
            </b-media>
            <b-form-invalid-feedback v-if=" errors.logo ">
              {{ errors.logo[0] }}
            </b-form-invalid-feedback>
          </b-col>
          <!-- banner --> <!--allowed for alliancepartner-->
          <b-col md="6" class="mt-2 mb-2">
            <!-- media -->
            <b-media no-body :class=" errors.banner ? 'is-invalid' : '' " :area-invalid=" errors.banner ? true : false ">
              <b-media-aside>
                <a :href=" '/view/file?path=' + userData.banner_img " target="_blank">
                  <b-img ref="previewBannerEl" rounded :src=" '/view/file?path=' + userData.banner_img " height="80" />
                </a>
                <!--/ avatar -->
              </b-media-aside>

              <b-media-body class="mt-75 ml-75">
                <!-- upload button --> <!--allowed for alliancepartner-->
                <b-button v-ripple.400=" 'rgba(255, 255, 255, 0.15)' " variant="primary" size="sm" class="mb-75 mr-75"
                  @click=" $refs.refBannerEl.$el.click() ">
                  Banner
                </b-button>
                <b-form-file ref="refBannerEl" v-model=" banner " accept=".jpg, .png, .gif" :hidden=" true " plain
                  @input=" validateSize(banner, 'banner', inputBannerRenderer) " />
                <!--/ upload button -->

                <!-- reset -->
                <b-button v-ripple.400=" 'rgba(186, 191, 199, 0.15)' " variant="outline-secondary" size="sm"
                  class="mb-75 mr-75" v-if=" banner "
                  @click=" $refs.previewBannerEl.src = '/view/file?path=' + userData.banner_img; banner = null ">
                  Reset
                </b-button>
                <!--/ reset -->
                <small>Allowed JPG, GIF or PNG. Max Size 2mb</small>
              </b-media-body>
            </b-media>
            <b-form-invalid-feedback v-if=" errors.banner ">
              {{ errors.banner[0] }}
            </b-form-invalid-feedback>
          </b-col>
        </b-row>
        <b-row>
          <b-col cols="12">
            <b-button v-ripple.400=" 'rgba(255, 255, 255, 0.15)' " variant="primary" class="mt-1 mr-1" type="submit">
              Update
            </b-button>

            <b-button v-ripple.400=" 'rgba(186, 191, 199, 0.15)' " type="reset" class="mt-1" variant="outline-secondary"
              @click.prevent=" resetData ">
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
  BInputGroup, BButton, BForm, BFormGroup, BFormRadio, BCardText, BFormFile, BFormInput, BRow, BCol,
  BCard, BFormTextarea, BMedia, BMediaAside, BMediaBody, BLink, BImg, BFormInvalidFeedback
} from 'bootstrap-vue'
import vSelect from 'vue-select'
import flatPickr from 'vue-flatpickr-component'
import Ripple from 'vue-ripple-directive'
import Cleave from 'vue-cleave-component'

import { useInputImageRenderer } from '@core/comp-functions/forms/form-utils';
// eslint-disable-next-line import/no-extraneous-dependencies
import 'cleave.js/dist/addons/cleave-phone.us'
import { ref } from '@vue/composition-api'


import { required, email } from '@validations'
import formValidation from '@core/comp-functions/forms/form-validation'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { validateSize } from '@core/utils/utils'

import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import FeatherIcon from '../../@core/components/feather-icon/FeatherIcon.vue'

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
    ValidationObserver,
    FeatherIcon
  },
  directives: {
    Ripple,
  },
  props: {
    informationData: {
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
    is_consultant: function () {
      return this.$store.state.auth.user.role.is_consultant;
    }
  },
  mounted() {
    console.log(this.informationData);
  },
  data() {
    return {
      specializations: ['Doctor', 'Health Care Workers', 'Others'],
      groups: ['Doctor Group', 'Physician Group'],
      types: ['Doctors', 'Healthcare Professionals'],
      qualifications: ['Graduate', 'Post Graduate', 'PG diploma', 'PhD'],
      errors: [],
      logo: null,
      signature: null,
      banner: null,
      cheque: null,
      required,
      maxSize: 2097152
    }
  },
  watch: {
    cheque: function (val) {
      if (val) {
        this.errors = [];
      }
    }
  },
  setup(props) {
    const refLogoEl = ref(null)
    const previewLogoEl = ref(null)
    const refSignatureEl = ref(null)
    const previewSignatureEl = ref(null)
    const refBannerEl = ref(null)
    const previewBannerEl = ref(null)
    const refCertificateEl = ref(null)
    const previewCertificateEl = ref(null)
    const refChequeEl = ref(null)
    const previewChequeEl = ref(null)
    const certificate = ref([])

    const inputLogoRenderer = useInputImageRenderer(refLogoEl, previewLogoEl)
    const inputSignatureRenderer = useInputImageRenderer(refSignatureEl, previewSignatureEl)
    const inputBannerRenderer = useInputImageRenderer(refBannerEl, previewBannerEl)
    const inputCertificateRenderer = useInputImageRenderer(refCertificateEl, previewCertificateEl)
    const inputChequeRenderer = useInputImageRenderer(refChequeEl, previewChequeEl)

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
      certificate,
      previewLogoEl,
      refSignatureEl,
      previewSignatureEl,
      inputLogoRenderer,
      inputSignatureRenderer,
      refBannerEl,
      previewBannerEl,
      inputBannerRenderer,
      refCertificateEl,
      previewCertificateEl,
      inputCertificateRenderer,
      refChequeEl,
      previewChequeEl,
      inputChequeRenderer,

      userData,
      refFormObserver,
      getValidationState,
      resetForm,

    }
  },
  methods: {
    validateSize,
    async onSubmit() {
      try {

        if (this.logo)
          if (!this.cheque && !this.userData.cancelled_cheque) {
            this.errors = {
              'cheque': ['Cancelled Cheque field is required']
            }
            return;
          } else {
            this.errors = [];
          }


        let userData = new FormData();
        userData.append('logo', this.logo);
        userData.append('qualification', this.userData.qualification);
        userData.append('specialized_in', this.userData.specialized_in);
        userData.append('cost', this.userData.cost);
        userData.append('signature_img', this.signature);
        this.certificate.map((certificate, index) => {
          userData.append(`certificates[${index}]`, certificate);
        })
        userData.append('bank_name', this.userData.bank_name);
        userData.append('account_no', this.userData.account_no);
        userData.append('ifsc_code', this.userData.ifsc_code);
        userData.append('cancelled_cheque', this.cheque);
        userData.append('banner_img', this.banner);


        const { data } = await axios.post(
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
        this.$emit('change');


      } catch (error) {
        if (error.response) {
          this.errors = error.response.data.errors;
        }
        console.log(error)
      }
    },
    remove(index) {
      this.certificate.splice(index, 1);
    },
    async removeFile(index) {
      try {
        const { data } = await axios.delete(`/account/certificate/${index}`)
        if (data.success) {
          this.resetForm();
          this.$emit('change');
        }
      } catch (error) {
        if (error.response) {
          this.errors = error.response.data.errors;
        }
      }
    },
    resetData() {
      this.resetForm();
      this.logo = null;
      this.signature = null;
      this.banner = null;
      this.cheque = null;
      this.certificate = null;

      this.$refs.previewLogoEl.src = '/view/file?path=' + this.userData.logo;
      this.$refs.previewSignatureEl.src = '/view/file?path=' + this.userData.signature
      this.$refs.previewBannerEl.src = '/view/file?path=' + this.userData.banner_img
      this.$refs.previewChequeEl.src = '/view/file?path=' + this.userData.cancelled_cheque
      //this.$refs.previewCertificateEl.src = '/view/file?path='+this.userData.certificates
    }
  },
}
</script>

<style lang="scss">
@import '@core/scss/vue/libs/vue-select.scss';
@import '@core/scss/vue/libs/vue-flatpicker.scss';
</style>