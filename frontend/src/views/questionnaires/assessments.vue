<template>
    <div style="min-height: 300px">
        <b-overlay :show="$store.state.app.shallShowOverlay" no-wrap opacity="0.5" blur="1px"></b-overlay>
        <b-card>
            <b-row class="mb-2">
                <!-- Per Page -->
                <b-col cols="12" md="6" class="d-flex align-items-center justify-content-start mb-1 mb-md-0">
                    <label>Show</label>
                    <v-select v-model="qpagination.per_page" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                        :options="[10, 25, 50, 100, 1000]" :clearable="false"
                        class="per-page-selector d-inline-block mx-50" />
                    <label>entries</label>
                </b-col>
            </b-row>

            <b-col cols="12" md="6" class="d-flex align-items-center justify-content-start mb-1 mb-md-0">
                <ul class="nav nav-tabs nav-justified" id="myTab2" role="tablist">
                    <li class="nav-item">
                        <a :class="tabId === 'assessments' ? 'nav-link active' : 'nav-link'" @click="showAssessments()"
                            id="assessments-tab-justified" data-bs-toggle="tab" role="tab" aria-controls="home-just"
                            aria-selected="true">Assigned Assessments</a>
                    </li>
                    <li class="nav-item">
                        <a :class="tabId === 'history' ? 'nav-link active' : 'nav-link'" id="history-tab-justified"
                            @click="showHistory()" data-bs-toggle="tab" role="tab" aria-controls="history-just"
                            aria-selected="false">Assessments History</a>
                    </li>
                </ul>
            </b-col>

            <div class="tab-content pt-1">
                <div :class="tabId === 'assessments' ? 'tab-pane active' : 'tab-pane'" id="assessments" role="tabpanel"
                    aria-labelledby="home-tab-justified" :key="key">

                    <b-table style="min-height: 250px" ref="refRolesTable" class="position-relative" :items="questionnaires"
                        responsive :fields="qcolumns" :totalRows="qpagination.total" primaryKey="index"
                        :sort-by.sync="sortBy" show-empty empty-text="No matching records found"
                        :sort-desc.sync="isSortDirDesc">
                        <!-- Column: Actions -->
                        <template #cell(index)="data">
                            {{ data.index + 1 }}
                        </template>
                        <template #cell(name)="data">
                            <span class="text-primary">{{ data.item.name.toUpperCase() }}</span>
                        </template>
                        <template #cell(status)="data">
                            <span class="text-success" v-if="data.item.result"> COMPLETED</span>
                            <span class="text-primary" v-else> NOT-COMPLETE</span>
                        </template>
                        <!-- Column: Actions -->
                        <template #cell(actions)="data">
                            <div v-if="$store.getters.user.role.is_consultant == 1">
                                <router-link
                                    :to="{ name: 'fill-questionnaire', params: { id: data.item.id }, query: { clientid: id, new: 'true' } }">
                                    <b-button variant="primary">
                                        <span variant="primary" class="text-nowrap">Start</span>
                                    </b-button>
                                </router-link>
                            </div>
                        </template>
                    </b-table>
                    <b-pagination v-if="qpagination.total" v-model="qpagination.page" :page.sync="qpagination.page"
                        :total-rows="qpagination.total" :index="1" :per-page="qpagination.per_page" align="right"
                        @change="handlePaginationChange">
                    </b-pagination>
                </div>
                <div :class="tabId === 'history' ? 'tab-pane active' : 'tab-pane'" id="history" role="tabpanel"
                    aria-labelledby="home-tab-justified" :key="key">
                    <b-table style="min-height: 250px" ref="refRolesTable" class="position-relative" :items="historydata"
                        responsive :fields="historycolumns" :totalRows="qpagination.total" primaryKey="index"
                        :sort-by.sync="sortBy" show-empty empty-text="No matching records found"
                        :sort-desc.sync="isSortDirDesc">
                        <!-- Column: Actions -->
                        <template #cell(index)="data">
                            {{ data.index + 1 }}
                        </template>
                        <template #cell(name)="data">
                            <span class="text-primary">{{ data.item.name.toUpperCase() }}</span>
                        </template>
                        <template #cell(status)="data">
                            <span class="text-success" v-if="data.item.result"> COMPLETED</span>
                            <span class="text-primary" v-else> NOT-COMPLETE</span>
                        </template>
                        <template #cell(updated_at)="data">
                            {{ moment(data.item.updated_at).format("DD-MM-YYYY") }}
                        </template>

                        <!-- Column: Actions -->
                        <template #cell(actions)="data">
                            <router-link v-if="data.item.result" :to="{
                                name: 'view-questionnaire',
                                params: { id: data.item.questionnaire_user_id },
                            }">
                                <b-button variant="info">
                                    <span variant="primary" class="text-nowrap">View Questionnaire</span>
                                </b-button>
                            </router-link>
                        </template>
                    </b-table>
                    <b-pagination v-if="qpagination.total" v-model="qpagination.page" :page.sync="qpagination.page"
                        :total-rows="qpagination.total" :index="1" :per-page="qpagination.per_page" align="right"
                        @change="handlePaginationChange">
                    </b-pagination>
                </div>
            </div>
            <b-modal v-permission="['edit-questionnaires']" id="assign" ref="assign" ok-only ok-title="Assign"
                @show="resetData" @hidden="resetData" @ok="handleOk" centered size="lg" title="Questionnaire Assignment"
                no-close-on-backdrop>
                <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
                    <form ref="form" @submit.prevent="handleSubmit(onAssign)">
                        <b-form-group label="Assign To">
                            <b-row>
                                <b-col cols="12" md="2" v-if="$store.getters.user.role.is_consultant != 1">
                                    <b-form-radio v-model="userData.assignedto" name="assignedto" value="consultant"
                                        @change="handleAssignTo">Incharge / Consultant</b-form-radio>
                                </b-col>
                                <b-col cols="12" md="2">
                                    <b-form-radio v-model="userData.assignedto" name="assignedto" value="clients"
                                        @change="handleAssignTo">
                                        Clients
                                    </b-form-radio>
                                </b-col>
                            </b-row>
                        </b-form-group>
                        <b-row>
                            <b-col cols="12" md="6">
                                <validation-provider #default="validationContext" name="clients" rules="required">
                                    <b-form-group label="Select Users" label-for="clients"
                                        :state="getValidationState(validationContext)">
                                        <v-select v-model="userData.clients"
                                            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="userlist"
                                            :reduce="(val) => val.id" :clearable="false" multiple input-id="clients" />
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
            <b-modal v-permission="['edit-questionnaires']" id="view-users" hide-footer centered size="lg"
                @show="getAssignedUsers" @hidden="resetUsers" title="Assigned Users" ref="view-users" no-close-on-backdrop>
                <b-table style="min-height: 250px" ref="refRolesTable" class="position-relative" :items="list" responsive
                    :fields="columns" :totalRows="totalRows" primaryKey="index" :sort-by.sync="sortBy" show-empty
                    empty-text="No matching records found" :sort-desc.sync="isSortDirDesc" :sticky-header="true">
                    <!-- Column: Actions -->
                    <template #cell(index)="data">
                        {{ data.index + 1 }}
                    </template>
                    <template #cell(name)="data">
                        <span class="text-primary">{{ data.item.name.toUpperCase() }}</span>
                    </template>
                    <!-- Column: Actions -->
                    <template #cell(actions)="data">
                        <a @click="handleRemoveUser('questionnaire', data.item.questionnaire_user_id)"
                            v-permission="['edit-questionnaire']" style="cursor: pointer" title="remove">
                            <feather-icon icon="TrashIcon" />
                        </a>
                        <router-link v-if="data.item.result" v-permission="['edit-questionnaire']" :to="{
                            name: 'view-questionnaire',
                            params: { id: data.item.questionnaire_user_id },
                        }" title="view result">
                            <feather-icon icon="EyeIcon" />
                        </router-link>
                    </template>
                </b-table>
            </b-modal>
        </b-card>
    </div>
</template>
<script>
import moment from 'moment';
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
    BFormDatepicker,
    BFormRadioGroup,
    BFormRadio,
} from "bootstrap-vue";

import { ValidationProvider, ValidationObserver } from "vee-validate";

import formValidation from "@core/comp-functions/forms/form-validation";
import { required } from "@validations";

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

import vSelect from "vue-select";

import Ripple from "vue-ripple-directive";

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
        BFormDatepicker,
        BFormRadioGroup,
        BFormRadio
    },
    props: {
        id: {
            type: [Number, String],
            default: null,
        },
    },
    data() {
        return {
            tabId: 'assessments',
            totalRows: 0,
            qpagination: {
                page: 1,
                per_page: 10,
                total: null,
            },
            questionnaire: null,
            required,
            users: [],
            readonly: false,
            errors: [],
            list: [],
            userlist: [],
            consultants: [],
            clients: [],
            moment: moment
        };
    },
    directives: {
        Ripple,
    },
    setup() {
        const questionnaires = null;
        const historydata = null;
        const loading = true;
        const sortBy = "1";
        const isSortDirDesc = false;
        const qcolumns = [
            { key: "index", label: "Sr. No.", sortable: true },
            { key: "name", label: "Name", sortable: true },
            { key: "description", label: "Description", sortable: true },
            { key: "actions" },
        ];
        const historycolumns = [
            { key: "index", label: "Sr. No.", sortable: true },
            { key: "name", label: "Name", sortable: true },
            { key: "updated_at", label: "Date", sortable: true },
            { key: "description", label: "Description", sortable: true },
            { key: "actions" },
        ];
        const columns = [
            { key: "index", label: "Sr. No.", sortable: true },
            { key: "name", label: "Name", sortable: true },
            { key: "email", label: "Email", sortable: true },
            { key: "phone", label: "Contact Number", sortable: true },
            { key: "actions", label: "Action", sortable: false },
        ];
        const userData = {
            assignedto: "",
            clients: [],
            partners: [],
        };
        const resetUserData = function () {
            userData.value = {
                assignedto: "",
                clients: [],
                partners: [],
            };
        };
        const { refFormObserver, getValidationState, resetForm } = formValidation(
            resetUserData
        );

        return {
            questionnaires,
            historydata,
            loading,
            sortBy,
            isSortDirDesc,
            qcolumns,
            historycolumns,
            columns,
            refFormObserver,
            getValidationState,
            resetForm,
        };
    },
    computed: {
        user_id: function () {
            return this.id ? this.id : "";
        },
    },

    watch: {
        "pagination.per_page": function (val) {
            this.handleFilterChange(val);
        },
    },
    created() {
        this.getList();
        this.getAssessmentHistory();
        this.getPartners();
        this.qcolumnsupdate();
        this.getConsultants();
        this.getClients();
    },
    methods: {
        qcolumnsupdate() {
            if (this.$store.getters.user.role.is_consultant != 1) {
                this.qcolumns = [
                    { key: "index", label: "Sr. No.", sortable: true },
                    { key: "name", label: "Name", sortable: true },
                    { key: "description", label: "Description", sortable: true },
                ];
            }
        },
        async getList() {
            try {
                this.loading = true;
                this.qpagination.id = this.user_id;
                this.qpagination.assessments = 'true';
                const { data } = await axios.get("/questionnaire", {
                    params: this.qpagination,
                });
                this.questionnaires = data.questionnaires.data;
                this.qpagination.per_page = data.questionnaires.per_page;
                this.qpagination.total = data.questionnaires.total;
                this.qpagination.page = data.questionnaires.page;
            } catch (err) {
                console.log(err);
            }
            this.loading = false;
        },
        async getAssessmentHistory() {
            try {
                this.loading = true;
                this.qpagination.id = this.user_id;
                this.qpagination.assessments = 'true';
                const { data } = await axios.get("/questionnaire/assessmenthistory", {
                    params: this.qpagination,
                });
                this.historydata = data.questionnaires.data;
                this.qpagination.per_page = data.questionnaires.per_page;
                this.qpagination.total = data.questionnaires.total;
                this.qpagination.page = data.questionnaires.page;
            } catch (err) {
                console.log(err);
            }
            this.loading = false;
        },
        handlePaginationChange(val) {
            this.gpagination.page = val;
            this.getList();
        },
        handleFilterChange(val) {
            this.pagination.per_page = val;
            this.getList();
        },
        async getPartners() {
            try {
                const { data } = await axios.get("/alliance-partner/list");
                this.partners = data.partners;
            } catch (err) {
                console.log(err);
            }
        },
        handleAssignTo() {
            this.userlist = [];
            this.userData.clients = [];
            if (this.userData.assignedto == "consultant") {
                this.userlist = this.consultants;
            } else if (this.userData.assignedto == "clients") {
                this.userlist = this.clients;
            }
            console.log(this.userlist);
        },
        async getConsultants() {
            try {
                this.consultants = [];
                var params = {};
                const { data } = await axios.get("/consultants/list", {
                    params: params,
                });
                this.consultants = data.users;
            } catch (error) {
                console.log(error);
            }
        },
        async getClients() {
            try {
                this.clients = [];
                if (this.$store.getters.user.role.is_consultant == 1) {
                    var params = { consultant: [this.$store.getters.user.id] };
                }
                if (this.$store.getters.user.role_id == 2) {
                    var params = { partners: [this.$store.getters.user.id] };
                }
                const { data } = await axios.get("/client/list", {
                    params: params,
                });
                this.clients = data.clients;
            } catch (error) {
                console.log(error);
            }
        },
        async getAssignedUsers() {
            try {
                let id = this.questionnaire.id;
                const { data } = await axios.get(`/questionnaire/${id}/users`, {
                    params: this.pagination,
                });
                this.list = data.users;
                this.totalRows = data.totalRows;
            } catch (err) {
                console.log(err);
            }
        },

        viewAssignModal(questionnaire) {
            this.questionnaire = questionnaire;
            this.$refs["assign"].show();
        },
        viewUserModal(questionnaire) {
            this.questionnaire = questionnaire;
            this.$refs["view-users"].show();
        },

        async handleDeleteQuestionnaire(id) {
            try {
                const data = await axios.delete(`/questionnaire/${id}`);
                this.$toast({
                    component: ToastificationContent,
                    props: {
                        title: data.data.message,
                        icon: "BellIcon",
                        variant: data.data.success ? "success" : "danger",
                    },
                });
                this.getList();
            } catch (err) {
                console.log(err);
            }
        },

        resetFormCard() {
            this.errors = [];
        },

        async onAssign() {
            try {
                this.userData.questionnaire = this.questionnaire.id;
                const { data } = await axios.post("/questionnaire/assignment", this.userData);
                this.$toast({
                    component: ToastificationContent,
                    props: {
                        title: data.message,
                        icon: "BellIcon",
                        variant: data.success ? "success" : "danger",
                    },
                });
                this.$nextTick(() => {
                    this.$refs["assign"].hide();
                });
            } catch (err) {
                console.log(err);
            }
        },
        handleOk(bvModalEvt) {
            bvModalEvt.preventDefault();

            this.$refs.refFormObserver.handleSubmit(this.onAssign);
        },

        resetUsers() {
            this.list = [];
            this.pagination = {
                page: 1,
                per_page: 10,
                total: null,
            };
        },
        resetData() {
            this.userData = {
                clients: [],
                partners: [],
            };
        },
        async handleRemoveUser(type, id) {
            try {
                const { data } = await axios.delete(`/assignment/${id}/user?type=${type}`);
                this.$toast({
                    component: ToastificationContent,
                    props: {
                        title: data.message,
                        icon: "BellIcon",
                        variant: data.success ? "success" : "danger",
                    },
                });
                this.getAssignedUsers();
            } catch (error) {
                this.$toast({
                    component: ToastificationContent,
                    props: {
                        title: error.response.data.message,
                        icon: "BellIcon",
                        variant: "danger",
                    },
                });
            }
        },
        showAssessments() {
            this.tabId = 'assessments';
        },
        showHistory() {
            this.tabId = 'history';
        },
    },
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
