<template>
  <div>
    <b-overlay :show="$store.state.app.shallShowOverlay" no-wrap opacity="0.5" blur="1px"></b-overlay>
    <b-row class="mb-1">
      <b-col cols="12" sm="6" class="d-flex align-items-center justify-content-start mb-1 mb-md-0">

        <b-pagination v-if="pagination.total" v-model="pagination.page" :page.sync="pagination.page"
          :total-rows="pagination.total" :index="1" :per-page="pagination.per_page" aria-controls="video-row" align="left"
          @change="handlePaginationChange">
          <template #prev-text>
            <feather-icon icon="ChevronLeftIcon" size="18" />
          </template>
          <template #next-text>
            <feather-icon icon="ChevronRightIcon" size="18" />
          </template>
        </b-pagination>

      </b-col>

      <b-col cols="12" md="5">
        <div class="d-flex align-items-center justify-content-end" v-permission="['edit-video']">
          <b-form-input v-model="pagination.keyword" class="d-inline-block mr-1" placeholder="Search Video Here..."
            type="search" @update="handleClearKeyword" />
          <b-button variant="primary" @click="handleSearchVideo">
            <span class="text-nowrap">Search</span>
          </b-button>
          <v-select v-model="selectkeyword" :placeholder="'Search Recent Sessions'" @input="searchbytitle" :options="titlelists" :clearable="false"
            class="per-page-selector d-inline-block mx-50" style="min-width: 200px;width: 100%;" />

        </div>


      </b-col>
    </b-row>

    <b-row id="video-row" v-if="list && list.length">
      <b-col v-for="video in list" :key="video.link" cols="12" md="3" lg="3" sm="3" xl="3">
        <loading-card v-if="loading"></loading-card>
        <video-card v-else :video="video" :buttons="true" @remove="getList"></video-card>
      </b-col>
    </b-row>
    <b-row v-else>
      <b-col style="text-align:center">
        <span> No Data Available</span>
      </b-col>
    </b-row>
    <div class="mx-2 mb-2">
      <b-row>

        <b-col cols="12" sm="6" class="d-flex align-items-center justify-content-center justify-content-sm-start">

        </b-col>
        <!-- Pagination -->
        <b-col cols="12" sm="6" class="d-flex align-items-center justify-content-center justify-content-sm-end">

          <b-pagination v-if="pagination.total" v-model="pagination.page" :page.sync="pagination.page"
            :total-rows="pagination.total" :index="1" :per-page="pagination.per_page" aria-controls="video-row"
            align="right" @change="handlePaginationChange">
            <template #prev-text>
              <feather-icon icon="ChevronLeftIcon" size="18" />
            </template>
            <template #next-text>
              <feather-icon icon="ChevronRightIcon" size="18" />
            </template>
          </b-pagination>

        </b-col>

      </b-row>
    </div>
  </div>
</template>
<style >
iframe {
  width: 100% !important;
  height: 200px !important;
}
</style>
<script>
import { BCard, BCardText, BTable, BRow, BCol, BPagination, BSpinner, BOverlay, BFormInput, BButton } from 'bootstrap-vue'
import VideoCard from './card.vue';
import LoadingCard from './loadingCard.vue';

import vSelect from 'vue-select'

export default {
  components: {
    BCard,
    BCardText,
    BTable,
    VideoCard,
    BRow,
    BCol,
    BPagination,
    BSpinner,
    LoadingCard,
    vSelect,
    BOverlay,
    BFormInput,
    BButton
  },

  setup() {
    const pagination = {
      page: 1,
      per_page: 8,
      total: null,
      keyword: '',

    };
    const list = null;
    const titlelists = [];
    const loading = true;
    const search = "";
    const selectkeyword = "";

    return {
      list,
      titlelists,
      pagination,
      selectkeyword,
      loading
    }
  },
  created() {
    this.getList();
  },
  methods: {
    searchbytitle(){
      this.pagination.keyword = this.selectkeyword;
      this.getList();
    },
    async getList() {
      try {
        this.loading = true;
        const { data } = await axios.get('videos', {
          params: this.pagination
        });
        if (data.videos) {
          this.list = data.videos.data
          this.titlelists = data.lists
          this.pagination.per_page = data.videos.per_page
          this.pagination.total = data.videos.total;
          this.pagination.page = data.videos.page
        }


      } catch (err) {
        console.log(err)
      }
      this.loading = false;
    },
    async handlePaginationChange(val) {
      this.pagination.page = val;
      this.getList()
    },

    async handleSearchVideo() {
      this.getList();
    },

    async handleClearKeyword(val) {
      if (!val) {
        this.getList();
      }
    }
  }
}
</script>
<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";

.modal-body {
  min-height: 300px !important;
}
</style>
