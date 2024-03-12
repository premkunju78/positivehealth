<template>
    <div style="min-height:300px">
        <b-overlay :show="$store.state.app.shallShowOverlay" no-wrap opacity="0.5"  blur="1px"></b-overlay>
        <b-card v-permission="['view-accounts']">
            <b-row class="mb-2">
                <!-- Per Page -->
                <b-col
                    cols="12"
                    md="6"
                    class="d-flex align-items-center justify-content-start mb-1 mb-md-0"
                >
                    <label>Show</label>
                    <v-select
                        v-model="pagination.per_page"
                        :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                        :options="[10, 25, 50, 100, 1000]"
                        :clearable="false"
                        class="per-page-selector d-inline-block mx-50"
                    />
                    <label>entries</label>
                </b-col>

                <!-- Search -->
                <b-col cols="12" md="6">
                </b-col>
            </b-row>

            <b-table
                style="min-height:250px"
                ref="refRolesTable"
                class="position-relative"
                :items="users"
                responsive
                :fields="columns"
                :totalRows="pagination.total"
                primaryKey="index"
                :sort-by.sync="sortBy"
                show-empty
                empty-text="No matching records found"
                :sort-desc.sync="isSortDirDesc"
            >
                <!-- Column: Actions -->
                <template #cell(index)="data">
                    {{ data.index + 1 }}
                </template>
                <template #cell(name)="data">
                    <span class="text-primary">{{
                        data.item.name.toUpperCase()
                    }}</span>
                </template>
                <template #cell(description)="data">
                    <span class="text-primary" v-html=" data.item.description"></span>
                </template>

                <!-- Column: Actions -->
                <template #cell(payment_status)="data">
                    <span class="text-info" v-if="data.item.razorpay_payment_id">PAID</span>
                     <span v-else class="text-danger" >UNPAID</span>
                </template>
            </b-table>
            <b-pagination
                v-if="pagination.total"
                v-model="pagination.page"
                :page.sync="pagination.page"
                :total-rows="pagination.total"
                :index="1"
                :per-page="pagination.per_page"
                aria-controls="video-row"
                align="right"
                @change="handlePaginationChange"
            >
            </b-pagination>
        </b-card>
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
    BFormDatepicker
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
        BFormDatepicker
    },
    data() {
        let self = this;
        return {
          rzp1: '',
          rzpayment_id: '',
            pagination: {
                page: 1,
                per_page: 10,
                total: null
            },
            list:[],
			user: null,
			required,
			type: 'Create Pakage',
            groupPackages: [],
            readonly: false,
            errors: [],
            mappingData:{
                partners: [],
                cost: null
            },
            totalRows: 0,
            partners: [],
        };
    },
    directives: {
        Ripple,
    },
    setup() {
        const users = null;
        const loading = true;
        const sortBy = "1";
        const isSortDirDesc = false;
        const columns = [
            { key: "index", label: "Sr. No.", sortable: true },
            { key: "consultant", label: "Consultant name", sortable: true },
            { key: "client", label: "Client Name", sortable: true },
            { key: "transaction_date", label: "Transaction Date", sortable: false },
             { key: "cost", label: "Amount", sortable: false },
            { key: "payment_status", label: "Status", sortable: false },
        ];

        const options =null;
        return {
            users,
            options,
            loading,
            sortBy,
            isSortDirDesc,
            columns,
        };
    },
    watch: {
        "pagination.per_page": function(val) {
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
                const { data } = await axios.get("/payment/consultant", {
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
        handleFilterChange(val) {
            this.pagination.per_page = val;
            this.getList();
        },
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
</style>
