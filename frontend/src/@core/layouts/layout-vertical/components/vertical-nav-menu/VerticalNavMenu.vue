<template>
  <div class="main-menu menu-fixed menu-accordion menu-shadow" :class="[
    {
      expanded: !isVerticalMenuCollapsed || (isVerticalMenuCollapsed && isMouseHovered),
    },
    skin === 'semi-dark' ? 'menu-dark' : 'menu-light',
  ]" @mouseenter="updateMouseHovered(true)" @mouseleave="updateMouseHovered(false)">
    <!-- main menu header-->
    <div class="navbar-header expanded mb-4">
      <slot name="header" :toggleVerticalMenuActive="toggleVerticalMenuActive" :toggleCollapsed="toggleCollapsed"
        :collapseTogglerIcon="collapseTogglerIcon">
        <ul class="nav navbar-nav flex-row">
          <!-- Logo & Text -->
          <li class="nav-item mr-auto">
            <b-link class="navbar-brand" to="/">
              <span class="brand-logo">
                <b-img :src="logo" alt="logo" />
              </span>
            </b-link>
          </li>

          <!-- Toggler Button -->
          <li class="nav-item nav-toggle">
            <b-link class="nav-link modern-nav-toggle">
              <feather-icon icon="XIcon" size="20" class="d-block d-xl-none" @click="toggleVerticalMenuActive" />
              <feather-icon :icon="collapseTogglerIconFeather" size="20" class="d-none d-xl-block collapse-toggle-icon"
                @click="toggleCollapsed" />
            </b-link>
          </li>
        </ul>
      </slot>
    </div>
    <!-- / main menu header-->
    <!-- Shadow -->
    <div :class="{ 'd-block': shallShadowBottom }" class="shadow-bottom" />

    <!-- main menu content-->
    <vue-perfect-scrollbar :settings="perfectScrollbarSettings" class="main-menu-content scroll-area" tagname="ul"
      @ps-scroll-y="
        (evt) => {
          shallShadowBottom = evt.srcElement.scrollTop > 0;
        }
      ">
      <vertical-nav-menu-items :items="adminMenuItems" class="navigation navigation-main" v-if="role === 1" />

      <vertical-nav-menu-items :items="alliancepartnerMenuItems" class="navigation navigation-main" v-if="role === 2" />

      <vertical-nav-menu-items :items="clientsMenuItems" class="navigation navigation-main" v-if="role === 3" />
      <vertical-nav-menu-items :items="consultantsMenuItems" class="navigation navigation-main"
        v-if="is_consultant == 1 && is_upgraded == 0" />
      <vertical-nav-menu-items :items="upgradedconsultantsMenuItems" class="navigation navigation-main"
        v-if="is_consultant == 1 && is_upgraded == 1" />

      <vertical-nav-menu-items :items="coordinatorsMenuItems" class="navigation navigation-main" v-if="role === 10" />
      <vertical-nav-menu-items :items="healthcoachMenuItems" class="navigation navigation-main"
        v-if="role == 11 && cis == 0" />
      <vertical-nav-menu-items :items="mhMenuItems" class="navigation navigation-main" v-if="role == 15 && cis == 0" />
      <vertical-nav-menu-items :items="meMenuItems" class="navigation navigation-main" v-if="role == 16 && cis == 0" />
      <vertical-nav-menu-items :items="accIncMenuItems" class="navigation navigation-main" v-if="role == 16 && cis == 0" />
      <vertical-nav-menu-items :items="dhMenuItems" class="navigation navigation-main" v-if="role == 17" />
      <vertical-nav-menu-items :items="samplecollectorMenuItems" class="navigation navigation-main" v-if="role == 18" />
      <vertical-nav-menu-items :items="dataentryuserMenuItems" class="navigation navigation-main" v-if="role == 19" />
      <vertical-nav-menu-items :items="CISMenuItems" class="navigation navigation-main" v-if="role == 11 && cis == 1" />
    </vue-perfect-scrollbar>

    <!-- /main menu content-->
  </div>
</template>

<script>
import adminMenuItems from "@/navigation/vertical/admin";
import alliancepartnerMenuItems from "@/navigation/vertical/alliancepartner";
import clientsMenuItems from "@/navigation/vertical/clients";
import consultantsMenuItems from "@/navigation/vertical/consultants";
import upgradedconsultantsMenuItems from "@/navigation/vertical/upgradedconsultants";
import coordinatorsMenuItems from "@/navigation/vertical/coordinators";
import healthcoachMenuItems from "@/navigation/vertical/healthcoach";
import mhMenuItems from "@/navigation/vertical/mh";
import meMenuItems from "@/navigation/vertical/me";
import accIncMenuItems from "@/navigation/vertical/accountsincharge";
import dhMenuItems from "@/navigation/vertical/dh";
import samplecollectorMenuItems from "@/navigation/vertical/samplecollector";
import dataentryuserMenuItems from "@/navigation/vertical/dataentryuser";
import CISMenuItems from "@/navigation/vertical/CISMenuItems";

import VuePerfectScrollbar from "vue-perfect-scrollbar";
import { BLink, BImg } from "bootstrap-vue";
import { provide, computed, ref } from "@vue/composition-api";
import useAppConfig from "@core/app-config/useAppConfig";
import { $themeConfig } from "@themeConfig";
import VerticalNavMenuItems from "./components/vertical-nav-menu-items/VerticalNavMenuItems.vue";
import useVerticalNavMenu from "./useVerticalNavMenu";

export default {
  components: {
    VuePerfectScrollbar,
    VerticalNavMenuItems,
    BLink,
    BImg,
  },
  props: {
    isVerticalMenuActive: {
      type: Boolean,
      required: true,
    },
    toggleVerticalMenuActive: {
      type: Function,
      required: true,
    },
  },
  computed: {
    role: function () {
      return this.$store.state.auth.user.role_id;
    },
    cis: function () {
      return this.$store.state.auth.user.detail.cisf;
    },
    is_upgraded: function () {
      return this.$store.state.auth.user.detail.is_upgraded;
    },
    is_consultant: function () {
      return this.$store.state.auth.user.role.is_consultant;
    },
    logo: function () {
      return this.$store.state.auth.user.logo
        ? "/view/file?path=" + this.$store.state.auth.user.logo
        : this.appLogoImage;
    },
  },
  setup(props) {
    const {
      isMouseHovered,
      isVerticalMenuCollapsed,
      collapseTogglerIcon,
      toggleCollapsed,
      updateMouseHovered,
    } = useVerticalNavMenu(props);

    const { skin } = useAppConfig();

    // Shadow bottom is UI specific and can be removed by user => It's not in `useVerticalNavMenu`
    const shallShadowBottom = ref(false);

    provide("isMouseHovered", isMouseHovered);

    const perfectScrollbarSettings = {
      maxScrollbarLength: 60,
      wheelPropagation: false,
    };

    const collapseTogglerIconFeather = computed(() =>
      collapseTogglerIcon.value === "unpinned" ? "CircleIcon" : "DiscIcon"
    );

    // App Name
    const { appName, appLogoImage } = $themeConfig.app;

    return {
      adminMenuItems,
      alliancepartnerMenuItems,
      clientsMenuItems,
      consultantsMenuItems,
      coordinatorsMenuItems,
      healthcoachMenuItems,
      mhMenuItems,
      meMenuItems,
      accIncMenuItems,
      dhMenuItems,
      samplecollectorMenuItems,
      dataentryuserMenuItems,
      CISMenuItems,
      upgradedconsultantsMenuItems,
      perfectScrollbarSettings,
      isVerticalMenuCollapsed,
      collapseTogglerIcon,
      toggleCollapsed,
      isMouseHovered,
      updateMouseHovered,
      collapseTogglerIconFeather,

      // Shadow Bottom
      shallShadowBottom,

      // Skin
      skin,

      // App Name
      appName,
      appLogoImage,
    };
  },
};
</script>

<style lang="scss">
@import "~@core/scss/base/core/menu/menu-types/vertical-menu.scss";

.def-brand {
  display: block;
  position: absolute;
  bottom: 150px;
  margin-left: 8px;
}

ul.navigation.navigation-main li.nav-item[custom-class="upgrade-menu"] a {
  padding-left: 0!important;
  padding-right: 0!important;
  
}

ul.navigation.navigation-main li.nav-item[custom-class="upgrade-menu"] > a svg {
  margin-right: 0!important;
  color: #000000;
}

ul.navigation.navigation-main li.nav-item[custom-class="upgrade-menu"] {
  background: linear-gradient(109.6deg, rgb(255, 219, 47) 11.2%, rgb(244, 253, 0) 100.2%);
}

ul.navigation.navigation-main li.nav-item[custom-class="upgrade-menu"].active a {
  background: transparent!important;
}

ul.navigation.navigation-main li.nav-item[custom-class="upgrade-menu"] .menu-title {
  padding-left: 5px;
  padding-right: 5px;  
  padding-top: 2px;
  color: #000000;
}

</style>
