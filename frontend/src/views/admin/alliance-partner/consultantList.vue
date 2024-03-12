<template>
    <b-row class="d-flex justify-content-center" >
        <b-col cols="9" >
            <div class="card ">
                <div class="card-body">

                    <!-- Connections -->
                    <div class="d-flex mt-2" v-for="consultant in consultants" :key="consultant.id">
                        <div class="flex-shrink-0">
                            <img :src="'/view/file?path='+consultant.avatar" alt="google" class="me-1" height="48" width="48">
                        </div>
                        <div class="d-flex align-item-center justify-content-between flex-grow-1 ml-1">
                            <div class="me-1" style="width:25%">
                                <p class="fw-bolder mb-0">{{consultant.name}}</p>
                                <small>{{consultant.specialized_in}}( {{consultant.specialization}} )</small><br>
                                <b><small>{{consultant.experience ? consultant.experience+ 'Years Of Experience' : ''}} </small></b>
                            </div>
                            
                            <div class="mt-50 mt-sm-0">
                            <b-form-checkbox v-model="consultant.checked" name="check-button" switch @change="mapConsultant(consultant)"></b-form-checkbox>
                            </div>
                        </div>
                    </div>
                    <b-pagination
                        v-if="pagination.total"
                        v-model="pagination.page"
                        :page.sync="pagination.page"
                        :total-rows="pagination.total"
                        :index="1"
                        :per-page="pagination.per_page"
                        aria-controls="package-row"
                        align="right"
                        @change="handlePaginationChange" 
                    >
                    </b-pagination>
                <!-- /Connections -->
                </div>
            </div>
        </b-col>
       
    </b-row >
   
</template>
<script>
import {BFormCheckbox, BRow,BCol,BPagination} from 'bootstrap-vue';
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
export default {
    components: {
        BFormCheckbox,
        BRow,
        BCol,
        BPagination
    },
    props: {
        id: {
            type: [Number,String],
            default: null
        }
    },
    data() {
        return {
            consultants: [],
            pagination: {
                page: 1,
                per_page: 10,
                total: 0,
                list:true,
                filters: {
                    'authorization' : 'Internal',
                    is_upgraded: true
                }
            },
        };
    },
    created(){
        this.getConsultants();
    },
    methods: {
        async getConsultants() {
            try {
                const { data } = await axios.get('/consultants',{
                    params: this.pagination
                });
                this.consultants = data.users;
                this.pagination.per_page = data.per_page;
                this.pagination.total = data.total;
                this.pagination.page = data.page;
            } catch( error ) {

            }
        },

        handlePaginationChange(val) {
            this.pagination.page = val;
            this.getConsultants();
        },
        async mapConsultant(consultant){
           try {
               let data;
                if( consultant.checked ) {
                    data  = await axios.post(`/alliance-partners/consultant`,{
                        consultant_id: consultant.id,
                        aliance_id: this.id
                    });
                }
                else {
                    data = await axios.delete(`/alliance-partners/consultant/${consultant.alian_consultant_id}`);
                }

                this.$toast({
                    component: ToastificationContent,
                    props: {
                        title: data.data.message,
                        icon: "EditIcon",
                        variant: data.data.success ? "success" : "info"
                    }
                });
                this.getConsultants();
                
            } catch( error ) {

            }
        }
    }
}
</script>ri