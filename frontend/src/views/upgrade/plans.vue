<template>
  <b-card no-body class="mb-0" style="min-height: 100vh;">
    <div class="m-2">
      <b-row :key="key">
          <b-col :sm="6" :md="6" :lg="6" :xl="6">
            <div class="offer-banner">
              <feather-icon icon="HeartIcon" size="100" />
              <h3 class="title">Become Premium Consultant</h3>
              <p class="subtitle">Avail our introductory offers</p>
              <p class="price"><del>&#8377;999 </del><big>&#8377;199</big> for 1 year</p>
              <p><b-button @click=" processPayment() " class="join-btn">Join Now</b-button></p>
            </div>

            <b-button variant="primary" class="reach-us mt-5 mb-sm-0 mr-sm-1" type="button" @click="initViewMoreReply">
              Know More
            </b-button>        

          </b-col>
          <b-col :sm="6" :md="6" :lg="6" :xl="6">
            <div class="point-list">
              <div class="list-header">
                <h3 class="flex-header">
                  <feather-icon icon="StarIcon" size="30"/>
                  <span>Premium Benefits</span>
                  <feather-icon icon="StarIcon" size="30"/>
                </h3>
              </div>
              <div class="list-content">
                <ul>
                  <li class="icon-list-item">
                    <feather-icon icon="CheckCircleIcon" size="20"/>
                    <span>Become a premium consultant and increase your online reach</span>
                  </li>
                  <li class="icon-list-item">
                    <feather-icon icon="CheckCircleIcon" size="20"/>
                    <span>Start your own Health and Wellness brand.</span>
                  </li>
                  <li class="icon-list-item">
                    <feather-icon icon="CheckCircleIcon" size="20"/>
                    <span>Have your own dedicated healthcare and support team</span>
                  </li>
                  <li class="icon-list-item">
                    <feather-icon icon="CheckCircleIcon" size="20"/>
                    <span>Offer your clients customized wellness programs and increase your revenue</span>
                  </li>
                </ul>
              </div>              
            </div>
          </b-col>
      </b-row>      
    </div>

    <b-modal id="action-view-more-modal" ref="action-view-more-modal" hide-footer no-close-on-backdrop>
      <b-img
        fluid
        :src="msgSentImg"
        alt="Message Sent"
        style="max-height: 224px;"
      />
      <h4 class="header">We will get back to you within 24 working hours</h4>
    </b-modal>

    <b-modal id="plans-modal" hide-footer>
      <template #modal-title>Select Plan</template>
      <div class="d-block text-left">
        <ul class="plan-inline-list">
          <li class="list">
            <label>
              <p class="radio">
                <input type="radio" name="plan_id"/>
              </p>
              <p class="radio-content">
                <span class="title">Duration<br/><strong>6 Months</strong></span>
                <span class="plan-price">&#8377; 600</span>
              </p>
            </label>
          </li>
          <li class=list>
            <label>
              <p class="radio">
                <input type="radio" name="plan_id"/>
              </p>
              <p class="radio-content">
                <span class="title">Duration<br/><strong>1 Year</strong></span>
                <span class="plan-price">&#8377;999</span>
              </p>
            </label>
          </li>
          <li class=list>
            <label>
              <p class="radio">
                <input type="radio" name="plan_id"/>
              </p>
              <p class="radio-content">
                <span class="title">Duration<br/><strong>2 Year</strong></span>
                <span class="plan-price">&#8377;1500</span>
              </p>
            </label>
          </li>
          <li class=list>
            <label>
              <p class="radio">
                <input type="radio" name="plan_id"/>
              </p>
              <p class="radio-content">
                <span class="title">Duration<br/><strong>5 Year</strong></span>
                <span class="plan-price">&#8377;2999</span>
              </p>
            </label>
          </li>
        </ul>

        <b-button variant="primary" class="mt-2 mb-sm-0 mr-sm-1" type="button" @click="processPayment">
          Proceed To Payment
        </b-button>        
      </div>
    </b-modal>


  </b-card>
</template>

<script>
import {
  BButton,
  BMedia,
  BAvatar,
  BRow,
  BCol,
  BFormGroup,
  BFormInput,
  BForm,
  BTable,
  BCard,
  BCardHeader,
  BCardTitle,
  BFormCheckbox,
  BFormInvalidFeedback,
  BFormFile,
  BMediaAside,
  BMediaBody,
  BLink,
  BImg,
  BFormRadioGroup,
  BFormRadio,
  BFormTextarea,
  BDropdown,
  BDropdownItem,
  VBModal,
  BModal
} from "bootstrap-vue";
import { required, email } from "@validations";
import formValidation from "@core/comp-functions/forms/form-validation";
import vSelect from "vue-select";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { ref } from "@vue/composition-api";
import flatPickr from "vue-flatpickr-component";
import Ripple from "vue-ripple-directive";
import { validateSize } from "@core/utils/utils";

import FeatherIcon from "../../@core/components/feather-icon/FeatherIcon.vue";

import VoerroTagsInput from "@voerro/vue-tagsinput";

require("@voerro/vue-tagsinput/dist/style.css");

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import "quill/dist/quill.snow.css";
import { quillEditor } from "vue-quill-editor";
import moment from 'moment';


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
    BModal,
    BImg,
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
      prgrm: {},
      cost: null,
      plans: [],
      plan: {},
      final_share_url: null,
      ap_costs: null,
      userInfo: null,
      cost: null,
      plans: [],
      plan: {}, 
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
    validateSize,
    async packageDetail() {
      //const { data } = await axios.get(`lead/plan/${this.id}`);
      //this.plan = data.package;
      this.cost = 1;
      //this.selected_plan = data.plan;
    },
    async viewPlans() {
      this.$bvModal.show("plans-modal");
    },
    async initViewMoreReply() {
      this.$refs["action-view-more-modal"].show();      
    },
    async planDetail() {
      const { data } = await axios.get(`plan/${this.id}`);
      this.program = data.package;
      this.cost = data.price;
      this.selected_plan = data.plan;
    },
    processPayment() {
      if (this.cost) {
        this.message = null;
        this.createOrder().then(({ data }) => {
          this.paymentOptions.order_id = data.order.id;
          let self = this;
          this.paymentOptions.handler = async function (response) {
            response.id = self.id;
            response.cost = self.cost;
            response.coupon = self.coupon_code;
            response.coupon_amount = self.coupon_amount;
            await self.savePaymentDetail(response).then(() => {

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
    async calculate_amount() {
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
    async createOrder() {
      return await axios.post("/razorpay/consultantorder", {
        amount: this.cost * 100,
        currency: "INR",
        id: this.$store.state.auth.user.id,
      });
    },
    async savePaymentDetail(response) {
      const { data } = await axios.post(`/razorpay/consultantpayment/${this.$store.state.auth.user.id}`, response);
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

      window.location.href = '/?upgraded=1';
    }
  },
};
</script>
<style lang="scss">
@import "@core/scss/vue/libs/vue-select.scss";
@import "@core/scss/vue/libs/vue-flatpicker.scss";

.offer-banner {
  max-width: 500px;
  border-radius: 100%;
  background: radial-gradient(circle at 10% 20%, rgb(26, 178, 203) 0%, rgb(0, 102, 161) 90.1%);
  min-height: 400px;
  text-align: center;
  padding-top: 4rem;
  display: block;
  margin-right: auto;
  margin-left: auto;
}

.offer-banner h3.title,
.offer-banner p.price {
  color: #FFFFFF;  
}

.offer-banner h3.title {
  padding-top: 2rem;
}

.offer-banner svg {
  color: #990E0E;
}

.offer-banner p.price big {
  color: #000000!important;
  font-size: 2rem;
  font-weight: 550;  
}

.offer-banner p.subtitle  {
  font-size: 18px;
  color: #000000;
  font-weight: 550;
}

.offer-banner  button.join-btn {
  background: radial-gradient(circle at 10.6% 22.1%, rgb(206, 18, 18) 0%, rgb(122, 21, 21) 100.7%);
  display: block;
  max-width: 144px;
  margin-right: auto;
  margin-left: auto;
  padding: 10px;
  color: #FFFFFF;
  margin-top: 2rem;
  border-radius: 7px;
}

.plan-inline-list {
  display: flex!important;
  list-style-type: none;
  padding-left: 0;
  flex-wrap: wrap;
  gap: 1rem 0;
}

.plan-inline-list label {
  display: flex;
  gap: 10px;  
}

.plan-inline-list li {
  flex: 50%;
  cursor: pointer;
}

.radio-content .plan-price {
  background: #f0d5d5;
  padding: 5px 10px;
  color: #000000;
  border-radius: 4px;
}

.point-list .list-content ul {
  padding-left: 0;
  list-style-type: none;  
}

.point-list .list-content {
  margin-top: 2rem;
}

.point-list .list-content ul li {
  min-height: 50px;
  line-height: 25px;
  border: 1px solid #EEEEEE;
  margin-bottom: 1rem;
  padding: 10px 2rem;
  color: #000000;
  font-weight: 550;
  gap: 10px;
  align-items: center;
}

.list-header .flex-header {
  align-items: center;
  display: flex;
  justify-content: center; 
  gap: 10px; 
  color: #1a27b6;
}

.list-content li.icon-list-item {
  display: flex;  
  gap: 10px;
  align-items: center;
}

.reach-us{
  margin-right: auto!important;
  margin-left: auto!important;
  display: block!important;
}

#action-view-more-modal .modal-body {
  text-align: center;
}

</style>
