<template>
    <div style="min-height:300px">   
        <b-overlay :show="$store.state.app.shallShowOverlay" no-wrap opacity="0.5"  blur="1px"></b-overlay>
            <b-card :title="type"  v-permission="['create-investigation-config']">
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
                                            v-model="testItemData.name"
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
                            <b-col cols="12" md="6" >
                                <validation-provider
                                    #default="validationContext"
                                    name="categories"
                                >
                                    <b-form-group
                                        label="Select Category"
                                        label-for="categories"
                                        :state="getValidationState(validationContext)"
                                    >
                                       <v-select
                                            v-model="testItemData.test_category"
                                            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                            :options="categories"
                                            :reduce="val => val.id"
                                            :clearable="true"
                                            id="categories"
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
                :items="testItems"
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
                        <b-dropdown-item @click="editTestItem(data.item)"  v-permission="['edit-investigation-config']">
                            <feather-icon icon="EditIcon" />
                            <span class="align-middle ml-50" >Edit</span>
                        </b-dropdown-item>
                        <b-dropdown-item @click="handleDeleteTestItem(data.item.id)"  v-permission="['delete-investigation-config']">
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
			category: null,
			required,
			type: 'Create Test Item',
            readonly: false,
            categories: [],
            errors: [],
        };
    },
    directives: {
        Ripple,
    },
    setup() {
        const testItems = null;
        const loading = true;
        const sortBy = "1";
        const isSortDirDesc = false;
        const columns = [
            { key: "index", label: "Sr. No.", sortable: true },
            { key: "name", label: "Item Name", sortable: true },
            { key: "category", label: "Category Name", sortable: true },
            //{ key: "description", label: "Category Description", sortable: true },
            { key: "actions" }
        ];
        const testItemData = ref({
            name: "",
            description: "",
        });

        const resetTestItemData = function() {
            testItemData.value = {
                name: "",
                description: "",
            };
        };
        const {
            refFormObserver,
            getValidationState,
            resetForm
        } = formValidation(resetTestItemData);
        return {
            testItems,

            loading,
            sortBy,
            isSortDirDesc,
            columns,
            testItemData,
            refFormObserver,
            getValidationState,
			resetTestItemData,
            resetForm
        };
    },
    computed:{
        id : function(){
            return this.category.id;
        }
    },
    created() {
        this.getList();
        this.getCategories();
    },
    methods: {
        async getList() {
            try {
                this.loading = true;
                const { data } = await axios.get("testItem", {
                    params: this.pagination
                });
                this.testItems = data.testItems;
                this.pagination.per_page = data.per_page;
                this.pagination.total = data.total;
                this.pagination.page = data.page;
            } catch (err) {
                console.log(err);
            }
            this.loading = false;
        },

        async getCategories(){
            const {data} = await axios.get('testCategory/list');
            this.categories = data.categories;
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
                if (this.testItemData.id) {
                    data = await axios.put(
                        `/testItem/${this.testItemData.id}`,
                        this.testItemData
                    );
                } else {
                    data = await axios.post("/testItem", this.testItemData);
                }
                console.log(data )
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
                if( error.response){
                    this.errors = error.response.data.errors;
                } else {
                    comsole.log(error);
                }
            }
        },

		async editTestItem( test ) {
			this.type= 'Update Test Item'
            // this.readonly = true;
            this.testItem = test;
            this.testItemData.id  = test.id
            this.testItemData.name = test.name;
            this.testItemData.test_category = test.test_category_id;
		},

		async handleDeleteTestItem(id){
			try {
                const data = await axios.delete(`/testItem/${id}`);
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
            this.resetTestItemData();
            this.resetForm();
            this.type = 'Create Test item';
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
