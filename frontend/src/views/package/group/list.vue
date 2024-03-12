<template>
    <div style="min-height:300px">   
        <b-overlay :show="$store.state.app.shallShowOverlay" no-wrap opacity="0.5"  blur="1px"></b-overlay>
            <b-card :title="type"  v-permission="['create-program']" >
                <validation-observer
                    #default="{ handleSubmit }"
                    ref="refFormObserver"
                >
                    <form ref="form" @submit.prevent="handleSubmit(onSubmit)">
                        <b-row>
                            <b-col cols="12" md="6">
                                <validation-provider
                                    #default="validationContext"
                                    name="Name"
                                    rules="required"
                                >
                                    <b-form-group label="Name" label-for="name">
                                        <b-form-input
                                            id="name-input"
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                            v-model="groupData.name"
                                            :readonly="readonly"
                                            :class="errors.name ? 'is-invalid' : ''"
                                            :area-invalid="errors.name ? true : false"
                                        />
                                        <b-form-invalid-feedback v-if="errors.name">
                                             {{errors.name[0] }}
                                        </b-form-invalid-feedback>
                                        <b-form-invalid-feedback v-else>
                                            {{ validationContext.errors[0] }}
                                        </b-form-invalid-feedback>
                                    </b-form-group>
                                </validation-provider>
                            </b-col>
                            <b-col cols="12" md="6">
                                <validation-provider
                                    #default="validationContext"
                                    name="Description"
                                    rules="required"
                                >
                                    <b-form-group
                                        label="Description"
                                        label-for="description"
                                    >
                                        <b-form-input
                                            id="description"
                                            v-model="groupData.description"
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                             :readonly="readonly"
                                        />
                                        <b-form-invalid-feedback>
                                            {{ validationContext.errors[0] }}
                                        </b-form-invalid-feedback>
                                    </b-form-group>
                                </validation-provider>
                            </b-col>
                             <b-col cols="12" md="6" v-if="type === 'Create Group'">
                                <validation-provider
                                    #default="validationContext"
                                    name="packages"
                                >
                                    <b-form-group
                                        label="Select Programms"
                                        label-for="packages"
                                        :state="getValidationState(validationContext)"
                                    >
                                       <v-select
                                            v-model="groupData.packages"
                                            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                            :options="packages"
                                            :reduce="val => val.id"
                                            :clearable="true"
                                            multiple
                                            id="packages"
                                        />
                                        <b-form-invalid-feedback :state="getValidationState(validationContext)">
                                            {{ validationContext.errors[0] }}
                                        </b-form-invalid-feedback>
                                    </b-form-group>
                                </validation-provider>
                            </b-col>

                             <b-col cols="12" md="6" v-else>
                                <validation-provider
                                    #default="validationContext"
                                    name="packages"
                                >
                                    <b-form-group
                                        label="Add Pragramms"
                                        label-for="packages"
                                        :state="getValidationState(validationContext)"
                                    >
                                       <v-select
                                            v-model="groupData.packages"
                                            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                            :options="groupPackages"
                                            :reduce="val => val.id"
                                            :clearable="true"
                                            multiple
                                            id="groupPackages"
                                        />
                                        <b-form-invalid-feedback :state="getValidationState(validationContext)">
                                            {{ validationContext.errors[0] }}
                                        </b-form-invalid-feedback>
                                    </b-form-group>
                                </validation-provider>
                            </b-col>
                        </b-row>
                        <b-row style=" margin-top: 2%;">
                            <!-- Action Buttons -->
                            <b-button
                                variant="primary"
                                :block="
                                $store.getters['app/currentBreakPoint'] === 'xs'"
                                type="submit"
                                 class="mr-2"
                                v-permission="['create-program']"
                            >
                            Save Changes
                            </b-button>
                            <b-button
                                v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                                type="button"
                                @click="resetFormCard"
                                variant="outline-secondary"
                            >
                                Cancel
                            </b-button>
                         
                        </b-row>
                    </form>
                </validation-observer>
        </b-card>
        <b-card>
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
                :items="groups"
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

                <!-- Column: Actions -->
                <template #cell(actions)="data">
                    <b-dropdown variant="link" no-caret>
                        <template #button-content>
                            <feather-icon
                                icon="MoreVerticalIcon"
                                size="16"
                                class="align-middle text-body"
                            />
                        </template>
                         <b-dropdown-item @click="viewPackageModal(data.item)"  v-permission="['view-program']">
                            <feather-icon icon="EyeIcon" />
                            <span class="align-middle ml-50">View Programms</span>
                        </b-dropdown-item>
                        <b-dropdown-item @click="editGroup(data.item)"  v-permission="['edit-program']">
                            <feather-icon icon="EditIcon" />
                            <span class="align-middle ml-50" >Edit</span>
                        </b-dropdown-item>
                         <b-dropdown-item   @click="viewAssignModal(data.item)"  v-permission="['edit-program']">
                            <feather-icon icon="UsersIcon" />
                            <span class="align-middle ml-50">Assign</span>
                        </b-dropdown-item>
                         <b-dropdown-item @click="viewUserModal(data.item)"  v-permission="['edit-program']">
                            <feather-icon icon="EyeIcon" />
                            <span class="align-middle ml-50">View Users</span>
                        </b-dropdown-item>
                        <b-dropdown-item @click="handleDeleteGroup(data.item.id)" v-permission="['delete-program']">
                            <feather-icon icon="TrashIcon" />
                            <span class="align-middle ml-50">Delete</span>
                        </b-dropdown-item>
                    </b-dropdown>
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
            <b-modal
                v-permission="['edit-program']"
                id="assignUser"
                ref="assign"
                ok-only
                ok-title="Assign"
                @show="getAssignedPackages(true)"
                @hidden="resetData"
                @ok="handleOk"
                centered
                size="lg"
                title="Program Assignment"
                no-close-on-backdrop
            >
            <validation-observer
                    #default="{ handleSubmit }"
                    ref="refFormObserver"
            >
                <form ref="form"  @submit.prevent="handleSubmit(onAssign)">
                    <b-row>
                        <b-col col="3" md="3"></b-col>
                         <b-col cols="12" md="6" >
                            <validation-provider
                                #default="validationContext"
                                name="alliance-partners"
                                rules="required"
                            >
                                <b-form-group
                                    label="Select Alliance Partners"
                                    label-for="partners"
                                    :state="getValidationState(validationContext)"
                                    >
                                    <v-select
                                        v-model="mappingData.partners"
                                        :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                        :options="partners"
                                        :reduce="val => val.id"
                                        :clearable="false"
                                        multiple
                                        input-id="partners"
                                        @input="getAssignedPackages(true)"
                                    />
                                      <b-form-invalid-feedback :state="getValidationState(validationContext)">
                                        {{ validationContext.errors[0] }}
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>
                                
                        </b-col>
                    </b-row>
                    <b-row v-for="pkg in plist" :key="pkg.title">
                        <b-col cols="12" md="6">
                            <validation-provider
                                #default="validationContext"
                                name="Program Name"
                                rules="required"
                            >
                            <b-form-group label="Program Name" :label-for="pkg.title"  :state="getValidationState(validationContext)">
                                <b-form-input :readonly="true" :id="pkg.title" :value="pkg.title"></b-form-input>
                            </b-form-group>
                            </validation-provider>
                        </b-col>
                        <b-col cols="12" md="6">
                            <validation-provider
                                #default="validationContext"
                                name="cost"
                                rules="required"
                            >
                                <b-form-group
                                    label="Cost"
                                    label-for="cost"
                                    :state="getValidationState(validationContext)"
                                    >
                                    <b-form-input type="number" id="cost" :min="1" v-model="mappingData.cost[pkg.id]"></b-form-input>
                                    <b-form-invalid-feedback :state="getValidationState(validationContext)">
                                        {{ validationContext.errors[0] }}
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>
                        </b-col>
                         <!-- <b-col cols="12" md="6">
                            <validation-provider
                                #default="validationContext"
                                name="Expiry Date"
                                rules="required"
                            >
                                <b-form-group
                                    label="Expiry Date"
                                    label-for="expiry_date"
                                    :state="getValidationState(validationContext)"
                                    >
                                    <b-form-datepicker id="expiry_date" :min="new Date()" v-model="mappingData.expiry_date"></b-form-datepicker>
                                    <b-form-invalid-feedback :state="getValidationState(validationContext)">
                                        {{ validationContext.errors[0] }}
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider> 
                        </b-col>-->
                       
                    </b-row>
                    
                </form>
            </validation-observer>
            </b-modal>
            <b-modal
                v-permission="['edit-program']"
                id="view-users"
                hide-footer
                centered
                size="lg"
                @show="getAssignedUsers"
                @hidden="resetUsers"
                title="Assigned Users"
                ref="view-users"
                no-close-on-backdrop
              
            >
            <b-table
                style="min-height:250px"
                ref="refRolesTable"
                class="position-relative"
                :items="list"
                responsive
                :fields="ucolumns"
                :totalRows="totalRows"
                primaryKey="index"
                :sort-by.sync="sortBy"
                show-empty
                empty-text="No matching records found"
                :sort-desc.sync="isSortDirDesc"
                :sticky-header="true"   
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
                  <!-- Column: Actions -->
                <template #cell(actions)="data">
                   <a @click="handleRemoveUser('packageGroup',data.item.package_group_user_id)" v-permission="['edit-program']" style="cursor:pointer" title="remove">
                    <feather-icon icon="TrashIcon" />
                    </a>
                </template>
            </b-table>
            
            </b-modal>
            <b-modal
                v-permission="['view-program']"
                id="view-users"
                hide-footer
                centered
                size="lg"
                @show="getAssignedPackages(false)"
                @hidden="resetPackages"
                title="Group Programms"
                ref="view-programs"
                no-close-on-backdrop
              
            >
            <b-table
                style="min-height:250px"
                ref="refRolesTable"
                class="position-relative"
                :items="plist"
                responsive
                :fields="pcolumns"
                :totalRows="totalRows"
                primaryKey="index"
                :sort-by.sync="sortBy"
                show-empty
                empty-text="No matching records found"
                :sort-desc.sync="isSortDirDesc"
                :sticky-header="true"   
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
                    <span v-html="data.item.description"></span>
                </template>
                  <!-- Column: Actions -->
                <template #cell(actions)="data">
                   <a @click="handleRemovePackage(data.item.package_group_id)" v-permission="['edit-program']" style="cursor:pointer" title="remove">
                    <feather-icon icon="TrashIcon" />
                    </a>
                </template>
            </b-table>
            
            </b-modal>
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
        BOverlay,
        BFormCheckbox,
        BFormDatepicker
    },
    data() {
        return {
            pagination: {
                page: 1,
                per_page: 10,
                total: null
            },
			group: null,
			required,
			type: 'Create Group',
            groupPackages: [],
            readonly: false,
            errors: [],
            packages: [],
            mappingData:{
                cost:{}
            },
            partners:[],
            list: [],
            plist: [],
            totalRows: 0,  
        };
    },
    directives: {
        Ripple,
    },
    setup() {
        const groups = null;
        const loading = true;
        const sortBy = "1";
        const isSortDirDesc = false;
        const columns = [
            { key: "index", label: "Sr. No.", sortable: true },
            { key: "name", label: "Group Name", sortable: true },
            { key: "description", label: "Group Description", sortable: true },
            { key: "actions" }
        ];
        const pcolumns = [
            { key: "index", label: "Sr. No.", sortable: true },
            { key: "title", label: "Program Name", sortable: true },
            { key: "category", label: "Category", sortable: true },
            { key: "sub_category", label: "Sub Category", sortable: true },
            { key: "description", label: "Description", sortable: true },
            { key: "actions" }
        ];
        const ucolumns = [
            { key: "index", label: "Sr. No.", sortable: true },
            { key: "name", label: "Name", sortable: true },
            { key: "email", label: "Email", sortable: true },
            { key: "phone", label: "Contact Number", sortable: true },  
            { key: "cost", label: "Program Cost", sortable: true },  
            { key: "actions", label: "Action", sortable: false },
        ];
        const groupData = ref({
            name: "",
            description: "",
            packages: []
        });

        const resetGroupData = function() {
            groupData.value = {
                name: "",
                description: "",
                packages:[]
            };
        };
        const {
            refFormObserver,
            getValidationState,
            resetForm
        } = formValidation(resetGroupData);
        return {
            groups,

            loading,
            sortBy,
            isSortDirDesc,
            ucolumns,
            columns,
            pcolumns,
            groupData,
            refFormObserver,
            getValidationState,
			resetGroupData,
            resetForm
        };
    },
    computed:{
        id : function(){
            return this.group.group_id;
        }
    },

    watch: {
        "pagination.per_page": function(val) {
            this.handleFilterChange(val);
        }
    },
    created() {
        this.getList();
        this.getPackages();
    },
    methods: {
        async getList() {
            try {
                this.loading = true;
                const { data } = await axios.get("packageGroup", {
                    params: this.pagination
                });
                this.groups = data.groups;
                this.pagination.per_page = data.per_page;
                this.pagination.total = data.total;
                this.pagination.page = data.page;
            } catch (err) {
                console.log(err);
            }
            this.loading = false;
        },
        async getPackages(){
			try {
                const {data} = await axios.get('/packages/list');
               this.packages = this.allPackages = data.packages;
            } catch (err) {
                console.log(err);
            }
		},
        handlePaginationChange(val) {
            this.pagination.page = val;
            this.getList();
        },
        handleFilterChange(val) {
            this.pagination.per_page = val;
            this.getList();
        },
        async onSubmit() {
            try {
                let data;
                if (this.groupData.id) {
                    data = await axios.put(
                        `/packageGroup/${this.groupData.id}`,
                        this.groupData
                    );
                } else {
                    data = await axios.post("/packageGroup", this.groupData);
                }
                this.resetFormCard();
               
                this.$toast({
                    component: ToastificationContent,
                    props: {
                        title: data.data.message,
                        icon: "BellIcon",
                        variant: data.data.success ? "success" : "danger"
                    }
                });
				
				this.getList();
            } catch (error) {
               this.errors = error.response.data.errors;
            }
        },

		async editGroup( group ) {
			this.type= 'Add Program'
            // this.readonly = true;
            this.groupData.id  = group.id;
            this.groupData.name = group.name;
            this.groupData.description = group.description;
             this.getGroupPackages( this.groupData.id );
            // console.log(data)
            // if( data ){
            //     this.groupPackages = this.packages.filter( v => {
            //         return data.includes(v.id);
            //     });
            // }
		},
        async getGroupPackages(group){
            try {
                const {data} = await axios.get('/packages/list?group='+group);
                this.groupPackages = data.packages;
                // const packageIds = []
                // data.packages.map(d => {
                //     packageIds.push(d.id);
                // });
                // return packageIds;
            } catch (err) {
                console.log(err);
            }
        },
        async getPartners() {
            try {
                // const { data } = await axios.get('/packageGroup/alliance-partner/list?group_id='+this.group.id);
                const { data } = await axios.get('/packageGroup/alliance-partner/list');
                this.partners = data.partners;
            } catch (err) {
                console.log(err)
            }
        },

        async getAssignedUsers() {
            try {
                let id = this.group.id;
                const { data } = await axios.get(`packageGroup/${id}/users`, {
                    params: this.pagination 
                });
                this.list = data.users;
                this.totalRows = data.totalRows;

            } catch (err) {
                console.log(err)
            }
        },

        async getAssignedPackages(assign = false){
             try {
                let id = this.group.id;
                
                if(assign){
                    this.pagination.assign =assign;
                    this.pagination.users = this.mappingData.partners;
                }
                const { data } = await axios.get(`packageGroup/${id}/packages`, {
                    params: this.pagination,
                });
                this.plist = data.packages;
                this.totalRows = data.totalRows;

            } catch (err) {
                console.log(err)
            }
        },
        resetPackages(){
            this.plist=[];
        },
        resetUsers() {
            this.list = [];
        },

        viewAssignModal( group ) {
            this.group = group;
            this.getPartners();
            this.$refs['assign'].show();
        },
        viewUserModal(group) {
            this.group = group;
            this.$refs['view-users'].show();
        },
         viewPackageModal(group) {
            this.group = group;
            this.$refs['view-programs'].show();
        },

		async handleDeleteGroup(id){
			try {
                const data = await axios.delete(`/packageGroup/${id}`);
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

        resetFormCard() {
            this.resetForm();
            this.groupData = {
                name: "",
                description: "",
                packages:[]
            };
            this.groupPackages = [];
            this.type = 'Create Group';
            this.readonly = false;
            this.errors = [];
        },

        async onAssign(){
            try {
                this.mappingData.group = this.group.id;
                const {data} = await axios.post('/packageGroup/assignment',this.mappingData);
                 this.$toast({
                    component: ToastificationContent,
                    props: {
                        title: data.message,
                        icon: "BellIcon",
                        variant: data.success ? "success" : "danger"
                    }
                });
                this.$nextTick(() => {
                    this.$refs['assign'].hide()
                })
            } catch(err) {
                console.log(err)
            }
          
        },

        handleOk(bvModalEvt) {
            bvModalEvt.preventDefault()

            this.$refs.refFormObserver.handleSubmit(this.onAssign);
        },
        resetData() {
            this.mappingData = {
                partners: [],
                cost: {}
            }
        },

         async handleRemoveUser(type, id) {
            try {
                const { data } = await axios.delete(`/assignment/${id}/user?type=${type}`);
                this.$toast({
                    component: ToastificationContent,
                    props: {
                        title: data.message,
                        icon: "BellIcon",
                        variant: data.success ? "success" : "danger"
                    }
                });
                this.getAssignedUsers();
            } catch (error) {
                this.$toast({
                    component: ToastificationContent,
                    props: {
                        title: error.response.data.message,
                        icon: "BellIcon",
                        variant: "danger"
                    }
                });
            }
        },

         async handleRemovePackage(id) {
            try {
                const { data } = await axios.delete(`/packageGroup/mapping/${id}`);
                this.$toast({
                    component: ToastificationContent,
                    props: {
                        title: data.message,
                        icon: "BellIcon",
                        variant: data.success ? "success" : "danger"
                    }
                });
                this.getAssignedPackages();
            } catch (error) {
                this.$toast({
                    component: ToastificationContent,
                    props: {
                        title: error.response.data.message,
                        icon: "BellIcon",
                        variant: "danger"
                    }
                });
            }
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
#assignUser___BV_modal_body_{
    min-height:200px !important;
    max-height:300px !important;
    overflow-y: auto;
}
</style>
