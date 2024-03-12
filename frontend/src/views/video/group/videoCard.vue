<template>
    <div class="card" style="width:100%">
        <div class="embed-responsive embed-responsive-16by9" v-html="video.embed.html"></div>
        <div class="card-body">
            <h5 class="card-title">{{video.name}}</h5>
            <p class="card-text">
                {{video.description}}
            </p>
            <!-- <p class="card-text">
                <small class="text-muted"> Video-id :- {{id}}</small>
            </p> -->
          
            <b-button
                v-ripple.400="'rgba(113, 102, 240, 0.15)'"
                variant="danger"
                @click="handleRemoveVideo(id)"
                v-permission="['edit-group']"
            >
              Remove
            </b-button>
        </div>
    </div>
</template>
<style lang="scss" scoped>
@media only screen and (min-width: 768px) {
  iframe{
    height: 13em;
  }
  .card{
    width: 50%;
  }
}
</style>
<script>
import {BModal,BButton,BCardText,VBModal, BFormGroup,BFormInput, BRow, BCol} from 'bootstrap-vue';
import Ripple from 'vue-ripple-directive';
import {removeVideo} from './videos';

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

export default {
    name: 'VideoCard',
    props: {
        video: {
            type: Object,
            defualt: () => {},
        },
        group : {
            type : [Number, String],
            required: true
        },
        creator: {
            type : [Number, String],
            required: true
        }
    },
    computed: {
        id : function () {
            return this.video.uri.split('/')[2];
        }
    },
    directives: {
        'b-modal': VBModal,
        Ripple,
    },
    components: {
        BModal,
        BButton,
        BCardText,
        BFormGroup,
        BFormInput,
        BRow,
        BCol
    },
    data() {
        return {
            groups : [
                { label : 'Staff', value: 1},
                { label : 'Alliance Partners', value: 2}
            ],
            role: null
        }
    },

    methods: {
        removeVideo,
        async handleRemoveVideo( id ){
            try {
                const { data } = await axios.delete(`/groups/${this.group}/videos/${this.id}`);
                this.$toast({
                    component: ToastificationContent,
                    props: {
                        title: data.message,
                        icon: "BellIcon",
                        variant: data.success ? "success" : "danger"
                    }
                });
                this.$emit('remove');
            } catch (err) {
                console.log(err);
            }
        }
    }
}
</script>
