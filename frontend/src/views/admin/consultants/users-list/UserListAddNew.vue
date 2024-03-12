<template>
  <b-sidebar
    id="add-new-user-sidebar"
    :visible="isAddNewUserSidebarActive"
    bg-variant="white"
    sidebar-class="sidebar-lg"
    shadow
    backdrop
    no-header
    right
    @hidden="resetForm"
    @change="(val) => $emit('update:is-add-new-user-sidebar-active', val)"
  >
    <template #default="{ hide }">
      <!-- Header -->
      <div class="d-flex justify-content-between align-items-center content-sidebar-header px-2 py-1">
        <h5 class="mb-0">
          Add New Consultant
        </h5>

        <feather-icon
          class="ml-1 cursor-pointer"
          icon="XIcon"
          size="16"
          @click="hide"
        />

      </div>

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

          <!-- First Name -->
          <validation-provider
            #default="validationContext"
            name="First Name"
            rules="required"
          >
            <b-form-group
              label="First Name"
              label-for="first_name"
            >
              <b-form-input
                id="first_name"
                v-model="userData.first_name"
                autofocus
                :state="getValidationState(validationContext)"
                trim
                placeholder="John"
              />

              <b-form-invalid-feedback>
                {{ validationContext.errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>
           <!-- Last Name -->
          <validation-provider
            #default="validationContext"
            name="Last Name"
            rules="required"
          >
            <b-form-group
              label="Last Name"
              label-for="last_name"
            >
              <b-form-input
                id="last_name"
                v-model="userData.last_name"
                autofocus
                :state="getValidationState(validationContext)"
                trim
                placeholder="Doe"
              />

              <b-form-invalid-feedback>
                {{ validationContext.errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>
          <!-- Email -->
          <validation-provider
            #default="validationContext"
            name="Email"
            rules="required|email"
          >
            <b-form-group
              label="Email"
              label-for="email"
            >
              <b-form-input
                id="email"
                v-model="userData.email"
                :state="getValidationState(validationContext)"
                trim
              />

              <b-form-invalid-feedback>
                {{ validationContext.errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>

          <!-- Phone Number -->

          <validation-provider
              #default="validationContext"
              name="Phone Number"
              rules="required"
          >
              <b-form-group
                  label="Phone Number"
                  label-for="phone number"
              >
                  <b-form-input
                      id="phone_number"
                      v-model="userData.phone"
                      type="number"
                      placeholder="9877766556"
                      :state="
                          getValidationState(validationContext)
                      "
                  />
                  <b-form-invalid-feedback>
                      {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
              </b-form-group>
          </validation-provider>

          <!-- User Role -->
          <validation-provider
            #default="validationContext"
            name="Role"
            rules="required"
          >
            <b-form-group
              label="Role"
              label-for="user-role"
              :state="getValidationState(validationContext)"
            >
              <v-select
                v-model="userData.role"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="roles"
                :reduce="val => val.id"
                :clearable="false"
                input-id="user-role"
              />
              <b-form-invalid-feedback :state="getValidationState(validationContext)">
                {{ validationContext.errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>
          <!-- User Specialization -->
          <validation-provider
            #default="validationContext"
            name="Specialization"
            rules="required"
          >
            <b-form-group
              label="Specialization"
              label-for="specialization"
              :state="getValidationState(validationContext)"
            >
              <v-select
                v-model="userData.specialization"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="specializations"
                :clearable="false"
                input-id="specialization"
              />
              <b-form-invalid-feedback :state="getValidationState(validationContext)">
                {{ validationContext.errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>
          <!-- User Authorization -->
          <validation-provider
            #default="validationContext"
            name="Authorization"
            rules="required"
          >
            <b-form-group
              label="Authorization"
              label-for="authorization"
              :state="getValidationState(validationContext)"
            >
              <v-select
                v-model="userData.authorization"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="authorizations"
                :clearable="false"
                input-id="authorization"
              />
              <b-form-invalid-feedback :state="getValidationState(validationContext)">
                {{ validationContext.errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>
          <!-- User Specialized In -->
           <validation-provider
            #default="validationContext"
            name="Specialized In"
            rules="required"
          >
            <b-form-group
              label="Specialized In"
              label-for="specialized_in"
              :state="getValidationState(validationContext)"
            >
              <b-form-input
                id="specialized_in"
                v-model="userData.specialized_in"
                :state="getValidationState(validationContext)"
                trim
              />

              <b-form-invalid-feedback :state="getValidationState(validationContext)">
                {{ validationContext.errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>
          <!-- Form Actions -->
          <div class="d-flex mt-2">
            <b-button
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              variant="primary"
              class="mr-2"
              type="submit"
            >
              Save Details
            </b-button>
            <b-button
              v-ripple.400="'rgba(186, 191, 199, 0.15)'"
              type="button"
              variant="outline-secondary"
              @click="hide"
            >
              Cancel
            </b-button>
          </div>

        </b-form>
      </validation-observer>
    </template>
  </b-sidebar>
</template>

<script>
import {
  BSidebar, BForm, BFormGroup, BFormInput, BFormInvalidFeedback, BButton,
} from 'bootstrap-vue'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { ref } from '@vue/composition-api'
import { required, alphaNum, email } from '@validations'
import formValidation from '@core/comp-functions/forms/form-validation'
import Ripple from 'vue-ripple-directive'
import vSelect from 'vue-select'
import countries from '@/@fake-db/data/other/countries'
import store from '@/store'


import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default {
  components: {
    BSidebar,
    BForm,
    BFormGroup,
    BFormInput,
    BFormInvalidFeedback,
    BButton,
    vSelect,

    // Form Validation
    ValidationProvider,
    ValidationObserver,
  },
  directives: {
    Ripple,
  },
  model: {
    prop: 'isAddNewUserSidebarActive',
    event: 'update:is-add-new-user-sidebar-active',
  },
  props: {
    isAddNewUserSidebarActive: {
      type: Boolean,
      required: true,
    },
    user: {
      type: Object,
      default: {},
    }
  },
  data() {
    return {
      required,
      alphaNum,
      email,
      countries,
      specializations: ['Doctor','Health Care Workers', 'Others'],
      authorizations: ['In House','External'],
    }
  },
  setup(props, { emit }) {
    const blankUserData = {
      name: '',
      email: '',
      role: null,
      phone: '',
      first_name: '',
      last_name:'',
      specialization: '',
      authorization: 'In House',
      specialized_in: ''
    }

    const userData = ref(JSON.parse(JSON.stringify(blankUserData)))
    const resetuserData = () => {
      userData.value = JSON.parse(JSON.stringify(blankUserData))
    }


    const roles = [];

    const {
      refFormObserver,
      getValidationState,
      resetForm,
    } = formValidation(resetuserData)

    return {
      userData,
      roles,

      refFormObserver,
      getValidationState,
      resetForm,
    }
  },
  watch:{
    isAddNewUserSidebarActive: function(val) {
      if( this.user.id  ) {
        this.userData = Object.assign({}, this.user);
        this.userData.role = this.user.role_id
      }
    }

  },
  created() {
    this.getRoles();

  },
  methods: {
    async getRoles() {
      try {
        const {data} = await axios.get('/roles/list');
        this.roles = data.roles;
        console.log(this.roles);
      } catch( err ) {
        console.log(err)
      }
    },

    async onSubmit() {
      try {
        let data;
        if( this.user.id ) {
          data = await axios.put(`/consultants/${this.userData.id}`, this.userData);
        }else {
          data = await axios.post('/consultants', this.userData);
        }
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.data.message,
            icon: 'EditIcon',
            variant: data.data.success ? 'success' : 'info',
          },
        });
        this.$emit('refetch-data')
        this.$emit('update:is-add-new-user-sidebar-active', false)
      } catch( err ) {
        console.log(err)
      }
    }
  }
}
</script>

<style lang="scss">
@import '@core/scss/vue/libs/vue-select.scss';

#add-new-user-sidebar {
  .vs__dropdown-menu {
    max-height: 200px !important;
  }
}
</style>
