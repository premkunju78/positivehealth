<template>
  <div class="clearfix">
    <router-link :to="'package/list'" class="float-right mt-1 mb-1">
      <b-button variant="primary">
        <span class="text-nowrap">Back</span>
      </b-button>
    </router-link>
    <b-row class="content-header">
      <!-- Content Left -->
      <b-col class="content-header-left mb-2" cols="12" md="9">
        <b-row class="breadcrumbs-top">
          <b-col cols="12">
            <h2 class="content-header-title float-left pr-1 mb-0">
              {{ program.type }} - {{ program.category }}
            </h2>
          </b-col>
        </b-row>
      </b-col>
    </b-row>
    <b-row class="mt-4">
      <!-- <b-col cols="12" style="text-align:right">
             <router-link :to="{ name: $store.state.auth.user.role_id === 1 ?  'packages' : 'viewPackages' }">
                <b-button
                    
                    variant="outline-secondary"
                    type="reset"
                    class="mb-1 mb-sm-0 mr-0 mr-sm-1"
                    :block="
                        $store.getters['app/currentBreakPoint'] === 'xs'
                    "
                >
                    <feather-icon  icon="ArrowLeftIcon"></feather-icon>
                    Back
                </b-button>
            </router-link>
        </b-col> -->
      <b-col :md="8" :sm="12">
        <div class="align-items-center justify-content-center ml-3">
          <b-row>
            <b-col cols="10">
              <h3 class="mb-1 text-info" style="font-weight: bolder" v-if="program">
                {{ program.title }}
              </h3>
            </b-col>
          </b-row>
          <b-row>
            <b-col cols="11" class="mb-2" style="text-align: justify" v-if="program" v-html="program.description">
            </b-col>
          </b-row>
          <b-row v-if="userInfo && userInfo.cisf == '1' || $store.state.auth.user.role_id == 2">
            <table class="table">
              <thead>
                <tr>
                  <th v-if="$store.state.auth.user.role_id != 2">Alliance Partner</th>
                  <th>Plan</th>
                </tr>
              </thead>
              <tr v-for="cost in ap_costs">
                <td v-if="$store.state.auth.user.role_id != 2">{{ cost.ap_name }}</td>
                <td>
                  <table class="table">
                    <tr v-for="(data, index) in cost.data">
                      <td>{{ index }}</td>
                      <td>{{ data }}</td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </b-row>

          <b-row v-if="[3].includes($store.state.auth.user.role_id)
            ">
            <!-- <b-col cols="12" class="mb-2" >
                        <h5 class="mb-1 text-info" style="font-weight:bolder">
                               Sessions
                        </h5>
                        <ul style="list-style:none">
                            <li style="font-weight:450" v-for="session in program.sessions" :key="session">
                                {{session.type}} - {{session.no_of_sessions}}
                            </li>
                        </ul>
                    </b-col>  -->
            <b-col cols="7" class="mb-2" v-if="program.enrolled != 1">
              <v-select class="mt-2" v-model="cost" :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'" :options="plans"
                :clearable="false" placeholder="Select Plan" input-id="type" @input="message = null;
                amount = program.data[cost];
                request_callbacks = request_callback[cost]
                                            " />
              <span class="text-danger" v-if=" message ">
                {{ message }}

              </span>
            </b-col>
            <b-col cols="7">
              <p v-if=" amount ">
                Amount: <b>₹{{ amount }}</b>
              </p>
            </b-col>
            <b-col cols="7" class="mb-2" v-if=" program.enrolled != 1 ">
              <div class="form-group text-left">
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="Coupon Code" v-model=" coupon_code " required />
                  <div class="input-group-append">
                    <button class="input-group-text btn btn-primary" style="color: #fff"
                      @click.prevent=" calculate_amount(program.data) ">
                      Apply
                    </button>
                  </div>
                </div>
                <span v-if=" coupon_message "><small :style=" 'color:' + coupon_color ">{{
                    coupon_message
                    }}</small></span>
              </div>
            </b-col>
          </b-row>

          <div class="item-rating justify-content-start" v-if=" [3].includes($store.state.auth.user.role_id) ">
            <b-button size="lg" variant="info mr-3" v-if=" request_callbacks == 'Yes' ">
              <span style="color: #fff" class="text-nowrap" @click=" notifyAdminCallBack(program) ">Request Call
                back</span>
            </b-button>
            <b-button size="lg" variant="primary mr-1" v-if="
              program &&
                program.duration !== 'Free Session' &&
                program.payment_type === 'Paid' && request_callbacks == 'No'
            " :disabled=" program.transaction_id " @click=" processPayment(program) ">
              <span style="color: #fff" class="text-nowrap">Pay Now</span>
            </b-button>
            <b-button size="lg" v-if=" program && program.enrolled == 1 " variant="primary mr-1"
              :disabled=" program && program.enrolled == 1 " @click=" saveEnrollDetail(program) ">
              <span style="color: #fff" class="text-nowrap">{{
                program.enrolled == 1 ? "Enrolled" : "Enroll"
                }}</span>
            </b-button>
            <span></span>
          </div>
        </div>
      </b-col>
      <b-col :md=" 4 " :sm=" 12 ">
        <img v-if=" program " class="card-img-top mt-1" :src=" '/view/file?path=' + program.image " alt="Image" />
      </b-col>
    </b-row>
    <b-row class="mt-4">
      <b-col :md="12" :sm="12" :lg="12" class="text-center"> 
        <b-button variant="primary" class="mt-1 mb-1" @click=" programActions('reachus') " data-target="reachus">
          <span class="text-nowrap">Reach Us</span>
        </b-button>

        <b-button variant="success" class="mt-1 mb-1" @click=" programActions('refer') " data-target="reachus">
          <span class="text-nowrap">Refer</span>
        </b-button>

      </b-col>
    </b-row>

    <b-modal id="program-actions-modal" ref="actions-program-modal" title="hello static" ok-only no-close-on-backdrop>
      <b-img
        fluid
        :src="msgSentImg"
        alt="Message Sent"
        style="max-height: 224px;"
      />
      <h4>We will get back to you shortly</h4>
      <b-button class="notice-btn">i</b-button>
    </b-modal>
    
  </div>  
</template>
<script>
import { BRow, BCol, BButton, BFormGroup, VBModal, BModal, BImg } from "bootstrap-vue";

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import vSelect from "vue-select";
import FeatherIcon from "../../@core/components/feather-icon/FeatherIcon.vue";
import Coupon from "./coupon.vue";
export default {
  components: {
    BRow,
    BCol,
    BButton,
    BFormGroup,
    vSelect,
    FeatherIcon,
    VBModal,
    BModal,
    BImg,
  },
  props: {
    id: {
      type: [Number, String],
      required: true,
    },
  },
  mixins: [Coupon],
  data() {
    return {
      loading: false,
      program: null,
      prgrm: {},
      ap_costs: null,
      userInfo: null,
      cost: null,
      plans: [],
      request_callback: {},
      message: null,
      package: null,
      paymentOptions: {
        key: process.env.MIX_RAZOR_KEY,
        currency: "INR",
        name: "Prajana",
        description: "Test Transaction",
        prefill: {
          name: this.$store.state.auth.user.name,
          email: this.$store.state.auth.user.email,
          contact: this.$store.state.auth.user.phone,
        },
        theme: {
          color: "#425b76",
          width: "500px",
          height: "900px",
        },
      },
      msgSentImg: require('@/assets/images/consultant/message-sent.png'),
    };
  },
  created() {
    this.packageDetail();
  },
  methods: {
    async packageDetail() {
      const { data } = await axios.get(`package/${this.id}`);
      this.program = data.package;
      this.ap_costs = data.ap_costs;
      this.userInfo = data.userInfo;
      this.program.data = JSON.parse(this.program.data);
      Object.keys(this.program.data).map((plan) => {
        this.plans.push(plan);
      });
      this.request_callback = data.request_callback_data;
    },
    processPayment(pkg) {
      if (this.cost) {
        this.message = null;
        pkg.cost = this.amount;
        this.package = pkg;
        this.paymentOptions.amount = pkg.cost * 100;
        this.createOrder(pkg).then(({ data }) => {
          this.paymentOptions.order_id = data.order.id;
          let self = this;
          this.paymentOptions.handler = async function (response) {
            response.id = self.package.package_user_id;
            response.cost = self.package.cost;
            response.coupon = self.coupon_code;
            response.coupon_amount = self.coupon_amount;
            await self.savePaymentDetail(response).then(() => {
              self.packageDetail();
            });
          };
          this.rzp1 = new Razorpay(this.paymentOptions);
          this.rzp1.on("payment.success", function () {
            alert("payment success");
          });
          this.rzp1.open();
        });
      } else {
        this.message = "Please selct Plan First";
      }
    },
    async createOrder(pkg) {
      return await axios.post("/razorpay/order", {
        amount: pkg.cost * 100,
        currency: "INR",
        id: pkg.package_user_id,
      });
    },

    async savePaymentDetail(response) {
      return await axios.post("/razorpay/payment", response);
    },
    async saveEnrollDetail(program) {
      const { data } = await axios.post(`/package/${program.package_user_id}/enroll`);
      this.$toast({
        component: ToastificationContent,
        props: {
          title: data.message,
          icon: "BellIcon",
          variant: data.success ? "success" : "danger",
        },
      });
      this.packageDetail();
    },
    async notifyAdminCallBack(program) {
      const { data } = await axios.post("/program/callbackreq", {
        id: program.id,
        package_user_id: program.package_user_id,
      });

      this.$toast({
        component: ToastificationContent,
        props: {
          title: data.message,
          icon: "BellIcon",
          variant: data.success ? "success" : "danger",
        },
      });
    },
    async programActions(action) {
      this.prgrm.title = 'Hello World!';
      this.prgrm.description = 'Hello World!';
      this.$refs["actions-program-modal"].show();
    }
  },
};

</script>
<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";

::placeholder {
  /* Chrome, Firefox, Opera, Safari 10.1+ */
  color: #26a79d !important;
  opacity: 1;
  /* Firefox */
}

.v-select.vs--single .vs__selected {
  color: #26a79d;
}

#program-actions-modal .modal-header, 
#program-actions-modal .modal-footer {
  display: none !important;
} 

#program-actions-modal .modal-body {
  text-align: center;
}

#program-actions-modal .modal-body button.notice-btn {
  position: absolute;
  top: -1.5rem;
  left: 0;
  right: 0;
  margin-left: auto;
  margin-right: auto;
  width: 44px;
  height: 44px;
  padding: 0;
  border-radius: 100px;
  background-color: #007AFE!important;
  border: 0;
}

</style>
