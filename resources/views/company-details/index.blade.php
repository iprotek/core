@extends('iprotek_core::layout.pages.view-dashboard')

@section('logout-link','/logout')
@section('site-title', 'COMPANY DETAILS')
@section('head') 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!--
    <link rel="stylesheet" href="/css/icons.css">
-->
@endsection
@section('breadcrumb')
    <!--
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active">Widgets</li>
    -->
@endsection
@section('content') 
  <div id="main-content">
    <!--<factories-view></factories-view>--> 
    
    <company-details-view></company-details-view>

  </div> 
@endsection

@section('foot') 
  <script>
    ActivateMenu(['menu-company-details']);
  </script> 
  <script src="/iprotek/js/manage/company-details.js"> </script>
@endsection

