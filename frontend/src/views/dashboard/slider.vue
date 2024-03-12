<template>
  <b-carousel v-if="coupons">
    <b-carousel-slide v-for="coupon in coupons" :key="coupon.id">
      <b-card style="height: 270px; background: #05055a; border-radius: 20px">
        <div>
          <b-card-title class="mb-25">
            <h5 class="text-info">Offer Zone</h5>
          </b-card-title>
        </div>
        <h4 class="pt-1" style="color: #fff">
          AVAIL {{ coupon.discount_value
          }}<i v-if="coupon.discount_type == 'Percentage'">%</i> OFF*
        </h4>
        <div class="d-flex" style="color: #fff">
          <h5 class="pt-1" style="color: #fff">Prajana Wellness Program</h5>
        </div>
        <h5 class="pt-1" style="color: #fff">USE CODE: {{ coupon.discount_code }}</h5>
        <div class="d-flex pt-1" style="color: #fff">
          <div>Valid Upto: {{ moment(coupon.end_date).format('DD-MM-YYYY') }}</div>
        </div>
        <div class="d-flex justify-content-right pt-2" style="color: #fff; float: right">
          <span>*Conditions Apply</span>
        </div>
        <!-- <div class="d-flex justify-content-center pt-2" style="color:#fff">
         <button class="btn btn-default" style="background:#fff">Know More</button>
       </div> -->
      </b-card>
    </b-carousel-slide>
  </b-carousel>
</template>
<script>
import { BCard, BLink, BCardTitle, BCarousel, BCarouselSlide } from "bootstrap-vue";
import moment from 'moment';
export default {
  components: {
    BCard,
    BLink,
    BCardTitle,
    BCarousel,
    BCarouselSlide,
  },
  data() {
    return {
      coupons: null,
      moment:moment
    };
  },
  computed: {},
  async created() {
    const { data } = await axios.get(`discountCoupon/list`);
    this.coupons = data.coupons;
  },
};
</script>

<style lang="scss">
.carousel-caption {
  position: unset;
  text-align: left;
}
.carousel-indicators {
    position: unset;
    text-align: center;
    margin-top: -32px;
    display: block !important
}
.carousel-indicators li {
   background-color: #afabab;
    display: inline-block;
}
</style>
