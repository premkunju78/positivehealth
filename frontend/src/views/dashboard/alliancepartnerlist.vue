<template>
  <b-table
    ref="refUserListTable"
    class="position-relative"
    :items="items"
    responsive
    :fields="tableColumns"
    primary-key="id"
    :sort-by.sync="sortBy"
    :totalRows="totalRows"
    show-empty
    empty-text="No matching records found"
    :sort-desc.sync="isSortDirDesc"
  >
    <!-- Column: User -->
    <template #cell(user)="data">
      <b-media vertical-align="center">
        <template #aside>
          <b-avatar
            size="32"
            :src="data.item.avatar"
            :text="avatarText(data.item.name)"
            :variant="`light-${resolveUserRoleVariant(data.item.role)}`"
            :to="{ name: 'apps-users-view', params: { id: data.item.id } }"
          />
        </template>
        {{ data.item.name }}
      </b-media>
    </template>
    <template #cell(id)="data">
      {{ "AP-" + data.item.id }}
    </template>
    <!-- Column: Role -->
    <template #cell(role)="data">
      <div class="text-nowrap">
        <feather-icon
          :icon="resolveUserRoleIcon(data.item.role)"
          size="18"
          class="mr-50"
          :class="`text-${resolveUserRoleVariant(data.item.role)}`"
        />
        <span class="align-text-top text-capitalize">{{ data.item.role }}</span>
      </div>
    </template>

    <template #cell(coordinators)="data">
      <div class="text-nowrap">
        <p
          class="align-text-top text-capitalize m-0"
          v-for="name in data.item.coordinators"
        >
          {{ name }}
        </p>
      </div>
    </template>

    <template #cell(call)="data">
      <b-button
        variant="success"
        class="btn btn-primary ml-2"
        @click="callNow(data.item.phone)"
      >
        <span class="text-nowrap">Call Now</span>
      </b-button>
    </template>

    <!-- Column: Actions -->
    <template #cell(actions)="data">
      <b-dropdown
        variant="link"
        no-caret
        :right="$store.state.appConfig.isRTL"
        v-permission="['view-users']"
      >
        <template #button-content>
          <feather-icon
            icon="MoreVerticalIcon"
            size="16"
            class="align-middle text-body"
          />
        </template>
        <b-dropdown-item
          :to="{
            name: 'mapped-clients',
            params: { id: data.item.id, role: 'alliance-partner' },
          }"
        >
          <feather-icon icon="EyeIcon" />
          <span class="align-middle ml-50">View Clients</span>
        </b-dropdown-item>

        <b-dropdown-item
          :to="{
            name: 'edit-partner',
            params: { id: data.item.id, user: data.item },
          }"
          v-permission="['edit-users']"
        >
          <feather-icon icon="EditIcon" />
          <span class="align-middle ml-50">Edit</span>
        </b-dropdown-item>
        <!-- <b-dropdown-item :to="{ name: 'AllianConsultantList', params: { id: data.item.id } }"  v-permission="['edit-users']">
              <feather-icon icon="UserIcon" />
              <span class="align-middle ml-50">Consultants</span>
            </b-dropdown-item> -->

        <!-- <b-dropdown-item  v-permission="['delete-users']">
              <feather-icon icon="TrashIcon"/>
              <span class="align-middle ml-50">Delete</span>
            </b-dropdown-item> -->
      </b-dropdown>
    </template>
  </b-table>
</template>
<script>
import {
  BCard,
  BLink,
  BCardTitle,
  BCarousel,
  BCarouselSlide,
  BTable,
  BDropdown,
  BDropdownItem,
} from "bootstrap-vue";
import StarRating from "vue-star-rating";

export default {
  components: {
    BCard,
    BLink,
    BCardTitle,
    BCarousel,
    BCarouselSlide,
    StarRating,
    BTable,
    BDropdown,
    BDropdownItem,
  },
  data() {
    return {
      items: [],
      perPage: 10,
      perPageOptions: [10, 25, 50, 100],
      limit: "10",
      sortBy: "1",
      isSortDirDesc: false,
      pagination: {
        page: 1,
        per_page: 1000,
        total: null,
        filters: {},
      },
      tableColumns: [
        { key: "id", label: "User Id", sortable: true },
        { key: "name", label: "Name", sortable: true },
        { key: "email", label: "Email", sortable: true },
        { key: "phone", label: "Phone", sortable: true },
      ],
    };
  },
  created() {
    this.getList();
    this.columns();
  },
  methods: {
    columns() {
      if (this.$store.getters.user.role_id === 10) {
        this.tableColumns = [
          { key: "id", label: "User Id", sortable: true },
          { key: "name", label: "Name", sortable: true },
          { key: "email", label: "Email", sortable: true },
          { key: "group", label: "Group", sortable: true, filterByFormatted: true },
          { key: "type", label: "Sub Group", sortable: true },
          { key: "call", label: "Call", sortable: true },
        ];
      }
    },
    async getList() {
      try {
        const { data } = await axios.get("/alliance-partners", {
          params: this.pagination,
        });
        this.items = data.partners;
        this.pagination.per_page = data.per_page;
        this.pagination.total = data.total;
        this.pagination.page = data.page;
      } catch {
        this.$toast({
          component: ToastificationContent,
          props: {
            title: "Error fetching client list",
            icon: "AlertTriangleIcon",
            variant: "danger",
          },
        });
      }
    },
  },
};
</script>

<style lang="scss">
.carousel-caption {
  position: unset;
  text-align: left;
}
.carousel-indicators {
  position: unset;
  text-align: center;
  margin-top: -32px;
  display: block !important;
}
.carousel-indicators li {
  background-color: #afabab;
  display: inline-block;
}
</style>
