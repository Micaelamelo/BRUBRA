@extends('welcome')

@section('content')
<template v-if="menu==0">
   <home-page></home-page>
</template>
<template v-if="menu==2">
   <contact-page></contact-page>
</template>
<template v-if="menu==1">
    <about-page></about-page>
</template>
@endsection
