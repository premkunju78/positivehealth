<template>
  <div class="clearfix">
    <router-link v-if="$route.meta.backButton" :to="{ path: path }" class="float-right mt-1 mb-1">
      <b-button variant="primary">
        <span class="text-nowrap">Back</span>
      </b-button>
    </router-link>
    <b-row v-if="$route.meta.breadcrumb || $route.meta.pageTitle" class="content-header">
      <!-- Content Left -->
      <b-col class="content-header-left mb-2" cols="12" md="9">
        <b-row class="breadcrumbs-top">
          <b-col cols="12">
            <h2 class="content-header-title float-left pr-1 mb-0">
              <span v-if="$route.meta.clientModule">{{ clientModule }} - </span> {{ pageTitle }}
            </h2>
            <div class="breadcrumb-wrapper">
              <b-breadcrumb>
                <b-breadcrumb-item to="/">
                  <feather-icon icon="HomeIcon" size="16" class="align-text-top" />
                </b-breadcrumb-item>
                <b-breadcrumb-item v-for="item in $route.meta.breadcrumb" :key="item.text" :active="item.active"
                  :to="item.to">
                  {{ item.text }}
                </b-breadcrumb-item>
              </b-breadcrumb>
            </div>
          </b-col>
        </b-row>
      </b-col>

      <!-- Content Right -->
      <!-- <b-col
      class="content-header-right text-md-right d-md-block d-none mb-1"
      md="3"
      cols="12"
    >
      <b-dropdown
        variant="link"
        no-caret
        toggle-class="p-0"
        right
      >

        <template #button-content>
          <b-button
            v-ripple.400="'rgba(255, 255, 255, 0.15)'"
            variant="primary"
            class="btn-icon"
          >
            <feather-icon icon="SettingsIcon" />
          </b-button>
        </template>

        <b-dropdown-item :to="{ name: 'apps-todo' }">
          <feather-icon
            icon="CheckSquareIcon"
            size="16"
          />
          <span class="align-middle ml-50">Todo</span>
        </b-dropdown-item>

        <b-dropdown-item :to="{ name: 'apps-chat' }">
          <feather-icon
            icon="MessageSquareIcon"
            size="16"
          />
          <span class="align-middle ml-50">Chat</span>
        </b-dropdown-item>

        <b-dropdown-item :to="{ name: 'apps-email' }">
          <feather-icon
            icon="MailIcon"
            size="16"
          />
          <span class="align-middle ml-50">Email</span>
        </b-dropdown-item>

        <b-dropdown-item :to="{ name: 'apps-calendar' }">
          <feather-icon
            icon="CalendarIcon"
            size="16"
          />
          <span class="align-middle ml-50">Calendar</span>
        </b-dropdown-item>
      </b-dropdown>
    </b-col> -->
    </b-row>
  </div>
</template>

<script>
import {
  BBreadcrumb,
  BBreadcrumbItem,
  BRow,
  BCol,
  BDropdown,
  BDropdownItem,
  BButton,
} from "bootstrap-vue";
import Ripple from "vue-ripple-directive";

export default {
  directives: {
    Ripple,
  },
  components: {
    BBreadcrumb,
    BBreadcrumbItem,
    BRow,
    BCol,
    BDropdown,
    BDropdownItem,
    BButton,
  },
  data() {
    return {
      clientName: "",
    };
  },
  mounted() {
  },
  computed: {
    path: function () {
      return this.$route.meta.backButton.split(":id").join(this.$route.params.id).split(":userid").join(this.$route.params.userid);
    },
    pageTitle: function () {
      this.getUser();
      return this.$route.meta.pageTitle.split(":name").join(this.$route.query.name);
    },
    clientModule: function () {
      return this.clientName;
    },
  },
  methods: {
    async getUser() {
      if (this.$route.params.id) {
        const { data } = await axios.get(`/client/` + this.$route.params.id);
        this.clientName = data.user.name;
      }
    },
  },
};
</script>
