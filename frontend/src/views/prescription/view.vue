<template>
  <section class="invoice-preview-wrapper">

    <!-- Alert: No item found -->
    <b-alert variant="danger" :show="prescriptionData === undefined">
      <h4 class="alert-heading">
        Error fetching investigation data
      </h4>
    </b-alert>

    <b-row v-if="prescriptionData" class="invoice-preview">

      <!-- Col: Left (Invoice Container) -->
      <b-col cols="10" xl="9" md="8">
        <b-card no-body class="invoice-preview-card">
          <!-- Header -->
          <b-card-body class="invoice-padding pb-0">

            <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">

              <!-- Header: Left Content -->
              <div>
                <div class="logo-wrapper">
                  <b-img v-if="prescriptionData.logo" :src="'/view/file?path=' + prescriptionData.logo" fluid :width="100"
                    :height="100" class='m1'></b-img>
                  <div v-else>
                    <logo />
                    <h3 class="text-primary invoice-logo">
                      Prajana
                    </h3>
                  </div>
                </div>
                <p class="card-text mb-25">
                </p>
                <p class="card-text mb-25">
                </p>
                <p class="card-text mb-0">
                </p>
              </div>

              <!-- Header: Right Content -->
              <div class="mt-md-0 mt-2 ">
                Consultant <b>{{ prescriptionData.consultant }}</b>
                <div class="invoice-date-wrapper">
                  <!-- <p class="invoice-date-title">
                      Phone Number:  {{ prescriptionData.phone }}
                  </p> -->
                </div>
              </div>
            </div>
          </b-card-body>


          <!-- Spacer -->
          <hr class="invoice-spacing">

          <!-- Invoice Client & Payment Details -->
          <b-card-body class="invoice-padding pt-0">
            <b-row class="invoice-spacing">

              <!-- Col: Invoice To -->
              <b-col cols="11" xl="6" class="p-0">
                <h6 class="ml-1 mb-2">
                  Client:
                </h6>
                <h6 class="ml-1 mb-25">
                  {{ prescriptionData.patient_name }}
                </h6>
                <p class="ml-1 card-text mb-25" v-if="prescriptionData.dob">
                  Age.{{ age(prescriptionData.dob) }}
                </p>
                <!-- <p class="ml-1 card-text mb-25">
                  Mobile No.{{ prescriptionData.patient_phone }}
                </p>

                <p class="ml-1 card-text mb-25" style="opacity:0.6">
                  {{ prescriptionData.patient_email }}
                </p> -->
              </b-col>

              <!-- Col: Payment Details -->
              <b-col xl="6" cols="12" class="p-0 mt-xl-0 mt-2 d-flex justify-content-xl-end">
                <div>
                  <h6 class="mr-1 mb-0 text-info">
                    Prescription No : {{ prescriptionData.no }}
                  </h6>
                  <p>Date: {{ moment(prescriptionData.created_at).format('MMM DD,YYYY') }}</p>
                </div>
              </b-col>
            </b-row>
          </b-card-body>

          <!-- Invoice Description: Table -->
          <b-table-lite responsive bordered :items="prescriptionData.data" :fields="fields"
            style="min-height:500px;padding-top:3%">
            <template #cell(index)="data">
              <span style="color:#fff;">{{ data.index + 1 }}</span>
            </template>
            <template #cell(drug)="data">
              <b-card-text class="font-weight-bold text-primary mb-25">
                {{ data.item.drug }}
              </b-card-text>
            </template>
            <template #cell(timing)="data">
              {{ data.item.timing.join(',') }}
            </template>
          </b-table-lite>

          <!-- Spacer -->
          <hr class="invoice-spacing">

          <!-- Note -->
          <b-card-body class="invoice-padding pt-0 mt-2">
            <div class="d-flex justify-content-end flex-column mt-0" style="text-align:right">
              <span v-if="prescriptionData.signature_img">
                <b-img :src="'/view/file?path=' + prescriptionData.signature_img" fluid :width="75" :height="75"
                  class='m1'></b-img>
              </span>
              <small class="font-weight-bold">Signatures</small>
            </div>
          </b-card-body>
        </b-card>
      </b-col>
    </b-row>
  </section>
</template>

<script>
import { ref, onUnmounted } from '@vue/composition-api'
import store from '@/store'
import router from '@/router'
import {
  BRow, BCol, BCard, BCardBody, BTableLite, BCardText, BButton, BAlert, BLink, VBToggle,
  BImg
} from 'bootstrap-vue'
import Logo from '@core/layouts/components/Logo.vue'
import Ripple from 'vue-ripple-directive';
import moment from 'moment';

export default {
  directives: {
    Ripple,
    'b-toggle': VBToggle,
  },
  components: {
    BRow,
    BCol,
    BCard,
    BCardBody,
    BTableLite,
    BCardText,
    BButton,
    BAlert,
    BLink,
    BImg,

    Logo,
  },
  setup() {
    const prescriptionData = ref(null)

    const fields = [
      { key: 'index', label: '#', sortable: true, tdClass: 'width40' },
      { key: 'drug', label: 'Drug Name', sortable: true },
      { key: 'dosage', label: 'Dosage', sortable: true },
      { key: 'timing', label: 'Frequeny', sortable: true },
      { key: 'days', label: 'Days', sortable: true },
      { key: 'stomach', label: 'Food Related', sortable: true },
    ];



    axios.get(`prescription/${router.currentRoute.params.id}`)
      .then(response => {
        prescriptionData.value = response.data.prescription
      })
      .catch(error => {
        if (error.response.status === 404) {
          prescriptionData.value = undefined
        }
      })

    const printInvoice = () => {
      window.print()
    }

    return {
      prescriptionData,
      fields,
      printInvoice,
    }
  },
  data() {
    return {
      moment: moment
    };
  },
  methods: {
    age: (dob) => {
      if (dob != '' && dob != null) {
        var diff = (new Date().getTime() - new Date(dob).getTime()) / 1000;
        diff /= (60 * 60 * 24);
        return Math.abs(Math.round(diff / 365.25));
      }
    }
  }
}
</script>



<style lang="scss">
.width40 {
  width: 10px !important;
  text-align: center;
  background-color: #7566ed;
}

[dir] .table th,
[dir] .table td {
  padding: 0.72rem 1rem !important;
}
</style>
