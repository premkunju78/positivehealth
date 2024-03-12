<template>
  <div class="row justify-content-center" v-permission="['create-discount-coupons']">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <div class="col-sm-12 list-box">
            <div class="">
              <span class="bold-fonts"> Discount Code &nbsp;<span class="text-danger">*</span> </span>
              <div class="form-group mt-1">
                <input type="text" class="form-control" minlength="3" v-model="coupon.discount_code" />
                <small class="grey">User will enter this code while booking event</small>
              </div>
            </div>
          </div>
          <div class="col-sm-12 list-box">
            <div class="">
              <span class="bold-fonts"> Discount Type </span>
              <div class="form-group mt-1">
                <div class="form-check mt-1">
                  <input class="form-check-input" type="radio" name="discountType" id="discountType" value="Percentage"
                    @change="coupon.discount_value = null" v-model="coupon.discount_type" />
                  <label @click="coupon.discount_value = null; coupon.discount_type = 'Percentage'"
                    class="form-check-label font-300"> Percentage </label>
                </div>
                <div class="form-check mt-1">
                  <input class="form-check-input" type="radio" name="discountType" value="Fixed Amount"
                    v-model="coupon.discount_type" @change="coupon.discount_value = null" />
                  <label @click="coupon.discount_value = null; coupon.discount_type = 'Fixed Amount'"
                    class="form-check-label font-300"> Fixed Amount </label>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-12 list-box">
            <div class="">
              <span class="bold-fonts"> Value&nbsp;<span class="text-danger">*</span> </span>
              <div class="form-group mt-1">
                <label class="control-label">Discount value</label>
                <input v-if="coupon.discount_type === 'Percentage'" type="number" :min="1" :max="100" placeholder="%"
                  class="form-control" minlength="3" v-model="coupon.discount_value" />
                <input v-if="coupon.discount_type === 'Fixed Amount'" type="text" class="form-control" min="1"
                  max="1000000" placeholder="$ 0.00" v-model="coupon.discount_value" />
              </div>
            </div>
          </div>
          <div class="col-sm-12 list-box">
            <div class="">
              <span class="bold-fonts"> Active Dates </span>
              <div class="form-group mt-1 row">
                <div class="col-sm-6">
                  <label for="">Start Date&nbsp;<span class="text-danger">*</span> </label>
                  <input type="date" class="form-control" :min="formatDate(new Date())" v-model="coupon.start_date" />
                </div>
                <div class="col-sm-6">
                  <label for="">End Date&nbsp;<span class="text-danger">*</span> </label>
                  <input type="date" class="form-control" :min="formatDate(new Date())" v-model="coupon.end_date" />
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-12 list-box" style="border: nonne">
            <div class=" align-center">
              <button type="buton" class="btn btn-info" style="width: 50%" @click="onSubmit">
                <i class="fa fa-send"></i>&nbsp;{{ id ? 'Update' : 'Generate' }} Discount Code
              </button>
              <span class="badge badge-pill badge-warning mt-3" v-if="this.isbuttonDisabled">Create Event Types before
                generating DiscountCoupon</span>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style rel="stylesheet"  scoped>
.pull-right {
  float: right;
}

.grey {
  color: rgb(180, 178, 178);
}

.bold-fonts {
  font-weight: bolder;
}

.font-300 {
  font-weight: 300;
}

.align-center {
  text-align: center;
}
</style>
<script>

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
export default {
  name: 'createDiscountCoupon',
  props: {
    id: {
      type: [Number, String],
      default: null
    }
  },
  data() {
    return {
      rows: [],
      page: 1,
      index: 0,
      per_page: 10,
      coupon: {
        discount_type: "Percentage",
      },
      rows: [1],
      isbuttonDisabled: false
    };
  },
  created() {
    if (this.id) {
      this.fetchCouponDetails()
    }

  },
  methods: {
    async fetchCouponDetails() {
      const { data } = await axios.get(`/discountCoupon/${this.id}`);
      this.coupon = data.coupon;
    },

    getDiscountCoupons() {
      this.rows = this.discount_coupons;
    },
    addRow() {
      this.rows.push(this.index + 1);
    },
    deleteRow(row, index) {
      var index1 = this.rows.indexOf(row);
      this.rows.splice(index1, 1);
    },
    formatDate(date) {
      var d = new Date(date),
        month = "" + (d.getMonth() + 1),
        day = "" + d.getDate(),
        year = d.getFullYear();

      if (month.length < 2) month = "0" + month;
      if (day.length < 2) day = "0" + day;

      return [year, month, day].join("-");
    },
    async onSubmit() {
      if (
        this.coupon.discount_code &&
        this.coupon.discount_value &&
        this.coupon.start_date &&
        this.coupon.end_date
      ) {
        console.log(this.coupon.discount_value)
        if (this.coupon.discount_value == 0 || (this.coupon.discount_type === 'Percentage' && this.coupon.discount_value > 100)) {
          this.$toast({
            component: ToastificationContent,
            props: {
              title: "please enter a valid discount value",
              icon: "BellIcon",
              variant: "danger"
            }
          })
          return;
        }
        let data;
        if (this.id) {
          data = await axios.put(`/discountCoupon/${this.coupon.id}`, this.coupon)
        }
        else {
          data = await axios.post("/discountCoupon", this.coupon);
        }
        this.$toast({
          component: ToastificationContent,
          props: {
            title: data.data.message,
            icon: "BellIcon",
            variant: data.data.success ? "success" : "danger"
          }
        });
        this.$router.push({ name: 'discountCoupon' });
      } else {
        this.$toast({
          component: ToastificationContent,
          props: {
            title: "please fill all the required input fields first",
            icon: "BellIcon",
            variant: "danger"
          }
        });
      }
    },
  },
};
</script>