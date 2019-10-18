<template>
<!-- Header -->
<div class="animated fadeIn">
    <header class="w3-container w3-teal w3-center" style="padding:128px 16px">
        <h1 class="w3-margin w3-jumbo">BRUBRA</h1>
        <h4>V i s u a l i z a  n d o    R e p u t a c i ó n</h4>
          <div class="w3-container w3-center">
          <loading :active.sync="visible" :can-cancel="false"></loading>
          <b-card>
            <b-input placeholder="Ingrese 'seller=ID' del vendedor deseado" v-model="search"></b-input>
            <b-button v-show="habilitar" class="w3-button w3-black w3-padding-large w3-large w3-margin-top" @click.prevent="searchit">Visualizar</b-button>
            <b-button v-show="!habilitar" class="w3-button w3-black w3-padding-large w3-large w3-margin-top" @click.prevent="nuevo">Nuevo</b-button>
          </b-card>
          </div>
    </header>

    <b-modal ref="my-modal" hide-footer title="Error">
      <div class="d-block text-center">
        <h3>Ingrese seller=ID</h3>
      </div>
      <b-button class="mt-3" variant="outline-danger" block @click="hideModal">Cerrar</b-button>
    </b-modal>

    <b-modal ref="my-warning-modal" hide-footer title="Warning">
      <div class="d-block text-center">
        <h3>Por favor, vuelva a intentarlo</h3>
      </div>
      <b-button class="mt-3" variant="outline-warning" block @click="hideWarningModal">Cerrar</b-button>
    </b-modal>

    <div v-show="able" class="w3-row-padding w3-padding-64 w3-container">
     <div class="w3-content">
         <div class="w3-threequarter">
             <h1>{{vendedor}}</h1>
             <p class="w3-text-grey w3-justify">El vendedor {{vendedor}} fue analizado por última vez el día: {{creado}}
                si quieres actualizar su información presiona el botón Eliminar y luego, vuelve a analizar su código.
            </p>
            <b-button class="mt-3"  variant="outline-danger" @click="update">Eliminar</b-button>
         </div>
     </div>
   </div>

    <div v-show="able" class="w3-row-padding w3-light-grey w3-padding-64 w3-container">
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

    <div v-show="able" class="w3-row-padding w3-padding-64 w3-container">
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

      <div v-show="able" class="w3-row-padding w3-light-grey w3-padding-64 w3-container">
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

     <div v-show="able" class="w3-row-padding w3-padding-64 w3-container">
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
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'

    export default {
      components: {
          visualizer: Visualizer,
          visualizerb: Chart,
          doughnut: DoughnutChart,
          wordcloud: WordCloud,
          wordnetwork: WordNetwork,
          Loading,
      },
      data() {
          return {
              search: null,
              contacts: [], //para line CHART
              frequent: [], //para word cloud
              puntajes: [], //para barchart y doughnut
              todo: [],
              able:false,
              visible: false,
              habilitar: true,
              vendedor: null,
              creado: null,
          }
      },
      methods : {
        hideModal() {
          this.$refs['my-modal'].hide()
       },

       hideWarningModal() {
         this.$refs['my-warning-modal'].hide()
       },

        nuevo(){
            this.contacts=[];
            this.frequent=[];
            this.puntajes=[];
            this.todo=[];           //toda info
            this.able=false;        //para los graficos
            this.visible=false;     //rueda espera
            this.habilitar=true;    //boton visualizar/nuevo
            this.search=null;       //url
            this.vendedor=null;
            this.creado=null;
        },

      async searchit () {
           if (this.search==null) {
             this.$refs['my-modal'].show(); //modal de que no ingreso amazon seller ok
           }
           else {
            this.visible = true;             //ruedita de espera
          await axios.get('/api/scraping/'+this.search) //busco si existe el nombre
             .then(response => {
               console.log(response.data);
              if(response.data){ //si existe (!=null) entonces obtengo los datos de la respuesta
            /*     this.todo= response.data;   //toda la info
                 this.todo.forEach(element => {
                    this.contacts=element.contacts;
                    this.frequent=element.frequent;
                    this.puntajes=element.puntajes;
                  });
                  */

                  this.contacts= response.data[0];
                  this.frequent= response.data[1];
                  this.puntajes= response.data[2];
                  this.vendedor= response.data[3][0].vendedor;
                  this.creado= response.data[4][0].created_at;

                  this.visible=false;
                  this.able=true;
                  this.habilitar=false;
              }
              else{ //no existe (==null) entonces hago example y lo guardo
                console.log("no existe");
                axios.get('api/scraping/url/'+this.search)
                 .then(response => {
                   console.log(response.data);
                  this.todo= response.data;   //toda la info

                  let con; let freq; let pun; let ven;
                  this.todo.forEach(element => {
                     con=element.contacts;
                     freq=element.frequent;
                     pun=element.puntajes;
                     ven=element.vendedor;
                   });

                     const page={
                        contacts: con,
                        frequent: freq,
                        puntajes: pun,
                        vendedor: ven,
                     }

              axios.post('api/scraping/url/'+this.search, page).
                 then(response => {
                   console.log("se creo");
                   axios.get('/api/scraping/'+this.search) //busco si existe el nombre
                      .then(response => {
                        this.contacts= response.data[0];
                        this.frequent= response.data[1];
                        this.puntajes= response.data[2];
                        this.vendedor= response.data[3][0].vendedor;
                        this.creado= response.data[4][0].created_at;

                        this.visible=false;
                        this.able=true;
                        this.habilitar=false;
                    })
                 });
               }).catch((error) => {
                  console.log(error);
                  this.visible = false;                   //saca la ruedita de espera
                  this.$refs['my-warning-modal'].show();  //modal de ERR CONNECTION RESET
               });
             }

             })
             .catch((error) => {
                console.log(error);
                this.visible = false;                   //saca la ruedita de espera
                this.$refs['my-warning-modal'].show();  //modal de ERR CONNECTION RESET
             });
           }
      },

      update(){
        axios.delete('api/scraping/'+this.search).
          then(response => {
              console.log("se elimino");
              this.nuevo();
              window.location.reload();
           })
      }

    }

  }
</script>
