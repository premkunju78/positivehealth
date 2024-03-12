<template>
    <div style="min-height:300px">
        <b-overlay :show="$store.state.app.shallShowOverlay" no-wrap opacity="0.5" blur="1px"></b-overlay>
        <b-card :title="type" v-permission="['create-program']">
            <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
                <form ref="form" @submit.prevent="handleSubmit(onSubmit)">
                    <b-row>
                        <b-col cols="12" md="6">
                            <!-- User Specialization -->
                            <validation-provider #default="validationContext" name="Program Type" rules="required">
                                <b-form-group label="Program Type" label-for="type"
                                    :state="getValidationState(validationContext)">
                                    <v-select v-model="packageData.type" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                        :options="['Wellness', 'Women Care', 'Lifeskill', 'Workshop']" :clearable="false"
                                        input-id="type" />
                                    <b-form-invalid-feedback v-if="errors.type">
                                        {{ errors.type[0] }}
                                    </b-form-invalid-feedback>
                                    <b-form-invalid-feedback :state="getValidationState(validationContext)" v-else>
                                        {{ validationContext.errors[0] }}
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>
                        </b-col>
                        <b-col cols="12" md="6">
                            <validation-provider #default="validationContext" name="Category" rules="required">
                                <b-form-group label="Category" label-for="category">
                                    <v-select v-model="packageData.category"
                                        :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                        :options="['Diet Program', 'Yoga / Fitness Program', 'Counselling Program', 'Arika Program', 'Umika Program']"
                                        :clearable="false" input-id="category" />
                                    <!-- <b-form-input id="category" :state="getValidationState(
                                            validationContext
                                        )
                                        " v-model="packageData.category" :readonly="readonly"
                                        :class="errors.category ? 'is-invalid' : ''"
                                        :area-invalid="errors.category ? true : false" /> -->
                                    <b-form-invalid-feedback v-if="errors.category">
                                        {{ errors.category[0] }}
                                    </b-form-invalid-feedback>
                                    <b-form-invalid-feedback v-else>
                                        {{ validationContext.errors[0] }}
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>
                        </b-col>
                        <b-col cols="12" md="6">
                            <validation-provider #default="validationContext" name="Sub Category" rules="required">
                                <b-form-group label="Sub Category" label-for="sub-category">
                                    <b-form-input id="sub-category" :state="getValidationState(
                                            validationContext
                                        )
                                        " v-model="packageData.sub_category" :readonly="readonly"
                                        :class="errors.sub_category ? 'is-invalid' : ''"
                                        :area-invalid="errors.sub_category ? true : false" />
                                    <b-form-invalid-feedback v-if="errors.sub_category">
                                        {{ errors.sub_category[0] }}
                                    </b-form-invalid-feedback>
                                    <b-form-invalid-feedback v-else>
                                        {{ validationContext.errors[0] }}
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>
                        </b-col>
                        <b-col cols="12" md="6">
                            <validation-provider #default="validationContext" name="Title" rules="required">
                                <b-form-group label="Title" label-for="title">
                                    <b-form-input id="name-input" :state="getValidationState(
                                            validationContext
                                        )
                                        " v-model="packageData.title" :readonly="readonly"
                                        :class="errors.title ? 'is-invalid' : ''"
                                        :area-invalid="errors.title ? true : false" />
                                    <b-form-invalid-feedback v-if="errors.title">
                                        {{ errors.title[0] }}
                                    </b-form-invalid-feedback>
                                    <b-form-invalid-feedback v-else>
                                        {{ validationContext.errors[0] }}
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>
                        </b-col>
                        <b-col cols="12" md="6">
                            <!-- User Specialization -->
                            <validation-provider #default="validationContext" name="Duration" rules="required">
                                <b-form-group label="Duration" label-for="type"
                                    :state="getValidationState(validationContext)">
                                    <v-select v-model="packageData.duration"
                                        :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                        :options="['1', '3', '6', '12', 'Free Session']" :clearable="false" input-id="type" />
                                    <b-form-invalid-feedback v-if="errors.duration">
                                        {{ errors.duration[0] }}
                                    </b-form-invalid-feedback>
                                    <b-form-invalid-feedback :state="getValidationState(validationContext)" v-else>
                                        {{ validationContext.errors[0] }}
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>
                        </b-col>
                        <b-col cols="12" md="6">
                            <!-- User Specialization -->
                            <validation-provider #default="validationContext" name="Plan" rules="required">
                                <b-form-group label="Plan" label-for="type" :state="getValidationState(validationContext)">
                                    <v-select v-model="packageData.duration"
                                        :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                        :options="['1 Month', '3 Months', '6 Months', '12 Months', 'Free Session']"
                                        :clearable="false" input-id="type" />
                                    <b-form-invalid-feedback v-if="errors.duration">
                                        {{ errors.duration[0] }}
                                    </b-form-invalid-feedback>
                                    <b-form-invalid-feedback :state="getValidationState(validationContext)" v-else>
                                        {{ validationContext.errors[0] }}
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>
                        </b-col>
                        <b-col cols="12" md="6">
                            <validation-provider #default="validationContext" name="Paytment Type" rules="required">
                                <b-form-group label="Paytment Type" label-for="payment_type"
                                    :state="getValidationState(validationContext)">
                                    <v-select v-model="packageData.payment_type"
                                        :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="['Paid', 'Free']"
                                        :clearable="false" input-id="payment_type" />
                                    <b-form-invalid-feedback v-if="errors.payment_type">
                                        {{ errors.payment_type[0] }}
                                    </b-form-invalid-feedback>
                                    <b-form-invalid-feedback :state="getValidationState(validationContext)" v-else>
                                        {{ validationContext.errors[0] }}
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>
                        </b-col>
                        <b-col cols="12" md="6">
                            <validation-provider #default="validationContext" name="Communication Type" rules="required">
                                <b-form-group label="Communication Type" label-for="comunication_type"
                                    :state="getValidationState(validationContext)">
                                    <v-select v-model="packageData.communication_type"
                                        :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                        :options="['Audio Call', 'Video Call']" :clearable="false"
                                        input-id="comunication_type" />
                                    <b-form-invalid-feedback v-if="errors.communication_type">
                                        {{ errors.comunication_type[0] }}
                                    </b-form-invalid-feedback>
                                    <b-form-invalid-feedback :state="getValidationState(validationContext)" v-else>
                                        {{ validationContext.errors[0] }}
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>
                        </b-col>
                        <b-col sm="6">
                            <!-- image -->
                            <b-media no-body :class="errors.image ? 'is-invalid' : ''"
                                :area-invalid="errors.image ? true : false">
                                <b-media-aside>
                                    <a :href="'/view/file?path=' + packageData.image" target="_blank">
                                        <b-img ref="previewEl" rounded :src="'/view/file?path=' + packageData.image"
                                            height="80" />
                                    </a>
                                    <!--/ image -->
                                </b-media-aside>

                                <b-media-body class="mt-75 ml-75">
                                    <!-- upload button -->
                                    <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" size="sm"
                                        class="mb-75 mr-75" @click="$refs.refInputEl.$el.click()">
                                        Upload Program Thumbnail
                                    </b-button>
                                    <b-form-file ref="refInputEl" v-model="image" accept=".jpg, .png, .gif" :hidden="true"
                                        plain @input="validateSize(image, 'image', imageRenderer)" />
                                    <!--/ upload button -->

                                    <!-- reset -->
                                    <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" variant="outline-secondary"
                                        size="sm" class="mb-75 mr-75" v-if="image"
                                        @click="$refs.previewEl.src = '/view/file?path=' + packageData.image; image = null">
                                        Reset
                                    </b-button>
                                    <!--/ reset -->
                                    <b-card-text>Allowed JPG, GIF or PNG. Max size of 2MB</b-card-text>
                                </b-media-body>
                            </b-media>
                            <b-form-invalid-feedback v-if=" errors.image ">
                                {{ errors.image[0] }}
                            </b-form-invalid-feedback>
                        </b-col>
                        <b-col cols="12" md="6">
                            <validation-provider #default=" validationContext " name="Description" rules="required">
                                <b-form-group label="Description" label-for="description">
                                    <quill-editor id="description" v-model=" packageData.description "
                                        :options=" editorOption " />

                                    <b-form-invalid-feedback v-if=" errors.description ">
                                        {{errors.description[0] }}
                                    </b-form-invalid-feedback>
                                    <b-form-invalid-feedback v-else>
                                        {{ validationContext.errors[0] }}
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>
                        </b-col>



                    </b-row>
                    <b-row style=" margin-top: 2%;">
                        <!-- Action Buttons -->
                        <b-button variant="primary" :block="
                            $store.getters['app/currentBreakPoint'] === 'xs'
                        " type="submit" class="mr-2"
                            v-permission=" ['create-program'] ">
                            Save Changes
                        </b-button>
                        <b-button v-ripple.400=" 'rgba(186, 191, 199, 0.15)' " type="button" @click=" resetFormCard "
                            variant="outline-secondary">
                            Cancel
                        </b-button>

                    </b-row>
                </form>
            </validation-observer>
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

import Ripple from 'vue-ripple-directive'
import 'quill/dist/quill.core.css'
import 'quill/dist/quill.snow.css'
import 'quill/dist/quill.bubble.css'
import { quillEditor } from 'vue-quill-editor'


import { useInputImageRenderer } from '@core/comp-functions/forms/form-utils';

import { validateSize } from '@core/utils/utils'

import { ref } from '@vue/composition-api'


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
    },
    data() {
        var toolbarOptions = [
            ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
            [{ 'indent': '-1' }, { 'indent': '+1' }],          // outdent/indent
            [{ 'direction': 'rtl' }],                         // text direction
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
            [{ 'color': [] }],          // dropdown with defaults from theme
            [{ 'font': [] }],
            [{ 'align': [] }],                                       // remove formatting button
        ];
        return {
            editorOption: {
                modules: {
                    toolbar: toolbarOptions
                },
                theme: 'snow',
            },
            pagination: {
                page: 1,
                per_page: 10,
                total: null
            },
            list: [],
            package: null,
            required,
            type: 'Create Program',
            groupPackages: [],
            readonly: false,
            errors: [],
            mappingData: {
                partners: [],
                cost: null
            },
            totalRows: 0,
            partners: [],
            key: 0,
            image: null
        };
    },
    directives: {
        Ripple,
    },
    setup() {
        const loading = true;
        const packageData = ref({
            title: "",
            description: "",
            category: "",
            sub_category: "",
            id: null,
            duration: 'Free Session',
            image: null

        });

        const resetPackageData = function () {
            packageData.value = {
                title: "",
                description: "",
                category: "",
                sub_category: "",
                id: null,
                duration: 'Free Session',
                image: null
            };
        };

        const refInputEl = ref(null)
        const previewEl = ref(null)

        const imageRenderer = useInputImageRenderer(refInputEl, previewEl)
        const {
            refFormObserver,
            getValidationState,
            resetForm
        } = formValidation(resetPackageData);
        return {
            loading,
            packageData,
            refFormObserver,
            getValidationState,
            resetPackageData,
            resetForm,
            imageRenderer,
            refInputEl,
            previewEl

        };
    },
    computed: {
        id: function () {
            return this.package.id;
        }
    },

    watch: {
        "pagination.per_page": function (val) {
            this.handleFilterChange(val);
        }
    },
    methods: {
        validateSize,
        async getList() {
            try {
                this.loading = true;
                const { data } = await axios.get("package", {
                    params: this.pagination
                });
                this.packages = data.packages;
                this.pagination.per_page = data.per_page;
                this.pagination.total = data.total;
                this.pagination.page = data.page;

                this.key++;
            } catch (err) {
                console.log(err);
            }
            this.loading = false;
        },
        async onSubmit() {
            try {
                let data;
                if (this.packageData.id) {
                    data = await axios.put(
                        `/package/${this.packageData.id}`,
                        this.packageData
                    );
                } else {
                    data = await axios.post("/package", this.packageData);
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

        editPackage(data) {
            this.type = 'Update Program'
            // this.readonly = true;

            this.packageData.id = data.id;
            this.packageData.title = data.title;
            this.packageData.category = data.category;
            this.packageData.sub_category = data.sub_category;
            this.packageData.description = data.description;
            this.packageData.duration = data.duration;
            this.packageData.type = data.type;


        },


        resetFormCard() {
            this.resetForm();
            this.resetPackageData();
            this.type = 'Create Program';
            this.readonly = false;
            this.errors = [];
        },
    }
};
</script>

<style lang="scss" scoped>
.per-page-selector {
    width: 90px;
}

.ql-container {
    min-height: 100px !important
}
</style>

<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";
@import '@core/scss/vue/libs/quill.scss';

.ql-container {
    min-height: 100px !important
}
</style>