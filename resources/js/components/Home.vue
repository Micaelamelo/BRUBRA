<template>
<!-- Header -->
<div class="animated fadeIn">
    <header class="w3-container w3-teal w3-center" style="padding:128px 16px">
        <h1 class="w3-margin w3-jumbo">BRUBRA</h1>
        <h4>V i s u a l i z a  n d o    R e p u t a c i ó n</h4>
          <div class="w3-container w3-center"  style="margin: 25px 300px">
          <b-card>
            <b-input placeholder="Ingrese 'seller=ID' del vendedor deseado" v-model="search"></b-input>
            <b-button class="w3-button w3-black w3-padding-large w3-large w3-margin-top" @click="searchit">Visualizar</b-button>
          </b-card>

          </div>
    </header>

    <div class="w3-row-padding w3-light-grey w3-padding-64 w3-container">
     <div class="w3-panel">
       <div class="w3-third">
          <visualizerb  v-if="able" v-bind:data="this.puntajes"></visualizerb>
       </div>

         <div class="w3-third w3-center">
             <h1>Datos univariantes</h1>
             <p class="w3-text-grey w3-justify">A la izquierda se encuentra un gráfico de barras que representa
              los datos absolutos del vendedor, la cantidad efectiva de cada tipo de reseña recibida.
              Mientras que a la derecha se encuentra un gráfico circular que muestra los datos relativos,
              es decir, el porcentaje de cada puntaje recibido.
            </p>
         </div>

         <div class="w3-third">
            <doughnut  v-if="able" v-bind:data="this.puntajes"></doughnut>
         </div>
     </div>
   </div>

    <div class="w3-row-padding w3-padding-64 w3-container">
        <div class="w3-content">
          <div class="w3-half">
              <h1>Datos a lo largo del tiempo</h1>
              <p class="w3-text-grey w3-justify"> Aquí te mostramos un gráfico de línea que muestra el progreso de las calificaciones agregadas al vendedor a lo largo del tiempo.
                Los valores que aparecen en el eje X son los meses en los cuales el vendedor recibió reseñas. Los valores del eje Y representan el promedio de
                las calificaciones recibidas durante ese mes.
              </p>
          </div>
          <div class="w3-half">
            <visualizer v-if="able" v-bind:data="this.contacts"></visualizer>
          </div>
        </div>
      </div>

      <div class="w3-row-padding w3-light-grey w3-padding-64 w3-container">
       <div class="w3-content">
         <div class="w3-center">
         <h1>Palabras frecuentes en reseñas</h1>
         <p class="w3-text-grey w3-center">
           Esta WordCloud está compuesta por las palabras más utilizadas por los clientes a la hora de describir su experiencia con el vendedor.
           Cuanto más grande es la palabra, mayor fue utilizada.
           Los colores representan el promedio de las calificaciones de las reseñas donde fueron utilizadas:
           si es rojo significa que el promedio de las calificaciones es menor a 3, si es azul significa que su promedio es igual 3
           y si es verde su promedio es mayor a 3.
         </p>
         </div>
         <div>
           <wordcloud  v-if="able" v-bind:data="this.frequent"></wordcloud>
         </div>
       </div>
     </div>

     <div class="w3-row-padding w3-padding-64 w3-container">
      <div class="w3-content">
        <div class="w3-center">
        <h1>Red de palabras frecuentes</h1>
        </div>
        <p class="w3-text-grey w3-center">
          Esta red revela las relaciones semánticas de la nube de palabras. Es una combinación de la nube anterior y un grafo ponderado.
          Los nodos representan las palabras más utilizadas en las reseñas y, un arco entre dos nodos indica que esas dos palabras han sido utilizadas en la misma reseña.
          Cuanto más frecuentes hayan sido dos palabras en las mismas reseñas, mayor será el grosor del arco que los une.
        </p>
        <div>
          <wordnetwork v-if="able" v-bind:data="this.frequent"></wordnetwork>
        </div>
      </div>
    </div>

    <div class="w3-container w3-black w3-center w3-opacity w3-padding-64">
        <h1 class="w3-margin w3-xlarge">BRUBRA sólo analiza perfiles de Amazon.es</h1>
    </div>

</div>
</template>


<script>
import axios from 'axios'
import Visualizer from './Visualizer.vue'
import Chart from './Chart.vue'
import WordCloud from './WordCloud.vue'
import WordNetwork from './WordNetwork.vue'
import DoughnutChart from './DoughnutChart.vue'

    export default {
      components: {
          visualizer: Visualizer,
          visualizerb: Chart,
          doughnut: DoughnutChart,
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
