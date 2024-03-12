<template>
    <div style="min-height:300px">   
        <b-overlay :show="$store.state.app.shallShowOverlay" no-wrap opacity="0.5"  blur="1px"></b-overlay>
            <b-card :title="type"  v-permission="['create-affirmation']">
                <validation-observer
                    #default="{ handleSubmit }"
                    ref="refFormObserver"
                >
                    <form ref="form" @submit.prevent="handleSubmit(onSubmit)">
                        <b-row>
                            <b-col cols="12" md="8">
                                <validation-provider
                                    #default="validationContext"
                                    name="Affirmation"
                                    rules="required"
                                >
                                    <b-form-group label="Affirmation" label-for="Affirmation">
                                        <b-form-textarea
                                            id="affirmation"
                                            :state="
                                                getValidationState(
                                                    validationContext
                                                )
                                            "
                                            rows="4"
                                            v-model="affirmation.text"
                                            :readonly="readonly"
                                            :class="errors.body ? 'is-invalid' : ''"
                                            :area-invalid="errors.body ? true : false"
                                        />
                                        <b-form-invalid-feedback v-if="errors.body">
                                             {{errors.name[0] }}
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
                :items="affirmations"
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
                        <b-dropdown-item @click="editAffirmation(data.item)"  v-permission="['edit-affirmation']">
                            <feather-icon icon="EditIcon" />
                            <span class="align-middle ml-50" >Edit</span>
                        </b-dropdown-item>
                        <b-dropdown-item @click="handleDeleteAffirmation(data.item.id)"  v-permission="['delete-affirmation']">
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
	BFormGroup,
    BFormTextarea,
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
        BFormTextarea,
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
			category: null,
			required,
			type: 'New Affirmation',
            readonly: false,
            errors: [],
        };
    },
    directives: {
        Ripple,
    },
    setup() {
        const affirmations = null;
        const loading = true;
        const sortBy = "1";
        const isSortDirDesc = false;
        const columns = [
            { key: "index", label: "Sr. No.", sortable: true },
            { key: "text", label: "Affirmation", sortable: true },
            { key: "actions" }
        ];
        const affirmation = ref({
            text: "",
        });

        const resetAffirmation = function() {
            affirmation.value = {
                text: "",
            };
        };
        const {
            refFormObserver,
            getValidationState,
            resetForm
        } = formValidation(resetAffirmation);
        return {
            affirmations,

            loading,
            sortBy,
            isSortDirDesc,
            columns,
            affirmation,
            refFormObserver,
            getValidationState,
			resetAffirmation,
            resetForm
        };
    },
    created() {
        this.getList();
    },
    methods: {
        async getList() {
            try {
                this.loading = true;
                const { data } = await axios.get("affirmation", {
                    params: this.pagination
                });
                this.affirmations = data.affirmations;
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
        async onSubmit() {
            try {
                let data;
                if (this.affirmation.id) {
                    data = await axios.put(
                        `/affirmation/${this.affirmation.id}`,
                        this.affirmation
                    );
                } else {
                    data = await axios.post("/affirmation", this.affirmation);
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

		async editAffirmation( affirmation ) {
			this.type= 'Update Affirmation'
            this.affirmation = affirmation;
		},

		async handleDeleteAffirmation(id){
			try {
                const data = await axios.delete(`/affirmation/${id}`);
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
            this.resetAffirmation();
            this.resetForm();
            this.type = 'Create Category';
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
</style>

<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";
</style>
