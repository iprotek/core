@extends('iprotek_core::layouts.app')

@section('content') 
    <div id="main-content">
        <sub-setup-view></sub-setup-view>
    </div>
@endsection

@section('foot')
    <script src="/iprotek/design/templates/adminlte3.1.0/plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="/iprotek/js/sub-accounts-default.js"></script> 
    <!--<script src="/js/app.js"></script>-->
@endsection