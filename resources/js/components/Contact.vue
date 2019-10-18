<template>
    <div class="w3-row-padding w3-padding-64 w3-container animated fadeIn">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="text-center header-page">Vendedores analizados</h1>
                </div>

          <!--      <div class="col-md-6">
                  <li v-for="page in pages">
                     {{page.vendedor}} - {{ page.name }} Fue analizado por última vez {{page.created_at}}
                  </li> -->
                  <div style="overflow: scroll">

                  <table id="firstTable">
                  <thead>
                    <tr>
                      <th>Vendedor</th>
                      <th>ID Seller</th>
                      <th>Analizado por última vez</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="page in pages">
                      <td>{{page.vendedor}}</td>
                      <td>{{page.name}}</td>
                      <td>{{page.created_at}}</td>
                    </tr>
                  </tbody>
                </table>
              </div>

            </div>

            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import  "./css/contact.css";

  export default {
    data(){
      return {
        pages: [],
      }
    },
    mounted:function(){
        axios.get('/api/pages').
        then(response =>{
          this.pages=response.data;
        })

    }
  }
</script>
