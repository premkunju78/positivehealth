<template>
  <div>
    <b-card no-body class="mb-0">

      <div class="m-2">

        <!-- Table Top -->
        <b-row>

          <!--

          <b-col cols="12" md="6" class="d-flex align-items-center justify-content-start mb-1 mb-md-0">
            <ul class="nav nav-tabs nav-justified" id="myTab2" role="tablist">
              <li class="nav-item">
                <a :class="tabId === 'upcoming' ? 'nav-link active' : 'nav-link'"
                  @click="fetchSchedules('upcoming', false)" id="home-tab-justified" data-bs-toggle="tab" role="tab"
                  aria-controls="home-just" aria-selected="true">Upcoming</a>
              </li>
              <li class="nav-item">
                <a :class="tabId === 'past' ? 'nav-link active' : 'nav-link'" id="profile-tab-justified"
                  @click="fetchSchedules('past', true)" data-bs-toggle="tab" role="tab" aria-controls="profile-just"
                  aria-selected="false">Past</a>
              </li>
            </ul>
          </b-col>

          -->

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
            <template #cell(timeslot_from)="{ item }">
              {{ moment(item.timeslot_from).format("DD-MM-YYYY hh:mm A") }}
            </template>

            <template #cell(timeslot_to)="{ item }">
              <span v-if="tabId=='past'">{{ moment(item.timeslot_to).format("DD-MM-YYYY hh:mm A") }}</span>
              <span v-if="tabId=='upcoming'">{{ moment(item.timeslot_to).diff(moment(item.timeslot_from), 'minutes') }} MINS</span>
            </template>
            <template #cell(action)="data">
              <b-dropdown variant="link" no-caret v-permission="['view-enquiries']">
                <template #button-content>
                  {{ data.item.status }}
                </template>
                <b-dropdown-item @click="updateStatus(data.item.id, 'Completed')">
                  <span class="align-middle ml-50">Completed</span>
                </b-dropdown-item>
                <b-dropdown-item @click="updateStatus(data.item.id, 'Rescheduled')">
                  <span class="align-middle ml-50">Rescheduled</span>
                </b-dropdown-item>
              </b-dropdown>
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
import moment from 'moment';


import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

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
  props: {
    id: {
      type: [Number, String],
      default: null,
    }
  },
  data() {
    return {
      moment: moment,
      tabId: 'upcoming',
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
        { key: 'description', label: 'Description', sortable: true },
        { key: 'timeslot_from', label: 'From', sortable: true },
        { key: 'timeslot_to', label: 'To', sortable: true },
        { key: 'action', label: 'Action', sortable: true },
      ],
      key: 0,
    }
  },
  created() {
    if ([2, 3].includes(this.$store.state.auth.user.role_id)) {
      this.tableColumns[0].label = "Consultant Name"
    } else {
      this.tableColumns[0].label = "Client Name"
    }
    if (this.tabId == 'upcoming') {
      this.tableColumns[3].label = "Duration"
    } else {
      this.tableColumns[3].label = "To"
    }

    if(this.$route.name == 'todays-schedules') {
      this.tabId = 'todays';
    }else if(this.$route.name == 'past-schedules') {
      this.tabId = 'past';
    }else if(this.$route.name == 'upcoming-schedules') {
      this.tabId = 'upcoming';
    }

    this.fetchSchedules(this.tabId, false);
  },
  watch:{
    $route (to, from){
      if(this.$route.name == 'todays-schedules') {
        this.tabId = 'todays';
      }else if(this.$route.name == 'past-schedules') {
        this.tabId = 'past';
      }else if(this.$route.name == 'upcoming-schedules') {
        this.tabId = 'upcoming';
      }

      this.fetchSchedules(this.tabId, false);
    }
  },
  computed: {
    user_id: function () {
      return this.id ? this.id : this.$store.getters.user.id;
    }
  },
  methods: {
    async fetchSchedules(type, past) {
      try { 
        if (this.tabId == 'upcoming') {
          this.tableColumns[3].label = "Duration"
        } else {
          this.tableColumns[3].label = "To"
        }
        const { data } = await axios.get(`/bookings/list?type=${type}`, { params: { id: this.user_id } });
        this.items = data.schedules;
        this.totalRows = data.totalRows;
      } catch {
        this.$toast({
          component: ToastificationContent,
          props: {
            title: 'Error fetching client list',
            icon: 'AlertTriangleIcon',
            variant: 'danger',
          },
        })
      }
    },
    async updateStatus(id, status) {
      try {
        const { data } = await axios.post("/bookings/updatestatus", { id: id, status: status }, {});
        var past = (this.tabId == 'past') ? true : false;
        this.fetchSchedules(this.tabId, past);
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "EditIcon",
            variant: data.success ? "success" : "info",
          },
        });
      } catch (error) {
        if (error.response) {
          this.errors = error.response.data.errors;
        }
      }
    },
  }
}
</script>

<style></style>
