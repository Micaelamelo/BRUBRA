<template>
  <div class="cloud">
    <vue-word-cloud :words="words" :color="([, , rating]) => rating>3 ? '#66b59a' : rating==3  ? '#4f90cc' : '#ff7373'" rotation=0 font-family="Roboto">
    </vue-word-cloud>
    <p>

    </p>
     Frecuencia:
    <template v-for="[w, f, r] in words">
        <span class="w3-text-grey w3-justify" > {{w}}({{f}}) - </span>  <slot></slot>
    </template>
  </div>
</template>

<script>
import VueWordCloud from "vuewordcloud";

export default {
  components: {
    VueWordCloud,
  },
  props:['data'],
  data() {
    return {
      toggleOn: true,
      words: [],
      palabras: [],
    };
  },
  mounted() {
    this.getWords();
  },

  methods: {
    async getWords(){

        /*if(this.data){
          this.data.forEach(element => {
              this.words.push([element.word , element.frequency, element.rating]);
          })
        }*/
        if(this.data){
          this.data.map(cont => {
            cont.frequent.forEach(element=>{
              element.rating=parseInt(element.rating);
              this.words.push([element.word , element.frequency, element.rating]);
            })
          })
        }
    }
  }

};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
@import url('https://fonts.googleapis.com/css?family=Roboto');
.cloud {
  height: 150px;
  padding: 20px 0;
  cursor: pointer;
}
</style>
