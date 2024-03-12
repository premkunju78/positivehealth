<template>
  <div>
    <b-card title="Role List"
      no-body
      class="mb-0"
    >

    <div class="m-2">

      <!-- Table Top -->
      <b-row>

        <!-- Per Page -->
        <b-col
          cols="12"
          md="6"
          class="d-flex align-items-center justify-content-start mb-1 mb-md-0"
        >
          <!-- <label>Show</label>
          <v-select
            v-model="perPage"
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :options="perPageOptions"
            :clearable="false"
            class="per-page-selector d-inline-block mx-50"
          />
          <label>entries</label> -->
        </b-col>

        <!-- Search -->
        <b-col
          cols="12"
          md="6"
        >
          <div class="d-flex align-items-center justify-content-end" v-permission="['create-roles']">
           
            <router-link :to="{ name: 'settings-create-role'}" >
                <b-button  variant="primary">
                  <span  variant="primary" class="text-nowrap">Add Role</span>
                </b-button>
              </router-link>
          </div>
        </b-col>
      </b-row>

    </div>

          <b-table
            ref="refRolesTable"
            class="position-relative"
            :items="roles"
            responsive
            :fields="columns"
            primary-key="id"
            :totalRows="totalRows"
            :sort-by.sync="sortBy"
            show-empty
            empty-text="No matching records found"
            :sort-desc.sync="isSortDirDesc"
          >

          <!-- Column: Actions -->
          <template #cell(actions)="data" >
            <b-dropdown
              variant="link"
              no-caret
              v-if="$store.getters.user.role_id !== data.item.id &&  currentUserPermissions.includes('edit-roles') && ![1,2,3].includes(data.item.id)"
            >

              <template #button-content >
                <feather-icon
                  icon="MoreVerticalIcon"
                  size="16"
                  class="align-middle text-body"
                 
                />
              </template>
              <b-dropdown-item :to="{ name: 'settings-edit-role', params: { id: data.item.id } }"
               v-permission="['edit-roles']">
                <feather-icon icon="EditIcon" />
                <span class="align-middle ml-50">Edit</span>
              </b-dropdown-item>

              <b-dropdown-item  v-permission="['delete-roles']" 
              @click="handleDeleteRole(data.item)"
              >
                <feather-icon icon="TrashIcon" />
                <span class="align-middle ml-50">Delete</span>
              </b-dropdown-item>
            </b-dropdown>
          </template>
          </b-table>
  </b-card>
</div>
</template>


<script>
import {
  BCard, BRow, BCol, BFormInput, BButton, BTable, BMedia, BAvatar, BLink,
  BBadge, BDropdown, BDropdownItem, BPagination,
} from 'bootstrap-vue'
import roleStoreModule from './roleStoreModule'
import vSelect from 'vue-select'
import store from '@/store'
import { ref, onUnmounted } from '@vue/composition-api'
import { mapActions, mapGetters } from "vuex";

import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'


export default {
  data() {
    return {
      perPage: 10,
      perPageOptions: [10, 25, 50, 100],
      totalRows: "",
      search: "",
      searchQuery:"",
      limit: "10",
      sortBy:'1',
      isSortDirDesc: false,
      roles: []
    };
  },
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
    BBadge,
    BDropdown,
    BDropdownItem,
    BPagination,

    vSelect

  },
  computed: {
    ...mapGetters(["currentUserPermissions"]),
    columns() {
      return [
        { key: 'label', label: 'Role Name', sortable: true },
        { key: 'description', label: 'Role Description', sortable: true },
        { key: 'actions' },

      ];
    }
  },
  setup() {
    const ROLE_APP_STORE_MODULE_NAME = 'app-role'
    // Register module
    if (!store.hasModule(ROLE_APP_STORE_MODULE_NAME)) store.registerModule(ROLE_APP_STORE_MODULE_NAME, roleStoreModule)

    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(ROLE_APP_STORE_MODULE_NAME)) store.unregisterModule(ROLE_APP_STORE_MODULE_NAME)
    })
  },
  methods: {
    //------------------------ Get All Roles -------------------\\
    async Get_Roles(page) {
      await store.dispatch('app-role/fetchRoles')
        .then(response => {
          this.roles = response.data.roles;
          this.totalRows = response.data.totalRows;

          this.isLoading = false;
        })
        .catch(response => {
          // Complete the animation of theprogress bar.
          setTimeout(() => {
            this.isLoading = false;
          }, 500);
        });
    },
    async handleDeleteRole( roleData ) {
      try {
        const {data} = await store.dispatch('app-role/deleteRole', roleData);
        this.Get_Roles();
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.data.message,
            icon: 'EditIcon',
            variant: data.data.success ? 'success' : 'info',
          },
        });
      } catch( err ) {
        console.log(err.message);
      }
    
    }

  },
  created: function() {
    this.Get_Roles(1);
  }


}
</script>

<style>

</style>
