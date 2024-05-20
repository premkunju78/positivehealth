<template>
  <b-row class="mt-4">
    <b-col :md="6" offset-md="3" :sm="12" class="shadow p-3" v-if="!program && externalPays == false">
      <!-- Brand logo-->
      <b-link class="brand-logo mb-3 d-block">
        <b-img :src="appLogoImage" alt="logo" />
      </b-link>
      <!-- /Brand logo-->
      <div class="align-items-center justify-content-center">
        <h4>Invalid Payment Link. Please contact support team!</h4>
      </div>
    </b-col>
    <b-col :md="6" offset-md="3" :sm="12" class="shadow p-3" v-if="program || externalPays == true">
      <!-- Brand logo-->
      <b-link class="brand-logo mb-3 d-block">
        <b-img :src="appLogoImage" alt="logo" />
      </b-link>
      <!-- /Brand logo-->
      <div class="align-items-center justify-content-center" v-if="!paymentdone">
        <img
          v-if="externalPays == false"
          class="card-img-top mt-1 mb-2"
          :src="'/view/file?path=' + program.image"
          alt="Image"
        />
        <img
          v-if="externalPays == true && clientgroup"
          class="card-img-top mt-1 mb-2"
          :src="'/view/file?path=' + clientgroup.logo"
          alt="Image"
        />
        <b-row>
          <b-col cols="10">
            <h3 class="mb-1 text-info" style="font-weight: bolder" v-if="program">
              {{ program.title }}
            </h3>
            <h3 class="mb-1 text-info" style="font-weight: bolder" v-if="clientgroup">
              <strong>{{ clientgroup.group_name }} ({{ clientgroup.name }})</strong>
            </h3>
            <p class="mb-1 text-info" style="font-weight: bolder" v-if="clientgroup">
              Group Type: <strong>{{ clientgroup.group_type }}</strong>
            </p>
            <p class="mb-1 text-info" style="font-weight: bolder" v-if="clientgroup">
              Total Sessions: <strong>{{ clientgroup.session_no }}</strong>
            </p>
            <p class="mb-1 text-info" style="font-weight: bolder" v-if="clientgroup">
              Total Participants: <strong>{{ clientgroup.no_of_participants }}</strong>
            </p>
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
          <b-col
            cols="12"
            class="mb-2"
            style="text-align: justify"
            v-if="clientgroup"
            v-html="clientgroup.description"
          >
          </b-col>
        </b-row>
        <b-row v-if="program || clientgroup.session_type == 'paid'">
          <b-col cols="7">
            <p v-if="cost">
              Amount: <b>₹{{ cost }}</b>
            </p></b-col
          >
        </b-row>
        <b-row v-if="externalPays == false">
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
            <span style="color: #fff" class="text-nowrap">
              <span v-if="program || clientgroup.session_type == 'paid'">Pay Now</span>
              <span v-if="clientgroup && clientgroup.session_type == 'free'">Join Now</span>
            </span>
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
    <b-modal id="client-details" ref="client-details" :hide-footer="proccessingText === false ? false : true" @cancel="closeValidationModal" :cancel-title="clientgroup.session_type === 'free' && externalClient.varificationType == 'internal' && clientVarified == true ? 'Not Interested' : 'Close'" :ok-title="externalClient.varificationType == 'internal' && clientgroup.session_type == 'free' && clientVarified == true ? 'Yes i\'m Interested' : 'Validate'" centered size="md" title="Validate Phone Number" @ok.prevent="validateClientOtp" no-close-on-backdrop>
      <b-row>
        <b-col cols="12" md="12">
          <div class="mb-2" >
            <b-form-group label="Phone Number" label-for="phone" v-if="clientVarified === false">
              <b-form-input
                id="phone-input"
                v-model="externalClient.phone"
                :readonly="readonly"
                @input="validateClient"
              />
            </b-form-group>
            <div class="mt-2" v-if="clientVarified == true && proccessingText === false">
              <p v-if="externalClient.varificationType == 'internal' && clientgroup.session_type == 'paid'"><span class="text-success"><strong>We have validated your account!<br/>You can now proceed for payment.</strong></span></p>
              <p v-if="externalClient.varificationType == 'internal' && clientgroup.session_type == 'free'"><span class="text-success"><strong>We have validated your account!</strong></span><br/><br/><span><strong>Are you interested to join the workshop?</strong></span></p>
              <p v-if="externalClient.varificationType == 'external'"><span class="text-danger"><strong>To validate your account please complete the OTP proccess!<br/> OTP is: {{ externalClient.otp }}</strong></span><p>
              <p v-if="externalClient.varificationType == 'external'">
                <b-form-group label="Enter OTP" label-for="phone">
                  <b-form-input
                    id="otp-varif-input"
                    v-model="externalClient.otp_varif"
                  />
                </b-form-group>                 
              </p>
            </div>
            <div class="mt-2" v-if="clientVarified == true && proccessingText !== false">
              <p><span class="text-success" v-html="proccessingText"></span></p>
            </div>
          </div>
        </b-col>    
      </b-row>            
    </b-modal>
  </b-row>
</template>
<script>
import { BRow, BCol, BButton, BFormGroup, BImg, BModal, BFormInvalidFeedback,
  BFormInput,
  BOverlay,
  BFormCheckbox,
  BFormDatepicker,
  BFormFile 
} from "bootstrap-vue";

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
    BModal,
    BFormInput,
    BOverlay,
    BFormCheckbox,
    BFormDatepicker,
    BFormFile, 
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
      clientgroup: null,
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
      externalPays: false,
      clientVarified: false,
      proccessingText: false,
      externalClient: {},
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
    if(this.$route.name == 'clientspayment') {
      this.externalPays = true
    }
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
      if(this.externalPays == true) {
        const { data } = await axios.get(`clientgroup/view/${this.id}`);
        this.clientgroup = data.group;
        this.cost = data.group.payment_amount;
        this.selected_plan = null;
      } else {
        const { data } = await axios.get(`lead/program/${this.id}`);
        this.program = data.package;
        this.cost = data.price;
        this.selected_plan = data.plan;
      }
    },
    async processPayment(pkg) {
      if(this.externalPays == true && this.clientVarified == false) {
        this.$refs["client-details"].show();        
      }else {
        if (this.cost && (this.program || this.clientgroup.session_type == 'paid')) {          
          if(this.externalPays == true && this.clientVarified == true) {
            pkg = {}
          }
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
        } else if (this.externalPays == true && this.clientgroup.session_type == 'free') {
          if(this.externalClient.varificationType == 'external') {
            window.location.href = "https://staging.positivehealth.app/healthinfinit/register?phone=" + this.externalClient.phone + "&group_id=" + this.clientgroup.id                 
          } else if (this.externalClient.varificationType == 'internal') {
            this.proccessingText = '<strong>You have been registered for the Workshop.<br/>you will redirect in 5 seconds...</strong>';
            setTimeout(function(){
              window.location.href = "https://staging.positivehealth.app/" + this.externalClient.apEndpoint + "/login/?group_id=" + this.clientgroup.id
            }.bind(this), 5000)
          }           
        } else { 
          this.message = "Please selct Plan First";
        }
      }
    },
    async validateClientOtp() {
      if((this.externalClient.varificationType == 'external') 
        && (this.externalClient.otp_varif != this.externalClient.otp)
      ) {
        this.clientVarified = false      
        this.externalClient.phone = "";       
        this.externalClient.otp = "";       
        this.externalClient.otp_varif = "";       
        alert('Invalid OTP');
      }else{
        this.clientVarified = true      
        this.$refs["client-details"].hide();                
        this.processPayment(this.program);
      }
    },
    async validateClient() {
      if(this.externalClient.phone.length == 10) {
        const { data } = await axios.post("/validate_external_client", {
          phone: this.externalClient.phone
        });

        this.externalClient.varificationType = data.varification_type;
        this.externalClient.apEndpoint = data.ap_endpoint;
        this.externalClient.otp = data.otp;       

        this.clientVarified = true      
        this.paymentOptions.prefill.contact = this.externalClient.phone
      }else{
        this.clientVarified = false      
        this.paymentOptions.prefill.contact = ""      
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
        for_external: this.externalPays
      });
    },
    async closeValidationModal() {
      if(this.externalPays == true && this.clientVarified == true && this.clientgroup.session_type === 'free') {
        location.reload();
      }
    },
    async savePaymentDetail(response) {
      response.for_external = this.externalPays
      const { data } = await axios.post("/razorpay/leadpayment", response);
      if (data.success) {
        this.paymentdone = true;
      }

      if(this.externalPays == true && this.externalClient.varificationType == 'external') {
        window.location.href = "https://staging.positivehealth.app/healthinfinit/register?phone=" + this.externalClient.phone + "&group_id=" + this.clientgroup.id                 
      } else if (this.externalPays == true && this.externalClient.varificationType == 'internal') {
        window.location.href = "https://staging.positivehealth.app/login/" + this.externalClient.apEndpoint + "/?group_id=" + this.clientgroup.id
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
