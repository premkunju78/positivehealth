<template>
    <div style="min-height:300px">
        <b-overlay :show="$store.state.app.shallShowOverlay" no-wrap opacity="0.5" blur="1px"></b-overlay>
        <b-card :title="type">
            <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
                <form ref="form" @submit.prevent="handleSubmit(onSubmit)">
                    <b-row>
                        <b-col cols="12" md="6">
                            <validation-provider #default="validationContext" name="Title" rules="required">
                                <b-form-group label="Title" label-for="title">
                                    <b-form-input id="title" :state="
                                        getValidationState(
                                            validationContext
                                        )
                                    " v-model="reminderData.title" :readonly="readonly"
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
                            <validation-provider #default="validationContext" name="Date Time" rules="required">
                                <b-form-group label="Date Time" label-for="date">
                                    <flat-pickr v-model="reminderData.date" class="form-control" id="date"
                                        :config="config" />
                                    <b-form-invalid-feedback v-if="errors.date">
                                        {{ errors.date[0] }}
                                    </b-form-invalid-feedback>
                                    <b-form-invalid-feedback :state="getValidationState(validationContext)">
                                        {{ validationContext.errors[0] }}
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>
                        </b-col>
                        <b-col cols="12" md="6">
                            <validation-provider #default="validationContext" name="Notes">
                                <b-form-group label="Notes" label-for="notes">
                                    <b-form-textarea rows="3" id="notes" v-model="reminderData.notes"
                                        :class="errors.notes ? 'is-invalid' : ''"
                                        :area-invalid="errors.notes ? true : false"></b-form-textarea>
                                    <b-form-invalid-feedback v-if="errors.notes">
                                        {{ errors.notes[0] }}
                                    </b-form-invalid-feedback>
                                    <b-form-invalid-feedback>
                                        {{ validationContext.errors[0] }}
                                    </b-form-invalid-feedback>
                                </b-form-group>
                            </validation-provider>
                        </b-col>

                    </b-row>
                    <b-row style=" margin-top: 2%;">
                        <!-- Action Buttons -->
                        <b-button variant="primary" :block="
                        $store.getters['app/currentBreakPoint'] === 'xs'" type="submit" class="mr-2">
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
            <b-table style="min-height:250px" ref="refRolesTable" class="position-relative" :items="reminders"
                responsive :fields="columns" :totalRows="pagination.total" primaryKey="index" :sort-by.sync="sortBy"
                show-empty empty-text="No matching records found" :sort-desc.sync="isSortDirDesc">
                <!-- Column: Actions -->
                <template #cell(index)="data">
                    {{ data.index + 1 }}
                </template>
                <template #cell(date)="{ item }">
                    {{ moment(item.date).format('DD-MM-YYYY, hh:mm A') }}
                </template>
                <template #cell(status)="data">
                    <b-form-group label="" label-cols="10" class="status_Text"
                        v-if="data.item.user_id == user_id && data.item.status === 'OPEN'">
                        <b-dropdown variant="link" no-caret :right="$store.state.appConfig.isRTL">
                            <template #button-content>
                                {{ data.item.status }}
                            </template>
                            <b-dropdown-item @click="updatestatus(data.item.id, 'OPEN')">
                                <span class="align-middle ml-50">OPEN</span>
                            </b-dropdown-item>
                            <b-dropdown-item @click="updatestatus(data.item.id, 'CLOSED')">
                                <span class="align-middle ml-50">CLOSED</span>
                            </b-dropdown-item>
                        </b-dropdown>
                    </b-form-group>
                    <span v-else> {{ data.item.status }} </span>
                </template>
                <template #cell(actions)="data">
                    <b-dropdown variant="link" no-caret v-permission="['edit-reminders']">
                        <template #button-content>
                            <feather-icon icon="MoreVerticalIcon" size="16" class="align-middle text-body" />
                        </template>
                        <b-dropdown-item @click="editReminder(data.item)" v-if="data.item.status === 'OPEN'">
                            <feather-icon icon="EditIcon" />
                            <span class="align-middle ml-50">Edit</span>
                        </b-dropdown-item>
                        <b-dropdown-item @click="handleDeleteReminder(data.item.id)">
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
    BFormInvalidFeedback,
    BFormInput,
    BFormGroup,
    BFormFile,
    BFormTextarea,
    BOverlay,
    BImg
} from "bootstrap-vue";
import { ValidationProvider, ValidationObserver } from "vee-validate";

import formValidation from "@core/comp-functions/forms/form-validation";
import { required } from "@validations";


import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

import vSelect from "vue-select";

import Ripple from 'vue-ripple-directive';

import 'flatpickr/dist/flatpickr.css';
import flatPickr from 'vue-flatpickr-component'


import { ref } from '@vue/composition-api'
import moment from 'moment'

export default {
    name: 'Reminder',
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
        BFormInvalidFeedback,
        BFormInput,
        BFormGroup,
        BFormFile,
        BFormTextarea,
        BOverlay,
        ValidationProvider,
        ValidationObserver,
        vSelect,
        BImg,
        flatPickr,
    },
    data() {

        return {
            pagination: {
                page: 1,
                per_page: 10,
                total: null
            },
            required,
            type: 'New Reminder',
            readonly: false,
            errors: [],
            reminders: [],
            totalRows: 0,
            config: {
                enableTime: true,
                dateFormat: 'd-m-Y h:i K'
            },
            moment: moment


        }
    },
    directives: {
        Ripple,
    },
    setup() {
        const reminder = null;
        const loading = true;
        const sortBy = "1";
        const isSortDirDesc = false;

        const columns = [
            { key: "index", label: "Sr. No.", sortable: true },
            { key: "title", label: "Title", sortable: true },
            { key: "date", label: "Date Time", sortable: true },
            { key: "notes", label: "Notes", sortable: true },
            { key: "status", label: "Status", sortable: true },
            { key: "actions", label: "Action", sortable: false },
        ];

        const reminderData = ref({
            title: null,
            notes: "",
            date: null
        });

        const resetReminderData = () => {
            reminderData.value = {
                title: null,
                notes: "",
                date: null
            }
        }
        const {
            refFormObserver,
            getValidationState,
            resetForm
        } = formValidation(resetReminderData);

        return {
            reminder,

            loading,
            sortBy,
            isSortDirDesc,
            columns,
            reminderData,
            refFormObserver,
            getValidationState,
            resetForm,
            resetReminderData
        };
    },
    created() {
        this.getList();
    },
    computed: {
        user_id: function () {
            return this.$store.getters.user.id;
        },
        role_id: function () {
            return this.$store.getters.user.role_id;
        },
    },
    methods: {

        async getList() {
            try {
                this.loading = true;
                const { data } = await axios.get("/reminder", {
                    params: this.pagination
                });
                this.reminders = data.reminders;
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

        async onSubmit() {
            try {
                let data;
                if (this.reminderData.id) {
                    data = await axios.put(`/reminder/${this.reminderData.id}`, this.reminderData);
                } else {
                    data = await axios.post("/reminder", this.reminderData);
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

        editReminder(reminder) {
            this.loading = true;
            this.type = 'Update Reminder'
            this.reminder = reminder;
            // this.readonly = true;
            this.reminderData.id = reminder.id;
            this.reminderData.notes = reminder.notes;
            this.reminderData.title = reminder.title;
            this.reminderData.date = new Date(reminder.date);
            this.loading = false;
        },


        async handleDeleteReminder(id) {
            try {
                const data = await axios.delete(`/reminder/${id}`);
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
            this.resetReminderData();
            this.reminder = null;
            this.errors = [];
            this.type = "New Reminder";
            this.resetForm();

        },
        async updatestatus(id, status) {
            try {
                const { data } = await axios.post(
                    "/reminder/updatestatus",
                    { id: id, status: status },
                    {}
                );
                this.$toast({
                    component: ToastificationContent,
                    props: {
                        title: data.message,
                        icon: "EditIcon",
                        variant: data.success ? "success" : "info",
                    },
                });
                this.getList();
            } catch (error) {
                if (error.response) {
                    this.errors = error.response.data.errors;
                }
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

.form-control[readonly] {
    background-color: #fff;
}
</style>
