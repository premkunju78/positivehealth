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
    <b-row>
      <b-col>
        <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" type="submit" variant="primary" style="float:right"
          v-permission="['edit-questionnaire']" @click="onSubmit">
          Update
        </b-button>
        <router-link :to="{ name: 'questionnaires' }">
          <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" type="reset" class="mr-1" variant="outline-secondary"
            style="float:right">
            cancel
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
import { BCard, BCardText, BRow, BCol, BForm, BButton } from 'bootstrap-vue'
import Ripple from 'vue-ripple-directive'


import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

import $ from "jquery";
import RateYo from 'rateyo';
import 'rateyo/lib/cjs/rateyo.min.css';
window.jQuery = $;
window.$ = $;
require("jquery-ui-sortable");

require('formBuilder/dist/form-render.min.js')


export default {
  name: "FillQuestionnaire",
  components: {
    BCard,
    BCardText,
    BRow,
    BCol,
    BForm,
    BButton,
  },
  directives: {
    Ripple,
  },
  props: ['id'],
  data() {
    return {
      formRenderer: null,
      formData: null,
      questionnaire: {},
      errors: {},
    }
  },
  created() {
    this.getFormData();
  },
  methods: {
    async getFormData() {
      try {
        const { data } = await axios.get(`/questionnaire/user/${this.id}`);
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
    },
    async onSubmit() {
      this.questionnaire.result = this.formRenderer.userData
      let valid = true;
      this.questionnaire.result.map(res => {
        if (res.required) {
          if (!res.userData.length) {
            valid = false;
          }
        }
      })
      if (valid) {
        this.questionnaire.id = this.id;
        if (this.questionnaire.result.length) {
          try {
            const { data } = await axios.put(`/questionnaire/result/${this.id}`, this.questionnaire);


            this.$toast({
              component: ToastificationContent,
              props: {
                title: data.message,
                icon: "BellIcon",
                variant: data.success ? "success" : "danger"
              }
            });

            this.$router.replace({ name: 'questionnaires' })

          } catch (error) {
            this.errors = error.response.data.errors;
          }
        } else {
          this.errors.content = "Please fill form first";
        }
      } else {
        this.$toast({
          component: ToastificationContent,
          props: {
            title: "Please fill all the required fields first",
            icon: "BellIcon",
            variant: "danger"
          }
        });
      }



    },
    resetQuestionnaire() {
      this.questionnaire.result = this.formData;
    },
  }

}
</script>
