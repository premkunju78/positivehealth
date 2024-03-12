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
          <h5>Investigations</h5>
        </b-card-title>
      </div>
      <!-- <feather-icon
        icon="SettingsIcon"
        size="18"
        class="text-muted cursor-pointer"
      /> -->
      <b-card-text class="mb-0">
          <router-link :to="{name: 'clientInvestigation'}"><h5 class="text-info">View All</h5></router-link>
        </b-card-text>
    </b-card-header>

    <b-table
      :items="investigations"
      responsive
      :fields="fields"
      class="mb-0"
      style="height:220px;overflow:auto;"
    >
        <template #cell(created_at)="{item}">
            {{moment(item.created_at).format('DD-MM-YYYY')}}
        </template>
         <template #cell(no)="{item}">
            <b class="text-primary"> {{item.no}}</b>
        </template>
        <template #cell(actions)="{item}">
           <b-link :to="{name: 'viewInvestigation', params:{id: item.id}}" v-if="($store.state.auth.user.role_id === 3)">View</b-link>
           <!-- <a href="#">View</a> -->
        </template>
    </b-table>
  </b-card>
</template>

<script>
import {
  BCard, BTable, BAvatar, BImg,BCardHeader,BCardTitle,BCardText, BLink
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
    BCardText,
    BLink
  },
  props: {
    tableData: {
      type: Array,
      default: () => [],
    },
  },
  computed : {
    id: function(){
      return this.$store.state.auth.user.role_id === 3 ? this.$store.state.auth.user.id : null;
    }
  },
  data() {
    return {
      investigations: [],
      moment: moment,
      fields: [
        { key: 'no', label: 'Report' },
        { key: 'created_at', label: 'Date' },
      ],
    }
  },
  async created() {
    if( this.id ) {
      this.fields.push({key: 'actions', label: 'Actions'})
    } else {
       this.fields.push({key: 'patient', label: 'Client'})
    }
    const { data } = await axios.get("investigation",{
        params: {
            per_page: 3,
            page:1,
            id: this.id
        }
       
    });
    this.investigations = data.investigations;
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