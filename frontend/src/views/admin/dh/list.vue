<template>
  <div>
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

          <!-- Search -->
          <b-col cols="12" md="6">
            <div
              v-if="!id"
              class="d-flex align-items-center justify-content-end"
              v-permission="['create-diagnostichead']"
            >
              <!-- <b-form-input
                v-model="searchQuery"
                class="d-inline-block mr-1"
                placeholder="Search..."
              /> -->
              <router-link :to="{ name: 'dh-create' }">
                <b-button variant="primary" v-permission="['create-diagnostichead']">
                  <span class="text-nowrap">Add Diagnostic Partner</span>
                </b-button>
              </router-link>
            </div>
          </b-col>
        </b-row>
      </div>

      <b-modal id="alliance-partners-list" ref="alliance-partners-list" ok-only centered size="md" title="Associated Alliance Partners" hide-footer no-close-on-backdrop>
        <ul class="d-inline-block  pl-0 w-100" v-if="associated_alliance_partners">
          <li class="d-flex w-100 mb-1" v-for="ap in associated_alliance_partners">
            {{ ap.name }}
          </li>
        </ul>
        <span class="text-danger d-block text-center" v-else><strong>No City Found</strong>!</span>
      </b-modal>

      <b-modal id="view-location-availability" ref="user-location-availability" ok-only centered size="md" title="City wise availability" hide-footer no-close-on-backdrop>
        <ul class="d-inline-block pl-0 w-100" v-if="user_availability">
          <li class="d-flex w-100 mb-0" v-for="location in user_availability">
            <span class="mr-auto">{{ location.city }}</span>
            <b-dropdown variant="link" no-caret :right="$store.state.appConfig.isRTL">
              <template #button-content>
                <span class="badge caps" :class="location.status === 'active' ? 'badge-success' : 'badge-danger'">
                  {{ location.status }}
                </span>
              </template>
              <b-dropdown-item @click="updateLocationAvailability(location)"">
                <span class="align-middle ml-50" v-if="location.status === 'inactive'">Active</span>
                <span class="align-middle ml-50" v-if="location.status === 'active'">In-Active</span>
              </b-dropdown-item>
            </b-dropdown>
          </li>
        </ul>
        <span class="text-danger d-block text-center" v-else><strong>No City Found</strong>!</span>
      </b-modal>

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
         :tbody-tr-class="rowClass" 
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
          {{ "DH-" + data.item.id }}
        </template>
        <template #cell(alliancepartners)="data">
          <div class="text-nowrap">
            <p class="align-text-top text-capitalize m-0" v-for="name in data.item.alliancepartners">{{ name }}</p>
          </div>
        </template>

        <template #cell(dh_status)="data">          

          <b-button class="btn-sm" variant="info" @click="viewAvailability(data.item)">
            <span class="text-nowrap">Cities Presence</span>
          </b-button>                

        </template>

        <template #cell(assigned_alliance)="data">          

          <b-button class="btn-sm" variant="success" @click="viewAssignedAlliance(data.item)">
            <span class="text-nowrap">
              <feather-icon
                icon="EyeIcon"
                size="16"
                color="#FFFFFF"
              />
            </span>
          </b-button>                

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
              <!-- <b-dropdown-item :to="{ name: 'apps-users-view', params: { id: data.item.id } }" >
                <feather-icon icon="FileTextIcon" />
                <span class="align-middle ml-50">Details</span>
              </b-dropdown-item> -->

              <b-dropdown-item
                :to="{
                  name: 'edit-dh',
                  params: { id: data.item.id, user: data.item },
                }"
              >
                <feather-icon icon="EditIcon" />
                <span class="align-middle ml-50">Edit</span>
              </b-dropdown-item>
              <b-dropdown-item
                :to="{
                  name: 'items-investigations',
                  query: { user_id: data.item.id },
                }"
                v-if="data.item.investigation_status === 'completed'"
              >
                <feather-icon icon="EyeIcon" />
                <span class="align-middle ml-50">View Investigations</span>
              </b-dropdown-item>
              <b-dropdown-item
                :to="{
                  name: 'items-healthpackages',
                  query: { user_id: data.item.id },
                }"
                v-if="data.item.healthpackage_status === 'completed'"
              >
                <feather-icon icon="EyeIcon" />
                <span class="align-middle ml-50">View Health Packages</span>
              </b-dropdown-item>
              <b-dropdown-item
                :to="{
                  name: 'items-onsitetests',
                  query: { user_id: data.item.id },
                }"
                v-if="data.item.onsites_status === 'completed'"
              >
                <feather-icon icon="EyeIcon" />
                <span class="align-middle ml-50">View Onsite Tests</span>
              </b-dropdown-item>
              <b-dropdown-item
                :to="{
                  name: 'items-centres',
                  query: { user_id: data.item.id },
                }"
                v-if="data.item.centres_status === 'completed'"
              >
                <feather-icon icon="EyeIcon" />
                <span class="align-middle ml-50">View Centres Availability</span>
              </b-dropdown-item>
              <b-dropdown-item
                :to="{
                  name: 'samplecollector',
                  query: { user_id: data.item.id },
                }"
              >
                <feather-icon icon="EyeIcon" />
                <span class="align-middle ml-50">View Sample Collector</span>
              </b-dropdown-item>
              <b-dropdown-item
                :to="{
                  name: 'dataentryuser',
                  query: { user_id: data.item.id },
                }"
              >
                <feather-icon icon="EyeIcon" />
                <span class="align-middle ml-50">View DataEntry User</span>
              </b-dropdown-item>

              <!--
              <b-dropdown-item
                :to="{ name: 'coodinator-alliance-view', params: { id: data.item.id },query: {name: data.item.name, for_dh: true } }"
                v-if="available_ap === true && data.item.assigned_alliance === null"
              >
                <feather-icon icon="UsersIcon" />
                <span class="align-middle ml-50">Assign AP</span>
              </b-dropdown-item>
              -->

              <b-dropdown-item v-if="$store.getters.user.role_id == 1" @click="deleteMh(data.item)">
                <feather-icon icon="TrashIcon" />
                <span class="align-middle ml-50">Delete</span>
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
        { key: "name", label: "Full Name", sortable: true },
        { key: "email", label: "Email", sortable: true },
        { key: "phone", label: "Contact Number", sortable: true },
        { key: "assigned_alliance", label: "AP", sortable: true },
        { key: "dh_status", label: "Availability", sortable: true },
        { key: "actions" },
      ],
      ucolumns: [
        { key: "index", label: "Sr. No.", sortable: true },
        { key: "name", label: "Name", sortable: true },
        { key: "email", label: "Email", sortable: true },
        { key: "phone", label: "Contact Number", sortable: true },
        { key: "assigned_alliance", label: "Alliance Partener", sortable: true },
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
      dh: null,
      list: [],
      refFormObserver,
      getValidationState,
      resetForm,
      required,
      availability_for: null,
      available_ap: true,
      user_availability: [],
      associated_alliance_partners: [],
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
  },
  methods: {
    async getList() {
      try {
        this.pagination.id = this.id;
        this.pagination.role = this.role;
        const { data } = await axios.get("/dh", {
          params: this.pagination,
        });
        console.log(data.dh);
        this.items = data.dh;
        this.pagination.per_page = data.per_page;
        this.pagination.total = data.total;
        this.pagination.page = data.page;
        this.available_ap = data.available_ap;
      } catch {
        toast({
          component: ToastificationContent,
          props: {
            title: "Error fetching dh list",
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
    rowClass(item, type) {
      if (item && type === "row") {
        if (
        item.investigation_status === "completed" 
        && item.healthpackage_status === "completed" 
        && item.onsites_status === "completed" 
        && item.centres_status === "completed" 
        ) {
          return "tr-completed";
        } else {
          return "tr-pending";
        }
      } else {
        return null;
      }
    },
    async viewAvailability(record) {
      this.availability_for = record.id;
      this.user_availability = JSON.parse(record.available_cities);
      this.$refs["user-location-availability"].show();
    },
    async updateLocationAvailability(location) {
      try {
        const { data } = await axios.post(`/users/update-location-availability/${this.availability_for}`,
          { 
            city: location.city,
            status: location.status 
          }
        );

        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "EditIcon",
            variant: data.success ? "success" : "info",
          },
        });

        this.$refs["user-location-availability"].hide();
        this.getList();

      } catch (error) {
        if (error.response) {
          this.errors = error.response.data.errors;
        }
      }
    },
    async viewAssignedAlliance(record){
      try {
        const { data } = await axios.get(`/dh/getalliancepartners/${record.id}`);
        this.associated_alliance_partners = data.alliancepartners;
        this.$refs["alliance-partners-list"].show();

      } catch (error) {
        if (error.response) {
          this.errors = error.response.data.errors;
        }
      }
    },
    async deleteMh(dh) {
      if (confirm("Do you really want to delete?")) {
        try {
          let data;
          data = await axios.delete(`/dh/${dh.id}`);
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

.caps {
  text-transform: capitalize;
}

.tr-pending {
  background: beige;
}

.tr-completed {
  background: #58cd5c54;
  color: #000;
}

</style>
