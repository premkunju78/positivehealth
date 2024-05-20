<template>
  <b-card>

    <b-row class="mb-2" v-if="$store.getters.user.role_id === 17">
      <b-col cols="12" md="6">          
        <h4 v-if="table_shown === false && edit_mode === false">Create New Package</h4>
        <h4 v-if="table_shown === true">Package  List</h4>
        <h4 v-if="table_shown === false && edit_mode === true">Edit Package</h4>
      </b-col>
      <b-col cols="12" md="6" class="text-right">          
        <b-button variant="primary" @click="toggleTable" v-if="table_shown === false">
          <span class="text-nowrap">
          <feather-icon
            icon="HardDriveIcon"
            size="16"
          />
          &nbsp;View Records</span>
        </b-button>

        <b-button variant="primary" type="button" @click="reloadPage" v-if="table_shown === false && edit_mode === true">
          <span class="text-nowrap">
          <feather-icon
            icon="FileTextIcon"
            size="16"
          />&nbsp;Add New Package</span>
        </b-button>

        <b-button variant="primary" @click="toggleTable" v-if="table_shown === true">
          <span class="text-nowrap">
          <feather-icon
            icon="FileTextIcon"
            size="16"
          />&nbsp;Show Form</span>
        </b-button>

      </b-col>
    </b-row>

    <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
      <!-- User Info: Input Fields -->
      <b-form @submit.prevent="handleSubmit(onSubmit)" ref="packageForm" v-if="table_shown === false">
        <b-row class="d-flex justify-content-center">
          <b-col cols="20" md="10">

            <b-row class="mt-2 mb-2">

              <b-col cols="12" md="3">
                <validation-provider #default="validationContext" name="Package Name" rules="required">
                  <b-form-group label="Name of the Package" label-for="package-name">
                    <b-form-input id="package-name" v-model="packageData.name"
                      :state="getValidationState(validationContext)" placeholder="Package Name" />
                    <b-form-invalid-feedback>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>

              <b-col cols="12" md="2">
                <validation-provider #default="validationContext" name="No. of Test" rules="required">
                  <b-form-group label="Total No. of the Test" label-for="test-number">
                    <b-form-input type="number" :readonly="true" id="test-number" v-model="packageData.num_of_test"
                      :state="getValidationState(validationContext)" placeholder="No. of Test" />
                    <b-form-invalid-feedback>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>

              <b-col cols="12" md="2">
                <validation-provider
                  #default="validationContext"
                  name="test_cost"
                >
                  <b-form-group
                    label="Original Cost"
                    label-for="test_cost"
                    :state="getValidationState(validationContext)"
                  >
                    <b-form-input
                      type="number"
                      :state="getValidationState(validationContext)"
                      v-model="packageData.cost"
                      :class="errors.name ? 'is-invalid' : ''"
                      :area-invalid="errors.test_cost ? true : false" 
                      @input="calculateDiscount"
                    />
                    <b-form-invalid-feedback :state="getValidationState(validationContext)">
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>

              <b-col cols="12" md="2">
                <validation-provider #default="validationContext" name="discount" rules="required">
                  <b-form-group label="Discount (%)" label-for="test-number">
                    <b-form-input type="number" id="package-discount" v-model="packageData.discount"
                      :state="getValidationState(validationContext)" placeholder="Enter discount" @input="calculateDiscount"/>
                    <b-form-invalid-feedback>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>

              <b-col cols="12" md="3">
                <validation-provider #default="validationContext" name="Final Cost" rules="required">
                  <b-form-group label="Final Cost" label-for="test-number">
                    <b-form-input type="number" :readonly="true" id="final-cost" v-model="packageData.final_cost"
                      :state="getValidationState(validationContext)" placeholder="Final Cost" />
                    <b-form-invalid-feedback>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>

            </b-row>  

            <b-row class="mt-2 mb-2" v-for="(num, index) in total_tests" :key="index">

              <b-col cols="12" md="4">
                <validation-provider
                  #default="validationContext"
                  name="Name of Test"
                >
                  <b-form-group
                    label="Test Name"
                    label-for="test_name"
                    :state="getValidationState(validationContext)"
                  >
                    <b-form-input
                      :state="getValidationState(validationContext)"
                      v-model="packageData.tests[index].name"
                      :class="errors.name ? 'is-invalid' : ''"
                      :area-invalid="errors.test_name ? true : false"
                    />
                    <b-form-invalid-feedback :state="getValidationState(validationContext)">
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>

              <b-col cols="12" md="3">
                <validation-provider #default="validationContext" name="no-of-tests" rules="required">
                  <b-form-group label="No of Test(s)" label-for="no-of-tests">
                    <b-form-input type="number" id="no-of-tests" v-model="packageData.tests[index].no_of_tests"
                      :state="getValidationState(validationContext)" placeholder="No of Test(s)" />
                    <b-form-invalid-feedback>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>

              <b-col cols="12" md="3">
                <validation-provider #default="validationContext" name="no-of-tests">
                  <b-form-group label="Upload Vector Image" label-for="vector-image">
                    <b-form-file ref="refTestVectorEl[index]" v-model="packageData.tests[index].vector_image" accept=".jpg, .png, .gif" />
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>

              <b-col cols="12" md="2" v-if="index===0">
                <b-button variant="info" class="mt-2 btn-sm" @click="addMoreTests"
                  :block="$store.getters['app/currentBreakPoint'] === 'xs'" type="button">
                  <feather-icon
                    icon="PlusIcon"
                    size="16"
                  />&nbsp;<span>Add More</span>
                </b-button>              
              </b-col>

            </b-row>  

            <b-row class="mt-2 mb-2">

              <b-col cols="12" md="8" class="mt-2 mb-2">
                <!-- media -->
                <b-media no-body :class="errors.logo ? 'is-invalid' : ''" :area-invalid="errors.logo ? true : false">
                  <b-media-aside>
                    <a :href="'/view/file?path=' + packageData.logo" target="_blank">
                      <b-img ref="previewLogoEl" rounded :src="'/view/file?path=' + packageData.logo" height="80" />
                    </a>
                    <!--/ avatar -->
                  </b-media-aside>

                  <b-media-body class="mt-75 ml-75">
                    <!-- upload button -->
                    <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" size="sm" class="mb-75 mr-75"
                      @click="$refs.refLogoEl.$el.click()">
                      Vector Image
                    </b-button>
                    <b-form-file ref="refLogoEl" v-model="logo" accept=".jpg, .png, .gif" :hidden="true" plain
                      @input="validateSize(logo, 'logo', inputLogoRenderer)" />
                    <!--/ upload button -->

                    <!-- reset -->
                    <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" variant="outline-secondary" size="sm"
                      class="mb-75 mr-75" v-if="logo" @click="
                        $refs.previewLogoEl.src = '/view/file?path=' + packageData.logo;
                      logo = null;
                      ">
                      Reset
                    </b-button>
                    <!--/ reset -->
                    <small>Allowed JPG, GIF or PNG. Max size 2mb</small>
                  </b-media-body>
                </b-media>
                <b-form-invalid-feedback v-if="errors.logo">
                  {{ errors.logo[0] }}
                </b-form-invalid-feedback>
              </b-col>              

              <b-col cols="12" md="8">
                <b-form-group label="Notes" label-for="languages">
                  <b-form-textarea id="notes" v-model="packageData.notes" rows="4" placeholder="notes" />
                </b-form-group>
              </b-col>

            </b-row>

          </b-col>
        </b-row>
        <b-row style="margin-top: 5%; margin-left: 40%">
          <!-- Action Buttons -->
          <router-link :to="{ name: 'dh' }">
            <b-button variant="outline-secondary" type="reset" class="mb-1 mb-sm-0 mr-0 mr-sm-1"
              :block="$store.getters['app/currentBreakPoint'] === 'xs'">
              Back
            </b-button>
          </router-link>

          <b-button variant="primary" class="mb-1 mb-sm-0 mr-0 mr-sm-1"
            :block="$store.getters['app/currentBreakPoint'] === 'xs'" type="submit">
            Save Changes
          </b-button>
        </b-row>
      </b-form>
    </validation-observer>
    <div class="m-0" v-if="table_shown === true"> 
      <b-table
        ref="packagesListTable"
        class="position-relative"
        :items="healthpackageItems"
        responsive
        :fields="tableColumns"
        primary-key="id"
        :sort-by.sync="sortBy"
        :totalRows="totalRows"
        show-empty
        empty-text="No matching records found"
        :sort-desc.sync="isSortDirDesc"
      >

        <template #cell(id)="data">
          {{ "PKG/" + data.item.id }}
        </template>

        <template #cell(formatted_tests)="data">
          <div v-html="data.item.formatted_tests"></div>
        </template>

        <!-- Column: Actions -->
        <template #cell(actions)="data">
          <div class="text-nowrap">
            <b-dropdown
              variant="link"
              no-caret
              :right="$store.state.appConfig.isRTL"
            >
              <template #button-content>
                <feather-icon
                  icon="MoreVerticalIcon"
                  size="16"
                  class="align-middle text-body"
                />
              </template>

              <b-dropdown-item @click="editHealthpackageItem(data.item)">
                <feather-icon icon="EditIcon" />
                <span class="align-middle ml-50">Edit</span>
              </b-dropdown-item>

              <b-dropdown-item @click="viewHealthpackageItem(data.item)">
                <feather-icon icon="EyeIcon" />
                <span class="align-middle ml-50">View</span>
              </b-dropdown-item>

            </b-dropdown>
          </div>
        </template>
      </b-table>
      <b-pagination
        v-if="pagination.total"
        v-model="pagination.page"
        :page.sync="pagination.page"
        :total-rows="pagination.total"
        :index="1"
        :per-page="pagination.per_page"
        aria-controls="package-row"
        align="right"
        @change="handlePaginationChange"
      >
      </b-pagination>
    </div>

    <b-modal id="view-healthpackage-modal" ref="view-healthpackage-modal" hide-footer title="Healthpackage Details" no-close-on-backdrop size="md">
        <template #modal-title>Healthpackage Details</template>
        <div class="d-block text-left">
          <div class="contant">
            <p>Name: {{ viewPackageData.name }}</p>
            <p>Cost: {{ viewPackageData.cost }}</p>
            <p>Total Tests: {{ viewPackageData.num_of_test }}</p>
          </div>
          <table class="table">
            <thead>
              <tr class="text-center"><td colspan="12" style="border: 0;"><h3 style="font-size: 16px; text-transform: uppercase;"><strong>Test Details</strong></h3></td></tr>
              <tr>
                <th>Test Name</th>
                <th>No. of Subtest</th>
                <th>Vector Picture</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(test, index) in viewPackageData.tests">
                <td>{{ test.name }}</td>
                <td>{{ test.no_of_tests }}</td>
                <td><img :src="'/view/file?path=' + test.vector_image" style="max-height: 50px; width: auto;"/></td>
              </tr>
            </tbody>
          </table>
          <div class="contant--notes" v-if="viewPackageData.notes !== null">
            <p>Notes: {{ viewPackageData.notes }}</p>
          </div>
        </div>
    </b-modal>

  </b-card>
</template>

<script>
import {
  BButton,
  BMedia,
  BAvatar,
  BRow,
  BCol,
  BFormGroup,
  BFormInput,
  BForm,
  BTable,
  BCard,
  BCardHeader,
  BCardTitle,
  BFormCheckbox,
  BFormInvalidFeedback,
  BFormFile,
  BMediaAside,
  BMediaBody,
  BLink,
  BImg,
  BFormRadioGroup,
  BFormTextarea,
  BDropdown,
  BDropdownItem,
  BPagination,
  VBModal,
  BModal
} from "bootstrap-vue";
import { required, email } from "@validations";
import formValidation from "@core/comp-functions/forms/form-validation";
import vSelect from "vue-select";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { ref } from "@vue/composition-api";
import flatPickr from "vue-flatpickr-component";
import Ripple from "vue-ripple-directive";

import { validateSize } from "@core/utils/utils";

import VoerroTagsInput from "@voerro/vue-tagsinput";

require("@voerro/vue-tagsinput/dist/style.css");

import { useInputImageRenderer } from "@core/comp-functions/forms/form-utils";

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

export default {
  components: {
    BButton,
    BMedia,
    BAvatar,
    BRow,
    BCol,
    BFormGroup,
    BFormInput,
    BForm,
    BTable,
    BCard,
    BCardHeader,
    BCardTitle,
    BFormCheckbox,
    BFormFile,
    vSelect,
    BFormInvalidFeedback,
    BMedia,
    BMediaAside,
    BMediaBody,
    BLink,
    BImg,
    flatPickr,
    BFormRadioGroup,
    BDropdown,
    BDropdownItem,
    BPagination,
    VBModal,
    BModal,
    BFormTextarea,
    "tags-input": VoerroTagsInput,

    ValidationProvider,
    ValidationObserver,
  },
  directives: {
    Ripple,
  },
  props: {
    id: {
      type: [Number, String, null],
      default: null,
    },
  },
  data() {
    return {
      required,
      table_shown: false,
      edit_mode: false,
      packageData: {
        name: null,
        num_of_test: null,
        cost: null,
        tests: [
          {
            name: null,
            no_of_tests: null,
            vector_image: null
          }
        ],
        notes: null,
        logo: null
      },
      email,
      errors: [],
      logo: null,
      idproof: null,
      cheque: null,
      genders: [
        { text: "Male", value: "male" },
        { text: "Female", value: "female" },
      ],
      maxSize: 2097152,
      packageCategoriesOptions: [],
      viewPackageData: [],
      healthpackageItems: [],      
      dh_user_id: null,
    };
  },
  setup(props) {
    const blankpackageData = {
      name: "",
      num_of_test: "",
      cost: "",
      tests: [
        {
          name: null,
          no_of_tests: null,
          vector_image: null
        }
      ],
      notes: "",
      logo: null
    };

    const packageData = ref(JSON.parse(JSON.stringify(blankpackageData)));
    const resetpackageData = () => {
      packageData.value = JSON.parse(JSON.stringify(blankpackageData));
    };

    const refLogoEl = ref(null);
    const previewLogoEl = ref(null);
    const inputLogoRenderer = useInputImageRenderer(refLogoEl, previewLogoEl);

    const refBannerEl = ref(null);
    const previewBannerEl = ref(null);
    const inputBannerRenderer = useInputImageRenderer(refBannerEl, previewBannerEl);


    const refIDProofEl = ref(null);
    const previewIDProofEl = ref(null);
    const refChequeEl = ref(null);
    const previewChequeEl = ref(null);


    const inputIDProofRenderer = useInputImageRenderer(refIDProofEl, previewIDProofEl);
    const inputChequeRenderer = useInputImageRenderer(refChequeEl, previewChequeEl);

    const roles = [];

    const { refFormObserver, getValidationState, resetForm } = formValidation(
      resetpackageData
    );

    return {
      roles,
      packageData: {
        name: null,
        num_of_test: null,
        cost: null,
        tests: [
          {
            name: null,
            no_of_tests: null,
            vector_image: null
          }
        ],
        notes: null,
        logo: null
      },
      refFormObserver,
      logo: null,
      banner: null,
      getValidationState,
      resetForm,
      refIDProofEl,
      previewIDProofEl,
      inputIDProofRenderer,
      refChequeEl,
      previewChequeEl,
      inputChequeRenderer,
      refLogoEl,
      previewLogoEl,
      inputLogoRenderer,
      refBannerEl,
      previewBannerEl,
      inputBannerRenderer,
      packageCategoriesOptions: [],
      total_tests: 1,
      perPage: 10,
      perPageOptions: [10, 25, 50, 100],
      totalRows: "",
      search: "",
      searchQuery: "",
      limit: "10",
      sortBy: "1",
      isSortDirDesc: false,
      tableColumns: [
        { key: "id", label: "Id", sortable: true },
        { key: "name", label: "Name", sortable: true },
        { key: "num_of_test", label: "Total Tests", sortable: true },
        { key: "formatted_tests", label: "Tests", sortable: true },
        { key: "cost", label: "Cost", sortable: true },
        { key: "actions", label: "Action", sortable: false },
      ],
      ucolumns: [
        { key: "index", label: "Sr. No.", sortable: true },
        { key: "name", label: "Name", sortable: true },
        { key: "num_of_test", label: "Total Tests", sortable: true },
        { key: "formatted_tests", label: "Tests", sortable: true },
        { key: "cost", label: "Cost", sortable: true },
        { key: "actions", label: "Action", sortable: false },
      ],
      userData: {
        roles: [],
        consultants: [],
      },
      pagination: {
        page: 1,
        per_page: 10,
        total: null,
      },
      healthpackageItems: [],      
    };
  },
  created() {
    this.getRoles();

    if (this.$route.query.user_id) {
      this.dh_user_id = this.$route.query.user_id
      this.table_shown = true
    }

    if (this.id) {
      this.fetchpackageData();
    }

    this.getHealthpackageList()
    this.packageData.num_of_test = 1
  },

  methods: {
    validateSize,
    async getRoles() {
      try {
        const { data } = await axios.get("/roles/list");
        this.roles = data.roles;
      } catch (err) {
        console.log(err);
      }
    },
    setFile(e, prop) {
      let supportedTypes = ["image/jpg", "image/png", "image/jpeg", "image/gif"];
      let image = e.target.files[0];
      let type = image.type;
      if (!supportedTypes.includes(type)) {
        this.$toast({
          component: ToastificationContent,
          props: {
            title: "Please select valid image file",
            icon: "EditIcon",
            variant: "danger",
          },
        });
        return;
      } else {
        this.packageData[prop] = e.target.files[0];
      }
    },
    async getHealthpackageList() {
      try {
        this.pagination.dh_user_id = this.dh_user_id;
        this.pagination.id = this.id;
        this.pagination.role = this.role;
        const { data } = await axios.get("/dh/healthpackages/list", {
          params: this.pagination,
        });

        this.healthpackageItems = data.healthpackages;
        this.pagination.per_page = data.per_page;
        this.pagination.total = data.total;
        this.pagination.page = data.page;

        console.log(this.healthpackageItems)

      } catch {
        this.$toast({
          component: ToastificationContent,
          props: {
            title: "Error fetching dh list",
            icon: "AlertTriangleIcon",
            variant: "danger",
          },
        });
      }
    },
    async addMoreTests() {
      this.packageData.tests.push(
        {
          name: null,
          no_of_tests: null,
          vector_image: null
        }
      )

      this.total_tests = parseInt(this.packageData.tests.length) 
      this.packageData.num_of_test = this.total_tests
    },
    async onSubmit() {
      try {
        let data;
        let packageData = new FormData();

        if(this.packageData.tests.length > 0) {
          this.packageData.tests.forEach((value, index) => {
            console.log(value.vector_image, index);
            packageData.append("vectorimg_" + index, value.vector_image);            
          });           
        }

        if(this.packageData.id) {
          packageData.append("id", this.packageData.id);
        }

        packageData.append("name", this.packageData.name);
        packageData.append("num_of_test", this.packageData.num_of_test);
        packageData.append("tests", JSON.stringify(this.packageData.tests));
        packageData.append("notes", this.packageData.notes);
        packageData.append("cost", this.packageData.cost);
        packageData.append("logo", this.logo);

        data = await axios.post("/dh/healthpackages/store/", packageData, {
          headers: {
            "content-type": "multipart/form-data",
          },
        });

        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.data.message,
            icon: "EditIcon",
            variant: data.data.success ? "success" : "info",
          },
        });

        this.packageData = {
          name: null,
          num_of_test: null,
          cost: null,
          tests: [
            {
              name: null,
              no_of_tests: null,
              vector_image: null
            }
          ],
          notes: null,
          logo: null        
        }

        this.total_tests = parseInt(this.packageData.tests.length) 
        this.packageData.num_of_test = this.total_tests       
        this.getHealthpackageList();

        this.$nextTick(() => this.$refs.refFormObserver.reset());

      } catch (error) {
        if (error.response) {
          this.errors = error.response.data.errors;
        }
      }
    },

    async fetchpackageData() {
      try {
        const { data } = await axios.get(`/dh/${this.id}`);
        this.packageData = data.dh;
        if (this.packageData.service_areas) {
          this.packageData.service_areas = JSON.parse(data.dh.service_areas)
        } else {
          this.packageData.service_areas = []
        }
      } catch (err) {
        console.log(err);
      }
    },
    remove(index) {
      this.certificate.splice(index, 1);
    },
    async removeFile(index) {
      try {
        const { data } = await axios.delete(`/account/certificate/${index}`, {
          params: {
            id: this.id,
          },
        });
        if (data.success) {
          this.packageData.certificates.splice(index, 1);
        }
      } catch (error) {
        if (error.response) {
          this.errors = error.response.data.errors;
        }
      }
    },
    addRow(field, index = null) {
      this.packageData["service_areas"].push({ address: '', pincode: '' });
    },
    removeRow(index, field) {
      console.log(this.packageData["service_areas"]);
      this.packageData["service_areas"].splice(index, 1);
      //this.packageData["service_areas"].push("");
      console.log(this.packageData["service_areas"]);
    },
    async toggleTable() {
      if(this.table_shown === false){
        this.table_shown = true
        this.getHealthpackageList();
      }else{
        this.table_shown = false      
      }
    },
    async editHealthpackageItem(records) {
      this.packageData = records
      console.log(this.packageData)

      this.total_tests = parseInt(this.packageData.tests.length) 
      this.packageData.num_of_test = this.total_tests
      this.table_shown = false
      this.edit_mode = true
    },
    reloadPage() {
      window.location.reload();
    },
    async calculateDiscount() {
      let final_cost = this.packageData.cost;
      if(this.packageData.discount !== null && this.packageData.cost !== null) {
        final_cost = this.packageData.cost - ((this.packageData.cost*this.packageData.discount)/100)
      }

      this.packageData.final_cost = final_cost;
    },
    async viewHealthpackageItem(records) {
      this.viewPackageData = records;
      this.$refs['view-healthpackage-modal'].show();            
    }
  },
};
</script>

<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";
@import "@core/scss/vue/libs/vue-flatpicker.scss";

.custom-radio {
  margin-top: 0 !important;
}

ul.list {
  padding-left: 0;
  list-style-type: numeric;
  display: inline;
}
</style>
