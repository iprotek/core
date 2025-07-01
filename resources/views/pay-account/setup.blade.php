@section('nav_bar_color', 'navbar-dark shadow-sm')

@extends('iprotek_core::layouts.app')

@section('content')
    <?php
        $email = isset($email) ? $email : '';//auth()->user()->email;
    ?>
    <div id="main-content" class="contianer" style="min-height:85vh;">
        <div class="row mx-4 justify-content-center">
            <div class="col-sm-4 text-dark mt-5" style="min-width:350px;">  
                    @include('iprotek_core::pay-account.login.design')
            </div>
        </div>
        <!-- ADD SOME POP-UP LOG-IN HERE FOR ACCOUNT WEBSITE -->
         @if(config('iprotek_account.url') && config('iprotek.app_type') != 'ACCOUNT SYSTEM')
            <!-- CHECKIF IF THE ACCOUNT SYSTEM HAS LOGGED IN -->
                @if(auth('admin')->check())
                    <!-- IF NOT COMPATIBLE WITH THE REQUESTOR IT WILL LOGOUT THEN REFRESH -->
                    <!-- ELSE REDIRECT TO MANAGE PAGE -->
                @else
                    <!-- WAIT FOR THE LOGIN AND WAIT FOR THE POP UP TO LOGGED IN -->

                @endif
         @endif

    </div>
@endsection

@section('foot')
    @include('iprotek_core::layouts.copyright-footer')
    <script src="/iprotek/design/templates/adminlte3.1.0/plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="/iprotek/js/pay-forgot-password.js"></script>
    <!--<script src="/js/app.js"></script>-->
    <!-- POP-UP FOR LOGIN SCRIPTING ON ACCOUNT WEBSITE HERE -->
    @if(config('iprotek_account.url') && config('iprotek.app_type') != 'ACCOUNT SYSTEM')

    @endif
@endsection