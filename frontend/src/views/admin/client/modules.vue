<template>
  <div>
    <b-overlay :show="$store.state.app.shallShowOverlay" no-wrap opacity="0.5" blur="1px"></b-overlay>

    <b-card-code>
      <!-- 
    <b-card-text class="mb-0">
      Here is the list of Client Modules. You can find user related modules below.
    </b-card-text> -->
      <!-- types -->
      <app-collapse accordion type="margin">
        <!-- <app-collapse-item title="Video">
        <b-button
          v-ripple.400="'rgba(255, 255, 255, 0.15)'"
          variant="success"
          :to="{ name: 'assigned-videos'}"
          >
          Assigned Videos
        </b-button>
      </app-collapse-item> -->

        <app-collapse-item title="Programs" v-if="paidprograms.length">
          <table v-if="programs.length" class="table table-striped">
            <thead>
              <tr>
                <th>Program Name</th>
                <th>Plan</th>
                <th>Date of Joining</th>
                <th>Healthcoach</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="userprogram in programs">
                <td>
                  {{ userprogram.program_name }}
                </td>
                <td>
                  {{ userprogram.plan }}
                </td>
                <td>
                  {{ (userprogram.joined_date) ? moment(userprogram.joined_date).format('DD-MM-YYYY') : '-' }}
                </td>
                <td>
                  {{ userprogram.hc }}
                </td>
                <td>
                  <b-form-group label="" label-cols="10" class="status_Text" v-if="$store.state.auth.user.role_id == 10 ||
                    $store.state.auth.user.is_upgraded == 11
                    ">
                    <b-dropdown variant="link" no-caret :right="$store.state.appConfig.isRTL">
                      <template #button-content>
                        {{ userprogram.status }}
                      </template>
                      <b-dropdown-item v-if="userprogram.status != 'Joined'"
                        @click="updatestatus(userprogram.id, 'Joined')">
                        <span class="align-middle ml-50">Joined</span>
                      </b-dropdown-item>
                      <b-dropdown-item v-if="userprogram.status != 'Completed'"
                        @click="updatestatus(userprogram.id, 'Completed')">
                        <span class="align-middle ml-50">Completed</span>
                      </b-dropdown-item>
                    </b-dropdown>
                  </b-form-group>
                  <span v-else>{{ userprogram.status }}</span>
                </td>
              </tr>
            </tbody>
          </table>
          <div v-if="!programs.length">
            <p>No programs found</p>
          </div>
        </app-collapse-item>

        <app-collapse-item title="Appointments" v-permission="['view-appointments']"
          v-if="paidprograms.length && is_consultant != '1' && $store.state.auth.user.role_id != 2">
          <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="success"
            :to="{ name: 'clientSchedules', props: { userId: this.id } }">
            Appointments
          </b-button>
        </app-collapse-item>
        <app-collapse-item title="Session schedule" v-permission="['view-workflows']" v-if="paidprograms.length && $store.state.auth.user.role_id != 2">
          <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="success"
            :to="{ name: 'workFlow', props: { userId: this.id } }">
            Session schedule
          </b-button>
        </app-collapse-item>

        <app-collapse-item title="Prescription" v-permission="['view-prescription']">
          <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="success"
            :to="{ name: 'prescriptions', props: { id: this.id } }">
            Prescriptions
          </b-button>
        </app-collapse-item>

        <app-collapse-item title="Investigation" v-permission="['view-investigation']" v-if="$store.state.auth.user.role_id != 2">
          <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="success"
            :to="{ name: 'investigations', props: { id: this.id } }">
            Investigations
          </b-button>
        </app-collapse-item>

        <app-collapse-item title="Case Sheets" v-permission="['view-case-sheet']" v-if="$store.state.auth.user.role_id != 2">
          <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="success" :to="{ name: 'clientCaseSheets' }">
            Case Sheets
          </b-button>
        </app-collapse-item>
        <app-collapse-item title="Questionnaire" v-permission="['view-questionnaire']" v-if="paidprograms.length && $store.state.auth.user.role_id != 2
          ">
          <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="success"
            :to="{ name: 'clientQuestionnaires', props: { id: this.id } }">
            Questionnaire
          </b-button>
          <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="success"
            :to="{ name: 'clientHTMLQuestionnaires', props: { id: this.id } }">
            HTML Questionnaire
          </b-button>
        </app-collapse-item>
        <app-collapse-item title="Assessments" v-permission="['view-questionnaire']" v-if="paidprograms.length &&
          $store.state.auth.user.role_id != 2 && $store.state.auth.user.role_id != 3
          ">
          <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="success"
            :to="{ name: 'assessments', props: { id: this.id } }">
            Assessments
          </b-button>
        </app-collapse-item>
        <app-collapse-item title="Diet Plans" v-permission="['view-diet-plan']" v-if="paidprograms.length">
          <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="success"
            :to="{ name: 'diet-plan-client', props: { id: this.id } }">
            Diet Plans
          </b-button>
        </app-collapse-item>
        <app-collapse-item title="Recipe" v-permission="['view-recipes']" v-if="paidprograms.length">
          <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="success"
            :to="{ name: 'recipe-client', props: { id: this.id } }">
            Recipe
          </b-button>
        </app-collapse-item>

        <app-collapse-item title="Cross Referral" v-if="upgraded && paidprograms.length">
          <b-button v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="success"
            :to="{ name: 'crs', props: { id: this.id } }">
            Cross Referral
          </b-button>
        </app-collapse-item>
      </app-collapse>
    </b-card-code>
  </div>
</template>

<script>
import BCardCode from "@core/components/b-card-code";
import AppCollapse from "@core/components/app-collapse/AppCollapse.vue";
import AppCollapseItem from "@core/components/app-collapse/AppCollapseItem.vue";
import Ripple from "vue-ripple-directive";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import moment from 'moment';

import {
  BFormRadioGroup,
  BFormRadio,
  BFormGroup,
  BCardText,
  BOverlay,
  BButton,
  BDropdown,
  BDropdownItem,
} from "bootstrap-vue";

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
    BDropdown,
    BDropdownItem,
  },
  directives: {
    Ripple,
  },
  data() {
    return {
      show: false,
      pageTitle: "Default App Title",
      programs: [],
      paidprograms: [],
      moment: moment,
    };
  },
  props: ["id"],
  computed: {
    upgraded: function () {
      return this.$store.state.auth.user.is_upgraded;
    },
    is_consultant: function () {
      return this.$store.state.auth.user.role.is_consultant;
    },
  },
  mounted() { },
  created() {
    this.getPrograms();
  },
  methods: {
    async getPrograms() {
      try {
        this.programs = [];
        const { data } = await axios.get("/client/programlist/" + this.id, {
          params: {},
        });
        this.programs = data.userprograms;
        this.paidprograms = data.paidprograms;
      } catch (error) {
        console.log(error);
      }
    },
    async updatestatus(id, status) {
      try {
        const { data } = await axios.post(
          "/client/update_programstatus",
          { id: id, status: status },
          {}
        );
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.message,
            icon: "EditIcon",
            variant: data.success ? "success" : "info",
          },
        });
        this.getPrograms();
      } catch (error) {
        if (error.response) {
          this.errors = error.response.data.errors;
        }
      }
    },
  },
};
</script>
