<template>
  <div class="navbar-container d-flex content align-items-center">

    <!-- Nav Menu Toggler -->
    <ul class="nav navbar-nav d-xl-none">
      <li class="nav-item">
        <b-link
          class="nav-link"
          @click="toggleVerticalMenuActive"
        >
          <feather-icon
            icon="MenuIcon"
            size="21"
          />
        </b-link>
      </li>
    </ul>

    <!-- Left Col -->
    <div class="bookmark-wrapper align-items-center flex-grow-1 d-none d-lg-flex">
      <dark-Toggler class="d-none d-lg-block" />
      <b-form-input  v-model="searchString" type="search" class="form-control mr-5 ml-2" style="height: 40px;border-radius: 10px;margin: 1%;" placeholder="🔍 Search">
        <feather-icon slot="prepend" icon="SearchIcon" size="20"  style="color:#26a79d;margin-top:1%"/>
      </b-form-input>
    </div>

    <b-navbar-nav class="nav align-items-center ml-auto">
      <!-- <search-bar /> -->
      <!-- <feather-icon
        style="color:#26a79d;"
        icon="MessageSquareIcon"
        size="30"
      />&nbsp;&nbsp; -->
      <!-- <notification-dropdown />&nbsp;&nbsp; -->
      <b-nav-item-dropdown
        right
        toggle-class="d-flex align-items-center dropdown-user-link"
        class="dropdown-user"
      >
        <template #button-content>
          <div class="d-sm-flex d-none user-nav">
            <p class="user-name font-weight-bolder mb-0" style="color:#26a79d">
              {{user.company_name}}
            </p>
          </div>
          <b-avatar
            size="40"
            variant="light-primary"
            badge
            :src="'/view/file?path='+user.avatar"
            class="badge-minimal"
            badge-variant="success"
          />
        </template>

        <b-dropdown-item link-class="d-flex align-items-center" :to="{name:'user-profile'}" >
          <feather-icon
            size="16"
            icon="UserIcon"
            class="mr-50"
          />
          <span>Profile</span>
        </b-dropdown-item>
        <b-dropdown-item link-class="d-flex align-items-center">
          <feather-icon
            size="16"
            icon="SettingsIcon"
            class="mr-50"
          />
          <span>Settings</span>
        </b-dropdown-item>
        <b-dropdown-item link-class="d-flex align-items-center">
          <feather-icon
            size="16"
            icon="MailIcon"
            class="mr-50"
          />
          <span>Inbox</span>
        </b-dropdown-item>

        <b-dropdown-item link-class="d-flex align-items-center">
          <feather-icon
            size="16"
            icon="CheckSquareIcon"
            class="mr-50"
          />
          <span>Task</span>
        </b-dropdown-item>

        <b-dropdown-item link-class="d-flex align-items-center">
          <feather-icon
            size="16"
            icon="MessageSquareIcon"
            class="mr-50"
          />
          <span>Chat</span>
        </b-dropdown-item>

        <b-dropdown-divider />

        <b-dropdown-item link-class="d-flex align-items-center" @click="logout">
          <feather-icon
            size="16"
            icon="LogOutIcon"
            class="mr-50"
          />
          <span>Logout</span>
        </b-dropdown-item>
      </b-nav-item-dropdown>
    </b-navbar-nav>
  </div>
</template>

<script>
import {
  BLink, BNavbarNav, BNavItemDropdown, BDropdownItem, BDropdownDivider, BAvatar,
  BFormInput,BCardTitle
} from 'bootstrap-vue'
import DarkToggler from '@core/layouts/components/app-navbar/components/DarkToggler.vue'
import { mapActions, mapGetters } from "vuex";

import SearchBar from '@core/layouts/components/app-navbar/components/SearchBar.vue'

import NotificationDropdown from '@core/layouts/components/app-navbar/components/NotificationDropdown.vue'

export default {
  components: {
    BLink,
    BNavbarNav,
    BNavItemDropdown,
    BDropdownItem,
    BDropdownDivider,
    BAvatar,
    SearchBar,
    NotificationDropdown, 
    BFormInput,
    BCardTitle,

    // Navbar Components
    DarkToggler,
  },
  props: {
    toggleVerticalMenuActive: {
      type: Function,
      default: () => {},
    },
  },
  data() {
    return {
      data: {
        user_name:'default',
        searchString:''
      },
    }
  },
  computed: {
    // let user = this.$store.getters.user;
    // this.user_name = user.name;
    ...mapGetters(["user"]),
   

  },
  methods: {
      ...mapActions({signout:'logout'}),
    async logout() {
      try {
          const {data} = await axios.post(`/logout`)

          if( data.success ) {
            this.signout();
            this.$router.push({name:'login'})
          }
      } catch(error) {
          this.signout();
            this.$router.push({name:'login'})
      }
    }
  }
}
</script>
<style>
 .header-navbar[class*=bg-] .navbar-nav .nav-item > a svg {
    color: #26a79d !important;
}
</style>
