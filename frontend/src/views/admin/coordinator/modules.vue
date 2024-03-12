<template>
  <div>
    <b-overlay :show="$store.state.app.shallShowOverlay" no-wrap opacity="0.5"  blur="1px"></b-overlay>

  <b-card-code title="Types">

    <b-card-text class="mb-0">
      Here is the list of Client Modules. You can find user related modules below.
    </b-card-text>
    <!-- types -->
    <app-collapse
      accordion
      type="margin"
    >

      <!-- <app-collapse-item title="Video">
        <b-button
          v-ripple.400="'rgba(255, 255, 255, 0.15)'"
          variant="success"
          :to="{ name: 'assigned-videos'}"
          >
          Assigned Videos
        </b-button>
      </app-collapse-item> -->

      <app-collapse-item title="Appointments" v-permission="['view-appointments']">
        <b-button
          v-ripple.400="'rgba(255, 255, 255, 0.15)'"
          variant="success"
          :to="{ name: 'clientSchedules', props: {userId: this.id}}"
          >
          Appointments
        </b-button>
      </app-collapse-item>

       <app-collapse-item title="Prescription" v-permission="['view-prescription']">
         <b-button
          v-ripple.400="'rgba(255, 255, 255, 0.15)'"
          variant="success"
          :to="{ name: 'prescriptions', props: {id: this.id}}"
          >
          Prescriptions
        </b-button>
      </app-collapse-item>
      
      <app-collapse-item title="Investigation" v-permission="['view-investigation']">
         <b-button
          v-ripple.400="'rgba(255, 255, 255, 0.15)'"
          variant="success"
          :to="{ name: 'investigations', props: {id: this.id}}"
          >
          Investigations
        </b-button>
      </app-collapse-item>

      <app-collapse-item title="Case Sheets" v-permission="['view-case-sheet']">
        <b-button
          v-ripple.400="'rgba(255, 255, 255, 0.15)'"
          variant="success"
          :to="{ name: 'clientCaseSheets'}"
          >
          Case Sheets
        </b-button>
      </app-collapse-item>
       <app-collapse-item title="Questionnaire" v-permission="['view-questionnaire']">
        <b-button
          v-ripple.400="'rgba(255, 255, 255, 0.15)'"
          variant="success"
          :to="{ name: 'clientQuestionnaires', props: {id: this.id}}"
          >
          Questionnaire
        </b-button>
      </app-collapse-item>
      <app-collapse-item title="Diet Plans" v-permission="['view-diet-plan']">
        <b-button
          v-ripple.400="'rgba(255, 255, 255, 0.15)'"
          variant="success"
          :to="{ name: 'diet-plan-client', props: {id: this.id} }"
          >
          Diet Plans 
        </b-button>
      </app-collapse-item>
      <app-collapse-item title="Recipe" v-permission="['view-recipes']">
        <b-button
          v-ripple.400="'rgba(255, 255, 255, 0.15)'"
          variant="success"
          :to="{ name: 'recipe-client', props: {id: this.id} }"
          >
          Recipe
        </b-button>
      </app-collapse-item>
     
       <app-collapse-item title="Cross Referral" v-if="upgraded">
        <b-button
          v-ripple.400="'rgba(255, 255, 255, 0.15)'"
          variant="success"
          :to="{ name: 'crs', props: {id: this.id}}"
          >
          Cross Referral
        </b-button>
      </app-collapse-item>
    </app-collapse>
  </b-card-code>
</div>
</template>

<script>
import BCardCode from '@core/components/b-card-code'
import AppCollapse from '@core/components/app-collapse/AppCollapse.vue'
import AppCollapseItem from '@core/components/app-collapse/AppCollapseItem.vue'
import Ripple from 'vue-ripple-directive'

import {
  BFormRadioGroup, BFormRadio, BFormGroup, BCardText,BOverlay,BButton
} from 'bootstrap-vue'

export default {
  components: {
    BCardCode,
    AppCollapse,
    AppCollapseItem,
    BFormRadioGroup,
    BFormRadio,
    BCardText,
    BFormGroup,
    BOverlay,
    BButton,
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      show: false,
    }
  },
  props: ['id'],
  computed: {
    upgraded: function(){
      return this.$store.state.auth.user.is_upgraded;
    }
  },
  mounted(){

  }
}
</script>
