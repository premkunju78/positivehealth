<template>
    <div style="min-height:300px">
      <b-overlay :show="$store.state.app.shallShowOverlay" no-wrap opacity="0.5"  blur="1px"></b-overlay>

        <b-card  v-permission="['create-case-sheet']">
            <validation-observer
            #default="{ handleSubmit }"
            ref="refFormObserver"
            >
                <form ref="form" @submit.prevent="handleSubmit(onSubmit)">
                    <b-row>
                    <b-col cols="12" md="6">
                        <validation-provider
                            #default="validationContext"
                            name="date"
                            rules="required"
                        >
                            <b-form-group label="Date" label-for="date">
                                <b-form-input
                                    id="date"
                                    :state="
                                        getValidationState(
                                            validationContext
                                        )
                                    "
                                    v-model="caseSheetData.date"
                                    :readonly="readonly"
                                    :class="errors.date ? 'is-invalid' : ''"
                                    :area-invalid="errors.date ? true : false"
                                />
                                <b-form-invalid-feedback v-if="errors.date">
                                        {{errors.date[0] }}
                                </b-form-invalid-feedback>
                                <b-form-invalid-feedback v-else>
                                    {{ validationContext.errors[0] }}
                                </b-form-invalid-feedback>
                            </b-form-group>
                        </validation-provider>
                    </b-col>
                    </b-row>

                    <b-row>
                    <b-col cols="12" md="6">
                        <validation-provider
                            #default="validationContext"
                            name="Chief Concern"
                            rules="required"
                        >
                            <b-form-group label="Chief Concern" label-for="chief_concern">
                                <quill-editor
                                id="associate_concern"
                                v-model="caseSheetData.chief_concern"
                                :options="editorOption"
                                />
                                <b-form-invalid-feedback v-if="errors.chief_concern">
                                        {{errors.chief_concern[0] }}
                                </b-form-invalid-feedback>
                                <b-form-invalid-feedback v-else>
                                    {{ validationContext.errors[0] }}
                                </b-form-invalid-feedback>
                            </b-form-group>
                        </validation-provider>
                    </b-col>
                    </b-row>
                    <b-row>
                    <b-col cols="12" md="6">
                        <validation-provider
                            #default="validationContext"
                            name="Associate Concern"
                            rules="required"
                        >
                            <b-form-group label="Associate Concern" label-for="associate_concern">
                                <quill-editor
                                id="associate_concern"
                                v-model="caseSheetData.associate_concern"
                                :options="editorOption"
                                />
                                <b-form-invalid-feedback v-if="errors.associate_concern">
                                        {{errors.associate_concern[0] }}
                                </b-form-invalid-feedback>
                                <b-form-invalid-feedback v-else>
                                    {{ validationContext.errors[0] }}
                                </b-form-invalid-feedback>
                            </b-form-group>
                        </validation-provider>
                    </b-col>
                    </b-row>
                    <b-row>
                        <b-col cols="12" md="6">
                            <validation-provider
                                #default="validationContext"
                                name="Client Feedback"
                                rules="required"
                            >
                                <b-form-group
                                    label="Client Feedback"
                                    label-for="client_feedback"
                                >
                                <quill-editor
                                id="client_feedback"
                                v-model="caseSheetData.client_feedback"
                                :options="editorOption"
                                />
                                    <b-form-invalid-feedback v-if="errors.client_feedback">
                                        {{errors.client_feedback[0] }}
                                    </b-form-invalid-feedback>
                                    <b-form-invalid-feedback v-else>
                                        {{ validationContext.errors[0] }}
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>
                        </b-col>

                    </b-row>
                    <b-row>
                        <b-col cols="12" md="6">
                            <validation-provider
                                #default="validationContext"
                                name="plan_of_action"
                                rules="required"
                            >
                                <b-form-group
                                    label="Plan of action"
                                    label-for="plan_of_action"
                                >
                                <quill-editor
                                id="plan_of_action"
                                v-model="caseSheetData.plan_of_action"
                                :options="editorOption"
                                />

                                    <b-form-invalid-feedback v-if="errors.plan_of_action">
                                        {{errors.plan_of_action[0] }}
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
                        <b-button
                            variant="primary"
                            :block="
                            $store.getters['app/currentBreakPoint'] === 'xs'"
                            type="submit"
                            class="mr-2"
                            v-permission="['create-case-sheet']"
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
        BFormTextarea,
    	BFormGroup,
        BOverlay,
        BFormCheckbox,
        BFormDatepicker
    } from "bootstrap-vue";
    import { ValidationProvider, ValidationObserver } from "vee-validate";

    import formValidation from "@core/comp-functions/forms/form-validation";


    import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
    
    import { required } from "@validations";

    import vSelect from "vue-select";

    import Ripple from 'vue-ripple-directive'
    import 'quill/dist/quill.core.css'
    import 'quill/dist/quill.snow.css'
    import 'quill/dist/quill.bubble.css'
    import { quillEditor } from 'vue-quill-editor'

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
            BFormDatepicker,
            quillEditor
        },
        props: {
            id: {
                type: [Number,String],
                required: true
            }
        },
        data() {
            var toolbarOptions = [
                ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
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
                errors:[],
                readonly:false,
                loading:false,
                required
            };
        },
        directives: {
            Ripple,
        },
        setup() {
            const caseSheetData = ref({
              date: "",
              chief_concern:"",
              associate_concern: "",
              client_feedback:"",
              plan_of_action:"",
              id: null,
              client: null,

            });

            const resetcaseSheetData = function() {
                caseSheetData.value = {
                    date: "",
                    chief_concern:"",
                    associate_concern:"",
                    client_feedback: "",
                    plan_of_action:"",
                    id: null,
                    client: null,
                };
            };
            const {
                refFormObserver,
                getValidationState,
                resetForm
            } = formValidation(resetcaseSheetData);
            return {
                caseSheetData,
                refFormObserver,
                getValidationState,
                resetcaseSheetData,
                resetForm
            };
        },
        created() {
            //this.getList();
        },
        methods: {
           async onSubmit() {
                try {
                    var data = null;
                    this.caseSheetData.client = this.id;
                    if (this.caseSheetData.id) {
                        data = await axios.post(`/caseSheet/${this.caseSheetData.id}`,this.caseSheetData);
                    } else {
                        data = await axios.post("/caseSheet", this.caseSheetData);
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
                    //this.$router.replace('/casesheet/list');
                } catch (error) {
                    console.log(error);
                    if( error.response ){
                        this.errors = error.response.data.errors;
                    }
                }
            },

            resetFormCard() {
                this.resetcaseSheetData();
                this.resetForm();
            },
        }
    };
</script>

<style lang="scss" scoped>
    .per-page-selector {
        width: 90px;
    }
    .ql-container{
        min-height:100px !important
    }
    </style>

    <style lang="scss">
    @import "@core/scss/vue/libs/vue-select.scss";
    @import '@core/scss/vue/libs/quill.scss';
    .ql-container{
        min-height:100px !important
    }
</style>
