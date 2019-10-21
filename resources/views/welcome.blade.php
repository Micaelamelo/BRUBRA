<!DOCTYPE html>
<html lang="es">
<title>BRUBRA</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

<link href="https://fonts.googleapis.com/css?family=Crimson+Text&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Roboto+Slab&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">
<link href="{{'css/welcome.css'}}" rel="stylesheet">
<link href="{{secure_asset('css/app.css')}}" rel="stylesheet">
<body>
<div id="app" >
    <!-- Navbar -->
    <div class="w3-top">
    <div class="w3-bar w3-teal w3-card w3-left-align w3-large">
        <a class="w3-bar-item w3-button w3-hide-medium w3-hide-large w3-right w3-padding-large w3-hover-white w3-large w3-teal" href="javascript:void(0);" onclick="myFunction()" title="Toggle Navigation Menu"><i class="fa fa-bars"></i></a>
        <a href="#" @click="menu=0" class="w3-bar-item w3-button w3-padding-large w3-hover-white" v-bind:class="{ 'w3-white': menu==0}">Home</a>
        <a href="#" @click="menu=1" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white" v-bind:class="{ 'w3-white': menu==1}">¿Cómo funciona?</a>
        <a href="#" @click="menu=2" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white" v-bind:class="{ 'w3-white': menu==2}">Historial</a>
    </div>

    <!-- Navbar on small screens -->
    <div id="navDemo" class="w3-bar-block w3-white w3-hide w3-hide-large w3-hide-medium w3-large">
        <a href="#" @click="menu=0" class="w3-bar-item w3-button w3-padding-large">Home</a>
        <a href="#" @click="menu=1" class="w3-bar-item w3-button w3-padding-large">¿Cómo funciona?</a>
        <a href="#" @click="menu=2" class="w3-bar-item w3-button w3-padding-large">Historial</a>

    </div>
    </div>
    <!--Contenido dinamico que irá cambiando sin refrescar la pagina-->
    <div>
        @yield('content')
    </div>
    <!-- Footer -->
    <footer class="w3-container w3-padding-64 w3-center w3-opacity">
    <p>By <a target="_blank">Micaela Melo</a></p>
    </footer>
</div>
<script src="js/app.js"></script>

<script>
// Used to toggle the menu on small screens when clicking on the menu button
function myFunction() {
  var x = document.getElementById("navDemo");
  if (x.className.indexOf("w3-show") == -1) {
    x.className += " w3-show";
  } else {
    x.className = x.className.replace(" w3-show", "");
  }
}
</script>
