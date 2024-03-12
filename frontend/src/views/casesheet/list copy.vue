<template>
  <div style="min-height:300px">
    <div
      class="body-content-overlay"
      :class="{'show': mqShallShowLeftSidebar}"
      @click="mqShallShowLeftSidebar = false"
    />

    <div class="email-app-list">
      <!-- App Searchbar Header -->
      <div class="app-fixed-search d-flex align-items-center">

        <!-- Toggler -->
        <div class="sidebar-toggle d-block d-lg-none ml-1">
          <feather-icon
            icon="MenuIcon"
            size="21"
            class="cursor-pointer"
            @click="mqShallShowLeftSidebar = true"
          />
        </div>

        <!-- Searchbar -->
        <div class="d-flex align-content-center justify-content-between w-100">
           <b-form-checkbox
              id="groupBYCheckbox"
              v-model="groupBy"
              :value="true"
              :unchecked-value="false"
              @change="groupCaseSheetData"
              switch
            >
              Group By Consultant Name
            </b-form-checkbox>
        </div>
      </div>

      <!-- <div class="app-action">
        <div class="action-left"></div>
        <div
          v-show="selectedEmails.length"
          class="align-items-center"
          :class="{'d-flex': selectedEmails.length}"
        > -->
        <!-- Update Mail Folder Dropdown -->
        <!-- <b-dropdown
          variant="link"
          no-caret
          toggle-class="p-0"
          right
        >
          <template #button-content>
            <feather-icon
              icon="FolderIcon"
              size="17"
              class="align-middle text-body"
            />
          </template>

          <b-dropdown-item @click="moveSelectedEmailsToFolder('draft')">
            <feather-icon icon="Edit2Icon" />
            <span class="align-middle ml-50">Draft</span>
          </b-dropdown-item>
        </b-dropdown> -->
      <!-- </div>
      </div> -->
    <!-- Emails List -->
    <vue-perfect-scrollbar
      :settings="perfectScrollbarSettings"
      class="email-user-list scroll-area"
    >
      <ul class="email-media-list" v-if="sheets">
        <b-media
          v-for="(sheet, index) in sheets"
          :key="'sheet'+index"
          tag="li"
          no-body
          :class="{ 'mail-read': true }"
          @click="updateEmailViewData(sheet)"
        >

          <b-media-aside class="media-left mr-50">
            <b-avatar
              class="avatar"
              size="40"
              variant="primary"
              v-if="sheet[0]"
              :src="sheet[0].consultant_avatar?sheet[0].consultant_avata:require('@/assets/images/avatars/1.png')"
            />
            <b-avatar
              class="avatar"
              size="40"
              variant="primary"
              v-else
              :src="sheet.consultant_avatar?sheet.consultant_avata:require('@/assets/images/avatars/1.png')"
            />
            <div class="user-action">

              <div class="email-favorite">
                {{sheet.length}} Records
              </div>
            </div>
          </b-media-aside>

          <b-media-body>
            <div class="mail-details">
              <div class="mail-items">
                <h5 class="mb-25">
                  {{ sheet[0] ? sheet[0].consultant:sheet.consultant}}
                </h5>
                <span class="text-truncate">{{ sheet[0] ? sheet[0].role : sheet.role }}</span>
              </div>
              <div class="mail-meta-item">
                <!-- <feather-icon
                  v-if="email.attachments.length"
                  icon="PaperclipIcon"
                /> -->
                <!-- <span
                  v-for="label in email.labels"
                  :key="label"
                  class="mx-50 bullet bullet-sm"
                  :class="`bullet-${resolveLabelColor(label)}`"
                /> -->
                <span class="mail-date" v-if="sheet[0]">{{ formatDateToMonthShort(sheet[0].created_at, { hour: 'numeric', minute: 'numeric', }) }}</span>
                <span class="mail-date" v-else>{{ formatDateToMonthShort(sheet.created_at, { hour: 'numeric', minute: 'numeric', }) }}</span>
              </div>
            </div>

            <div class="mail-message">
              <p class="text-truncate mb-0">
                {{ filterTags(sheet[0] ? sheet[0].subjective:sheet.subjective) }}
              </p>
            </div>
          </b-media-body>
        </b-media>
      </ul>
      <div
        class="no-results"
        :class="{'show': !sheets}"
      >
        <h5 v-if="!client"> Please Select Client First</h5>
        <h5 v-else>No Items Found</h5>
      </div>
    </vue-perfect-scrollbar>
  </div>

  <!-- Email View/Detail -->
  <email-view
    :class="{'show': showEmailDetails}"
    :email-view-data="emailViewData"
    :opended-email-meta="opendedEmailMeta"
    @close-email-view="showEmailDetails = false"
    @toggle-email-starred="toggleStarred(emailViewData)"
    @update-email-label="updateOpenEmailLabel"
    @change-opened-email="changeOpenedEmail"

  />

  <!-- Sidebar -->
  <portal to="content-renderer-sidebar-left">
    <email-left-sidebar
      :id="id"
      :shall-show-email-compose-modal.sync="shallShowEmailComposeModal"
      :emails-meta="emailsMeta"
      :class="{'show': mqShallShowLeftSiiddebar}"
      @close-left-sidebar="mqShallShowLeftSidebar = false"
      @client-change="handleClientChange"
    />
    
  </portal>
</div>

</template>

<script>
import store from '@/store'
import {
  ref, onUnmounted, computed, watch,
  // ref, watch, computed, onUnmounted,
} from '@vue/composition-api'
import {
  BFormInput, BInputGroup, BInputGroupPrepend, BDropdown, BDropdownItem,
  BFormCheckbox, BMedia, BMediaAside, BMediaBody, BAvatar,
} from 'bootstrap-vue'
import VuePerfectScrollbar from 'vue-perfect-scrollbar'
import { filterTags, formatDateToMonthShort } from '@core/utils/filter'
import { useResponsiveAppLeftSidebarVisibility } from '@core/comp-functions/ui/app'
import EmailLeftSidebar from './EmailLeftSidebar.vue'
import EmailView from './EmailView.vue'
import useEmail from './useEmail'

export default {
  components: {
    BFormInput,
    BInputGroup,
    BInputGroupPrepend,
    BDropdown,
    BDropdownItem,
    BFormCheckbox,
    BMedia,
    BMediaAside,
    BMediaBody,
    BAvatar,

    // 3rd Party
    VuePerfectScrollbar,
    EmailLeftSidebar,
    EmailView,

  },
  props:{
    id: {
      type: [Number,String, null],
      default: null
    }
  },
  created(){
    if( this.id ) {
        this.handleClientChange(this.id);
    }
  },

  setup() {
  const perfectScrollbarSettings = {
    maxScrollbarLength: 150,
  }
  const { resolveLabelColor } = useEmail()

  const sheets =ref(null);
  const client =ref(null);
  // Emails & EmailsMeta
  const emails = ref([
    {
      id: 1,
      from: {
        email: 'lakshmi@mail.com',
        name: 'Dr Lakshmi',
        avatar: require('@/assets/images/avatars/1.png'),
      },
      to: [
        {
          name: 'me',
          email: 'lakshmi@mail.com',
        },
      ],
      subject: 'Ortho Doctor',
      cc: [],
      bcc: [],
      message:
        '<p>Hi John,</p><p>Biscuit lemon drops marshmallow. Cotton candy marshmallow bear claw. Dragée tiramisu cookie cotton candy. Carrot cake sweet roll I love macaroon wafer jelly soufflé I love dragée. Jujubes jelly I love carrot cake topping I love. Sweet candy I love chupa chups dragée. Tart I love gummies. Chocolate bar carrot cake candy wafer candy canes oat cake I love. Sesame snaps icing pudding sweet roll marshmallow. Cupcake brownie sweet roll chocolate bar I love gummies. Biscuit biscuit macaroon sesame snaps macaroon icing I love soufflé caramels. Apple pie candy jelly. I love icing gummi bears jelly-o pie muffin apple pie.</p><p>Marshmallow halvah brownie cake marzipan ice cream marshmallow. I love lollipop toffee croissant liquorice wafer muffin. Lollipop jelly beans caramels lollipop tootsie roll pudding pie macaroon tootsie roll. Oat cake jujubes gummies cake cake powder cupcake soufflé muffin. Chocolate caramels muffin tart. Jelly beans caramels dessert cotton candy liquorice chocolate cake. Chupa chups muffin bear claw I love. Biscuit jujubes soufflé tart caramels pie sugar plum. Croissant jelly beans cake. Ice cream chocolate liquorice dessert cookie chocolate cake. Powder tart sweet roll macaroon croissant. Sweet tootsie roll macaroon gummi bears macaroon. Gingerbread cake tart.</p><p>Regrads,</p><p>Kristeen Sicilia</p>',
      attachments: [],
      isStarred: false,
      labels: ['ortho'],
      time: 'Mon Dec 10 2018 07:46:00 GMT+0000 (GMT)',
      replies: [],
      folder: 'inbox',
      isRead: true,
    },
    {
      id: 2,
      from: {
        email: 'john@mail.com',
        name: 'John Doe',
        avatar: require('@/assets/images/avatars/2.png'),
      },
      to: [
        {
          name: 'me',
          email: 'dees@mail.com',
        },
      ],
      subject: 'Homeopathy Doctor',
      cc: ['vrushankbrahmshatriya@mail.com'],
      bcc: ['menka@mail.com'],
      message:
        ' <p>Hello John,</p><p>Chocolate bar chupa chups sweet roll chocolate muffin macaroon liquorice tart. Carrot cake topping jelly-o cupcake sweet apple pie jelly I love. Chocolate cake I love dessert carrot cake tootsie roll chocolate I love. Tootsie roll pie marzipan sesame snaps. Lemon drops cake I love chocolate I love. Cheesecake croissant croissant cheesecake tootsie roll chocolate cake caramels. I love I love cake jelly. Pastry jelly beans I love I love. I love tootsie roll gummies toffee I love cookie cookie. Brownie tart croissant.</p><p>I love jelly pie. Croissant jelly beans lemon drops oat cake. Cupcake carrot cake ice cream cake cookie jelly-o fruitcake jelly macaroon. Biscuit muffin cupcake chocolate bar lollipop danish.</p><p>Ice cream I love I love lemon drops cotton candy macaroon. Jujubes soufflé oat cake sweet roll bear claw cotton candy cookie toffee tootsie roll. Danish sesame snaps sesame snaps cookie jelly danish chocolate bar.</p><p>Fruitcake pastry bear claw chocolate cookie. Biscuit icing bear claw. Sweet roll liquorice jelly-o muffin toffee I love wafer. Candy pastry halvah. Pie pastry candy canes tart. Fruitcake I love liquorice icing sweet roll macaroon. I love ice cream jelly-o biscuit brownie marzipan topping pudding sesame snaps. Chupa chups brownie brownie cotton candy sugar plum toffee. Tiramisu tootsie roll gingerbread biscuit I love ice cream brownie sesame snaps. I love croissant I love I love brownie pie marshmallow.</p><p>Regrads</p><p>Tressa Gass</p>',
      attachments: [],
      isStarred: true,
      labels: ['neuro', 'private'],
      time: 'Mon Dec 10 2018 07:55:00 GMT+0000 (GMT)',
      replies: [],
      folder: 'inbox',
      isRead: true,
    },
  ])
  const emailsMeta = ref({})
  const selectedEmails = ref([])

  const updateEmailViewData = email => {
    emailViewData.value = email
    showEmailDetails.value = true

  }
  const showEmailDetails = ref(false)
  const shallShowEmailComposeModal = ref(false)

  const emailViewData = ref([])
  const opendedEmailMeta = computed(() => {
    const openedEmailIndex = emails.value.findIndex(e => e.id === emailViewData.value.id)
    return {
      hasNextEmail: Boolean(emails.value[openedEmailIndex + 1]),
      hasPreviousEmail: Boolean(emails.value[openedEmailIndex - 1]),
    }
  })
  const moveSelectedEmailsToFolder = folder => {}
  const toggleStarred = email => {}


  const updateOpenEmailLabel = ref({})
  const changeOpenedEmail = dir => {}

  const toggleSelectedMail = mailId => {}
  const searchQuery = "seach your query"
  const updateRouteQuery = val => {}
  const mqShallShowLeftSiiddebar=true;

  const { mqShallShowLeftSidebar } = useResponsiveAppLeftSidebarVisibility()

  const groupBy = ref(false);
  return {
      // UI
      perfectScrollbarSettings,
      mqShallShowLeftSiiddebar,

      // Emails & EmailsMeta
      emails,
      groupBy,
      sheets,
      client,
      emailsMeta,
      selectedEmails,

      updateEmailViewData,
      toggleSelectedMail,

      // Search Query
      searchQuery,
      updateRouteQuery,

      // UI Filters
      filterTags,
      formatDateToMonthShort,

      // useEmail
      resolveLabelColor,

      // Email Details
      showEmailDetails,
      emailViewData,
      opendedEmailMeta,
      updateOpenEmailLabel,
      changeOpenedEmail,

      // Mail Actions
      toggleStarred,
      moveSelectedEmailsToFolder,
      shallShowEmailComposeModal,
      // Left Sidebar Responsiveness
      mqShallShowLeftSidebar,
    }
  },

  methods: {
    groupCaseSheetData(val){
      this.handleClientChange( this.client, val)
    },

    async handleClientChange( client, group = false ){
      this.client =client;
      if( client ) {
        try {
          const {data} = await axios.get(`caseSheet/client/${client}`,{
            params: {
              group
            }
          })
          this.sheets = data.sheets;
          this.emailViewData = [];
          this.showEmailDetails = false
        } catch (error) {

        }
      } else {
        this.groupBy =false;
        this.sheets = null;
        this.emailViewData = [];
        this.showEmailDetails = false
      }
    }
  }
}
</script>
<style lang="scss" scoped>

</style>

<style lang="scss">
@import "~@core/scss/base/pages/app-email.scss";
</style>
