<template>
    <b-card style="height:238px !important">
        <div class="media m-2">
            <div class="media-body">
            <blockquote style=" text-indent: -0.45em; font-weight:400">
                {{affirmation}}
            </blockquote>
            </div>
        </div>
    </b-card>
</template>
<script>

import { BCard } from 'bootstrap-vue';

import { mapGetters } from "vuex";

export default {
    components: {
        BCard
    },
    data() {
        return {
            affirmation : ''
        }
    },
    computed: {
        ...mapGetters(["user"])
    },
    async created(){
        const {data} = await axios.get(`affirmation/today`);
        this.affirmation = data.affirmation;
    },
}
</script>