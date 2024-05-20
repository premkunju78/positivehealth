<template>
  <b-card>
    <b-row class="mb-2" v-if="$store.getters.user.role_id === 17">
      <b-col cols="12" md="6">          
        <h4 v-if="table_shown === false && edit_mode === false">Create New availability</h4>
        <h4 v-if="table_shown === true">OnSite Test List</h4>
        <h4 v-if="table_shown === false && edit_mode === true">Edit Test</h4>
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
          />&nbsp;Add New Test</span>
        </b-button>

        <ul class="flex-list" v-if="table_shown === true">
          <li> 
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
            <h4>Create Onsite Package</h4>

            <b-row class="mt-2 mb-2">

              <b-col cols="12" md="4">
                <validation-provider
                  #default="validationContext"
                  name="test-category"
                  rules="required"
                >
                  <b-form-group label="Select Test Category" label-for="category">
                    <v-select
                      id="test_category-input"
                      :reduce="val => val.id"
                      :state="getValidationState(validationContext)"
                      v-model="onsiteData.test_category"
                      :class="errors.test_category ? 'is-invalid' : ''"
                      :area-invalid="errors.test_category ? true : false"
                      :options="testCategoriesOptions" 
                    >
                    </v-select>
                    <b-form-invalid-feedback v-if="errors.test_category">
                      {{ errors.test_category[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback v-else>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>            

              <b-col cols="12" md="4">
                <validation-provider #default="validationContext" name="Name" rules="required">
                  <b-form-group label="Name" label-for="name">
                    <b-form-input id="package-name" v-model="onsiteData.name"
                      :state="getValidationState(validationContext)" placeholder="Name" />
                    <b-form-invalid-feedback>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>

              <b-col cols="12" md="4">
                <validation-provider #default="validationContext" name="Part" rules="required">
                  <b-form-group label="Total Parts" label-for="part">
                    <b-form-input id="package-part" v-model="onsiteData.part"
                      :state="getValidationState(validationContext)" placeholder="Part" />
                    <b-form-invalid-feedback>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>

              <b-col cols="12" md="4">
                <validation-provider #default="validationContext" name="View" rules="required">
                  <b-form-group label="View" label-for="view">
                    <b-form-input id="onsite-package-view" v-model="onsiteData.view"
                      :state="getValidationState(validationContext)" placeholder="View" />
                    <b-form-invalid-feedback>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>

              <b-col cols="12" md="4">
                <validation-provider #default="validationContext" name="Cost" rules="required">
                  <b-form-group label="Cost" label-for="view">
                    <b-form-input type="number" v-model="onsiteData.cost"
                      :state="getValidationState(validationContext)" placeholder="Cost" />
                    <b-form-invalid-feedback>
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>

              <b-col cols="12" md="4" class="mt-2 mb-2">
                <!-- media -->
                <b-media no-body :class="errors.logo ? 'is-invalid' : ''" :area-invalid="errors.logo ? true : false">
                  <b-media-aside>
                    <a :href="'/view/file?path=' + onsiteData.logo" target="_blank">
                      <b-img ref="previewLogoEl" rounded :src="'/view/file?path=' + onsiteData.logo" height="80" />
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
                        $refs.previewLogoEl.src = '/view/file?path=' + onsiteData.logo;
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

            </b-row>  

            <b-row class="mt-2 mb-2">

              <b-col cols="12" md="12">
                <b-form-group label="Notes" label-for="languages">
                  <b-form-textarea id="notes" v-model="onsiteData.notes" rows="4" placeholder="notes" />
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
        ref="availabilitysListTable"
        class="position-relative"
        :items="onsiteItems"
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

              <b-dropdown-item @click="editOnsiteItem(data.item)">
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
    BFormTextarea,
    BDropdown,
    BDropdownItem,
    BPagination,
    VBModal,
    BModal,
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
      onsiteData: {},
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
      testCategoriesOptions: [],
      onsiteItems: [],      
      dh_user_id: null,
    };
  },
  setup(props) {
    const blankonsiteData = {
      name: "",
      num_of_test: "",
      tests: [],
      notes: ""
    };

    const onsiteData = ref(JSON.parse(JSON.stringify(blankonsiteData)));
    const resetonsiteData = () => {
      onsiteData.value = JSON.parse(JSON.stringify(blankonsiteData));
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
      resetonsiteData
    );

    return {
      roles,
      onsiteData,
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
        { key: "test_category", label: "Test Category", sortable: true },
        { key: "name", label: "Name", sortable: true },
        { key: "part", label: "Part", sortable: true },
        { key: "view", label: "View", sortable: true },
        { key: "cost", label: "Cost", sortable: true },
        { key: "actions", label: "Action", sortable: false },
      ],
      ucolumns: [
        { key: "index", label: "Sr. No.", sortable: true },
        { key: "name", label: "Name", sortable: true },
        { key: "part", label: "Part", sortable: true },
        { key: "view", label: "View", sortable: true },
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
      centresItems: [],      
      onsiteItems: [],      
    };
  },
  created() {
    if (this.$route.query.user_id) {
      this.dh_user_id = this.$route.query.user_id
      this.table_shown = true
    }

    this.getRoles();
    this.getTestCategories();
    this.getOnsiteList();

    if (this.id) {
      this.fetchonsiteData();
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
        this.onsiteData[prop] = e.target.files[0];
      }
    },
    async getTestCategories() {
      try {

        this.loading = true;
        this.packageCategoriesOptions = [];
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
              'label': value.name
            })
        });

      } catch (err) {
        console.log(err);
      }
      this.loading = false;
    },
    async getOnsiteList() {

      try {
        this.pagination.dh_user_id = this.dh_user_id;      
        this.pagination.search_word = null
        if(this.search.value !== null && this.search.value.length > 3) {
          this.pagination.search_word = this.search.value
        }

        this.pagination.id = this.id;
        this.pagination.role = this.role;
        const { data } = await axios.get("/dh/onsitetests/list", {
          params: this.pagination,
        });

        this.onsiteItems = data.onsitetests_list;
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
    async selectpackageType() {
      const searchValue = (property_value, array) => {
        return array.filter((category) => category.id === this.onsiteData.package_category);
      }      

      let selected__package_type = searchValue(this.onsiteData.package_category, this.packageCategoriesOptions)[0]
      console.log(selected__package_type)
      this.onsiteData.package_type = selected__package_type.type
    },
    async onSubmit() {
      try {
        let data;
        let onsiteData = new FormData();

        if(this.onsiteData.id) {
          onsiteData.append("id", this.onsiteData.id);
        }

        if(this.onsiteData.test_category !== null) {
          onsiteData.append("test_category", this.onsiteData.test_category);
        }

        onsiteData.append("name", this.onsiteData.name);
        onsiteData.append("part", this.onsiteData.part);
        onsiteData.append("view", this.onsiteData.view);
        onsiteData.append("cost", this.onsiteData.cost);
        onsiteData.append("notes", this.onsiteData.notes);
        onsiteData.append("logo", this.logo);

        data = await axios.post("/dh/onsitetests/store", onsiteData, {
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

        this.onsiteData = {};
        this.$refs.previewLogoEl.src = '';
        this.$nextTick(() => this.$refs.refFormObserver.reset());
        this.getOnsiteList();

      } catch (error) {
        if (error.response) {
          this.errors = error.response.data.errors;
        }
      }
    },

    async fetchonsiteData() {
      try {
        const { data } = await axios.get(`/dh/${this.id}`);
        this.onsiteData = data.dh;
        if (this.onsiteData.service_areas) {
          this.onsiteData.service_areas = JSON.parse(data.dh.service_areas)
        } else {
          this.onsiteData.service_areas = []
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
          this.onsiteData.certificates.splice(index, 1);
        }
      } catch (error) {
        if (error.response) {
          this.errors = error.response.data.errors;
        }
      }
    },
    async toggleTable() {
      if(this.table_shown === false){
        this.table_shown = true;
        this.getOnsiteList();

      }else{
        this.table_shown = false      
      }
    },
    reloadPage() {
      window.location.reload();
    },
    addRow(field, index = null) {
      this.onsiteData["service_areas"].push({ address: '', pincode: '' });
    },
    removeRow(index, field) {
      console.log(this.onsiteData["service_areas"]);
      this.onsiteData["service_areas"].splice(index, 1);
      //this.onsiteData["service_areas"].push("");
      console.log(this.onsiteData["service_areas"]);
    },
    async editOnsiteItem(records) {
      this.onsiteData = records
      this.table_shown = false
      this.edit_mode = true
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
</style>
