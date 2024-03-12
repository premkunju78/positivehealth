<template>
  <div class="sidebar-left">
    <div class="sidebar">
      <div class="sidebar-content email-app-sidebar">
        <div class="email-app-menu">
           <div class="form-group-compose text-center compose-btn" v-if="!id">
             <v-select
                v-model="client"
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="clients"
                :reduce="val => val.id"
                :clearable="true"
                input-id="clients"
                placeholder="Select Client"
                @input="handleClientChage"
              />
          </div>
          <div v-if="client || id" v-permission="['create-case-sheet']" class="form-group-compose text-center compose-btn">
            <router-link  :to="{name: 'newcasesheet', params:{id: client?client:id}}">
             <b-button
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
               variant="primary"
               block
               v-permission="['create-case-sheet']"
             >
               <span class="text-nowrap">Add New</span>
             </b-button>
            </router-link>
          </div>

        </div>
      </div>
    </div>
  </div>
</template>

<script>
import VuePerfectScrollbar from 'vue-perfect-scrollbar'
import {
  BButton, BListGroup, BListGroupItem, BBadge,
} from 'bootstrap-vue'
import { isDynamicRouteActive } from '@core/utils/utils'
import Ripple from 'vue-ripple-directive'

import vSelect from "vue-select";
export default {
  directives: {
    Ripple,
  },
  components: {

    // BSV
    BButton,
    BListGroup,
    BListGroupItem,
    BBadge,
    vSelect,

    // 3rd Party
    VuePerfectScrollbar,
  },
  props: {
    shallShowEmailComposeModal: {
      type: Boolean,
      required: true,
    },
    emailsMeta: {
      type: Object,
      required: true,
    },
    id: {
      type: [Number,String,null],
      default: null,
    }
  },
  data() {
    return {
      clients: [],
      client:null
    }
  },
  setup() {
    const perfectScrollbarSettings = {
      maxScrollbarLength: 60,
    }

    const emailFilters = [
      { title: 'Inbox', icon: 'MailIcon', route: { name: 'apps-email' } },
      { title: 'Sent', icon: 'SendIcon', route: { name: 'apps-email-folder', params: { folder: 'sent' } } },
      { title: 'Draft', icon: 'Edit2Icon', route: { name: 'apps-email-folder', params: { folder: 'draft' } } },
      { title: 'Starred', icon: 'StarIcon', route: { name: 'apps-email-folder', params: { folder: 'starred' } } },
      { title: 'Spam', icon: 'InfoIcon', route: { name: 'apps-email-folder', params: { folder: 'spam' } } },
      { title: 'Trash', icon: 'TrashIcon', route: { name: 'apps-email-folder', params: { folder: 'trash' } } },
    ]

    const emailLabel = [
      { title: 'Personal', color: 'success', route: { name: 'apps-email-label', params: { label: 'personal' } } },
      { title: 'Company', color: 'primary', route: { name: 'apps-email-label', params: { label: 'company' } } },
      { title: 'Important', color: 'warning', route: { name: 'apps-email-label', params: { label: 'important' } } },
      { title: 'Private', color: 'danger', route: { name: 'apps-email-label', params: { label: 'private' } } },
    ]

    const resolveFilterBadgeColor = filter => {
      if (filter === 'Draft') return 'light-warning'
      if (filter === 'Spam') return 'light-danger'
      return 'light-primary'
    }

    return {
      // UI
      perfectScrollbarSettings,
      isDynamicRouteActive,
      resolveFilterBadgeColor,

      // Filter & Labels
      emailFilters,
      emailLabel,
    }
  },
  created(){
    this.getClients();
  },
  methods:{
    handleClientChage(val){
      this.$emit('client-change', val);
    },
    async getClients() {
      try {
          const { data } = await axios.get('consultant/client/list');
          this.clients = data.clients;
      } catch (err) {
          console.log(err)
      }
    },
  }
}
</script>
<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";
</style>
