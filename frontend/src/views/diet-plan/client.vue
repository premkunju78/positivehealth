<template>
    <list :id="id" />
</template>
<script>

import List from './list.vue';

export default {
    components:{List},
    props: {
      id: {
        type: [Number,String],
        defaul: null
      }
    },
}
</script>