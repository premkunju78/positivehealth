<template>
    <div style="min-height:300px">
        <b-overlay :show="$store.state.app.shallShowOverlay" no-wrap opacity="0.5" blur="1px"></b-overlay>
        <!-- <b-card> -->
        <b-row class="mb-3" gap="2">
            <!-- Per Page -->
            <b-col cols="12" md="6">
                <b-row>
                    <b-col cols="12" md="5" class=" text-info">
                        <v-select :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :clearable="true" v-model="roleId"
                            :options="specializations" input-id="role" class="text-info border-info" placeholder="All"
                            @input="roleChanged" />
                    </b-col>
                </b-row>
            </b-col>
        </b-row>
        <b-row :key="key">
            <b-col :sm="5" :md="4" :lg="3" :xl="3" v-for="consultant in consultants" :key="consultant.id">
                <b-card class="ecommerce-card" style="width: 99%; height:350px" no-body>
                    <div class="wishlist-btn-section" v-if="role === 3">
                       <b-button :class="wishlist_consultant_ids.includes(consultant.id) ? ' filled' : ''" class="wishlist-btn" @click="makeInWishlist(consultant.id)"><feather-icon icon="HeartIcon" size="20"/></b-button>
                    </div>
                    <!-- Product Details -->
                    <img :src="'/view/file?path=' + consultant.avatar" alt="Card image cap" class="card-img-top"
                        style="height:160px">
                    <b-card-body>
                        <div class="align-items-center justify-content-center">
                            <b-row class="mb-2">

                                <b-col cols="10">
                                    <h5 class="mb-1 text-info heading" style="font-weight:bolder" :title="consultant.name">
                                        {{ consultant.name }}
                                    </h5>
                                </b-col>
                                <b-col cols="10" class="mb-1 heading">
                                    <span class="text-primary">
                                        {{ consultant.specialized_in }}
                                    </span>
                                </b-col>
                                <b-col cols="10">
                                    <small style="font-weight:550"> {{
                                        consultant.experience ? consultant.experience +
                                    'Years Of Experience' : ''
                                    }} </small>
                                </b-col>

                            </b-row>
                        </div>
                        <div class="d-flex align-items-center item-rating justify-content-center footer-button">
                            <b-button variant="info mr-1" size="sm"
                                :to="{ name: 'viewConsultant', params: { 'id': consultant.id } }">
                                <span style="color:#fff" class="text-nowrap">Know More</span>
                            </b-button>
                            <!--
                            <b-button variant="primary" size="sm" v-if="role === 3">
                                <b-link style="color:#fff" class="text-nowrap"
                                    :to="{ name: 'book-appointments', params: { id: consultant.id, role: consultant.role_id } }">Consult</b-link>
                            </b-button>
                            -->
                            <span></span>
                        </div>
                    </b-card-body>


                </b-card>
            </b-col>
        </b-row>

        <b-pagination v-if="pagination.total" v-model="pagination.page" :page.sync="pagination.page"
            :total-rows="pagination.total" :index="1" :per-page="pagination.per_page" aria-controls="video-row"
            align="right" @change="handlePaginationChange">
        </b-pagination>
        <!-- </b-card> -->
    </div>
</template>
<style>
iframe {
    width: 100% !important;
    height: 200px !important;
}

.ecommerce-card img {
    object-fit: contain;
    object-position: top;
}
</style>

<script>
import {
    BCard,
    BCardText,
    BTable,
    BRow,
    BCol,
    BLink,
    BPagination,
    BSpinner,
    BButton,
    BDropdown,
    BDropdownItem,
    VBModal,
    BModal,
    BFormInvalidFeedback,
    BFormInput,
    BFormTextarea,
    BFormGroup,
    BOverlay,
    BFormCheckbox,
    BFormDatepicker,
    BCardBody
} from "bootstrap-vue";
import { ValidationProvider, ValidationObserver } from "vee-validate";

import formValidation from "@core/comp-functions/forms/form-validation";
import { required } from "@validations";


import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

import vSelect from "vue-select";

import Ripple from 'vue-ripple-directive'

import { ref } from '@vue/composition-api'


export default {
    components: {
        BCard,
        BCardText,
        BTable,
        BRow,
        BCol,
        BPagination,
        BSpinner,
        vSelect,
        BButton,
        BDropdown,
        BDropdownItem,
        VBModal,
        BModal,
        ValidationProvider,
        ValidationObserver,
        BFormInvalidFeedback,
        BFormInput,
        BFormGroup,
        BFormTextarea,
        BOverlay,
        BFormCheckbox,
        BFormDatepicker,
        BCardBody,
        BLink
    },
    data() {
        return {
            key: 0,
            pagination: {
                page: 1,
                per_page: 10,
                total: null,
                filters: {}
                //filters: { authorization: 'Internal'},
            },
            roleId: null,
            list: [],
            consultant: null,
            required,
            specializations: ['Medical', 'Wellness Provider', 'Others'],
            errors: [],
            totalRows: 0,
            type: null,
            plan: null,
            recent: 'border-info text-info',
            sort: null,
            wishlist_consultant_ids: []
        };
    },
    computed: {
        role: function () {
            return this.$store.state.auth.user.role_id;
        }
    },
    directives: {
        Ripple,
    },
    setup() {
        const consultants = null;
        const loading = true;
        const options = null;
        return {
            consultants,
            options,
            loading
        };
    },
    watch: {
        "pagination.per_page": function (val) {
            this.handleFilterChange(val);
        }
    },
    created() {
        this.getList();
        this.getFavoritesConsultants();

    },
    methods: {
        async getList() {
            try {
                this.loading = true;
                if(this.$store.state.auth.user.detail.hide_consultants){
                    this.pagination.hide_consultants = this.$store.state.auth.user.hide_consultants;
                }
                if(this.$store.state.auth.user.role.is_consultant=='1'){
                    this.pagination.consultant_id = this.$store.state.auth.user.id;
                }
                const { data } = await axios.get("/consultants", {
                    params: this.pagination
                });
                this.consultants = data.users;
                this.pagination.per_page = data.per_page;
                this.pagination.total = data.total;
                this.pagination.page = data.page;
            } catch (err) {
                console.log(err);
            }
            this.loading = false;
        },
        async getFavoritesConsultants() {
            const { data } = await axios.get("/get-favorites-consultants");                    
            this.wishlist_consultant_ids = data.user.consultant_ids
        },
        handlePaginationChange(val) {
            this.pagination.page = val;
            this.getList();
        },
        handleFilterChange(val) {
            this.pagination.per_page = val;
            this.getList();
        },
        roleChanged() {
            this.pagination.filters.specialization = this.roleId;
            this.getList()
        },
        async makeInWishlist(consultant_id) {
            if(this.wishlist_consultant_ids.includes(consultant_id)) {
                this.wishlist_consultant_ids.splice(this.wishlist_consultant_ids.indexOf(consultant_id), 1)                                                     
            }else{
                this.wishlist_consultant_ids.push(consultant_id)                                     
            }

            const { data } = await axios.post("/make-favorites-consultants", {
                consultant_ids: this.wishlist_consultant_ids,
                user_id: this.$store.state.auth.user.id
            });

            console.log(this.wishlist_consultant_ids);
        }

    }
};
</script>

<style lang="scss" scoped>
.per-page-selector {
    width: 90px;
}


.footer-button {
    position: absolute;
    bottom: 25px;
    min-width: 100%
}
</style>

<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";

.razorpay-checkout-frame {
    height: 100% !important;
}

::placeholder {
    /* Chrome, Firefox, Opera, Safari 10.1+ */
    color: #26a79d !important;
    opacity: 1;
    /* Firefox */
}

.v-select.vs--single .vs__selected {
    color: #26a79d;
}

.wishlist-btn-section {
    position: relative;
}

.wishlist-btn-section .wishlist-btn {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: transparent !important;
    color: #000000 !important;
    border: 0;    
}

.wishlist-btn.filled svg {
    fill: #F00;
    stroke: #F00;
}

</style>
