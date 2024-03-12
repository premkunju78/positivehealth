<template>
  <b-card >
      <!-- BODY -->
      <validation-observer
        #default="{ handleSubmit }"
        ref="refFormObserver"
      >
        <!-- Form -->
        <b-form
          class="p-2"
          @submit.prevent="handleSubmit(onSubmit)"
          @reset.prevent="resetForm"
        >
        <b-row>
            <b-col  cols="12"  md="4"
                v-for="(testList,category) in tests"  :key="category">
                <div>
                  <b-button v-b-toggle="category.replace(' ','-')" variant="info">{{category}}</b-button>
                  <b-collapse :id="category.replace(' ','-')"  visible class="mt-1">
                    <b-card >
                      <b-form-group
                        v-slot="{ ariaDescribedby }"
                      >
                        <b-form-checkbox-group
                          v-model="investigationData.test[category]"
                          :options="tests[category]"
                          :aria-describedby="ariaDescribedby"

                          stacked
                        ></b-form-checkbox-group>
                      </b-form-group>
                    </b-card>
                  </b-collapse>
                </div>
              
          </b-col>
        </b-row>
         <div class="d-flex " style="margin-top:5%">
              <b-button
                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                variant="primary"
                class="mr-2"
                type="submit"
              >
                Update Investigation
              </b-button>
              <b-button
                v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                type="button"
                variant="outline-secondary"
                @click="$router.back()"

              >
                Cancel
              </b-button>
              </router-link>
            </div>
        </b-form>
      </validation-observer>
  </b-card>
</template>

<script>
import { BCollapse, BCard, BCardText, BForm, BFormGroup, BFormInput, BFormInvalidFeedback, BButton, BRow,BCol, BFormCheckboxGroup, BOverlay } from 'bootstrap-vue'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { required } from '@validations'
import { ref } from '@vue/composition-api'
import Ripple from 'vue-ripple-directive'
import formValidation from '@core/comp-functions/forms/form-validation'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import { VBToggle } from 'bootstrap-vue';
export default {
    components: {
        BCard,
        BCardText,
        BForm,
        BFormGroup,
        BFormInput,
        BFormInvalidFeedback,
        BButton,
        ValidationProvider,
        ValidationObserver,
        BRow,
        BCol,
        BFormCheckboxGroup,
        BOverlay,
        BCollapse

    },
    directives: {
        Ripple,
        'b-toggle': VBToggle
    },
    props: {
      id: {
        type: [Number,String],
        required: true
      }
    },
    created(){
        this.getInvestigations();
        this.getTest();
    },
    data(){
        return {
            tests: [],
            required
        }
    },
    setup(props, { emit }) {
        const  investigation = {
            test : {}
        }
        const investigationData = ref(JSON.parse(JSON.stringify(investigation)))

        const resetInvestigationData = () => {
            investigation.value = JSON.parse(JSON.stringify(investigation))
        }


        const {
            refFormObserver,
            getValidationState,
            resetForm,
        } = formValidation(resetInvestigationData)

        return {
        investigationData,
        refFormObserver,
        getValidationState,
        resetForm,
        }
  },

    methods: {
        async getInvestigations(){
            try {
                const { data } = await axios.get(`investigation/${this.id}`);
                this.investigationData.id = data.investigation.id;
                this.investigationData.test = data.investigation.test;
            }catch (err) {
                console.log(err);
            }
        },
        async getTest(){
            const {data} = await axios.get('investigation/test/list');
            this.tests = data.test;
        },
        async onSubmit() {
            let data;
            try {
                this.investigationData.client = this.id;
                if( this.investigationData.id) {
                    data = await axios.put(`investigation/${this.investigationData.id}`, this.investigationData)
                } else {
                    data = await axios.post(`investigation`, this.investigationData)
                }
                this.$toast({
                    component: ToastificationContent,
                    props: {
                        title: data.data.message,
                        icon: 'EditIcon',
                        variant: data.data.success ? 'success' : 'info',
                    },
                });
              this.$router.back();
            } catch (error) {
                console.log(error)
            }
        },
    }
}
</script>