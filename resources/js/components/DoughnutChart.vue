<template>
    <doughnut-chart :chart-data="datacollection_relative"></doughnut-chart>
</template>

<script>
  import DoughnutChart from './DoughnutChart.js'
  import  "./css/doughnut.css";

  export default {
    components: {
      DoughnutChart
    },
    props:['data'],
    data () {
      return {
        datacollection_relative:null,
      }
    },
    mounted () {
      this.fillData_relative()
    },
    methods: {
      fillData_relative(){
        let positivo=0;
        let negativo=0;
        let neutro=0;
/*
        if(this.data) {
           this.data.forEach(element => {
              positivo=element.positivos;
              negativo=element.negativos;
              neutro=element.neutros;
           });
        }
*/
      this.data.map(puntaje=>{
        positivo= puntaje.puntajes[0].positivos;
        negativo= puntaje.puntajes[0].negativos;
        neutro= puntaje.puntajes[0].neutros;
        })

        let total= positivo+negativo+neutro;

        positivo= Math.round((positivo*100)/total);
        negativo= Math.round((negativo*100)/total);
        neutro= Math.round((neutro*100)/total);

        this.datacollection_relative = {
          labels: ['% Positivos', '% Neutros', '% Negativos'],
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
