<template>
    <div class="auth-wrapper auth-v2">
        <div class="auth-inner" style="width: 90%;margin: 0 auto;padding: 30px;">
            <!-- Brand logo-->
            <b-link class="brand-logo">
                <b-img fluid v-if="logo" :src="'/view/file?path=' + logo" alt="Logo" height="50" width="50"
                    style="margin-bottom:20px" />
                <!-- <vuexy-logo  v-else/>-->

                <b-img v-if="!logo" :src="appLogoImage" alt="logo" />
            </b-link>
            <b-row :key="key">
                <b-col :sm="5" :md="4" :lg="3" :xl="3" v-for="program in packages" :key="program.id">
                    <b-card class="ecommerce-card" no-body>
                        <!-- Product Details -->
                        <img :src="'/view/file?path=' + program.image" alt="Card image cap" class="card-img-top"
                            style="height: 160px" />
                        <b-card-body>
                            <div class="align-items-center justify-content-center">
                                <b-row class="mb-2">
                                    <b-col cols="9">
                                        <h5 class="mb-0 text-info heading" style="font-weight: bolder"
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
                                            Plan:
                                            {{ getPlanName(program) }}
                                        </small>
                                    </b-col>
                                </b-row>
                            </div>
                            <div class="d-flex align-items-center item-rating justify-content-center">
                                <b-button size="sm" @click="viewProgramModal(program)" variant="info mr-1">
                                    <span style="color: #fff" class="text-nowrap">Know More</span>
                                </b-button>
                            </div>
                        </b-card-body>
                    </b-card>
                </b-col>
            </b-row>
            <b-pagination v-if="pagination.total" v-model="pagination.page" :page.sync="pagination.page"
                :total-rows="pagination.total" :index="1" :per-page="pagination.per_page" aria-controls="video-row"
                align="right" @change="handlePaginationChange">
            </b-pagination>
        </div>

        <b-modal id="program" ref="view-program" @hidden="resetData" title="Program" ok-only no-close-on-backdrop>
            <h4>{{ prgrm.title }}</h4>
            <div v-html="prgrm.description"></div>
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
    BMedia,
    BMediaAside,
    BMediaBody,
    BFormFile,
    BLink,
    BImg,
} from "bootstrap-vue";
import { ValidationProvider, ValidationObserver } from "vee-validate";

import formValidation from "@core/comp-functions/forms/form-validation";
import { required } from "@validations";

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

import vSelect from "vue-select";

import Ripple from "vue-ripple-directive";
import "quill/dist/quill.core.css";
import "quill/dist/quill.snow.css";
import "quill/dist/quill.bubble.css";
import { quillEditor } from "vue-quill-editor";

import { useInputImageRenderer } from "@core/comp-functions/forms/form-utils";

import { validateSize } from "@core/utils/utils";
import flatPickr from "vue-flatpickr-component";

import { ref } from "@vue/composition-api";

export default {
    components: {
        BCard,
        BCardBody,
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
        quillEditor,
        BMedia,
        BMediaAside,
        BMediaBody,
        BFormFile,
        BLink,
        BImg,
        flatPickr,
    },
    props: {
        url: {
            type: [String, null],
            default: null,
        },
    },
    data() {
        var toolbarOptions = [
            ["bold", "italic", "underline", "strike"], // toggled buttons
            [{ list: "ordered" }, { list: "bullet" }],
            [{ indent: "-1" }, { indent: "+1" }], // outdent/indent
            [{ direction: "rtl" }], // text direction
            [{ header: [1, 2, 3, 4, 5, 6, false] }],
            [{ color: [] }], // dropdown with defaults from theme
            [{ font: [] }],
            [{ align: [] }], // remove formatting button
        ];
        return {
            prgrm: {},
            logo: null,
            editorOption: {
                modules: {
                    toolbar: toolbarOptions,
                },
                theme: "snow",
            },
            pagination: {
                url: this.url,
                page: 1,
                per_page: 10,
                total: null,
            },
            plan: "",
            list: [],
            package: null,
            required,
            type: "",
            groupPackages: [],
            readonly: false,
            errors: [],
            pkg: null,
            mappingData: {
                partners: [],
                cost: {},
                plans: [],
                costs: [],
            },
            totalRows: 0,
            partners: [],
            key: 0,
            image: null,
            showRange: false,
            plans: [
                "1 Month",
                "2 Months",
                "3 Months",
                "6 Months",
                "9 Months",
                "12 Months",
                "1 Session",
                "2 Sessions",
                "3 Sessions",
                "4 Sessions",
                "5 Sessions",
                "6 Sessions",
                "7 Sessions",
                "8 Sessions",
                "9 Sessions",
                "10 Sessions",
                "11 Sessions",
                "12 Sessions",
                "60 Minutes",
                "90 Minutes",
                "120 Minutes",
            ],
            sessions: [
                "Diet Session",
                "Yoga Session",
                "HITT Sessions",
                "Doctor Consultation",
                "Counseling",
                "Training",
                "Workshops",
            ],
        };
    },

    directives: {
        Ripple,
    },
    setup() {
        const packages = [];
        const loading = true;
        const sortBy = "1";
        const isSortDirDesc = false;
        const columns = [
            { key: "index", label: "Sr. No.", sortable: true },
            { key: "title", label: "Title", sortable: true },
            { key: "category", label: "Category", sortable: true },
            { key: "type", label: "Type", sortable: true },
            { key: "description", label: "Description", sortable: true },
            { key: "actions" },
        ];
        const ucolumns = [
            { key: "index", label: "Sr. No.", sortable: true },
            { key: "name", label: "Name", sortable: true },
            { key: "email", label: "Email", sortable: true },
            { key: "phone", label: "Contact Number", sortable: true },
            { key: "cost", label: "Program Cost", sortable: true },
            { key: "status", label: "Status", sortable: false },
            { key: "enrolled", label: "Enrolled", sortable: true },
            { key: "actions", label: "Action", sortable: false },
        ];
        const packageData = ref({
            title: "",
            description: "",
            category: "",
            id: null,
            duration: "Free Session",
            image: null,
            payment_type: "Free",
            communication_type: "Video Call",
            start_date: "",
            end_date: "",
            sessions: [{}],
            plans: ["10 min"],
        });

        const resetPackageData = function () {
            packageData.value = {
                title: "",
                description: "",
                category: "",
                id: null,
                duration: "Free Session",
                image: null,
                payment_type: "Free",
                communication_type: "Video Call",
                start_date: "",
                end_date: "",
                sessions: [{}],
                plans: ["10 min"],
            };
        };

        const refInputEl = ref(null);
        const previewEl = ref(null);

        const imageRenderer = useInputImageRenderer(refInputEl, previewEl);
        const { refFormObserver, getValidationState, resetForm } = formValidation(
            resetPackageData
        );
        return {
            packages,

            loading,
            sortBy,
            isSortDirDesc,
            columns,
            ucolumns,
            packageData,
            refFormObserver,
            getValidationState,
            resetPackageData,
            resetForm,
            imageRenderer,
            refInputEl,
            previewEl,
        };
    },
    computed: {
        id: function () {
            return this.package.id;
        },
    },

    watch: {
        "pagination.per_page": function (val) {
            this.handleFilterChange(val);
        },
    },
    created() {
        this.getList();
    },
    methods: {
        validateSize,
        async getList() {
            try {
                this.loading = true;
                this.pagination.url = this.url;
                const { data } = await axios.get("alliance-partners/programs", {
                    params: this.pagination,

                });
                this.packages = data.packages;
                this.pagination.per_page = data.per_page;
                this.pagination.total = data.total;
                this.pagination.page = data.page;
                this.logo = data.user.logo;

                this.key++;
            } catch (err) {
                console.log(err);
            }
            this.loading = false;
        },
        viewProgramModal(data) {
            this.prgrm = data;
            this.$refs["view-program"].show();
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
                let packageData = new FormData();
                packageData.append("title", this.packageData.title);
                packageData.append("description", this.packageData.description);
                packageData.append("category", this.packageData.category);
                packageData.append("image", this.image);
                packageData.append("type", this.packageData.type);
                packageData.append("payment_type", this.packageData.payment_type);
                packageData.append("communication_type", this.packageData.communication_type);
                packageData.append("sessions", JSON.stringify(this.packageData.sessions));
                packageData.append("plans", JSON.stringify(this.packageData.plans));
                packageData.append("start_date", this.packageData.start_date);
                packageData.append("end_date", this.packageData.end_date);

                if (this.packageData.id) {
                    packageData.append("id", this.packageData.id);
                    data = await axios.post(`/package/${this.packageData.id}`, packageData, {
                        headers: {
                            "content-type": "multipart/form-data",
                        },
                    });
                } else {
                    data = await axios.post("/package", packageData, {
                        headers: {
                            "content-type": "multipart/form-data",
                        },
                    });
                }
                this.resetFormCard();

                this.$toast({
                    component: ToastificationContent,
                    props: {
                        title: data.data.message,
                        icon: "BellIcon",
                        variant: data.data.success ? "success" : "danger",
                    },
                });

                this.getList();
            } catch (error) {
                if (error.response) {
                    this.errors = error.response.data.errors;
                } else {
                    console.log(error);
                }
            }
        },

        async editPackage(data) {
            await window.scroll({
                top: 0,
                left: 0,
                behavior: "smooth",
            });
            this.type = "Update Program";
            // this.readonly = true;
            this.packageData.id = data.id;
            this.packageData.title = data.title;
            this.packageData.category = data.category;
            this.packageData.description = data.description;
            this.packageData.type = data.type;
            this.packageData.payment_type = data.payment_type;
            this.packageData.communication_type = data.communication_type;
            this.packageData.sessions = data.sessions;
            this.packageData.plans = data.plans ? data.plans : [];
            this.packageData.image = data.image;
            this.packageData.start_date = data.start_date;
            this.packageData.end_date = data.end_date;
        },

        async getPartners() {
            try {
                const { data } = await axios.get(
                    "/package/alliance-partner/list?package_id=" + this.package.id
                );
                this.partners = data.partners;
            } catch (err) {
                console.log(err);
            }
        },
        async getAssignedUsers() {
            try {
                let id = this.package.id;
                const { data } = await axios.get(`package/${id}/users`, {
                    params: this.pagination,
                });
                this.list = data.users;
                this.totalRows = data.totalRows;
            } catch (err) {
                console.log(err);
            }
        },

        viewAssignModal(data) {
            this.package = data;
            this.mappingData.plans = data.plans;
            this.mappingData.costs = data.cost;
            this.getPartners();
            this.$refs["assign"].show();
        },

        viewUserModal(data) {
            this.package = data;
            this.$refs["view-users"].show();
        },

        async handleDeletePackage(id) {
            try {
                const data = await axios.delete(`/package/${id}`);
                this.$toast({
                    component: ToastificationContent,
                    props: {
                        title: data.data.message,
                        icon: "BellIcon",
                        variant: data.data.success ? "success" : "danger",
                    },
                });
                this.getList();
                this.key++;
            } catch (err) {
                console.log(err);
            }
        },

        resetFormCard() {
            this.resetForm();
            this.resetPackageData();
            this.type = "";
            this.readonly = false;
            this.errors = [];
            this.image = null;
            this.$refs.previewEl.src = "/view/file?path=" + this.packageData.image;
        },

        async onAssign() {
            try {
                this.mappingData.package = this.package.id;
                const { data } = await axios.post("/package/assignment", this.mappingData);
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
        resetUsers() {
            this.list = [];
        },

        handleOk(bvModalEvt) {
            bvModalEvt.preventDefault();

            this.$refs.refFormObserver.handleSubmit(this.onAssign);
        },
        resetData() {
            this.mappingData.partners = [];
            this.mappingData.cost = {};
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

        addRow(field) {
            let val = field === "plans" ? "" : {};
            this.packageData[field].push(val);
        },
        removeRow(index, field) {
            this.packageData[field].splice(index, 1);
        },
        enableFields(a) {
            if (a === "Workshop" || a == "LifeSkill") this.showRange = true;
            else this.showRange = false;
        },

        validatePlan(value, index) {
            this.packageData.plans[index] = "";
            if (!this.packageData.plans.includes(value)) {
                this.packageData.plans[index] = value;
            }
        },
        getPlanName(program) {
            return program.plans ? program.plans.slice(0, 2).join("/") : "";
        },
        typeChanged() {
            this.pagination.type = this.type;
            this.getList();
        },
        planChanged() {
            this.pagination.plan = this.plan;
            this.getList();
        },
        fetchRecent() {
            this.pagination.recent = true;
            this.pagination.sort = false;
            this.recent = "border-info text-info";
            this.sort = "";
            this.getList();
        },
        sortAlphabetically() {
            this.pagination.sort = true;
            this.pagination.recent = false;
            this.sort = "border-info text-info";
            this.recent = "";
            this.getList();
        },
    },
};
</script>
  
<style lang="scss" scoped>
.per-page-selector {
    width: 90px;
}

.ql-container {
    min-height: 100px !important;
}
</style>
  
<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";
@import "@core/scss/vue/libs/quill.scss";
@import "@core/scss/vue/libs/vue-flatpicker.scss";
</style>
  