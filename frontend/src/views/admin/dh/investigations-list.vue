<template>
  <b-card>
    <b-row class="mb-2" v-if="$store.getters.user.role_id === 17">
      <b-col cols="12" md="6">          
        <h4 v-if="table_shown === false">Create Test Item</h4>
        <h4 v-if="table_shown === true">Investigation Items</h4>
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
      <b-form @submit.prevent="handleSubmit(onSubmit)" v-if="table_shown === false">
        <b-row class="d-flex justify-content-center">
          <b-col cols="20" md="12">

            <b-row class="mt-2 mb-2" v-for="(num, index) in num_rows" :key="index">

              <b-col cols="12" md="3">
                <validation-provider
                  #default="validationContext"
                  name="category"
                  rules="required"
                >
                  <b-form-group label="Select Test Category" label-for="category">
                    <v-select
                      id="category-input"
                      :reduce="val => val.id"
                      :state="getValidationState(validationContext)"
                      v-model="testData[index].category"
                      :class="errors.category ? 'is-invalid' : ''"
                      :area-invalid="errors.category ? true : false"
                      :options="testCategoriesOptions" 
                      @input="selectTestType(index)"
                    >
                    </v-select>
                    <b-form-invalid-feedback v-if="errors.category">
                      {{ errors.category[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-else>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>            

              <b-col cols="12" md="3">
                <validation-provider
                  #default="validationContext"
                  name="sub-category"
                  rules="required"
                >
                  <b-form-group label="Select Sub Category" label-for="category">
                    <v-select
                      id="sub_category-input"
                      :reduce="val => val.id"
                      :state="getValidationState(validationContext)"
                      v-model="testData[index].sub_category"
                      :class="errors.sub_category ? 'is-invalid' : ''"
                      :area-invalid="errors.sub_category ? true : false"
                      :options="testSubCategoriesOptions[index]" 
                    >
                    </v-select>
                    <b-form-invalid-feedback v-if="errors.sub_category">
                      {{ errors.sub_category[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-else>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>            

              <b-col cols="12" md="3">
                <b-form-group label="Test Type" label-for="type">
                  <b-form-input
                    ref="testType[index]" 
                    v-model="testData[index].type"
                    :readonly="true"  
                  />
                </b-form-group>
              </b-col>

              <b-col cols="12" md="3">
                <validation-provider
                  #default="validationContext"
                  name="Cost"
                >
                  <b-form-group
                    label="Cost"
                    label-for="cost"
                    :state="getValidationState(validationContext)"
                  >
                    <b-form-input
                      id="name-input"
                      :state="getValidationState(validationContext)"
                      v-model="testData[index].cost"
                      :class="errors.name ? 'is-invalid' : ''"
                      :area-invalid="errors.cost ? true : false"
                    />
                    <b-form-invalid-feedback :state="getValidationState(validationContext)">
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>
            </b-row>

            <b-row class="mt-0 mb-2">

              <b-col cols="12" md="6">
                <b-button variant="info" class="mt-0 btn-sm" @click="addMoreInvItems"
                  :block="$store.getters['app/currentBreakPoint'] === 'xs'" type="button">
                  <feather-icon
                    icon="PlusIcon"
                    size="16"
                  />
                  &nbsp;<span>Add More</span>
                </b-button>
              </b-col>

              <b-col cols="12" md="6" class="text-right">
                <b-button variant="primary" class="mb-1 mb-sm-0 mr-0 mr-sm-1"
                  :block="$store.getters['app/currentBreakPoint'] === 'xs'" type="submit">
                  <feather-icon
                    icon="PlusIcon"
                    size="16"
                  />
                  &nbsp;Save Changes
                </b-button>
              </b-col>

            </b-row>
          </b-col>
        </b-row>
      </b-form>
    </validation-observer>
    
    <div class="m-0" v-if="table_shown === true"> 
      <b-table
        ref="investigationItemsListTable"
        class="position-relative"
        :items="investigationItems"
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
          {{ "INV-T/" + data.item.id }}
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

              <b-dropdown-item @click="editInvestigationItem(data.item)">
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
        aria-controls="package-row"
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
      testData: [
        {
          category: null, 
          sub_category: null,
          type: null,
          cost: null
        }
      ],
      table_shown: false,
      required,
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
      num_rows: 1,
      testCategoriesOptions: [],
      testSubCategoriesOptions: [],
      investigationItems: [],
      dh_user_id: null,
    };
  },
  setup(props) {
    const blanktestData = [
      {
        category: null, 
        sub_category: null,
        type: null,
        cost: null
      }
    ];

    const testData = ref(JSON.parse(JSON.stringify(blanktestData)));
    const resettestData = () => {
      testData.value = JSON.parse(JSON.stringify(blanktestData));
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
      resettestData
    );

    return {
      roles,
      testData: [
        {
          category: null, 
          sub_category: null,
          type: null,
          cost: null
        }
      ],
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
        { key: "category", label: "Category", sortable: true },
        { key: "sub_category", label: "Sub Categpry", sortable: true },
        { key: "type", label: "Type", sortable: true },
        { key: "cost", label: "Cost", sortable: true },
        { key: "actions", label: "Action", sortable: false },
      ],
      ucolumns: [
        { key: "index", label: "Sr. No.", sortable: true },
        { key: "category", label: "Category", sortable: true },
        { key: "sub_category", label: "Sub Categpry", sortable: true },
        { key: "type", label: "Type", sortable: true },
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
      testSubCategoriesOptions: [],
      num_rows: 1,
      investigationItems: [],
      dh_user_id: null,
    };
  },
  created() {
    if (this.$route.query.user_id) {
      this.dh_user_id = this.$route.query.user_id
      this.table_shown = true
    }

    if (this.id) {
      this.fetchpackageData();
    }

    this.getRoles();
    this.getInvestigationList();
    this.getTestCategories()
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
        this.testData[prop] = e.target.files[0];
      }
    },
    async getInvestigationList() {
      try {
        this.pagination.dh_user_id = this.dh_user_id;
        this.pagination.id = this.id;
        this.pagination.role = this.role;
        const { data } = await axios.get("/dh/investigation/items/list", {
          params: this.pagination,
        });

        this.investigationItems = data.investigation_tests;
        this.pagination.per_page = data.per_page;
        this.pagination.total = data.total;
        this.pagination.page = data.page;

        console.log(this.investigationItems)

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
    async getTestCategories() {
      try {

        this.loading = true;
        this.testCategoriesOptions = [];
        const { data } = await axios.get("testCategory", {
          params: {
            page: 1, 
            per_page: 100
          }
        });

        data.categories.forEach((value, index) => {
            this.testCategoriesOptions.push({
              'id': value.id,
              'label': value.name,
              'type': value.type
            })
        });

      } catch (err) {
        console.log(err);
      }
      this.loading = false;
    },
    async selectTestType(i) {

      const { data } = await axios.get("testItem", {
        params: {
          page: 1, 
          per_page: 100,
          category: this.testData[i].category 
        }
      });

      let optionsSC = [];
      data.testItems.forEach((value, index) => {
        optionsSC.push({
          'id': value.id,
          'label': value.name,
        })
      });

      this.testData[i].sub_category = null
      this.testData[i].type = null
      this.testData[i].cost = null

      this.testSubCategoriesOptions[i] = optionsSC
      console.log(this.testSubCategoriesOptions[i], optionsSC)

      const searchValue = (property_value, array) => {
        return array.filter((category) => category.id === this.testData[i].category);
      }      

      let selected__type = searchValue(this.testData[i].category, this.testCategoriesOptions)[0]
      this.testData[i].type = selected__type.type
    },
    async addMoreInvItems() {
      this.testData.push({
        category: null, 
        sub_category: null,
        type: null,
        cost: null
      })

      this.num_rows = parseInt(this.testData.length)
    },
    async onSubmit() {

      try {
        this.errors = [];

        const { data } = await axios.post(`/dh/investigation/store`, this.testData);

        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "EditIcon",
            variant: data.success ? "success" : "info",
          },
        });

        this.testData = []
        this.testData.push({
          category: null, 
          sub_category: null,
          type: null,
          cost: null
        })

        this.num_rows = parseInt(this.testData.length);
        this.$nextTick(() => this.$refs.refFormObserver.reset());
        this.getInvestigationList();

      } catch (error) {
        if (error.response) {
          this.errors = error.response.data.errors;
        }
      }
    },
    handlePaginationChange(val) {
      this.pagination.page = val;
      this.getInvestigationList();
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
          this.testData.certificates.splice(index, 1);
        }
      } catch (error) {
        if (error.response) {
          this.errors = error.response.data.errors;
        }
      }
    },
    addRow(field, index = null) {
      this.testData["service_areas"].push({ address: '', pincode: '' });
    },
    removeRow(index, field) {
      console.log(this.testData["service_areas"]);
      this.testData["service_areas"].splice(index, 1);
      //this.testData["service_areas"].push("");
      console.log(this.testData["service_areas"]);
    },
    async toggleTable() {
      if(this.table_shown === false){
        this.table_shown = true
        this.getInvestigationList();
      }else{
        this.table_shown = false      
      }
    },
    async editInvestigationItem(records) {
      this.testData = []
      this.testData.push(records)
      this.table_shown = false
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
</style>
