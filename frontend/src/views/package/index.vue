<template>
    <div style="min-height:300px">
        <b-overlay :show="$store.state.app.shallShowOverlay" no-wrap opacity="0.5" blur="1px"></b-overlay>
        <!-- <b-card> -->
        <b-row class="mb-3" gap="2">
            <!-- Per Page -->
            <b-col cols="12" md="6">

                <b-row>
                    <b-col cols="12" md="4" class=" text-info">
                        <v-select :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                            :options="['Wellness', 'Women Care', 'Lifeskill', 'Workshop']" :clearable="true" v-model="type"
                            input-id="program_type" class="text-info border-info" @input="typeChanged" />
                    </b-col>
                    <b-col cols="12" md="4" class="text-info">
                        <v-select :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                            :options="['Diet Program', 'Yoga / Fitness Program', 'Counselling Program', 'Arika Program', 'Umika Program']"
                            :clearable="true" v-model="category" input-id="category" class="text-info border-info"
                            placeholder="Program Category" @input="typeChanged" />
                    </b-col>
                    <b-col cols="12" md="4" class=" text-info">
                        <v-select v-model="plan" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                            :options="['1 Month', '3 Months', '6 Months', '12 Months', 'Free Session']" :clearable="true"
                            input-id="duration" class="text-info border-info" placeholder="Plan Duration"
                            @input="planChanged" />
                    </b-col>
                </b-row>
            </b-col>


            <!-- Search -->
            <b-col cols="12" md="6" class="d-flex justify-content-end">
                <b-row>
                    <b-col cols="12" md="4" class=" text-info">
                        <b-button @click="fetchRecent" variant="link" :class="recent">Recent</b-button>
                    </b-col>
                    <b-col cols="12" md="4" class=" text-info">
                        <b-button variant="link" @click="sortAlphabetically" :class="sort">Alphabetically</b-button>
                    </b-col>
                </b-row>
            </b-col>
        </b-row>
        <b-row :key="key">
            <b-col :sm="5" :md="4" :lg="3" :xl="3" v-for="program in packages" :key="program.id">
                <b-card class="ecommerce-card" style="width: 96%;" no-body>
                    <!-- Product Details -->
                    <img :src="'/view/file?path=' + program.image" alt="Card image cap" class="card-img-top"
                        style="height: 160px;">
                    <b-card-body>
                        <div class="align-items-center justify-content-center">
                            <b-row class="mb-2">

                                <b-col cols="10">
                                    <h5 class="mb-1 text-info heading" style="font-weight:bolder" :title="program.title">
                                        {{ program.title }}
                                    </h5>
                                </b-col>
                                <b-col cols="10" class="mb-1 heading">
                                    <span class="text-primary ">
                                        {{ program.category }}
                                    </span>
                                </b-col>

                                <b-col cols="12" class="d-flex">
                                    <small class="plan-span" :title="program.plans ? program.plans.join('/') : ''">
                                        <feather-icon icon="ClockIcon"></feather-icon>&nbsp;Plan:
                                        {{ getPlanName(program) }}
                                    </small>
                                </b-col>
                            </b-row>
                        </div>
                        <div class="d-flex align-items-center item-rating justify-content-center footer-button">
                            <b-button size="sm" variant="info mr-1"
                                :to="{ name: 'viewPackage', params: { 'id': program.id } }">
                                <span style="color:#fff" class="text-nowrap">Know More</span>
                            </b-button>
                            <b-button size="sm" variant="primary" :disabled="program.enrolled == 1"
                                @click="notifyAdmin(program)">
                                <span style="color:#fff" class="text-nowrap">Free Session</span>
                            </b-button>
                            <!-- <b-button  size="md" variant="primary" v-if="(program.duration !== 'Free Session') && (program.payment_type === 'Paid')" 
                                    :disabled="program.enrolled == 1" @click="processPayment(program)" > 
                                    <span  style="color:#fff" class="text-nowrap">Pay Now</span>
                                </b-button>
                                 <b-button size="md" variant="primary" v-else  :disabled="program.enrolled == 1" @click="saveEnrollDetail(program)" >
                                    <span style="color:#fff"  class="text-nowrap">Enroll</span>
                                </b-button> -->
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
</style>

<script>
import {
    BCard,
    BCardText,
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
        BCardBody
    },
    data() {
        let self = this;
        return {
            rzp1: '',
            key: 0,
            rzpayment_id: '',
            pagination: {
                page: 1,
                per_page: 10,
                total: null
            },
            list: [],
            category:"",
            package: null,
            required,
            type: 'Create Pakage',
            groupPackages: [],
            readonly: false,
            errors: [],
            mappingData: {
                partners: [],
                cost: null
            },
            totalRows: 0,
            partners: [],
            paymentOptions: {
                "key": process.env.MIX_RAZOR_KEY,
                "currency": "INR",
                "name": "Prajana",
                "description": "Test Transaction",
                "prefill": {
                    "name": this.$store.state.auth.user.name,
                    "email": this.$store.state.auth.user.email,
                    "contact": this.$store.state.auth.user.phone
                },
                "theme": {
                    "color": "#425b76",
                    "width": "500px",
                    "height": "900px"
                },

            },
            type: null,
            plan: null,
            recent: 'border-info text-info',
            sort: null
        };
    },
    directives: {
        Ripple,
    },
    setup() {
        const packages = null;
        const loading = true;
        const sortBy = "1";
        const isSortDirDesc = false;
        const columns = [
            { key: "index", label: "Sr. No.", sortable: true },
            { key: "title", label: "Title", sortable: true },
            { key: "category", label: "Category", sortable: true },
            { key: "sub_category", label: "Sub Category", sortable: true },
            { key: "description", label: "Description", sortable: true },
            { key: "cost", label: "Payment", sortable: false }
        ];

        const options = null;
        return {
            packages,
            options,
            loading,
            sortBy,
            isSortDirDesc,
            columns,
        };
    },
    watch: {
        "pagination.per_page": function (val) {
            this.handleFilterChange(val);
        }
    },
    created() {
        this.getList();

    },
    methods: {
        async getList() {
            try {
                this.loading = true;
                const { data } = await axios.get("package", {
                    params: this.pagination
                });
                console.log(data)
                this.packages = data.packages;
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
        handleFilterChange(val) {
            this.pagination.per_page = val;
            this.getList();
        },
        processPayment(pkg) {
            this.package = pkg
            this.paymentOptions.amount = pkg.cost * 100;
            this.createOrder(pkg)
                .then(({ data }) => {
                    this.paymentOptions.order_id = data.order.id;
                    let self = this;
                    this.paymentOptions.handler = async function (response) {
                        response.id = self.package.package_user_id;
                        await self.savePaymentDetail(response)
                            .then(() => {
                                self.getList();
                            });
                    }
                    this.rzp1 = new Razorpay(this.paymentOptions);
                    this.rzp1.on('payment.success', function () {

                    })
                    this.rzp1.open();
                });
        },
        async createOrder(pkg) {
            return await axios.post('/razorpay/order', {
                "amount": pkg.cost * 100,
                "currency": "INR",
                "id": pkg.package_user_id
            });
        },

        async savePaymentDetail(response) {
            return await axios.post('/razorpay/payment', response);
        },
        typeChanged() {
            this.pagination.type = this.type;
            this.pagination.category = this.category;
            this.getList()
        },
        planChanged() {
            this.pagination.plan = this.plan;
            this.getList()
        },
        fetchRecent() {
            this.pagination.recent = true;
            this.recent = 'border-info text-info';
            this.sort = '';
            this.getList()
        },
        sortAlphabetically() {
            this.pagination.sort = true;
            this.sort = 'border-info text-info';
            this.recent = '';
            this.getList()
        },
        async saveEnrollDetail(program) {
            const { data } = await axios.post(`/package/${program.package_user_id}/enroll`);
            this.$toast({
                component: ToastificationContent,
                props: {
                    title: data.message,
                    icon: "BellIcon",
                    variant: data.success ? "success" : "danger"
                }
            });
            this.getList();
        },
        getPlanName(program) {
            return program.plans ? program.plans.slice(0, 5).join('/') : ''
        },
        async notifyAdmin(program) {
            const { data } = await axios.post('/program/freeSession', {
                id: program.id,
                package_user_id: program.package_user_id
            });
            this.$toast({
                component: ToastificationContent,
                props: {
                    title: data.message,
                    icon: "BellIcon",
                    variant: data.success ? "success" : "danger"
                }
            });
        }
    }
};
</script>

<style lang="scss" scoped>
.per-page-selector {
    width: 90px;
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
</style>
