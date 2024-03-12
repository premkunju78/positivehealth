<template>
  <b-tabs vertical content-class="col-12 col-md-9 mt-1 mt-md-0" pills nav-wrapper-class="col-md-3 col-12"
    nav-class="nav-left">

    <!-- general tab -->
    <b-tab active>

      <!-- title -->
      <template #title>
        <feather-icon icon="UserIcon" size="18" class="mr-50" />
        <span class="font-weight-bold">General</span>
      </template>

      <account-setting-general v-if="userData" :key="key" :general-data="userData" @change="fetchUserData" />
    </b-tab>
    <!--/ general tab -->

    <!-- info -->
    <b-tab
      v-if="role === 2 || role === 15 || role === 16 || role === 17  || (role > 3 && ['Medical', 'Wellness Provider'].includes(userData.specialization))">
      <!-- title -->
      <template #title>
        <feather-icon icon="InfoIcon" size="18" class="mr-50" />
        <span class="font-weight-bold">Information</span>
      </template>
      <account-setting-consultant v-if="userData && ![1, 3].includes(role)" :key="key" :information-data="userData"
        @change="fetchUserData" />
      <!-- <account-setting-information
        v-if="userData && role === 2"
        :key="key"
        :information-data="userData"
        @change="fetchUserData"
      /> -->
    </b-tab>


    <!-- Bio -->
    <b-tab v-if="is_consultant == 1">
      <!-- title -->
      <template #title>
        <feather-icon icon="FileIcon" size="18" class="mr-50" />
        <span class="font-weight-bold">Bio</span>
      </template>

      <account-setting-bio v-if="userData" :bio-data="userData" :key="key" @change="fetchUserData" />
    </b-tab>

    <!-- change password tab -->
    <b-tab v-if="false">

      <!-- title -->
      <template #title>
        <feather-icon icon="LockIcon" size="18" class="mr-50" />
        <span class="font-weight-bold">Change Password</span>
      </template>

      <account-setting-password />
    </b-tab>
    <!--/ change password tab -->


  </b-tabs>
</template>

<script>
import { BTabs, BTab } from 'bootstrap-vue'
import AccountSettingGeneral from './AccountSettingGeneral.vue'
import AccountSettingPassword from './AccountSettingPassword.vue'
import AccountSettingInformation from './AccountSettingInformation.vue'
import AccountSettingConsultant from './AccountSettingConsultant.vue'
import AccountSettingBio from './AccountSettingBio.vue'

export default {
  components: {
    BTabs,
    BTab,
    AccountSettingGeneral,
    AccountSettingPassword,
    AccountSettingInformation,
    AccountSettingBio,
    AccountSettingConsultant
  },
  data() {
    return {
      options: {},
      userData: {},
      key: 0
    }
  },
  computed: {
    id: function () {
      return this.$store.state.auth.user.id;
    },
    role: function () {
      return this.$store.state.auth.user.role_id;
    },
    is_consultant: function () {
      return this.$store.state.auth.user.role.is_consultant;
    }
  },
  created() {
    this.fetchUserData();
  },

  methods: {
    async fetchUserData() {
      try {
        const { data } = await axios.get(`/consultant/${this.$store.state.auth.user.id}`);

        this.userData = data.user
        // console.log(this.userData.specialization); #ToDo: have to fix rendering issue
        if (this.userData.certificates) {
          this.userData.certificates = JSON.parse(this.userData.certificates);
          // if( this.userData.certificates[0]){
          //   this.userData.certificates = this.userData.certificates[0]
          // }else {
          //   this.userData.certificates = [];
          // }
        }
        if (this.userData.skills) {
          this.userData.skills = JSON.parse(this.userData.skills)
        }
        if (this.userData.service_areas) {
          this.userData.service_areas = JSON.parse(this.userData.service_areas)
        }else{
          this.userData.service_areas = []
        }
        if (this.userData.languages) {
          this.userData.languages = JSON.parse(this.userData.languages)
        }
        this.key++;
      } catch (err) {
        console.log(err)
      }
    },
  }
}
</script>
