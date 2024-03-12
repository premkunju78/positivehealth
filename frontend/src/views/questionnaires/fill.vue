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
          Submit
        </b-button>
        <b-button v-ripple.400="'rgba(186, 191, 199, 0.15)'" type="reset" class="mr-1" variant="outline-secondary"
          style="float:right" @click="resetQuestionnaire">
          Reset
        </b-button>
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

window.jQuery = $;
window.$ = $;
require("jquery-ui-sortable");

require('formBuilder/dist/form-render.min.js')
import RateYo from 'rateyo';
import 'rateyo/lib/cjs/rateyo.min.css';


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
      errors: {
        content: ''
      }
    }
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
                RateYo(document.getElementById(fieldData.name), {
                  numStars: 10,
                  onChange: function (rating, rateYoInstance) {
                    console.log(rating);
                  }
                })
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
      this.questionnaire.client_id = this.$route.query.clientid;
      this.questionnaire.new = this.$route.query.new;
      let valid = true;
      this.questionnaire.result.map(res => {

        if (res.required) {
          console.log(res)
          if (!res.userData.length || res.userData[0] == '') {
            valid = false;
          }
        }
      })
      if (valid) {
        this.questionnaire.id = this.id;
        if (this.questionnaire.result.length) {
          try {
            const { data } = await axios.post("/questionnaire/result", this.questionnaire);
            console.log(data)
            this.errors = {};

            this.$toast({
              component: ToastificationContent,
              props: {
                title: data.message,
                icon: "BellIcon",
                variant: data.success ? "success" : "danger"
              }
            });
            if (this.$store.getters.user.role.is_consultant == 1) {
              this.$router.replace(`/assessments/${this.questionnaire.client_id}`);
            } else {
              this.$router.replace({ name: 'questionnaires' })
            }
          } catch (error) {
            this.errors = error.response.data.errors;
          }
        } else {
          this.errors.content = "Please fill form first";
        }
      } else {
        this.errors.content = "Please fill all the required fields first";
      }
    },
    resetQuestionnaire() {
      this.formRenderer = $('#fb-view').formRender({
        formData: this.formData,
        render: true
      });
      this.questionnaire.result = this.formData;
    },
  }

}
</script>
