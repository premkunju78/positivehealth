import Vue from 'vue'
import FeatherIcon from '@core/components/feather-icon/FeatherIcon.vue'
import permission from '@/directive/permission';

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

Vue.component(FeatherIcon.name, FeatherIcon)

Vue.directive('permission', permission);

Vue.use(ToastificationContent);