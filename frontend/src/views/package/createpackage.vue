<template>
  <div style="min-height: 300px">
    <b-overlay :show="$store.state.app.shallShowOverlay" no-wrap opacity="0.5" blur="1px"></b-overlay>
    <b-card :title="type" v-permission="['create-program']">
      <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
        <form ref="form" @submit.prevent="handleSubmit(onSubmit)">
          <b-row>
            <b-col cols="12" md="4">
              <!-- User Specialization -->
              <validation-provider #default="validationContext" name="Program Type" rules="required">
                <b-form-group label="Program Type" label-for="type" :state="getValidationState(validationContext)">
                  <v-select v-model="packageData.type" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                  :options="['Wellness', 'Women Care', 'Lifeskill', 'Workshop']" :clearable="false" input-id="type" />
                  <b-form-invalid-feedback v-if="errors.type">
                    {{ errors.type[0] }}
                  </b-form-invalid-feedback>
                  <b-form-invalid-feedback :state="getValidationState(validationContext)" v-else>
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col cols="12" md="4">
              <validation-provider #default="validationContext" name="Category" rules="required">
                <b-form-group label="Category" label-for="category">
                  <b-form-input id="category" :state="getValidationState(validationContext)"
                    v-model="packageData.category" :readonly="readonly" :class="errors.category ? 'is-invalid' : ''"
                    :area-invalid="errors.category ? true : false" />
                    <!-- <v-select v-model="packageData.category"
                                        :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                        :options="['Diet Program', 'Yoga / Fitness Program', 'Counselling Program', 'Arika Program', 'Umika Program']"
                                        :clearable="false" input-id="category" /> -->
                  <b-form-invalid-feedback v-if="errors.category">
                    {{ errors.category[0] }}
                  </b-form-invalid-feedback>
                  <b-form-invalid-feedback v-else>
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col cols="12" md="4">
              <validation-provider #default="validationContext" name="Name of the Program" rules="required">
                <b-form-group label="Name of the Program" label-for="Name of the Program">
                  <b-form-input id="name-input" :state="getValidationState(validationContext)" v-model="packageData.title"
                    :readonly="readonly" :class="errors.title ? 'is-invalid' : ''"
                    :area-invalid="errors.title ? true : false" />
                  <b-form-invalid-feedback v-if="errors.title">
                    {{ errors.title[0] }}
                  </b-form-invalid-feedback>
                  <b-form-invalid-feedback v-else>
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col cols="12" md="4">
              <validation-provider #default="validationContext" name="Payment Type" rules="required">
                <b-form-group label="Payment Type" label-for="payment_type"
                  :state="getValidationState(validationContext)">
                  <v-select v-model="packageData.payment_type" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="['Paid', 'Free']" :clearable="false" input-id="payment_type" />
                  <b-form-invalid-feedback v-if="errors.payment_type">
                    {{ errors.payment_type[0] }}
                  </b-form-invalid-feedback>
                  <b-form-invalid-feedback :state="getValidationState(validationContext)" v-else>
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col cols="12" md="4">
              <validation-provider #default="validationContext" name="Communication Type" rules="required">
                <b-form-group label="Communication Type" label-for="comunication_type"
                  :state="getValidationState(validationContext)">
                  <v-select v-model="packageData.communication_type" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="['Audio Call', 'Video Call']" :clearable="false" input-id="comunication_type" />
                  <b-form-invalid-feedback v-if="errors.communication_type">
                    {{ errors.comunication_type[0] }}
                  </b-form-invalid-feedback>
                  <b-form-invalid-feedback :state="getValidationState(validationContext)" v-else>
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col md="4" v-if="['LifeSkill', 'Workshop'].includes(packageData.type)">
              <validation-provider #default="validationContext" name="Start Date" rules="required">
                <b-form-group label="Start Date" label-for="Start Date">
                  <flat-pickr v-model="packageData.start_date" class="form-control"
                    :config="{ enableTime: false, dateFormat: 'd-m-Y' }" />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>

            <b-col md="4" v-if="['LifeSkill', 'Workshop'].includes(packageData.type)">
              <validation-provider #default="validationContext" name="End Date" rules="required">
                <b-form-group label="End Date" label-for="End Date">
                  <flat-pickr v-model="packageData.end_date" class="form-control"
                    :config="{ enableTime: false, dateFormat: 'd-m-Y' }" />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col sm="4">
              <!-- image -->
              <b-media no-body :class="errors.image ? 'is-invalid' : ''" :area-invalid="errors.image ? true : false">
                <b-media-aside>
                  <a :href="'/view/file?path=' + packageData.image" target="_blank">
                    <b-img ref="previewEl" rounded :src="'/view/file?path=' + packageData.image" height="80" />
                  </a>
                  <!--/ image -->
                </b-media-aside>

                <b-media-body class="mt-75 ml-75">
                  <!-- upload button -->
                  <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" size="sm" class="mb-75 mr-75"
                    @click="$refs.refInputEl.$el.click()">
                    Upload Program Thumbnail
                  </b-button>
                  <b-form-file ref="refInputEl" v-model="image" accept=".jpg, .png, .gif" :hidden="true" plain
                    @input="validateSize(image, 'image', imageRenderer)" />
                  <!--/ upload button -->

                  <!-- reset -->
                  <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" variant="outline-secondary" size="sm"
                    class="mb-75 mr-75" v-if="image" @click="$refs.previewEl.src = '/view/file?path=' + packageData.image;
                    image = null;
                                        ">
                    Reset
                  </b-button>
                  <!--/ reset -->
                  <b-card-text>Allowed JPG, GIF or PNG. Max size of 2MB</b-card-text>
                </b-media-body>
              </b-media>
              <b-form-invalid-feedback v-if=" errors.image ">
                {{ errors.image[0] }}
              </b-form-invalid-feedback>
            </b-col>
            <b-col cols="12" md="12">
              <validation-provider #default=" validationContext " name="Description" rules="required">
                <b-form-group label="Description" label-for="description">
                  <quill-editor id="description" v-model=" packageData.description " :options=" editorOption " />
                  <b-form-invalid-feedback v-if=" errors.description ">
                    {{ errors.description[0] }}
                  </b-form-invalid-feedback>
                  <b-form-invalid-feedback v-else :state=" getValidationState(validationContext) ">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
          </b-row>

          <b-button style="float: right" @click=" addRow('plans') " size="sm" variant="primary mr-1">
            <feather-icon icon="PlusIcon"></feather-icon>
            <span style="color: #fff" class="text-nowrap">Add Plan</span>
          </b-button>
          <h5 class="p-1">Plans</h5>
          <b-row>
            <b-col md="12" sm="12" v-for="( plan, index ) in  packageData.plans " :key=" index " style="
                margin-bottom: 22px;
                box-shadow: 1px 1px 10px 2px #ced1ed;
                padding: 24px;
                border-radius: 3px !important;
              ">
              <b-row>
                <b-col cols="4">
                  <b-form-group label="Plan" label-for="type">
                    <v-select v-model=" packageData.plans[index] " :dir=" $store.state.appConfig.isRTL ? 'rtl' : 'ltr' "
                      :options=" plans " :clearable=" false " input-id="type" @input=" (val) => validatePlan(val, index) " />
                  </b-form-group>
                </b-col>
                <b-col cols="4">
                  <b-form-group label="Cost" label-for="cost">
                    <b-form-input id="cost" v-model=" packageData.cost[index] " />
                  </b-form-group>
                </b-col>
                <b-col cols="2">
                  <b-form-group label="Request Call Back" label-for="request_callback">
                    <b-form-checkbox v-model=" packageData.request_callback[index] " value="Yes" unchecked-value="No">
                    </b-form-checkbox>
                    {{ packageData.request_callback[index] }}
                  </b-form-group>
                </b-col>
                <b-col cols="2" v-if=" index > 0 ">
                  <b-button style="float: right" @click=" removePlanRow(index, 'plans') " size="sm" variant="link mr-1">
                    <feather-icon class="text-danger" icon="DeleteIcon"></feather-icon>
                  </b-button>
                </b-col>
              </b-row>

              <b-button style="float: right;margin-bottom:10px" @click=" addRow('sessions', index) " size="sm"
                variant="primary mr-1">
                <feather-icon icon="PlusIcon"></feather-icon>
                <span style="color: #fff" class="text-nowrap">Add Session</span>
              </b-button>
              <h5>Allowed Sessions</h5>

              <table class="table table-striped table-bordered">
                <tr>
                  <th style="width: 45%">Session Type</th>
                  <th>Total No. of Sessions</th>
                  <th v-for=" mindex  in  packageData.months[index] " :key=" mindex ">
                    Month {{ mindex }}
                  </th>
                  <th style="width: 10%"></th>
                </tr>
                <tbody>
                  <tr v-for="( session, sindex ) in  packageData.sessions[index] " :key=" sindex ">
                    <td>
                      <v-select v-model=" session.type " :dir=" $store.state.appConfig.isRTL ? 'rtl' : 'ltr' "
                        :options=" sessions " :clearable=" false " input-id="type" />
                    </td>
                    <td>
                      <b-form-input id="name-input" type="number" :min=" 1 " v-model=" session.no_of_sessions "
                        :readonly=" readonly " :class=" errors.title ? 'is-invalid' : '' " />
                    </td>
                    <td v-for=" mindex  in  packageData.months[index] " :key=" mindex ">
                      <b-form-input id="name-input month-input" type="number" :min=" 1 "
                        v-model=" session.month_sessions[mindex] " :class=" errors.title ? 'is-invalid' : '' " />
                    </td>
                    <td v-if=" sindex > 0 ">
                      <b-button style="float: right" @click=" removeRow(sindex, index, 'sessions') " size="sm"
                        variant="link mr-1">
                        <feather-icon class="text-danger" icon="DeleteIcon"></feather-icon>
                      </b-button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </b-col>
          </b-row>

          <b-row style="margin-top: 2%">
            <!-- Action Buttons -->
            <b-button variant="primary" :block=" $store.getters['app/currentBreakPoint'] === 'xs' " type="submit"
              class="mr-2" v-permission=" ['create-program'] ">
              Save Changes
            </b-button>
            <b-button v-ripple.400=" 'rgba(186, 191, 199, 0.15)' " type="button" @click=" resetFormCard "
              variant="outline-secondary">
              Cancel
            </b-button>
          </b-row>
        </form>
      </validation-observer>
    </b-card>
  </div>
</template>
<style>
iframe {
  width: 100% !important;
  height: 200px !important;
}
</style>
<script>
import {
  BCard,
  BCardBody,
  BCardText,
  BTable,
  BRow,
  BCol,
  BPagination,
  BSpinner,
  BButton,
  BDropdown,
  BDropdownItem,
  VBModal,
  BModal,
  BFormInvalidFeedback,
  BFormInput,
  BFormTextarea,
  BFormGroup,
  BOverlay,
  BFormCheckbox,
  BFormDatepicker,
  BMedia,
  BMediaAside,
  BMediaBody,
  BFormFile,
  BLink,
  BImg,
} from "bootstrap-vue";
import { ValidationProvider, ValidationObserver } from "vee-validate";

import formValidation from "@core/comp-functions/forms/form-validation";
import { required } from "@validations";

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

import vSelect from "vue-select";

import Ripple from "vue-ripple-directive";
import "quill/dist/quill.core.css";
import "quill/dist/quill.snow.css";
import "quill/dist/quill.bubble.css";
import { quillEditor } from "vue-quill-editor";

import { useInputImageRenderer } from "@core/comp-functions/forms/form-utils";

import { validateSize } from "@core/utils/utils";
import flatPickr from "vue-flatpickr-component";

import { ref } from "@vue/composition-api";
import FeatherIcon from "../../@core/components/feather-icon/FeatherIcon.vue";

export default {
  components: {
    BCard,
    BCardBody,
    BCardText,
    BTable,
    BRow,
    BCol,
    BPagination,
    BSpinner,
    vSelect,
    BButton,
    BDropdown,
    BDropdownItem,
    VBModal,
    BModal,
    ValidationProvider,
    ValidationObserver,
    BFormInvalidFeedback,
    BFormInput,
    BFormGroup,
    BFormTextarea,
    BOverlay,
    BFormCheckbox,
    BFormDatepicker,
    quillEditor,
    BMedia,
    BMediaAside,
    BMediaBody,
    BFormFile,
    BLink,
    BImg,
    FeatherIcon,
    flatPickr,
  },
  data() {
    var toolbarOptions = [
      ["bold", "italic", "underline", "strike"], // toggled buttons
      [{ list: "ordered" }, { list: "bullet" }],
      [{ indent: "-1" }, { indent: "+1" }], // outdent/indent
      [{ direction: "rtl" }], // text direction
      [{ header: [1, 2, 3, 4, 5, 6, false] }],
      [{ color: [] }], // dropdown with defaults from theme
      [{ font: [] }],
      [{ align: [] }], // remove formatting button
    ];
    return {
      editorOption: {
        modules: {
          toolbar: toolbarOptions,
        },
        theme: "snow",
      },
      pagination: {
        page: 1,
        per_page: 10,
        total: null,
      },
      plan: "",
      list: [],
      package: null,
      required,
      type: "Create Program",
      groupPackages: [],
      readonly: false,
      errors: [],
      pkg: null,
      mappingData: {
        partners: [],
        cost: {},
        request_callback: {},
        plans: [],
      },
      totalRows: 0,
      partners: [],
      key: 0,
      image: null,
      showRange: false,
      plans: [
        "21 Days",
        "1 Month",
        "2 Months",
        "3 Months",
        "6 Months",
        "9 Months",
        "12 Months",
        "1 Session",
        "2 Sessions",
        "3 Sessions",
        "4 Sessions",
        "5 Sessions",
        "6 Sessions",
        "7 Sessions",
        "8 Sessions",
        "9 Sessions",
        "10 Sessions",
        "11 Sessions",
        "12 Sessions",
        "60 Minutes",
        "90 Minutes",
        "120 Minutes",
      ],
      sessions: [
        "Diet Session",
        "Yoga Session",
        "HITT Sessions",
        "Doctor Consultation",
        "Counseling",
        "Training",
        "Workshops",
      ],
    };
  },

  directives: {
    Ripple,
  },
  setup() {
    const packages = [];
    const loading = true;
    const sortBy = "1";
    const isSortDirDesc = false;
    const columns = [
      { key: "index", label: "Sr. No.", sortable: true },
      { key: "title", label: "Title", sortable: true },
      { key: "category", label: "Category", sortable: true },
      { key: "type", label: "Type", sortable: true },
      { key: "description", label: "Description", sortable: true },
      { key: "actions" },
    ];
    const ucolumns = [
      { key: "index", label: "Sr. No.", sortable: true },
      { key: "name", label: "Name", sortable: true },
      { key: "email", label: "Email", sortable: true },
      { key: "phone", label: "Contact Number", sortable: true },
      { key: "cost", label: "Program Cost", sortable: true },
      { key: "status", label: "Status", sortable: false },
      { key: "enrolled", label: "Enrolled", sortable: true },
      { key: "actions", label: "Action", sortable: false },
    ];
    const packageData = ref({
      title: "",
      description: "",
      category: "",
      id: null,
      duration: "Free Session",
      image: null,
      payment_type: "Free",
      communication_type: "Video Call",
      start_date: "",
      end_date: "",
      sessions: [],
      plans: ["21 Days"],
      cost: [],
      request_callback: [],
      months: [],
    });

    const resetPackageData = function () {
      packageData.value = {
        title: "",
        description: "",
        category: "",
        id: null,
        duration: "Free Session",
        image: null,
        payment_type: "Free",
        communication_type: "Video Call",
        start_date: "",
        end_date: "",
        sessions: [],
        plans: ["21 Days"],
        cost: [],
        request_callback: [],
        months: [],
      };
    };

    const refInputEl = ref(null);
    const previewEl = ref(null);

    const imageRenderer = useInputImageRenderer(refInputEl, previewEl);
    const { refFormObserver, getValidationState, resetForm } = formValidation(
      resetPackageData
    );
    return {
      packages,

      loading,
      sortBy,
      isSortDirDesc,
      columns,
      ucolumns,
      packageData,
      refFormObserver,
      getValidationState,
      resetPackageData,
      resetForm,
      imageRenderer,
      refInputEl,
      previewEl,
    };
  },
  computed: {
    id: function () {
      return this.package.id;
    },
  },
  created() {
    if (this.$route.params.id) {
      this.editPackage(this.$route.params.id);
    }
  },
  methods: {
    validateSize,
    async onSubmit() {
      try {
        let data;
        let packageData = new FormData();

        packageData.append("title", this.packageData.title);
        packageData.append("description", this.packageData.description);
        packageData.append("category", this.packageData.category);
        packageData.append("image", this.image);
        packageData.append("type", this.packageData.type);
        packageData.append("payment_type", this.packageData.payment_type);
        packageData.append("communication_type", this.packageData.communication_type);
        packageData.append("sessions", JSON.stringify(this.packageData.sessions));
        packageData.append("plans", JSON.stringify(this.packageData.plans));
        packageData.append("cost", JSON.stringify(this.packageData.cost));
        packageData.append("request_callback", JSON.stringify(this.packageData.request_callback));
        packageData.append("months", JSON.stringify(this.packageData.months));
        packageData.append("start_date", this.packageData.start_date);
        packageData.append("end_date", this.packageData.end_date);

        if (this.packageData.id) {
          packageData.append("id", this.packageData.id);
          data = await axios.post(`/package/${this.packageData.id}`, packageData, {
            headers: {
              "content-type": "multipart/form-data",
            },
          });
        } else {
          data = await axios.post("/package", packageData, {
            headers: {
              "content-type": "multipart/form-data",
            },
          });
        }
        this.resetFormCard();

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

    async editPackage(id) {
      const { data } = await axios.get(`package/${id}`);
      this.type = "Update Program";
      // this.readonly = true;
      this.packageData.id = data.package.id;
      this.packageData.title = data.package.title;
      this.packageData.category = data.package.category;
      this.packageData.description = data.package.description;
      this.packageData.type = data.package.type;
      this.packageData.payment_type = data.package.payment_type;
      this.packageData.communication_type = data.package.communication_type;
      this.packageData.sessions = data.package.sessions;
      this.packageData.plans = data.package.plans ? data.package.plans : [];
      this.packageData.cost = data.package.cost ? data.package.cost : [];
      this.packageData.request_callback = data.package.request_callback ? data.package.request_callback : [];
      this.packageData.months = data.package.months ? data.package.months : [];
      this.packageData.image = data.package.image;
      this.packageData.start_date = data.package.start_date;
      this.packageData.end_date = data.package.end_date;
    },

    enableFields(a) {
      if (a === "Workshop" || a == "LifeSkill") this.showRange = true;
      else this.showRange = false;
    },

    validatePlan(value, index) {
      this.packageData.plans[index] = "";
      if (!this.packageData.plans.includes(value)) {
        this.packageData.plans[index] = value;
      }
    },
    getPlanName(program) {
      return program.plans ? program.plans.slice(0, 2).join("/") : "";
    },
    resetFormCard() {
      this.resetForm();
      this.resetPackageData();
      this.type = "Create Program";
      this.readonly = false;
      this.errors = [];
      this.image = null;
      this.$refs.previewEl.src = "/view/file?path=" + this.packageData.image;
    },

    addRow(field, index = null) {
      let val = field === "plans" ? "" : {};
      if (field == "plans") {
        this.packageData[field].push(val);
      } else {
        this.packageData[field].push("");
        if (!this.packageData["sessions"][index]) {
          this.packageData["sessions"][index] = [{ month_sessions: [] }];
        } else {
          this.packageData["sessions"][index].push({ month_sessions: [] });
        }
        console.log(this.packageData);
      }
    },
    removeRow(index, pindex, field) {
      this.packageData[field][pindex].splice(index, 1);
      this.packageData[field].push("");
    },
    removePlanRow(index, field) {
      this.packageData[field].splice(index, 1);
    },

    validatePlan(value, index) {
      this.packageData.plans[index] = "";
      if (!this.packageData.plans.includes(value)) {
        this.packageData.plans[index] = value;
      }
      switch (value) {
        case "2 Months":
          this.packageData.months[index] = 2;
          break;
        case "3 Months":
          this.packageData.months[index] = 3;
          break;
        case "6 Months":
          this.packageData.months[index] = 6;
          break;
        case "9 Months":
          this.packageData.months[index] = 9;
          break;
        case "12 Months":
          this.packageData.months[index] = 12;
          break;
        default:
          this.packageData.months[index] = null;
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.per-page-selector {
  width: 90px;
}

.ql-container {
  min-height: 100px !important;
}
</style>

<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";
@import "@core/scss/vue/libs/quill.scss";
@import "@core/scss/vue/libs/vue-flatpicker.scss";

.month-input {
  min-width: 50px;
}
</style>
