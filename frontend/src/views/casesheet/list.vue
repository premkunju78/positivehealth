<template>
  <div style="min-height:300px">
    <div v-if="client || id" v-permission="['create-case-sheet']" class="form-group-compose text-center compose-btn"
      style="
      float: right;
      margin: 15px;
  ">
      <router-link :to="{ name: 'newcasesheet', params: { id: client ? client : id } }">
        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" block v-permission="['create-case-sheet']">
          <span class="text-nowrap">Add New</span>
        </b-button>
      </router-link>
    </div>
    <div class="email-app-list" style="width:100%">
      <!-- App Searchbar Header -->
      <b-table style="min-height: 250px" ref="refRolesTable" class="position-relative" :items="sheets" responsive
        :fields="columns" :totalRows="sheets.length" primaryKey="index" show-empty empty-text="No matching records found">
        <template #cell(index)="data">
          {{ data.index + 1 }}
        </template>
        <template #cell(chief_concern)="data">
          <span v-html="data.item.chief_concern"></span>
        </template>
        <template #cell(client_feedback)="data">
          <span v-html="data.item.client_feedback"></span>
        </template>
        <template #cell(associate_concern)="data">
          <span v-html="data.item.associate_concern"></span>
        </template>
        <template #cell(plan_of_action)="data">
          <span v-html="data.item.plan_of_action"></span>
        </template>
        <template #cell(actions)="data">
          <a @click="updateEmailViewData(data.item)" class="btn btn-primary">View info</a>
        </template>
      </b-table>
      <b-pagination v-if="pagination.total" v-model="pagination.page" :page.sync="pagination.page"
        :total-rows="pagination.total" :index="1" :per-page="pagination.per_page" aria-controls="package-row"
        align="right" @change="handlePaginationChange">
      </b-pagination>
    </div>
    <!-- Email View/Detail -->
    <email-view :class="{ 'show': showEmailDetails }" :email-view-data="emailViewData"
      :opended-email-meta="opendedEmailMeta" @close-email-view="showEmailDetails = false"
      @toggle-email-starred="toggleStarred(emailViewData)" @update-email-label="updateOpenEmailLabel"
      @change-opened-email="changeOpenedEmail" />
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
  BTable,
  BRow,
  BCol,
  BLink,
  BButton,
  BPagination,
} from 'bootstrap-vue'
import VuePerfectScrollbar from 'vue-perfect-scrollbar'
import { filterTags, formatDateToMonthShort } from '@core/utils/filter'
import { useResponsiveAppLeftSidebarVisibility } from '@core/comp-functions/ui/app'
import EmailLeftSidebar from './EmailLeftSidebar.vue'
import EmailView from './EmailView.vue'
import useEmail from './useEmail'

export default {
  components: {
    BButton,
    BTable,
    BRow,
    BCol,
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
    BPagination,
    // 3rd Party
    VuePerfectScrollbar,
    EmailLeftSidebar,
    EmailView,

  },
  props: {
    id: {
      type: [Number, String, null],
      default: null
    }
  },
  created() {
    if (this.id) {
      this.handleClientChange(this.id);
    }
  },

  setup() {
    const perfectScrollbarSettings = {
      maxScrollbarLength: 150,
    }
    const { resolveLabelColor } = useEmail()

    const sheets = ref(null);
    const client = ref(null);
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
    const moveSelectedEmailsToFolder = folder => { }
    const toggleStarred = email => { }


    const updateOpenEmailLabel = ref({})
    const changeOpenedEmail = dir => { }

    const toggleSelectedMail = mailId => { }
    const searchQuery = "seach your query"
    const updateRouteQuery = val => { }
    const mqShallShowLeftSiiddebar = true;

    const { mqShallShowLeftSidebar } = useResponsiveAppLeftSidebarVisibility()

    const groupBy = ref(false);
    const columns = [

      { key: "consultant", label: "Consultant", sortable: true },
      { key: "date", label: "Date", sortable: true },
      { key: "chief_concern", label: "Chief Concern", sortable: true },
      { key: "associate_concern", label: "Associate Concern", sortable: true },
      { key: "client_feedback", label: "Client Feedback", sortable: true },
      { key: "plan_of_action", label: "Plan of action", sortable: false },
      { key: "actions", label: "Actions", sortable: false },
    ];
    return {
      pagination: {
        page: 1,
        per_page: 10,
        total: null,
      },
      columns,
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
    groupCaseSheetData(val) {
      this.handleClientChange(this.client, val)
    },
    handlePaginationChange(val) {
      this.pagination.page = val;
      this.handleClientChange(this.client)
    },
    async handleClientChange(client, group = false) {
      this.client = client;

      if (client) {
        try {
          this.pagination.group = group;
          const { data } = await axios.get(`caseSheet/client/${client}`, {
            params: this.pagination
          })
          this.sheets = data.sheets;
          this.pagination.per_page = data.per_page;
          this.pagination.total = data.total;
          this.pagination.page = data.page;
          this.emailViewData = [];
          this.showEmailDetails = false
        } catch (error) {

        }
      } else {
        this.groupBy = false;
        this.sheets = null;
        this.emailViewData = [];
        this.showEmailDetails = false
      }
    }
  }
}
</script>
<style lang="scss" scoped></style>

<style lang="scss">
@import "~@core/scss/base/pages/app-email.scss";

[dir=ltr] .email-application .content-area-wrapper .content-right {
  border-left: 1px solid #ebe9f1;
  width: 100% !important;
}

[dir=ltr] .email-application .email-app-details {
  transform: translateX(100%);
  width: 100% !important;
}
html[dir] .navbar-sticky .app-content {
    padding: calc(2rem + 4.45rem + 1.3rem) 2rem 0;
}
html .content.app-content .content-area-wrapper .content-body{
  padding-left: 10px;
}
html .content.app-content .content-area-wrapper .content-wrapper{
  overflow: auto;
}
</style>
