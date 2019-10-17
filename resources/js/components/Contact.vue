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
</template>

<script>
import axios from 'axios'

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

<style>
table {
  font-family: 'Roboto', sans-serif;
  width: 750px;
  border-collapse: collapse;
  border: 3px solid #44475C;
  margin: 10px 10px 0 10px;
}
table th {
  text-transform: uppercase;
  text-align: left;
  background: #257a7c;
  color: #FFF;
  padding: 8px;
  min-width: 30px;
}
table td {
  text-align: left;
  padding: 8px;
  border-right: 2px solid #7D82A8;
}
table td:last-child {
  border-right: none;
}
table tbody tr:nth-child(2n) td {
  background: #bfe7e7;
}
</style>
