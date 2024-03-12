<template>
  <b-card>
    <!-- BODY -->
    <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
      <!-- Form -->
      <b-form
        class="p-2"
        @submit.prevent="handleSubmit(onSubmit)"
        @reset.prevent="resetForm"
      >
        <b-row>
          <b-col sm="12" class="d-flex justify-content-end mb-2">
            <b-button variant="primary" @click="addLine">
              <span class="text-nowrap">New Line</span>
            </b-button>
          </b-col>
          <b-col sm="6" class="mb-2" v-if="!id && !prescriptionId">
            <div class="form-group-compose text-center compose-btn">
              <v-select
                v-model="client"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="clients"
                :reduce="(val) => val.id"
                :clearable="true"
                input-id="clients"
                placeholder="Select Client"
              />
            </div>
          </b-col>
          <b-col sm="6" class="mb-2">
            <div class="form-group-compose text-center compose-btn">
              <validation-provider
                #default="validationContext"
                name="Instructions"
                rules="required"
              >
                <b-form-textarea
                  id="instructions"
                  v-model="instructions"
                  :state="getValidationState(validationContext)"
                  placeholder=" instructions for prescription"
                  :readonly="readonly"
                  :class="errors.instructions ? 'is-invalid' : ''"
                  :area-invalid="errors.instructions ? true : false"
                />
                <b-form-invalid-feedback v-if="errors.instructions">
                  {{ errors.instructions[0] }}
                </b-form-invalid-feedback>
                <b-form-invalid-feedback>
                  {{ validationContext.errors[0] }}
                </b-form-invalid-feedback>
              </validation-provider>
            </div>
          </b-col>
          <b-col sm="12">
            <div>
              <table class="table table-stripped table-hover table-bordered">
                <thead>
                  <tr>
                    <th>Drug Name</th>
                    <th>Dosage</th>
                    <th style="width: 8%">Frequeny</th>
                    <th>Days</th>
                    <th style="width: 15%">Food Related</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(prescription, index) in prescriptions" :key="index">
                    <td>
                      <validation-provider
                        #default="validationContext"
                        name="Drug Name"
                        rules="required"
                      >
                        <b-form-input
                          id="name"
                          v-model="prescriptions[index].drug"
                          :state="getValidationState(validationContext)"
                          :readonly="readonly"
                          placeholder="Drug Name"
                          :class="errors.drug ? 'is-invalid' : ''"
                          :area-invalid="errors.drug ? true : false"
                        />
                        <b-form-invalid-feedback v-if="errors.drug">
                          {{ errors.drug[0] }}
                        </b-form-invalid-feedback>
                        <b-form-invalid-feedback>
                          {{ validationContext.errors[0] }}
                        </b-form-invalid-feedback>
                      </validation-provider>
                    </td>
                    <td>
                      <validation-provider
                        #default="validationContext"
                        name="Dosage"
                        rules="required"
                      >
                        <b-form-input
                          id="dosage"
                          v-model="prescriptions[index].dosage"
                          :state="getValidationState(validationContext)"
                          :readonly="readonly"
                          :class="errors.dosage ? 'is-invalid' : ''"
                          :area-invalid="errors.dosage ? true : false"
                        />
                        <b-form-invalid-feedback v-if="errors.dosage">
                          {{ errors.dosage[0] }}
                        </b-form-invalid-feedback>
                        <b-form-invalid-feedback>
                          {{ validationContext.errors[0] }}
                        </b-form-invalid-feedback>
                      </validation-provider>
                    </td>
                    <td>
                      <validation-provider
                        #default="validationContext"
                        name="Timing"
                        rules="required"
                      >
                        <b-form-checkbox-group
                          v-model="prescriptions[index].timing"
                          :options="timings"
                          class="demo-inline-spacing"
                          :name="'timing'.index"
                          :id="'timing'.index"
                        />
                        <b-form-invalid-feedback
                          :state="getValidationState(validationContext)"
                        >
                          {{ validationContext.errors[0] }}
                        </b-form-invalid-feedback>
                      </validation-provider>
                    </td>
                    <td>
                      <validation-provider
                        #default="validationContext"
                        name="Days"
                        rules="required"
                      >
                        <b-form-input
                          id="days"
                          v-model="prescriptions[index].days"
                          type="number"
                          :state="getValidationState(validationContext)"
                          :class="errors.days ? 'is-invalid' : ''"
                          :area-invalid="errors.days ? true : false"
                        />
                        <b-form-invalid-feedback v-if="errors.days">
                          {{ errors.days[0] }}
                        </b-form-invalid-feedback>
                        <b-form-invalid-feedback>
                          {{ validationContext.errors[0] }}
                        </b-form-invalid-feedback>
                      </validation-provider>
                    </td>
                    <td>
                      <validation-provider
                        #default="validationContext"
                        name="Stomac"
                        rules="required"
                      >
                        <b-form-radio-group
                          v-model="prescriptions[index].stomach"
                          :options="['Empty Stomach', 'Before Food', 'After Food']"
                          class="demo-inline-spacing"
                          :name="'stomac'.index"
                          :id="'stomac'.index"
                        />
                        <b-form-invalid-feedback
                          :state="getValidationState(validationContext)"
                        >
                          {{ validationContext.errors[0] }}
                        </b-form-invalid-feedback>
                      </validation-provider>
                    </td>
                    <td>
                      <feather-icon
                        v-if="index > 0"
                        icon="TrashIcon"
                        @click="removeLine(index)"
                      />
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </b-col>
        </b-row>
        <div class="d-flex" style="margin-top: 5%">
          <b-button
            v-ripple.400="'rgba(255, 255, 255, 0.15)'"
            variant="primary"
            class="mr-2"
            type="submit"
          >
            Save prescription
          </b-button>
          <b-button
            v-ripple.400="'rgba(186, 191, 199, 0.15)'"
            type="button"
            variant="outline-secondary"
            @click="$router.back()"
          >
            Cancel
          </b-button>
        </div>
      </b-form>
    </validation-observer>
  </b-card>
</template>

<script>
import vSelect from "vue-select";

import {
  BCollapse,
  BCard,
  BCardText,
  BForm,
  BFormGroup,
  BFormInput,
  BFormTextarea,
  BFormInvalidFeedback,
  BButton,
  BRow,
  BCol,
  BFormCheckboxGroup,
  BFormRadioGroup,
  BOverlay,
} from "bootstrap-vue";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { required } from "@validations";
import { ref } from "@vue/composition-api";
import Ripple from "vue-ripple-directive";
import formValidation from "@core/comp-functions/forms/form-validation";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import { VBToggle } from "bootstrap-vue";
import AppCollapse from "@core/components/app-collapse/AppCollapse.vue";
import AppCollapseItem from "@core/components/app-collapse/AppCollapseItem.vue";

export default {
  components: {
    BCard,
    BCardText,
    BForm,
    BFormGroup,
    BFormInput,
    BFormTextarea,
    BFormInvalidFeedback,
    BButton,
    ValidationProvider,
    ValidationObserver,
    BRow,
    BCol,
    BFormCheckboxGroup,
    BFormRadioGroup,
    BOverlay,
    BCollapse,
    AppCollapse,
    AppCollapseItem,
    vSelect,
  },
  directives: {
    Ripple,
    "b-toggle": VBToggle,
  },
  props: {
    id: {
      type: [Number, String],
      default: null,
    },
    prescriptionId: {
      type: [Number, String],
      default: null,
    },
  },
  created() {
    if (!this.id) {
      this.getClients();
    }
    if (this.prescriptionId) {
      this.getPrescription();
    }
  },
  data() {
    return {
      userData: {},
      errors: [],
      required,
      readonly: false,
      clients: [],
      client: null,
      timings: [
        { text: "Morning", value: "Morning" },
        { text: "Afternoon", value: "Afternoon" },
        { text: "Night", value: "Night" },
      ],
    };
  },
  setup() {
    const investigation = [
      {
        drug: null,
        timing: [],
        stomach: null,
        days: null,
        dosage: null,
      },
    ];
    const prescriptions = ref(JSON.parse(JSON.stringify(investigation)));

    const resetInvestigationData = () => {
      prescriptions.value = JSON.parse(JSON.stringify(investigation));
    };

    const { refFormObserver, getValidationState, resetForm } = formValidation(
      resetInvestigationData
    );

    return {
      prescriptions,
      refFormObserver,
      getValidationState,
      resetForm,
    };
  },

  methods: {
    addLine() {
      this.prescriptions.push({
        drug: "",
        timing: [],
        stomach: "",
        days: null,
        dosage: null,
      });
    },
    removeLine(index) {
      this.prescriptions.splice(index);
    },
    async getPrescription() {
      try {
        const { data } = await axios.get(`prescription/${this.prescriptionId}`);
        this.prescriptions = data.prescription.data;
        this.instructions = data.prescription.instructions;
      } catch (error) {
        cnsole.log(error);
      }
    },
    async onSubmit() {
      let data;
      try {
        let prescriptionData = {
          client: this.id ? this.id : this.client,
          prescriptions: this.prescriptions,
          instructions: this.instructions,
        };
        if (this.prescriptionId) {
          data = await axios.put(`prescription/${this.prescriptionId}`, prescriptionData);
        } else {
          data = await axios.post(`prescription`, prescriptionData);
        }
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.data.message,
            icon: "EditIcon",
            variant: data.data.success ? "success" : "info",
          },
        });
        this.$router.back();
      } catch (error) {
        console.log(error);
      }
    },

    async getClients() {
      try {
        const { data } = await axios.get("consultant/client/list");
        this.clients = data.clients;
      } catch (err) {
        console.log(err);
      }
    },
  },
};
</script>
<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";
</style>
<style scoped>
td {
  padding-left: 5px !important;
  padding-right: 5px !important;
}
</style>
