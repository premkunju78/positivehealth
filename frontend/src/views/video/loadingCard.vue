<template>
    <div class="card" style="width: 22rem;">
    <div class="embed-responsive embed-responsive-16by9">
        <div class="d-flex justify-content-center mb-1">
            <b-spinner label="Loading..." />
        </div>
    </div>
    <div class="card-body">
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
import {BSpinner} from 'bootstrap-vue';

export default {
    name: 'LoadingCard',
    components: {
        BSpinner
    }
}
</script>
