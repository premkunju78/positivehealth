<template>
  <b-card>
    <b-row style="margin-top:2%;">
      <b-col cols="2"></b-col>
      <b-col cols="8">
        <span v-if="errors && errors.content" class="text-danger">
          {{ errors.content }}
        </span>
        <div id="fb-view">

        </div>
      </b-col>

    </b-row>
    <b-row class="justify-content-center">
      <b-col cols="3" class="mt-3">
        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" style="float:right"
          :disabled="true">
          Submit
        </b-button>
        <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" type="reset" class="mr-1" variant="outline-secondary"
          style="float:right" :disabled="true">
          Reset
        </b-button>
      </b-col>
    </b-row>
    <b-row>
      <b-col>
        <router-link :to="{ name: 'questionnaires' }">
          <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" type="reset" class="mr-1" variant="outline-secondary"
            style="float:right">
            Go Back
          </b-button>
        </router-link>
      </b-col>
    </b-row>
  </b-card>
</template>
<style >
ul.stage-wrap {
  height: 458px !important;
  overflow-y: auto !important;
}
</style>
<script>
import { BCard, BCardText, BRow, BCol, BButton } from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import RateYo from 'rateyo';
import 'rateyo/lib/cjs/rateyo.min.css';
import $ from "jquery";

window.jQuery = $;
window.$ = $;
require("jquery-ui-sortable");

require('formBuilder/dist/form-render.min.js')


export default {
  name: "PreviewQuestionnaire",
  components: {
    BCard,
    BCardText,
    BRow,
    BCol,
    BButton
  },
  props: ['id'],
  data() {
    return {
      formRenderer: null,
      formData: null,
      errors: []
    }
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
        const { data } = await axios.get(`/questionnaire/${this.id}`);
        this.formData = data.content;
        let fields = [{
          label: 'Star Rating',
          attrs: {
            type: 'starRating'
          },
          icon: '🌟'
        }
        ];
        let templates = {
          starRating: function (fieldData) {
            return {
              field: '<span id="' + fieldData.name + '">',
              onRender: function () {
                RateYo(document.getElementById(fieldData.name), { numStars: 10 })
              }
            };
          }
        };
        this.formRenderer = $('#fb-view').formRender({
          fields,
          templates,
          formData: data.content,
          render: true
        });

      } catch (error) {
        console.log(error)
        this.$toast({
          component: ToastificationContent,
          props: {
            title: 'something went wrong contact admin',
            icon: "BellIcon",
            variant: "danger"
          }
        });
      }
    }
  }

}
</script>
