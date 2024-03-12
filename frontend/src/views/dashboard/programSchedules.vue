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
          <h5>Programmes Scheduled</h5>
        </b-card-title>
      </div>
      <!-- <feather-icon
        icon="SettingsIcon"
        size="18"
        class="text-muted cursor-pointer"
      /> -->
      <b-card-text class="mb-0">
          <router-link :to="{name: 'packages'}"><h5 class="text-info">View All</h5></router-link>
        </b-card-text>
    </b-card-header>

    <b-table
      :items="packages"
      responsive
      :fields="fields"
      class="mb-0"
      thead-class="hidden_header"
      style="height:120px;overflow:auto"
    >
         <template #cell(title)="{item}">
            {{item.title}} ({{item.type}})
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
      packages: [],
      moment: moment,
      fields: [
        { key: 'title', label: 'Title' },
        { key: 'category', label: 'Category' },
      ],
    }
  },
  async created() {
    const { data } = await axios.get("packages/me",{
        params: {
            per_page: 4,
            page:1,
        }
    });
    this.packages = data.packages;
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
<style>

.hidden_header {
  display: none;
}
</style>
