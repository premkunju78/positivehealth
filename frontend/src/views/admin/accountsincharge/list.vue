<template>
  <div>
    <b-card no-body class="mb-0">
      <div class="m-2">
        <!-- Table Top -->
        <b-row>
          <!-- Per Page -->
          <b-col cols="12" md="6" class="d-flex align-items-center justify-content-start mb-1 mb-md-0">
          </b-col>

          <!-- Search -->
          <b-col cols="12" md="6">
            <div v-if="!id" class="d-flex align-items-center justify-content-end"
              v-permission="['create-marketingexecutive']">
              <!-- <b-form-input
                v-model="searchQuery"
                class="d-inline-block mr-1"
                placeholder="Search..."
              /> -->
              <router-link :to="{ name: 'accountsincharge-create' }">
                <b-button variant="primary" v-permission="['create-marketingexecutive']">
                  <span class="text-nowrap">Add Account Incharge</span>
                </b-button>
              </router-link>
            </div>
          </b-col>
        </b-row>
      </div>

      <b-table ref="refUserListTable" class="position-relative" :items="items" responsive :fields="tableColumns"
        primary-key="id" :sort-by.sync="sortBy" :totalRows="totalRows" show-empty empty-text="No matching records found"
        :sort-desc.sync="isSortDirDesc">
        <!-- Column: User -->
        <template #cell(user)="data">
          <b-media vertical-align="center">
            <template #aside>
              <b-avatar size="32" :src="data.item.avatar" :text="avatarText(data.item.name)" :variant="`light-primary`"
                :to="{ name: 'apps-users-view', params: { id: data.item.id } }" />
            </template>
            {{ data.item.name }}
          </b-media>
        </template>
        <template #cell(id)="data">
          {{ "ME-" + data.item.id }}
        </template>
        <template #cell(alliancepartners)="data">
          <div class="text-nowrap">
            <p class="align-text-top text-capitalize m-0" v-for="name in data.item.alliancepartners">{{ name }}</p>
          </div>
        </template>

        <!-- Column: Actions -->
        <template #cell(actions)="data">
          <div class="text-nowrap">
            <b-dropdown variant="link" no-caret :right="$store.state.appConfig.isRTL"
              v-permission="['edit-marketingexecutive']">
              <template #button-content>
                <feather-icon icon="MoreVerticalIcon" size="16" class="align-middle text-body"
                  v-permission="['edit-marketingexecutive']" />
              </template>
              <!-- <b-dropdown-item :to="{ name: 'apps-users-view', params: { id: data.item.id } }" >
                <feather-icon icon="FileTextIcon" />
                <span class="align-middle ml-50">Details</span>
              </b-dropdown-item> -->

              <b-dropdown-item :to="{
                name: 'edit-me',
                params: { id: data.item.id, user: data.item },
              }" v-permission="['edit-marketingexecutive']">
                <feather-icon icon="EditIcon" />
                <span class="align-middle ml-50">Edit</span>
              </b-dropdown-item>
              <b-dropdown-item v-if="$store.getters.user.role_id == 1" @click="deleteMe(data.item)">
                <feather-icon icon="EditIcon" />
                <span class="align-middle ml-50">Delete</span>
              </b-dropdown-item>
              <b-dropdown-item
                :to="{ name: 'coodinator-alliance-view', params: { id: data.item.id }, query: { name: data.item.name } }"
                v-permission="['view-me']">
                <feather-icon icon="UsersIcon" />
                <span class="align-middle ml-50">Assign</span>
              </b-dropdown-item>
              <!-- <b-dropdown-item @click="viewUserModal(data.item)"  v-permission="['edit-coordinators']">
                <feather-icon icon="EyeIcon" />
                <span class="align-middle ml-50">View Consultants</span>
              </b-dropdown-item> -->
              <!-- <b-dropdown-item  v-permission="['delete-users']">
                <feather-icon icon="TrashIcon"/>
                <span class="align-middle ml-50">Delete</span>
              </b-dropdown-item> -->
            </b-dropdown>
          </div>
        </template>
      </b-table>
      <b-pagination v-if="pagination.total" v-model="pagination.page" :page.sync="pagination.page"
        :total-rows="pagination.total" :index="1" :per-page="pagination.per_page" aria-controls="package-row"
        align="right" @change="handlePaginationChange">
      </b-pagination>
    </b-card>

  </div>
</template>

<script>
import {
  BCard,
  BRow,
  BCol,
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
        { key: "gender", label: "Gender", sortable: true },
        { key: "email", label: "Email ID", sortable: true },
        { key: "phone", label: "Contact Number", sortable: true },
        { key: "alliancepartners", label: "Alliance Partners", sortable: true },
        { key: "actions" },
      ],
      ucolumns: [
        { key: "index", label: "Sr. No.", sortable: true },
        { key: "name", label: "Name", sortable: true },
        { key: "email", label: "Email", sortable: true },
        { key: "phone", label: "Contact Number", sortable: true },
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
      roles: [],
      consultants: [],
      me: null,
      list: [],
      refFormObserver,
      getValidationState,
      resetForm,
      required,
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
    this.getColumns();
  },
  methods: {
    getColumns() {
      if (this.$store.getters.user.role_id === 15) {
        this.tableColumns = [
          { key: "id", label: "Id", sortable: true },
          { key: "name", label: "Name", sortable: true },
          { key: "gender", label: "Gender", sortable: true },
          { key: "email", label: "Email ID", sortable: true },
          { key: "phone", label: "Contact Number", sortable: true },
          { key: "actions" },
        ];
      }
    },
    async getList() {
      try {
        this.pagination.id = this.id;
        this.pagination.role = this.role;
        const { data } = await axios.get("/accountsincharge", {
          params: this.pagination,
        });
        console.log(data.me);
        this.items = data.me;
        this.pagination.per_page = data.per_page;
        this.pagination.total = data.total;
        this.pagination.page = data.page;
      } catch {
        toast({
          component: ToastificationContent,
          props: {
            title: "Error fetching me list",
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


    async getRoles() {
      try {
        const { data } = await axios.get("/adminRoles/list");
        console.log(data);
        this.roles = data.roles;
      } catch (error) {
        console.log(error);
      }
    },

    async deleteMe(me) {
      if (confirm("Do you really want to delete?")) {
        try {
          let data;
          data = await axios.delete(`/accountsincharge/${me.id}`);
          this.$toast({
            component: ToastificationContent,
            props: {
              title: data.data.message,
              icon: "EditIcon",
              variant: data.data.success ? "success" : "info"
            }
          });
          this.getList();
        } catch (error) {

        }
      }
    },
  },
};
</script>

<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";

#assignUser___BV_modal_body_ {
  min-height: 200px !important;
  max-height: 300px !important;
  overflow-y: auto;
}
</style>
