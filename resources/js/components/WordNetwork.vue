<template>
  <d3-network :net-nodes="nodes" :net-links="links" :options="options">
  </d3-network>
</template>

<style scoped src="vue-d3-network/dist/vue-d3-network.css"></style>

<script>
import D3Network from 'vue-d3-network'

export default {
  components: {
    D3Network,
  },
  props:['data'],
  data () {
    return {
        nodes: [],
        links: [],
        options: {
            force: 3000,
            nodeSize: 50,
            nodeLabels: true,
            linkLabels:true,
            canvas: false,
            fontSize: 20,
        }
    }
  },
  mounted() {
    this.fillData();
  },
  methods: {
    async fillData(){
      let i=1;
      if(this.data) {
         this.data.map(cont => {
           cont.frequent.forEach(element => {
            if(element.rating>3){
                this.nodes.push({id: i, name: element.word, _color: '#66b59a'});
              }
            if(element.rating==3){
               this.nodes.push({id: i, name: element.word, _color: '#4f90cc'});
            }
            if(element.rating<3){
                this.nodes.push({id: i, name: element.word, _color: '#ff7373'});
           }
         i=i+1;
         });
       })
      }

      this.data.map(cont => {
      cont.frequent.forEach(element => {
       element.edges.forEach(edge => {
         let idOrigen, idDestino;

         this.nodes.forEach(node  =>{
           if(node.name==(element.word)){
             idOrigen=node.id;
           }
           if(node.name==(edge))
            idDestino=node.id;
         })

         if((element.edges.filter(i => i === edge).length)==1)
            this.links.push({sid: idOrigen, tid: idDestino, _color:'black', _svgAttrs:{
           'stroke-width': 1,
            }});

        if((element.edges.filter(i => i === edge).length)>=2 &&(element.edges.filter(i => i === edge).length)<5  )
           this.links.push({sid: idOrigen, tid: idDestino, _color:'black', _svgAttrs:{
          'stroke-width': 2,
           }});

         if((element.edges.filter(i => i === edge).length)>=5)
             this.links.push({sid: idOrigen, tid: idDestino, _color:'black', _svgAttrs:{
           'stroke-width':3,
            }});

         if((element.edges.filter(i => i === edge).length)>=10)
         this.links.push({sid: idOrigen, tid: idDestino, _color:'black', _svgAttrs:{
           'stroke-width':5,
            }});

            if((element.edges.filter(i => i === edge).length)>=20)
            this.links.push({sid: idOrigen, tid: idDestino, _color:'black', _svgAttrs:{
              'stroke-width':9,
               }});

       })
     })
    })
    },
  },
}

  </script>
