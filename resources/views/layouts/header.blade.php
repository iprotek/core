<?php
use iProtek\Core\Helpers\AppVarHelper;

?>

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if(!isset($title))
      <title> 
          {{ AppVarHelper::get('business_name',  config('app.name', 'Laravel') )  }}
      </title>
      <meta name="author" content="Jose de Eagle">
    @endif


    <?php
      //Customise icon
      $logoInfo = AppVarHelper::get(["business_logo_url","business_logo_type"]);
      $logo_url = $logoInfo['business_logo_url'] ?: '/images/logo.png';
      $logo_type = $logoInfo['business_logo_type'] ?: 'image/png';
      if($logo_url && $logo_type){
        ?>
        <link rel="icon" type="{{$logo_type}}" href="{{$logo_url}}">
        <?php
      }
    ?>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <!-- Scripts -->

    <!-- Fonts -->
    <!-- ADMIN LTE -->
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/iprotek/design/templates/adminlte3.1.0/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="/iprotek/design/templates/adminlte3.1.0/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/iprotek/design/templates/adminlte3.1.0/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="/iprotek/design/templates/adminlte3.1.0/plugins/jqvmap/jqvmap.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="/iprotek/design/templates/adminlte3.1.0/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="/iprotek/design/templates/adminlte3.1.0/plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="/iprotek/design/templates/adminlte3.1.0/plugins/summernote/summernote-bs4.min.css">
    <link rel="stylesheet" href="/iprotek/design/templates/adminlte3.1.0/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css"><!-- Summernote -->

    <link rel="stylesheet" href="/iprotek/design/templates/adminlte3.1.0/plugins/fullcalendar/main.css">

    <!--XPOSE-->
    <script src="/iprotek/js/xpose/Xpose.js?version=2.0"></script>
    <script src="/iprotek/js/xpose/Xpose-Request.js?version=2.1"></script>
    <script src="/iprotek/design/templates/adminlte3.1.0/plugins/jquery/jquery.min.js"></script>
    <link rel="stylesheet" href="/iprotek/css/Xpose-style.css"> 
    <link href="/iprotek/css/direct-chat.css" rel="stylesheet"> 
    <link rel="stylesheet" href="/iprotek/css/Xpose-style.css">
    <link rel="stylesheet" href="/iprotek/css/w3school/searchinput.css">
    <link rel="stylesheet" href="/iprotek/css/redtable.css">
    <link rel="stylesheet" href="/iprotek/css/Xpose-hover.css">
    <script src="/iprotek/js/xpose/Xpose-Events.js"></script>
    
    <link href="{{ asset('/iprotek/css/star-rating.css') }}" rel="stylesheet">
    
    <link rel="stylesheet" href="/iprotek/design/templates/adminlte3.1.0/plugins/select2/css/select2.min.css"> 
    <!-- CHATTING PUSHER-->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <style>
      .sidebar-collapse .brand-link .brand-text b{
          display:none;
      }
    </style>
    @yield('head')
</head>
<body>