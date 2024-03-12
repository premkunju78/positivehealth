<template>
  <div>
    <b-card no-body class="mb-0">

      <div class="m-2">

        <!-- Table Top -->
        <b-row>


          <!-- Per Page -->
          <b-col cols="12" md="6" class="d-flex align-items-center justify-content-start mb-1 mb-md-0">

          </b-col>

          <!-- Search -->
          <b-col cols="12" md="6">
            <div class="d-flex align-items-center justify-content-end" v-permission="['create-users']">
              <!-- <b-form-input
                v-model="searchQuery"
                class="d-inline-block mr-1"
                placeholder="Search..."
              /> -->
              <!-- <b-button
                variant="primary"
              >
                <span class="text-nowrap">Export</span>
              </b-button> -->
            </div>
          </b-col>
        </b-row>

      </div>
      <div class="tab-content pt-1">
        <div class="tab-pane active" :id="id" role="tabpanel" aria-labelledby="home-tab-justified" :key="key">
          <b-table ref="refScheduleListTable" class="position-relative" :items="items" responsive :fields="tableColumns"
            primary-key="id" :sort-by.sync="sortBy" :totalRows="totalRows" show-empty
            empty-text="No matching records found" :sort-desc.sync="isSortDirDesc">
            <template #cell(name)="data">
              <span>{{ data.item.booked_by === user_id ? data.item.creator : data.item.name }}</span>
            </template>
            <!-- Column: Video -->
            <template #cell(video)="data">

              <b-button variant="info" v-permission="['create-video-meeting']"
                v-if="!data.item.host_link && (new Date(data.item.timeslot_to).getTime() >= new Date().getTime())"
                @click="generateVideoLink(data.item)">
                <span class="text-nowrap">Create Meeting</span>
              </b-button>

              <a target="_blank" v-if="data.item.host_link"
                :href="data.item.booked_by === user_id ? data.item.participant_link : data.item.host_link">
                <b-button variant="info">
                  <span class="text-nowrap primary">Join Now</span>
                </b-button>
              </a>


            </template>
            <!-- Column: Voice -->
            <template #cell(voice)="data">
              <b-button variant="success" @click="callNow(data.item)">
                <span class="text-nowrap">Call Now</span>
              </b-button>
            </template>
            <template #cell(schedule_time)="data">
              {{ moment(data.item.timeslot_from).format("DD-MM-YYYY hh:mm A") }} - {{
                moment(data.item.timeslot_to).format("DD-MM-YYYY hh:mm A") }}
            </template>
            <template #cell(duration)="data">
              {{ moment(data.item.timeslot_to).diff(moment(data.item.timeslot_from), 'minutes') }} MINS
            </template>
            <template #cell(status)="data">
              {{ data.item.status }}
            </template>
            <template #cell(rating)="data">
            </template>
          </b-table>
        </div>
      </div>


    </b-card>
  </div>
</template>

<script>
import {
  BCard, BRow, BCol, BFormInput, BButton, BTable, BMedia, BAvatar, BLink,
  BBadge, BDropdown, BDropdownItem, BPagination, BTooltip,
} from 'bootstrap-vue'


import vSelect from 'vue-select'

import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import moment from 'moment';

export default {
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
    BTooltip,
    vSelect,
  },
  data() {
    return {
      moment: moment,
      id: 'upcoming',
      items: [],
      perPage: 10,
      perPageOptions: [10, 25, 50, 100],
      totalRows: "",
      search: "",
      searchQuery: "",
      limit: "10",
      sortBy: '1',
      isSortDirDesc: false,
      tableColumns: [
        { key: 'name', label: 'Client Name', sortable: true, },
        { key: 'schedule_time', label: 'Schedule Time', sortable: true },
        { key: 'duration', label: 'Duration', sortable: true },
        { key: 'description', label: 'Description', sortable: true },
        // { key: 'timeslot_from', label: 'From', sortable: true },
        // { key: 'timeslot_to', label: 'To', sortable: true },
        { key: 'video', label: 'Video Call', sortable: false },

      ],
      key: 0,
    }
  },
  created() {
    this.fetchMeetings();
    if (![2, 3].includes(this.role_id)) {
      this.tableColumns.push({ key: 'voice', label: 'Audio Call', sortable: false });
    }
    if ([2, 3].includes(this.$store.state.auth.user.role_id)) {
      this.tableColumns[0].label = "Consultant Name"
    } else {
      this.tableColumns[0].label = "Client Name"
    }
    this.tableColumns.push({ key: 'status', label: 'Status', sortable: false });
    this.tableColumns.push({ key: 'rating', label: 'Rating', sortable: false });
  },
  computed: {
    user_id: function () {
      return this.$store.getters.user.id;
    },
    role_id: function () {
      return this.$store.getters.user.role_id;
    }
  },
  methods: {
    async fetchMeetings() {
      try {
        const { data } = await axios.get(`/meetings/list?type=today`);
        this.items = data.schedules;
        this.totalRows = data.totalRows;
      } catch {
        this.$toast({
          component: ToastificationContent,
          props: {
            title: 'Error fetching meeting list',
            icon: 'AlertTriangleIcon',
            variant: 'danger',
          },
        })
      }
    },

    async generateVideoLink(schedule) {
      try {
        const { data } = await axios.post(`/meeting/link`, schedule);
        if (data.success) {
          this.fetchMeetings();
        }
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: 'AlertTriangleIcon',
            variant: data.success ? 'success' : 'danger',
          },
        })
      } catch {
        this.$toast({
          component: ToastificationContent,
          props: {
            title: 'Error generating video link',
            icon: 'AlertTriangleIcon',
            variant: 'danger',
          },
        })
      }
    },

    async callNow(item) {
      try {
        const { data } = await axios.post(`/call/now`, item);
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: 'AlertTriangleIcon',
            variant: data.success ? 'success' : 'danger',
          },
        })
      } catch {
        this.$toast({
          component: ToastificationContent,
          props: {
            title: 'Error while initiating a call',
            icon: 'AlertTriangleIcon',
            variant: 'danger',
          },
        })
      }
    },


  }
}
</script>

<style></style>
