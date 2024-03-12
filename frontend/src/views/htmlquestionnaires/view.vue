<template>
  <b-card>
    <b-row style="margin-top: 2%">
      <b-col cols="12">
        <span v-if="errors && errors.content" class="text-danger">
          {{ errors.content }}
        </span>
        <iframe :src="'/view/htmlquestionnaireresult/' + id + '/' + $store.state.auth.user.id" frameborder="0"
          style="width: 100%; height: 600px"></iframe>
      </b-col>
    </b-row>
    <b-row>
      <b-col>
        <router-link :to="{ name: 'htmlquestionnaires' }">
          <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" type="reset" class="mr-1" variant="outline-secondary"
            style="float: right">
            Go Back
          </b-button>
        </router-link>
      </b-col>
    </b-row>
  </b-card>
</template>
<style>
ul.stage-wrap {
  height: 458px !important;
  overflow-y: auto !important;
}
</style>
<script>
import { BCard, BCardText, BRow, BCol, BButton } from "bootstrap-vue";
import Ripple from "vue-ripple-directive";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

import $ from "jquery";

window.jQuery = $;
window.$ = $;
require("jquery-ui-sortable");

require("formBuilder/dist/form-render.min.js");

export default {
  name: "PreviewQuestionnaire",
  components: {
    BCard,
    BCardText,
    BRow,
    BCol,
    BButton,
  },
  props: ["id"],
  data() {
    return {
      formRenderer: null,
      formData: null,
      errors: [],
    };
  },
  directives: {
    Ripple,
  },
  created() {
    this.getFormData();
  },
  methods: {
    async getFormData() {
      try {
        const { data } = await axios.get(`/htmlquestionnaire/${this.id}`);
        this.formData = data.content;
        this.formRenderer = $("#fb-view").formRender({
          formData: data.content,
          render: true,
        });
      } catch (error) {
        console.log(error);
        this.$toast({
          component: ToastificationContent,
          props: {
            title: "something went wrong contact admin",
            icon: "BellIcon",
            variant: "danger",
          },
        });
      }
    },
  },
};
</script>
