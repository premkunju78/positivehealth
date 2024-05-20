<template>
    <div style="min-height:300px">   
        <b-overlay :show="$store.state.app.shallShowOverlay" no-wrap opacity="0.5"  blur="1px"></b-overlay>
            <b-card :title="type" v-permission="['create-investigation-config']">
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
                                            v-model="categoryData.name"
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
                            <!-- <b-col cols="12" md="6">
                                <validation-provider
                                    #default="validationContext"
                                    name="Description"
                                >
                                    <b-form-group
                                        label="Description"
                                        label-for="description"
                                    >
                                        <b-form-input
                                            id="description"
                                            v-model="categoryData.description"
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
                            </b-col> -->
                        </b-row>
                        <b-row>
                            <b-col cols="12" md="6">
                              <validation-provider
                                #default="validationContext"
                                name="type"
                                rules="required"
                              >
                                <b-form-group label="Type" label-for="type">
                                  <v-select
                                    id="type-input"
                                    :reduce="val => val.id"
                                    :state="getValidationState(validationContext)"
                                    v-model="categoryData.type"
                                    :readonly="readonly"
                                    :class="errors.type ? 'is-invalid' : ''"
                                    :area-invalid="errors.type ? true : false"
                                    :options="categoryTypesOptions" 
                                  >
                                  </v-select>
                                  <b-form-invalid-feedback v-if="errors.type">
                                    {{ errors.type[0] }}
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
                :items="categories"
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

                <template #cell(type)="data">
                    <span class="text-primary" v-if="data.item.type === 'onsite'">OnSite</span>
                    <span class="text-primary" v-if="data.item.type === 'home-based'">Home Based</span>
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
                        <b-dropdown-item @click="editCategory(data.item)"  v-permission="['edit-investigation-config']">
                            <feather-icon icon="EditIcon" />
                            <span class="align-middle ml-50" >Edit</span>
                        </b-dropdown-item>
                        <b-dropdown-item @click="handleDeleteCategory(data.item.id)"  v-permission="['delete-investigation-config']">
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
			type: 'Create Category',
            readonly: false,
            errors: [],
            categoryTypesOptions: [
                { id: 'onsite', label: 'OnSite' },
                { id: 'home-based', label: 'Home Based' },
            ]
        };
    },
    directives: {
        Ripple,
    },
    setup() {
        const categories = null;
        const loading = true;
        const sortBy = "1";
        const isSortDirDesc = false;
        const columns = [
            { key: "index", label: "Sr. No.", sortable: true },
            { key: "name", label: "Category Name", sortable: true },
            { key: "type", label: "Type", sortable: true },
            { key: "actions" }
        ];
        const categoryData = ref({
            name: "",
            description: "",
        });

        const resetCategoryData = function() {
            categoryData.value = {
                name: "",
                description: "",
            };
        };
        const {
            refFormObserver,
            getValidationState,
            resetForm
        } = formValidation(resetCategoryData);
        return {
            categories,

            loading,
            sortBy,
            isSortDirDesc,
            columns,
            categoryData,
            refFormObserver,
            getValidationState,
			resetCategoryData,
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
                const { data } = await axios.get("testCategory", {
                    params: this.pagination
                });
                this.categories = data.categories;
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
                if (this.categoryData.id) {
                    data = await axios.put(
                        `/testCategory/${this.categoryData.id}`,
                        this.categoryData
                    );
                } else {
                    data = await axios.post("/testCategory", this.categoryData);
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

		async editCategory( category ) {
			this.type= 'Update Category'
            // this.readonly = true;
            this.category = category;
            this.categoryData.id  = category.id
            this.categoryData.name = category.name;
            this.categoryData.type = category.type;
            this.categoryData.description = category.description;
            window.scrollTo({ top: 0, behavior: "smooth" });
		},

		async handleDeleteCategory(id){
            if(!confirm('Are you sure!')) {
               return ;
            }
            
			try {
                const data = await axios.delete(`/testCategory/${id}`);
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
            this.resetCategoryData();
            this.resetForm();
            this.type = 'Create Category';
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
