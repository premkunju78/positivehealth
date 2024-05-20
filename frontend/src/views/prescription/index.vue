<template>
    <b-card >
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

            <b-col
            cols="12"
            md="6"
            >
                <div class="d-flex align-items-center justify-content-end" v-permission="['create-prescription']">
                <!-- <b-form-input
                    v-model="searchQuery"
                    class="d-inline-block mr-1"
                    placeholder="Search..."
                /> -->
                    <router-link :to="{name: 'newPrescription', params: {id: this.id}}" v-permission="['create-prescription']">
                        <b-button
                            variant="primary"
                        >
                            <span class="text-nowrap">New prescription</span>
                        </b-button>
                    </router-link>

                </div>
          </b-col>
        </b-row>
         <b-table
                style="min-height:250px"
                ref="refRolesTable"
                class="position-relative"
                :items="prescriptions"
                responsive
                :fields="columns"
                :totalRows="pagination.total"
                primaryKey="index"
                :sort-by.sync="sortBy"
                show-empty
                empty-text="No matching records found"
                :sort-desc.sync="isSortDirDesc"
            >
                <template #cell(index)="data">
                    {{ data.index + 1 }}
                </template>
                 <template #cell(created_at)="data">
                    {{ moment(data.item.created_at).format('ll') }}
                </template>
                 <template #cell(actions)="data">
                    <b-dropdown variant="link" no-caret v-permission="['edit-prescription']">
                        <template #button-content>
                            <feather-icon
                                icon="MoreVerticalIcon"
                                size="16"
                                class="align-middle text-body"
                            />
                        </template>
                        <b-dropdown-item :to="{name: 'editPrescription', params:{prescriptionId: data.item.id}}" v-permission="['edit-prescription']">
                            <feather-icon icon="EditIcon" />
                            <span class="align-middle ml-50" >Edit</span>
                        </b-dropdown-item>
                        <b-dropdown-item @click="handleDeletePrescription(data.item.id)" v-permission="['delete-prescription']">
                            <feather-icon icon="TrashIcon" />
                            <span class="align-middle ml-50">Delete</span>
                        </b-dropdown-item>
                        <b-dropdown-item :to="{name: 'viewPrescription', params:{id: data.item.id, client_id: $route.params.id}}" v-permission="['delete-prescription']">
                                <feather-icon icon="EyeIcon" />
                                <span class="align-middle ml-50" >View</span>
                        </b-dropdown-item>
                    </b-dropdown>
                    <b-link :to="{name: 'viewPrescription', params:{id: data.item.id, client_id: $store.state.auth.user.id}}" 
                    v-if="role === 3">
                            <feather-icon icon="EyeIcon" />
                            <span class="align-middle ml-50" >View</span>
                    </b-link>
                    
                </template>
            </b-table>
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
    </b-card>
</template>

<script>
import {BLink,   BDropdown,BImg,
        BDropdownItem,BPagination,BTable,BCard, BCardText, BForm, BFormGroup, BFormInput, BFormInvalidFeedback, BButton, BRow,BCol, BFormCheckboxGroup, BOverlay } from 'bootstrap-vue'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import { required } from '@validations'
import { ref } from '@vue/composition-api'
import Ripple from 'vue-ripple-directive'
import formValidation from '@core/comp-functions/forms/form-validation'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import moment from 'moment';
import vSelect from "vue-select";

export default {
    components: {
        BTable,
        BPagination,
        BCard,
        BCardText,
        BForm,
        BFormGroup,
        BFormInput,
        BFormInvalidFeedback,
        BButton,
        ValidationProvider,
        ValidationObserver,
        BRow,
        BCol,
        BFormCheckboxGroup,
        BOverlay,
        vSelect,
        BDropdown,
        BDropdownItem,
        BLink,
        BImg

    },
    directives: {
        Ripple,
    },
    props: {
      id: {
        type: [Number,String],
        defaul: null
      }
    },
    computed:{
        role: function(){
            return this.$store.state.auth.user.role_id
        },
        client: function() {
            return this.id ? this.id : this.$store.state.auth.user.id;
        }
    },
    created(){
        if( [2,3].includes(this.role) ){
            this.columns.shift();
            this.columns.unshift( { key: "specialized_in", label: "Specialized In", sortable: true });
            this.columns.unshift( { key: "consultant", label: "Consultant_name", sortable: true });
            this.columns.unshift( { key: "index", label: "Sr. No.", sortable: true });
        }
        else if( this.role !== 1 || !this.id) 
        {
            this.columns.shift(); this.columns.shift();
            this.columns.unshift(  { key: "no", label: "Prescription No", sortable: true } );
        }
        this.getList();
    },
    data(){
        return {
            pagination: {
                page: 1,
                per_page: 10,
                total: null,
            },
            moment: moment,
            item: {},
        }
    },
    setup() {
        const prescriptions = null;
        const loading = true;
        const sortBy = "1";
        const isSortDirDesc = false;
        const columns = [
            { key: "index", label: "Sr. No.", sortable: true },
            { key: "no", label: "Prescription No", sortable: true },
            { key: "instructions", label: "Instructions", sortable: true },
            { key: "created_at", label: "Date", sortable: true },
            { key: "actions", label: "Actions", sortable: false },
        ];
       

        return {
            prescriptions,

            loading,
            sortBy,
            isSortDirDesc,
            columns,
        };
    },
    methods: {
        async getList(){
            try {
                this.pagination.id = this.client;
                this.pagination.client = this.id;
                const { data } = await axios.get("prescription", {
                    params: this.pagination
                });
                this.prescriptions = data.prescriptions;
            }catch (err) {
                console.log(err);
            }
        },

        handlePaginationChange(val) {
            this.pagination.page = val;
            this.getList();
        },

        async handleDeletePrescription(id){
			try {
                const data = await axios.delete(`/prescription/${id}`);
                this.$toast({
                    component: ToastificationContent,
                    props: {
                        title: data.data.message,
                        icon: "BellIcon",
                        variant: data.data.success ? "success" : "danger"
                    }
                });
				this.getList();
            } catch (err) {
                console.log(err);
            }
		},

        viewFileDialog( item ) {
            this.item = item;
            this.$refs['view-files'].show();
        },
        resetItem() {
            this.item = {};
        }
    }
}
</script>
<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";

.modal-body{
    min-height: 300px !important;
}
</style>