<template>
    <div style="min-height:300px">
        <div v-if="false">
            <b-overlay :show="$store.state.app.shallShowOverlay" no-wrap opacity="0.5" blur="1px"></b-overlay>
            <!-- <b-card> -->
            <b-row class="mb-3" gap="2">
                <!-- Per Page -->
                <b-col cols="12" md="6">

                    <b-row>
                        <b-col cols="12" md="5" class=" text-info">
                            <v-select :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                :options="['Consultation', 'LifeSkill', 'Wellness', 'Workshop']" :clearable="true"
                                v-model="type" input-id="program_type" class="text-info border-info"
                                placeholder="Program Type" @input="typeChanged" />
                        </b-col>
                        <b-col cols="12" md="5" class=" text-info">
                            <v-select v-model="plan" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                :options="['1 Month', '3 Months', '6 Months', '12 Months', 'Free Session']"
                                :clearable="true" input-id="duration" class="text-info border-info"
                                placeholder="Plan Duration" @input="planChanged" />
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
                <b-col :sm="5" :md="4" :lg="3" :xl="3" v-for="program in       packages      " :key="program.id">
                    <b-card class="ecommerce-card" no-body>
                        <!-- Product Details -->
                        <img :src="'/view/file?path=' + program.image" alt="Card image cap" class="card-img-top"
                            style="height: 160px;">
                        <b-card-body>
                            <div class="align-items-center justify-content-center">
                                <b-row class="mb-2">

                                    <b-col cols="10">
                                        <h5 class="mb-1 text-info heading" style="font-weight:bolder"
                                            :title="program.title">
                                            {{ program.title }}
                                        </h5>
                                    </b-col>
                                    <b-col cols="10" class="mb-1 heading">
                                        <span class="text-primary">
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
                            <div class="d-flex align-items-center item-rating justify-content-center">
                                <b-button size="md" variant="info mr-1" :to="{
                                    name: 'viewPackage', params: { 'id': program.id, 'fdf':" dsfd" } }">
                                    <span style="color:#fff" class="text-nowrap">Know More</span>
                                </b-button>
                                <span></span>
                            </div>
                        </b-card-body>


                    </b-card>
                </b-col>
            </b-row>
        </div>
        <b-table style="min-height:250px" ref="refRolesTable" class="position-relative" :items=" packages " responsive
            :fields=" columns " :totalRows=" pagination.total " primaryKey="index" :sort-by.sync=" sortBy " show-empty
            empty-text="No matching records found" :sort-desc.sync=" isSortDirDesc ">
            <!-- Column: Actions -->
            <template #cell(index)=" data ">
                {{ data.index + 1 }}
            </template>
            <template #cell(name)=" data ">
                <span class="text-primary">{{
                    data.item.name.toUpperCase()
                    }}</span>
            </template>
            <template #cell(start_date)=" { item } ">
                {{ (item.start_date) ? moment(item.start_date).format('DD-MM-YYYY'):'-' }}
            </template>
            <template #cell(end_date)=" { item } ">
                {{ (item.end_date) ? moment(item.end_date).format('DD-MM-YYYY') :'-' }}
            </template>
            <template #cell(description)=" data ">
                <span class="text-primary" v-html=" data.item.description "></span>
            </template>
            <template #cell(sessions)=" data ">
                <p style="font-weight:bold">{{ data.item.plan }}</p>
                <div v-for="  session   in   data.item.sessions  ">
                    <p>{{ session.type }} - {{ session.no_of_sessions }}</p>
                </div>
            </template>
            <!-- Column: Actions -->
            <template #cell(cost)=" data ">
                <span class="text-primary" v-if=" data.item.transaction_id ">&nbsp; ₹ {{
                    data.item.cost
                    }}</span>
                <b-button v-else variant="success" :block=" $store.getters['app/currentBreakPoint'] === 'xs' " type="submit"
                    class="mr-2" v-permission=" ['view-pkgs'] " @click="processPayment(data.item)">
                    Pay &nbsp; ₹ {{ data.item.cost }}
                </b-button>

            </template>
            <template #cell(action)=" data ">

                <b-button size="sm" variant="info mr-1" :to=" { name: 'viewPackage', params: { 'id': data.item.id } } ">
                    <span style="color:#fff" class="text-nowrap">Know More</span>
                </b-button>
            </template>
        </b-table>
        <b-pagination v-if=" pagination.total " v-model=" pagination.page " :page.sync=" pagination.page "
            :total-rows=" pagination.total " :index=" 1 " :per-page=" pagination.per_page " aria-controls="video-row"
            align="right" @change=" handlePaginationChange ">
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
import moment from 'moment'


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
                total: null,
                enrolled: true
            },
            list: [],
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
            type: null,
            plan: null,
            recent: 'border-info text-info',
            sort: null,
            moment: moment
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
            { key: "title", label: "Program Name", sortable: true },
            { key: "category", label: "Category", sortable: true },
            { key: "sessions", label: "Sessions", sortable: true },
            { key: "start_date", label: "Start Date", sortable: true },
            { key: "end_date", label: "End Date", sortable: true },
            // { key: "description", label: "Description", sortable: true },
            { key: "cost", label: "Cost", sortable: false },
            { key: "action", label: "Action", sortable: false }
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
                const { data } = await axios.get("packages/me", {
                    params: this.pagination
                });
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
        typeChanged() {
            this.pagination.type = this.type;
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
            return program.plans ? program.plans.slice(0, 2).join('/') : ''
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
