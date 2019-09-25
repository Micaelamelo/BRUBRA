<template>
<!-- Header -->
<div class="animated fadeIn">
    <header class="w3-container w3-red w3-center" style="padding:128px 16px">
        <h1 class="w3-margin w3-jumbo">BRUBRA</h1>
        <h4>Visualizando reputación</h4>
        <p class="w3-xlarge"></p>

          <b-card class="w3-margin w3-jumbo">
            <b-input id="inline-form-input-name" class="mb-2 mr-sm-2 mb-sm-0" placeholder="Ingrese ID de seller de Amazon" v-model="search"></b-input>
            <button class="w3-button w3-black w3-padding-large w3-large w3-margin-top" @click="searchit">Visualizar</button>
          </b-card>
    </header>


      <wordcloud v-if="able" v-bind:data="this.frequent"></wordcloud>
      <wordnetwork v-if="able" v-bind:data="this.frequent"></wordnetwork>

      <visualizer v-bind:data="this.contacts"></visualizer>
      <visualizerb v-if="able" v-bind:data="this.puntajes"></visualizerb>

</div>
</template>


<script>
import axios from 'axios'
import Visualizer from './Visualizer.vue'
import Chart from './Chart.vue'
import WordCloud from './WordCloud.vue'
import WordNetwork from './WordNetwork.vue'
//TENGO QUE SACAR UN COMPONENTE DE ACA
    export default {
      components: {
          visualizer: Visualizer,
          visualizerb: Chart,
          wordcloud: WordCloud,
          wordnetwork: WordNetwork,
      },
      data() {
          return {
              search: '',
              contacts: [], //para line CHART
              frequent: [], //para word cloud
              puntajes: [], //para barchart y doughnut
              able:false,
          }
      },
      methods : {
           async searchit () {
              console.log("al menos entra aca");
               axios.get('/api/scraping/'+this.search)
               .then(response => {
                 console.log("entra");  //aca consigo toda la info
                 response.data.forEach(element => {
                    this.contacts=element.contacts;
                    this.frequent=element.frequent;
                    this.puntajes=element.puntajes;
                  }
                )
                    this.able=true;
               })
          }
      }
    }
</script>
