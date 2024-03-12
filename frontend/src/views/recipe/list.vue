<template>
    <div style="min-height:300px">
        <b-overlay :show="$store.state.app.shallShowOverlay" no-wrap opacity="0.5" blur="1px"></b-overlay>
        <b-card :title="type" v-permission="['create-recipes']" v-if="!id">
            <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
                <form ref="form" @submit.prevent="handleSubmit(onSubmit)">
                    <b-row>
                        <b-col cols="12" md="6">
                            <validation-provider #default="validationContext" name="Name" rules="required">
                                <b-form-group label="Name" label-for="name">
                                    <b-form-input id="name" v-model="recipeData.name" :state="getValidationState(
                                        validationContext
                                    )
                                        " :readonly="readonly" :class="errors.name ? 'is-invalid' : ''"
                                        :area-invalid="errors.name ? true : false" />
                                    <b-form-invalid-feedback v-if="errors.name">
                                        {{ errors.name[0] }}
                                    </b-form-invalid-feedback>
                                    <b-form-invalid-feedback>
                                        {{ validationContext.errors[0] }}
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>
                        </b-col>
                        <b-col cols="12" md="6">
                            <validation-provider #default="validationContext" name="Tags" rules="required">
                                <b-form-group label="Tags" label-for="tags">
                                    <tags-input v-model="recipeData.tags" id="tags" id-field="id" text-field="name"
                                        :typeahead="true" :add-tags-on-comma="true"
                                        typeahead-url="/api/search/tags?tag=:search">
                                    </tags-input>

                                    <b-form-invalid-feedback v-if="errors.instructions">
                                        {{ errors.instructions[0] }}
                                    </b-form-invalid-feedback>
                                    <b-form-invalid-feedback :state="getValidationState(validationContext)">
                                        {{ validationContext.errors[0] }}
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>
                        </b-col>
                        <b-col cols="12" md="6">
                            <!-- User Specialization -->
                            <validation-provider #default="validationContext" name="Recipe Type" rules="required">
                                <b-form-group label="Recipe Type" label-for="type"
                                    :state="getValidationState(validationContext)">
                                    <!-- <v-select v-model="recipeData.type" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                                        :options="['Breakfast', 'Chutney', 'Dal', 'Rice', 'Smoothie', 'Snacks', 'Soup', 'Veg Curry']"
                                        :clearable="false" input-id="type" /> -->

                                    <b-form-input id="type" v-model="recipeData.type" :state="getValidationState(
                                        validationContext
                                    )
                                        " :readonly="readonly" :class="errors.type ? 'is-invalid' : ''"
                                        :area-invalid="errors.type ? true : false" />

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
                            <validation-provider #default="validationContext" name="Image" rules="size:2048">
                                <b-form-group label="Image Upload" label-for="image"
                                    :state="getValidationState(validationContext)">
                                    <b-form-file v-model="image" id="image" accept=".jpg, .png, .jpeg"
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
                            <validation-provider #default="validationContext" name="PDF" rules="size:2048">
                                <b-form-group label="PDF Upload" label-for="pdf"
                                    :state="getValidationState(validationContext)">
                                    <b-form-file v-model="pdf" id="pdf" accept=".pdf"
                                        v-on:change="e => setFileObject(e, 'pdf')"
                                        :state="getValidationState(validationContext)"
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
                                    <b-form-textarea rows="3" id="instructions" v-model="recipeData.instructions"
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
                    </b-row>
                    <b-row style=" margin-top: 2%;">
                        <!-- Action Buttons -->
                        <b-button variant="primary" :block="$store.getters['app/currentBreakPoint'] === 'xs'" type="submit"
                            class="mr-2" v-permission="['create-diet-plan']">
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
                <b-col cols="12" md="2" class="d-flex align-items-center justify-content-start mb-1 mb-md-0">
                    <label>Show</label>
                    <v-select v-model="pagination.per_page" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                        :options="[10, 25, 50, 100, 1000]" :clearable="false"
                        class="per-page-selector d-inline-block mx-50" />
                    <label>entries</label>
                </b-col>
                <b-col cols="8" md="3" sm="6" class="justify-content-start mb-1 mb-md-0">
                    <b-form-group label="Name" label-for="name">
                        <b-form-input id="name" v-model="pagination.filter" placeholder="Search by name..."
                            @input="applyFilters" />
                    </b-form-group>
                </b-col>
                <!-- Search -->
                <b-col cols="12" md="6">
                </b-col>
            </b-row>

            <b-table style="min-height:250px" ref="refRolesTable" class="position-relative" :items="recipes" responsive
                :fields="columns" :totalRows="pagination.total" primaryKey="index" :sort-by.sync="sortBy" show-empty
                empty-text="No matching records found" :sort-desc.sync="isSortDirDesc">
                <!-- Column: Actions -->
                <template #cell(index)="data">
                    {{ (data.index + 1) + ((pagination.page - 1) * pagination.per_page) }}
                </template>
                <template #cell(image)="{ item }">
                    <a v-if="item.image" :href="'/view/file?path=' + item.image" target="_blank">
                        <b-img :src="'/view/file?path=' + item.image" fluid :width="75" :height="75" class='m1'></b-img>
                    </a>
                </template>
                <template #cell(pdf)="{ item }">
                    <a v-if="item.pdf" class="btn btn-success" @click="viewRecipeModal(item)" target="_blank">
                        View Recipe
                    </a>
                </template>
                <template #cell(tags)="{ item }">
                    <span v-for="(tag, index) in item.tags" :key="'tag' + tag.id">
                        <span class="badge badge-info">{{ tag.name }},</span>
                        <br v-if="index % 3 === 0">
                    </span>
                </template>
                <template #cell(actions)="data">
                    <b-dropdown variant="link" no-caret v-permission="['edit-recipes']">
                        <template #button-content>
                            <feather-icon icon="MoreVerticalIcon" size="16" class="align-middle text-body" />
                        </template>
                        <b-dropdown-item @click="editRecipe(data.item)" v-permission="['edit-recipes']">
                            <feather-icon icon="EditIcon" />
                            <span class="align-middle ml-50">Edit</span>
                        </b-dropdown-item>
                        <b-dropdown-item @click="viewAssignModal(data.item)" v-permission="['edit-recipes']">
                            <feather-icon icon="UsersIcon" />
                            <span class="align-middle ml-50">Assign</span>
                        </b-dropdown-item>
                        <b-dropdown-item @click="viewUserModal(data.item)" v-permission="['edit-recipes']">
                            <feather-icon icon="EyeIcon" />
                            <span class="align-middle ml-50">View Users</span>
                        </b-dropdown-item>
                        <b-dropdown-item @click="handleDeleteRecipe(data.item.id)" v-permission="['delete-recipes']">
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
            <b-modal v-permission="['edit-recipes']" id="viewpdf" ref="viewpdf" ok-only ok-title="Close" centered size="lg"
                title="View Recipe" no-close-on-backdrop>
                <div id="pdf" v-if="recipe">
                    <object class="pdf-object" width="100%" height="650" type="application/pdf"
                        :data="'/view/file?path=' + recipe.pdf + '#zoom=85&scrollbar=0&toolbar=0&navpanes=0'"
                        id="pdf_content">
                        <p>Insert your error message here, if the PDF cannot be displayed.</p>
                    </object>
                </div>
            </b-modal>
            <b-modal v-permission="['edit-recipes']" id="assign" ref="assign" ok-only ok-title="Assign" @show="getPartners"
                @hidden="resetData" @ok="handleOk" centered size="lg" title="Diet Plan Assignment" no-close-on-backdrop>
                <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
                    <form ref="form" @submit.prevent="handleSubmit(onAssign)">
                        <b-row>
                            <b-col cols="12" md="6" v-if="$store.getters.user.role_id == '1'">
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
                        </b-row>

                    </form>
                </validation-observer>
            </b-modal>
            <b-modal v-permission="['edit-recipes']" id="view-users" hide-footer centered size="lg" @show="getAssignedUsers"
                @hidden="resetUsers" title="Assigned Users" ref="view-users" no-close-on-backdrop>
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
                        <a @click="handleRemoveUser('recipe', data.item.recipe_user_id)" v-permission="['edit-recipes']"
                            style="cursor:pointer" title="remove">
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
    BImg,
} from "bootstrap-vue";
import { ValidationProvider, ValidationObserver } from "vee-validate";

import formValidation from "@core/comp-functions/forms/form-validation";
import { required, size } from "@validations";


import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

import vSelect from "vue-select";

import Ripple from 'vue-ripple-directive';


import { ref } from '@vue/composition-api'

import VoerroTagsInput from '@voerro/vue-tagsinput';

require('@voerro/vue-tagsinput/dist/style.css')
export default {
    name: 'Recipie',
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
        "tags-input": VoerroTagsInput,
    },
    props: ["id"],
    data() {

        return {
            pagination: {
                page: 1,
                per_page: 10,
                total: null,
                filter: null
            },
            required, size,
            type: 'Create Recipe',
            readonly: false,
            errors: [],
            list: [],
            totalRows: 0,
            recipe: null,
            userData: {
                clients: [],
                partners: [],
            },
            partners: [],
            clients: [],
            tags: []

        }
    },
    directives: {
        Ripple,
    },
    created() {
        if (this.$store.getters.user.role_id != 3 && !this.id) {
            this.columns.push({ key: "actions" });
        } else {
            this.columns.unshift({ key: "consultant_type", label: "Consultant Tyep", sortable: true });
            this.columns.unshift({ key: "consultant", label: "Consultant", sortable: true });
        }
        this.columns.unshift({ key: "index", label: "Sr. No.", sortable: true });
        this.getList();
        this.getClients();
    },
    setup() {
        const recipes = null;
        const loading = true;
        const sortBy = "1";
        const isSortDirDesc = false;
        const pdf = ref(null);
        const image = ref(null);

        const columns = [
            { key: "index", label: "Sr. No.", sortable: true },
            { key: "name", label: "Name", sortable: true },
            { key: "type", label: "Type", sortable: true },
            { key: "image", label: "Image", sortable: true },
            { key: "pdf", label: "PDF", sortable: true },
            { key: "tags", label: "Tags", sortable: false },
            { key: "instructions", label: "Instructions", sortable: true },

        ];
        const ucolumns = [
            { key: "index", label: "Sr. No.", sortable: true },
            { key: "name", label: "Name", sortable: true },
            { key: "type", label: "Type", sortable: true },
            { key: "email", label: "Email", sortable: true },
            { key: "phone", label: "Contact Number", sortable: true },
            { key: "actions", label: "Action", sortable: false },
        ];

        const recipeData = ref({
            name: "",
            type: "",
            instructions: "",
            image: null,
            pdf: null,
            tags: [],
        });

        const resetRecipeData = () => {
            recipeData.value = {
                name: "",
                type: "",
                instructions: "",
                image: null,
                pdf: null,
                tags: []
            }
        }
        const {
            refFormObserver,
            getValidationState,
            resetForm
        } = formValidation(resetRecipeData);
        return {
            recipes,
            pdf,
            image,
            ucolumns,

            loading,
            sortBy,
            isSortDirDesc,
            columns,
            recipeData,
            refFormObserver,
            getValidationState,
            resetForm,
            resetRecipeData,


        };
    },
    methods: {
        applyFilters() {
            this.getList();
        },
        async getList() {
            try {
                this.loading = true;
                this.pagination.client = this.id;
                const { data } = await axios.get("/recipe", {
                    params: this.pagination
                });
                this.recipes = data.recipes;
                this.pagination.per_page = data.per_page;
                this.pagination.filter = data.filter;
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
            this.agination.per_page = val;
            this.getList();
        },

        async onSubmit() {
            try {
                let data;
                let formData = new FormData;
                formData.append('name', this.recipeData.name);
                formData.append('type', this.recipeData.type);
                formData.append('instructions', this.recipeData.instructions);
                formData.append('tags', JSON.stringify(this.recipeData.tags));

                if (this.recipeData.id) {
                    if (this.recipeData.image) {
                        formData.append('image', this.recipeData.image);
                    }
                    if (this.recipeData.pdf) {
                        formData.append('pdf', this.recipeData.pdf);
                    }
                    data = await axios.post(
                        `/recipe/${this.recipeData.id}`, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });
                } else {
                    formData.append('image', this.recipeData.image);
                    formData.append('pdf', this.recipeData.pdf);
                    data = await axios.post("/recipe", formData, {
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
                this.errors = error.response.data.errors;
            }
        },

        editRecipe(data) {
            this.type = 'Update Recipe'
            this.recipe = data;
            // this.readonly = true;
            this.recipeData.id = data.id;
            this.recipeData.name = data.name;
            this.recipeData.type = data.type;
            this.recipeData.expiry_date = data.expiry_date;
            this.recipeData.tags = data.tags;
            this.recipeData.instructions = data.instructions;
        },

        async getAssignedUsers() {
            try {
                let id = this.recipe.id;
                const { data } = await axios.get(`recipe/${id}/users`, {
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
            if (this.userData.partners.length && $store.getters.user.role_id == 1) {
                try {
                    const { data } = await axios.get('/client/list', {
                        params: { partners: this.userData.partners, recipe: this.recipe.id }
                    });
                    this.clients = data.clients;
                } catch (err) {
                    console.log(err)
                }
            } else {
                try {
                    const { data } = await axios.get('/consultant/client/list', {
                    });
                    this.clients = data.clients;
                } catch (err) {
                    console.log(err)
                }
            }

        },

        viewAssignModal(recipe) {
            this.recipe = recipe;
            this.$refs['assign'].show();
        },
        viewRecipeModal(recipe) {
            this.recipe = recipe;
            this.$refs['viewpdf'].show();
        },
        viewUserModal(recipe) {
            this.recipe = recipe;
            this.$refs['view-users'].show();
        },

        async handleDeleteRecipe(id) {
            try {
                const data = await axios.delete(`/recipe/${id}`);
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
                supportedTypes = ['image/jpg', 'image/png', 'image/jpeg', 'image/gif']
            } else {
                supportedTypes = ['application/pdf']
            }

            let imageUrl = e.target.files[0];

            let type = imageUrl.type;

            if (!supportedTypes.includes(type)) {
                this[field] = null;
                this.recipeData[field] = null;
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
            this.recipeData[field] = imageUrl;
        },

        resetFormCard() {
            this.resetRecipeData();
            this.pdf = null;
            this.image = null;
            this.type = "Create Recipe"
            this.recipe = null;
            this.resetForm()
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
                clients: []
            }
        },

        async onAssign() {
            try {
                this.userData.recipe = this.recipe.id;
                const { data } = await axios.post('/recipe/assignment', this.userData);
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

#pdf object {}
</style>

<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";

#assignUser___BV_modal_body_ {
    min-height: 200px !important;
    max-height: 300px !important;
    overflow-y: auto;
}
</style>
