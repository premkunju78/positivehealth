<template> 
  <b-card
    v-if="tableData"
    no-body
    class="card-company-table"
    title="Consultations"
  >
    <b-card-header class="align-items-baseline">
      <div>
        <b-card-title class="mb-25">
          <h5>Consultations</h5>
        </b-card-title>
      </div>
      <!-- <feather-icon
        icon="SettingsIcon"
        size="18"
        class="text-muted cursor-pointer"
      /> -->
      <b-card-text class="mb-0">
          <router-link :to="{name: 'schedules'}"><h5 class="text-primary">View All</h5></router-link>
        </b-card-text>
    </b-card-header>

    <b-table
      :items="schedules"
      responsive
      :fields="fields"
      class="mb-0"
      style="height:230px;overflow:auto"
    >
        <template #cell(timeslot_from)="{item}">
           {{moment(item.timeslot_from).format('Do MMM YY hh:mm')}}
        </template>
         <template #cell(actions)="{item}">
            <a href="#" style="text-decoration:underline" class="text-info">view Details</a>
        </template>
    </b-table>
  </b-card>
</template>

<script>
import {
  BCard, BTable, BAvatar, BImg,BCardHeader,BCardTitle,BCardText
} from 'bootstrap-vue'
import moment from 'moment';

export default {
  components: {
    BCard,
    BTable,
    BAvatar,
    BImg,
    BCardHeader,
    BCardTitle,
    BCardText
  },
  props: {
    tableData: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      schedules: [],
      moment: moment,
      fields: [
        { key: 'consultant', label: 'Name' },
        { key: 'timeslot_from', label: 'appointment time' },
        { key: 'description', label: 'details' },
        { key: 'actions', label: 'Actions' },
      ],
    }
  },
  async created() {
    const { data } = await axios.post("schedules",{
        per_page: 4,
        page:1,
    });
    this.schedules = data.schedules; 
  }
}
</script>

<style lang="scss" scoped>
@import '~@core/scss/base/bootstrap-extended/include';
@import '~@core/scss/base/components/variables-dark';

.card-company-table ::v-deep td .b-avatar.badge-light-company {
  .dark-layout & {
    background: $theme-dark-body-bg !important;
  }
}
</style>
