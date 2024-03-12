<template>
  <div style="min-height: 300px">
    <b-overlay
      :show="$store.state.app.shallShowOverlay"
      no-wrap
      opacity="0.5"
      blur="1px"
    ></b-overlay>
    <b-card v-permission="['view-discount-coupons']">
      <b-row class="mb-2">
        <!-- Per Page -->
        <b-col
          cols="12"
          md="6"
          class="d-flex align-items-center justify-content-start mb-1 mb-md-0"
        >
          <label>Show</label>
          <v-select
            v-model="pagination.per_page"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="[10, 25, 50, 100, 1000]"
            :clearable="false"
            class="per-page-selector d-inline-block mx-50"
          />
          <label>entries</label>
        </b-col>

        <!-- Search -->
        <b-col cols="12" md="6">
          <div
            class="d-flex align-items-center justify-content-end"
            v-permission="['create-discount-coupons']"
          >
            <b-link :to="{ name: 'createDiscountCoupon' }">
              <b-button variant="primary">
                <span class="text-nowrap">Add New</span>
              </b-button>
            </b-link>
          </div>
        </b-col>
      </b-row>

      <b-table
        style="min-height: 250px"
        ref="refRolesTable"
        class="position-relative"
        :items="discounts"
        responsive
        :fields="columns"
        :totalRows="pagination.total"
        primaryKey="index"
        :sort-by.sync="sortBy"
        show-empty
        empty-text="No matching records found"
        :sort-desc.sync="isSortDirDesc"
      >
        <template #cell(index)="data">
          {{ data.index + 1 }}
        </template>
        <!-- Column: Actions -->
        <template #cell(actions)="data">
          <b-dropdown variant="link" no-caret :right="$store.state.appConfig.isRTL">
            <template #button-content>
              <feather-icon
                icon="MoreVerticalIcon"
                size="16"
                class="align-middle text-body"
                v-if="[1, $store.getters.user.id].includes(data.item.user_id)"
              />
            </template>
            <b-dropdown-item
              :to="{ name: 'editDiscountCoupon', params: { id: data.item.id } }"
              v-permission="['edit-discount-coupons']"
            >
              <feather-icon icon="EditIcon" />
              <span class="align-middle ml-50">Edit</span>
            </b-dropdown-item>
            <b-dropdown-item @click="viewAssignModal(data.item)">
              <feather-icon icon="UsersIcon" />
              <span class="align-middle ml-50">Assign</span>
            </b-dropdown-item>

            <!-- <b-dropdown-item  v-permission="['delete-discount-coupons']">
                            <feather-icon icon="TrashIcon"/>
                            <span class="align-middle ml-50">Delete</span>
                        </b-dropdown-item> -->
          </b-dropdown>
        </template>
      </b-table>
      <b-pagination
        v-if="pagination.total"
        v-model="pagination.page"
        :page.sync="pagination.page"
        :total-rows="pagination.total"
        :index="1"
        :per-page="pagination.per_page"
        aria-controls="video-row"
        align="right"
        @change="handlePaginationChange"
      >
      </b-pagination>
    </b-card>

    <b-modal
      v-permission="['view-clients']"
      id="assign"
      ref="assign"
      ok-only
      ok-title="Assign"
      @show="getCoordinators"
      @hidden="resetData"
      @ok="handleOk"
      centered
      size="lg"
      title="Coordinator Assignment"
      no-close-on-backdrop
    >
      <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
        <form ref="form" @submit.prevent="handleSubmit(onAssign)">
          <b-row>
            <b-col cols="12" md="6">
              <validation-provider
                #default="validationContext"
                name="coordinators"
                rules="required"
              >
                <b-form-group
                  label="Select Coordinator"
                  label-for="coordinators"
                  :state="getValidationState(validationContext)"
                >
                  <v-select
                    v-model="userData.coordinators"
                    :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="coordinators"
                    :reduce="(val) => val.id"
                    :clearable="false"
                    multiple
                    input-id="coordinators"
                  />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
          </b-row>
        </form>
      </validation-observer>
    </b-modal>
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
  BCardText,
  BTable,
  BRow,
  BCol,
  BLink,
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
} from "bootstrap-vue";
import { ValidationProvider, ValidationObserver } from "vee-validate";

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

import vSelect from "vue-select";

import Ripple from "vue-ripple-directive";

import formValidation from "@core/comp-functions/forms/form-validation";
import { required } from "@validations";

export default {
  components: {
    BCard,
    BCardText,
    BTable,
    BRow,
    BCol,
    BLink,
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
    required
  },
  data() {
    const { refFormObserver, getValidationState, resetForm } = formValidation(
      this.resetData,
      this.resetHcData
    );
    let self = this;
    return {
      pagination: {
        page: 1,
        per_page: 10,
        total: null,
      },
      discount: null,
      readonly: false,
      coordinators:[],
      userData: {
        coordinators: [],
      },
      refFormObserver,
      getValidationState,
      resetForm,
    };
  },
  directives: {
    Ripple,
  },
  setup() {
    const discounts = null;
    const loading = true;
    const sortBy = "1";
    const isSortDirDesc = false;
    const columns = [
      { key: "index", label: "Sr. No.", sortable: true },
      { key: "discount_code", label: "Discount Code", sortable: true },
      { key: "discount_type", label: "Type", sortable: true },
      { key: "discount_value", label: "Discount Value", sortable: false },
      { key: "start_date", label: "Start Date", sortable: false },
      { key: "end_date", label: "End Date", sortable: false },
      { key: "actions", label: "Actions", sortable: false },
    ];

    const options = null;
    return {
      discounts,
      options,
      loading,
      sortBy,
      isSortDirDesc,
      columns,
    };
  },
  watch: {
    "pagination.per_page": function (val) {
      this.handleFilterChange(val);
    },
  },
  created() {
    this.getList();
  },
  methods: {
    async getList() {
      try {
        this.loading = true;
        const { data } = await axios.get("/discountCoupon", {
          params: this.pagination,
        });
        this.discounts = data.coupons;
        this.pagination.per_page = data.per_page;
        this.pagination.total = data.total;
        this.pagination.page = data.page;
      } catch (err) {
        console.log(err);
      }
      this.loading = false;
    },

    handlePaginationChange(val) {
      this.pagination.page = val;
      this.getList();
    },
    handleFilterChange(val) {
      this.pagination.per_page = val;
      this.getList();
    },
    viewAssignModal(discount) {
      this.discount = discount;
      this.$refs["assign"].show();
    },
    async getCoordinators() {
      try {
        this.coordinators = [];
        const { data } = await axios.get("/coordinators/list", {
          params: {},
        });
        this.coordinators = data.users;
      } catch (error) {
        console.log(error);
      }
    },
    handleOk(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.$refs.refFormObserver.handleSubmit(this.onAssign);
    },
    async onAssign() {
      try {
        this.userData.coupon_id = this.discount.id;
        const { data } = await axios.post("/discountCoupon/assignment", this.userData);
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "BellIcon",
            variant: data.success ? "success" : "danger",
          },
        });
        this.$nextTick(() => {
          this.$refs["assign"].hide();
        });
      } catch (err) {
        console.log(err);
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.per-page-selector {
  width: 90px;
}
</style>

<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";
.razorpay-checkout-frame {
  height: 100% !important;
}
</style>
