<template>
    <b-card title="Create your Questionnaire">
      <validation-observer
        #default="{ handleSubmit }"
        ref="refFormObserver"
      >
        <b-form ref="form" @submit.prevent="handleSubmit(onSubmit)">
          <b-row>
            <b-col>
              <b-button
                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                type="submit"
                variant="primary"
                style="float:right"
                v-permission="['create-questionnaire']"
              >
                Submit
              </b-button>
              <b-button
                v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                type="reset"
                class="mr-1"
                variant="outline-secondary"
                style="float:right"
                @click="resetQuestionnaire"
              >
                Reset
              </b-button>
            </b-col>
          </b-row>
          <b-row>
            <b-col md="6">
              <validation-provider
                #default="validationContext"
                name="mc-questionnaires-name"
                rules="required"
              >
                <b-form-group
                  label="Questionnaire Name"
                  label-for="mc-questionnaires-name"
                >
                  <b-form-input
                    :state="
                        getValidationState(
                            validationContext
                        )
                    "
                    v-model="questionnaire.name"
                    :class="errors && errors.name ? 'is-invalid' : ''"
                    :area-invalid="errors && errors.name ? true : false"
                    id="mc-questionnaires-name"
                    placeholder="Questionnaire Name"
                  />
                  <b-form-invalid-feedback v-if="errors && errors.name">
                        {{errors.name[0] }}
                  </b-form-invalid-feedback>
                  <b-form-invalid-feedback v-else>
                      {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col md="6">
              <validation-provider
                #default="validationContext"
                name="mc-questionnaires-description"
                rules="required"
              >
                <b-form-group
                  label="Questionnaire Description"
                  label-for="mc-questionnaires-description"
                >
                  <b-form-input
                   :state="
                        getValidationState(
                            validationContext
                        )
                    "
                    id="mc-questionnaires-description"
                    :class="errors && errors.description ? 'is-invalid' : ''"
                    :area-invalid="errors && errors.description ? true : false"
                    v-model="questionnaire.description"
                    placeholder="Questionnaire Description"
                  />
                  <b-form-invalid-feedback v-if="errors && errors.description">
                        {{errors.description[0] }}
                  </b-form-invalid-feedback>
                  <b-form-invalid-feedback v-else>
                      {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
          </b-row>
          <b-row style="margin-top:2%;">
            <b-col cols="12">
              <span v-if="errors && errors.content" class="text-danger">
                {{errors.content }}
              </span>
              <div id="fb-editor" >
                
              </div>
              
            </b-col>
          </b-row>
       
        </b-form>
      </validation-observer>
</b-card>
</template>
<style >
ul.stage-wrap {
  height: 458px !important;
  overflow-y: auto !important;
}
</style>
<script>
import { BCard, BCardText, BRow, BCol, BFormGroup, BFormInput, BFormCheckbox, BForm, BButton,BFormInvalidFeedback } from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'

import { ValidationProvider, ValidationObserver } from "vee-validate";

import formValidation from "@core/comp-functions/forms/form-validation";
import { required } from "@validations";


import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

import $ from "jquery";

window.jQuery = $;
window.$ = $;
require("jquery-ui-sortable");
require("formBuilder");

export default {
  components: {
    BCard,
    BCardText,
    BRow,
    BCol,
    BFormGroup,
    BFormInput,
    BFormCheckbox,
    BForm,
    BButton,
    BFormInvalidFeedback,


    ValidationProvider, 
    ValidationObserver
  },
  directives: {
    Ripple,
  },

  data() {
    const {
          refFormObserver,
          getValidationState,
          resetForm
    } = formValidation(this.resetQuestionnaireData);
    return {
      formBuilder: null,
      questionnaire: {
        name: '',
        description: '',
        content: ''
      },
      required,
      errors:{},
      refFormObserver,
      getValidationState,
      resetForm
    }
  },
  mounted(){
    this.formBuilder = $('#fb-editor').formBuilder({
      disabledActionButtons: ['data','save','clear'],
      disableFields: ['hidden','button'],
      disabledAttrs: ['access']
    });
  },
  methods:{
    async onSubmit() {
      this.questionnaire.content = this.formBuilder.actions.getData()
      if( this.questionnaire.content.length ) {
        try {
          const {data} =  await axios.post( "/questionnaire", this.questionnaire );
          console.log(data)
          this.errors = {};
          
          this.$toast({
              component: ToastificationContent,
              props: {
                  title: data.message,
                  icon: "BellIcon",
                  variant: data.success ? "success" : "danger"
              }
          });

          this.$router.replace({name: 'questionnaires'})

        } catch (error) {
          this.errors = error.response.data.errors;
        }
      }  else {
        this.errors.content = "Please create form first";
      }
     

    },
    resetQuestionnaire() {
      this.formBuilder.actions.clearFields();
      this.errors = {};
      this.resetQuestionnaireData();
      this.questionnaire = {
        name: '',
        description: '',
        content: ''
      }
    },
    resetQuestionnaireData(){
      this.questionnaire.value = {
        name: '',
        description: '',
        content: ''
      }
    }
  }
  
}
</script>
