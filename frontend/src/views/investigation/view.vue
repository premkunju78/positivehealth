<template>
  <section class="invoice-preview-wrapper">
    <!-- Alert: No item found -->
    <b-alert variant="danger" :show="invoiceData === undefined">
      <h4 class="alert-heading">Error fetching investigation data</h4>
    </b-alert>

    <b-row v-if="invoiceData" class="invoice-preview">
      <!-- Col: Left (Invoice Container) -->
      <b-col cols="10" xl="9" md="8">
        <b-card no-body class="invoice-preview-card">
          <!-- Header -->
          <b-card-body class="invoice-padding pb-0">
            <div
              class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0"
            >
              <!-- Header: Left Content -->
              <div>
                <div class="logo-wrapper">
                  <b-img
                    v-if="invoiceData.logo"
                    :src="'/view/file?path=' + invoiceData.logo"
                    fluid
                    :width="100"
                    :height="100"
                    class="m1"
                  ></b-img>
                  <div v-else>
                    <logo />
                    <h3 class="text-primary invoice-logo">Prajana</h3>
                  </div>
                </div>
                <p class="card-text mb-25"></p>
                <p class="card-text mb-25"></p>
                <p class="card-text mb-0"></p>
              </div>

              <!-- Header: Right Content -->
              <div class="mt-md-0 mt-2">
                Referred by <b>{{ invoiceData.consultant }}</b>
                <!-- <div class="invoice-date-wrapper">
                  <p class="invoice-date-title">Phone Number: {{ invoiceData.phone }}</p>
                </div> -->
              </div>
            </div>
          </b-card-body>

          <!-- Spacer -->
          <hr class="invoice-spacing" />

          <!-- Invoice Client & Payment Details -->
          <b-card-body class="invoice-padding pt-0">
            <b-row class="invoice-spacing">
              <!-- Col: Invoice To -->
              <b-col cols="11" xl="6" class="p-0">
                <h6 class="ml-1 mb-2">Client:</h6>
                <h6 class="ml-1 mb-25">
                  {{ invoiceData.patient_name }}
                </h6>
                <p class="ml-1 card-text mb-25" v-if="invoiceData.dob">
                  Age.{{ age(invoiceData.dob) }}
                </p>
                <!-- <p class="ml-1 card-text mb-25">
                  Mobile No.{{ invoiceData.patient_phone }}
                </p>

                <p class="ml-1 card-text mb-25" style="opacity:0.6">
                  {{ invoiceData.patient_email }}
                </p> -->
              </b-col>

              <!-- Col: Payment Details -->
              <b-col
                xl="6"
                cols="12"
                class="p-0 mt-xl-0 mt-2 d-flex justify-content-xl-end"
              >
                <div>
                  <h6 class="mr-1 mb-0 text-info">
                    Investigation No : {{ invoiceData.no }}
                  </h6>
                  <p>Date: {{ moment(invoiceData.created_at).format("DD-MM-YYYY") }}</p>
                </div>
              </b-col>
            </b-row>
          </b-card-body>
          <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
            <form
              class="p-2"
              @submit.prevent="handleSubmit(onSubmit)"
              @reset.prevent="resetForm"
            >
              <!-- Invoice Description: Table -->
              <b-table-lite
                responsive
                bordered
                :items="invoiceData.tests"
                :fields="fields"
                style="padding-top: 3%"
              >
                <template #cell(index)="data">
                  <span style="color: #fff">{{ data.index + 1 }}</span>
                </template>
                <template #cell(name)="data">
                  <b-card-text class="font-weight-bold text-primary mb-25">
                    {{ data.item.name }}
                  </b-card-text>
                </template>
                <template #cell(unit)="data">
                  <validation-provider
                    #default="validationContext"
                    name="Unit"
                    rules="required"
                    v-if="
                      $store.getters.user.role.id == 10 ||
                      $store.getters.user.role.id == 11
                    "
                  >
                    <b-form-input
                      v-model="data.item.unit"
                      :state="getValidationState(validationContext)"
                      :readonly="readonly"
                      placeholder="Unit"
                      :class="errors.unit ? 'is-invalid' : ''"
                      :area-invalid="errors.unit ? true : false"
                    />
                    <b-form-invalid-feedback v-if="errors.unit">
                      {{ errors.unit[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </validation-provider>
                  <span v-else>{{ data.item.unit ? data.item.unit : "-" }}</span>
                </template>
                <template #cell(result)="data">
                  <validation-provider
                    #default="validationContext"
                    name="Result"
                    rules="required"
                    v-if="
                      $store.getters.user.role.id == 10 ||
                      $store.getters.user.role.id == 11
                    "
                  >
                    <b-form-input
                      v-model="data.item.result"
                      :state="getValidationState(validationContext)"
                      :readonly="readonly"
                      placeholder="Result"
                      :class="errors.result ? 'is-invalid' : ''"
                      :area-invalid="errors.result ? true : false"
                    />
                    <b-form-invalid-feedback v-if="errors.result">
                      {{ errors.result[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </validation-provider>
                  <span v-else>{{ data.item.result ? data.item.result : "-" }}</span>
                </template>
                <template #cell(ref_range)="data">
                  <validation-provider
                    #default="validationContext"
                    name="Ref Range Min"
                    rules="required"
                    v-if="
                      $store.getters.user.role.id == 10 ||
                      $store.getters.user.role.id == 11
                    "
                  >
                    <b-form-input
                      v-model="data.item.ref_range_min"
                      :state="getValidationState(validationContext)"
                      :readonly="readonly"
                      placeholder="Min"
                      :class="errors.ref_range_min ? 'is-invalid' : ''"
                      :area-invalid="errors.ref_range_min ? true : false"
                      style="width: 48%; float: left; margin-right: 10px"
                    />
                    <b-form-invalid-feedback v-if="errors.ref_range_min">
                      {{ errors.ref_range_min[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </validation-provider>
                  <p v-else>
                    Min:
                    {{ data.item.ref_range_min != null ? data.item.ref_range_min : "-" }}
                  </p>
                  <validation-provider
                    #default="validationContext"
                    name="Ref Range Max"
                    rules="required"
                    v-if="
                      $store.getters.user.role.id == 10 ||
                      $store.getters.user.role.id == 11
                    "
                  >
                    <b-form-input
                      v-model="data.item.ref_range_max"
                      :state="getValidationState(validationContext)"
                      :readonly="readonly"
                      placeholder="Max"
                      :class="errors.ref_range_max ? 'is-invalid' : ''"
                      :area-invalid="errors.ref_range_max ? true : false"
                      style="width: 48%; float: left"
                    />
                    <b-form-invalid-feedback v-if="errors.ref_range_max">
                      {{ errors.ref_range_max[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </validation-provider>
                  <p v-else>
                    Max:
                    {{ data.item.ref_range_max != null ? data.item.ref_range_max : "-" }}
                  </p>
                </template>
              </b-table-lite>
              <div
                class="mb-4 mt-4 text-right"
                v-if="
                  $store.getters.user.role.id == 10 || $store.getters.user.role.id == 11
                "
              >
                <!-- Action Buttons -->
                <b-button variant="primary" type="submit" class="mr-2">
                  Save Changes
                </b-button>
              </div>
            </form>
          </validation-observer>
          <!-- Spacer -->
          <hr class="invoice-spacing" />

          <!-- Note -->
          <b-card-body class="invoice-padding pt-0 mt-2">
            <div
              class="d-flex justify-content-end flex-column mt-0"
              style="text-align: right"
            >
              <span v-if="invoiceData.signature_img">
                <b-img
                  :src="'/view/file?path=' + invoiceData.signature_img"
                  fluid
                  :width="75"
                  :height="75"
                  class="m1"
                >
                </b-img>
              </span>
              <small class="font-weight-bold">Signatures</small>
            </div>
          </b-card-body>
        </b-card>
      </b-col>
    </b-row>
  </section>
</template>

<script>
import { ref, onUnmounted } from "@vue/composition-api";
import store from "@/store";
import router from "@/router";
import moment from 'moment';

import {
  BRow,
  BCol,
  BCard,
  BCardBody,
  BTableLite,
  BCardText,
  BButton,
  BAlert,
  BLink,
  VBToggle,
  BImg,
  BFormGroup,
  BFormInput,
} from "bootstrap-vue";
import Logo from "@core/layouts/components/Logo.vue";
import Ripple from "vue-ripple-directive";
import invoiceStoreModule from "./invoiceStoreModule";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { required } from "@validations";
import formValidation from "@core/comp-functions/forms/form-validation";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

export default {
  directives: {
    Ripple,
    "b-toggle": VBToggle,
  },
  components: {
    BRow,
    BCol,
    BCard,
    BCardBody,
    BTableLite,
    BCardText,
    BButton,
    BAlert,
    BLink,
    BImg,

    Logo,
    BFormGroup,
    BFormInput,
    ValidationProvider,
    ValidationObserver,
  },
  data() {
    return {
      errors: [],
      required,
      readonly: false,
      investigationDetail: null,
      moment: moment
    };
  },
  setup() {
    const invoiceData = ref(null);
    const paymentDetails = ref({});
    const fields = [
      { key: "index", label: "#", sortable: true, tdClass: "width40" },
      { key: "name", label: "Investigation Advice", sortable: false },
    ];



    // Invoice Description
    // ? Your real data will contain this information
    const invoiceDescription = [
      {
        taskTitle: "Native App Development",
        taskDescription:
          "Developed a full stack native app using React Native, Bootstrap & Python",
        rate: "$60.00",
        hours: "30",
        total: "$1,800.00",
      },
      {
        taskTitle: "UI Kit Design",
        taskDescription:
          "Designed a UI kit for native app using Sketch, Figma & Adobe XD",
        rate: "$60.00",
        hours: "20",
        total: "$1200.00",
      },
    ];

    const INVOICE_APP_STORE_MODULE_NAME = "preview";

    // Register module
    if (!store.hasModule(INVOICE_APP_STORE_MODULE_NAME))
      store.registerModule(INVOICE_APP_STORE_MODULE_NAME, invoiceStoreModule);

    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(INVOICE_APP_STORE_MODULE_NAME))
        store.unregisterModule(INVOICE_APP_STORE_MODULE_NAME);
    });

    store
      .dispatch("preview/fetchInvestigation", { id: router.currentRoute.params.id })
      .then((response) => {
        invoiceData.value = response.data.investigation;
        this.investigationDetail = response.data.investigation;
      })
      .catch((error) => {
        if (error.response.status === 404) {
          invoiceData.value = undefined;
        }
      });

    const printInvoice = () => {
      window.print();
    };

    const { refFormObserver, getValidationState, resetForm } = formValidation();

    return {
      invoiceData,
      fields,
      paymentDetails,
      invoiceDescription,
      printInvoice,
      refFormObserver,
      getValidationState,
      resetForm,
    };
  },
  created() {
    this.getFields();
  },
  methods: {
     age: (dob) => {
      if (dob != '' && dob != null) {
        var diff = (new Date().getTime() - new Date(dob).getTime()) / 1000;
        diff /= (60 * 60 * 24);
        return Math.abs(Math.round(diff / 365.25));
      }
    },
    getFields() {
      if (this.$store.getters.user.role.id == '10' || this.$store.getters.user.role.id == '11') {
        this.fields = [
          { key: "index", label: "#", sortable: true, tdClass: "width40" },
          { key: "name", label: "Investigation Advice", sortable: false },
          { key: "result", label: "Result", sortable: false },
          { key: "unit", label: "Unit", sortable: false },
          { key: "ref_range", label: "Ref Range", sortable: false },
        ];
      }

    },
    async onSubmit() {
      try {
        let data;
        data = await axios.post(`/investigation/updateTestData`, {
          id:this.invoiceData.id,
          tests: this.invoiceData.tests,
        });
        // this.resetFormCard();
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.data.message,
            icon: "BellIcon",
            variant: data.data.success ? "success" : "danger",
          },
        });

        this.$router.back();
      } catch (error) {
        if (error.response) {
          this.errors = error.response.data.errors;
        } else {
          console.log(error);
        }
      }
    },
  },
};
</script>

<style lang="scss">
.width40 {
  width: 10px !important;
  text-align: center;
  background-color: #7566ed;
}

[dir] .table th,
[dir] .table td {
  padding: 0.72rem 1rem !important;
}
</style>
