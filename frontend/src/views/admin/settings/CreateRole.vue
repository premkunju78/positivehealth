<template>
  <b-card>
    <b-overlay :show="$store.state.app.shallShowOverlay" no-wrap opacity="0.5" blur="1px"></b-overlay>

    <!-- BODY -->
    <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
      <!-- Form -->
      <b-form class="p-2" @submit.prevent="handleSubmit(onSubmit)" @reset.prevent="resetForm">
        <b-row>
          <b-col>
            <!--  Name -->
            <validation-provider #default="validationContext" name="Name" rules="required">
              <b-form-group label="Name" label-for="name">
                <b-form-input id="name" v-model="roleData.name" :state="getValidationState(validationContext)" trim
                  placeholder="Admin" />

                <b-form-invalid-feedback>
                  {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>
          </b-col>
          <b-col>
            <!-- Description -->
            <validation-provider #default="validationContext" name="Description" rules="required">
              <b-form-group label="Description" label-for="description">
                <!-- <b-form-input
                    id="description"
                    v-model="roleData.description"
                    :state="getValidationState(validationContext)"
                    trim
                  /> -->

                <v-select v-model="roleData.description" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                  :options="roledescription" :clearable="false" />

                <b-form-invalid-feedback>
                  {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </b-form-group>
            </validation-provider>
          </b-col>

          <b-col>
            <color-picker v-model="roleData.color" :color="roleData.color" />
          </b-col>
        </b-row>
        <b-row>
          <b-col cols="12" md="4" v-for="(mod, key) in modules" :key="key">
            <b-card :title="(mod == 'Questionnaire') ? 'Form Builder' : mod">
              <b-form-group v-slot="{ ariaDescribedby }">
                <b-form-checkbox-group :key="update" v-model="roleData.permissions[key]"
                  :options="options[mod.toLowerCase()]" :aria-describedby="ariaDescribedby"
                  stacked></b-form-checkbox-group>
              </b-form-group>
            </b-card>
          </b-col>
        </b-row>
        <div class="d-flex mt-2">
          <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" class="mr-2" type="submit">
            Save Details
          </b-button>
          <router-link :to="{ name: 'settings-role-list' }">
            <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" type="button" variant="outline-secondary">
              Cancel
            </b-button>
          </router-link>
        </div>
      </b-form>
    </validation-observer>
  </b-card>
</template>

<script>
import {
  BCard,
  BCardText,
  BForm,
  BFormGroup,
  BFormInput,
  BFormInvalidFeedback,
  BButton,
  BRow,
  BCol,
  BFormCheckboxGroup,
  BOverlay,
} from "bootstrap-vue";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { required } from "@validations";
import { ref } from "@vue/composition-api";
import Ripple from "vue-ripple-directive";
import formValidation from "@core/comp-functions/forms/form-validation";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import store from "@/store";
import colorpicker from "../../components/colorpicker.vue";
import vSelect from "vue-select";

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
    "color-picker": colorpicker,
    vSelect,
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      required,
      options: {},
      update: 0,
      colors: "#000000",
      roledescription: ["Medical", "Wellness Provider", "Others"],
    };
  },
  props: {
    id: {
      type: [Number, String],
      default: null,
    },
  },
  setup(props, { emit }) {
    const blankRoleData = {
      name: "",
      description: "",
      permissions: [],
      color: "#000000",
    };
    const modules = [
      "Users",
      "Clients",
      "Alliance-Partners",
      "Coordinators",
      "Healthcoaches",
      "MarketingHead",
      "DiagnosticHead",
      "SampleCollector",
      "DataentryUser",
      "MarketingExecutive",
      "Roles",
      "Video",
      "Recipes",
      "Reminders",
      "Diet-Plan",
      "Questionnaire",
      "Appointments",
      "Workflows",
      "Case-Sheet",
      "Program",
      "Investigation",
      "Prescription",
      "Accounts",
      "Leads",
      "Accounts",
      "Discount-Coupons",
      "Enquiries"
    ];

    const permissions = [];
    const roleData = ref(JSON.parse(JSON.stringify(blankRoleData)));

    const resetroleData = () => {
      roleData.value = JSON.parse(JSON.stringify(blankRoleData));
    };

    const { refFormObserver, getValidationState, resetForm } = formValidation(
      blankRoleData
    );

    return {
      roleData,
      modules,
      refFormObserver,
      getValidationState,
      resetForm,
      permissions,
    };
  },

  created() {
    this.getPermissions();
    if (this.id) {
      this.getRoleData();
    }
  },

  methods: {
    updateValue() { },
    async onSubmit() {
      if (this.id) {
        await axios.put(`/roles/${this.id}`, this.roleData).then(({ data }) => {
          if (data.success) {
            this.$toast({
              component: ToastificationContent,
              props: {
                title: data.message,
                icon: "EditIcon",
                variant: data.success ? "success" : "info",
              },
            });
          }
        });
      } else {
        await axios.post(`/roles`, this.roleData).then(({ data }) => {
          if (data.success) {
            this.$toast({
              component: ToastificationContent,
              props: {
                title: data.message,
                icon: "EditIcon",
                variant: data.success ? "success" : "info",
              },
            });
          }
        });
      }
      this.$router.replace("/settings/role/list");
    },
    async getRoleData() {
      try {
        const { data } = await axios.get(`/roles/${this.id}/edit`);
        this.roleData = data.data;
      } catch (error) {
        console.log(error);
      }
    },
    async getPermissions() {
      try {
        this.fetchPermissions().then(({ data }) => {
          this.permissions = data.permissions;
          this.modules = data.modules;
          Object.keys(this.permissions).map((key) => {
            this.modules.forEach((element) => {
              if (this.permissions[key].includes(element.toLowerCase())) {
                if (!this.options[element.toLowerCase()]) {
                  this.options[element.toLowerCase()] = [];
                }
                this.options[element.toLowerCase()].push({
                  text: this.permissions[key],
                  value: key,
                });
              }
            });
          });
          this.update++;
        });
      } catch (error) {
        console.log(error);
      }
    },

    async fetchPermissions() {
      return await axios.get(`/permissions`);
    },
  },
};
</script>


<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";
</style>
