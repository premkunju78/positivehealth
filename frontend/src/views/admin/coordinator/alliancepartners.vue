<template>
  <div>
    <b-card no-body class="mb-4">
      <div class="m-2">
        <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
          <!-- User Info: Input Fields -->
          <b-form @submit.prevent="handleSubmit(onSubmit)">
            <b-row class="d-flex">
              <b-col cols="12" md="4">
                <validation-provider
                  #default="validationContext"
                  name="Alliance Partner"
                  rules="required"
                >
                  <b-form-group
                    label="Alliance Partner"
                    label-for="alliance-partner"
                    :state="getValidationState(validationContext)"
                  >
                    <v-select
                      v-model="userData.alliance_partner"
                      :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                      :options="alliancePartners"
                      :reduce="(val) => val.id"
                      :clearable="false"
                      input-id="alliance-partner"
                    />
                    <b-form-invalid-feedback v-if="errors.alliance_partner">
                      {{ errors.alliance_partner[0] }}
                    </b-form-invalid-feedback>
                    <b-form-invalid-feedback
                      :state="getValidationState(validationContext)"
                      v-else
                    >
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                  </b-form-group>
                </validation-provider>
              </b-col>
              <b-col cols="12" md="2">
                <b-button
                  variant="primary"
                  :block="$store.getters['app/currentBreakPoint'] === 'xs'"
                  type="submit"
                  class="mt-2"
                >
                  Add
                </b-button>
              </b-col>
            </b-row>
          </b-form>
        </validation-observer>
      </div>
    </b-card>
    <b-card no-body class="mb-0">
      <div class="m-2">
        <!-- Table Top -->
        <b-row>
          <!-- Per Page -->
          <b-col
            cols="12"
            md="6"
            class="d-flex align-items-center justify-content-start mb-1 mb-md-0"
          >
          </b-col>
        </b-row>
      </div>

      <b-table
        ref="refUserListTable"
        class="position-relative"
        :items="items"
        responsive
        :fields="tableColumns"
        primary-key="id"
        :sort-by.sync="sortBy"
        :totalRows="totalRows"
        show-empty
        empty-text="No matching records found"
        :sort-desc.sync="isSortDirDesc"
      >
        <!-- Column: User -->
        <template #cell(user)="data">
          <b-media vertical-align="center">
            <template #aside>
              <b-avatar
                size="32"
                :src="data.item.avatar"
                :text="avatarText(data.item.name)"
                :variant="`light-primary`"
                :to="{ name: 'apps-users-view', params: { id: data.item.id } }"
              />
            </template>
            {{ data.item.name }}
          </b-media>
        </template>
        <template #cell(id)="data">
          {{ "AP-" + data.item.user_id }}
        </template>
        <!-- Column: Actions -->
        <template #cell(actions)="data">
          <div class="text-nowrap">
            <!-- Coordinator Modules -->
            <b-dropdown variant="link" no-caret :right="$store.state.appConfig.isRTL">
              <template #button-content>
                <feather-icon
                  icon="MoreVerticalIcon"
                  size="16"
                  class="align-middle text-body"
                  v-permission="['edit-coordinators']"
                />
              </template>
              <b-dropdown-item>
                <feather-icon icon="TrashIcon" />
                <span class="align-middle ml-50" @click="handleDeletePlan(data.item.id)"
                  >Delete</span
                >
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
    </b-card>
  </div>
</template>

<script>
import {
  BCard,
  BRow,
  BCol,
  BForm,
  BFormInput,
  BButton,
  BTable,
  BMedia,
  BAvatar,
  BLink,
  BFormGroup,
  BBadge,
  BDropdown,
  BDropdownItem,
  BPagination,
  BTooltip,
  VBModal,
  BModal,
  BFormInvalidFeedback,
} from "bootstrap-vue";

import { ValidationProvider, ValidationObserver } from "vee-validate";

import vSelect from "vue-select";

import formValidation from "@core/comp-functions/forms/form-validation";

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import { required } from "@validations";

export default {
  components: {
    BCard,
    BRow,
    BCol,
    BForm,
    BFormInput,
    BButton,
    BTable,
    BMedia,
    BAvatar,
    BLink,
    VBModal,
    BModal,
    BFormInvalidFeedback,
    BBadge,
    BDropdown,
    BDropdownItem,
    BPagination,
    BTooltip,
    vSelect,
    BFormGroup,
    BFormInput,

    ValidationProvider,
    ValidationObserver,
  },
  data() {
    const { refFormObserver, getValidationState, resetForm } = formValidation(
      this.resetData
    );
    return {
      items: [],
      alliancePartners: [],
      perPage: 10,
      perPageOptions: [10, 25, 50, 100],
      totalRows: "",
      search: "",
      searchQuery: "",
      limit: "10",
      sortBy: "1",
      isSortDirDesc: false,
      tableColumns: [
        { key: "id", label: "User Id", sortable: true },
        { key: "company_name", label: "Full Name", sortable: true },
        { key: "email", label: "Email", sortable: true },
        { key: "phone", label: "Contact Number", sortable: true },
        { key: "actions" },
      ],
      ucolumns: [
        { key: "index", label: "Sr. No.", sortable: true },
        { key: "company_name", label: "Name", sortable: true },
        { key: "email", label: "Email", sortable: true },
        { key: "phone", label: "Contact Number", sortable: true },
        { key: "actions", label: "Action", sortable: false },
      ],
      userData: {
        roles: [],
        alliance_partner: "",
      },
      pagination: {
        page: 1,
        per_page: 10,
        total: null,
      },
      roles: [],
      consultants: [],
      coordinator: null,
      list: [],
      refFormObserver,
      getValidationState,
      resetForm,
      required,
      errors: [],
    };
  },
  props: {
    id: {
      type: [Number, String, null],
      default: null,
    },
    role: {
      type: [String, null],
      default: null,
    },
  },
  created() {
    this.getList();
    this.getAlliancePartners();
  },
  mounted() {},
  methods: {
    async getList() {
      try {
        this.pagination.id = this.id;
        this.pagination.role = this.role;
        const { data } = await axios.get(`/coordinators/alliancepartners/${this.id}`, {
          params: this.pagination,
        });
        console.log(data);
        this.items = data.alliancepartners;
        this.pagination.per_page = data.per_page;
        this.pagination.total = data.total;
        this.pagination.page = data.page;
      } catch {
        toast({
          component: ToastificationContent,
          props: {
            title: "Error fetching coordinator list",
            icon: "AlertTriangleIcon",
            variant: "danger",
          },
        });
      }
    },
    handlePaginationChange(val) {
      this.pagination.page = val;
      this.getList();
    },
    async getAlliancePartners() {
      try {
        const { data } = await axios.get("/alliance-partner/list");
        this.alliancePartners = data.partners;
        console.log(data);
      } catch (err) {
        console.log(err);
      }
    },
    resetData() {
      this.userData = {
        alliance_partner: "",
      };
    },

    async onSubmit() {
      try {
        this.userData.coordinator = this.id;
        const { data } = await axios
          .post(`/coordinators/alliancepartners/${this.id}`, this.userData)
          .then((response) => {
            this.availabilityMessage = response.data.message;
          });
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "BellIcon",
            variant: data.success ? "success" : "danger",
          },
        });
        this.getList();
      } catch (err) {
        console.log(err);
      }
    },
    async handleDeletePlan(id) {
      try {
        const data = await axios.delete(`/coordinators/alliancepartner/${id}`);
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.data.message,
            icon: "BellIcon",
            variant: data.data.success ? "success" : "danger",
          },
        });
        this.getList();
      } catch (err) {
        console.log(err);
      }
    },
  },
};
</script>

<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";
@import "@core/scss/vue/libs/vue-select.scss";
@import "@core/scss/vue/libs/vue-flatpicker.scss";
.custom-radio {
  margin-top: 0 !important;
}
</style>
