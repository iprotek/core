    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="X-CSRF-TOKEN" value="<?=csrf_token()?>" />

    <title>{{ config('app.name', ' ') }} - @yield("site-title")</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="/iprotek/css/icons.css?v=1.2">
    <link rel="stylesheet" href="/iprotek/css/ribbon.css">
    <?php
      //Customise icon
      $logoInfo = \iProtek\Core\Helpers\AppVarHelper::get(["business_logo_url","business_logo_type"]);
      $logo_url = $logoInfo['business_logo_url'] ?: '/images/logo.png';
      $logo_type = $logoInfo['business_logo_type'] ?: 'image/png';
      if($logo_url && $logo_type){
        ?>
        <link rel="icon" type="{{$logo_type}}" href="{{$logo_url}}">
        <?php
      }
    ?>

    <!-- ADMIN LTE -->
    <!-- Google Font: Source Sans Pro -->
    <!--<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">-->
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
    <!-- Theme style -->
    <link rel="stylesheet" href="/iprotek/design/templates/adminlte3.1.0/dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="/iprotek/design/templates/adminlte3.1.0/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="/iprotek/design/templates/adminlte3.1.0/plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="/iprotek/design/templates/adminlte3.1.0/plugins/summernote/summernote-bs4.min.css">
    <link rel="stylesheet" href="/iprotek/design/templates/adminlte3.1.0/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">

    <link rel="stylesheet" href="/iprotek/design/templates/adminlte3.1.0/plugins/fullcalendar/main.css">

    <!--XPOSE-->
    <script src="/iprotek/js/xpose/Xpose.js?version=2.0"></script>
    <script src="/iprotek/js/xpose/Xpose-Socket.js?version=2.0"></script>
    <script src="/iprotek/js/xpose/Xpose-Request.js?version=2.1"></script>
    <script src="/iprotek/design/templates/adminlte3.1.0/plugins/jquery/jquery.min.js"></script>
    <link href="{{ asset('/iprotek/css/direct-chat.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="/iprotek/css/Xpose-style.css">
    <link rel="stylesheet" href="/iprotek/css/w3school/searchinput.css">
    <link rel="stylesheet" href="/iprotek/css/redtable.css">
    <link rel="stylesheet" href="/iprotek/css/Xpose-hover.css">
    <script src="/iprotek/js/xpose/Xpose-Events.js"></script> 

    <!-- PUSHER NOTIFICATIONS && CHATTING PUSHER-->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

    <!-- datatable -->
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  
    <link rel="stylesheet" href="/iprotek/css/w3school/preview-image.css">

    @if(config('iprotek.google_map_key'))
      <script src="https://maps.googleapis.com/maps/api/js?key={{config('iprotek.google_map_key')}}&libraries=places,marker,geometry"></script>
      <style>
        .gm-style-iw-chr{
          height:0px;
        }
        
        @keyframes bounce {
          0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
          40% {transform: translateY(-10px);}
          60% {transform: translateY(-5px);}
        }
        .bounce {
          display: inline-block;
          animation: bounce 5s infinite;
        }
        
        @keyframes shake {
          0% { transform: rotate(0deg); }
          25% { transform: rotate(10deg); }
          50% { transform: rotate(-10deg); }
          75% { transform: rotate(10deg); }
          100% { transform: rotate(0deg); }
        }
        .shake {
          display: inline-block;
          animation: shake 0.5s infinite;
        }
        
      </style>
      
  @endif
    
    <style>
      
        @if( config('iprotek.is_demo') )
          .nav-item a i {
            color:red;
          }
        @endif

        .sidebar-collapse .brand-link .brand-text b{
            display:none;
        }
        /** STYLE FOR LOADING */
        .bd-example-modal-lg .modal-dialog{
            display: table;
            position: relative;
            margin: 0 auto;
            top: calc(50% - 24px);
        }
        
        .bd-example-modal-lg .modal-dialog .modal-content{
            background-color: transparent;
            border: none;
        }
    </style> 
    
    <style>
      tr.bg-gradient-secondary, td.bg-gradient-secondary{
        background:#c5d0db linear-gradient(180deg,#9ca8b3,#9aa7b3) repeat-x !important;
        color:black;
      }
      tr.bg-yellow, td.bg-yellow{
        background-color:#eed690 !important;
        color:black;
      }
      tr.bg-orange, td.bg-orange{
        background-color:#f2ad73 !important;
        color:black;
      }
      tr.bg-success, td.bg-success{
        background-color:#50cc6c !important;
        color:black;
      }
      tr.bg-primary, td.bg-primary{
        background-color:#5297e1 !important;
        color:black;
      }
      tr.bg-red, td.bg-red{
        background-color:#e6626e !important;
        color:black;
      }
      </style>
      <link rel="stylesheet" href="/iprotek/design/templates/adminlte3.1.0/plugins/select2/css/select2.min.css">
      <link rel="stylesheet" href="/iprotek/design/templates/adminlte3.1.0/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
      <style>
        .select2-selection--single{
          height: 38px;
          width: 100%;
        }
        </style>
    @yield('head')