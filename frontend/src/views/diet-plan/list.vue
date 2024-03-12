<template>
    <div style="min-height:300px">
        <b-overlay :show="$store.state.app.shallShowOverlay" no-wrap opacity="0.5" blur="1px"></b-overlay>
        <b-card :title="type" v-permission="['create-diet-plan']">
            <validation-observer #default="{ handleSubmit }" ref="refFormObserver">

                <form ref="form" @submit.prevent="handleSubmit(onSubmit)">
                    <b-row>
                        <b-col cols="12" md="6">
                            <validation-provider #default="validationContext" name="Diet Plan NO." rules="required">
                                <b-form-group label="Diet Plan NO." label-for="no">
                                    <b-form-input id="no" :state="getValidationState(
                                        validationContext
                                    )
                                        " v-model="dietData.no" :readonly="readonly" :class="errors.no ? 'is-invalid' : ''"
                                        :area-invalid="errors.no ? true : false" />
                                    <b-form-invalid-feedback v-if="errors.no">
                                        {{ errors.no[0] }}
                                    </b-form-invalid-feedback>
                                    <b-form-invalid-feedback v-else>
                                        {{ validationContext.errors[0] }}
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>
                        </b-col>
                        <b-col cols="12" md="6">
                            <validation-provider #default="validationContext" name="Title">
                                <b-form-group label="Title" label-for="title">
                                    <b-form-input id="title" v-model="dietData.title" :state="getValidationState(
                                        validationContext
                                    )
                                        " :readonly="readonly" :class="errors.no ? 'is-invalid' : ''"
                                        :area-invalid="errors.no ? true : false" />
                                    <b-form-invalid-feedback v-if="errors.title">
                                        {{ errors.title[0] }}
                                    </b-form-invalid-feedback>
                                    <b-form-invalid-feedback>
                                        {{ validationContext.errors[0] }}
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>
                        </b-col>
                        <b-col cols="12" md="6">
                            <validation-provider #default="validationContext" name="pdf" rules="size:2048">
                                <b-form-group label="Important Notes" label-for="pdf"
                                    :state="getValidationState(validationContext)">
                                    <b-form-file v-model="image" id="image" accept=".pdf"
                                        v-on:change="e => setFileObject(e, 'image')"
                                        :state="getValidationState(validationContext)"
                                        placeholder="Choose a file or drop it here..." drop-placeholder="Drop file here..."
                                        :class="errors.image ? 'is-invalid' : ''"
                                        :area-invalid="errors.image ? true : false">
                                    </b-form-file>
                                    <b-form-invalid-feedback v-if="errors.image">
                                        {{ errors.image[0] }}
                                    </b-form-invalid-feedback>
                                    <b-form-invalid-feedback :state="getValidationState(validationContext)">
                                        {{ validationContext.errors[0] }}
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>
                        </b-col>

                        <b-col cols="12" md="6">
                            <validation-provider #default="validationContext" name="PDF" rules="required">
                                <b-form-group label="PDF Upload" label-for="pdf"
                                    :state="getValidationState(validationContext)">
                                    <b-form-file v-model="pdf" id="pdf" accept=".pdf"
                                        :state="getValidationState(validationContext)"
                                        v-on:change="e => setFileObject(e, 'pdf')"
                                        placeholder="Choose a pdf file or drop it here..."
                                        drop-placeholder="Drop pdf file here..." :class="errors.pdf ? 'is-invalid' : ''"
                                        :area-invalid="errors.pdf ? true : false">
                                    </b-form-file>
                                    <b-form-invalid-feedback v-if="errors.pdf">
                                        {{ errors.pdf[0] }}
                                    </b-form-invalid-feedback>
                                    <b-form-invalid-feedback :state="getValidationState(validationContext)">
                                        {{ validationContext.errors[0] }}
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>
                        </b-col>
                        <b-col cols="12" md="6">
                            <validation-provider #default="validationContext" name="Instructions">
                                <b-form-group label="Instructions" label-for="instructions"
                                    :state="getValidationState(validationContext)">
                                    <b-form-textarea rows="3" id="instructions" v-model="dietData.instructions"
                                        :class="errors.instructions ? 'is-invalid' : ''"
                                        :area-invalid="errors.instructions ? true : false"></b-form-textarea>
                                    <b-form-invalid-feedback v-if="errors.instructions">
                                        {{ errors.instructions[0] }}
                                    </b-form-invalid-feedback>
                                    <b-form-invalid-feedback :state="getValidationState(validationContext)">
                                        {{ validationContext.errors[0] }}
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>
                        </b-col>
                        <b-col cols="12" md="6" v-if="id && $store.getters.user.role_id != '5'">
                            <validation-provider #default="validationContext" name="Expiry Date" rules="required">
                                <b-form-group label="Plan Expiry Date" label-for="expiry_date"
                                    :state="getValidationState(validationContext)">
                                    <b-form-datepicker id="expiry" :min="new Date()" v-model="dietData.expiry_date"
                                        :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"></b-form-datepicker>
                                    <b-form-invalid-feedback :state="getValidationState(validationContext)">
                                        {{ validationContext.errors[0] }}
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>
                        </b-col>
                    </b-row>
                    <b-row style=" margin-top: 2%;">
                        <!-- Action Buttons -->
                        <b-button variant="primary" :block="$store.getters['app/currentBreakPoint'] === 'xs'" type="submit" class="mr-2"
                            v-permission="['create-diet-plan']">
                            Save Changes
                        </b-button>
                        <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" type="button" @click="resetFormCard"
                            variant="outline-secondary">
                            Cancel
                        </b-button>

                    </b-row>
                </form>
            </validation-observer>
        </b-card>
        <b-card>
            <b-row class="mb-2">
                <!-- Per Page -->
                <b-col cols="12" md="6" class="d-flex align-items-center justify-content-start mb-1 mb-md-0">
                    <label>Show</label>
                    <v-select v-model="pagination.per_page" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                        :options="[10, 25, 50, 100, 1000]" :clearable="false"
                        class="per-page-selector d-inline-block mx-50" />
                    <label>entries</label>
                </b-col>

                <!-- Search -->
                <b-col cols="12" md="6">
                </b-col>
            </b-row>
            <b-modal id="modal-primary" ok-only ok-title="Accept" modal-class="modal-primary" centered
                title="Primary Modal">
                <b-card-text>
                    Biscuit chocolate cake gummies. Lollipop I love macaroon bear claw caramels. I love marshmallow
                    tiramisu I love
                    fruitcake I love gummi bears. Carrot cake topping liquorice. Pudding caramels liquorice sweet I
                    love. Donut powder
                    cupcake ice cream tootsie roll jelly.
                </b-card-text>
            </b-modal>
            <b-table style="min-height:250px" ref="refRolesTable" class="position-relative" :items="plans" responsive
                :fields="columns" :totalRows="pagination.total" primaryKey="index" :sort-by.sync="sortBy" show-empty
                empty-text="No matching records found" :sort-desc.sync="isSortDirDesc">
                <!-- Column: Actions -->
                <template #cell(index)="data">
                    {{ data.index + 1 }}
                </template>
                <template #cell(title)="{ item }">
                    {{ (item.title) ? item.title : 'NA' }}
                </template>
                <template #cell(image)="{ item }">
                    <!-- <a v-if="item.image" :href="'/view/file?path=' + item.image" target="_blank">
                        <b-img :src="'/view/file?path=' + item.image" fluid :width="75" :height="75" class='m1'></b-img>
                    </a> -->
                    <a v-if="item.image" :href="'/view/file?path=' + item.image" target="_blank">
                        <feather-icon icon="FileIcon" />
                    </a>
                </template>
                <template #cell(pdf)="{ item }">
                    <!-- <a v-if="item.pdf" :href="'/view/file?path=' + item.pdf" target="_blank">
                        <feather-icon icon="FileIcon" />
                    </a> -->
                    <a v-if="item.pdf" class="btn btn-success" @click="viewDietModal(item)" target="_blank">
                        View Diet Plan
                    </a>
                    <!-- <b-button
                    v-b-modal.modal-primary
                    variant="outline-primary">
                    Primary
                  </b-button>
                  <div>
                    <object data="test.pdf" type="application/pdf" width="300" height="200">
                        alt : <a :href="'/view/file?path='+item.pdf">test.pdf</a>
                    </object>
                  </div> -->
                </template>
                <template #cell(instructions)="{ item }">
                    {{ (item.instructions) ? item.instructions : 'NA' }}
                </template>
                <template #cell(actions)="data">
                    <b-dropdown variant="link" no-caret v-permission="['edit-diet-plan']">
                        <template #button-content>
                            <feather-icon icon="MoreVerticalIcon" size="16" class="align-middle text-body" />
                        </template>
                        <b-dropdown-item @click="editPlan(data.item)" v-permission="['edit-diet-plan']">
                            <feather-icon icon="EditIcon" />
                            <span class="align-middle ml-50">Edit</span>
                        </b-dropdown-item>
                        <b-dropdown-item @click="viewAssignModal(data.item)" v-permission="['edit-diet-plan']">
                            <feather-icon icon="UsersIcon" />
                            <span class="align-middle ml-50">Assign</span>
                        </b-dropdown-item>
                        <b-dropdown-item @click="viewUserModal(data.item)" v-permission="['edit-diet-plan']">
                            <feather-icon icon="EyeIcon" />
                            <span class="align-middle ml-50">View Users</span>
                        </b-dropdown-item>
                        <b-dropdown-item @click="handleDeletePlan(data.item.id)" v-permission="['delete-diet-plan']">
                            <feather-icon icon="TrashIcon" />
                            <span class="align-middle ml-50">Delete</span>
                        </b-dropdown-item>
                    </b-dropdown>
                </template>
            </b-table>
            <b-pagination v-if="pagination.total" v-model="pagination.page" :page.sync="pagination.page"
                :total-rows="pagination.total" :index="1" :per-page="pagination.per_page" aria-controls="package-row"
                align="right" @change="handlePaginationChange">
            </b-pagination>
            <b-modal id="viewpdf" ref="viewpdf" ok-only ok-title="Close" centered size="lg"
                title="View Diet Plan" no-close-on-backdrop>
                <div id="pdf" v-if="plan">
                    <object class="pdf-object" width="100%" height="650" type="application/pdf"
                        :data="'/view/file?path=' + plan.pdf + '#zoom=85&scrollbar=0&toolbar=0&navpanes=0'"
                        id="pdf_content">
                        <p>Insert your error message here, if the PDF cannot be displayed.</p>
                    </object>
                </div>
            </b-modal>
            <b-modal v-permission="['edit-diet-plan']" id="assign" ref="assign" ok-only ok-title="Assign"
                @show="getPartners" @hidden="resetData" @ok="handleOk" centered size="lg" title="Diet Plan Assignment"
                no-close-on-backdrop>
                <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
                    <form ref="form" @submit.prevent="handleSubmit(onAssign)">
                        <b-row>
                            <b-col cols="12" md="6">
                                <validation-provider #default="validationContext" name="alliance-partners" rules="required">
                                    <b-form-group label="Select Alliance Partners" label-for="partners"
                                        :state="getValidationState(validationContext)">
                                        <v-select v-model="userData.partners"
                                            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="partners"
                                            :reduce="val => val.id" :clearable="false" multiple input-id="partners"
                                            @input="getClients" />
                                        <b-form-invalid-feedback :state="getValidationState(validationContext)">
                                            {{ validationContext.errors[0] }}
                                        </b-form-invalid-feedback>
                                    </b-form-group>
                                </validation-provider>
                            </b-col>
                            <b-col cols="12" md="6">
                                <validation-provider #default="validationContext" name="clients" rules="required">
                                    <b-form-group label="Select Clients" label-for="clients"
                                        :state="getValidationState(validationContext)">
                                        <v-select v-model="userData.clients"
                                            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="clients"
                                            :reduce="val => val.id" :clearable="false" multiple input-id="clients" />
                                        <b-form-invalid-feedback :state="getValidationState(validationContext)">
                                            {{ validationContext.errors[0] }}
                                        </b-form-invalid-feedback>
                                    </b-form-group>
                                </validation-provider>
                            </b-col>
                            <b-col cols="12" md="6">
                                <validation-provider #default="validationContext" name="Expiry Date" rules="required">
                                    <b-form-group label="Expiry Date" label-for="expiry_date"
                                        :state="getValidationState(validationContext)">
                                        <b-form-datepicker id="expiry" :min="new Date()" v-model="userData.expiry_date"
                                            :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"></b-form-datepicker>
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
            <b-modal v-permission="['edit-diet-plan']" id="view-users" hide-footer centered size="lg"
                @show="getAssignedUsers" @hidden="resetUsers" title="Assigned Users" ref="view-users" no-close-on-backdrop>
                <b-table style="min-height:250px" ref="refRolesTable" class="position-relative" :items="list" responsive
                    :fields="ucolumns" :totalRows="totalRows" primaryKey="index" :sort-by.sync="sortBy" show-empty
                    empty-text="No matching records found" :sort-desc.sync="isSortDirDesc" :sticky-header="true">
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
                        <a @click="handleRemoveUser('dietPlan', data.item.plan_user_id)" v-permission="['edit-diet-plan']"
                            style="cursor:pointer" title="remove user">
                            <feather-icon icon="TrashIcon" />
                        </a>
                    </template>
                </b-table>

            </b-modal>
        </b-card>
    </div>
</template>
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
    BFormFile,
    BFormTextarea,
    BOverlay,
    BFormCheckbox,
    BFormDatepicker,
    BImg
} from "bootstrap-vue";
import { ValidationProvider, ValidationObserver } from "vee-validate";

import formValidation from "@core/comp-functions/forms/form-validation";
import { required } from "@validations";


import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

import vSelect from "vue-select";

import Ripple from 'vue-ripple-directive';


import { ref } from '@vue/composition-api'

export default {
    name: 'DietPlan',
    components: {
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
        BFormFile,
        BFormTextarea,
        BOverlay,
        BFormCheckbox,
        BFormDatepicker,
        ValidationProvider,
        ValidationObserver,
        vSelect,
        BImg,
    },
    directives: {
        'b-modal': VBModal,
    },
    props: {
        id: {
            type: [Number, String],
            defaul: null
        }
    },
    data() {

        return {
            pagination: {
                page: 1,
                per_page: 10,
                total: null
            },
            required,
            type: 'Create Plan',
            readonly: false,
            errors: [],
            list: [],
            totalRows: 0,
            userData: {
                clients: [],
                partners: [],
                expiry_date: null
            },
            plan: null,
            partners: [],
            clients: [],

        }
    },
    directives: {
        Ripple,
    },
    setup() {
        const plans = null;
        const loading = true;
        const sortBy = "1";
        const pdf = ref(null);
        const image = ref(null);
        const isSortDirDesc = false;

        const ucolumns = [
            { key: "index", label: "Sr. No.", sortable: true },
            { key: "name", label: "Name", sortable: true },
            { key: "email", label: "Email", sortable: true },
            { key: "phone", label: "Contact Number", sortable: true },
            { key: "actions", label: "Action", sortable: false },
        ];
        const columns = [
            { key: "no", label: "Plan No", sortable: true },
            { key: "title", label: "Title", sortable: true },
            { key: "image", label: "Important Notes", sortable: true },
            { key: "pdf", label: "PDF", sortable: true },
            { key: "instructions", label: "Instructions", sortable: true },
        ];
        const dietData = ref({
            no: '',
            title: '',
            instructions: "",
            image: '',
            pdf: '',
            expiry_date: ''
        });

        const resetDietData = () => {
            dietData.value = {
                no: null,
                title: null,
                instructions: "",
                image: null,
                pdf: null,
                expiry_date: null
            }
        }
        const {
            refFormObserver,
            getValidationState,
            resetForm
        } = formValidation(resetDietData);

        return {
            plans,
            pdf,
            image,
            ucolumns,

            loading,
            sortBy,
            isSortDirDesc,
            columns,
            dietData,
            refFormObserver,
            getValidationState,
            resetForm,
            resetDietData
        };
    },
    created() {
        if (this.$store.getters.user.role_id != 3) {
            this.columns.push({ key: "actions" });
        } else {
            this.columns.unshift({ key: "consultant_type", label: "Consultant Tyep", sortable: true });
            this.columns.unshift({ key: "consultant", label: "Consultant", sortable: true });
        }
        this.columns.unshift({ key: "index", label: "Sr. No.", sortable: true });

        if (this.$store.getters.user.role_id == 3) {
            this.columns = [
                { key: "index", label: "Sr. No.", sortable: true },
                { key: "no", label: "Plan No", sortable: true },
                { key: "image", label: "Important Notes", sortable: true },
                { key: "pdf", label: "Diet Plan", sortable: true },
                { key: "instructions", label: "Instructions", sortable: true },
            ];
        }

        this.getList();
    },
    methods: {

        async getList() {
            try {
                this.loading = true;
                this.pagination.client = this.id;
                const { data } = await axios.get("/dietPlan", {
                    params: this.pagination
                });
                this.plans = data.plans;
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
        viewDietModal(plan) {
            this.plan = plan;
            this.$refs['viewpdf'].show();
        },

        async onSubmit() {
            try {
                let data;
                let formData = new FormData;
                formData.append('client', this.id ? this.id : null);
                formData.append('no', this.dietData.no);
                formData.append('title', this.dietData.title);
                formData.append('expiry_date', this.dietData.expiry_date);
                formData.append('instructions', this.dietData.instructions);
                if (this.dietData.id) {
                    if (this.dietData.image) {
                        formData.append('image', this.dietData.image);
                    }
                    if (this.dietData.pdf) {
                        formData.append('pdf', this.dietData.pdf);
                    }
                    data = await axios.post(
                        `/dietPlan/${this.dietData.id}`, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });
                } else {

                    formData.append('image', this.dietData.image);
                    formData.append('pdf', this.dietData.pdf);
                    data = await axios.post("/dietPlan", formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });
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
                console.log(error);
                this.errors = error.response.data.errors;
            }
        },

        editPlan(data) {
            this.type = 'Update Plan'
            this.plan = data;
            // this.readonly = true;
            this.dietData.id = data.id;
            this.dietData.no = data.no;
            this.dietData.title = data.title;
            this.dietData.instructions = data.instructions;
        },

        async getAssignedUsers() {
            try {
                let id = this.plan.id;
                const { data } = await axios.get(`dietPlan/${id}/users`, {
                    params: this.pagination
                });
                this.list = data.users;
                this.totalRows = data.totalRows;

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
            if (this.userData.partners.length) {
                try {
                    const { data } = await axios.get('/client/list', {
                        params: { partners: this.userData.partners, plan: this.plan.id }
                    });
                    this.clients = data.clients;
                } catch (err) {
                    console.log(err)
                }
            }

        },

        viewAssignModal(plan) {
            this.plan = plan;
            this.$refs['assign'].show();
        },

        viewUserModal(plan) {
            this.plan = plan;
            this.$refs['view-users'].show();
        },

        async handleDeletePlan(id) {
            try {
                const data = await axios.delete(`/dietPlan/${id}`);
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

        setFileObject(e, field) {
            /*
              Set the local file variable to what the user has selected.
            */
            let supportedTypes = [];
            if (field === 'image') {
                supportedTypes = ['application/pdf']
            } else {
                supportedTypes = ['application/pdf']
            }

            let imageUrl = e.target.files[0];

            let type = imageUrl.type;

            if (!supportedTypes.includes(type)) {
                this[field] = null;
                this.dietData[field] = null;
                this.$toast({
                    component: ToastificationContent,
                    props: {
                        title: "Please select " + field + " file",
                        icon: "BellIcon",
                        variant: "warning"
                    }
                });
                return;
            }
            this.dietData[field] = imageUrl;
        },

        resetFormCard() {
            this.resetDietData();
            this.pdf = null;
            this.image = null;
            this.type = "Create Plan"
            this.resetForm();

        },

        resetUsers() {
            this.list = [];
        },

        handleOk(bvModalEvt) {
            bvModalEvt.preventDefault()
            this.$refs.refFormObserver.handleSubmit(this.onAssign);
        },

        resetData() {
            this.userData = {
                partners: [],
                clients: [],
                expiry_date: null
            }
        },

        async onAssign() {
            try {
                this.userData.plan = this.plan.id;
                const { data } = await axios.post('/dietPlan/assignment', this.userData);
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
            } catch (err) {
                console.log(err)
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
    }
}
</script>
<style lang="scss" scoped>
.per-page-selector {
    width: 90px;
}
</style>

<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";

#assignUser___BV_modal_body_ {
    min-height: 200px !important;
    max-height: 300px !important;
    overflow-y: auto;
}
</style>
