@extends('layouts.app')

@section('content') 
    <div id="main-content">
        <sub-setup-view></sub-setup-view>
    </div>
@endsection

@section('foot')
    <script src="/design/templates/adminlte3.1.0/plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="/js/sub-accounts-default.js"></script> 
    <!--<script src="/js/app.js"></script>-->
@endsection