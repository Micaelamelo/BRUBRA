<template>
    <bar-chart :chart-data="datacollection"></bar-chart>
</template>

<script>
  import BarChart from './BarChart.js'

  export default {
    components: {
      BarChart,
    },
    props:['data'],
    data () {
      return {
        datacollection: null
      }
    },
    mounted () {
      this.fillData();
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
    }
  }
</script>

<style>
  .small {
    max-width: 600px;
    margin:  150px auto;
  }
</style>
