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
      <b-col cols="12" xl="12" md="12">
        <b-button
          variant="primary"
          @click="printInovie"
        >
          <feather-icon
            icon="DownloadIcon"
          />        
          <span class="text-nowrap">Download Invoice</span>
        </b-button>        
        <b-card no-body class="invoice-preview-card mt-2" id="client-prescription-invoice">
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

                  <address>
                    <p><b>{{ prescriptionData.consultant_clinic }}</b></p>
                    <p>{{ prescriptionData.consultant_pincode }}</p>
                    <p>{{ prescriptionData.consultant_address }}</p>
                  </address>

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
                <p><b>{{ prescriptionData.consultant }}</b><br/>({{ prescriptionData.consultant_specialized_in }})</p>
                <p><feather-icon icon="MailIcon" />  <span>{{ prescriptionData.consultant_email }}</span></p>

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
                <h6 class="ml-1 mb-25">
                  {{ prescriptionData.patient_name }}
                </h6>
                <p class="ml-1 card-text mb-25" v-if="prescriptionData.dob">
                  Age.{{ age(prescriptionData.dob) }} years /
                  <b>{{ prescriptionData.patient_gender }}</b>
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
            style="padding-top:3%">
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


    axios.get(`prescription/${router.currentRoute.params.id}?client_id=${router.currentRoute.params.client_id}`)
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
    },
    printInovie() {
      const prtHtml = document.getElementById('client-prescription-invoice').innerHTML;
      let stylesHtml = '';
      for (const node of [...document.querySelectorAll('link[rel="stylesheet"], style')]) {
        stylesHtml += node.outerHTML;
      }

      const WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');

      WinPrint.document.write(`<!DOCTYPE html>
      <html>
        <head>
          ${stylesHtml}
        </head>
        <body>
          ${prtHtml}
        </body>
      </html>`);

      WinPrint.document.close();
      WinPrint.focus();
      WinPrint.print();
      WinPrint.close();

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

address p {
  margin: 0;
  padding: 0
}

[dir] .table th,
[dir] .table td {
  padding: 0.72rem 1rem !important;
}
</style>
