<template>
  <div class="email-app-details">

    <!-- Email Header -->
    <div class="email-detail-header">

      <!-- Header: Left -->
      <div class="email-header-left d-flex align-items-center">
        <span class="go-back mr-1">
          <feather-icon
            :icon="$store.state.appConfig.isRTL ? 'ChevronRightIcon' : 'ChevronLeftIcon'"
            size="20"
            class="align-bottom"
            @click="$emit('close-email-view')"
          />
        </span>
        <h4 class="email-subject mb-0">
          {{emailViewData[0] ?  emailViewData[0].consultant : emailViewData.consultant }}
        </h4>
      </div>
    </div>

    <!-- Email Details -->
    <vue-perfect-scrollbar
      :settings="perfectScrollbarSettings"
      class="email-scroll-area scroll-area"
    >

      <!-- Label Row -->
      <!-- <b-row>
        <b-col cols="12">
          <div class="email-label">
            <b-badge
              v-for="(label) in emailViewData.labels"
              :key="label"
              pill
              class="text-capitalize mr-50"
              :variant="`light-${resolveLabelColor(label)}`"
            >
              {{ label }}
            </b-badge>
          </div>
        </b-col>
      </b-row> -->

      <!-- Action: Show Earlier Message -->
      <!-- <b-row
        v-if="!showWholeThread && emailViewData.replies && emailViewData.replies.length"
        class="mb-1"
      >
        <b-col cols="12">
          <b-link
            class="font-weight-bold"
            @click="showWholeThread = true"
          >
            View {{ emailViewData.replies.length }} Earlier Message{{ emailViewData.replies.length > 1 ? 's' : '' }}
          </b-link>
        </b-col>
      </b-row> -->

      <!-- Earlier Email Messages -->
      <!-- <template v-if="showWholeThread">
        <b-row
          v-for="threadMail in emailViewData.replies.slice().reverse()"
          :key="threadMail.id"
        >
          <b-col cols="12">
            <email-message-card :message="threadMail" />
          </b-col>
        </b-row>
      </template> -->

      <!-- Email Thread -->
      <b-row>
        <b-col cols="12" v-if="emailViewData[0]">
          <email-message-card    
          v-for=" sheet in emailViewData"
          :key="'record'+sheet.id" 
          :message="sheet" />
        </b-col>
        <b-col cols="12" v-else>
          <email-message-card
          :message="emailViewData" />
        </b-col>
      </b-row>

      <!-- Email Actions: Reply or Forward -->
      <!-- <b-row>
        <b-col cols="12">
          <b-card>
            <div class="d-flex justify-content-between">
              <h5 class="mb-0">
                Click here to
                <b-link>Add New</b-link>
              </h5>
            </div>
          </b-card>
        </b-col>
      </b-row> -->
    </vue-perfect-scrollbar>
  </div>
</template>

<script>
import {
  BDropdown, BDropdownItem, BRow, BCol, BBadge, BCard, BLink,
} from 'bootstrap-vue'
import VuePerfectScrollbar from 'vue-perfect-scrollbar'
import { ref, watch } from '@vue/composition-api'
import useEmail from './useEmail'
import EmailMessageCard from './EmailMessageCard.vue'

export default {
  components: {

    // BSV
    BDropdown,
    BDropdownItem,
    BRow,
    BCol,
    BBadge,
    BCard,
    BLink,

    // 3rd Party
    VuePerfectScrollbar,

    // SFC
    EmailMessageCard,
  },
  props: {
    emailViewData: {
      type: [Array,Object],
      required: true,
    },
    opendedEmailMeta: {
      type: Object,
      required: true,
    },
  },
  setup(props) {
    const { resolveLabelColor } = useEmail()

    const perfectScrollbarSettings = {
      maxScrollbarLength: 150,
    }

    const showWholeThread = ref(false)

    watch(() => props.emailViewData.id, () => {
      showWholeThread.value = false
    })

    return {

      // UI
      perfectScrollbarSettings,
      showWholeThread,

      // useEmail
      resolveLabelColor,
    }
  },
}
</script>

<style>

</style>
