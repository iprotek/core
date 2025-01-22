@extends('iprotek_core::layout.pages.view-dashboard')

@section('logout-link','/logout')
@section('site-title', 'MY DETAILS')
@section('head')
     
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection
@section('breadcrumb')
    <!--
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active">Widgets</li>
    -->
@endsection
@section('content') 
  <div id="main-content">
    <?php
      $user_admin_id = auth('admin')->user()->id;
    ?>
     <my-details-container :group_id="{{$group_id}}" :user_admin_id="{{$user_admin_id}}" ></my-details-container>
  </div>
   
@endsection

@section('foot') 
  <script>
    //ActivateMenu(['menu-dashboard']);
  </script> 
  
    <script src="/iprotek/js/manage/my-details.js?v=1"> </script>
  
  
@endsection

