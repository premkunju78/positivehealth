<template>
  <div style="min-height: 300px">
    <b-overlay
      :show="$store.state.app.shallShowOverlay"
      no-wrap
      opacity="0.5"
      blur="1px"
    ></b-overlay>
    <b-card :title="type">
      <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
        <form ref="form" @submit.prevent="handleSubmit(onSubmit)">
          <b-row>
            <b-col cols="12" md="4">
              <validation-provider
                #default="validationContext"
                name="Name"
                rules="required"
              >
                <b-form-group label="Name" label-for="name">
                  <b-form-input
                    id="name-input"
                    :state="getValidationState(validationContext)"
                    v-model="groupData.name"
                    :readonly="readonly"
                    :class="errors.name ? 'is-invalid' : ''"
                    :area-invalid="errors.name ? true : false"
                  />
                  <b-form-invalid-feedback v-if="errors.name">
                    {{ errors.name[0] }}
                  </b-form-invalid-feedback>
                  <b-form-invalid-feedback v-else>
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col cols="12" md="4">
              <!-- First Name -->
              <validation-provider
                #default="validationContext"
                name="Start Date"
                rules="required"
              >
                <b-form-group
                  label="Start Date"
                  label-for="start_date"
                  :state="getValidationState(validationContext)"
                >
                  <flat-pickr
                    v-model="groupData.start_date"
                    class="form-control"
                    :config="config.timePicker"
                  />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col cols="12" md="4">
              <!-- First Name -->
              <validation-provider
                #default="validationContext"
                name="End Date"
                rules="required"
              >
                <b-form-group
                  label="End Date"
                  label-for="end_date"
                  :state="getValidationState(validationContext)"
                >
                  <flat-pickr
                    v-model="groupData.end_date"
                    class="form-control"
                    :config="config.timePicker"
                  />
                  <b-form-invalid-feedback :state="getValidationState(validationContext)">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col cols="12" md="12">
              <validation-provider
                #default="validationContext"
                name="Description"
                rules="required"
              >
                <b-form-group label="Description" label-for="description">
                  <quill-editor
                    id="description"
                    v-model="groupData.description"
                    :options="editorOption"
                    :state="getValidationState(validationContext)"
                    rows="4"
                  />
                  <b-form-invalid-feedback>
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
          </b-row>
          <b-row style="margin-top: 2%">
            <!-- Action Buttons -->
            <b-button
              variant="primary"
              :block="$store.getters['app/currentBreakPoint'] === 'xs'"
              type="submit"
              class="mr-2"
            >
              Save Changes
            </b-button>
            <b-button
              v-ripple.400="'rgba(186, 191, 199, 0.15)'"
              type="button"
              @click="resetFormCard"
              variant="outline-secondary"
            >
              Cancel
            </b-button>
          </b-row>
        </form>
      </validation-observer>
    </b-card>
    <b-card>
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
        <b-col cols="12" md="6"> </b-col>
      </b-row>

      <b-table
        style="min-height: 250px"
        ref="refRolesTable"
        class="position-relative"
        :items="groups"
        responsive
        :fields="columns"
        :totalRows="pagination.total"
        primaryKey="index"
        :sort-by.sync="sortBy"
        show-empty
        empty-text="No matching records found"
        :sort-desc.sync="isSortDirDesc"
      >
        <!-- Column: Actions -->
        <template #cell(index)="data">
          {{ data.index + 1 }}
        </template>
        <template #cell(name)="data">
          <span class="text-primary">{{ data.item.name.toUpperCase() }}</span>
        </template>
        <template #cell(description)="data">
          <span class="text-primary" v-html="data.item.description"></span>
        </template>

        <!-- Column: Actions -->
        <template #cell(actions)="data">
          <b-dropdown variant="link" no-caret>
            <template #button-content>
              <feather-icon
                icon="MoreVerticalIcon"
                size="16"
                class="align-middle text-body"
              />
            </template>
            <b-dropdown-item @click="editGroup(data.item)">
              <feather-icon icon="EditIcon" />
              <span class="align-middle ml-50">Edit</span>
            </b-dropdown-item>
            <b-dropdown-item @click="viewUserModal(data.item)">
              <feather-icon icon="EyeIcon" />
              <span class="align-middle ml-50">View Clients</span>
            </b-dropdown-item>
            <b-dropdown-item @click="handleDeleteGroup(data.item.id)">
              <feather-icon icon="TrashIcon" />
              <span class="align-middle ml-50">Delete</span>
            </b-dropdown-item>
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
        aria-controls="package-row"
        align="right"
        @change="handlePaginationChange"
      >
      </b-pagination>
      <b-modal
        v-permission="['edit-program']"
        id="view-users"
        hide-footer
        centered
        size="lg"
        @show="getAssignedUsers"
        @hidden="resetUsers"
        title="Assigned Clients"
        ref="view-users"
        no-close-on-backdrop
      >
        <b-table
          style="min-height: 250px"
          ref="refRolesTable"
          class="position-relative"
          :items="users"
          responsive
          :fields="ucolumns"
          primaryKey="index"
          :sort-by.sync="sortBy"
          show-empty
          empty-text="No matching records found"
          :sort-desc.sync="isSortDirDesc"
          :sticky-header="true"
        >
          <!-- Column: Actions -->
          <template #cell(index)="data">
            {{ data.index + 1 }}
          </template>
          <!-- Column: Actions -->
          <template #cell(actions)="data">
            <a
              @click="handleRemoveUser(data.item.mapping_id)"
              style="cursor: pointer"
              title="remove client"
            >
              <feather-icon icon="TrashIcon" />
            </a>
          </template>
        </b-table>
      </b-modal>
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
  BFormGroup,
  BOverlay,
  BFormCheckbox,
  BFormDatepicker,
} from "bootstrap-vue";
import { ValidationProvider, ValidationObserver } from "vee-validate";

import formValidation from "@core/comp-functions/forms/form-validation";
import { required } from "@validations";

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

import vSelect from "vue-select";
import flatPickr from "vue-flatpickr-component";

import Ripple from "vue-ripple-directive";
import { ref } from "@vue/composition-api";

import "quill/dist/quill.snow.css";
import { quillEditor } from "vue-quill-editor";

export default {
  components: {
    BCard,
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
    BOverlay,
    BFormCheckbox,
    BFormDatepicker,
    flatPickr,
    quillEditor,
  },
  data() {
    var toolbarOptions = [
      ["bold", "italic", "underline", "strike"], // toggled buttons
      ['link', 'image'],
      [{ list: "ordered" }, { list: "bullet" }],
      [{ indent: "-1" }, { indent: "+1" }], // outdent/indent
      [{ direction: "rtl" }], // text direction
      [{ header: [1, 2, 3, 4, 5, 6, false] }],
      [{ color: [] }], // dropdown with defaults from theme
      [{ font: [] }],
      [{ align: [] }], // remove formatting button
    ];
    return {
      pagination: {
        page: 1,
        per_page: 10,
        total: null,
      },
      group: null,
      required,
      type: "Create Group",
      groupPackages: [],
      readonly: false,
      errors: [],
      packages: [],
      mappingData: {
        cost: {},
      },
      partners: [],
      list: [],
      users: [],
      totalRows: 0,
      config: {
        timePicker: {
          wrap: true,
          enableTime: false,
          dateFormat: 'd-m-Y'
          // noCalendar: true,
        },
      },
      editorOption: {
        modules: {
          toolbar: toolbarOptions,
        },
        theme: "snow",
      },
    };
  },
  directives: {
    Ripple,
  },
  setup() {
    const groups = null;
    const loading = true;
    const sortBy = "1";
    const isSortDirDesc = false;
    const columns = [
      { key: "index", label: "Sr. No.", sortable: true },
      { key: "name", label: "Group Name", sortable: true },
      { key: "description", label: "Group Description", sortable: true },
      { key: "actions" },
    ];
    const ucolumns = [
      { key: "index", label: "Sr. No.", sortable: true },
      { key: "user_name", label: "Client Name", sortable: true },
      { key: "actions", label: "Action", sortable: false },
    ];
    const groupData = ref({
      name: "",
      description: "",
      start_date: "",
      end_date: "",
    });

    const resetGroupData = function () {
      groupData.value = {
        name: "",
        description: "",
        start_date: "",
        end_date: "",
      };
    };
    const { refFormObserver, getValidationState, resetForm } = formValidation(
      resetGroupData
    );
    return {
      groups,
      loading,
      sortBy,
      isSortDirDesc,
      ucolumns,
      columns,
      groupData,
      refFormObserver,
      getValidationState,
      resetGroupData,
      resetForm,
    };
  },
  computed: {
    id: function () {
      return this.group.group_id;
    },
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
        const { data } = await axios.get("clientGroup", {
          params: this.pagination,
        });
        this.groups = data.groups;
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
    async onSubmit() {
      try {
        let data;
        if (this.groupData.id) {
          data = await axios.put(`/clientGroup/${this.groupData.id}`, this.groupData);
        } else {
          data = await axios.post("/clientGroup", this.groupData);
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

        this.getList();
      } catch (error) {
        this.errors = error.response.data.errors;
      }
    },

    async editGroup(group) {
      this.type = "Add Program";
      // this.readonly = true;
      this.groupData.id = group.id;
      this.groupData.name = group.name;
      this.groupData.description = group.description;
      this.groupData.start_date = group.start_date;
      this.groupData.end_date = group.end_date;
      // console.log(data)
      // if( data ){
      //     this.groupPackages = this.packages.filter( v => {
      //         return data.includes(v.id);
      //     });
      // }
    },
    viewUserModal(group) {
      this.group = group;
      this.$refs["view-users"].show();
    },

    async handleDeleteGroup(id) {
      try {
        const data = await axios.delete(`/clientGroup/${id}`);
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

    resetFormCard() {
      this.resetForm();
      this.groupData = {
        name: "",
        description: "",
        start_date: "",
        end_date: "",
      };
      this.groupPackages = [];
      this.type = "Create Group";
      this.readonly = false;
      this.errors = [];
    },
    async getAssignedUsers() {
      try {
        this.loading = true;
        const { data } = await axios.get("clientgroup/assignedusers", {
          params: { group_id: this.group.id },
        });
        this.users = data.users;
      } catch (err) {
        console.log(err);
      }
    },
    async handleRemoveUser(id) {
      try {
        const { data } = await axios.delete(`/assignment/${id}/user?type=clientGroups`);
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "BellIcon",
            variant: data.success ? "success" : "danger",
          },
        });
        this.getAssignedUsers();
      } catch (error) {
        this.$toast({
          component: ToastificationContent,
          props: {
            title: error.response.data.message,
            icon: "BellIcon",
            variant: "danger",
          },
        });
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
@import "@core/scss/vue/libs/vue-flatpicker.scss";
#assignUser___BV_modal_body_ {
  min-height: 200px !important;
  max-height: 300px !important;
  overflow-y: auto;
}
</style>
