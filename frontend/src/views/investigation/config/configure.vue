<template>
<b-card-code title="Repeating Forms">
    <div>

        <b-form ref="form" :style="{height: trHeight}" class="repeater-form" @submit.prevent="submit">
            <!-- Test Category Name -->

            <b-row class="mb-50">
                <b-col cols="6">
                    <b-form-group label="Test Category" label-for="test-category">
                        <b-form-input id="test-category" type="text" placeholder="Investigation Heading" v-model="test_category" />
                    </b-form-group>
                </b-col>
            </b-row>

            <!-- Row Loop -->
            <b-row v-for="(item, index) in items" :id="item.id" :key="item.id" ref="row">

                <!-- Item Name -->
                <b-col md="4">
                    <b-form-group label="Test Item" label-for="test-item">
                        <b-form-input id="test-item" type="text" placeholder="Investigation item" v-model="item.test_items" />
                    </b-form-group>
                </b-col>

                <b-col lg="2" md="3" class="mb-50">
                    <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" class="mt-0 mt-md-2" @click="repeateAgain">
                        <feather-icon icon="PlusIcon" class="mr-25" />
                        <span>Add New</span>
                    </b-button>
                </b-col>

                <!-- Remove Button -->
                <b-col lg="2" md="3" class="mb-50">
                    <b-button v-ripple.400="'rgba(234, 84, 85, 0.15)'" variant="outline-danger" class="mt-0 mt-md-2" @click="removeItem(index)">
                        <feather-icon icon="XIcon" class="mr-25" />
                        <span>Delete</span>
                    </b-button>
                </b-col>
                <b-col cols="12">
                    <hr>
                </b-col>
            </b-row>
            <b-button type="submit" variant="primary">Submit</b-button>

        </b-form>
    </div>
    <template #code>
        {{ codeBasic }}
    </template>
</b-card-code>
</template>

<script>
import BCardCode from '@core/components/b-card-code'
import {
    BForm,
    BFormGroup,
    BFormInput,
    BRow,
    BCol,
    BButton,
} from 'bootstrap-vue'
import {
    heightTransition
} from '@core/mixins/ui/transition'
import Ripple from 'vue-ripple-directive'
import {
    codeBasic
} from './code'
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

export default {
    components: {
        BCardCode,
        BForm,
        BRow,
        BCol,
        BButton,
        BFormGroup,
        BFormInput,
        ToastificationContent,
    },
    directives: {
        Ripple,
    },
    mixins: [heightTransition],
    data() {
        return {
            test_category: '',
            items: [{
                id: 1,
                test_items: '',
                prevHeight: 0,
            }],
            nextTodoId: 2,
            codeBasic,
        }
    },
    mounted() {
        this.initTrHeight()
    },
    created() {
        window.addEventListener('resize', this.initTrHeight)
    },
    destroyed() {
        window.removeEventListener('resize', this.initTrHeight)
    },
    methods: {
        repeateAgain() {
            this.items.push({
                id: this.nextTodoId += this.nextTodoId,
            })

            this.$nextTick(() => {
                this.trAddHeight(this.$refs.row[0].offsetHeight)
            })

        },
        async submit() {
            try {
                let data = await axios.post("/investigation/storeconfig", {
                    "test_categories": this.test_category,
                    "test_items": this.items
                });
                this.$toast({
                    component: ToastificationContent,
                    props: {
                        title: data.data.message,
                        icon: "CheckIcon",
                        variant: data.data.success ? "success" : "info"
                    }
                });
            } catch (error) {
                console.log(error);
                if (error.response) {
                    this.errors = error.response.data.errors;
                }
            }
        },
        removeItem(index) {
            this.items.splice(index, 1)
            this.trTrimHeight(this.$refs.row[0].offsetHeight)
        },
        initTrHeight() {
            this.trSetHeight(null)
            this.$nextTick(() => {
                this.trSetHeight(this.$refs.form.scrollHeight)
            })
        },
    },
}
</script>

<style lang="scss" scoped>
.repeater-form {
    overflow: hidden;
    transition: 0.35s height;
}
</style>
