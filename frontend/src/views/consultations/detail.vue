<template>
    <b-card no-body class="mb-0">
        <b-row class="mt-4">
            <b-col cols="12" style="text-align:right">
                 <router-link :to="{ name:   'Consultations'}">
                    <b-button
                        
                        variant="outline-secondary"
                        type="reset"
                        class="mb-1 mb-sm-0 mr-0 mr-sm-1"
                        :block="
                            $store.getters['app/currentBreakPoint'] === 'xs'
                        "
                    >
                        <feather-icon  icon="ArrowLeftIcon"></feather-icon>
                        Back
                    </b-button>
                </router-link>
            </b-col>
            <b-col :md="8" :sm="12">
                <div class="align-items-center justify-content-center ml-3">
                    <b-row>
                        <b-col cols="10">
                            <h3 class="mb-1 text-info" style="font-weight:bolder">
                                    {{ consultant.name }}
                            </h3>
                         </b-col>
                    </b-row>
                    <b-row>
                        <b-col cols="11"  style="text-align: justify;" v-html="consultant.bio">
                        </b-col> 
                        <b-col cols="11" >
                          <h5 class="mb-1 text-info" style="font-weight:bolder">
                              Specialization
                          </h5> 
                          <ul style="list-style:none" v-if="consultant.specialization">
                            <li>{{consultant.specialization}}({{consultant.specialized_in}})</li>
                          </ul>
                            <ul style="list-style:none" v-else>
                                <li style="font-weight:450;color:#c7c7c7">
                                    Nothing To Show
                                </li>
                            </ul>
                        </b-col>
                        <!-- <b-col cols="12">
                            <h5 class="mb-1 text-info" style="font-weight:bolder">
                                Skills
                            </h5>
                            <ul style="list-style:none" v-if="consultant.skills.length">
                                <li style="font-weight:450" v-for="skil in consultant.skills" :key="skil">
                                    {{skil.name}}
                                </li>
                            </ul>
                             <ul style="list-style:none" v-else>
                                <li style="font-weight:450;color:#c7c7c7">
                                  Nothing To Show
                                </li>
                            </ul>
                        </b-col>  -->
                        <b-col cols="12" class="mb-2" >
                            <h5 class="mb-1 text-info" style="font-weight:bolder">
                                   Languages Spoken
                            </h5>
                            <ul style="list-style:none" v-if="consultant.languages.length">
                                <li style="font-weight:450" v-for="language in consultant.languages" :key="language">
                                  {{language.name}}
                                </li>
                            </ul>
                            <ul style="list-style:none" v-else>
                                <li style="font-weight:450;color:#c7c7c7">
                                  Nothing To Show
                                </li>
                            </ul>
                        </b-col> 
                    </b-row>
                    <ul class="action-btn-list" v-if="$store.state.auth.user.id !== id" > 
                        <li v-if="$store.state.auth.user.role_id === 3">
                            <b-link style="color:#fff;float: right; min-width: 200px;"  class=" btn btn-primary text-nowrap" :to="{name:'book-appointments', params: {id: consultant.id, role:  consultant.role_id} }" >Consult</b-link>
                        </li>
                        <li v-if="$store.state.auth.user.role_id !== 3">
                            <b-link @click="referClientModal" style="color:#fff;float: right; min-width: 200px;"  class=" btn btn-success text-nowrap" >Refer</b-link>
                        </li>
                    </ul>
                </div> 
            </b-col>
            <b-col :md="4" :sm="12">
                <img class="card-img-top mt-1" :style="consultant.avatar? '':'height:200px' " :src="'/view/file?path='+consultant.avatar" :alt="consultant.first_name" >
            </b-col>
        </b-row>
        <b-modal id="client-selection-modal" ref="client-selection-modal" hide-footer title="Select Client" no-close-on-backdrop>
            <template #modal-title> Select Client</template>
            <div class="d-block text-left">
                <div class="mb-2"">
                      <v-select
                          :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                          :options="clientLists"
                          :reduce="val => val.id"
                          placeholder="Select Clients"
                          :clearable="true"
                          @input="openReferPage"
                      />                   
                </div>
            </div>
        </b-modal>
    </b-card>
</template>
<script>

import {BRow,BCol,BButton,BFormGroup,BLink,VBModal,BModal} from 'bootstrap-vue';

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import vSelect from "vue-select";
import FeatherIcon from '../../@core/components/feather-icon/FeatherIcon.vue';

export default {
    components: {
        BRow,
        BCol,
        BButton,
        BFormGroup,
        vSelect,
        FeatherIcon,
        BLink,
        VBModal,
        BModal
    },
    props:{
        id: {
            type: [Number,String],
            required: true,
        }
    },
    data() {
        return {
            loading: false,
            client_id: false,
            consultant:null,
            clientLists: []
        }
    },
    created() {
        this.consultantDetail();
    },
    methods: {
        async consultantDetail() {
            const {data} = await axios.get(`consultant/${this.id}`,{params:{slot:false}});
            this.consultant = data.user;
            this.consultant.skills = JSON.parse(data.user.skills);
            this.consultant.languages = JSON.parse(data.user.languages);
        },
        async referClientModal() {
            this.getClientLists();
            this.$refs['client-selection-modal'].show();            
        }, 
        async getClientLists(search) {
            const {data} = await axios.get('/clients', {
                params: {
                    'search': search,
                    'page': 1, 
                    'per_page': 200
                },
            });

            data.clients.forEach((value, index) => {
                this.clientLists.push({
                    'id': value.id,
                    'label': value.name
                })
            });

            console.log(this.clientLists)
        },
        async openReferPage(id){
            window.location.href="/crs/" + id + "/" + this.consultant.id;
        }

    }
}
</script>
<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";
::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
  color: #26a79d !important;
  opacity: 1; /* Firefox */
}
.v-select.vs--single .vs__selected {
    color: #26a79d;
}

.action-btn-list {
    display: flex;
    gap: 2rem;
    list-style-type: none;
    padding: 0;
    float: right;    
}

</style>

