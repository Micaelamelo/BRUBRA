<template>
  <div>
    <bar-chart :chart-data="datacollection"></bar-chart>

    <p class="w3-text-grey w3-justify"> Total: {{total}}</p>
  </div>
</template>
<script>
  import BarChart from './BarChart.js'
  import  "./css/chart.css";

  export default {
    components: {
      BarChart,
    },
    props:['data'],
    data () {
      return {
        total: 0,
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

    /*    if(this.data) {
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

        this.total=positivo+negativo+neutro;

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
