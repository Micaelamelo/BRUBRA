<template>
  <div class="small">
    <line-chart :chart-data="datacollection" :options="this.options"></line-chart>
<!--    <button @click="fillData()">Randomize</button> -->
  </div>
</template>

<script>
  import LineChart from './LineChart.js'

  export default {
    components: {
      LineChart
    },
    props:['data'],
    data () {
      return {
        datacollection: null,
        Ratings: [],
        options:{
                  scales: {
                      xAxes: [{
                          display: true,
                          scrollbar: {
                               enabled: true
                           },
                          scaleLabel: {
                              display: true,
                          },gridLines: {
                        		display: true
                        	}
                      }],
                      yAxes: [{
                          display: true,
                          ticks: {
                            stepSize: 0.5,
                            min: 0,
                            max: 5,
                         },
                          gridLines: {
                        		display: true
                        	}
                      }]
                  },
      }
    }
  },
    mounted () {
      this.fillData();
    },
    methods: {
      fillData () {
        let Years = new Array();

        if(this.data) {
               this.data.forEach(element => {
               Years.push(element.fecha);
               this.Ratings.push(element.rating);
               });
        }

        this.datacollection = {
          labels: Years,
          datasets: [
            {
              label: 'Promedio de ratings recibidos por mes',
              backgroundColor: '#f87979',
              data: this.Ratings,
              //fill: false,
              lineTension: 0,
            },
          ],
         }
       }
     }
   }
</script>
