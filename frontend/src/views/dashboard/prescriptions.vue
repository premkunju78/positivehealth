<template>
  <b-card
    v-if="tableData"
    no-body
    class="card-company-table"
    title="Appointments"
  >
    <b-card-header class="align-items-baseline">
      <div>
        <b-card-title class="mb-25">
          <h5>Prescriptions</h5>
        </b-card-title>
      </div>
      <!-- <feather-icon
        icon="SettingsIcon"
        size="18"
        class="text-muted cursor-pointer"
      /> -->
      <b-card-text class="mb-0">
          <router-link :to="{name: 'clientPrescriptions'}"><h5 class="text-info">View All</h5></router-link>
        </b-card-text>
    </b-card-header>

    <b-table
      :items="prescriptions"
      responsive
      :fields="fields"
      class="mb-0"
      style="height:220px;overflow:auto;font-size:x-small"
    >
        <template #cell(created_at)="{item}">
            {{moment(item.created_at).format('hh:mm a')}}
        </template>
         <template #cell(no)="{item}">
            <b class="text-primary"> {{item.no}}</b>
        </template>
        <template #cell(actions)="{item}">
           <a href="#">View</a>
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
      prescriptions: [],
      moment: moment,
      fields: [
        { key: 'no', label: 'Prescription' },
        { key: 'created_at', label: 'Date' },
      ],
    }
  },
  async created() {
      if( this.$store.state.auth.user.role_id > 3 ){
          this.fields.push({ key: 'client', label: 'Client' })
      }else {
            this.fields.push({ key: 'consultant', label: 'Consultant' })
      }
    const { data } = await axios.get("prescription",{
        params: {
            per_page: 3,
            page:1,
            id: this.$store.state.auth.user.id
        }
       
    });
    this.prescriptions = data.prescriptions;
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