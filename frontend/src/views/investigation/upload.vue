<template>
  <b-card>
    <b-row class="d-flex justify-content-center">
        <b-col cols="12">
            <div class="editor-container">
                <dropzone id="myVueDropzone" :url="'/api/investigation/'+id+'/upload'" 
                :headers="{Authorization:'Bearer ' + $store.getters.token}"
                @dropzone-removedFile="dropzoneR" @dropzone-success="dropzoneS" 
                />
            </div>
        </b-col>
    </b-row>
  </b-card>
</template>

<script>
import Dropzone from '@core/components/dropzone';
import {BCard,BCardText,BRow,BCol} from 'bootstrap-vue';


import ToastificationContent from '@core/components/toastification/ToastificationContent.vue'

export default {
  name: 'UploadReports',
  components: {
    Dropzone,
    BCard,
    BCardText,
    BRow,
    BCol
  },
  props:{
    id: {
        type: Number,
        required: true,
    } 
  },
  methods: {
    csrf() {
      return document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute('content');
    },
    dropzoneS(file) {
       this.$toast({
            component: ToastificationContent,
            props: {
                title: "upload success",
                icon: "BellIcon",
                variant: "success"
            }
        });
      this.$emit('change');
    },
    dropzoneR(file) {
      axios.put(`/investigation/${this.id}/upload`, { file: file.name }).then(() => {
        this.$toast({
            component: ToastificationContent,
            props: {
                title: "delete success",
                icon: "BellIcon",
                variant: "success"
            }
        });
        this.$emit('change');
      });
    },
  },
};
</script>