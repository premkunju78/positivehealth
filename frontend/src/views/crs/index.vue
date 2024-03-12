<template>
<div class="ecommerce-application" style="height: inherit">

    <section id="ecommerce-header">
        <b-overlay :show="$store.state.app.shallShowOverlay" no-wrap opacity="0.5" blur="1px"></b-overlay>
        <b-card>
            <b-row class="mb-2">
                <b-col cols="8" md="4" sm="6" class="justify-content-start mb-1 mb-md-0">
                    <b-form-group label="Specialization Type" label-for="from_date">
                        <v-select v-model="pagination.filters.specialization" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="specializations" :clearable="false" input-id="specialization" @input="getSpecialized" />
                    </b-form-group>
                </b-col>
                <b-col cols="8" md="4" sm="6" class="justify-content-start mb-1 mb-md-0">
                    <b-form-group label="Specialized In" label-for="to_date">
                        <v-select v-model="pagination.filters.specialized_in" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="specialized" :clearable="true" input-id="specialized" @input="getList" />
                    </b-form-group>
                </b-col>
            </b-row>
        </b-card>
    </section>

    <!-- Prodcuts -->
    <section class="grid-view wishlist-items" v-if="upgraded">
        <b-card v-for="user in users" :key="user.id" class="ecommerce-card" style="min-width: 310px;" no-body>
            <!-- Product Details -->
            <b-card-body>
                <div class="d-flex align-items-center">
                    <b-row class="mb-2">
                        <b-col>
                            <b-avatar size="80" variant="light" src="/images/users/usr.jpg" />
                        </b-col>
                        <b-col>
                            <h5 class="mb-0">
                                {{ user.name }}
                            </h5>
                            <b-badge variant="light-primary" class="profile-badge">
                                {{ user.specialized_in }}
                            </b-badge>
                        </b-col>
                    </b-row>
                </div>
                <div class="d-flex align-items-center item-rating">
                    <ul class="unstyled-list list-inline">
                        <li v-for="star in 5" :key="star" class="ratings-list-item" :class="{'ml-25': star-1}">
                            <feather-icon icon="StarIcon" size="16" :class="[{'fill-current': star <= 4}, star <= 4? 'text-warning' : 'text-muted']" />
                        </li>
                    </ul>
                </div>
            </b-card-body>

            <!-- Action Buttons -->
            <div class="item-options text-center">
                <b-button variant="light" class="btn-wishlist remove-wishlist" @click="openConsultantModal(user)">
                    <feather-icon icon="EyeIcon" />
                    <span>View</span>
                </b-button>

                <b-button variant="primary" class="btn-cart move-cart" :block="$store.getters['app/currentBreakPoint'] === 'xs'" @click="openReferModal(user)">
                    <feather-icon icon="PlusSquareIcon" class="mr-50" />
                    <span>Refer</span>
                </b-button>
            </div>
        </b-card>
    </section>

    <b-pagination v-if="pagination.total" v-model="pagination.page" :page.sync="pagination.page" :total-rows="pagination.total" :index="1" :per-page="pagination.per_page" aria-controls="video-row" align="right" @change="handlePaginationChange">
    </b-pagination>

    <b-modal id="refer-consultant" ok-only ok-title="Refer" centered size="md" @hidden="resetNotes" @ok="handleOk" title="Refer Consultant" ref="refer-consultant" no-close-on-backdrop>
        <b-row class="mb-2">
            <b-col cols="12" class="justify-content-center mb-1 mb-md-0">
                <b-form-group label="Rferral Notes" label-for="notes">
                    <b-form-textarea id="notes" v-model="mappingData.notes" :rows="4">
                    </b-form-textarea>
                </b-form-group>
            </b-col>
        </b-row>
    </b-modal>

    <b-modal id="consultant" hide-footer centered size="md" title="Consultant Details" ref="consultant" no-close-on-backdrop>
        <div class="card-body">
            <div class="user-avatar-section">
                <div class="d-flex align-items-center flex-column">
                    <img class="img-fluid rounded mt-3 mb-2" :src="user.avatar || require('@/assets/images/avatars/13-small.png')" height="110" width="110" alt="User avatar">
                    <div class="user-info text-center">
                        <h4>{{user.first_name}}</h4>
                        <span class="badge bg-light-secondary">{{user.specialization}}</span>
                    </div>
                </div>
            </div>

            <h4 class="fw-bolder border-bottom pb-50 mb-1">Details</h4>
            <div class="info-container">
                <ul class="list-unstyled">
                    <li class="mb-75">
                        <span class="fw-bolder me-25">Full Name:</span>
                        <span>{{user.name}}</span>
                    </li>
                    <li class="mb-75">
                        <span class="fw-bolder me-25">Email:</span>
                        <span>{{user.email}}</span>
                    </li>
                    <li class="mb-75">
                        <span class="fw-bolder me-25">Role:</span>
                        <span>{{user.role}}</span>
                    </li>
                    <li class="mb-75">
                        <span class="fw-bolder me-25">Contact:</span>
                        <span>{{user.phone}}</span>
                    </li>
                    <li class="mb-75">
                        <span class="fw-bolder me-25">Specialization:</span>
                        <span>{{user.specialization}}</span>
                    </li>
                    <li class="mb-75">
                        <span class="fw-bolder me-25">Specialization In:</span>
                        <span>{{user.specialized_in}}</span>
                    </li>
                </ul>
            </div>
        </div>
    </b-modal>
</div>
</template>
<style>
iframe {
    width: 100% !important;
    height: 200px !important;
}
</style>
<script>
import {
    BCard,
    BCardBody,
    BCardText,
    BLink,
    BBadge,
    BAvatar,
    BTable,
    BRow,
    BCol,
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
    BFormDatepicker
} from "bootstrap-vue";
import {
    ValidationProvider,
    ValidationObserver
} from "vee-validate";

import formValidation from "@core/comp-functions/forms/form-validation";
import {
    required
} from "@validations";


import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

import vSelect from "vue-select";

import Ripple from 'vue-ripple-directive'

import {
    ref
} from '@vue/composition-api'


export default {
    components: {
        BCard,
        BCardBody,
        BCardText,
        BLink,
        BBadge,
        BAvatar,
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
        BFormDatepicker
    },
    props: ['id'],
    computed: {
        upgraded: function(){
        return this.$store.state.auth.user.is_upgraded;
        }
    },
    data() {
        return {
            pagination: {
                page: 1,
                per_page: 10,
                total: null,
                filters: {},
                client_id: this.id
            },
            users: [],
            user: {},
            mappingData: {},
            totalRows: 0,
            specialized: [],
            specializations: ['Medical','Wellness Provider', 'Others'],
        };
    },
    directives: {
        Ripple,
    },
    setup() {
        const loading = true;
        const sortBy = "1";
        const isSortDirDesc = false;
        const columns = [{
                key: "index",
                label: "Sr. No.",
                sortable: true
            },
            {
                key: 'name',
                label: 'Full Name',
                sortable: true
            },
            {
                key: 'email',
                label: 'Email',
                sortable: true
            },
            {
                key: 'contact_no',
                label: 'Contact Number',
                sortable: true
            },
            {
                key: 'specialization',
                label: 'Specialization',
                sortable: true
            },
            {
                key: 'specialized_in',
                label: 'Specialized In',
                sortable: true
            },
            {
                key: 'actions'
            },
        ];

        return {

            loading,
            sortBy,
            isSortDirDesc,
            columns,
        };
    },
    methods: {
        async getSpecialized(val) {
            try {
                this.loading = true;
                const {
                    data
                } = await axios.get("consultants/specialized", {
                    params: {
                        specialization: val
                    }
                });
                this.specialized = data.specialized;
            } catch (err) {
                console.log(err);
            }
            this.getList();
            this.loading = false;
        },
        async getList() {
            try {
                this.loading = true;
                const {
                    data
                } = await axios.get("/consultants", {
                    params: this.pagination
                });
                this.users = data.users;
                this.pagination.per_page = data.per_page;
                this.pagination.total = data.total;
                this.pagination.page = data.page;
            } catch (err) {
                console.log(err);
            }
            this.loading = false;
        },

        handlePaginationChange(val) {
            this.pagination.page = val;
            this.getList();
        },

        openReferModal(user) {
            this.user = user;
            this.mappingData = {
                consultant_id: user.id,
                client_id: this.id,
                consultant_name: user.name,
                consultant_role: user.role_id,
                notes: ''
            };
            this.$refs['refer-consultant'].show();
        },

        openConsultantModal(user) {
            this.user = user;
            this.$refs['consultant'].show();
        },

        resetNotes() {
            this.mappingData = {};
        },
        async handleOk(bvModalEvt) {
            bvModalEvt.preventDefault();
            if (this.mappingData.notes) {
                try {
                    const {
                        data
                    } = await axios.post('/refer/consultant', this.mappingData);
                    this.$toast({
                        component: ToastificationContent,
                        props: {
                            title: data.message,
                            icon: "BellIcon",
                            variant: data.success ? "success" : "danger"
                        }
                    });
                    this.getList();
                    this.$nextTick(() => {
                        this.$refs['refer-consultant'].hide()
                    })
                } catch (err) {
                    console.log(err)
                }
            } else {
                this.$toast({
                    component: ToastificationContent,
                    props: {
                        title: 'Please add some notes first',
                        icon: "BellIcon",
                        variant: 'info'
                    }
                });
            }

        },
    }
};
</script>

<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";
@import "~@core/scss/base/pages/app-ecommerce.scss";
</style>
<style lang="scss" scoped>
.per-page-selector {
    width: 90px;
}
</style>
