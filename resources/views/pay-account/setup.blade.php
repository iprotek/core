@extends('iprotek_core::layouts.app')

@section('content')
    <?php
        $email = isset($email) ? $email : '';//auth()->user()->email;
    ?>
    <div id="main-content" class="contianer">
        <div class="row mx-4 justify-content-center">
            <div class="col-sm-5 text-dark mt-5">  
                    @include('iprotek_core::pay-account.login.design')
            </div>
        </div>
    </div>
@endsection

@section('foot')
    @include('iprotek_core::layouts.copyright-footer')
    <script src="/iprotek/design/templates/adminlte3.1.0/plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="/iprotek/js/pay-forgot-password.js"></script>
    <!--<script src="/js/app.js"></script>-->
@endsection