<template>
    <div style="min-height:300px">   
        <b-overlay :show="$store.state.app.shallShowOverlay" no-wrap opacity="0.5"  blur="1px"></b-overlay>
            <b-card :title="type"  v-permission="['create-video']">
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
                                    name="videos"
                                >
                                    <b-form-group
                                        label="Select Videos"
                                        label-for="videos"
                                        :state="getValidationState(validationContext)"
                                    >
                                       <v-select
                                            v-model="groupData.videos"
                                            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                            :options="videos"
                                            :reduce="val => val.id"
                                            :clearable="true"
                                            multiple
                                            id="videos"
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
                                    name="videos"
                                >
                                    <b-form-group
                                        label="Add Videos"
                                        label-for="videos"
                                        :state="getValidationState(validationContext)"
                                    >
                                       <v-select
                                            v-model="groupData.videos"
                                            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                            :options="groupVideos"
                                            :reduce="val => val.id"
                                            :clearable="true"
                                            multiple
                                            id="groupVideos"
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
                                v-permission="['create-video']"
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
                        v-model="gpagination.per_page"
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
                :fields="gcolumns"
                :totalRows="gpagination.total"
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
                        <b-dropdown-item v-permission="['view-video']" :to="{ name:'viewGroup', params: {'id' : data.item.group_id, creator: data.item.creator}}">
                            <feather-icon icon="EyeIcon" />
                            <span class="align-middle ml-50">View</span>
                        </b-dropdown-item>
                        <b-dropdown-item @click="editGroup(data.item)"  v-permission="['edit-video']">
                            <feather-icon icon="EditIcon" />
                            <span class="align-middle ml-50" >Edit</span>
                        </b-dropdown-item>
                         <b-dropdown-item   @click="viewAssignModal(data.item)"  v-permission="['edit-video']">
                            <feather-icon icon="UsersIcon" />
                            <span class="align-middle ml-50">Assign</span>
                        </b-dropdown-item>
                         <b-dropdown-item @click="viewUserModal(data.item)"  v-permission="['edit-video']">
                            <feather-icon icon="EyeIcon" />
                            <span class="align-middle ml-50">View Users</span>
                        </b-dropdown-item>
                        <b-dropdown-item @click="handleDeleteGroup(data.item.group_id)" v-permission="['delete-video']">
                            <feather-icon icon="TrashIcon" />
                            <span class="align-middle ml-50">Delete</span>
                        </b-dropdown-item>
                    </b-dropdown>
                </template>
            </b-table>
            <b-pagination
                v-if="gpagination.total"
                v-model="gpagination.page"
                :page.sync="gpagination.page"
                :total-rows="gpagination.total"
                :index="1"
                :per-page="gpagination.per_page"
                aria-controls="video-row"
                align="right"
                @change="handlePaginationChange"
            >
            </b-pagination>
            <!-- <b-row id="video-row">
            <b-col v-for="video in list" :key="video.link"  lg="3" md="4" sm="6">
                <loading-card v-if="loading"></loading-card>
                <group-card v-else :group="video" ></group-card>
            </b-col>
        
        </b-row> -->
            <b-modal
                v-permission="['edit-video']"
                id="assign"
                ref="assign"
                ok-only
                ok-title="Assign"
                @show="resetData"
                @hidden="resetData"
                @ok="handleOk"
                centered
                size="lg"
                title="Video Assignment"
                no-close-on-backdrop
            >
            <validation-observer
                    #default="{ handleSubmit }"
                    ref="refFormObserver"
            >
                <form ref="form"  @submit.prevent="handleSubmit(onAssign)">
                    <b-row>
                        <b-col cols="12" md="6">
                            <validation-provider
                                #default="validationContext"
                                name="Role"
                                rules="required"
                            >
                                <b-form-group
                                    label="Role"
                                    label-for="role"
                                    :state="getValidationState(validationContext)"
                                    >
                                    <v-select
                                        v-model="userData.role"
                                        :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                        :options="roles"
                                        :reduce="val => val.value"
                                        :clearable="false"
                                        input-id="user-role"
                                        @input="handleRoleChange"
                                    />
                                    <b-form-invalid-feedback :state="getValidationState(validationContext)">
                                        {{ validationContext.errors[0] }}
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>
                        </b-col>
                        <b-col cols="12" md="6" v-if="userData.role !==1">
                            <b-form-group
                                label="Assign To Clients"
                                label-for="clients-check"
                            >
                                <b-form-checkbox
                                    id="clients-check"
                                    v-model="client_check"
                                    name="checkbox-1"
                                    :value="1"
                                    :unchecked-value="0"
                                    @change="handleCheckboxChange"
                                >
                                </b-form-checkbox>
                            </b-form-group>
                        </b-col>
                        <b-col cols="12" md="6" v-if="userData.role===1">
                            <validation-provider
                                #default="validationContext"
                                name="staff"
                                rules="required"
                            >
                                <b-form-group
                                    label="Select Staff"
                                    label-for="users"
                                    :state="getValidationState(validationContext)"
                                    >
                                    <v-select
                                        v-model="userData.users"
                                        :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                        :reduce="val => val.id"
                                        multiple
                                        :options="users"
                                        :clearable="false"
                                        input-id="users"
                                    />
                                    <b-form-invalid-feedback :state="getValidationState(validationContext)">
                                        {{ validationContext.errors[0] }}
                                    </b-form-invalid-feedback>
                                </b-form-group>
                             </validation-provider>
                        </b-col>
                         <b-col cols="12" md="6"  v-if="userData.role ===2">
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
                                        v-model="userData.partners"
                                        :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                        :options="partners"
                                        :reduce="val => val.id"
                                        :clearable="false"
                                        multiple
                                        input-id="partners"
                                        @input="handlePartnerChange"
                                    />
                                      <b-form-invalid-feedback :state="getValidationState(validationContext)">
                                        {{ validationContext.errors[0] }}
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>
                                
                        </b-col>
                        <b-col cols="12" md="6" v-if=" userData.role !==1 && client_check === 1 ">
                            <validation-provider
                                #default="validationContext"
                                name="clients"
                                rules="required"
                            >
                                <b-form-group
                                    label="Select Clients"
                                    label-for="clients"
                                    :state="getValidationState(validationContext)"
                                    >
                                    <v-select
                                        v-model="userData.clients"
                                        :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                        :options="clients"
                                        :reduce="val => val.id"
                                        :clearable="false"
                                        multiple
                                        input-id="clients"
                                    />
                                    <b-form-invalid-feedback :state="getValidationState(validationContext)">
                                        {{ validationContext.errors[0] }}
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>
                        </b-col>
                        <b-col cols="12" md="6">
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
                                    <b-form-datepicker id="expiry_date" :min="new Date()" v-model="userData.expiry_date" :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"></b-form-datepicker>
                                    <b-form-invalid-feedback :state="getValidationState(validationContext)">
                                        {{ validationContext.errors[0] }}
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>
                        </b-col>
                       
                    </b-row>
                    
                </form>
            </validation-observer>
            </b-modal>
            <b-modal
                v-permission="['edit-video']"
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
                :fields="columns"
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
                   <a @click="handleRemoveUser('group',data.item.group_user_id)" v-permission="['edit-video']" style="cursor:pointer" title="remove user">
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
import GroupCard from "./card.vue";
import LoadingCard from "../loadingCard.vue";
import { ValidationProvider, ValidationObserver } from "vee-validate";

import formValidation from "@core/comp-functions/forms/form-validation";
import { required } from "@validations";


import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

import vSelect from "vue-select";

import Ripple from 'vue-ripple-directive'
import {getVideos as getGroupVideos} from './videos';
import Card from '../card';

export default {
    components: {
        BCard,
        BCardText,
        BTable,
        GroupCard,
        BRow,
        BCol,
        BPagination,
        BSpinner,
        LoadingCard,
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
    mixins: [Card],
    data() {
        return {
            gpagination: {
                page: 1,
                per_page: 10,
                total: null
            },
			group: null,
			required,
			type: 'Create Group',
            videos: [],
            groupVideos: [],
            readonly: false,
            errors: [],
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
        const gcolumns = [
            { key: "index", label: "Sr. No.", sortable: true },
            { key: "name", label: "Group Name", sortable: true },
            { key: "description", label: "Group Description", sortable: true },
            { key: "actions" }
        ];
        const groupData = {
            name: "",
            description: "",
            videos: []
        };

        const resetGroupData = function() {
            groupData.value = {
                name: "",
                description: "",
                videos:[]
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
            gcolumns,
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
        this.getVideos();
    },
    methods: {
        getGroupVideos,
        async getList() {
            try {
                this.loading = true;
                const { data } = await axios.get("groups", {
                    params: this.gpagination
                });
                this.groups = data.groups.data;
                this.gpagination.per_page = data.groups.per_page;
                this.gpagination.total = data.groups.total;
                this.gpagination.page = data.groups.page;
            } catch (err) {
                console.log(err);
            }
            this.loading = false;
        },
        async getVideos(){
			try {
                const {data} = await axios.get('/video/list');
               this.videos = this.allVideos = data.videos;
            } catch (err) {
                console.log(err);
            }
		},
        handlePaginationChange(val) {
            this.gpagination.page = val;
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
                        `/groups/${this.groupData.id}`,
                        this.groupData
                    );
                } else {
                    data = await axios.post("/groups", this.groupData);
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
			this.type= 'Add Videos'
            // this.readonly = true;
            this.groupData.id  = group.group_id;
            this.groupData.name = group.name;
            this.groupData.description = group.description;
            const data = await  this.getGroupVideos( this.groupData.id );
            
            if( data ){
                this.groupVideos = this.videos.filter( v => {
                    return !data.includes(v.id);
                });
            }
		},
        
        async getUsers(key = 'video') {
            try {
                const { data } = await axios.get('/user/list', {
                    params: { group: this.id }
                });
                this.users = data.users;
            } catch (err) {
                console.log(err)
            }
        },
        async getPartners() {
            try {
                const { data } = await axios.get('/alliance-partner/list');
                this.partners = data.partners;
            } catch (err) {
                console.log(err)
            }
        },
        async getClients(key = 'video') {
            if (this.client_check && this.userData.partners.length) {
                try {
                    const { data } = await axios.get('/client/list', {
                        params: { partners: this.userData.partners, group: this.id }
                    });
                    this.clients = data.clients;
                } catch (err) {
                    console.log(err)
                }
            }

        },

        async getAssignedUsers() {
            try {
                let id = this.group.group_id;
                const { data } = await axios.get(`groups/${id}/users`, {
                    params: this.pagination 
                });
                this.list = data.users;
                this.totalRows = data.totalRows;

            } catch (err) {
                console.log(err)
            }
        },

        viewAssignModal( group ) {
            this.group = group;
            this.$refs['assign'].show();
        },
        viewUserModal(group) {
            this.group = group;
            this.$refs['view-users'].show();
        },

		async handleDeleteGroup(id){
			try {
                const data = await axios.delete(`/groups/${id}`);
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
                videos:[]
            };
            this.groupVideos = [];
            this.type = 'Create Group';
            this.readonly = false;
            this.errors = [];
        },

        async onAssign(){
            try {
                this.userData.group = this.group.group_id;
                const {data} = await axios.post('/group/assignment',this.userData);
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
		handleCheckboxChange( val ) {
            if( val ) {
                this.getClients()
            }else {
                this.clients = [];
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
</style>
