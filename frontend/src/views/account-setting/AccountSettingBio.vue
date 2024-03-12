<template>
  <b-card>
    <validation-observer
      #default="{ handleSubmit }"
      ref="refFormObserver"
    >
      <!-- form -->
      <b-form @submit.prevent="handleSubmit(onSubmit)">
        <b-row>
          <!-- bio -->
          <b-col cols="12">
            <validation-provider
              #default="validationContext"
              name="Bio"
              rules="required"
            >
            <b-form-group
              label="Bio"
              label-for="bio-area"
            >
            <quill-editor
                id="description"
                v-model="userData.bio"
                :options="editorOption"
                rows="4"
              />
              <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
            </validation-provider>
          </b-col>
          <b-col md="6" >
            <validation-provider
                #default="validationContext"
                name="Experience"
                rules="required"
            >
              <b-form-group
                  label-for="qualification"
                  label="Experience"
              >
                  <b-form-input
                      id="ifsc"
                      type="number"
                      step="0.1"
                      v-model="userData.experience"
                      :state="getValidationState(validationContext)"
                      placeholder="Years Of Experience"
                  />
                  <b-form-invalid-feedback>
                      {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>
          </b-col>
          <!-- <b-col cols="12" md="6" >
              <validation-provider
                  #default="validationContext"
                  name="Skills"
                  rules="required"
              >
                  <b-form-group
                      label="Skills"
                      label-for="skills"
                      >
                      <tags-input
                          v-model="userData.skills"  
                          id="tags"
                          id-field="id"
                          text-field="name" 
                          :typeahead="true"
                          placeholder=""
                          :add-tags-on-comma="true"
                          :add-tags-on-space="true">
                      </tags-input>
                      <b-form-invalid-feedback :state="getValidationState(validationContext)">
                          {{ validationContext.errors[0] }}
                      </b-form-invalid-feedback>
                  </b-form-group>
              </validation-provider>
          </b-col> -->
          <b-col cols="12" md="6" >
            <validation-provider
                #default="validationContext"
                name="Languages Spoken"
                rules="required"
            >
                <b-form-group
                    label="Languages Spoken"
                    label-for="languages"
                >
                  <tags-input
                      v-model="userData.languages"  
                      id="tags"
                      id-field="id"
                      text-field="name" 
                      :typeahead="true"
                      :add-tags-on-comma="true"
                      :add-tags-on-space="true"
                      placeholder="">
                  </tags-input>
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                      {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
            </validation-provider>
          </b-col>
          <!-- <b-col md="6" >
            <validation-provider
                #default="validationContext"
                name="Aim"
                rules="required"
            >
              <b-form-group
                  label-for="aim"
                  label="Aim"
              >
                  <b-form-textarea
                      id="ifsc"
                      v-model="userData.aim"
                      :state="getValidationState(validationContext)"
                      placeholder="Aim"
                  />
                  <b-form-invalid-feedback>
                      {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>
          </b-col>
          -->

          <b-col cols="12">
            <b-button
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              variant="primary"
              class="mt-1 mr-1"
              type="submit"
            >
              Save changes
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
import { BButton, BForm, BFormGroup, BFormInput, BRow, BCol, BCard, BFormTextarea,BFormInvalidFeedback } from 'bootstrap-vue'
import vSelect from 'vue-select'
import flatPickr from 'vue-flatpickr-component'
import Ripple from 'vue-ripple-directive'
import Cleave from 'vue-cleave-component'

// eslint-disable-next-line import/no-extraneous-dependencies
import 'cleave.js/dist/addons/cleave-phone.us'
import 'quill/dist/quill.snow.css'
import { quillEditor } from 'vue-quill-editor'
import { ref } from '@vue/composition-api'

import { required,email } from '@validations'
import formValidation from '@core/comp-functions/forms/form-validation'
import { ValidationProvider, ValidationObserver } from 'vee-validate'



import VoerroTagsInput from '@voerro/vue-tagsinput';

require('@voerro/vue-tagsinput/dist/style.css')

import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'



export default {
  components: {
    BButton,
    BForm,
    BFormGroup,
    BFormInput,
    BRow,
    BCol,
    BCard,
    BFormTextarea,
    vSelect,
    flatPickr,
    Cleave,
    quillEditor,
    BFormInvalidFeedback,

    ValidationProvider,
    ValidationObserver,

    "tags-input": VoerroTagsInput,
  },
  directives: {
    Ripple,
  },
  props: {
    bioData: {
      type: Object,
      default: () => {},
    },
  },
  setup(props){
    const userData = ref(JSON.parse(JSON.stringify(props.bioData)))

    const resetuserData = () => {
      userData.value = JSON.parse(JSON.stringify(props.bioData))
    } 
    const {
      refFormObserver,
      getValidationState,
      resetForm,
    } = formValidation(resetuserData)


    return {

      userData,
      refFormObserver,
      getValidationState,
      resetForm,

    }
  },
  data() {
    var toolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
        [{ 'direction': 'rtl' }],                         // text direction
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
        [{ 'color': [] }],          // dropdown with defaults from theme
        [{ 'font': [] }],
        [{ 'align': [] }],                                       // remove formatting button
    ];
    return {
      editorOption: {
          modules: {
              toolbar: toolbarOptions
          },
        theme: 'snow',
      },
      errors: [],
      required
    }
  },
  methods: {
   

    async onSubmit() {
      try {
        if( this.userData.bio ) {
          const {data} = await axios.post('account/bio', this.userData)
          this.$toast({
            component: ToastificationContent,
            props: {
              title: data.message,
              icon: 'BellIcon',
              variant: data.success ? 'success' : 'info',
            },
          });
          this.$emit('change')
        }  
      } catch( error ) {
        if( error.response) {
          this.errors = error.response.data.errors;
        }
        console.log(error)
      }
    }
  },
}
</script>

<style lang="scss">
@import '@core/scss/vue/libs/quill.scss';
.ql-container{
    min-height:150px !important
}
</style>

