import formValidation from "@core/comp-functions/forms/form-validation";
import { required } from "@validations";



import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

export default {
    watch: {
        client_check: function(val) {
            if (val) {
                this.userData.users = [];
            } else {
                this.userData.clients = [];
            }
        }
    },

    data() {
        return {
            required,
            totalRows: 0,
            roles: [
                { label: 'Staff', value: 1 },
                { label: 'Alliance Partners', value: 2 }
            ],
            client_check: 0,

            users: [],
            clients: [],
            partners: [],
            list: []

        }
    },

    setup() {
        const userData = {
            role: null,
            users: [],
            clients: [],
            partners: [],
        }
        const resetUserData = function() {
            userData.value = {
                role: null,
                users: [],
                clients: [],
                partners: [],
            }
        }

        const loading = true;
        const sortBy = "1";
        const isSortDirDesc = false;
        const columns = [
            { key: "index", label: "Sr. No.", sortable: true },
            { key: "name", label: "User Name", sortable: true },
            { key: "assigned_date", label: "Assigned Date", sortable: true },
            { key: "expiry_date", label: "Expiry Date", sortable: true },
            { key: "email", label: "Email", sortable: true },
            { key: "phone", label: "Contact Number", sortable: true },
            { key: "actions", label: "Action", sortable: false },
        ];

        const {
            refFormObserver,
            getValidationState,
            resetForm
        } = formValidation(resetUserData);

        return {
            loading,
            sortBy,
            isSortDirDesc,
            columns,
            refFormObserver,
            getValidationState,
            resetForm,
            userData
        }
    },

    methods: {

        handleRoleChange(val) {
            if (val === 1) {
                this.userData.clients = [];
                this.userData.partners = [];
                this.getUsers()
            } else {
                this.userData.users = [];
                this.getPartners()
            }
        },

        handlePartnerChange() {
            this.getClients();
        },

        handleOk(bvModalEvt) {
            bvModalEvt.preventDefault()

            this.$refs.refFormObserver.handleSubmit(this.onAssign);
        },

        resetUsers() {
            this.list = [];
            this.pagination = {
                page: 1,
                per_page: 10,
                total: null
            };
        },
        resetData() {

            this.client_check = 0;
            this.userData = {
                role: null,
                users: [],
                clients: [],
                partners: [],
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
        }
    }
}