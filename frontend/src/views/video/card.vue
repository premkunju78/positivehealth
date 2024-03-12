<template>
    <div class="card" style="width:100%">
        <div class="embed-responsive embed-responsive-16by9" v-html="video.embed.html"></div>
        <div class="card-body">
            <h5 class="card-title">{{video.name}}</h5>
            <p class="card-text">{{video.description}}</p>
            <!-- <p class="card-text">
                <small class="text-muted"> Video-id :- {{id}}</small>
            </p> -->
            <b-button
                v-if="checkPermission(['edit-video']) && this.buttons "
                v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                variant="outline-primary"
                v-b-modal="video.link"
            >
              <feather-icon icon="CheckSquareIcon" />
                Assign
            </b-button>
            <b-button
                v-if="checkPermission(['edit-video']) && this.buttons "
                v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                variant="outline-info"
                v-b-modal="video.link+'view-users'"
                style="float:right"
            >
              <feather-icon icon="EyeIcon" />
                View
            </b-button>
              <b-button
                v-if="checkPermission(['edit-video']) && !this.buttons "
                v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                variant="danger"
                @click="handleRemoveVideo(id)"
            >
              Remove
            </b-button>
            <b-modal
                v-if="checkPermission(['edit-video']) && this.buttons "
                :id="video.link"
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
                v-if="checkPermission(['edit-video']) && this.buttons "
                :id="video.link+'view-users'"
                hide-footer
                centered
                size="lg"
                @show="getAssignedUsers"
                @hidden="resetUsers"
                title="Assigned Users"
                ref="view-user-modal"
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
                <template #cell(assigned_date)="data">
                    {{ moment(data.item.assigned_date).format("DD-MM-YYYY") }} 
                </template>
                 <!-- Column: Actions -->
                <template #cell(actions)="data">
                  <a @click="handleRemoveUser('video',data.item.user_video_id)" v-permission="['edit-video']" style="cursor:pointer" title="Unassign User">
                    <feather-icon icon="TrashIcon" />
                    </a>
                </template>
            </b-table>

            </b-modal>
        </div>
    </div>
</template>
<style lang="scss" scoped>

@media only screen and (min-width: 768px) {
  iframe{
    height: 13em;
  }
  .card{
    width: 50%;
  }
}
</style>
<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";
</style>

<script>
import moment from 'moment';

import {BModal,BButton,BCardText,VBModal, BFormGroup,BFormInput, BRow, BCol, BFormCheckbox,
	BFormInvalidFeedback,BTable,BPagination,BDropdownItem,BDropdown,BFormDatepicker} from 'bootstrap-vue';
import Ripple from 'vue-ripple-directive'
import vSelect from 'vue-select'

import Card from './card';

import { ValidationProvider, ValidationObserver } from "vee-validate";
import { required } from "@validations";
import {removeVideo} from './group/videos';

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

import { checkPermission } from '@/@core/utils/utils'
export default {
    name: 'VideoCard',
    props: {
        video: {
            type: Object,
            defualt: () => {},
        },
        buttons: {
            type: Boolean,
            defualt: true
        },
        client_id: {
            type: [String,null],
            defualt: null
        }
    },
    mixins: [Card],
    directives: {
        'b-modal': VBModal,
        Ripple,
    },
    computed: {
        id : function() {
            return this.video.uri.split('/')[2];
        }
    },
    components: {
        BModal,
        BButton,
        BCardText,
        BFormGroup,
        BFormInput,
        BRow,
        BCol,
        BFormCheckbox,
        BTable,
        BPagination,
        BDropdownItem,
        BDropdown,
        BFormDatepicker,

        ValidationProvider,
        ValidationObserver,
		BFormInvalidFeedback,
        vSelect,

    },

    data(){
        return {
            required,
            moment:moment
        };
    },
    methods: {
        removeVideo,
        checkPermission,
        handleOk(bvModalEvt) {
            bvModalEvt.preventDefault()

            this.$refs.refFormObserver.handleSubmit(this.onAssign);
        },

         async getUsers() {
            try {
                const { data } = await axios.get('/user/list', {
                    params: { video: this.id }
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
        async getClients() {
            if (this.client_check && this.userData.partners.length) {
                try {
                    const { data } = await axios.get('/client/list', {
                        params: { partners: this.userData.partners, video: this.id }
                    });
                    this.clients = data.clients;
                } catch (err) {
                    console.log(err)
                }
            }

        },

        async onAssign() {
            try {
                this.userData.video = this.id;
                const {data} = await axios.post('/video/assignment',this.userData);
                this.clients = data.clients;
                 this.$toast({
                    component: ToastificationContent,
                    props: {
                        title: data.message,
                        icon: "BellIcon",
                        variant: data.success ? "success" : "danger"
                    }
                });
                this.$nextTick(() => {
                    this.$bvModal.hide(this.video.link)
                })
            } catch(err) {
                console.log(err)
            }
        },

        async getAssignedUsers( )
        {
            try {
                const {data} = await axios.get(`/video/${this.id}/users`);
                this.list = data.users;
                this.totalRows = data.totalRows;

            } catch {

            }

        },

        async handleRemoveVideo( id ){
            try {
                const { data } = await axios.delete(`/assignment/${id}/user/${this.client_id}`);
                this.$toast({
                    component: ToastificationContent,
                    props: {
                        title: data.message,
                        icon: "BellIcon",
                        variant: data.success ? "success" : "danger"
                    }
                });
                this.$emit('remove');
            } catch (err) {
                console.log(err);
            }
        },
        handleCheckboxChange( val ) {
            if( val ) {
                this.getClients()
            } else {
                this.clients = [];
            }
        }
    }
}
</script>
