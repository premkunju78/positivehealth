<template>
  <b-card>
    <b-row class="mb-2">
      <b-col cols="12" md="6" v-if="$store.getters.user.role_id === 17">          
        <h4 v-if="table_shown === false && edit_mode === false">Create New availability</h4>
        <h4 v-if="table_shown === true">Availability  List</h4>
        <h4 v-if="table_shown === false && edit_mode === true">Edit availability</h4>
      </b-col>
      <b-col cols="12" :md="$store.getters.user.role_id === 17 ? 6 : 12" class="text-right">          
        <b-button variant="primary" @click="toggleTable" v-if="table_shown === false && $store.getters.user.role_id === 17">
          <span class="text-nowrap">
          <feather-icon
            icon="HardDriveIcon"
            size="16"
          />
          &nbsp;View Records</span>
        </b-button>

        <b-button variant="primary" type="button" @click="reloadPage" v-if="table_shown === false && edit_mode === true && $store.getters.user.role_id === 17">
          <span class="text-nowrap">
          <feather-icon
            icon="FileTextIcon"
            size="16"
          />&nbsp;Add New availability</span>
        </b-button>

        <ul class="flex-list" v-if="table_shown === true">
          <li>
            <b-form-input style="min-width: 300px;" @input="getCentresList" v-model="search.value" placeholder="Search Address" />
          </li>
          <li v-if="$store.getters.user.role_id === 17"> 
            <b-button variant="primary" @click="toggleTable">
              <span class="text-nowrap">
              <feather-icon
                icon="FileTextIcon"
                size="16"
              />&nbsp;Show Form</span>
            </b-button>
          </li>
        </ul>

      </b-col>
    </b-row>

    <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
      <!-- User Info: Input Fields -->
      <b-form @submit.prevent="handleSubmit(onSubmit)" v-if="table_shown === false">
        <b-row class="d-flex justify-content-center">
          <b-col cols="20" md="10">
            <h4>Area Availability</h4>

            <b-row class="mt-2 mb-2" :class="total_availability > 1 ? 'bordered-box' : ''" v-for="(num, index) in total_availability" :key="index">

              <b-col cols="12" md="4">
                <validation-provider #default="validationContext" name="Branch Name" rules="required">
                  <b-form-group label="Branch" label-for="availability-branch">
                    <b-form-input id="branch" v-model="availabilityData[index].branch"
                      :state="getValidationState(validationContext)" placeholder="Branch Name" />
                    <b-form-invalid-feedback>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>

              <b-col cols="12" md="4">
                <validation-provider #default="validationContext" name="Full Address" rules="required">
                  <b-form-group label="Full Address" label-for="test-number">
                    <b-form-textarea v-model="availabilityData[index].full_address" rows="4" placeholder="Full Address" />
                    <b-form-invalid-feedback>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>

              <b-col cols="12" md="4">
                <validation-provider #default="validationContext" name="Contact Number" rules="required">
                  <b-form-group label="Contact Number" label-for="availability-contact-number">
                    <b-form-input v-model="availabilityData[index].contact_number"
                      :state="getValidationState(validationContext)" placeholder="Contact Number" />
                    <b-form-invalid-feedback>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>

              <b-col cols="12" md="4">
                <validation-provider #default="validationContext" name="Map Code" rules="required">
                  <b-form-group label="Map Code" label-for="availability-map-code">
                    <b-form-input v-model="availabilityData[index].map_code"
                      :state="getValidationState(validationContext)" placeholder="Map Code" />
                    <b-form-invalid-feedback>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>

              <b-col cols="12" md="4">
                <validation-provider
                  #default="validationContext"
                  name="Test Available"
                  rules="required"
                >
                  <b-form-group label="Select Available Test " label-for="category">
                    <v-select
                      :reduce="val => val.id"
                      :state="getValidationState(validationContext)"
                      v-model="availabilityData[index].available_test_category"
                      :class="errors.available_test_category ? 'is-invalid' : ''"
                      :area-invalid="errors.available_test_category ? true : false"
                      :options="testCategoriesOptions" 
                    >
                    </v-select>
                    <b-form-invalid-feedback v-if="errors.available_test_category">
                      {{ errors.available_test_category[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-else>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>            

              <b-col cols="12" md="4">
                <validation-provider #default="validationContext" name="Delivery Executive Number" rules="required">
                  <b-form-group label="Delivery Executive Number" label-for="availability-delivery-executive-number">
                    <b-form-input v-model="availabilityData[index].delivery_executive_number"
                      :state="getValidationState(validationContext)" placeholder="Delivery Executive Number" />
                    <b-form-invalid-feedback>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>

            </b-row>  

            <b-row class="mt-0 mb-2">

              <b-col cols="12" md="8" class="mt-0 mb-0">

                <b-button variant="primary" class="mt-0" @click="addMoreAvailabilities"
                  :block="$store.getters['app/currentBreakPoint'] === 'xs'" type="button">
                  <feather-icon
                    icon="PlusIcon"
                    size="16"
                  />&nbsp;<span>Add More</span>
                </b-button>              

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
        ref="availabilitysListTable"
        class="position-relative"
        :items="centresItems"
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
          {{ "CI/" + data.item.id }}
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

              <b-dropdown-item @click="editCentresItem(data.item)">
                <feather-icon icon="EditIcon" />
                <span class="align-middle ml-50">Edit</span>
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
        aria-controls="availability-row"
        align="right"
        @change="handlePaginationChange"
      >
      </b-pagination>
    </div>

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
      search: {
        value: null
      },
      email,
      errors: [],
      signature: null,
      idproof: null,
      cheque: null,
      genders: [
        { text: "Male", value: "male" },
        { text: "Female", value: "female" },
      ],
      maxSize: 2097152,
      availabilityCategoriesOptions: [],
      testCategoriesOptions: [],
      centresItems: [],      
      dh_user_id: null,
    };
  },
  setup(props) {
    const blankavailabilityData = {
      branch: null, 
      full_address: null,
      contact_number: null,
      map_code: null,
      available_test_category: null,
      delivery_executive_number: null      
    };

    const availabilityData = ref(JSON.parse(JSON.stringify(blankavailabilityData)));
    const resetavailabilityData = () => {
      availabilityData.value = JSON.parse(JSON.stringify(blankavailabilityData));
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
      resetavailabilityData
    );

    return {
      roles,
      availabilityData: [
        {
          branch: null, 
          full_address: null,
          contact_number: null,
          map_code: null,
          available_test_category: null,
          delivery_executive_number: null      
        }
      ],
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
      testCategoriesOptions: [],
      total_tests: 1,
      total_availability: 1,
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
        { key: "branch", label: "Branch", sortable: true },
        { key: "full_address", label: "Full Address", sortable: true },
        { key: "contact_number", label: "Contact Number", sortable: true },
        { key: "map_code", label: "Map Code", sortable: true },
        { key: "available_test_category", label: "Available Test", sortable: true },
        { key: "delivery_executive_number", label: "Delivery Executive Number", sortable: true },
        { key: "actions", label: "Action", sortable: false },
      ],
      ucolumns: [
        { key: "index", label: "Sr. No.", sortable: true },
        { key: "branch", label: "Branch", sortable: true },
        { key: "full_address", label: "Full Address", sortable: true },
        { key: "contact_number", label: "Contact Number", sortable: true },
        { key: "map_code", label: "Map Code", sortable: true },
        { key: "available_test_category", label: "Available Test", sortable: true },
        { key: "delivery_executive_number", label: "Delivery Executive Number", sortable: true },
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
      centresItems: [],      
    };
  },
  created() {
    if (this.$route.query.user_id) {
      this.dh_user_id = this.$route.query.user_id
      this.table_shown = true
    }

    this.getRoles();
    this.getTestCategories();
    this.getCentresList();

    if (this.id) {
      this.fetchavailabilityData();
    }
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
        this.availabilityData[prop] = e.target.files[0];
      }
    },
    async getCentresList() {

      try {
        this.pagination.dh_user_id = this.dh_user_id;            
        this.pagination.search_word = null
        if(this.search.value !== null && this.search.value.length > 3) {
          this.pagination.search_word = this.search.value
        }

        this.pagination.id = this.id;
        this.pagination.role = this.role;
        const { data } = await axios.get("/dh/centres/list", {
          params: this.pagination,
        });

        this.centresItems = data.centres_list;
        this.pagination.per_page = data.per_page;
        this.pagination.total = data.total;
        this.pagination.page = data.page;

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
    async addMoreAvailabilities() {

      this.availabilityData.push({
        branch: null, 
        full_address: null,
        contact_number: null,
        map_code: null,
        available_test_category: null,
        delivery_executive_number: null      
      })

      this.total_availability = parseInt(this.availabilityData.length)
      console.log(this.availabilityData.length)      
    },
    async getTestCategories() {
      try {

        this.loading = true;
        this.testCategoriesOptions = [];
        const { data } = await axios.get("testCategory", {
          params: {
            page: 1, 
            per_page: 100,
            type: 'onsite'
          }
        });

        data.categories.forEach((value, index) => {
            this.testCategoriesOptions.push({
              'id': value.id,
              'label': value.name,
            })
        });

        console.log(this.testCategoriesOptions)

      } catch (err) {
        console.log(err);
      }
      this.loading = false;
    },
    async selectavailabilityType() {
      const searchValue = (property_value, array) => {
        return array.filter((category) => category.id === this.availabilityData.availability_category);
      }      

      let selected__availability_type = searchValue(this.availabilityData.availability_category, this.availabilityCategoriesOptions)[0]
      console.log(selected__availability_type)
      this.availabilityData.availability_type = selected__availability_type.type
    },
    async onSubmit() {

      try {
        const { data } = await axios.post("/dh/centres/store", this.availabilityData);
        console.log(data);

        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "EditIcon",
            variant: data.success ? "success" : "info",
          },
        });

        this.availabilityData = [];
        this.addMoreAvailabilities();
        this.getCentresList();

        this.$nextTick(() => this.$refs.refFormObserver.reset());

      } catch (error) {
        if (error.response) {
          this.errors = error.response.data.errors;
        }
      }
    },
    async toggleTable() {
      if(this.table_shown === false){
        this.table_shown = true
        this.getCentresList();
      }else{
        this.table_shown = false      
      }
    },
    async editCentresItem(records) {
      this.availabilityData = []
      this.availabilityData.push(records)
      this.total_availability = parseInt(this.availabilityData.length)
      this.table_shown = false
      this.edit_mode = true
    },
    reloadPage() {
      window.location.reload();
    },
    async fetchavailabilityData() {
      try {
        const { data } = await axios.get(`/dh/${this.id}`);
        this.availabilityData = data.dh;
        if (this.availabilityData.service_areas) {
          this.availabilityData.service_areas = JSON.parse(data.dh.service_areas)
        } else {
          this.availabilityData.service_areas = []
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
          this.availabilityData.certificates.splice(index, 1);
        }
      } catch (error) {
        if (error.response) {
          this.errors = error.response.data.errors;
        }
      }
    },
    addRow(field, index = null) {
      this.availabilityData["service_areas"].push({ address: '', pincode: '' });
    },
    removeRow(index, field) {
      console.log(this.availabilityData["service_areas"]);
      this.availabilityData["service_areas"].splice(index, 1);
      //this.availabilityData["service_areas"].push("");
      console.log(this.availabilityData["service_areas"]);
    },
  },
};
</script>

<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";
@import "@core/scss/vue/libs/vue-flatpicker.scss";

.custom-radio {
  margin-top: 0 !important;
}

ul.flex-list {
    display: flex;
    list-style-type: none;
    flex-wrap: wrap;
    gap: 0 2rem;
    float: right;
}

.bordered-box {
  border: 2px solid #EEEEEE;
  padding: 1rem 0.2rem;
  margin-bottom: 0.5rem
}
</style>
