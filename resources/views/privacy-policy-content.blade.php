<?php
    $show_background_image = 0;
    $cart_url = "/cart";
?>
@section('bg_image_style', "background-position: center; background-repeat: no-repeat;background-attachment: fixed;background-size: cover;")
@section('bg_color_style', "background-color:#0000000d; color:white;")
@section('nav_bar_color', "navbar-dark shadow-lg")

@extends('iprotek_core::layouts.app')

@section('head')   
    <link rel="stylesheet" href="/iprotek/design/templates/adminlte3.1.0/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="/iprotek/css/app-list.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <style>
        #calendar .table-bordered td, #calendar .table-bordered th {
            border: 1px solid #dee2e6;
        }
        .fc-daygrid-day-number{
            text-decoration: none;
        }
        .fc-color-picker {
            list-style: none;
            margin: 0;
            padding: 0;
            display:inherit;
        }
        .fc-color-picker i{
            padding: 2px;
            font-size:1.5em;
        }
        @keyframes white-glow {
            0%{
                filter: drop-shadow(0px 0px 2px #ffffff) drop-shadow(0px 0px 2px #ffffff);
            }
            50%{
                filter: drop-shadow(0px 0px 3px #ffffff) drop-shadow(0px 0px 3px #ffffff);
            }
            100%{
                filter: drop-shadow(0px 0px 2px #ffffff) drop-shadow(0px 0px 2px #ffffff);
            }
        }
        .image-glow {
            animation-name: white-glow;
            animation-duration: 1.5s;
            animation-iteration-count: infinite;
            animation-timing-function: linear;
        }

        @keyframes bottom-box-glow {
            0% {
                box-shadow: 0px 8px 2px -5px #fff;
            }
            50% {
                box-shadow: 0px 8px 5px -6px #fff;
            }
            100% {
                box-shadow: 0px 8px 3px -5px #fff;
            }
            
        }

        .active-menu-highlight-bottom {
            animation-name: bottom-box-glow; 
            animation-duration: 1.5s;
            animation-iteration-count: infinite;
            animation-timing-function: linear;
        }
        .col-sm-2-4 {
            flex-basis: 20%;
            max-width: 20%;
        }
        @media (max-width: 576px){
            .col-sm-2-4{
                flex-basis: 100%;
                max-width: 100%;
            }
        }
        @font-face {
            font-family: 'DancingScript';
            src: url('/fonts/dancing-script/DancingScript-Regular.ttf') format('truetype');
            font-weight: normal;
        }
        @font-face {
            font-family: 'DancingScript';
            src: url('/fonts/dancing-script/DancingScript-Bold.ttf') format('truetype');
            font-weight: bold;
        }
        .dancing-script {
            font-family: 'DancingScript', sans-serif;
            font-weight: normal;
        }
        .dancing-script-bold {
            font-family: 'DancingScript', sans-serif;
            font-weight: bold;
        }
        @font-face {
            font-family: 'StampFont';
            src: url('/fonts/stamp/DirtyEgo-Dn2R.ttf') format('truetype');
            font-weight: bold;
        }
        .stamp-font {
            font-family: 'StampFont', sans-serif;
            font-weight: bold;
        }
        .google-fonts{ 
            font-style: normal;
            line-height: 18px;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", "Roboto", "Helvetica", "Arial", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
        }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDEWsoInCSktMHcm0ioXECJCLU4clAjV2o&libraries=places"></script>
    
    <!-- ANIMATION -->
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="card mt-3">
                    <div class="card-body text-black">
                     @include('layout.content.privacy-policy')
                    </div>
                </div>
            </div>
        </div>
    </div> 
@endsection

@section('foot')
    <script src="/js/app.js?v=1.0.4"></script> 
    
@endsection