<template>
  <div>
    <b-row class="mb-1" >
      <b-col
          cols="12"
          sm="6"
          class="d-flex align-items-center justify-content-start mb-1 mb-md-0"
        >

          <b-pagination
            v-model="pagination.page"
            :page.sync="pagination.page"
            :total-rows="pagination.total"
            :index="1"
            :per-page="pagination.per_page"
            aria-controls="video-row"
            align="right"
            @change="handlePaginationChange"
          >
            <template #prev-text>
              <feather-icon
                icon="ChevronLeftIcon"
                size="18"
              />
            </template>
            <template #next-text>
              <feather-icon
                icon="ChevronRightIcon"
                size="18"
              />
            </template>
          </b-pagination>

      </b-col>

      <b-col
          cols="12"
          md="5"
        >
        <div class="d-flex align-items-center justify-content-end">
          <b-form-input
            v-model="pagination.keyword"
            class="d-inline-block mr-1"
            placeholder="Search Video Here..."
            type="search"
            @update="handleClearKeyword"
          />
          <b-button
            variant="primary"
            @click="handleSearchVideo"
          >
            <span class="text-nowrap">Search</span>
          </b-button>
          
        </div>
        
    
      </b-col>
    </b-row>
    
    <b-row id="video-row">
        <b-col v-for="video in list" :key="video.link" cols="3">
            <loading-card v-if="loading"></loading-card>
            <video-card v-else :video="video" :group="id" :creator="creator" @remove="getList"></video-card>
        </b-col>
    </b-row>
        <div class="mx-2 mb-2">
      <b-row>

        <b-col
          cols="12"
          sm="6"
          class="d-flex align-items-center justify-content-center justify-content-sm-start"
        >
        
        </b-col>
        <!-- Pagination -->
        <b-col
          cols="12"
          sm="6"
          class="d-flex align-items-center justify-content-center justify-content-sm-end"
        >

          <b-pagination
            v-model="pagination.page"
            :page.sync="pagination.page"
            :total-rows="pagination.total"
            :index="1"
            :per-page="pagination.per_page"
            aria-controls="video-row"
            align="right"
            @change="handlePaginationChange"
          >
            <template #prev-text>
              <feather-icon
                icon="ChevronLeftIcon"
                size="18"
              />
            </template>
            <template #next-text>
              <feather-icon
                icon="ChevronRightIcon"
                size="18"
              />
            </template>
          </b-pagination>

        </b-col>

      </b-row>
    </div>
  </div>
</template>
<style >
    iframe{
        width:100% !important;
        height: 200px !important;
    }
</style>
<script>
import { BCard, BCardText,BTable, BRow,BCol,BPagination,BSpinner,BButton,BFormInput } from 'bootstrap-vue'
import VideoCard from './videoCard.vue';
import LoadingCard from '../loadingCard.vue';
import {getVideos} from './videos';

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
    BButton,
    BFormInput 
  },

    props: ['id'],
  setup() {
      const pagination = {
        page: 1,
        per_page: 8,
        total: null,
        keyword: null,
        view: true,
      };
      const list = null;
      const loading = true;
      const creator= null;

      return {
          list,
          pagination,
          loading,
          creator
      }
  },
  created() {
    this.getList(); 
  },
  methods: {
    getVideos,
    async getList() {
      this.loading = true;
      const data = await  this.getVideos();
      if( data ) {
        this.list = data.videos.data
        this.pagination.per_page = data.videos.per_page
        this.pagination.total =data.videos.total;
        this.pagination.page=data.videos.page
        this.creator = data.creator;
      }
      this.loading = false;
        
    },
    async handlePaginationChange( val ){
       this.pagination.page=val;
        this.getList()
    },
    async handleSearchVideo() {
      this.getList();
    },

    async handleClearKeyword(val) {
      if( !val ) {
        this.getList();
      }
    }
  }
}
</script>

<style>

</style>
