<template>
  <div>
    <b-overlay :show="$store.state.app.shallShowOverlay" no-wrap opacity="0.5"  blur="1px"></b-overlay>
      <b-row class="mb-1" >
        <b-col
            cols="12"
            sm="5"
            class="d-flex align-items-center justify-content-start mb-1 mb-md-0"
          >

            <b-pagination
              v-model="pagination.page"
              :page.sync="pagination.page"
              :total-rows="pagination.total"
              :index="1"
              :per-page="pagination.per_page"
              aria-controls="video-row"
              align="left"
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
            md="7"
          >
          <div class="d-flex align-items-center justify-content-end">
            <v-select
              v-model="data.assignedVideos"
              :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
              :options="videos"
              :reduce="val => val.id"
              :clearable="true"
              multiple
              id="userVideos"
              class="d-inline-block mr-1"
              style="width:100%;background:#fff;"
              :class="error ? 'is-invalid' : ''"
              placeholder="Select Videos"
            />
             <b-form-datepicker id="expiry_date" :class="error ? 'is-invalid' : ''" :min="new Date()" v-model="data.expiry_date" placeholder="Expiry Date"></b-form-datepicker>
         
            
            <b-button
              variant="primary"
              @click="assignVideos"
            >
              <span class="text-nowrap">Assign Videos</span>
            </b-button>
          </div>
        </b-col>
      </b-row>


    <b-row id="video-row">
        <b-col v-for="video in list" :key="video.link" cols="3">
            <loading-card v-if="loading"></loading-card>
            <video-card v-else :video="video" :client_id="id"  @remove="getList"></video-card>
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
import { BCard, BCardText,BTable, BRow,BCol,BPagination,BSpinner,BOverlay,BFormInput,BButton,BFormGroup,BFormDatepicker,BFormInvalidFeedback} from 'bootstrap-vue'
import VideoCard from '../../video/card.vue';
import LoadingCard from '../../video/loadingCard.vue';

import vSelect from 'vue-select'
import {getUserVideos} from '../../video/group/videos';

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

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
    BButton,
    BFormGroup,
    BFormDatepicker,
    BFormInvalidFeedback
  },
  props: ['id'],
  data() {
    return {
      pagination : {
        page: 1,
        per_page: 8,
        total: null,
        keyword: null,
        user_id: this.id
      },
      list: [],
      loading: true,
      videos: [],
      error: null,
      data: {
        assignedVideos: [],
        client_id: this.id,
        expiry_date: null,
      }
    }
  },

  created() {
    this.getList();
    this.getVideoList();
  },
  methods: {
    getUserVideos,
    
    async getList() {
        try {
            this.loading = true;

            const { data } = await axios.get('/videos',{
                params: this.pagination
            });
            if( data.videos )
            {
              this.list = data.videos.data
              this.pagination.per_page = data.videos.per_page
              this.pagination.total =data.videos.total;
              this.pagination.page=data.videos.page
            }


        } catch(error) {
            console.log(error.response)
        }
        this.loading = false;
    },

    async getVideoList() {

        const data = await this.getUserVideos(this.id);
 
        if( data ) {
          const videoIds = []
            this.list.map(d => {
                videoIds.push(d.uri.split('/')[2]);
            });
          this.videos = data.videos.filter( v => {
            return !videoIds.includes(v.id);
          });

        }
     
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
    },

    async assignVideos() {
      try {
          if(this.data.expiry_date && this.data.assignedVideos.length) {
            this.error = null;
            const {data} = await axios.post('/user/assignment',this.data);
            this.data = {
              assignedVideos: [],
              client_id: this.id,
              expiry_date: null,
            };
            this.$toast({
              component: ToastificationContent,
              props: {
                  title: data.message,
                  icon: "BellIcon",
                  variant: data.success ? "success" : "danger"
              }
            });

            this.getData();
          }else {
            this.error = "please select videos/expiry date"
          }
         
      } catch(err) {
          console.log(err)
      }
    },

    getData() {
      this.getList();
      this.getVideoList();
    }
  }
}
</script>

<style>

</style>
