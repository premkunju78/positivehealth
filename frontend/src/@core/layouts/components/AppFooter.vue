<template>
  <p class="clearfix mb-0">
    <span class="float-md-left d-block d-md-inline-block mt-25">
      COPYRIGHT  © {{ new Date().getFullYear() }}
      <b-link
        class="ml-25"
        href="https://1.envato.market/pixinvent_portfolio"
        target="_blank"
      >Prajana</b-link>
      <span class="d-none d-sm-inline-block">, All rights Reserved</span>
    </span>

    <span class="float-md-right d-none d-md-block">
      <!-- Hand-crafted &amp; Made with -->
      <!-- <feather-icon
        icon="HeartIcon"
        size="21"
        class="text-danger stroke-current"
      /> -->
       <b-img
          :src="appLogoImage"
          alt="logo"
          style="width:100px;height:21px"
          title="HEALTH Infinit"
        />
    </span>
  </p>
</template>

<script>
import { BLink, BImg } from 'bootstrap-vue'

import { $themeConfig } from '@themeConfig';

export default {
  components: {
    BLink,
    BImg
  },
  setup(){

    // App Name
    const { appName, appLogoImage } = $themeConfig.app

    return {
      appName, appLogoImage
    }
  }
}
</script>
