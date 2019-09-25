<template>
  <div class="small">
    <bar-chart :chart-data="datacollection"></bar-chart>
    <doughnut-chart :chart-data="datacollection_relative"></doughnut-chart>
  <!--  <button @click="fillData()">Randomize</button> -->
  </div>
</template>

<script>
  import BarChart from './BarChart.js'
  import DoughnutChart from './DoughnutChart.js'

  export default {
    components: {
      BarChart,
      DoughnutChart
    },
    props:['data'],
    data () {
      return {
        datacollection: null,
        datacollection_relative:null,
      }
    },
    mounted () {
      this.fillData(),
      this.fillData_relative()
    },
    methods: {
      fillData () {
        let positivo=0;
        let negativo=0;
        let neutro=0;

        if(this.data) {
           this.data.forEach(element => {
              positivo=element.positivos;
              negativo=element.negativos;
              neutro=element.neutros;
           });
        }

        this.datacollection = {
          labels: ['Positivos', 'Neutros', 'Negativos'],
          datasets: [
            {
              label: 'Puntajes recibidos absolutos',
              backgroundColor: ['#96E1AE', '#79ACD2','#f87979'],
              data: [positivo, neutro, negativo],
            },
          ]
        }
      },

      fillData_relative(){
        let positivo=0;
        let negativo=0;
        let neutro=0;

        if(this.data) {
           this.data.forEach(element => {
              positivo=element.positivos;
              negativo=element.negativos;
              neutro=element.neutros;
           });
        }

        let total= positivo+negativo+neutro;

        positivo= Math.round((positivo*100)/total);
        negativo= Math.round((negativo*100)/total);
        neutro= Math.round((neutro*100)/total);

        this.datacollection_relative = {
          labels: ['Positivos', 'Neutros', 'Negativos'],
          datasets: [
            {
              label: 'Puntajes recibidos relativos',
              backgroundColor: ['#96E1AE', '#79ACD2','#f87979'],
              data: [positivo, neutro, negativo],
            },
          ]
        }
      },

    }
  }
</script>

<style>
  .small {
    max-width: 600px;
    margin:  150px auto;
  }
</style>
