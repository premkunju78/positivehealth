<template>
<b-card>
    <!-- BODY -->
    <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
        <!-- Form -->
        <b-form class="p-2" @submit.prevent="handleSubmit(onSubmit)" @reset.prevent="resetForm">
            <b-row>
                <b-col col="12" md="4" v-for="(testList,category) in tests" :key="category">
                    <div>
                        <app-collapse class="mt-1" accordion type="border">
                            <app-collapse-item :title="category.replace(' ','-')">
                              <b-card border-variant="primary">
                                    <b-form-group v-slot="{ ariaDescribedby }">
                                        <b-form-checkbox-group v-model="investigationData.test[category]" :options="tests[category]" :aria-describedby="ariaDescribedby" stacked></b-form-checkbox-group>
                                    </b-form-group>
                                </b-card>
                            </app-collapse-item>

                        </app-collapse>

                    </div>

                </b-col>
            </b-row>
            <div class="d-flex " style="margin-top:5%">
                <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" class="mr-2" type="submit">
                    Save Investigation
                </b-button>
                <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" type="button" variant="outline-secondary" @click="$router.back()">
                    Cancel
                </b-button>
            </div>
        </b-form>
    </validation-observer>
</b-card>
</template>

<script>
import {
    BCollapse,
    BCard,
    BCardText,
    BForm,
    BFormGroup,
    BFormInput,
    BFormInvalidFeedback,
    BButton,
    BRow,
    BCol,
    BFormCheckboxGroup,
    BOverlay
} from 'bootstrap-vue'
import {
    ValidationProvider,
    ValidationObserver
} from 'vee-validate'
import {
    required
} from '@validations'
import {
    ref
} from '@vue/composition-api'
import Ripple from 'vue-ripple-directive'
import formValidation from '@core/comp-functions/forms/form-validation'
import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'
import {
    VBToggle
} from 'bootstrap-vue';
import AppCollapse from '@core/components/app-collapse/AppCollapse.vue'
import AppCollapseItem from '@core/components/app-collapse/AppCollapseItem.vue'

export default {
    components: {
        BCard,
        BCardText,
        BForm,
        BFormGroup,
        BFormInput,
        BFormInvalidFeedback,
        BButton,
        ValidationProvider,
        ValidationObserver,
        BRow,
        BCol,
        BFormCheckboxGroup,
        BOverlay,
        BCollapse,
        AppCollapse,
        AppCollapseItem,

    },
    directives: {
        Ripple,
        'b-toggle': VBToggle
    },
    props: {
        id: {
            type: [Number, String],
            required: true
        }
    },
    created() {
        this.getTest();
    },
    data() {
        return {
            tests: [],
            required
        }
    },
    setup(props, {
        emit
    }) {
        const investigation = {
            test: {}
        }
        const investigationData = ref(JSON.parse(JSON.stringify(investigation)))

        const resetInvestigationData = () => {
            investigation.value = JSON.parse(JSON.stringify(investigation))
        }


        const {
            refFormObserver,
            getValidationState,
            resetForm,
        } = formValidation(resetInvestigationData)

        return {
            investigationData,
            refFormObserver,
            getValidationState,
            resetForm,
        }
    },

    methods: {
        async getTest() {
            const {
                data
            } = await axios.get('investigation/test/list');
            this.tests = data.test;
        },
        async onSubmit() {
            let data;
            try {
                this.investigationData.client = this.id;
                if (this.investigationData.id) {
                    data = await axios.put(`investigation/${this.investigationData.id}`, this.investigationData)
                } else {
                    data = await axios.post(`investigation`, this.investigationData)
                }
                this.$toast({
                    component: ToastificationContent,
                    props: {
                        title: data.data.message,
                        icon: 'EditIcon',
                        variant: data.data.success ? 'success' : 'info',
                    },
                });
                this.$router.back();
            } catch (error) {
                console.log(error)
            }
        },
    }
}
</script>
