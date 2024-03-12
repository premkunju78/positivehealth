<script>
import { t } from '@/@core/libs/i18n/utils';

    export default {
        data(){
            return {
                amount:0,
                coupon_code: null,
                coupon_amount: null,
                coupon_applied: false,
                coupon_message: '',
                coupon_color: 'red',
            }
        },
        methods: {
            async calculate_amount(pkg) {
                if( !this.cost){
                    this.message="Please select plan first";
                    return;
                }
                this.coupon_color = 'red';
                const {data} = await axios.get('coupon?code='+ this.coupon_code);
                let coupon = data.coupon;
                let cost = parseFloat(pkg[this.cost]);
                if (coupon) {
                    if (coupon.discount_type === "Percentage") {
                        this.coupon_amount =
                        cost *(parseInt(coupon.discount_value) / 100);
                    } else {
                        this.coupon_amount = parseInt(coupon.discount_value);
                    }
                    if( cost >= this.coupon_amount ) {
                        this.amount = cost - this.coupon_amount;
                        this.coupon_applied = true;
                        this.coupon_message = `coupon applied with discount ₹${this.coupon_amount}`;
                        this.coupon_color = '#67c7a5';
                    } else {
                        this.coupon_message = 'sorry can\'t use this coupon code';
                    }
                }else {
                    this.amount = cost;
                    this.coupon_message = 'coupon is not valid';
                }
            },

            resetCouponData() {
                this.coupon_code =  null;
                this.coupon_amount =  null;
                this.coupon_applied =  false;
                this.coupon_message =  '';
                this.coupon_color =  'red';
            }
        }
    }
</script>
