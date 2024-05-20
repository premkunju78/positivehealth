<template>
  <div>
    <user-list-add-new :is-add-new-user-sidebar-active.sync="isAddNewUserSidebarActive" :role-options="roleOptions"
      :user="user" @refetch-data="refetchData" />

    <!-- Table Container Card -->
    <b-card no-body class="mb-0">
      <div class="m-2">
        <b-row class="mb-2">
          <b-col cols="8" md="3" sm="6" class="justify-content-start mb-1 mb-md-0">
            <b-form-group label="Name" label-for="name">
              <b-form-input id="name" v-model="pagination.filters.name" placeholder="Search by name..."
                @input="applyFilters" />
            </b-form-group>
          </b-col>
          <b-col cols="8" md="3" sm="6" class="justify-content-start mb-1 mb-md-0">
            <b-form-group label="Role Type" label-for="role">
              <v-select v-model="pagination.filters.specialization" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="specializations" :clearable="true" @input="applyFilters" />
            </b-form-group>
          </b-col>
          <b-col cols="8" md="3" sm="6" class="justify-content-start mb-1 mb-md-0">
            <b-form-group label="Sub Role" label-for="role">
              <v-select v-model="pagination.filters.role" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="roles" :reduce="(val) => val.id" :clearable="true" @input="applyFilters" />
            </b-form-group>
          </b-col>
          <b-col cols="8" md="3" sm="6" class="justify-content-start mb-1 mb-md-0">
            <b-form-group label="Specialization" label-for="role">
              <v-select v-model="pagination.filters.specialized_in" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="specialized" :clearable="true" @input="applyFilters" />
            </b-form-group>
          </b-col>
        </b-row>
        <!-- Table Top -->
        <b-row>
          <!-- Per Page -->
          <b-col cols="12" md="6" class="d-flex align-items-center justify-content-start mb-1 mb-md-0">
            <label>Show</label>
            <v-select v-model="pagination.per_page" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
              :options="[10, 25, 50, 100, 1000]" :clearable="false" class="per-page-selector d-inline-block mx-50" />
            <label>entries</label>
          </b-col>

          <!-- Search -->
          <b-col cols="12" md="6">
            <div class="d-flex align-items-center justify-content-end">
              <!-- <b-form-input
                v-model="searchQuery"
                class="d-inline-block mr-1"
                placeholder="Search..."
              /> -->
              <b-link :to="{ name: 'addConsultant' }" v-permission="['create-users']">
                <b-button variant="primary">
                  <span class="text-nowrap">Add New Consultant</span>
                </b-button>
              </b-link>

              <!-- <b-button
                variant="primary"
                  @click="addUser"
                  v-permission="['create-users']"
              >
                <span class="text-nowrap">Add New</span>
              </b-button> -->
            </div>
          </b-col>
        </b-row>
      </div>
      <div v-if="role == 10 || role == 11" class="pl-4">
        <b-row>
          <b-col :sm="5" :md="4" :lg="3" :xl="3" v-for="consultant in users" :key="consultant.id">
            <b-card class="ecommerce-card" style="width: 99%; height:350px" no-body>
              <!-- Product Details -->
              <img :src="'/view/file?path=' + consultant.avatar" alt="Card image cap" class="card-img-top"
                style="height:160px">
              <b-card-body>
                <div class="align-items-center justify-content-center">
                  <b-row class="mb-2">

                    <b-col cols="10">
                      <h5 class="mb-1 text-info heading" style="font-weight:bolder" :title="consultant.name">
                        {{ consultant.name }}
                      </h5>
                    </b-col>
                    <b-col cols="10" class="mb-1 heading">
                      <span class="text-primary">
                        {{ consultant.specialized_in }}
                      </span>
                    </b-col>
                    <b-col cols="10">
                      <small style="font-weight:550"> {{ consultant.experience ? consultant.experience + 'Years Of experience' : ''}} </small>
                    </b-col>

                  </b-row>
                </div>
                <div class="d-flex align-items-center item-rating justify-content-center footer-button">
                  <b-button variant="info mr-1" size="sm"
                    :to="{ name: 'viewConsultant', params: { 'id': consultant.id } }">
                    <span style="color:#fff" class="text-nowrap">Know More</span>
                  </b-button>
                  <b-button variant="primary" size="sm" v-if="role === 3">
                    <b-link style="color:#fff" class="text-nowrap"
                      :to="{ name: 'book-appointments', params: { id: consultant.id, role: consultant.role_id } }">
                      Consult
                    </b-link>
                  </b-button>
                  <span></span>
                </div>
              </b-card-body>


            </b-card>
          </b-col>
        </b-row>

        <b-pagination v-if="pagination.total" v-model="pagination.page" :page.sync="pagination.page"
          :total-rows="pagination.total" :index="1" :per-page="pagination.per_page" aria-controls="video-row"
          align="right" @change="handlePaginationChange">
        </b-pagination>
      </div>
      <div v-else>
        <b-table ref="refUserListTable" class="position-relative" :items="users" responsive :fields="tableColumns"
          primary-key="id" :sort-by.sync="sortBy" show-empty empty-text="No matching records found"
          :sort-desc.sync="isSortDirDesc">
          <!-- Column: User -->
          <template #cell(user)="data">
            <b-media vertical-align="center">
              <template #aside>
                <b-avatar size="32" :src="data.item.avatar" :text="avatarText(data.item.name)"
                  :variant="`light-${resolveUserRoleVariant(data.item.role)}`"
                  :to="{ name: 'apps-users-view', params: { id: data.item.id } }" />
              </template>
              {{ data.item.name }}
            </b-media>
          </template>
          <template #cell(id)="data">
            {{ "CN-" + data.item.id }}
          </template>
          <!-- Column: Role -->
          <template #cell(role)="data">
            <div class="text-nowrap">
              <feather-icon :icon="resolveUserRoleIcon(data.item.role)" size="18" class="mr-50"
                :class="`text-${resolveUserRoleVariant(data.item.role)}`" />
              <span class="align-text-top text-capitalize">{{ data.item.role }}</span>
            </div>
          </template>
          <!-- Column: Role -->
          <template #cell(status)="{ item }">
            <div v-if="['Medical', 'Wellness Provider'].includes(item.specialization)" v-permission="['edit-users']">
              <b-button v-if="item.is_upgraded" variant="info" :block="$store.getters['app/currentBreakPoint'] === 'xs'"
                type="button" @click="viewDowngradeModal(item)" class="mr-2">
                Upgraded
              </b-button>
              <b-button v-else variant="danger" :block="$store.getters['app/currentBreakPoint'] === 'xs'" type="button"
                @click="viewUpgradeModal(item)" class="mr-2">
                Regular
              </b-button>
            </div>
            <div v-else></div>
          </template>

          <!-- Column: Actions -->
          <template #cell(actions)="data">
            <b-dropdown variant="link" no-caret :right="$store.state.appConfig.isRTL"
              v-permission="['edit-users', 'delete-users']">
              <template #button-content>
                <feather-icon icon="MoreVerticalIcon" size="16" class="align-middle text-body"
                  v-if="![1, $store.getters.user.id].includes(data.item.id)" />
              </template>
              <b-dropdown-item :to="{ name: 'mapped-clients', params: { id: data.item.id, role: 'consultant' } }">
                <feather-icon icon="EyeIcon" />
                <span class="align-middle ml-50">View Clients</span>
              </b-dropdown-item>

              <b-dropdown-item :to="{ name: 'editConsultant', params: { id: data.item.id } }"
                v-permission="['edit-users']">
                <feather-icon icon="EditIcon" />
                <span class="align-middle ml-50">Edit</span>
              </b-dropdown-item>
              <b-dropdown-item v-if="$store.getters.user.role_id == 1" @click="deleteConsultant(data.item)">
                <feather-icon icon="EditIcon" />
                <span class="align-middle ml-50">Delete</span>
              </b-dropdown-item>
              <b-dropdown-item @click="viewAssignModal(data.item)" v-permission="['edit-users']"
                v-if="data.item.is_upgraded">
                <feather-icon icon="UsersIcon" />
                <span class="align-middle ml-50">Link alliance partner</span>
              </b-dropdown-item>
              <b-dropdown-item @click="viewRemoveModal(data.item)" v-permission="['edit-users']">
                <feather-icon icon="UsersIcon" />
                <span class="align-middle ml-50">Remove consultant from list</span>
              </b-dropdown-item>
              <!-- <b-dropdown-item  v-permission="['delete-users']">
              <feather-icon icon="TrashIcon"/>
              <span class="align-middle ml-50">Delete</span>
            </b-dropdown-item> -->
            </b-dropdown>
          </template>
        </b-table>
        <b-pagination v-if="pagination.total" v-model="pagination.page" :page.sync="pagination.page"
          :total-rows="pagination.total" :index="1" :per-page="pagination.per_page" aria-controls="package-row"
          align="right" @change="handlePaginationChange">
        </b-pagination>
      </div>
    </b-card>
    <b-modal ref="upgrade-consultant" centered size="ms" ok-title="Upgrade" title="Upgrade Consultant"
      no-close-on-backdrop @hidden="resetData" @ok="handleUpgrade">
      <b-row>
        <b-col sm="12">
          <p>By Upgrading The Consultant Will Have Access To :</p>
          <ol>
            <li>Leads : <small>manage leads</small></li>
            <li>CRS : <small>Cross Referral System</small></li>
          </ol>
        </b-col>
      </b-row>
    </b-modal>
    <b-modal ref="downgrade-consultant" centered size="ms" ok-title="Update" title="Update Consultant Status"
      no-close-on-backdrop @hidden="resetData" @ok="handleDowngrade">
      <b-row>
        <b-col sm="12">
          <p>By updating the consultant status to Regular, He/She Will No Longer Have Access To :</p>
          <ol>
            <li>Leads : <small>manage leads</small></li>
            <li>CRS : <small>Cross Referral System</small></li>
          </ol>
        </b-col>
      </b-row>
    </b-modal>
    <b-modal v-permission="['edit-consultants']" id="assign" ref="assign" ok-only ok-title="Assign"
      @show="getAlliancePartners" @hidden="resetData" @ok="handleOk" centered size="lg" title="Link Alliance Partner"
      no-close-on-backdrop>
      <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
        <form ref="form" @submit.prevent="handleSubmit(onAssign)">
          <b-row>
            <b-col cols="12" md="12">
              <validation-provider #default="validationContext" name="alliance_partner" rules="required">
                <b-form-group label="Select Alliance Partner" label-for="healthcoaches"
                  :state="getValidationState(validationContext)">
                  <v-select v-model="linkData.alliance_partner" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="alliancePartners" :reduce="(val) => val.id" :clearable="false" input-id="leads" />
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

    <b-modal v-permission="['edit-consultants']" id="removem" ref="removem" ok-only ok-title="Remove"
      @show="getOtherConsultants" @hidden="resetRemoveData" @ok="handleRemoveOk" centered size="lg"
      title="Remove consultant from list" no-close-on-backdrop>
      <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
        <form ref="form" @submit.prevent="handleSubmit(onRemove)">
          <b-row>
            <b-col cols="12" md="12">
              <validation-provider #default="validationContext" name="consultant" rules="required">
                <b-form-group label="Select Consultant" label-for="consultants"
                  :state="getValidationState(validationContext)">
                  <v-select v-model="removeData.consultant" :multiple="true" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                    :options="otherconsultants" :reduce="(val) => val.id" :clearable="false" input-id="leads" />
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
  BBadge,
  BDropdown,
  BDropdownItem,
  BPagination,
  BFormGroup,
  BFormDatepicker,
} from "bootstrap-vue";
import vSelect from "vue-select";
import store from "@/store";
import { ref, onUnmounted } from "@vue/composition-api";
import { avatarText } from "@core/utils/filter";
import UsersListFilters from "./users-list/UsersListFilters.vue";
import useUsersList from "./users-list/useUsersList";
import userStoreModule from "./userStoreModule";
import UserListAddNew from "./users-list/UserListAddNew.vue";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import formValidation from "@core/comp-functions/forms/form-validation";
import { required } from "@validations";
import { ValidationProvider, ValidationObserver } from "vee-validate";

export default {
  components: {
    UsersListFilters,
    UserListAddNew,

    BCard,
    BRow,
    BCol,
    BFormInput,
    BButton,
    BTable,
    BMedia,
    BAvatar,
    BLink,
    BBadge,
    BDropdown,
    BDropdownItem,
    BPagination,
    BFormGroup,
    BFormDatepicker,
    ValidationProvider,
    ValidationObserver,
    vSelect,
  },
  data() {
    const { refFormObserver, getValidationState, resetForm } = formValidation();
    return {
      pagination: {
        page: 1,
        per_page: 10,
        total: null,
        filters: {},
      },
      roles: [],
      users: [],
      otherconsultants:[],
      user: null,
      loading: false,
      specialized: [],
      specializations: ["Medical", "Wellness Provider", "Others"],
      authorizations: ["Internal", "External"],
      consultant: {},
      linkData: {
        alliance_partner: "",
      },
      removeData: {
        consultant: [],
      },
      refFormObserver,
      getValidationState,
      resetForm,
      required,
      alliancePartners: [],
    };
  },
  computed: {
    role: function () {
      return this.$store.state.auth.user.role_id;
    }
  },
  setup() {
    const USER_APP_STORE_MODULE_NAME = "app-user";

    // Register module
    if (!store.hasModule(USER_APP_STORE_MODULE_NAME))
      store.registerModule(USER_APP_STORE_MODULE_NAME, userStoreModule);

    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(USER_APP_STORE_MODULE_NAME))
        store.unregisterModule(USER_APP_STORE_MODULE_NAME);
    });

    const isAddNewUserSidebarActive = ref(false);

    const roleOptions = [
      { label: "Admin", value: "admin" },
      { label: "Author", value: "author" },
      { label: "Editor", value: "editor" },
      { label: "Maintainer", value: "maintainer" },
      { label: "Subscriber", value: "subscriber" },
    ];

    const statusOptions = [
      { label: "Pending", value: "pending" },
      { label: "Active", value: "active" },
      { label: "Inactive", value: "inactive" },
    ];

    const {
      fetchUsers,
      tableColumns,
      perPage,
      currentPage,
      totalUsers,
      dataMeta,
      perPageOptions,
      searchQuery,
      sortBy,
      isSortDirDesc,
      refUserListTable,
      refetchData,

      // UI
      resolveUserRoleVariant,
      resolveUserRoleIcon,
      resolveUserStatusVariant,

      // Extra Filters
      roleFilter,
      planFilter,
      statusFilter,
    } = useUsersList();

    return {
      // Sidebar
      isAddNewUserSidebarActive,

      fetchUsers,
      tableColumns,
      perPage,
      currentPage,
      totalUsers,
      dataMeta,
      perPageOptions,
      searchQuery,
      sortBy,
      isSortDirDesc,
      refUserListTable,
      refetchData,

      // Filter
      avatarText,

      // UI
      resolveUserRoleVariant,
      resolveUserRoleIcon,
      resolveUserStatusVariant,

      roleOptions,
      statusOptions,

      // Extra Filters
      roleFilter,
      planFilter,
      statusFilter,
    };
  },
  created() {
    this.getList();
    this.getRoles();
    this.getSpecialized();
  },
  methods: {
    handleOk(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.$refs.refFormObserver.handleSubmit(this.onAssign);
    },
    handleRemoveOk(bvModalEvt) {
      bvModalEvt.preventDefault();
      this.$refs.refFormObserver.handleSubmit(this.onRemove);
    },
    viewAssignModal(consultant) {
      this.consultant = consultant;
      this.linkData.alliance_partner = consultant.alian_partner_id;
      this.$refs["assign"].show();
    },
    viewRemoveModal(consultant) {
      this.consultant = consultant;
      this.$refs["removem"].show();
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
    async getOtherConsultants() {
      try {
        const { data } = await axios.get("/consultants/list",{
          params:{
            consultant_id:this.consultant.id,
          }
        });
        this.otherconsultants = data.users;
        this.getHideConsultants(this.consultant.id);
        console.log(data);
      } catch (err) {
        console.log(err);
      }
    },
    async getHideConsultants(id) {
      try {
        const { data } = await axios.get("/consultant/"+id,{
        });
        this.removeData.consultant = JSON.parse(data.user.hide_consultants);
        console.log(data);
      } catch (err) {
        console.log(err);
      }
    },
    async onAssign() {
      try {
        this.linkData.consultant = this.consultant.id;
        const { data } = await axios.post("/consultants/allianassignment", this.linkData);
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
        this.getList();
      } catch (err) {
        console.log(err);
      }
    },
    async onRemove() {
      try {
        this.removeData.consultant_id = this.consultant.id;
        const { data } = await axios.post("/consultants/removeconsultant", this.removeData);
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "BellIcon",
            variant: data.success ? "success" : "danger",
          },
        });
        this.$nextTick(() => {
          this.$refs["removem"].hide();
        });
      } catch (err) {
        console.log(err);
      }
    },
    
    async getRoles() {
      try {
        const { data } = await axios.get("/roles/list");
        this.roles = data.roles;
        console.log(this.roles);
      } catch (err) {
        console.log(err);
      }
    },
    async getSpecialized(val = null) {
      try {
        this.loading = true;
        const { data } = await axios.get("consultants/specialized", {
          params: {
            specialization: val,
          },
        });
        this.specialized = data.specialized;
      } catch (err) {
        console.log(err);
      }
    },
    async getList() {
      try {
        this.loading = true;
        const { data } = await axios.get("/consultants", {
          params: this.pagination,
        });
        this.users = data.users;
        this.pagination.per_page = data.per_page;
        this.pagination.total = data.total;
        this.pagination.page = data.page;
      } catch (err) {
        console.log(err);
      }
      this.loading = false;
    },
    editUser(data) {
      this.user = data;
      this.isAddNewUserSidebarActive = true;
    },
    addUser() {
      this.user = {};
      this.isAddNewUserSidebarActive = true;
    },
    handlePaginationChange(val) {
      this.pagination.page = val;
      this.getList();
    },
    viewUpgradeModal(user) {
      this.user = user;
      this.$refs["upgrade-consultant"].show();
    },
    viewDowngradeModal(user) {
      this.user = user;
      this.$refs["downgrade-consultant"].show();
    },
    async handleUpgrade() {
      try {
        const { data } = await axios.put(`/consultants/${this.user.id}/upgrade`, {
          is_upgraded: true,
        });

        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "EditIcon",
            variant: data.success ? "success" : "info",
          },
        });
        this.getList();
      } catch (error) {
        console.log(error);
      }
    },
    async handleDowngrade() {
      try {
        const { data } = await axios.put(`/consultants/${this.user.id}/upgrade`, {
          is_upgraded: false,
        });

        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "EditIcon",
            variant: data.success ? "success" : "info",
          },
        });
        this.getList();
      } catch (error) {
        console.log(error);
      }
    },
    resetData() {
      this.user = null;
    },
    resetRemoveData() {
      this.user = null;
    },
    applyFilters() {
      this.getList();
    },
    async deleteConsultant(alliancepartner) {
      if (confirm("Do you really want to delete?")) {
        try {
          let data;
          data = await axios.delete(`/consultant/${alliancepartner.id}`);
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

<style lang="scss" scoped>
.per-page-selector {
  width: 90px;
}
</style>

<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";
</style>
