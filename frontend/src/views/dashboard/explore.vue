<template>
  <b-card
    no-body
    class="card-browser-states mt-1"
  >
    <b-card-header>
      <div>
        <b-card-title style="font-size:inherit"><b>Explore HealthInfinity</b></b-card-title>
      </div>
    </b-card-header>

    <!-- body -->
    <b-card-body style="height:450px">
      <b-img :src="planImg" alt="Plans" style="width:100%; height:100%"/>
    </b-card-body>
    <!--/ body -->
  </b-card>
</template>

<script>
import {
  BCard, BCardHeader, BCardTitle, BCardText, BCardBody, BMedia, BMediaAside, BMediaBody, BImg, BDropdown, BDropdownItem,
} from 'bootstrap-vue'
import VueApexCharts from 'vue-apexcharts'
import { $themeColors } from '@themeConfig'
/* eslint-disable global-require */
const $trackBgColor = '#e9ecef'
export default {
  components: {
    BCard,
    BCardHeader,
    BCardTitle,
    BCardText,
    BCardBody,
    BMedia,
    BMediaAside,
    BMediaBody,
    BImg,
    BDropdown,
    BDropdownItem,
    VueApexCharts,
  },
  data() {
    return {
      chartData: [],
      chartClone: {},
      chartColor: [$themeColors.primary, $themeColors.warning, $themeColors.secondary, $themeColors.info, $themeColors.danger],
      chartSeries: [54.4, 6.1, 14.6, 4.2, 8],
      planImg: require('@/assets/images/pages/mp7.jpg'),
      browserData: [
        {
          browserImg: require('@/assets/images/icons/google-chrome.png'),
          name: 'Helth Infinity got featured in XYZ',
          usage: '54.4%',
        },
        {
          browserImg: require('@/assets/images/icons/mozila-firefox.png'),
          name: 'Recipies to keep you fit this summer',
          usage: '6.1%',
        },
        {
          browserImg: require('@/assets/images/icons/apple-safari.png'),
          name: 'Watch Dr. Bhatt Talk about COVID Vaccines',
          usage: '14.6%',
        },
      ],
      chart: {
        series: [65],
        options: {
          grid: {
            show: false,
            padding: {
              left: -15,
              right: -15,
              top: -12,
              bottom: -15,
            },
          },
          colors: [$themeColors.primary],
          plotOptions: {
            radialBar: {
              hollow: {
                size: '22%',
              },
              track: {
                background: $trackBgColor,
              },
              dataLabels: {
                showOn: 'always',
                name: {
                  show: false,
                },
                value: {
                  show: false,
                },
              },
            },
          },
          stroke: {
            lineCap: 'round',
          },
        },
      },
    }
  },
  created() {
    for (let i = 0; i < this.browserData.length; i += 1) {
      const chartClone = JSON.parse(JSON.stringify(this.chart))
      chartClone.options.colors[0] = this.chartColor[i]
      chartClone.series[0] = this.chartSeries[i]
      this.chartData.push(chartClone)
    }
  },
}
</script>
