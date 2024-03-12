<template>
  <b-row class="mt-4">
    <b-col :md="6" offset-md="3" :sm="12" class="shadow p-3" v-if="!program">
      <!-- Brand logo-->
      <b-link class="brand-logo mb-3 d-block">
        <b-img :src="appLogoImage" alt="logo" />
      </b-link>
      <!-- /Brand logo-->
      <div class="align-items-center justify-content-center">
        <h4>Invalid Payment Link. Please contact support team!</h4>
      </div>
    </b-col>
    <b-col :md="6" offset-md="3" :sm="12" class="shadow p-3" v-if="program">
      <!-- Brand logo-->
      <b-link class="brand-logo mb-3 d-block">
        <b-img :src="appLogoImage" alt="logo" />
      </b-link>
      <!-- /Brand logo-->
      <div class="align-items-center justify-content-center" v-if="!paymentdone">
        <img
          class="card-img-top mt-1 mb-2"
          :src="'/view/file?path=' + program.image"
          alt="Image"
        />
        <b-row>
          <b-col cols="10">
            <h3 class="mb-1 text-info" style="font-weight: bolder" v-if="program">
              {{ program.title }}
            </h3>
          </b-col>
        </b-row>
        <b-row>
          <b-col
            cols="12"
            class="mb-2"
            style="text-align: justify"
            v-if="program"
            v-html="program.description"
          >
          </b-col>
        </b-row>
        <b-row>
          <b-col cols="7">
            <p v-if="cost">
              Amount: <b>₹{{ cost }}</b>
            </p></b-col
          >
        </b-row>
        <b-row>
          <b-col cols="7">
            <p v-if="cost">
              Selected Plan: <b>{{ selected_plan }}</b>
            </p></b-col
          >

          <b-col cols="7" class="mb-2">
            <div class="form-group text-left">
              <div class="input-group">
                <input
                  type="text"
                  class="form-control"
                  placeholder="Coupon Code"
                  v-model="coupon_code"
                  required
                />
                <div class="input-group-append">
                  <button
                    class="input-group-text btn btn-primary"
                    style="color: #fff"
                    @click.prevent="calculate_amount(program.data)"
                    :disabled='isDisabled'
                  >
                    Apply
                  </button>
                </div>
              </div>
              <span v-if="coupon_message"
                ><small :style="'color:' + coupon_color">{{
                  coupon_message
                }}</small></span
              >
            </div>
          </b-col>
        </b-row>

        <div class="item-rating justify-content-start">
          <b-button size="lg" variant="primary mr-1" @click="processPayment(program)">
            <span style="color: #fff" class="text-nowrap">Pay Now</span>
          </b-button>
        </div>
      </div>
      <div v-if="paymentdone">
        <h4>Payment Completed Successfully!</h4>
        <p>
          You have been successfully enrolled into our program. Please check your email
          for further instruction!
        </p>
      </div>
    </b-col>
  </b-row>
</template>
<script>
import { BRow, BCol, BButton, BFormGroup, BImg } from "bootstrap-vue";

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import { $themeConfig } from "@themeConfig";
import vSelect from "vue-select";
export default {
  components: {
    BRow,
    BCol,
    BButton,
    BFormGroup,
    BImg,
    vSelect,
  },
  props: {
    id: {
      type: [Number, String],
      required: true,
    },
  },
  data() {
    return {
      loading: false,
      program: null,
      cost: null,
      selected_plan: null,
      plans: [],
      message: null,
      package: null,
      paymentdone: false,
      amount: 0,
      coupon_code: null,
      coupon_amount: null,
      coupon_applied: false,
      coupon_message: "",
      coupon_color: "red",
      isDisabled:false,
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
    };
  },
  created() {
    this.packageDetail();
  },
  setup() {
    // App Name
    const { appName, appLogoImage } = $themeConfig.app;
    return {
      appName,
      appLogoImage,
    };
  },
  mounted() {
    let recaptchaScript = document.createElement("script");
    recaptchaScript.setAttribute("src", "https://checkout.razorpay.com/v1/checkout.js");
    document.head.appendChild(recaptchaScript);
  },
  methods: {
    async packageDetail() {
      const { data } = await axios.get(`lead/program/${this.id}`);
      this.program = data.package;
      this.cost = data.price;
      this.selected_plan = data.plan;
    },
    processPayment(pkg) {
      if (this.cost) {
        this.message = null;
        pkg.cost = this.cost;
        this.createOrder(pkg).then(({ data }) => {
          this.paymentOptions.order_id = data.order.id;
          let self = this;
          this.paymentOptions.handler = async function (response) {
            response.id = self.id;
            response.cost = self.cost;
            response.coupon = self.coupon_code;
            response.coupon_amount = self.coupon_amount;
            await self.savePaymentDetail(response).then(() => {
              //   self.packageDetail();
            });
          };
          this.rzp1 = new window.Razorpay(this.paymentOptions);
          this.rzp1.on("payment.success", function () {
            alert("payment success");
          });
          this.rzp1.open();
        });
      } else {
        this.message = "Please selct Plan First";
      }
    },
    async calculate_amount(pkg) {
      this.coupon_color = "red";
      const { data } = await axios.get("checkcoupon?code=" + this.coupon_code);
      let coupon = data.coupon;
      let cost = parseFloat(this.cost);
      if (coupon) {
        if (coupon.discount_type === "Percentage") {
          this.coupon_amount = cost * (parseInt(coupon.discount_value) / 100);
        } else {
          this.coupon_amount = parseInt(coupon.discount_value);
        }
        if (cost >= this.coupon_amount) {
          this.cost = cost - this.coupon_amount;
          this.coupon_applied = true;
          this.coupon_message = `coupon applied with discount ₹${this.coupon_amount}`;
          this.coupon_color = "#67c7a5";
          this.isDisabled=true;
        } else {
          this.coupon_message = "sorry can't use this coupon code";
        }
      } else {
        this.cost = cost;
        this.coupon_message = "coupon is not valid";
      }
    },
    async createOrder(pkg) {
      return await axios.post("/razorpay/leadorder", {
        amount: this.cost * 100,
        currency: "INR",
        id: this.id,
      });
    },

    async savePaymentDetail(response) {
      const { data } = await axios.post("/razorpay/leadpayment", response);
      if (data.success) {
        this.paymentdone = true;
      }
      this.$toast({
        component: ToastificationContent,
        props: {
          title: data.message,
          icon: "BellIcon",
          variant: data.success ? "success" : "danger",
        },
      });
    },
  },
};
</script>
<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";
::placeholder {
  /* Chrome, Firefox, Opera, Safari 10.1+ */
  color: #26a79d !important;
  opacity: 1; /* Firefox */
}
.v-select.vs--single .vs__selected {
  color: #26a79d;
}
</style>
